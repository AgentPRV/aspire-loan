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

### Database Config Setup
Create & Set Database ENV keys using any MySQL GUI

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