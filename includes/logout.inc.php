<?php
include_once '../config/db_connection.php';
$user->logout();
echo '<script>window.location.replace("../public/");</script>';
exit();
?>