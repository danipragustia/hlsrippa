<?php

if (!isset($_GET['id'])) {
    header('HTTP/1.0 404 Not Found');
    exit;    
}

session_start();
if (!isset($_SESSION['login']) && !isset($_SESSION['token'])) {
    http_response_code(403);
}

require_once 'hlsrippa.php';

$key = $pdo->query('SELECT `key_auth` FROM bddv_show WHERE id = ' . intval($_GET['id']) . ' LIMIT 1')->fetchColumn();

if (empty($key)) {
    http_response_code(403);
} else {
    die(base64_decode($key));
}
exit();

?>
