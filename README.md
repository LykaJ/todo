ToDoList
========
[![Maintainability](https://api.codeclimate.com/v1/badges/248aa5b3dd71f950d881/maintainability)](https://codeclimate.com/github/LykaJ/todo/maintainability)

# 1. Installation #

  1. Clone or download the project.
  2. Install dependencies with `composer install`.
  3. Edit the .env file to configure your database thanks to this variable:  `DATABASE_URL=mysql://user:pass@127.0.0.1:8889/database_name`.
  4. Create the database with `php bin/console doctrine:database:create`.
  5. Run the command `php bin/console doctrine:schema:create`.
  6. Import the data in the db.sql file into your database.
  7. Run `php bin/console server:start`.

# 2. Usage #

To test the project, you can use the following account:  
id: admin  
pass: admin
