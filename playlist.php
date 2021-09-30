<?php

if (!isset($_GET['id'])) {
    header('HTTP/1.0 404 Not Found');
    exit;    
}

require_once 'hlsrippa.php';
session_start();

if (!isset($_SESSION['login']) && !isset($_SESSION['token'])) {
    http_response_code(403);
    exit();
}

if (intval($pdo->query('SELECT COUNT(id) FROM bwca_token WHERE cur_token = "' . $_SESSION['token'] . '"')->fetchColumn()) === 0) {
    http_response_code(403);
    exit();
}

$data = $pdo->query('SELECT * FROM bddv_show WHERE id = ' . intval($_GET['id']) . ' LIMIT 1')->fetch(PDO::FETCH_ASSOC);

if (empty($data)) {
    http_response_code(403);
    exit();
}

$reg_playlist = '/#EXT-X-STREAM-INF:(.*)[\r\n]+(.*)[\r\n]+/m';
$reg_ts = '/#EXTINF:(.*)[\r\n]+(.*)[\r\n]+/m';
$reg_key = '/X-KEY:METHOD=(.*),URI="(.*)"/m';

$url = $data['m3u8'];

$ch = curl_init($url);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => 1
]);
$result = curl_exec($ch);
curl_close($ch);

preg_match_all($reg_playlist, $result, $playlist);


if (!count($playlist[1])) {
    preg_match_all($reg_ts, $result, $playlist);
}

if (count($playlist[1]) == count($playlist[2])) {

    $arr = [];
    $data_url = [];
    
    // remove https / http first
    $tempo = str_replace('http://', '', $url);
    $tempo = str_replace('https://', '', $tempo);
    
    $data_url = parse_url('https://' . $tempo);
    
    for($i = 0; $i < count($playlist[1]); $i++) {

	$tmp2 = $playlist[2][$i];

	// Get Path before m3u8
	$path = explode('/', $data_url['path']);
	
	unset($path[count($path) - 1]);
	$path = implode('/', $path);

	$target_url = explode('/', $data_url['host'] . $path . '/' . $tmp2);

	for($ii = 0; $ii < count($target_url); $ii++) {

	    if ($target_url[$ii] == '..') {
		unset($target_url[$ii - 1]);
		unset($target_url[$ii]);
	    }
	    
	}

	$target_url = implode('/', $target_url);

	array_push($arr, [
	    'direct' => $tmp2,
	    'proxy' => $config['domain'] . '/' . (strpos($tmp2, '.m3u8') !== false ? 'playlist' : 'media') . '.php?data=' . base64_encode($target_url)
	]);
	
    }

    // We start replace the string from m3u8
    
    $data_out = $result;
    foreach($arr as $x) {

	$data_out = str_replace($x['direct'], $x['proxy'], $data_out);
	
    }

    // Replace Key URL

    preg_match_all($reg_key, $data_out, $key_preg);

    if (isset($key_preg[2][0])) {
	
	$data_out = str_replace($key_preg[2], $config['domain'] . '/key.php?id=' . intval($_GET['id']), $data_out);

    }

    die($data_out);
    exit;

} else {

    header('HTTP/1.0 404 Not Found');
    exit;
    
}

?>
