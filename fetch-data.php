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
	case 'token':
	    $data = $pdo->query('SELECT * FROM bwca_token ORDER BY id DESC')->fetchAll(PDO::FETCH_ASSOC);
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

    if ($_POST['data'] === 'token') {
	$tmp_array['data'] = array_map(function($x) use ($pdo) {
	    return [
		'id' => $x['id'],
		'token' => $x['token'],
		'show_name' => $pdo->query('SELECT nama FROM bddv_show WHERE id = ' . intval($x['show']) . ' LIMIT 1')->fetchColumn()
	    ];
	}, $data);
	$tmp_array['show'] = array_map(function($x) {
	    return [
		'id' => $x['id'],
		'name' => $x['nama']
	    ];
	}, $pdo->query('SELECT * FROM bddv_show ORDER BY id DESC')->fetchAll(PDO::FETCH_ASSOC));
    }

    return_status($tmp_array);
    exit();
    
}

?>
