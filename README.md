# Parler For Wordpress
Parler For Wordpress is a plugin which enables people to add comments to Wordpress articles.
These comments become integrated into the Parler.

Use Composer to install project dependencies. In the PHPStorm IDE, use Tools/Composer/Install. You'll
need to point it to a PHP install on your machine. Alternatively, see the Composer instructions below.

Getting Started
---------------
To properly use this code you'll need PHP, composer, and Wordpress. Wordpress, in turn, has other perquisites.
See 

* [PHP Installation and Configuration](http://php.net/manual/en/install.php)
* [Download Composer](https://getcomposer.org/download/)
* [Installing WordPress](https://codex.wordpress.org/Installing_WordPress)

If you wish to install React on your system in order to work with the React components used by 
this project, see:

* [How to install npm](https://blog.npmjs.org/post/85484771375/how-to-install-npm)
* [React npm package](https://www.npmjs.com/package/react)

Also, see the README.md file in the <u>parler-wordpress-react</u> project.

Installing 
----------
Install this software from git using 

````
git clone https://user_name@10.2.2.12:7990/scm/par/parler-wordpress-php.git
````

How To Add React
----------------
The parler-wordpress-php project uses the code from the parler-wordpress-react project. 
To access the code from parler-worrdpress-react, follow these steps:

* Checkout the latest code for the <u>parler-wordpress-react</u> project
* Execute <b>npm run build</b> against the <u>parler-wordpress-react</u> project
* Rename the .css file in the <b>/build/static/css</b> directory to <b>parler-for-wordpress-public.css</b>.
* Rename the .js file in the <b>/build/static/js</b> directory to <b>parler-for-wordpress-public.js</b>.
* Copy the css file to the PHP project, placing it in the <b>/public/css</b> directory.
* Copy the js file to the PHP project, placing it in the <b>/public/js</b> directory.

Packaging The Plugin
--------------------
 Once you've followed the above steps, you're ready to package the plugin for deployment. Follow these steps:
* Run composer install
* Zip up the directory containing the code.
 
 Deploying The Plugin
 --------------------
To deploy the plugin, you must have admin privileges on a Wordpress site. If you've set up your
own site on your local machine, you should have the needed privileges. Follow these steps:

* In the upper menu of the Wordpress site, find the menu with the <b>site name</b>. Click the <b>Dashboard</b> menu item.
* In the dashboard panel, click the <b>Plugins</b> option.
* Find the <b>Parler for Wordpress</b> plugin and click its <b>Deactivate</b> option.
* Find the <b>Parler for Wordpress</b> plugin and click its <b>Delete</b> option.
* Go to the top of the <b>Plugins</b> page and click the <b>Add New</b> button.
* Go to the top of the <b>Add Plugins</b> page and click the <b>Upload Plugin</b> button.
* Use the <b>Chose File</b> button to select your .zip file. Then click the <b>Install Now</b> button.
* When the option <b>Activate Now</b> appears. Click it.

Trouble Shooting
----------------
* If you get an error saying your plugin is too large, open the file <b>php/php.ini</b> in your
 Wordpress instalation and change <b>upload_max_filesize</b>

