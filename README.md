<h1 align="center">Phonebook API Test Repository</h1>

## About Phonebook API Test

This is a repository showcasing an example of a phonebook API. It is built on the following technology:
* Laravel 8
* Laravel Sanctum (for Authentication)
* MySQL (Although other databases should work)
* PHP 7.4

## Getting Started

### Clone the repository
Run `git clone https://github.com/ianlchapman/phonebook-api.git` on your command line to clone the repository to your local machine.
Run `cd phonebook-api` to move in to the codebases folder.

### Update ENV variables
You can then update the `.env` to put at your local installation. The only values you are required to change are the database references.

### Setup the database
Run `php artisan migrate` to install the database schema.

### Running the test suite
* Run `php artisan test` to validate the functional tests pass

### Running the code quality tools:
* Run `./vendor/bin/phpcs` to validate the codebase meets the PSR-2 standard
* Run `./vendor/bin/phpstan analyse` to perform static analysis against the codebase
