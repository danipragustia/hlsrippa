<?php


if (isset($_GET['id'])) { 

    $user = $pdo->query('SELECT * FROM xezc_user WHERE id = ' . intval($_GET['id']) . ' LIMIT 1')->fetchAll(PDO::FETCH_ASSOC);

    if ($user) {

	$user = $user[0];

?>

    <form method="POST">
	<fieldset>
	    <div class="form-group mb-3">
		<div class="mb-2 w-100"><label class="text-muted">Username</label></div>
		<input type="text" class="form-control" name="username" placeholder="Username" value="<?php echo $user['username']; ?>" required>
	    </div>

	    <div class="form-group mb-3">
		<div class="mb-2 w-100"><label class="text-muted">Password</label></div>
		<input type="password" class="form-control" name="password" placeholder="Password">
	    </div>

	    <div class="form-group mb-3">
		<div class="mb-2 w-100"><label class="text-muted">Level</label></div>
		<select class="form-control" name="level">
		    <option value="Admin" <?php echo ($user['level'] === 'Admin' ? 'selected' : ''); ?>>Admin</option>
		    <option value="User"<?php echo ($user['level'] === 'User' ? 'selected' : ''); ?>>User</option>
		    <option value="Disable"<?php echo ($user['level'] === 'Disable' ? 'selected' : ''); ?>>Disable</option>
	    </select>
	</div>

	<div class="d-grid gap-1 d-md-flex pb-2">
	    <button type="submit" class="btn btn-primary">Save</button>
	</div>
    </fieldset>
</form>

<?php } else { ?>

    <div class="alert alert-danger" role="alert">Data tidak ditemukan</div>
    
<?php }
} ?>
