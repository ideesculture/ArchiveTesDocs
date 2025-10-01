#!/bin/bash
# Vide le cache Twig
sudo -u www-data php bin/console assets:install -e prod
sudo -u www-data php bin/console assetic:dump -e prod
sudo -u www-data php bin/console cache:clear -e prod
# Déconnecte les utilisateurs connectés
cat cache-clear-prod.sql | mysql -u archimage -pu3b9ucAgGméé archimage
