<?php

$config = [
    'domain' => 'http://localhost',
    'stream_key' => 'ayam'
];

try {
    $pdo = new PDO('mysql:host=localhost;dbname=hlsrippa;charset=utf8', 'root', 'jaja');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
} catch (PDOException $exception) {
    // If there is an error with the connection, stop the script and display the error.
    header('HTTP/1.1 503 Service Temporarily Unavailable');
}

function return_status(array $status, int $http_code = 200) {
    http_response_code($http_code);
    header('Content-type: application/json');
    exit(json_encode($status));
}

?>
