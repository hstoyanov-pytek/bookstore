<!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../styles/simple.css" />
    <link rel="stylesheet" type="text/css" href="../styles/custom.css" />
    <title>Bookstore Editor Panel</title>
    </head>
    <body>
        <header>
        <h1> <a href="/editor">Pytek Bookstore</a> </h1>
            <p>Welcome Editor <?php echo e("name");?></p>
            <nav>
                <a href="/editor">Home</a>
                <a href="/editor/authors">Authors</a>
                <a href="/editor/genres">Genres</a>
                <a href="/editor/languages">Languages</a>
                <a href="/editor/publishers">Publishers</a>
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
