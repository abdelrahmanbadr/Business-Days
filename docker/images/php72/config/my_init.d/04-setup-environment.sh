#!/bin/bash

echo "================================"
echo "PHP ENV: ${PHP_ENV}"
echo "================================"
echo " "

echo "==> Enabling development"
phpdismod -s ALL -v 7.2 production
phpenmod -s ALL -v 7.2 development

echo "================================"
echo "==> Reloading PHP7-FPM"
echo "================================"
service php7.2-fpm reload

echo "================================"
