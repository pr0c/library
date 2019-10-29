#Back-end
1. Create database
2. Create tables using synergy.sql
3. Change database name in app/config/config.php
```php
return [
    'database' => [
        'host' => 'localhost',
        'user' => 'root',
        'password' => 'root',
        'db' => 'database_name'
    ]
];
```
4. Open in command line project directory and run local server by command
`php -S localhost:8000`
OR if you're using web server just put files in website folder and run web server
5. Download and run front-end [https://github.com/pr0c/synergy_frontend](https://ithub.com/pr0c/synergy_frontend)