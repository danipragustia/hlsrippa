<?php

session_start();
require_once 'hlsrippa.php';

if (!isset($_SESSION['user'])) {
    if (!isset($_SESSION['login'])) {
	header('Location:' . $config['domain'] . '/login.php');
    }
}

if (isset($_GET['id'])) {

    $video_data = $pdo->query('SELECT * FROM bddv_show WHERE id = ' . intval($_GET['id']) . ' LIMIT 1')->fetch(PDO::FETCH_ASSOC);
    $source = $config['domain'] . '/playlist.php?id=' . intval($_GET['id']);
    
} else {
    header('Location:' . $config['domain'] . '/invalid.php');
}

if (!isset($video_data['nama'])) {
    header('Location:' . $config['domain'] . '/invalid.php');
}

// Update Token Session
if (isset($_SESSION['user'])) {
    $token_session = bin2hex(random_bytes(20));
    $pdo->query('UPDATE xezc_user SET cur_token = "' . $token_session . '" WHERE id = ' . intval($_SESSION['user']) . ' LIMIT 1');
    $_SESSION['token'] = $token_session;
}

?>
<!DOCTYPE html>
<html>
    <head>

	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="robots" content="noindex">
	<meta name="referrer" content="never" />
	<meta name="referrer" content="no-referrer" />

	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@700&display=swap" rel="stylesheet">

	<link rel="stylesheet" href="assets/main.css">
	<link rel="stylesheet" href="assets/bootstrap.min.css">
	<link rel="stylesheet" href="assets/bootstrap-icons.css">
	
	<link rel="stylesheet" href="https://cdn.plyr.io/3.6.8/plyr.css">

	<title><?php echo $video_data['nama']; ?> - HLSRipple</title>

    </head>
    <body>

	<div class="container py-3">

	    <h1><?php echo $video_data['nama']; ?></h1>
	    <hr>
	    <div id="status" class="py-2"></div>
	    <video controls crossorigin playsinline id="tewi-player"></video>
	    
	</div>

	<script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
	<script src="https://cdn.plyr.io/3.6.8/plyr.js"></script>
	<script>

	 <?php if (isset($_SESSION['user'])) { ?>

	 var tolerance = 0;
	 fetch_device();

	 setInterval(function(){
	     fetch_device();
	 }, 3000);

	 function show_alert(color, text) {
	     document.getElementById('status').innerHTML = '<div class="alert alert-' + color + '" role="alert">' + text + '</div>';
	 }

	 function fetch_device() {

	     var request = new XMLHttpRequest();
	     request.open('POST', '/check-session.php');
	     request.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

	     request.onload = function() {

		 if (this.readyState === 4) {

		     if (this.status == 200) {

			 var data = JSON.parse(this.response);
			 if (data.status == 1) {
			     document.getElementById('tewi-player').remove();
			     show_alert('danger', data.text);
			 }
			 
		     } else {

			 if (tolerance === 5) {
			     document.getElementById('tewi-player').remove();
			     show_alert('danger', 'There was error when trying get session device, please try again later.');
			 } else {
			     tolerance++;
			 }
			 
		     }
		     
		 }
		 
	     }

	     request.onerror = function() {
		 if (tolerance === 5) {
		     show_alert('danger', 'There was error when trying get session device, please try again later.');
		     document.getElementById('tewi-player').remove();
		 } else {
		     tolerance++;
		 }
	     };
	     
	     request.send('session=<?php echo $token_session; ?>');
	     
	 }
	 
	 <?php } ?>

	 document.addEventListener('DOMContentLoaded', () => {
	     const source = '<?php echo $source; ?>';
	     const video = document.querySelector('#tewi-player');
	     
	     const player = new Plyr(video);
	     
	     if (!Hls.isSupported()) {
		 video.src = source;
	     } else {
		 const hls = new Hls({
		     maxBufferSize: 1 * 1000 * 1000,
		     backBufferLength: 0
		 });
		 hls.loadSource(source);
		 hls.attachMedia(video);
		 window.hls = hls;
	     }
	     
	 });
	 
	</script>
    </body>
</html>
