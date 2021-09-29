<?php
if (isset($_GET['id'])) {
    switch (intval($_GET['id'])) {
	case 1:
	    include 'view/show.php';
	    break;
	case 2:
	    include 'view/token.php';
	    break;
	default:
	    http_response_code(404);
    }
} else {
    http_response_code(404);
}
exit();
?>
