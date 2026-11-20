<?php
require_once __DIR__ . '/inc/all.inc.php';

/// ============================CONTAINER========================================
$container = new \App\Utilities\Container();

$container->bind("pdo", function()
{
    return require __DIR__ . '/inc/db-connection.inc.php';
});

$container->bind("pagesController", function() use($container)
{
    $repo = $container->get("bookRepository");
    return new \App\Frontend\Controller\PagesController($repo);
});

$container->bind("bookRepository", function() use($container)
{
    $db = $container->get("database");
    return new \App\Repository\BookRepository($db);
});

$container->bind("database", function() use($container)
{
    $pdo = $container->get("pdo");
    return new \App\Utilities\Database\Database($pdo);
});

$container->bind("authService", function() use($container)
{
    $database = $container->get("database");
    return new \App\Utilities\AuthService($database);
});

$container->bind("gatewayController", function() use($container)
{
    $authService = $container->get("authService");
    return new \App\Gateway\Controller\GatewayController($authService);
});

$container->bind("router", function()
{
   return new \App\Router\Router();
});

/// ============================END CONTAINER========================================

$pagesController = $container->get("pagesController");
$gatewayController = $container->get("gatewayController");
$router = $container->get("router");

$router->add("GET", '', [$pagesController, 'displayLandingPage']);
$router->add("GET", '/', [$pagesController, 'displayLandingPage']);

$router->add("GET", '/book', [$pagesController, 'displayBookPage']);

$router->add("GET", '/edit', [$pagesController, 'editBookEntry']);
$router->add("POST", '/edit', [$pagesController, 'editBookEntry']);

$router->add("POST", '/delete', [$pagesController, 'deleteBookEntryById']);

$router->add("GET", '/create', [$pagesController, 'createBookEntry']);
$router->add("POST", '/create', [$pagesController, 'createBookEntry']);

$router->add("GET", '/authors', [$pagesController, 'displayAllAuthors']);
$router->add("GET", '/genres', [$pagesController, 'displayAllGenres']);
$router->add("GET", '/languages', [$pagesController, 'displayAllLanguages']);
$router->add("GET", '/publishers', [$pagesController, 'displayAllPublishers']);

$router->add("POST", '/login', [$gatewayController, 'login']);
$router->add("GET", '/login', [$gatewayController, 'login']);
$router->add("GET", '/logout', [$gatewayController, 'logout']);

$router->add("POST", '/register', [$gatewayController, 'register']);
$router->add("GET", '/register', [$gatewayController, 'register']);

$router->add("GET", '/admin', [$pagesController, 'displayLandingPage']);

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$router->dispatch($path);
