# laravel-crud-generator
A simple Laravel package for generating CRUD for your Laravel Applications.
This package provides a convenient way to generate the boilerplate code necessary for complete CRUD functionality.

## Requirements
    Laravel >= 9.0
    PHP >= 8.0
## Installation
Require this package in your Laravel project using composer:

```
composer require naowas/crud-generator
```

Once installed, publish the stubs/views using `php artisan vendor:publish` artisan command:

```
php artisan vendor:publish --provider="Naowas\CrudGenerator\CrudGeneratorServiceProvider"
```

## Usage

This package offers a single Artisan command to generate CRUD components for a specific model:


```
php artisan generate:crud YourModelName
```

The command will generate: 
1. Model
2. Controller
3. FromRequest
4. Migration
5. View (index,create,update,show)

**_Please note that you'll need to customize the migration file to define the database columns according to your specific business requirements.**_






