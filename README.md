# <img src="images/projecteurs.png" alt="Logo ArtNet" width="10%"> Projet Artnet 2025 

---

- [Projet Artnet 2025](#projet-artnet-2025)
    - [Introduction](#introduction)
        - [Informations](#informations)
        - [Présentation](#présentation)
    - [Diagrammes](#diagrammes)
        - [Diagramme des cas d'utilisations](#diagramme-des-cas-dutilisations)
        - [Diagramme de séquence](#diagramme-de-séquence)
        - [Diagramme des blocs internes](#diagramme-des-blocs-internes)
        - [Diagramme des classes](#diagramme-des-classes)
        - [Base de donnée](#base-de-donnée)
    - [Utilisations](#utilisation)
        - [Installation et configuration broker](#installation-et-configuration-broker)
    - [Itérations](#itérations)
        - [Itération 1](#itération-1)
        - [Itération 2](#itération-2)
        - [Itération 3](#itération-3)
    - [Protocole](#protocole)
    - [Changelog](#changelog)
    - [Défauts constatés non corrigés](#défauts-constatés-non-corrigés)
    - [Equipe de développement](#equipe-développement)

---

## Introduction

### Informations

- Nom du projet : ARTNET
- Date de début : 25 Février 2025
- Version : --

### Présentation

De nos jours, les DJ et animateurs de soirée utilisent couramment un ordinateur portable à la fois pour diffuser la musique et pour gérer les différents jeux de lumières pour éclairer et animer la piste de spectacle (danse, podium, scène, ...).
Nous souhaitons mettre en avant les possibilités offertes par notre système d’éclairage de scènes automatisé.

Il s’agit donc d’intégrer dans des univers DMX composés d’ éléments tel que :
- des scanners
- des PARS
- des lyres
- des Laser
- des spots spécifiques

De réaliser un système de supervision et de commande de l’ensemble de ces appareils compatibles avec un bus standard du spectacle (DMX 512).

Le système est composé de :

- Un module **Serveur** qui est le centre du système. Il héberge l’IHM d’administration technique et contient la base de données. 
Il est chargé de la communication avec les modules “Wifi-DMX” et l’application mobile de contrôle. 
Il pourra éventuellement contrôler un modem DMX filaire mais aussi stocker et gérer des constitutions de scènes pré enregistrées.

- Un module **Application de commande** qui sera l’interface utilisateur du systèmesur appareil mobile. 
Il permet au technicien de paramétrer et enregistrer les différents équipements DMX et les canaux associés à partir de leur adresse DMX, modifier la valeur des canaux associés aux équipements manuellement et éventuellement de programmer des situations pré enregistrés contenant les valeurs de différents canaux pour effectuer des changements complexes en un clic.


## Utilisation

### Installation et configuration broker
**Sur linux :**
- sudo apt install mosquitto    &emsp;| installe le broker
- sudo systemctl start/stop/status mosquitto    &emsp;| démarre, stop ou visualise l'état

## Itérations

### Itération 1

*Du 29 janvier au 28 mars*

- **Dialogue application/serveur :** Etablir une connexion etre l'application mobile et le serveur
- **Etablir la connexion avec les modules :** Associer et établir la communication avec les modules DMX
- **Activer/Désactiver un module :** Permettre l’activation ou la désactivation d’un module DMX
- **Configurer les équipements DMX et enregistrer les configurations :** : Définir les paramètres des équipements DMX et sauvegarder leurs configurations.

### Itération 2

*Du 29 mars au 23 mai*

- **Gérer la configuration technique du système via une IHM :** Fournir une interface utilsateur d'administration
- **Sauvegarder, exporter, importer l'état de configuration du système** : Permettre la gestion avancée des configurations enregistrées.
- **Créer et gérer des pré-enregistrement de valeurs de canaux :** Mise en place de scène pré-enregistrées

### Itération 3 

*Du 24 mai au 30 mai*

- **Mettre en forme l'affichage :** Mise en forme responsive à 100% 
- **Prendre en charge et communiquer avec un modem DMX Filaire** : Ajouter la compatibilité avec un modem DMX filaire


## Diagrammes

### Diagramme des cas d'utilisations

Diagramme du serveur :

![Diagramme serveur](images/diagrammes/casUtilisation/serveur.png)

Diagramme de l'application mobile :

![Diagramme appli mobile](images/diagrammes/casUtilisation/application.png)

### Diagramme de séquence

Création d'un nouveau module

![Diagramme sequence creation module](images/diagrammes/sequence/creationNouveauModule.png)

Création et configuration d'une scène :

![Diagramme sequence configuration ](images/diagrammes/sequence/creationScene.png)

Activer une scène :

![Diagramme sequence activation scene](images/diagrammes/sequence/activerScene.png)

### Diagramme des classes

Diagramme du serveur :

![Diagramme classe vue serveur](images/diagrammes/classes/serveur/vueServeur.png)

Diagramme de l'application mobile :

![Diagramme classe vue serveur](images/diagrammes/classes/application/vueApplication.png)

### Base de donnée

![Diagramme BDD](images/diagrammes/DiagrammeBDD.png)

## Protocole

Nous utilisons le protocole MQTT pour communiquer entre l'application, le serveur et chaque boîtier Wifi-DMX
Le protocole MQTT à pour "bloker" le serveur avec l'IHM d'administration, chaque boîtier va se souscrire (Devenir "Subscriber") à un Topic qui sera le numéro de l'univers configuré sur le boîtier, lorsqu'un message sera envoyé sur le serveur, si le boîtier à pour univers le  nom du Topic sur lequel est envoyé le message, il lira les données. Sinon, il les laissera.

Quand on veut envoyer un message avec des informations (tel que les canaux), il faut donc utiliser le topic :
"Artnet/Univers/X" (où X représente le numéro de l'univers), ensuite nous pouvons envoyer le message que l'on souhaite sur ce boîtier.

## Changelog


## Défauts constatés non corrigés


## Equipe développement

- ÉTUDIANT IR 1 : [Aymeric CLEMENT](aymeric.clement.pro@gmail.com)
- ÉTUDIANT IR 2 : [Dylan VAUDAINE](dylan.vaudaine.pro@gmail.com)

--- 

&copy; 2025 LaSalle Avignon