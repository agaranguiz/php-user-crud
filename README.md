# php-user-crud

RESTful user CRUD with JWT Authentication for API calls. Written in PHP, without frameworks.

## Installation
1. Create users table: 
```
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
)
```
2. Clone project:
   ``` git clone https://github.com/agaranguiz/php-user-crud.git ```
3. Install packages:
   ``` composer install ```
4. Create ``` .env ``` file based on ``` .env.example ``` with the database credentials.

## Usage

1. Manually create a valid user at the ``` users ``` table.
2. Call the ``` auth.php ``` endpoint with a valid username and password to obtain a token.
3. Add token to the headers of your API calls: ``` Authorization: Bearer OBTAINED_TOKEN ```

###### Table: Endpoints

| Endpoint | Method | GET Params | POST Params | Action |
| --- | --- | --- | --- | --- |
| auth.php | POST | none | username, password | Obtain JWT |
| users.php | GET | none | none | List all users |
| users.php | GET | id | none | List user by id |
| users.php | POST | none | username, password | Create user |
| users.php | PUT | id | username, password | Update user by id |
| users.php | DELETE | id | none | Delete user by id |
