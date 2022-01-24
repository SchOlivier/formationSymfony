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