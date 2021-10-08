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

// Clean some stuff
// For some reason this best way
array_map(function($x) {

    // Check file was 15 secs ago (best bet)
    if (is_file($x)) {
	if (time() - filemtime($x) >= 15) {
	    unlink($x);
	}
    }
    
}, glob('ts/*'));


if (isset($_GET['data'])) {
    
    $f_ts = 'ts/' . hash('sha256', base64_decode($_GET['data']), false);
    
    if (!file_exists($f_ts)) {

	// Ugly solution
	// We save it on temporary first, and if someone come first after us it doesnt broke our file
	$f_tmp = bin2hex(random_bytes(20));
	$tmp_path = 'tmp/' . $f_tmp;
	$fp = fopen($tmp_path, 'w+');
	if ($fp === FALSE) {
	    http_response_code(403);
	    exit;
	}
	
	$ch = curl_init('https://' . base64_decode($_GET['data']));
	curl_setopt_array($ch, [
	    CURLOPT_CONNECTTIMEOUT => 0,
	    CURLOPT_TIMEOUT => 1000, // 10 sec is safe bet
	    CURLOPT_RETURNTRANSFER => 1,
	    CURLOPT_FOLLOWLOCATION => 1,
	    CURLOPT_FRESH_CONNECT => 1,
	    CURLOPT_HEADER => 0,
	    CURLOPT_FILE => $fp
	]);

	//curl_setopt($ch, CURLOPT_WRITEFUNCTION, function($curl, $body) {
	//echo $body;
	//return strlen($body);
	//});

	curl_exec($ch);
	curl_close($ch);
	fclose($fp);

	// Check if file was correct
	if (file_exists($tmp_path)) {
	    // Check if file size wasn't zero
	    if (filesize($tmp_path) > 0) {
		if (copy($tmp_path, $f_ts)) {
		    
		    unlink($tmp_path);

		    header('Connection: keep-alive');
		    header('Content-Type:video/MP2T');
		    header('Content-Disposition: attachment; filename="0.ts"');
		    
		    require_once 'VideoStream.php';		    
		    $stream = new VideoStream($f_ts);
		    $stream->start();
		    
		}
	    } else {
		// Cleanup and exit fast possible
		unlink($tmp_path);
		exit();
		
	    }
	}


	
    } else {

	header('Connection: keep-alive');
	header('Content-Type:video/MP2T');
	header('Content-Disposition: attachment; filename="0.ts"');

	require_once 'VideoStream.php';
	$stream = new VideoStream($f_ts);
	$stream->start();
	
    }

}

?>
