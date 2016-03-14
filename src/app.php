<?php

use Caseyfw\SprinklerServer\RoutesLoader;
use Caseyfw\SprinklerServer\ServicesLoader;
use Silex\Application;
use Silex\Provider\HttpCacheServiceProvider;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

// Handle CORS preflight request.
$app->before(function (Request $request) {
    if ($request->getMethod() === "OPTIONS") {
        $response = new Response();
        $response->headers->set("Access-Control-Allow-Origin","*");
        $response->headers->set("Access-Control-Allow-Methods",
            "GET,POST,PUT,DELETE,OPTIONS");
        $response->headers->set("Access-Control-Allow-Headers","Content-Type");
        $response->setStatusCode(200);
        return $response->send();
   }
}, Application::EARLY_EVENT);

// Handle CORS response with correct headers.
$app->after(function (Request $request, Response $response) {
    $response->headers->set("Access-Control-Allow-Origin","*");
    $response->headers->set("Access-Control-Allow-Methods",
        "GET,POST,PUT,DELETE,OPTIONS");
});

// Parse incoming JSON.
$app->before(function (Request $request) {
    if (strpos($request->headers->get('Content-Type'),
        'application/json') === 0) {
        $data = json_decode($request->getContent(), true);
        $request->request->replace(is_array($data) ? $data : []);
    }
});

$app->register(new ServiceControllerServiceProvider());

$app->register(new DoctrineServiceProvider(),
    ["db.options" => $app["db.options"]]);

$app->register(new HttpCacheServiceProvider(),
    ["http_cache.cache_dir" => ROOT_PATH . "/var/cache"]);

// Load services.
$servicesLoader = new ServicesLoader($app);
$servicesLoader->bindServicesIntoContainer();

// Load routes.
$routesLoader = new RoutesLoader($app);
$routesLoader->bindRoutesToControllers();

$app->error(function (\Exception $e, $code) use ($app) {
    return new JsonResponse([
        "statusCode" => $code,
        "message" => $e->getMessage(),
        "stacktrace" => $e->getTraceAsString(),
    ]);
});

return $app;