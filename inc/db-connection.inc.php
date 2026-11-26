<?php

try {
    $pdo = new PDO('mysql:host=localhost;dbname=bookstore;charset=utf8mb4', 'bookstore', '2yX)TwQypzbRqE(z', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

}
catch (PDOException $e) {
    echo 'A problem occured with the database connection...';
    die();
}

return $pdo;
