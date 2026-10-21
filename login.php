<?php
session_start();
include 'koneksi.php';

$error = '';

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = md5($_POST['password']);

    $query = "SELECT * FROM admin WHERE username='$username' AND password='$password'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['username'] = $username;
        header("Location: admin.php");
        exit;
    } else {
        $error = "Username atau password salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Admin - Galeri Alumni</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-slate-100 flex items-center justify-center min-h-screen p-4">
  <div class="bg-white p-8 rounded-2xl shadow-md max-w-md w-full">
    <div class="text-center mb-6">
      <img src="assets/logo.png" alt="Logo" class="h-16 mx-auto mb-2 object-contain">
      <h2 class="text-2xl font-bold text-slate-800">Login Admin</h2>
      <p class="text-xs text-slate-500">Galeri XII IPA 2 - Ponpes Al-Mukhlishin</p>
    </div>

    <?php if ($error): ?>
      <div class="bg-red-50 text-red-600 p-3 rounded-lg text-sm mb-4 border border-red-200 text-center">
        <?= $error; ?>
      </div>
    <?php endif; ?>

    <form action="" method="POST" class="space-y-4">
      <div>
        <label class="block text-sm font-semibold text-slate-700 mb-1">Username</label>
        <input type="text" name="username" required class="w-full px-4 py-2 border rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none text-sm">
      </div>
      <div>
        <label class="block text-sm font-semibold text-slate-700 mb-1">Password</label>
        <input type="password" name="password" required class="w-full px-4 py-2 border rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none text-sm">
      </div>
      <button type="submit" name="login" class="w-full bg-emerald-700 hover:bg-emerald-800 text-white font-semibold py-2.5 rounded-xl transition shadow-sm text-sm">
        Masuk Dashboard
      </button>
    </form>
    
    <div class="mt-6 text-center">
      <a href="index.php" class="text-xs text-slate-400 hover:text-emerald-700">← Kembali ke Galeri Utama</a>
    </div>
  </div>
</body>
</html>