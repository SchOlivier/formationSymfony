# Docker :
Depuis le répertoire du projet :
- Créer le composer : `docker-compose up -d`
- S'y connecter : `docker exec -it www_symfony bash`
- Arrêter le conteneur : `docker stop www_symfony`

# Symfony
- Créer un projet : `symfony new where-to-eat-2401 --version=lts`
- Lancer le serveur web correspondant : `symfony server:start -d --port=80`, le numéro de port est la partie droite de `www: ports: "8741:80"` dans le docker-compose.yml. La partie gauche est le numéro de port en dehors du Docker, donc l'url de mon server web est : `http://127.0.0.1:8741/`
- Créer un controller (en vue d'un MVC ou d'une API par exemple) : `symfony console make:controller --no-template`
- Créer une entité : `symfony console make:entity`, permet d'ajouter les attributs, getters, setter, et même de définir les relations (oneToMany, ...). Peut-être réutilisée sur une entité créée auparavant si on veut ajouter des attributs.
- Voir la config : `symfony console config:dump`, generalement `symfony console config:dump framework`
- Faire un serializer : `symfony console make:serialize:normalizer` -> utile quant on veut remonter uniquement certains attributs en fonction d'un contexte donné (listAll, showOne).
- [Profiler](http://localhost:8741/_profiler/) (pour voir les dernières requêtes et les erreurs)

# Validatateurs
- On peut définir dans les entités des contraintes à respecter sur les attributs avec `Symfony\Component\Validator\Constraints`. On met ça sous forme d'annotations dans les attributs, voir [la doc](https://symfony.com/doc/current/reference/constraints.html).
- Exemples dans la classe `src\Entity\User.php`
- On peut lier les contraintes ORM aux contraintes validateur automatiquement en décommentant l'auto-mapping dans `config\packages\validator.yaml`
- `symfony console make:validator` permet de faire un validateur custom, on peut aussi faire des Traits.

# Doctrine
- Il y a une gestion de versionning. On peut créer un diff entre l'existant en BDD et les entités doctrine définies avec : `symfony console make:migration`. Ca va créer un fichier PHP dans le répertoire `migrations` à la date du jour qui liste les commandes SQL à jouer pour upgrade (ou downgrade). On joue ensuite `symfony console doctrine:migrations:migrate` pour jouer tous les upgrades à la suite (s'il y en a plusieurs), dans l'ordre chronologique. Pour les downgrades, aucune idée...

- Utile : on peut jouer des commandes sql avec `symfony console dbal:run-sql 'select * from user'`



# Divers
- Requête Curl pour tester une api : `curl -X POST http://localhost:8000/users -H 'Content-Type: application/json' -d '{"firstName": "Joseph"}'`
- Intégration d'un paginateur :
```php
controller.php
<?php
$userList = new ArrayAdapter([$user1, $user2, $user3, $user4, '...']);
$pagerfanta = new Pagerfanta($userList);
$this->serializer->serialize($pagerfanta, 'json');```
```php
<?php
namespace App\Serializer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Pagerfanta\Pagerfanta;

class PagerFantaNormalizer implements ContextAwareNormalizerInterface
{
    private $normalizer;
    public function __construct(ObjectNormalizer $normalizer)
    {
        $this->normalizer = $normalizer;
    }

    /**
    * You can add / remove / update any item after the object has been transformed into
    * array but BEFORE transformed to JSON.
    * { "results": [user1, user2, user3], "totalResults": 100, "currentPage": 10, "..." }
    */
    public function normalize($pagerFanta, string $format = null, array $context = [])
    {
        $data = [];
        $items = $pagerFanta->getCurrentPageResults();
        foreach ($items as $i => $item) {
            if (is_object($item)) {
                $items[$i] = $this->normalizer->normalize($item, $format, $context);
                }
            }
        $data['results'] = $items;
        $data = array_merge($data, [
            'totalResults' => $pagerFanta->getNbResults(),
            'totalPages' => $pagerFanta->getNbPages(),
            'hasPreviousPage' => $pagerFanta->hasPreviousPage(),
            'hasNextPage' => $pagerFanta->hasNextPage(),
            'currentPage' => $pagerFanta->getCurrentPage(),
            'nbItemsPerPage' => $pagerFanta->getMaxPerPage(),
        ]);

        return $data;
    }

    public function supportsNormalization($data, string $format = null, array $context = [])
    {
        return $data instanceof Pagerfanta;
    }
}
```