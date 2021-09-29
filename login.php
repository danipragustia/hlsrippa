<?php

session_start();
require_once 'hlsrippa.php';

if (isset($_SESSION['login'])) {
    header('Location:' . $config['domain'] . '/dashboard.php');
}

if (isset($_POST['username'], $_POST['password'])) {

    // TODO: Using prepare PDO
    $password_hash = $pdo->query('SELECT password FROM xezc_user WHERE username = "' . $_POST['username'] . '" LIMIT 1')->fetchColumn();

    if (empty($password_hash)) {
	return_status([
	    'status' => 1,
	    'text' => 'Invalid username or password'
	]);
    }
    
    if (password_verify($_POST['password'], $password_hash)) {
	$_SESSION['login'] = true;
	return_status([
	    'status' => 0
	]);
    }

    return_status([
	'status' => 1,
	'text' => 'Invalid username or password'
    ]);
    
}

?>
<html>
    <head>
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link rel="stylesheet" href="assets/bootstrap.min.css">
	
	<title>Login - HLSRipple</title>
    </head>
    <body>
	<section class="h-100">
	    <div class="container h-100">
		<div class="row justify-content-sm-center h-100">
		    <div class="col-xxl-4 col-xl-5 col-lg-5 col-md-7 col-sm-9">
			<div class="card shadow-lg my-5">
			    <div class="card-body p-5">
				<h1 class="fs-4 card-title fw-bold mb-4">Login</h1>
				<div id="status"></div>
				<form method="POST" class="needs-validation" autocomplete="off" id="form_login">
				    <fieldset id="field_login">
					<div class="mb-3">
					    <label class="mb-2 text-muted" for="username">Username</label>
					    <input type="text" class="form-control" name="username" required autofocus>
					</div>

					<div class="mb-3">
					    <div class="mb-2 w-100">
						<label class="text-muted" for="password">Password</label>
					    </div>
					    <input id="password" type="password" class="form-control" name="password" required>
					</div>

					<div class="d-flex align-items-center">
					    <div class="form-check">
						<input type="checkbox" name="remember" id="remember" class="form-check-input">
						<label for="remember" class="form-check-label">Remember Me</label>
					    </div>
					    <button type="submit" class="btn btn-primary ms-auto">Login</button>
					</div>

				    </fieldset>
				</form>
			    </div>
			</div>
			<div class="text-center mt-5 text-muted">
			    Copyright &copy; 2021 &mdash; 0w0 Technik Auflosung
			</div>
		    </div>
		</div>
	    </div>
	</section>

	<script>
	 document.getElementById("form_login").addEventListener("submit", function(event) {

	     event.preventDefault();
	     
	     document.getElementById('field_login').disabled = true;
	     document.getElementById('status').innerHTML = '';
	     document.querySelector('[type="submit"]').innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...';

	     var request = new XMLHttpRequest();
	     request.open('POST', '/login.php');
	     request.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	     request.onload = function() {

		 if (this.readyState === 4) {

		     if (this.status == 200) {

			 var data = JSON.parse(this.response);
			 if (data.status == 0) {

			     window.location.href = '<?php echo $config['domain']; ?>/dashboard.php';
			     
			 } else {

			     document.getElementById('status').innerHTML = '<div class="alert alert-danger" role="alert">' + (typeof data.text !== null ? data.text : 'Invalid login') + '</div>';
			     
			 }
			 
		     } else {

			 document.getElementById('status').innerHTML = '<div class="alert alert-danger" role="alert">There was error when trying to connect to server, please try again later.</div>';
			 
		     }

		     document.getElementById("field_login").disabled = false;
		     document.querySelector('[type="submit"]').innerHTML = 'Login';
		     document.getElementsByName('password')[0].value = '';
		     document.getElementsByName('password')[0].focus();
		     
		 }
		 
	     }

	     request.onerror = function() {

		 document.getElementById('status').innerHTML = '<div class="alert alert-danger" role="alert">There was error when trying generate link, please try again later.</div>';
		 document.getElementById("field_generate").disabled = false;
		 
	     };

	     request.send('username=' + encodeURIComponent(document.getElementsByName('username')[0].value) + '&password=' + encodeURIComponent(document.getElementsByName('password')[0].value));
	     
	 })
	</script>
	
    </body>
</html>
