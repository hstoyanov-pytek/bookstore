<?php
require __DIR__ . '/inc/all.inc.php';
use \App\Utilities\Database\Database;
use App\Utilities\WhereOp;

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

$route = @(string)($_GET['route'] ?? 'pages');

//Database::$insert->insertInto("book", ['title', 'publisher_id, year_published'])->values(array(['Book1', 2, 2011],['Book2', 1, 2102],['Book3'], ['Book4']))->getResults();
//Database::$insert->insertInto("book", ['title'])->values(array(['Book3', 1 , 1231]))->getResults();
//Database::$select->select("")->from("book")->getResults(\App\Model\BookModel::class);
//Database::$update->update("book")->set(array([ 'col' => 'publisher_id', 'value'=> '2']))->where("id = 31")->getResults();
//Database::$delete->delete()->from("book")->where("id = 31")->getResults();

if($route === 'pages')
{
    $page = @(string)($_GET['page'] ?? 'index');
    $pagesController = $container->get("pagesController");

    if($page === "book")
    {
        $id = @(int)($_GET['book_id'] ?? 1);
        $pagesController->displayBookPage($id);
    }
    else if($page === "edit")
    {
        $id = @(int)($_GET['book_id'] ?? 1);
        $pagesController->editBookEntry($id);
    }
    else if($page === "create")
    {
        $pagesController->createBookEntry();
    }
    else if($page === "delete")
    {
        $id = @(int)($_POST['id']);
        $pagesController->deleteBookEntryById($id);
    }
    else
    {
        $pagesController->displayLandingPage();
    }
}