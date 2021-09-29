<form id="form_generate_token">
    <fieldset id="field_generate_token">
	<div class="form-group mb-3">
	    <div class="mb-2 w-100"><label class="text-muted">Nama Show</label></div>
	    <select class="form-select" nama="id_show" id="id_show"></select>
	</div>

	<div class="d-grid gap-1 d-md-flex pb-2">
	    <button type="submit" class="btn btn-primary" id="button-generate-token"><i class="bi bi-plus-lg"></i> Generate Token</button>
	    <button type="button" class="btn btn-success" id="button-refresh-token"><i class="bi bi-arrow-repeat"></i> Refresh Data</button>
	</div>
    </fieldset>
</form>

<div id="status" class="my-2"></div>

<table class="table table-bordered">
    <thead>
	<tr>
	    <td>Token</td>
	    <td>Show</td>
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

     document.getElementById("field_generate_token").disabled = true; 
     document.getElementById('id_show').disabled = false;
     document.getElementById('button-generate-token').disabled = false;

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

			 document.getElementById('table_body').innerHTML += '<tr><td>' + ele.token + '</td><td>' + ele.show + '</td><td><button type="button" class="btn btn-sm btn-danger" data-id="' + ele.id  + '" name="btnHapus">Hapus</button></td></tr>';
			 
		     })

		     document.getElementsByName('btnHapus').forEach(function (ele) {
			 if (typeof ele.getAttribute('data-id') !== undefined) {
			     ele.addEventListener('click', function(event) {
				 event.preventDefault();
				 var request = new XMLHttpRequest();
				 request.open('POST', '/delete-token.php');
				 request.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
				 request.onload = function() {

				     if (this.readyState === 4) {
					 
					 var data = JSON.parse(this.response);
					 
					 if (this.status == 200 && data.status == 0) {
					     show_alert('success', (typeof data.text !== null ? data.text : 'Success generate token'));
					     refresh_data();
					 } else {
					     show_alert('danger', 'There was error when trying to connect to server, please try again later.');
					 }
					 
				     }
				 }

				 request.onerror = function() {
				     show_alert('danger', 'There was error when trying fetch token, please try again later.');
				     document.getElementById("field_generate_token").disabled = false;
				 };

				 request.send('id=' + parseInt(this.getAttribute('data-id')));
				 
			     });
			 }
		     });
		     
		 } else {
		     document.getElementById('table_body').innerHTML = '';

		     if (data.show.length === 0) {
			 document.getElementById('id_show').disabled = true;
			 document.getElementById('button-generate-token').disabled = true;
		     } else {
			 document.getElementById('id_show').innerHTML = '';
			 data.show.forEach(function(show) {
			     document.getElementById('id_show').innerHTML += '<option value="' + show.id + '">' + show.name + '</option>';
			 });
			 document.getElementById("field_generate_token").disabled = false;
		     }
		     
		     document.querySelector('[type="submit"]').innerHTML = '<i class="bi bi-plus-lg"></i> Generate Token';
		     show_alert('danger', (typeof data.text !== null ? data.text : 'Failed to fetch token data'));
		     return false;
		 }
		 
	     } else {
		 show_alert('danger', 'There was error when trying to connect to server, please try again later.');
	     }

	     
	     document.getElementById("field_generate_token").disabled = false;
	     document.querySelector('[type="submit"]').innerHTML = '<i class="bi bi-plus-lg"></i> Generate Token';
	     
	 }
	 
     }

     request.onerror = function() {
	 show_alert('danger', 'There was error when trying generate token, please try again later.');
	 document.getElementById("field_generate_token").disabled = false;
     };

     request.send('data=token');
     
 }

 function show_alert(color, text) {
     document.getElementById('status').innerHTML = '<div class="alert alert-' + color + '" role="alert">' + text + '</div>';
 }

 document.getElementById('button-refresh-token').addEventListener('click', function(event) {
     event.preventDefault();
     refresh_data();
 });

 document.getElementById("form_generate_token").addEventListener("submit", function(event) {
     
     event.preventDefault();
     document.getElementById('field_generate_token').disabled = true;
     document.getElementById('status').innerHTML = '';
     document.querySelector('[type="submit"]').innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...';

     var request = new XMLHttpRequest();
     request.open('POST', '/dashboard.php?page=token');
     request.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

     request.onload = function() {

	 if (this.readyState === 4) {

	     if (this.status == 200) {

		 var data = JSON.parse(this.response);
		 if (data.status == 0) {
		     show_alert('success', (typeof data.text !== null ? data.text : '<h4 class="alert-heading">Success Generate Token</h4><p>Here new token was generate</p><code>' + data.code + '</code><hr><p><b>Only 1 Device per token can be watch</b></p>'));
		     refresh_data();
		 } else {
		     show_alert('danger', (typeof data.text !== null ? data.text : 'Failed to generate token'));
		 }
		 
	     } else {
		 show_alert('danger', 'There was error when trying to connect to server, please try again later.');
	     }

	     document.getElementById("field_generate_token").disabled = false;
	     document.querySelector('[type="submit"]').innerHTML = '<i class="bi bi-plus-lg"></i> Generate Token';
	     document.getElementById('id_show').focus();
	     
	 }
	 
     }

     request.onerror = function() {
	 show_alert('danger', 'There was error when trying generate token, please try again later.');
	 document.getElementById("field_generate_token").disabled = false;
     };

     request.send('id_show=' + encodeURIComponent(document.getElementById('id_show').value));
     
 });
 
</script>
