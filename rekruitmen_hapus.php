<?php
session_start();
include('config/dbcon.php');

if (isset($_GET['rek_no'])) {
    $rek_no = mysqli_real_escape_string($con, $_GET['rek_no']);

    $delete = mysqli_query($con, "DELETE FROM rekrutmen WHERE rek_no='$rek_no'");

    if ($delete) {
        $_SESSION['status'] = "Data berhasil dihapus";
    } else {
        $_SESSION['status'] = "Gagal menghapus data";
    }
}

header("Location: rekruitmen.php");
exit();
