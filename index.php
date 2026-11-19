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

/// ============================END CONTAINER========================================

$pagesController = $container->get("pagesController");
$router = new \App\Router\Router();
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


$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$router->dispatch($path);
