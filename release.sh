#!/usr/bin/env bash
# Install composer deps
/usr/local/bin/php composer.phar install --no-ansi --no-dev --no-interaction --no-progress --no-scripts --optimize-autoloader
CurDate=$(date +%Y%m%d)
BuildDir="./build/$CurDate"
mkdir $BuildDir
cp -r ./ $BuildDir
cd $BuildDir
rm -r ./build ./.git/ ./.gitignore ./.idea/ ./config.php ./config.php.dist ./README.md ./phpcs.xml ./composer.* > /dev/null 2>&1
7z a -tzip -xr'!.*' ./build/parler-for-wordpress.zip ../parler-for-wordpress
echo "Release zipped in /build directory, please run \"composer.phar install && git checkout -- .\" to get back dev files"