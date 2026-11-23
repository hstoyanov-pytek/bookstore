<!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="<?php echo e(PROJECT_ROOT) ?>styles/simple.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo e(PROJECT_ROOT) ?>styles/custom.css" />
    <title>Bookstore Admin Panel</title>
    </head>
    <body>
        <header>
        <h1> <a href="/admin">Pytek Bookstore</a> </h1>
            <p>Local bookstore</p>
            <nav>
                <a href="/admin">Home</a>
                <a href="/admin/authors">Authors</a>
                <a href="/admin/genres">Genres</a>
                <a href="/admin/languages">Languages</a>
                <a href="/admin/publishers">Publishers</a>
                <a href="/admin/users">Users</a>
            </nav>
            <a href="/logout">Logout</a>
        </header>
            <main>
                <?php echo $contents; ?>
            </main>
        <footer>
        <p></p>
        </footer>
    </body>
</html>
