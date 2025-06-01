#!/bin/bash

# Script pour démarrer le conteneur Docker serveur-db
# Thierry VAIRA

# Le fichier de configuration docker-compose.yml
fichier_docker_compose=
if [ -z "$1" ]; then
    #echo "Usage: $0 <docker-compose.yml>"
    fichier_docker_compose="./docker-compose.yml"
else
    fichier_docker_compose="$1"
fi

# vérifier que la commande docker est installée
if ! command -v docker &>/dev/null; then
    echo "Docker non trouvé !"
    exit
fi

# vérifier que la commande docker-compose est installée
if ! command -v docker compose &>/dev/null; then
    echo "Docker Compose non trouvé !"
    exit
fi

# vérifier que le fichier docker-compose.yml existe
if [ ! -f "$fichier_docker_compose" ]; then
    echo "Le fichier $fichier_docker_compose n'existe pas !"
    exit
fi

# vérifier que le service mysql n'est pas en cours d'exécution
if systemctl is-active --quiet mysql; then
    echo "Le service MySQL est en cours d'exécution ! Veuillez l'arrêter avant de démarrer le conteneur."
    exit
fi

# vérifier que le conteneur serveur-db n'est pas déjà en cours d'exécution
if [ "$(docker ps -q -f name=serveur-db)" ]; then
    echo "Le conteneur serveur-db est déjà en cours d'exécution !"
    exit
fi

# Démarrer le conteneur Docker serveur-db
docker compose --file "$fichier_docker_compose" up --detach
