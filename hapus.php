<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    $query = mysqli_query($conn, "SELECT foto FROM galeri WHERE id = $id");
    $data = mysqli_fetch_assoc($query);

    if ($data) {
        $file_path = 'uploads/' . $data['foto'];
        if (file_exists($file_path)) {
            unlink($file_path);
        }
        mysqli_query($conn, "DELETE FROM galeri WHERE id = $id");
    }
}

header("Location: admin.php");
exit;
?>