<?php
require 'vendor/autoload.php';

$services = require 'config/services.php';
$routes   = require 'config/routes.php';

try {
    $container = new Area51\System\Container($services);
    $context   = new Area51\System\Routing\Context();
    $router    = new Area51\System\Routing\Router($container, $context, $routes);

    $controller = $router->getController();
    $parameters = $router->getParameters();

    call_user_func_array($controller, $parameters);
} catch (\Exception $e) {
    echo $e->getMessage();
}

exit;
