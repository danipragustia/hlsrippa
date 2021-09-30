<?php

session_start();
require_once 'hlsrippa.php';

if (isset($_GET['id'])) {

    if (!isset($_SESSION['login'])) {
	header('Location:/invalid.php');
    }

    $video_data = $pdo->query('SELECT * FROM bddv_show WHERE id = ' . intval($_GET['id']) . ' LIMIT 1')->fetch(PDO::FETCH_ASSOC);

    $source = json_encode([
	'type' => 'hls',
	'file' => $config['domain'] . '/playlist.php?id=' . intval($_GET['id'])
    ]);
    
} else {

    if (isset($_GET['token'])) {
	
	$check_data = $pdo->query('SELECT * FROM bwca_token WHERE token = "' . $_GET['token'] . '" LIMIT 1')->fetch(PDO::FETCH_ASSOC);
	
	if (empty($check_data)) {
	    header('Location:/invalid.php');
	} else {
	    
	    $video_data = $pdo->query('SELECT * FROM bddv_show WHERE id = ' . intval($check_data['show']) . ' LIMIT 1')->fetch(PDO::FETCH_ASSOC);
	    $source = json_encode([
		'type' => 'hls',
		'file' => $config['domain'] . '/playlist.php?id=' . intval($check_data['show'])
	    ]);
	    
	}
	
    } else {
	
	if (!isset($_SESSION['login'])) {
	    header('Location:/invalid.php');
	}
	
    }

}

if (empty($video_data)) {
    header('Location:/invalid.php');
}

// Update Token Session
$token_session = bin2hex(random_bytes(20));
$pdo->query('UPDATE bwca_token SET cur_token = "' . $token_session . '" WHERE id = ' . intval($check_data['id']) . ' LIMIT 1');
$_SESSION['token'] = $token_session;

?>
<!DOCTYPE html>
<html>
    <head>
	<title><?php echo $video_data['nama']; ?> - HLSRipple</title>

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

	<script type="text/javascript" src="https://ssl.p.jwpcdn.com/player/v/8.8.6/jwplayer.js"></script>
	<script type="text/javascript">jwplayer.key="64HPbvSQorQcd52B8XFuhMtEoitbvY/EXJmMBfKcXZQU2Rnn";</script>

	<script>
	 #video-jwplayer_wrapper {
	     position: relative;
	     padding-bottom: 56.25%; /* 16:9 format */
	     padding-top: 30px;
	     height: 0;
	     overflow: hidden;
	 }
	 #video-jwplayer_wrapper iframe, #video-jwplayer_wrapper object, #video-jwplayer_wrapper embed {
	     position: absolute;
	     top: 0;
	     left: 0;
	     width: 100%;
	     height: 100%;
	 }
	</script>

    </head>
    <body>

	<div class="container py-3">

	    <h1><?php echo $video_data['nama']; ?></h1>
	    <hr>
	    <div id="status" class="py-2"></div>
	    <div id="tewi-player"></div>
	    
	</div>

	<script>

	 <?php if (!isset($_SESSION['login'])) { ?>

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
	 
	 var player=jwplayer("tewi-player");
	 player.setup({
	     sources: <?php echo $source; ?>,
	     cast:{},
	     width: "100%",
	     aspectratio:"16:9",
	     startparam:"start",
	     primary:"html5",
	     autostart:false,
	     preload:"auto"
	 });
	 
	 player.on("setupError",function() {
	     swal("Server Error!", "Please contact us to fix it asap. Thank you!","error")
	 });
	 
	 player.on("error",function() {
	     swal("Server Error!", "Please contact us to fix it asap. Thank you!","error")
	 });

	 
	</script>
    </body>
</html>
