<?php

session_start();
require_once 'hlsrippa.php';

if (!isset($_SESSION['login'])) {
    header('Location:' . $config['domain'] . '/login.php');
}

// Dashboard CRUD
if (isset($_GET['page'])) {

    // Show
    
    if (isset($_POST['name'], $_POST['m3u8'], $_POST['key']) && $_GET['page'] === 'show') {

	$input = $pdo->prepare('INSERT INTO bddv_show (nama, m3u8, key_auth) VALUES (?, ?, ?)')->execute([
	    $_POST['name'],
	    $_POST['m3u8'],
	    $_POST['key'],
	]);

	if ($input) {

	    return_status([
		'status' => 0,
		'text' => 'Sukses masukkan show baru'
	    ]);
	    
	} else {

	    return_status([
		'status' => 1,
		'text' => 'Invalid data show'
	    ]);
	    
	}
	
    }

    // Token
    
    if (isset($_POST['id_show']) && $_GET['page'] === 'token') {

	if (intval($pdo->query('SELECT COUNT(ID) FROM bddv_show WHERE id = ' . intval($_POST['id_show']) . ' LIMIT 1')->fetchColumn()) === 1) {
	    $tmp_token = bin2hex(random_bytes(20));
	    $token = $pdo->prepare('INSERT INTO bwca_token (`token`, `show`) VALUES (?, ?)')->execute([
		$tmp_token,
		intval($_POST['id_show'])
	    ]);

	    if ($token) {
		return_status([
		    'status' => 0,
		    'code' => $tmp_token
		]);
	    } else { 
		return_status([
		    'status' => 1,
		    'text' => 'Invalid id show'
		]);
	    }
	    
	} else {
	    return_status([
		'status' => 1,
		'text' => 'Invalid id show'
	    ]);
	}
	
    }
    
}

?>
<html>
    <head>
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@700&display=swap" rel="stylesheet">

	<link rel="stylesheet" href="assets/bootstrap.min.css">
	<link rel="stylesheet" href="assets/bootstrap-icons.css">
	<style>
	 body {
	     font-size:18px;
	     font-family: -apple-system,BlinkMacSystemFont,Helvetica Neue,Eina 04,system-ui,Segoe UI,Roboto,Arial,Noto Sans,sans-serif,Apple Color Emoji,Segoe UI Emoji,Segoe UI Symbol,Noto Color Emoji;
	 }
	 h1, h2,h3, h4, h5 {
	     font-family: 'DM Sans', sans-serif;
	 }
	</style>
	
	<title>Dashboard - HLSRipple</title>
    </head>
    <body>
	<section class="container py-5">

	    <div class="container">

		<div class="row">

		    <div class="col-lg-6">
			<figure>
			    <blockquote class="blockquote mb-4">
				<h1 class="head-text">Dashboard</h1>
			    </blockquote>
			    <figcaption class="blockquote-footer">
				Biasa disebut Dashboard, Control Panel, atau halaman utama.
			    </figcaption>
			</figure>
		    </div>

		    <div class="col-lg-6 align-self-end">

			<div class="d-grid gap-1 d-md-flex">
			    <a href="<?php echo $config['domain'] . '/dashboard.php?page=show'; ?>" class="btn btn-lg btn-secondary"><i class="bi bi-film"></i> Show</button>
				<a href="<?php echo $config['domain'] . '/dashboard.php?page=token'; ?>" class="btn btn-lg btn-secondary"><i class="bi bi-receipt"></i> Token</button>
				    <a href="<?php echo $config['domain'] . '/logout.php'; ?>" class="btn btn-lg btn-secondary"><i class="bi bi-box-arrow-right"></i> Logout</a>
			</div>
			
		    </div>


		</div>

		<hr>

		<div id="content-dashboard">

		    <?php

		    if (isset($_GET['page'])) {
			switch($_GET['page']) {
			    case 'show':
				require 'view/show.php';
				break;
			    case 'token':
				require 'view/token.php';
				break;
			    default:
				require 'view/show.php';
				break;			    
			}
		    } else {
			require 'view/show.php';
		    }
		    
		    ?>
		    
		</div>
		
	    </div>
	    
	</section>
	
    </body>
</html>
