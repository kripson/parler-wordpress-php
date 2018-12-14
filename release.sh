#!/usr/bin/env bash

# Create build directory
BuildDir="../build/"
mkdir ${BuildDir}
rsync -av --progress ./ ${BuildDir} --exclude .git/ --exclude .idea/
cd ${BuildDir}

rm -r vendor/ > /dev/null 2>&1

# Install composer deps
composer install --no-ansi --no-dev --no-interaction --no-progress --no-scripts --optimize-autoloader

# Cleanup and build zip
rm -r build/ .git/ .gitignore .idea/ config.php config.php.dist README.md release.sh phpcs.xml composer.* > /dev/null 2>&1
7z a -tzip -xr'!.*' ../parler.zip ./
echo "Release zipped in /build directory, please run \"composer install && git checkout -- .\" to get back dev files"