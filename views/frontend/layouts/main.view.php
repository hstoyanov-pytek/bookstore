<!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="./styles/simple.css" />
    <link rel="stylesheet" type="text/css" href="./styles/custom.css" />
    <title>CMS Project</title>
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

            <?php if($displayLoginNav):?>
                <a href="/login">Login</a>
                <a href="/register">Register</a>
            <?php else: ?>
                <a href="/logout">Logout</a>
            <?php endif; ?>
        </header>
            <main>
                <?php echo $contents; ?>
            </main>
        <footer>
        <p></p>
        </footer>
    </body>
</html>
