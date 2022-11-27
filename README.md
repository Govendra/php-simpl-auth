Authentication for PHP simple lightweight and secure.

Writtern once, to be used everywhere.

Compeletely core PHP and mysql database-agnostic.

Requirment for local run
    
    xampp or other Apache, Mysql, PHP localhost According to your system.
    
    (optional) smtp configuration for forget password.

Installation

    Move this folder on xampp/htdocs.

    Creat a new Database 'my_admin' on phpmyadmin.

    Import 'my_admin' sql file in the database you just created.

Other
    
    If you configure SMTP in login.php mail the $resetLink to $user in line number 70 and remove code from line number 73.

Features

    signup
    login
    logout
    forget password
    reset password by token
