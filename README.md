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
composer Update
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

## API Documentation (Postman)
API Documentation is published on the postman cloud, click [here](https://documenter.getpostman.com/view/2470580/2s93m8xKRu) to open the same.

Collection can be exported from this [link](https://api.postman.com/collections/2470580-b80013ad-d635-4a8c-abb0-87356632c1b9?access_key=PMAT-01H1HN84KZPN392XXRCCY82K0B)


Note - Please add one environment and a key `token` in your postman because one script is there in Login API which will set the environment variable automatically.