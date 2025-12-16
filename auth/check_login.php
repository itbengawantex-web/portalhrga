<?php
session_start();
include('../config/dbcon.php');

if (!isset($_POST['username']) || !isset($_POST['password'])) {
    header("Location: login.php");
    exit();
}

$username = mysqli_real_escape_string($conn, $_POST['username']);
$password = $_POST['password'];

$sql = "SELECT * FROM users WHERE username = '$username' LIMIT 1";
$query = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($query);

if ($user && password_verify($password, $user['password'])) {

    $_SESSION['login'] = true;
    $_SESSION['username'] = $user['username'];
    $_SESSION['nama'] = $user['nama'];
    $_SESSION['role'] = $user['role'];

    header("Location: ../dashboard.php"); 
    exit();

} else {
    $_SESSION['error'] = "Username atau password salah!";
    header("Location: login.php");
    exit();
}
?>
