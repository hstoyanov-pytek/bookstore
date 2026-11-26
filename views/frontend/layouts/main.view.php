<!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="<?php echo e(PROJECT_ROOT) ?>styles/simple.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo e(PROJECT_ROOT) ?>styles/custom.css" />
    <title>Pytek bookstore</title>
    </head>
    <body>
        <header>
        <h1> <a href="/">Pytek Bookstore</a> </h1>
            <p>Local bookstore</p>
            <nav>
                <a href="/">Home</a>
                <a href="/authors">Authors</a>
                <a href="/genres">Genres</a>
                <a href="/languages">Languages</a>
                <a href="/publishers">Publishers</a>
            </nav>
            <hr>
            <?php if(@$_SESSION['permissions'] > 100): ?>
                <a href="/admin">Return to dashboard</a>
            <?php endif; ?>
            <?php if($displayLoginNav):?>
                <a href="/login">Login</a>
                <a href="/register">Register</a>
            <?php else: ?>
                <a href="/logout">Logout</a>
            <?php endif; ?>
            <hr>
            <?php if($this->showFilters):?>
                <?php require_once $_SERVER['DOCUMENT_ROOT'] . FILTERS_PATH; ?>
            <?php endif;?>
        </header>
            <main>
                <?php echo $contents; ?>
            </main>
        <footer>
        <p></p>
        </footer>
    </body>
</html>
