<?php

require_once('hlsrippa.php');
if (isset($_POST['session'])) {
    if (intval($pdo->query('SELECT COUNT(id) FROM bwca_token WHERE cur_token = "' . $_POST['session'] . '"')->fetchColumn()) === 0) {
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
