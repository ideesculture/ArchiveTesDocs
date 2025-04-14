php bin/console cache:clear --env=dev
php bin/console cache:clear --env=prod
rm -R var/cache/dev/*
rm -R var/cache/prod/*
php bin/console assets:install
php bin/console assetic:dump
chmod -R 777 var/cache
chmod -R 777 var/logs
echo -ne '\007'
echo --End of Update--
