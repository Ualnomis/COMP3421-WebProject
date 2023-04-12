<?php
include_once '../config/db_connection.php';
$user->logout();
header('Location: ../public/');
exit();
?>