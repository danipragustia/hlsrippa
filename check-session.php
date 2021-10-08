<?php

require_once('hlsrippa.php');
session_start();

if (isset($_POST['session'])) {
    if (intval($pdo->query('SELECT COUNT(id) FROM xezc_user WHERE cur_token = "' . $_POST['session'] . '" AND id = ' . intval($_SESSION['user']))->fetchColumn()) === 0) {
	return_status([
	    'status' => 1,
	    'text' => 'Session hanya berlaku 1 device'
	]);
    } else {
	return_status([
	    'status' => 0
	]);
    }
} else {
    return_status([
	'status' => 1,
	'text' => 'Session tidak tersedia'
    ]);
}

?>
