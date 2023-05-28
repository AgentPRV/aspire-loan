<p align="center"><img src="https://assets-global.website-files.com/5ed5b60be1889f546024ada0/5ed8a32c8e1f40c8d24bc32b_Aspire%20Logo%402x.webp" width="400" alt="Aspire"></a></p>


## About Aspire App

It is an app that allows authenticated users to go through a loan application.

Aspire App contains following features - 

- Signup / Signin for Users.
- Loan Approval by Admin.
- Loan Summaries in list & detail.
- Loan Repayments.


# Setup


Follow the below steps one by one in order to setup your application.
### Git clone
````
git clone https://github.com/AgentPRV/aspire-loan.git
````

### Composer Update
If composer is not installed in your system then install it from [here](https://getcomposer.org/download/)
````
composer update
````

If you are getting version conflict error that "Your requirements could not be resolved to an installable set of packages" which is due to different PHP versions then it can be resolved by below command, moreover we can use Docker to solve this issue.

````
composer install --ignore-platform-reqs
````
### Database Config Setup
Create a Database using any MySQL GUI and add database configuration (Host, Name and Password) in either .env file (need to create from .env.example file) or in config/database.php

### Run Migrations
````
php artisan migrate
````

### Run Seeders
````
php artisan db:seed
````

### Start the server
````
php artisan serve
````

## Testing
Aspire App contains Feature and Unit test cases both, please run the below command to run the test cases.

````
php artisan test
````

## API Documentation (Postman)
API Documentation is published on the postman cloud, click [here](https://documenter.getpostman.com/view/2470580/2s93m8xKRu) to open the same.

Collection can be exported from this [link](https://api.postman.com/collections/2470580-b80013ad-d635-4a8c-abb0-87356632c1b9?access_key=PMAT-01H1HN84KZPN392XXRCCY82K0B)


Note - Please add one environment and a key `token` in your postman because one script is there in Login API which will set the environment variable automatically.

## Project Overview and Decisions

### Authorization & Authentication - 
For Auth mechanism, we have used [Laravel Sanctum](https://laravel.com/docs/10.x/sanctum) which is a light weight library to create API tokens and SPA Authentication as well.

#### Alternative - 
[Laravel Passport](https://laravel.com/docs/10.x/passport) was one of the closest alternative for Sanctum but here we didn't required end-to-end oAuth Support, you can read more about this here in official [documentation](https://laravel.com/docs/10.x/passport#passport-or-sanctum) that when to use which one.

## Folder Structure - 

Apart from base Laravel structure below are the main folders in which we have our logic implementations - 

* app/Http/Controllers - This folder contains all the controllers which validate the API body if required and call their respective service layer.

* app/Models - This folder contains all the models having their schema relations.

* app/Services - This folder have all the services containing all business logic.

* database/factories - Contain all factories which majorly used in Testing.

* database/migrations - Contain all basic migrations that are required for application.

* database/seeders - Contain DB seeders.

* routes - Contain all routes for the application, in our use case we are using api.php file.

* tests - This folder contains all the test cases (Feature and Unit test case both)
