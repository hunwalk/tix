#!/bin/bash
WORKING_DIR="/var/www/html/"

echo "Setup entrypoint"

composer install

chmod -R 777 $WORKING_DIR/runtime
chmod -R 777 $WORKING_DIR/web/assets

echo "Migrating"

php yii migrate-user --interactive=0
php yii migrate-rbac --interactive=0
php yii migrate --interactive=0

php-fpm