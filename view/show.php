<form id="form_generate_show">
    <fieldset id="field_generate_show">
	<div class="form-group mb-3">
	    <div class="mb-2 w-100"><label class="text-muted">Name Show</label></div>
	    <input type="text" class="form-control" name="name" placeholder="Name Show" required>
	</div>

	<div class="form-group mb-3">
	    <div class="mb-2 w-100"><label class="text-muted">M3u8 URL</label></div>
	    <input type="text" class="form-control" name="m3u8" placeholder="m3u8 URL" required>
	</div>

	<div class="form-group mb-3">
	    <div class="mb-2 w-100"><label class="text-muted">Key Auth (base64)</label></div>
	    <input type="text" class="form-control" name="key" placeholder="Key Auth (base64) / Empty if direct">
	</div>

	<div class="d-grid gap-1 d-md-flex pb-2">
	    <button type="submit" class="btn btn-primary" id="button-tambah-show"><i class="bi bi-plus-lg"></i> Tambah Show</button>
	    <button type="button" class="btn btn-success" id="button-refresh-show"><i class="bi bi-arrow-repeat"></i> Refresh Data</button>
	</div>
    </fieldset>
</form>

<div id="status" class="py-2"></div>

<table class="table table-bordered">
    <thead>
	<tr>
	    <td>Nama</td>
	    <td>Link</td>
	    <td>Aksi</td>
	</tr>
    </thead>
    <tbody id="table_body"></tbody>
</table>
<script>

 refresh_data();

 function refresh_data() {

     document.getElementById('table_body').innerHTML = '<tr><td colspan="4"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span></td></tr>';

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

			 document.getElementById('table_body').innerHTML += '<tr><td>' + ele.nama + '</td><td>' + ele.m3u8 + '</td><td><a href="show.php?id=' + ele.id + '" target="_blank" class="btn btn-sm btn-primary">Show</a> <a target="_blank" class="btn btn-sm btn-success" href="<?php echo $config['domain']; ?>/playlist.php?id=' + ele.id + '&token=<?php echo $config['stream_key']; ?>">Get m3u8</a> <button type="button" class="btn btn-sm btn-danger" data-id="' + ele.id  + '" name="btnHapus">Hapus</button></td></tr>';
			 
		     })

		     document.getElementsByName('btnHapus').forEach(function (ele) {
			 if (typeof ele.getAttribute('data-id') !== undefined) {
			     ele.addEventListener('click', function(event) {
				 event.preventDefault();
				 var request = new XMLHttpRequest();
				 request.open('POST', '/delete-show.php');
				 request.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
				 request.onload = function() {

				     if (this.readyState === 4) {
					 
					 var data = JSON.parse(this.response);
					 if (this.status == 200 && data.status == 0) {
					     show_alert('success', (typeof data.text !== null ? data.text : 'Success generate show'));
					     refresh_data();
					 } else {
					     show_alert('danger', 'There was error when trying to connect to server, please try again later.');
					 }
					 
				     }
				 }

				 request.onerror = function() {
				     show_alert('danger', 'There was error when trying generate link, please try again later.');
				     document.getElementById("field_generate_show").disabled = false;
				 };

				 request.send('id=' + parseInt(this.getAttribute('data-id')));
				     
			     });
			 }
		     });
		     
		 } else {
		     document.getElementById('table_body').innerHTML = '';
		     show_alert('danger', (typeof data.text !== null ? data.text : 'Failed to fetch show data'));
		 }
		 
	     } else {
		 show_alert('danger', 'There was error when trying to connect to server, please try again later.');
	     }

	     document.getElementById("field_generate_show").disabled = false;
	     document.querySelector('[type="submit"]').innerHTML = '<i class="bi bi-plus-lg"></i> Tambah Show';
	     
	 }
	 
     }

     request.onerror = function() {
	 show_alert('danger', 'There was error when trying generate link, please try again later.');
	 document.getElementById("field_generate_show").disabled = false;
     };

     request.send('data=show');
     
 }

 function show_alert(color, text) {
     document.getElementById('status').innerHTML = '<div class="alert alert-' + color + '" role="alert">' + text + '</div>';
 }

 document.getElementById('button-refresh-show').addEventListener('click', function(event) {
     event.preventDefault();
     refresh_data();
 });
 
 document.getElementById("form_generate_show").addEventListener("submit", function(event) {
     
     event.preventDefault();
     document.getElementById('field_generate_show').disabled = true;
     document.getElementById('status').innerHTML = '';
     document.querySelector('[type="submit"]').innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...';

     var request = new XMLHttpRequest();
     request.open('POST', '/dashboard.php?page=show');
     request.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

     request.onload = function() {

	 if (this.readyState === 4) {

	     if (this.status == 200) {

		 var data = JSON.parse(this.response);
		 if (data.status == 0) {
		     show_alert('success', (typeof data.text !== null ? data.text : 'Success generate show'));
		     refresh_data();
		 } else {
		     show_alert('danger', (typeof data.text !== null ? data.text : 'Failed to generate show'));
		 }
		 
	     } else {
		 show_alert('danger', 'There was error when trying to connect to server, please try again later.');
	     }

	     document.getElementById("field_generate_show").disabled = false;
	     document.querySelector('[type="submit"]').innerHTML = '<i class="bi bi-plus-lg"></i> Tambah Show';
	     document.getElementsByName('name')[0].value = '';
	     document.getElementsByName('name')[0].focus();
	     
	 }
	 
     }

     request.onerror = function() {
	 show_alert('danger', 'There was error when trying generate link, please try again later.');
	 document.getElementById("field_generate_show").disabled = false;
     };

     request.send([
	 'name=' + encodeURIComponent(document.getElementsByName('name')[0].value),
	 'm3u8=' + encodeURIComponent(document.getElementsByName('m3u8')[0].value),
	 'key=' + encodeURIComponent(document.getElementsByName('key')[0].value)
     ].join('&'));
			 
			 
 });
     
</script>
