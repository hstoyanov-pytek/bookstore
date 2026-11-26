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
    $authService = $container->get("authService");
    return new \App\Frontend\Controller\PagesController($repo, $authService);
});

$container->bind("adminController", function() use($container)
{
    $bookRepository = $container->get("bookRepository");
    $userRepository = $container->get("userRepository");
    $authService = $container->get("authService");

    return new \App\Admin\Controller\AdminController(bookRepository: $bookRepository, userRepository: $userRepository, authService:$authService);
});

$container->bind("gatewayController", function() use($container)
{
    $authService = $container->get("authService");
    return new \App\Gateway\Controller\GatewayController($authService);
});

$container->bind("bookRepository", function() use($container)
{
    $db = $container->get("database");
    return new \App\Repository\BookRepository($db);
});

$container->bind("userRepository", function() use($container)
{
   $db = $container->get("database");
   return new \App\Repository\UsersRepository($db);
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

$container->bind("router", function()
{
   return new \App\Router\Router();
});

/// ============================END CONTAINER========================================

// create a session to store the role for the users that are not logged in
$authServices = $container->get("authService");
$authServices->ensureSessionUnique();

if(!$authServices->isLoggedIn())
{
    $_SESSION['role'] = "user";
    $_SESSION['permissions'] = 100;
}

$pagesController = $container->get("pagesController");
$gatewayController = $container->get("gatewayController");
$adminController = $container->get("adminController");
$router = $container->get("router");

$router->add("GET", '', [$pagesController, 'displayLandingPage']);
$router->add("GET", '/', [$pagesController, 'displayLandingPage']);
$router->add("GET", '/book', [$pagesController, 'displayBookPage']);
$router->add("GET", '/authors', [$pagesController, 'displayAllAuthors']);
$router->add("GET", '/genres', [$pagesController, 'displayAllGenres']);
$router->add("GET", '/languages', [$pagesController, 'displayAllLanguages']);
$router->add("GET", '/publishers', [$pagesController, 'displayAllPublishers']);

$router->add("GET", '/login', [$gatewayController, 'login']);
$router->add("GET", '/logout', [$gatewayController, 'logout']);
$router->add("GET", '/register', [$gatewayController, 'register']);
$router->add("POST", '/register', [$gatewayController, 'register']);
$router->add("POST", '/login', [$gatewayController, 'login']);

if($authServices->isLoggedIn() && ($_SESSION['role'] === "admin" || $_SESSION['role'] === "editor"))
{
    $permissions = $_SESSION['permissions'];

    if($permissions >= 110)
    {
        $router->add("GET", '/admin', [$adminController, 'displayAdminDashboard']);
        $router->add("GET", '/admin/edit', [$adminController, 'editBookEntry']);
        $router->add("GET", '/admin/create', [$adminController, 'createBookEntry']);
        $router->add("GET", '/admin/authors', [$adminController, 'displayAllAuthors']);
        $router->add("GET", '/admin/genres', [$adminController, 'displayAllGenres']);
        $router->add("GET", '/admin/languages', [$adminController, 'displayAllLanguages']);
        $router->add("GET", '/admin/publishers', [$adminController, 'displayAllPublishers']);
        $router->add("POST", '/admin/edit', [$adminController, 'editBookEntry']);
        $router->add("POST", '/admin/create', [$adminController, 'createBookEntry']);
    }

    if($_SESSION['permissions'] === 111)
    {
        $router->add("POST", '/admin/delete', [$adminController, 'deleteBookEntryById']);
        $router->add("GET", '/admin/users', [$adminController, 'displayAllUsers']);
        $router->add("GET", '/admin/users/edit', [$adminController, 'editUser']);
        $router->add("POST", '/admin/users/edit', [$adminController, 'editUser']);
        $router->add("POST", '/admin/users/delete', [$adminController, 'deleteUser']);
    }
}
else
{
    http_response_code(404);
}


$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$router->dispatch($path);
