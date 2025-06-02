#!/bin/bash
/usr/bin/php /var/www/html/artnet-2025/artnetAdministration/gestionModulesDMXWiFi.php &
/usr/bin/php /var/www/html/artnet-2025/artnetAdministration/gestionEquipementDMX.php &
wait