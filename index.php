<?php
require 'vendor/autoload.php';

$roomFactory  = new Area51\Factory\RoomFactory();
$robotFactory = new Area51\Factory\RobotFactory($roomFactory);
$robot = $robotFactory->make('akárki@valami.hu');

echo 'Welcome to Area 51...';
echo '<pre>' . print_r($robot, true) . '</pre>';

$routes = require 'config/routes.php';

try {
    $container = new Area51\System\Container();
    $context   = new Area51\System\Routing\Context();
    $router    = new Area51\System\Routing\Router($container, $context, $routes);

    $controller = $router->getController();
    $parameters = $router->getParameters();

    call_user_func_array($controller, $parameters);
} catch (\Exception $e) {
    echo $e->getMessage();
}

exit;
