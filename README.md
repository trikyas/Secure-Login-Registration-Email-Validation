# Secure-Login-Registration-Email-Validation

A secure Register | Login that uses ARGON2I Password hashing and includes Forgotten Password and Email Validation. 

## Getting Started

Clone or Download Repository
```
$ git clone https://github.com/trikyas/Secure-Login-Registration-Email-Validation
```
Navigate into the directory.
```
$ cd Secure-Login-Registration-Email-Validation/
```
Now just run composer require or composer install.
```
$ composer require phpmailer/phpmailer
```
Start your server e.g. MAMP, LAMP ...

Go to your phpMyAdmin and create an EMPTY Database, named registration and import the database.sql

Open your browser and enjoy.
[Secure-Login-Registration-Email-Validation](http://127.0.0.1:8888/registration/sign_up.php)

The email credentials are placed in ./includes/header.php on lines 41 - 42

```
$mail->Username = "YOUR_GMAIL_ADDRESS@gmail.com";
$mail->Password = "YOUR_GMAIL_PASSWORD";
```


## Authors

* **Chad Mooney** - *Initial work* - [Trikyas](https://github.com/trikyas)

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details

## Acknowledgments

* Hat tip to anyone who claims their code was used
* NO MD5! 
* Thanks to my family for being there.
* My Son **Sevah** , my Daughter **Josephine** and my Wife **Katrina**
* You guys are my world!
