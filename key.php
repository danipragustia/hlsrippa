<?php

if (!isset($_GET['id'])) {
    header('HTTP/1.0 404 Not Found');
    exit;    
}

require_once 'hlsrippa.php';
session_start();

if (isset($_GET['token'])) {
    if ($_GET['token'] !== $config['stream_key']) {
	http_response_code(403);
	exit();
    }
} else {
    if (!isset($_SESSION['user'])) {
	if (!isset($_SESSION['login'])) {
	    http_response_code(403);
	    exit();
	}
    }
}

$key = $pdo->query('SELECT `key_auth` FROM bddv_show WHERE id = ' . intval($_GET['id']) . ' LIMIT 1')->fetchColumn();

if (empty($key)) {
    http_response_code(403);
} else {
    die(base64_decode($key));
}
exit();

?>
