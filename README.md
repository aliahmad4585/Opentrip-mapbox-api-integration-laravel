# Opentrip and mapbox api integration
application allow the user to search of a places of interest search and visualization application. With this application it should be possible for a user to provide a location name and receive a visual representation of places of interest around this location

# Demo
http://still-waters-02760.herokuapp.com/

# Installation requirements
- PHP 7|8.1
- Laravel 8.4
- VueJS 2
- composer
- [Docker Desktop](https://www.docker.com/products/docker-desktop)

## Installation

- git clone https://github.com/aliahmad4585/Opentrip-mapbox-api-integration-laravel.git
- git checkout master
- Run **composer install**
- Run **php artisan key:generate**
- copy env.example to .env 
- add mapBox and opentrip api access keys in env file
- npm install
- npm run dev
- php artisan serve

# Docker container
- Run "docker-compose build" to make a container
- Run "docker-compose up -d" to run the container


# Run test cases

## unit test cases
- ./vendor/bin/phpunit

## end to end / automation test
 - php artisan dusk



