<?php
    $host = $_ENV['DB_HOST'] ?? 'mysql';
    $port = $_ENV['DB_PORT'] ?? '3306';
    $name = $_ENV['DB_NAME'] ?? 'lacanchadelsaber';
    $user = $_ENV['DB_USER'] ?? 'user';
    $pass = $_ENV['DB_PASS'] ?? 'pass';

    //  Parametros de la conexión a la base de datos
    define('DB_HOST', $host);
    define('DB_NAME', $name);
    define('DB_USER', $user);
    define('DB_PASS', $pass);
    define('DB_CHARSET', 'utf8mb4');
?>