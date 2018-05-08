<?php
require __DIR__ . '/../vendor/autoload.php';

use Area51\System\Collection;
use Area51\System\Container;
use Area51\System\Http\Request;
use Area51\System\Routing\Context;
use Area51\System\Routing\Router;

$services = require __DIR__ . '/../config/services.php';
$routes = require __DIR__ . '/../config/routes.php';

try {
    $container = new Container($services);
    $request   = new Request(new Collection($_SERVER));
    $container->add(Request::class, $request);

    $context   = new Context($request->getMethod(), $request->getPath());
    $router    = new Router($container, $routes, $context);

    $response = call_user_func_array($router->getController(), $router->getParameters());

//    if (is_object($response) && ($response instanceof Continental\Service\Response\ResponseInterface)) {
//        $response->send();
//    }
} catch (\Exception $e) {
    echo $e->getMessage();
}

exit;
