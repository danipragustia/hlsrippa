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
	    (empty($_POST['key']) ? NULL : $_POST['key']),
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

    // Accounts
    
    if (isset($_POST['username'], $_POST['password']) && $_GET['page'] === 'user') {

	if (intval($pdo->query('SELECT COUNT(ID) FROM xezc_user WHERE username = "' . htmlspecialchars($_POST['username'], ENT_QUOTES | ENT_HTML401) . '" LIMIT 1')->fetchColumn()) === 0) {

	    $register = $pdo->prepare('INSERT INTO xezc_user (username, password) VALUES (?, ?)')->execute([
		htmlspecialchars($_POST['username'], ENT_QUOTES | ENT_HTML401),
		password_hash(htmlspecialchars($_POST['password'], ENT_QUOTES | ENT_HTML401), PASSWORD_DEFAULT)
	    ]);

	    if ($register) {
		return_status([
		    'status' => 0
		]);
	    } else { 
		return_status([
		    'status' => 1,
		    'text' => 'Failed to register account'
		]);
	    }
	    
	} else {
	    return_status([
		'status' => 1,
		'text' => 'Failed to register account'
	    ]);
	}
	
    }

    // Settings
    if (isset($_POST['note']) && $_GET['page'] === 'settings') {
	$update = $pdo->prepare('UPDATE wegy_settings SET value = ? WHERE item = ? LIMIT 1')->execute([
	    $_POST['note'],
	    'note'
	]);
	
	if ($update) {
	    $_SESSION['status'] = 'Success save settings';
	} else {
	    $_SESSION['status'] = 'Failed when trying save settings';
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
	<link rel="stylesheet" href="assets/main.css">
	
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
			    <a href="<?php echo $config['domain'] . '/dashboard.php?page=show'; ?>" class="btn btn-lg btn-secondary"><i class="bi bi-film"></i> Show</a>
			    <a href="<?php echo $config['domain'] . '/dashboard.php?page=user'; ?>" class="btn btn-lg btn-secondary"><i class="bi bi-people"></i> Accounts</a>
			    <a href="<?php echo $config['domain'] . '/dashboard.php?page=settings'; ?>" class="btn btn-lg btn-secondary"><i class="bi bi-gear"></i> Settings</a>
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
			    case 'user':
				require 'view/user.php';
				break;
			    case 'settings':
				require 'view/settings.php';
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
