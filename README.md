# Calculator Code Challlenge Assignment

## Objective
Evaluate my skill level at each level in the stack.

## Assignment

Create a simple calculator that supports addition and subtraction operations only for only two
numbers (i.e. 3 and 5). This calculator must save all calculations to a database and be able to show
the latest calculations on the frontend (also after reloading the page).

# Install Instructions

## Docker

Make sure the ports `3001`, `3002` and `3306` are open. If not, you can config the `docker-compose.yml` file for different ports.

1. Change directory to `docker`
2. Run the command `docker-compose up -d`
3. Wait the build of the images, and then access the frontend with `locahost:{PORT:3001}` and the backend with `locahost:{PORT:3002}`

## Backend - PHP

1. Run the `composer install` command
    - You can also run this command to do that in the docker image: `docker exec $(docker ps --filter name=docker_calculator-backend --format {{.ID}}) sh -c "composer install"`
2. Modify the permissions for the `storage` folder to `755`
    - Docker command: `docker exec $(docker ps --filter name=docker_calculator-backend --format {{.ID}}) sh -c "chmod -R 775 storage/"`
3. In the root folder, copy the `.env.example` as `.env` and modify it.
    - If you did any modifications in the ports, is specially important to modify the `API_DOMAIN` variable
4. Run the migrations using the `php artisan migrate:fresh`
    - Docker command: `docker exec $(docker ps --filter name=docker_calculator-backend --format {{.ID}}) sh -c "php artisan migrate:fresh"`

To access the PHP backend, you need to send an `Accept` header with the value `application/x.calculator_code_challenge.v1+json`. You can do that using [Postman](https://www.getpostman.com/)

## Frontend - React

1. First, edit the `.env` file with address of the api endpoint (if you have modfied any port)
2. Run `npm install`
    - Docker command: `docker exec $(docker ps --filter name=docker_calculator-frontend --format {{.ID}}) sh -c "npm install"`
3. Run `npm run build` to create the production build
    - Docker command: `docker exec $(docker ps --filter name=docker_calculator-frontend --format {{.ID}}) sh -c "npm run build"`

# Tecnhologies used:

1. **Docker** for the containers
    - OS: [**Alpine**](https://alpinelinux.org/) 
    - Server: [**Nginx**](https://www.nginx.com/)
    - Process Managment: [**supervisord**](http://supervisord.org/)
    - **PHP-FPM**
2. **PHP** for the Backend
    - Framework: [**Lumen-Laravel**](https://lumen.laravel.com)
    - API Library: [**Dingo API**](https://github.com/dingo/api)
    - Testing: [**PHPUnit**](https://phpunit.de/)
    - Response Format: [**JSON API**](https://jsonapi.org)
3. **MYSQL** Database
4. **React** Frontend
    - Builder: [**create-react-app**](https://facebook.github.io/create-react-app/)
    - HTTP Calls: [**axios**](https://github.com/axios/axios)
    - Design: [**bootstrap**](https://getbootstrap.com/)
    - StyleSheets: [**sass**](https://sass-lang.com/)

# Assignment Requirements

> Create a simple calculator that supports addition and subtraction operations only for only two numbers (i.e. 3 and 5). 

### PHP 

#### **/calculus (POST)**
- Try to validate and save the calculation in the database
- It will return a **JSON** with the operation that has been saved
    - If you are try to do some kind of invalid calculation (Like +1+), it will return a json with errors messages and change the **HTTP Code** accordingly

##### Body
````
    {
        "calculation": ["1", "+", "1"]	
    }
````

### React
1. The `frontend/src/components/Calculator.js` component starts the calculator:
2. `frontend/src/components/Panel.js` which controls the `Display` of the Calculator
3. `frontend/src/components/ControlPanel;js` which sets the buttons (using the `frontend/src/components/Button.js`)

When you click the `=` sign, it validates all the data (with feedback), then send it to backend to be saved, where it returns if it succeded or failed.

> This calculator must save all calculations to a database and be able to show the latest calculations on the frontend (also after reloading the page).

### PHP 

#### **/calculus (GET)**
> Returns an **JSON** with all the operations that exist in the database.

### React

The `frontend/src/components/Calculator.js` component has also the `frontend/src/components/Log.js` that fetchs from the backend all the operations that were made.

> Use of docker (all the environment must run by issuing a docker-compose command)

You can run the `docker-compose up -d` command to start the entire application with Docker.

> Use of a reactive framework (use Vue, React or AngularJS, preference for Vue)

The entire application is made with **React**. 

> Use of a database (preference for MySQL)
The database can be found in the `backend/database` as `migrations`

The database image (**MYSQL 5.7**) can be found in the `docker/docker-compose.yml`, which is linked to the backend image.


> Use of PHP for all backend environment/web services
> PHP Framework – we have a preference for Laravel, but you can write raw PHP as long as you test it
The backend is an **REST API** made with **Lumen** which is a microframework from **Laravel**


> Use of an Automated Test Suite (i.e. unit tests, integration tests, acceptance tests – you’re welcome to choose what is best for your app)
The `backend/tests/` folder has all the **Unit Tests** for the backend:
- You can run the tests using this docker command: `docker exec $(docker ps --filter name=docker_calculator-backend --format {{.ID}}) sh -c "vendor/phpunit/phpunit/phpunit"` 
- `CalculationModelTest.php` tests the logic for the `backend/app/Calculation.php` class, which is where the application sum/substract the results of the calculations and save in the database.
- `CalculusControllerTest.php` has **HTTP** tests to the **INDEX (GET)** and **CREATE (POST)** methods
    - The responses are made based in the [**JSON API**](https://jsonapi.org) format