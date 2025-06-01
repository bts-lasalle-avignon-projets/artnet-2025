#!/bin/bash
# vérifier que la commande docker est installée
if ! command -v docker &>/dev/null; then
    echo "Docker non trouvé !"
    exit
fi
# vérifier que le fichier database/docker-compose.yml existe
if [ ! -f "sql/docker-compose.yml" ]; then
    echo "Le fichier sql/docker-compose.yml n'existe pas !"
    exit
fi
# vérifier que le conteneur mysql est en cours d'exécution
if [ ! "$(docker ps -q -f name=mysql)" ]; then
    exit
fi
docker compose --file sql/docker-compose.yml stop mysql
