# Parler For Wordpress

The plugin will sync onctent between Parler & WordPress.

Additionally it offers a drop-in replacement for the existing WordPress comments system.

Use Composer to install project dependencies. In the PHPStorm IDE, use Tools/Composer/Install. 

You'll need to point it to a PHP install on your machine. Alternatively, see the Composer instructions below.

Getting Started
---------------

To properly use this code you'll need PHP and Wordpress. Wordpress, in turn, has other perquisites.

The easiest way to run wordpress on your machine with this code is to install [Docker](https://docker.io)

Then read the readme in the docker wp dev repo to bring up a wordpress container with this plugin code. 

Clone this code along side this repo to make sure of the volume mount without changes needed to the docker-compose.yml

`git clone git@gitlab.parler.com:frontend/dev-docker-wp-plugin.git parler-for-wordpress`

Ensure the name of this projects directory is `parler-for-wordpress` which is what the docker envrionment specifies as the volume mount.

The file `parler-for-wordpress.php` has two important settings:

* PARLER4WP_VERSION
  * The semver of the build
* PARLER4WP_ENV
  * Either STAGING or PROD

Alternatively you can also copy the `config.php.dist` over to `config.php` to point at a custom uri and control other env vars.

If no config is provided then it defaults to live.

We have removed the react code from this plugin, it used to be compilied with the code.

Use `config.php` to point to your react dev envionrment if needed.
 
Prerequisites
----------
WordPress Minimum - PHP 5.4+

Install [Docker](https://docker.io) for Dev Environment.

Installing 
----------
Install this software from git using 

````
git clone git@gitlab.parler.com:frontend/parler-wordpress-php.git
````

If developing against the staging environment, move `config.php.dist` to `config.php`.

THere are some env vars you can override there to point at diff assets.

Deploying
---------

Change the environment in the root `parler-for-wordpress.php` file to STAGING or PROD.

Run `make` in your terminal within the project directory root.

This will create a distributable zip in the root of the project. 

Install that zip on a WordPress site and give it to QA to test.

Have the svn repo cloned next to this at `./parler`

Once approved, tag it and run the `./push_to_svn.sh` command. 

Then goto the svn repo directory and run
```bash
svn diff # Check yo self
svn status # Before you wreck yo self
svn ci -m "Added some new wizzbang feature." # Bop it
svn cp trunk/ tags/1.2.4 # Tag it
svn status # Pull it
svn ci -m "Tagged version 1.2.4" # Pass it
```

How To Add React
----------------

Read above on how to configure the `config.php` to point to your react assets locally.

Troubleshooting
----------------
* If you get an error saying your plugin is too large, open the file <b>php/php.ini</b> in your
 Wordpress installation and change <b>upload_max_filesize</b>

* If the bulk importer is not working, please ensure your wp installation hostname can be reached within it's localhost. The plugin utilizes async calls to your WP installation to background the bulk import jobs.

Notes
-----
This was originally based off of [this boilerplate](https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate)

TODOs
-----
 * Add some better text feedback after activation of plugin