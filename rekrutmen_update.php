<?php
session_start();
include('config/dbcon.php');

header('Content-Type: application/json');

$data = [
    'rek_no'          => $_POST['rek_no'] ?? '',
    'tanggal'         => $_POST['tanggal'] ?? '',
    'nama_rek'        => $_POST['nama_rek'] ?? '',
    'posisi'          => $_POST['posisi'] ?? '',
    'psikotes'        => $_POST['psikotes'] ?? '',
    'interview_hr'    => $_POST['interview_hr'] ?? '',
    'interview_user'  => $_POST['interview_user'] ?? '',
    'status'          => $_POST['status'] ?? ''
];

if (in_array('', [$data['rek_no'], $data['tanggal'], $data['nama_rek'], $data['posisi'], $data['status']])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Field wajib belum lengkap'
    ]);
    exit;
}

$stmt = $con->prepare("
    UPDATE rekrutmen SET
        tanggal=?,
        nama_rek=?,
        posisi=?,
        psikotes=?,
        interview_hr=?,
        interview_user=?,
        status=?
    WHERE rek_no=?
");

$stmt->bind_param(
    "ssssssss",
    $data['tanggal'],
    $data['nama_rek'],
    $data['posisi'],
    $data['psikotes'],
    $data['interview_hr'],
    $data['interview_user'],
    $data['status'],
    $data['rek_no']
);

if ($stmt->execute()) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Data rekrutmen berhasil diupdate'
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Update gagal'
    ]);
}
