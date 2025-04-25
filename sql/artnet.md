# Installation base de données artnet

## MySQL

[MySQL](https://www.mysql.com/fr/) est un système de gestion de bases de données relationnelles (SGBDR). Il fonctionne en **client/serveur**.

Il est distribué sous une double licence GPL et propriétaire. Il fait partie des logiciels de gestion de base de données les plus utilisés au monde, autant par le grand public (applications web principalement) que par des professionnels, en concurrence avec Oracle, Informix, PostgreSQL, et Microsoft SQL Server.

MySQL fonctionne sur de nombreux systèmes d’exploitation différents, incluant GNU/Linux, Mac OS X et Windows. Les bases de données sont accessibles en utilisant les langages de programmation C, C++, VisualBasic, VB .NET, C#, Delphi/Kylix, Eiffel, Java, Perl, PHP, Python, Windev, Ruby et Tcl. Une API spécifique est disponible pour chacun d’entre eux.

> En 2009, à la suite du rachat de MySQL par Sun Microsystems et des annonces du rachat de Sun Microsystems par Oracle Corporation, Michael Widenius, fondateur de MySQL, quitte cette société pour lancer le projet MariaDB, dans une démarche visant à remplacer MySQL tout en assurant l’interopérabilité. Le nom vient de la 2e fille de Michael Widenius, Maria (la première s’appelant My). MariaDB a été choisi par défaut sur les distributions « Debian ».

Le serveur peut être installé à partir des paquets ou en utilisant un conteneur [Docker](https://dev.mysql.com/doc/mysql-installation-excerpt/8.0/en/docker-mysql-getting-started.html).

### Solution n°1 : paquet Ubuntu

```sh
$ sudo apt install mysql-server

$ mysql --version

$ sudo systemctl status mysql.service

$ mysql -h 127.0.0.1 -u root -ppassword -e "show databases;"
```

### Solution n°2 : Docker

- Installation `docker-ce` :

```sh
$ sudo apt update
$ sudo apt install ca-certificates curl gnupg
$ sudo install -m 0755 -d /etc/apt/keyrings
$ curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo gpg --dearmor -o /etc/apt/keyrings/docker.gpg
$ sudo chmod a+r /etc/apt/keyrings/docker.gpg
$ echo   "deb [arch=$(dpkg --print-architecture) signed-by=/etc/apt/keyrings/docker.gpg] https://download.docker.com/linux/ubuntu \
  $(. /etc/os-release && echo "$VERSION_CODENAME") stable" |   sudo tee /etc/apt/sources.list.d/docker.list > /dev/null
$ sudo apt update

$ sudo apt install docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin

$ docker --version
$ docker compose version
$ docker version
```

- Fichier [docker-compose.yml](./docker-compose.yml) :

```yaml
version: '3'
services:
    mysql:
        image: mysql:latest
        environment:
            MYSQL_ROOT_PASSWORD: 'password'
            MYSQL_DATABASE: 'artnet'
            MYSQL_USER: 'artnet_User'
            MYSQL_PASSWORD: 'password'
        ports:
            - 3306:3306
        volumes:
            - bdd:/var/lib/mysql
volumes:
    bdd:
```

- Création et démarrage du serveur MySQL :

> Vérifier que le serveur MySQL ne s'exécute pas en local : `systemctl status mysql.service` sinon il faut l'arrêter avec `sudo systemctl stop mysql.service`.

```sh
$ docker compose --file sql/docker-compose.yml up --detach
```

> Sinon pour simplement démarrer le service mysql : `$ docker compose start mysql`

- Vérifications :

```sh
$ docker compose ls

$ docker compose ps
$ docker ps -a

$ docker images

$ docker compose top
```

- Redémarrer le service mysql :

```bash
$ docker compose restart mysql
```

- Arrêter le service mysql :

```bash
$ docker compose stop mysql
```

- Si besoin, supprimer l'image `mysql` :

```bash
$ docker compose rm mysql
$ docker rmi $(docker images mysql -q)
```

## Base de données artnet

cf. [artnet.sql](./artnet.sql)

- Installation :

```sh
$ mysql -h 127.0.0.1 -u root -ppassword artnet < sql/artnet.sql

$ mysql -h 127.0.0.1 -u root -ppassword artnet -e "show tables;"
```

## PHP

Dans le fichier [config.php](../artnetAdministration/config.php) :

```php
...
// Pour la base de données
define("DB_DRIVER", true); // true pour MySQL, false sans base de données
define("DB_HOST", "127.0.0.1");
define("DB_USER", "artnet_User");
define("DB_PASS", "password");
define("DB_NAME", "artnet");
...
```

Dans le constructeur de la classe [Model](../artnetAdministration/www/classes/model.php) :

```php
abstract class Model
{
    protected $dbh = null; // ?PDO
    protected $stmt; // PDOStatement

    public function __construct()
    {
        if (DB_DRIVER) {
            if (!in_array("PDO", get_loaded_extensions()))
                die("L’extension PDO n’est pas présente !<br><br>");
            if (!in_array("pdo_mysql", get_loaded_extensions()))
                die("L’extension pdo_mysql n’est pas présente !<br><br>");
            $this->dbh = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS) or die("Echec de la création de l’instance PDO !");
        }
    }
    ...
}
```

---
&copy; 2024-2025 LaSalle Avignon
