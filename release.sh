#!/usr/bin/env bash

# Create build directory
BuildDir="./build/"
mkdir ${BuildDir}
rsync -av --progress ./ ${BuildDir} --exclude .git/ --exclude .idea/ --exclude build/
cd ${BuildDir}

# Cleanup
rm -r vendor/ > /dev/null 2>&1

# Install composer deps
composer install --no-ansi --no-dev --no-interaction --no-progress --no-scripts --optimize-autoloader

# Cleanup and build zip
rm -r *.zip build/ .git/ .gitignore .idea/ logs/*.log config.php config.php.dist README.md *.sh phpcs.xml composer.* > /dev/null 2>&1
7z a -tzip -xr'!.*' ../parler.zip ./
echo "Release zip located in root of project at parler.zip"