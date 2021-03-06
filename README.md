# codeIgniter-login-registration

Version : 3.1.11

Login & Registration using UI & API

1. Create database named `codeigniter-demo` in MySQL, then import `codeigniter-demo.sql` folder file
2. Set base URL in `application/config/config.php`

```
$config['base_url'] = 'http://localhost:8001';
```
3. Set database configuration in `application/config/database.php` for `hostname`, `username`, `password`

```
$db['default'] = array(
	'hostname' => 'localhost', // 127.0.0.1
	'username' => 'root',
	'password' => '',
	'database' => 'codeigniter-demo',
);  
```
4. Go to Terminal / CLI. On the project root folder `codeIgniter-login-registration`, please run commnd `php -S localhost:8001`
   On success you will get message like below

```
PHP 7.1.23 Development Server started at Thu Mar 07 23:18:19 2021
Listening on http://localhost:8001

```
5. Open browser and navigate to `http://localhost:8001/`
	1. `http://localhost:8001/auth/login` -> Login
	2. `http://localhost:8001/index.php/auth/register` -> Registration

6. API is also created for Login & Registration. Below is the Postman Code

Login API 

```
curl --location --request POST 'http://localhost:8001/api/authentication/login' \
--header 'Content-Type: application/json' \
--header 'X-API-KEY: ydh9Z#au7NS8c4?G' \
--header 'Authorization: Basic YWRtaW46O00jNGVIfVIpejd3WWZ2Ug==' \
--data-raw '{
	"email" : "mike.smith@yahoo.com",
	"password" : "123456"
}'

```

Registration API

```
curl --location --request POST 'http://localhost:8001/api/authentication/registration' \
--header 'Content-Type: application/json' \
--header 'x-api-key: ydh9Z#au7NS8c4?G' \
--header 'Authorization: Basic YWRtaW46O00jNGVIfVIpejd3WWZ2Ug==' \
--data-raw '{
	"first_name" : "Mike",
	"last_name" : "Smith",
	"email" : "mike.smith@yahoo.com",
	"password" : "123456"
}'

```



