# WPRouter
A router for Wordpress.

## Installation:
```console
composer require ivrok/wp-router
```

## Usage example:
```php
use Ivrok\WPRouter\WPRoute;
use Ivrok\WPRouter\WPRouter;

$usersController = new \UsersController();

$router = new WPRouter();
$router->addRoute(new WPRoute("show-users", [$usersController, "index"]));
$router->addRoute(new WPRoute("show-users/{su_id}", fn($suID) => echo $suID);
$router->init();

```
