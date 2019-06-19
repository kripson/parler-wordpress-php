# the compiler to use.
BuildDir=./build/
VERSION=staging-1.3.1

all: build

build: clean build_dir build_sync build_clean_vendors build_composer build_clean build_zip

build_dir:
	mkdir $(BuildDir)

build_clean:
	cd $(BuildDir) && rm -rf Makefile *.zip build/ .git/ .gitignore .idea/ logs/*.log config.php config.php.dist README.md *.sh phpcs.xml composer.* > /dev/null 2>&1

build_clean_vendors:
	rm -r $(BuildDir)vendor/

build_sync:
	rsync -av --progress ./ $(BuildDir) --exclude .git/ --exclude .idea/ --exclude build/

clean:
	rm -rf *.zip build/ logs/*.log

build_composer:
	cd $(BuildDir) && composer install --no-ansi --no-dev --no-interaction --no-progress --no-scripts --optimize-autoloader

build_zip:
	7z a -tzip -xr'!.*' ./parler-$(VERSION).zip $(BuildDir)
