## laravel-user-management ##

This is a basic user/role management application built using Laravel 5.4.  It was designed to be used as a starting point for larger projects that require role based access.  Because of this, there is limited functionality and almost no styling.  The seeder creates a single user and a single ADMIN role, which is assigned to that user.


### Installation ###

* Create a database for the app to use
* `git clone https://github.com/jpospisil402/laravel-user-management.git projectname`
* `cd projectname`
* `cp .env.example .env`
* Edit *.env* to include the desired settings (connection details, mail settings, etc...)
* `composer install`
* `npm install`
* `npm run production`
* `php artisan key:generate`
* `php artisan migrate --seed` to create and populate tables
* `php artisan serve` to start the app on http://localhost:8000/


### Include ###

* [Laravel 5.4](https://laravel.com/docs/5.4) PHP web framework
* [Vue.js](https://vuejs.org) Javascript framework
* [Bootstrap](http://getbootstrap.com) for CSS and jQuery plugins
* [Font Awesome](http://fortawesome.github.io/Font-Awesome) for the nice icons


### Features ###

* Authentication (registration, login, logout, password reset, mail confirmation, throttle)
* User roles : ADMIN
* Middleware for verifying a specific role
* Admin interface for managing users and adding roles


### Packages included ###

The included packages can be found by viewing package.json and composer.json, both of which are at the root of the project.


### Tips ###

* Upon installation, a single user is created.  That user can be defined in the .env file using the following variables: FIRST_USER_NAME, FIRST_USER_EMAIL, FIRST_USER_PW
* If those variables are not set, these default credentials are used: email: admin@localhost.domain, password: p@55w0rd