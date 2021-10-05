<?php

$note = $pdo->query('SELECT value FROM wegy_settings WHERE item = "note" LIMIT 1')->fetchColumn();
$cache_time = $pdo->query('SELECT value FROM wegy_settings WHERE item = "cache_time" LIMIT 1')->fetchColumn();

if ($note === false) {
    echo '<p>Failed to connect database</p>';
    
} else {
?>
<form method="POST">

    <div id="status"><?php echo (isset($_SESSION['status']) ? '<div class="alert alert-success" role="alert">' . $_SESSION['status'] . '</div>' : ''); unset($_SESSION['status']); ?></div>

    <div class="form-group my-2">
	<label for="txtNote mb-3">Cache time <span class="badge bg-warning text-dark">Experimental</span></label>
	<input type="number" class="form-control my-3" placeholder="Cache time (0 = Disable)" name="cache_time" min="0" value="<?php echo $cache_time; ?>">
    </div>
	
    <div class="form-group my-2">
	<label for="txtNote mb-3">Announcement (only visible on user)</label>
	<textarea class="form-control my-3" placeholder="Announcement" name="note" rows="12"><?php echo $note; ?></textarea>
    </div>

    <button type="submit" class="btn btn-primary">Save Settings</button>
    
</form>
<?php } ?>
