
## Mosaic - Admin Dashboard Template for Laravel

How to get the best out of this template:

#### Setup your .env config file

Make sure to add the database configuration in your .env file such as database name, username, password and port.

##
#### Install Laravel dependencies

In the root of your Laravel application, run the ``php composer.phar install`` (or ``composer install``) command to install all of the framework's dependencies. 

##
#### Migrate the tables

In order to migrate the tables and setup the bare minimum structure for this app
to display some data you shoud open your terminal, locate and enter this project
directory and run the following command

##### ``php artisan migrate``

##
#### Generate some test data

Once you have all your database tables setup you can then generate some test data
which will come from our pre-made database table seeders.
In order to do so, in your terminal run the following command

##### ``php artisan db:seed``

N.B. If you run this command twice, all the test data will be duplicated and added
to the existing table data, if you want to avoid having duplicate test data please
make sure to ``truncate`` the following tables in your database:
- ``campaign_marketer``
- ``campaigns``
- ``customers``
- ``datafeeds``
- ``invoices``
- ``jobs``
- ``marketers``
- ``members``
- ``orders``
- ``transactions``

##
#### Compile the front-end
 
In order to compile all the CSS and JS assets for the front-end of this site you need to install NPM dependencies. To do that, open the terminal, type npm install and press the ``Enter`` key.

Then run ``npm run dev`` in the terminal to run a development server to re-compile static assets when making changes to the template.

When you have done with changes, run ``npm run build`` for compiling and minify for production.

##
#### Launch the Laravel backend

In order to make this Laravel installation work properly on your local machine you
can run the following command in your terminal window.

##### ``php artisan serve``

You should receive a message similar to this
``Starting Laravel development server: http://127.0.0.1:8000`` simply copy the URL
in your browser and you'll be ready to test out your new mosaic laravel app.
