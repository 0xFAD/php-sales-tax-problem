# Sales tax problem solution
The goal of this project is to solve the **Sales Tax Problem** in PHP.

## How to use
### Clone the repo
```bash
git clone git@github.com:0xFAD/php-sales-tax-problem.git
cd php-sales-tax-problem
```

### PHP-CLI
If you have already installed PHP in your system you can run the following commands:
```bash
composer install
./vendor/bin/phpunit tests/ # run tests
php bin/console basket
```

### Docker
Otherwise, if PHP is not present in your system, but you already have a docker (or docker-machine) installation you can compile the project image and run the previously commands inside container shell as follows:
```bash
docker build --rm --force-rm --no-cache -t 0xfad/sales-tax-problem .
docker run -it --rm -v $PWD:/opt 0xfad/sales-tax-problem ash
composer install
./vendor/bin/phpunit tests/ # run tests
php bin/console basket
```
