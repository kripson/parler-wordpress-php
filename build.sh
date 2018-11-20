#!/usr/bin/env bash
composer.phar install --no-ansi --no-dev --no-interaction --no-progress --no-scripts --optimize-autoloader
rm config.php config.php.dist README.md phpcs.xml composer.* build.sh
7z a -tzip ./build/parler-wordpress-php-$(date +%Y-%m-%d).zip ../parler-wordpress-php