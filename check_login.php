<?php
session_start();
include('config/dbcon.php');

if (!isset($_POST['username']) || !isset($_POST['password'])) {
    $_SESSION['error'] = "Form tidak lengkap!";
    header("Location: index.php");
    exit();
}

$username = mysqli_real_escape_string($con, $_POST['username']);
$password = $_POST['password'];

$sql = "SELECT * FROM users WHERE username = '$username' LIMIT 1";
$query = mysqli_query($con, $sql);

if (!$query) {
    die("Query error: " . mysqli_error($con));
}

$user = mysqli_fetch_assoc($query);

if ($user && password_verify($password, $user['password'])) {

    $_SESSION['login'] = true;
    $_SESSION['username'] = $user['username'];
    $_SESSION['nama'] = $user['full_name'];
    $_SESSION['role'] = $user['role'];

    header("Location: dashboard.php");
exit();

} else {
    $_SESSION['error'] = "Username atau password salah!";
    header("Location: index.php");
    exit();
}
?>

