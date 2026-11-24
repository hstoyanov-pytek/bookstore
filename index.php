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

$container->bind("editorController", function() use($container)
{
    $bookRepository = $container->get("bookRepository");
    $authService = $container->get("authService");
    return new \App\Editor\Controller\EditorController($bookRepository, $authService);
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
}

$pagesController = $container->get("pagesController");
$gatewayController = $container->get("gatewayController");
$adminController = $container->get("adminController");
$editorController = $container->get("editorController");
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

if($authServices->isLoggedIn() && $_SESSION['role'] === "admin")
{
    $router->add("GET", '/admin', [$adminController, 'displayAdminDashboard']);
    $router->add("GET", '/admin/edit', [$adminController, 'editBookEntry']);
    $router->add("GET", '/admin/create', [$adminController, 'createBookEntry']);
    $router->add("GET", '/admin/authors', [$adminController, 'displayAllAuthors']);
    $router->add("GET", '/admin/genres', [$adminController, 'displayAllGenres']);
    $router->add("GET", '/admin/languages', [$adminController, 'displayAllLanguages']);
    $router->add("GET", '/admin/publishers', [$adminController, 'displayAllPublishers']);
    $router->add("GET", '/admin/users', [$adminController, 'displayAllUsers']);
    $router->add("GET", '/admin/users/edit', [$adminController, 'editUser']);

    $router->add("POST", '/admin/users/edit', [$adminController, 'editUser']);
    $router->add("POST", '/admin/users/delete', [$adminController, 'deleteUser']);
    $router->add("POST", '/admin/edit', [$adminController, 'editBookEntry']);
    $router->add("POST", '/admin/delete', [$adminController, 'deleteBookEntryById']);
    $router->add("POST", '/admin/create', [$adminController, 'createBookEntry']);
}

if($authServices->isLoggedIn() && $_SESSION['role'] === "editor")
{
    $router->add("GET", '/editor', [$editorController, 'displayEditorDashboard']);
    $router->add("GET", '/editor/edit', [$editorController, 'editBookEntry']);
    $router->add("GET", '/editor/create', [$editorController, 'createBookEntry']);
    $router->add("GET", '/editor/authors', [$editorController, 'displayAllAuthors']);
    $router->add("GET", '/editor/genres', [$editorController, 'displayAllGenres']);
    $router->add("GET", '/editor/languages', [$editorController, 'displayAllLanguages']);
    $router->add("GET", '/editor/publishers', [$editorController, 'displayAllPublishers']);
    $router->add("POST", '/editor/edit', [$editorController, 'editBookEntry']);
    $router->add("POST", '/editor/delete', [$editorController, 'deleteBookEntryById']);
    $router->add("POST", '/editor/create', [$editorController, 'createBookEntry']);
}

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$router->dispatch($path);
