@ECHO OFF
setlocal DISABLEDELAYEDEXPANSION
SET BIN_TARGET=%~dp0/../../../../vendor/symfony/phpunit-bridge/bin/simple-phpunit
php "%BIN_TARGET%" %*
