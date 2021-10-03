<?php

session_start();

if (!isset($_SESSION['login'])) {
    http_response_code(403);
}

if (isset($_POST['id'])) {

    require_once 'hlsrippa.php';

    if (intval($_POST['id']) >= 0) {
	if ($pdo->query('DELETE FROM xezc_user WHERE id = ' . intval($_POST['id']) . ' LIMIT 1')) {
	    
	    $pdo->query('DELETE FROM bwca_token WHERE user_id = ' . intval($_POST['id']));
	    
	    return_status([
		'status' => 0,
		'text' => 'Sukses hapus user'
	    ]);
	} else {
	    return_status([
		'status' => 1,
		'text' => 'Invalid ID user'
	    ]);
	}
    } else {
	http_response_code(403);
    }
    
}

?>
