# addEventListenerSymfonyTest

$composer install
Creation de .env.local
$php bin/console doctrine:database:create
$php bin/console doctrine:schema:update --dump-sql
$php bin/console doctrine:schema:update --force
$php bin/console doctrine:fixture:load
