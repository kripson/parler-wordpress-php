#!/usr/bin/env bash
/usr/local/bin/php composer.phar install --no-ansi --no-dev --no-interaction --no-progress --no-scripts --optimize-autoloader
rm config.php config.php.dist README.md phpcs.xml composer.* > /dev/null 2>&1
zip -r ./build/parler-wordpress-php-$(date +%Y-%m-%d).zip ./parler-for-wordpress -x "*.DS_Store"
echo "Release zipped in /build directory, please run \"composer.phar install && git checkout -- .\" to get back dev files"