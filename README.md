# WPRouter
A router for Wordpress.

## Usage example:
```php

use Ivrok\ShowUsers\Users\UsersController;
use Ivrok\WpPageRouter\WPPageRoute;
use Ivrok\WpPageRouter\WPPageRouter;
use Ivrok\WPRouter\WPRoute;
use Ivrok\WPRouter\WPRouter;
$usersController = new UsersController();

$router = new WPRouter();
$router->addRoute(new WPRoute("show-users", [$usersController, "index"]));
$router->addRoute(new WPRoute("show-users/{su_id}", [$usersController, "index"]));
$router->init();

```
