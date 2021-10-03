<?php

session_start();

if (!isset($_SESSION['login'])) {
    http_response_code(403);
}

if (isset($_POST['data'])) {

    require_once 'hlsrippa.php';
    
    switch($_POST['data']) {
	case 'show':
	    $data = $pdo->query('SELECT * FROM bddv_show ORDER BY id DESC')->fetchAll(PDO::FETCH_ASSOC);
	    break;
	case 'user':
	    $data = $pdo->query('SELECT * FROM xezc_user ORDER BY id DESC')->fetchAll(PDO::FETCH_ASSOC);
	    break;
	default:
	    http_response_code(403);
	    exit();
	    break;
    }

    $tmp_array = [];

    if (!empty($data)) {

	$tmp_array['status'] = 0;
	$tmp_array['data'] = $data;
	
    } else {

	$tmp_array['status'] = 1;
	$tmp_array['text'] = 'Data Kosong';
    }

    return_status($tmp_array);
    exit();
    
}

?>
