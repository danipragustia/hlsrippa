<?php

require_once 'hlsrippa.php';
$pdo->prepare('INSERT INTO xezc_user (username,password) VALUES (?,?)')->execute([
    'mimin',
    password_hash('janganditanya', PASSWORD_DEFAULT)
]);

?>
