# How To Add React
The parler-wordpress-php project uses the code from the parler-wordpress-react project. 
To access the code from parler-worrdpress-react, follow these steps:

* Checkout the latest code for the <u>parler-wordpress-react</u> project
* Execute <b>npm run build</b> against the <u>parler-wordpress-react</u> project
* Rename the file .css file in the <b>/build/static/css</b> directory to <b>parler-for-wordpress-public.css</b>.
* Rename the file .js file in the <b>/build/static/js</b> directory to <b>parler-for-wordpress-public.js</b>.
* Copy the css file to the PHP project, placing it in the <b>/public/css</b> directory.
* Copy the js file to the PHP project, placing it in the <b>/public/js</b> directory.

# Packaging The Plugin
 Once you've followed the above steps, you're ready to package the plugin for deployment. To do this, just zip up 
 the directory containing the code.