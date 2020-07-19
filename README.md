> ### Todo App Backend

This app is a manage your todos.
You can view a demo here [Demo](https://tudu-app-frontend.herokuapp.com/)
Api hosted there [API](https://tudu-app-backend.herokuapp.com/api)
API Docs [here](https://documenter.getpostman.com/view/4827230/T1DjkzZN)

## Features

-   Create a todo
-   Edit a todo
-   Complete a todo
-   Delete a todo

# Getting started

## Installation

Please check the official laravel installation guide for server requirements before you start. [Official Documentation](https://laravel.com/docs/5.8/installation)

Clone the repository

    git clone https://github.com/kingeke/tudu-backend.git

Clone the frontend repository and read the documentation there [frontend](https://github.com/kingeke/tudu-frontend.git)

    git clone https://github.com/kingeke/tudu-frontend.git

Switch to the repo folder

    cd tudu-backend

Install all the dependencies using composer

    composer install

Copy the example env file and make the required configuration changes in the .env file

    cp .env.example .env

Generate a new application key

    php artisan key:generate

Generate a new JWT token key

    php artisan jwt:secret

**Make sure you set the correct database connection information before running the migrations**

    php artisan migrate

Start the local development server

    php artisan serve

You can now access the server at http://127.0.0.1:8000

# Testing

## PHP

To run tests for the backend and assert the app still works 100%, create a .env.test file in the root of the folder, set DB_DATABASE variable to your test database, and set JWT_SECRET variable to the variable provided when you ran

    php artisan jwt:secret

then run

    php vendor/phpunit/phpunit/phpunit
