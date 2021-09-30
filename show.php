<?php

session_start();
require_once 'hlsrippa.php';

if (!isset($_SESSION['login'])) {
    header('Location:' . $config['domain'] . '/login.php');
}

if (isset($_GET['id'])) {

    $video_data = $pdo->query('SELECT * FROM bddv_show WHERE id = ' . intval($_GET['id']) . ' LIMIT 1')->fetch(PDO::FETCH_ASSOC);

    if (empty($video_data)) {
	header('Location:/invalid.php');
	exit();
    }

    $source = json_encode([
	'type' => 'hls',
	'file' => $config['domain'] . '/playlist.php?id=' . intval($_GET['id'])
    ]);
    
}

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
	    <div id="tewi-player"></div>
	    
	</div>

	<script>
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
	 }
	 );
	 
	 player.on("error",function() {
	     swal("Server Error!", "Please contact us to fix it asap. Thank you!","error")
	 }
	 );
	</script>
    </body>
</html>
