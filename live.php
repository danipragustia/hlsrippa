<?php

session_start();
require_once 'hlsrippa.php';

if (!isset($_SESSION['user'])) {
    header('Location:' . $config['domain'] . '/login.php');
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
	
	<title>Show - HLSRipple</title>
    </head>
    <body>
	<section class="container py-5">

	    <div class="container">

		<div class="row">

		    <div class="col-lg-6">
			<figure>
			    <blockquote class="blockquote mb-4">
				<h1 class="head-text">Show</h1>
			    </blockquote>
			    <figcaption class="blockquote-footer">
				Hal atau pokok yg ingin dibicarakan / diperlihatkan
			    </figcaption>
			</figure>
		    </div>
		    <div class="col-lg-6 align-self-end">
			<div class="d-grid gap-1 d-md-flex">
			    <a href="<?php echo $config['domain'] . '/live.php'; ?>" class="btn btn-lg btn-secondary"><i class="bi bi-film"></i> Show</a>
			    <a href="<?php echo $config['domain'] . '/logout.php'; ?>" class="btn btn-lg btn-secondary"><i class="bi bi-box-arrow-right"></i> Logout</a>
			</div>
		    </div>
		</div>

		<hr>

		<div id="content-dashboard">

		    <?php
		    $data = $pdo->query('SELECT value FROM wegy_settings WHERE item = "note" LIMIT 1')->fetchColumn();
		    if (!empty($data)) {
			echo '<div class="card my-3">';
			echo '<div class="card-body">';
			echo '<h5 class="card-title"><i class="bi bi-info-circle"></i> Info</h5>';
			echo '<p class="card-text text-muted">' . $data . '</p>';
			echo '</div>';
			echo '</div>';
		    }
		    ?>
		    <div class="table-responsive">
			<table class="table table-bordered">
			    <thead>
				<tr>
				    <td>Nama</td>
				    <td>Aksi</td>
				</tr>
			    </thead>
			    <tbody>
				<?php

				$data = $pdo->query('SELECT * FROM bddv_show')->fetchAll(PDO::FETCH_ASSOC);

				if ($data !== false) {

				    if (count($data) > 0) {
					array_map(function($x) {
					    echo '<tr>';
					    echo '<td>' . $x['nama'] . '</td>';
					    echo '<td><a class="btn btn-sm btn-success" href="show.php?id=' . intval($x['id']) . '">Go to show</a></td>';
					    echo '</tr>';
					}, $data);
				    } else {
					echo '<tr><td rowspan="2">Tidak ada show saat ini</td></tr>';
				    }
				}

				?>
			    </tbody>
			</table>
		    </div>
		</div>

	    </div>	    

	</section>

    </body>

</html>
