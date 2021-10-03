<?php

session_start();
require_once 'hlsrippa.php';

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

if (isset($_GET['data'])) {

    $ch = curl_init('https://' . base64_decode($_GET['data']));
    curl_setopt_array($ch, [
	CURLOPT_CONNECTTIMEOUT => 0,
	CURLOPT_TIMEOUT => 1000,
	CURLOPT_RETURNTRANSFER => 1,
	CURLOPT_FOLLOWLOCATION => 1,
	CURLOPT_FRESH_CONNECT => 1
    ]);

    curl_setopt($ch, CURLOPT_WRITEFUNCTION, function($curl, $body) {
	echo $body;
	return strlen($body);
    });

    header('Connection: keep-alive');
    header('Content-Type:video/MP2T');
    header('Content-Disposition: attachment; filename="0.ts"');

    curl_exec($ch);
    
}

?>
