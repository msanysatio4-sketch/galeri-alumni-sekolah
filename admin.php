<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

$msg = '';

// Fungsi Kompresi Otomatis Target ~5MB (Kualitas Tinggi)
function compressAndSaveImage($source, $destination, $target_size_bytes = 5242880) {
    $info = getimagesize($source);
    if (!$info) return false;

    // Load gambar berdasarkan format
    if ($info['mime'] == 'image/jpeg') {
        $image = imagecreatefromjpeg($source);
    } elseif ($info['mime'] == 'image/png') {
        $image = imagecreatefrompng($source);
    } elseif ($info['mime'] == 'image/webp') {
        $image = imagecreatefromwebp($source);
    } else {
        return false;
    }

    // Perbaiki rotasi otomatis jika dari kamera HP (EXIF)
    if ($info['mime'] == 'image/jpeg' && function_exists('exif_read_data')) {
        @$exif = exif_read_data($source);
        if (!empty($exif['Orientation'])) {
            switch ($exif['Orientation']) {
                case 3: $image = imagerotate($image, 180, 0); break;
                case 6: $image = imagerotate($image, -90, 0); break;
                case 8: $image = imagerotate($image, 90, 0); break;
            }
        }
    }

    // Cek Resolusi - Batasi dimensi ke 3000px agar tetap HD tajam
    $width = imagesx($image);
    $height = imagesy($image);
    $max_dim = 3000;

    if ($width > $max_dim || $height > $max_dim) {
        if ($width > $height) {
            $new_width = $max_dim;
            $new_height = floor($height * ($max_dim / $width));
        } else {
            $new_height = $max_dim;
            $new_width = floor($width * ($max_dim / $height));
        }
        $tmp_img = imagecreatetruecolor($new_width, $new_height);
        
        // Jaga transparansi jika PNG/WEBP
        if ($info['mime'] == 'image/png' || $info['mime'] == 'image/webp') {
            imagealphablending($tmp_img, false);
            imagesavealpha($tmp_img, true);
        }

        imagecopyresampled($tmp_img, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
        imagedestroy($image);
        $image = $tmp_img;
    }

    // Kompres bertahap dari kualitas 92% hingga mendekati target 5MB
    $quality = 92;
    do {
        ob_start();
        imagejpeg($image, NULL, $quality);
        $data = ob_get_clean();
        $quality -= 4;
    } while (strlen($data) > $target_size_bytes && $quality >= 65);

    file_put_contents($destination, $data);
    imagedestroy($image);
    return true;
}

// Proses Upload Foto
if (isset($_POST['upload'])) {
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);

    $filename = $_FILES['foto']['name'];
    $tmp_name = $_FILES['foto']['tmp_name'];
    $file_size = $_FILES['foto']['size'];
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

    $allowed_ext = ['jpg', 'jpeg', 'png', 'webp'];

    if (in_array($ext, $allowed_ext)) {
        // Izinkan file hingga 15MB
        if ($file_size <= 15000000) { 
            $new_filename = uniqid('foto_') . '.jpg'; // Disimpan standar JPG berkuantitas tinggi
            $destination = 'uploads/' . $new_filename;

            // Jika ukuran > 5MB, jalankan kompresi otomatis target 5MB
            if ($file_size > 5242880) {
                $upload_success = compressAndSaveImage($tmp_name, $destination, 5242880);
            } else {
                $upload_success = move_uploaded_file($tmp_name, $destination);
            }

            if ($upload_success) {
                $query = "INSERT INTO galeri (judul, kategori, deskripsi, foto) VALUES ('$judul', '$kategori', '$deskripsi', '$new_filename')";
                if (mysqli_query($conn, $query)) {
                    $msg = "<p class='text-emerald-600 bg-emerald-50 p-3 rounded-lg text-sm border border-emerald-200 mb-4'>Foto berhasil diunggah dan dioptimalkan!</p>";
                } else {
                    $msg = "<p class='text-red-600 bg-red-50 p-3 rounded-lg text-sm border border-red-200 mb-4'>Gagal menyimpan ke database.</p>";
                }
            } else {
                $msg = "<p class='text-red-600 bg-red-50 p-3 rounded-lg text-sm border border-red-200 mb-4'>Gagal memproses file foto.</p>";
            }
        } else {
            $msg = "<p class='text-red-600 bg-red-50 p-3 rounded-lg text-sm border border-red-200 mb-4'>Ukuran file terlalu besar (Maksimal 15MB).</p>";
        }
    } else {
        $msg = "<p class='text-red-600 bg-red-50 p-3 rounded-lg text-sm border border-red-200 mb-4'>Format file tidak didukung! (Gunakan JPG, PNG, atau WEBP).</p>";
    }
}

// Ambil semua daftar foto
$data_foto = mysqli_query($conn, "SELECT * FROM galeri ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Admin - Galeri Alumni</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-slate-50 text-slate-800 min-h-screen">

  <nav class="bg-white border-b border-slate-200 py-4 px-6 flex justify-between items-center max-w-6xl mx-auto">
    <div class="flex items-center gap-3">
      <img src="assets/logo.png" alt="Logo" class="h-8 w-auto">
      <span class="font-bold text-slate-800">Panel Admin Galeri</span>
    </div>
    <div class="flex items-center gap-4 text-sm">
      <a href="index.php" target="_blank" class="text-emerald-700 hover:underline">Lihat Galeri ↗</a>
      <a href="logout.php" class="bg-red-50 text-red-600 hover:bg-red-100 px-3 py-1.5 rounded-lg text-xs font-semibold transition">Logout</a>
    </div>
  </nav>

  <main class="max-w-6xl mx-auto p-4 md:p-6 grid grid-cols-1 md:grid-cols-3 gap-6">

    <!-- Form Upload Foto -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 md:col-span-1 h-fit">
      <h2 class="text-lg font-bold text-slate-800 mb-4 border-b pb-2">Upload Foto Baru</h2>
      
      <?= $msg; ?>

      <form action="" method="POST" enctype="multipart/form-data" class="space-y-4">
        <div>
          <label class="block text-xs font-semibold text-slate-700 mb-1">Judul Foto</label>
          <input type="text" name="judul" required placeholder="Contoh: Rihlah ke Danau" class="w-full px-3 py-2 border rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none text-xs">
        </div>

        <div>
          <label class="block text-xs font-semibold text-slate-700 mb-1">Kategori</label>
          <select name="kategori" required class="w-full px-3 py-2 border rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none text-xs bg-white">
            <option value="Keseharian">Keseharian</option>
            <option value="Acara">Acara</option>
            <option value="Rihlah">Rihlah</option>
            <option value="Kelulusan">Kelulusan</option>
          </select>
        </div>

        <div>
          <label class="block text-xs font-semibold text-slate-700 mb-1">Deskripsi / Kesan</label>
          <textarea name="deskripsi" rows="3" placeholder="Cerita singkat tentang foto..." class="w-full px-3 py-2 border rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none text-xs"></textarea>
        </div>

        <div>
          <label class="block text-xs font-semibold text-slate-700 mb-1">Pilih File Foto (Maks. 15MB)</label>
          <input type="file" name="foto" required accept="image/*" class="w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
          <p class="text-[10px] text-slate-400 mt-1">*Foto > 5MB otomatis dioptimalkan tanpa mengurangi ketajaman.</p>
        </div>

        <button type="submit" name="upload" class="w-full bg-emerald-700 hover:bg-emerald-800 text-white font-semibold py-2 rounded-xl transition text-xs shadow-sm">
          Unggah Foto
        </button>
      </form>
    </div>

    <!-- Kelola Daftar Foto -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 md:col-span-2">
      <h2 class="text-lg font-bold text-slate-800 mb-4 border-b pb-2">Daftar Foto Terunggah</h2>

      <div class="overflow-x-auto">
        <table class="w-full text-left text-xs text-slate-600">
          <thead class="bg-slate-100 text-slate-700 font-semibold uppercase">
            <tr>
              <th class="p-3">Foto</th>
              <th class="p-3">Judul & Kategori</th>
              <th class="p-3">Deskripsi</th>
              <th class="p-3 text-center">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100">
            <?php if (mysqli_num_rows($data_foto) > 0): ?>
              <?php while ($f = mysqli_fetch_assoc($data_foto)): ?>
                <tr>
                  <td class="p-3">
                    <img src="uploads/<?= $f['foto']; ?>" class="w-12 h-12 object-cover rounded-lg border">
                  </td>
                  <td class="p-3">
                    <div class="font-bold text-slate-800"><?= htmlspecialchars($f['judul']); ?></div>
                    <span class="text-[10px] bg-emerald-50 text-emerald-700 px-2 py-0.5 rounded-full font-semibold"><?= $f['kategori']; ?></span>
                  </td>
                  <td class="p-3 max-w-xs truncate"><?= htmlspecialchars($f['deskripsi']); ?></td>
                  <td class="p-3 flex items-center justify-center gap-3 h-[72px]">
                    <a href="edit.php?id=<?= $f['id']; ?>" class="text-blue-600 hover:text-blue-800 font-semibold">Edit</a>
                    <span class="text-slate-300">|</span>
                    <a href="hapus.php?id=<?= $f['id']; ?>" onclick="return confirm('Yakin ingin menghapus foto ini?')" class="text-red-600 hover:text-red-800 font-semibold">Hapus</a>
                  </td>
                </tr>
              <?php endwhile; ?>
            <?php else: ?>
              <tr>
                <td colspan="4" class="p-4 text-center text-slate-400">Belum ada foto yang diunggah.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

  </main>
</body>
</html>