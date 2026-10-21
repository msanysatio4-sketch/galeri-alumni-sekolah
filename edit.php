<?php
include 'koneksi.php';

// Ambil ID dari URL
$id = isset($_GET['id']) ? $_GET['id'] : 0;
$query = mysqli_query($conn, "SELECT * FROM galeri WHERE id = '$id'");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "Data tidak ditemukan!";
    exit;
}

// Proses update data
if (isset($_POST['update'])) {
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    
    // Update data ke database (tanpa ganti foto)
    $update = mysqli_query($conn, "UPDATE galeri SET judul='$judul', kategori='$kategori', deskripsi='$deskripsi' WHERE id='$id'");
    
    if ($update) {
        echo "<script>alert('Data berhasil diupdate!'); window.location='admin.php';</script>";
    } else {
        echo "<script>alert('Gagal mengupdate data!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Foto</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 p-10">
    <div class="max-w-md mx-auto bg-white p-6 rounded-xl shadow-md">
        <h2 class="text-2xl font-bold mb-4">Edit Data Foto</h2>
        
        <!-- Menampilkan foto yang sedang diedit -->
        <img src="uploads/<?= $data['foto']; ?>" class="w-full h-40 object-cover rounded-lg mb-4">

        <form method="POST" action="">
            <div class="mb-3">
                <label class="block text-sm font-semibold text-slate-600">Judul Foto</label>
                <input type="text" name="judul" value="<?= htmlspecialchars($data['judul']); ?>" class="w-full border p-2 rounded" required>
            </div>
            
            <div class="mb-3">
                <label class="block text-sm font-semibold text-slate-600">Kategori</label>
                <select name="kategori" class="w-full border p-2 rounded" required>
                    <option value="Keseharian" <?= $data['kategori'] == 'Keseharian' ? 'selected' : ''; ?>>Keseharian</option>
                    <option value="Acara" <?= $data['kategori'] == 'Acara' ? 'selected' : ''; ?>>Acara</option>
                    <option value="Rihlah" <?= $data['kategori'] == 'Rihlah' ? 'selected' : ''; ?>>Rihlah</option>
                    <option value="Kelulusan" <?= $data['kategori'] == 'Kelulusan' ? 'selected' : ''; ?>>Kelulusan</option>
                </select>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-semibold text-slate-600">Deskripsi</label>
                <textarea name="deskripsi" class="w-full border p-2 rounded" required><?= htmlspecialchars($data['deskripsi']); ?></textarea>
            </div>
            
            <div class="flex justify-between">
                <a href="admin.php" class="bg-slate-300 px-4 py-2 rounded font-semibold">Batal</a>
                <button type="submit" name="update" class="bg-blue-600 text-white px-4 py-2 rounded font-semibold">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</body>
</html>