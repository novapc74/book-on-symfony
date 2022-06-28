## [Test App Vacancy](https://gitlab.com/prog-positron/test-app-vacancy)

***
## How to use:
```
$ git clone git@github.com:novapc74/book-on-symfony.git
$ composer update
$ yarn
$ yarn run dev

$ symfony console doctrine:migrations:migrate
$ symfony console doctrine:schema:validate

$symfony serve
```
***
### Upload the books:
```
symfony console app:book-parser https://gitlab.com/prog-positron/test-app-vacancy/-/raw/master/books.json

```
***
### PostgresSQL
```
$ sudo -u postgres psql

postgres=# CREATE DATABASE test;
postgres=# CREATE USER test WITH ENCRYPTED PASSWORD 'test';
postgres=# GRANT ALL PRIVILEGES ON DATABASE test TO test;
postgres=# exit

OR

php bin/console doctrine:schema:update --dump-sql
php bin/console doctrine:schema:update --force
php bin/console doctrine:schema:validate
```
***
### Add some data to .env file
```
DB_NAME=your_database_name
DB_USER=your_user_name
DB_PASSWORD=your_password

// fore example (postgresSQL): 
DATABASE_URL="postgresql://${DB_USER}:${DB_PASSWORD}@127.0.0.1:5432/${DB_NAME}?serverVersion=12.11&charset=utf8"
```