# Posts Web App

### Setup

For this example application, a database must be created and the connection info added to `src/Repositories/Repository.php`.
Note that for a real application we would not be putting credentials in source code. Instead, environment variables would be used.

### Install dependencies

```
$ composer install
```

### Running tests

```
$ vendor\bin\phpunit
```

### Running the application
You can run the application using the built-in PHP web server, specifying the document root as the `src` directory:

```
$ php -S localhost:7777 -t src/Views
```

Alternatively, you can run it using Apache or Nginx.
yes