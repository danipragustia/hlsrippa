<?php

session_start();

if (!isset($_SESSION['login'])) {
    http_response_code(403);
}

if (isset($_POST['id'])) {

    require_once 'hlsrippa.php';

    if (intval($_POST['id']) >= 0) {
	if ($pdo->query('DELETE FROM bwca_token WHERE id = ' . intval($_POST['id']) . ' LIMIT 1')) {
	    return_status([
		'status' => 0,
		'text' => 'Sukses hapus data token'
	    ]);
	} else {
	    return_status([
		'status' => 1,
		'text' => 'Invalid ID token'
	    ]);
	}
    } else {
	http_response_code(403);
    }
    
}

?>
