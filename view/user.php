<form id="form_generate_register">
    <fieldset id="field_generate_register">
	<div class="form-group mb-3">
	    <div class="mb-2 w-100"><label class="text-muted">Username</label></div>
	    <input type="text" class="form-control" name="username" id="username" placeholder="Username">
	</div>
	<div class="form-group mb-3">
	    <div class="mb-2 w-100"><label class="text-muted">Password</label></div>
	    <input type="password" class="form-control" name="password" id="password" placeholder="Password">
	</div>

	<div class="d-grid gap-1 d-md-flex pb-2">
	    <button type="submit" class="btn btn-primary" id="button-generate-register"><i class="bi bi-plus-lg"></i> Register</button>
	    <button type="button" class="btn btn-success" id="button-refresh-register"><i class="bi bi-arrow-repeat"></i> Refresh Data</button>
	</div>
    </fieldset>
</form>

<div id="status" class="my-2"></div>

<table class="table table-bordered">
    <thead>
	<tr>
	    <td>ID</td>
	    <td>Username</td>
	    <td>Aksi</td>
	</tr>
    </thead>
    <tbody id="table_body">
    </tbody>
</table>
<script>

 refresh_data();
 
 function refresh_data() {

     document.getElementById('table_body').innerHTML = '<tr><td colspan="4"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span></td></tr>';

     document.getElementById("field_generate_register").disabled = true; 
     document.getElementById('button-generate-register').disabled = false;

     var request = new XMLHttpRequest();
     request.open('POST', '/fetch-data.php');
     request.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

     request.onload = function() {

	 if (this.readyState === 4) {

	     if (this.status == 200) {

		 var data = JSON.parse(this.response);
		 if (data.status == 0) {

		     document.getElementById('table_body').innerHTML = '';
		     data.data.forEach(function (ele) {

			 document.getElementById('table_body').innerHTML += '<tr><td>' + ele.id + '</td><td>' + ele.username + '</td><td><button type="button" class="btn btn-sm btn-danger" data-id="' + ele.id  + '" name="btnHapus">Hapus</button></td></tr>';
			 
		     })

		     document.getElementsByName('btnHapus').forEach(function (ele) {
			 if (typeof ele.getAttribute('data-id') !== undefined) {
			     ele.addEventListener('click', function(event) {
				 event.preventDefault();
				 var request = new XMLHttpRequest();
				 request.open('POST', '/delete-user.php');
				 request.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
				 request.onload = function() {

				     if (this.readyState === 4) {
					 
					 var data = JSON.parse(this.response);
					 
					 if (this.status == 200 && data.status == 0) {
					     show_alert('success', (typeof data.text !== null ? data.text : 'Success remove account'));
					     refresh_data();
					 } else {
					     show_alert('danger', 'There was error when trying to connect to server, please try again later.');
					 }
					 
				     }
				 }

				 request.onerror = function() {
				     show_alert('danger', 'There was error when trying fetch user, please try again later.');
				     document.getElementById("field_generate_register").disabled = false;
				 };

				 request.send('id=' + parseInt(this.getAttribute('data-id')));
				 
			     });
			 }
		     });
		     
		 } else {
		     document.getElementById('table_body').innerHTML = '';
		     
		     document.querySelector('[type="submit"]').innerHTML = '<i class="bi bi-plus-lg"></i> Register';
		     show_alert('danger', (typeof data.text !== null ? data.text : 'Failed to fetch user data'));
		     return false;
		 }
		 
	     } else {
		 show_alert('danger', 'There was error when trying to connect to server, please try again later.');
	     }

	     
	     document.getElementById("field_generate_register").disabled = false;
	     document.querySelector('[type="submit"]').innerHTML = '<i class="bi bi-plus-lg"></i> Register';
	     
	 }
	 
     }

     request.onerror = function() {
	 show_alert('danger', 'There was error when trying register account, please try again later.');
	 document.getElementById("field_generate_register").disabled = false;
     };

     request.send('data=user');
     
 }

 function show_alert(color, text) {
     document.getElementById('status').innerHTML = '<div class="alert alert-' + color + '" role="alert">' + text + '</div>';
 }

 document.getElementById('button-refresh-register').addEventListener('click', function(event) {
     event.preventDefault();
     refresh_data();
 });

 document.getElementById("form_generate_register").addEventListener("submit", function(event) {
     
     event.preventDefault();
     document.getElementById('field_generate_register').disabled = true;
     document.getElementById('status').innerHTML = '';
     document.querySelector('[type="submit"]').innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...';

     var request = new XMLHttpRequest();
     request.open('POST', '/dashboard.php?page=user');
     request.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

     request.onload = function() {

	 if (this.readyState === 4) {

	     if (this.status == 200) {

		 var data = JSON.parse(this.response);
		 if (data.status == 0) {
		     show_alert('success', 'Success register account');
		     refresh_data();
		 } else {
		     show_alert('danger', (typeof data.text !== null ? data.text : 'Failed to register account'));
		 }
		 
	     } else {
		 show_alert('danger', 'There was error when trying to connect to server, please try again later.');
	     }

	     document.getElementById("field_generate_register").disabled = false;
	     document.querySelector('[type="submit"]').innerHTML = '<i class="bi bi-plus-lg"></i> Register';
	     
	 }
	 
     }

     request.onerror = function() {
	 show_alert('danger', 'There was error when trying register account, please try again later.');
	 document.getElementById("field_generate_register").disabled = false;
     };

     request.send('username=' + encodeURIComponent(document.getElementById('username').value) + '&password=' + encodeURIComponent(document.getElementById('password').value));
     
 });
 
</script>
