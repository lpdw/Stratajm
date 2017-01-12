# Strata'j'm
========================

This is an upcoming extension for the Strata'j'm website (stratajm-idf.fr), a french organization.

This new tool will eventually allow Srata'j'm to manager its games and members. A new public page will display all registered games.

# How to set up the project after a git pull :
$composer install
$composer update
$composer require knplabs/knp-paginator-bundle
$php bin/console doctrine:database:create
$php bin/console doctrine:schema:update --force
$php bin/console assetic:dump
$php bin/console server:run
