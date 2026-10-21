<?php
include 'koneksi.php';

if (isset($_POST['id']) && isset($_POST['action'])) {
    $id = intval($_POST['id']);
    $action = $_POST['action']; // Menerima perintah 'like' atau 'unlike'
    
    if ($action === 'like') {
        $sql = "UPDATE galeri SET likes = likes + 1 WHERE id = $id";
    } else {
        $sql = "UPDATE galeri SET likes = GREATEST(likes - 1, 0) WHERE id = $id"; // Mencegah angka minus (-)
    }
    
    if (mysqli_query($conn, $sql)) {
        // Ambil jumlah like terbaru
        $result = mysqli_query($conn, "SELECT likes FROM galeri WHERE id = $id");
        $data = mysqli_fetch_assoc($result);
        echo $data['likes'];
    } else {
        echo "gagal";
    }
}
?>