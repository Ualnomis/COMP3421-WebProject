<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "giftify";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

include('../classes/user.class.php');
$user = new User($conn);
session_start();
?>
