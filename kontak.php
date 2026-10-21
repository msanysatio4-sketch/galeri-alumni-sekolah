<?php
include 'koneksi.php';

$msg = '';

// Proses jika form kontak dikirim
if (isset($_POST['kirim_pesan'])) {$nama = mysqli_real_escape_string($conn,$_POST['nama']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);$pesan = mysqli_real_escape_string($conn,$_POST['pesan']);

    if (!empty($nama) && !empty($pesan)) {$msg = "<div class='bg-emerald-50 dark:bg-emerald-950/50 border border-emerald-200 dark:border-emerald-500/30 text-emerald-700 dark:text-emerald-300 p-4 rounded-2xl text-xs mb-6'>Terima kasih, $nama! Pesan kamu berhasil dikirim.</div>";
    } else {
        $msg = "<div class='bg-red-50 dark:bg-red-950/50 border border-red-200 dark:border-red-500/30 text-red-700 dark:text-red-300 p-4 rounded-2xl text-xs mb-6'>Mohon isi nama dan pesan terlebih dahulu.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="id" class="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Hubungi Kami - Galeri Alumni XII IPA 2</title>
  
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = { darkMode: 'class' }
  </script>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  
  <style>
    body { font-family: 'Plus Jakarta Sans', sans-serif; overflow-x: hidden; }
  </style>
</head>
<body class="bg-slate-50 text-slate-800 dark:bg-slate-950 dark:text-slate-100 min-h-screen flex flex-col justify-between transition-colors duration-300 relative">

  <!-- BACKGROUND OVERLAY -->
  <div class="fixed inset-0 z-0 pointer-events-none">
    <div class="absolute top-0 left-0 w-[500px] h-[500px] bg-blue-500/5 dark:bg-blue-600/15 rounded-full blur-[100px]"></div>
    <div class="absolute bottom-0 right-0 w-[500px] h-[500px] bg-cyan-500/5 dark:bg-cyan-600/15 rounded-full blur-[100px]"></div>
    <div class="absolute inset-0 bg-[radial-gradient(#cbd5e1_1px,transparent_1px)] dark:bg-[radial-gradient(#334155_1px,transparent_1px)] [background-size:24px_24px] opacity-60 dark:opacity-30"></div>
  </div>

  <canvas id="starfield" class="fixed inset-0 pointer-events-none transition-opacity duration-1000 opacity-0 dark:opacity-100" style="z-index: 1;"></canvas>

  <!-- TOMBOL NAVIGASI KANAN ATAS -->
  <div class="fixed top-5 right-6 md:right-12 z-50 flex items-center gap-5">
    <a href="login.php" title="Login Admin" class="text-slate-300 hover:text-cyan-400 hover:scale-110 transition-all drop-shadow-lg">
      <svg width="24" height="24" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
    </a>
    <button onclick="toggleTheme()" id="themeBtn" aria-label="Toggle Theme" class="text-amber-400 hover:text-amber-300 hover:scale-110 transition-all cursor-pointer drop-shadow-lg">
      <svg width="24" height="24" id="sunIcon" class="w-6 h-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
      <svg width="24" height="24" id="moonIcon" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" /></svg>
    </button>
  </div>

  <div class="relative z-10 flex flex-col flex-grow">
    
    <!-- NAVBAR & JUDUL -->
    <header class="relative w-full pt-20 pb-10 px-6 md:px-12">
      <?php include 'navbar.php'; ?>

      <div class="max-w-4xl mx-auto relative z-10 flex flex-col items-center justify-center text-center gap-2 mt-8">
        <span class="text-[10px] md:text-xs font-bold tracking-[0.15em] text-cyan-600 dark:text-cyan-400 uppercase bg-cyan-500/10 px-4 py-1.5 rounded-full border border-cyan-500/20 backdrop-blur-md">Ponpes Al-Mukhlishin Sibuhuan</span>
        <h1 class="text-3xl md:text-5xl font-black text-slate-900 dark:text-white tracking-tight leading-tight mt-2">
          Hubungi <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-cyan-500 dark:from-yellow-300 dark:to-amber-400">Kami</span>
        </h1>
        <p class="text-slate-600 dark:text-slate-400 font-medium text-xs md:text-sm tracking-widest uppercase mt-1">Punya saran, kritik, atau ingin berbagi foto kenangan?</p>
      </div>
    </header>

    <!-- KONTEN KONTAK -->
    <main class="max-w-4xl mx-auto px-4 py-8 flex-grow w-full grid grid-cols-1 md:grid-cols-3 gap-8">
      
      <!-- Info Kontak / Alamat (1 Kolom) -->
      <div class="md:col-span-1 space-y-4">
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-md">
          <div class="w-10 h-10 rounded-2xl bg-blue-500/10 text-blue-600 dark:text-cyan-400 flex items-center justify-center mb-4">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
          </div>
          <h3 class="text-sm font-bold text-slate-800 dark:text-white">Lokasi Pondok</h3>
          <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 leading-relaxed">Ponpes Al-Mukhlishin Sibuhuan, Kabupaten Padang Lawas, Sumatera Utara.</p>
        </div>

        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-md">
          <div class="w-10 h-10 rounded-2xl bg-cyan-500/10 text-cyan-600 dark:text-cyan-400 flex items-center justify-center mb-4">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/></svg>
          </div>
          <h3 class="text-sm font-bold text-slate-800 dark:text-white">Pengembang Web</h3>
          <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 leading-relaxed">M. Sani Satio R Hasibuan<br>Angkatan 2023 XII IPA 2</p>
        </div>
      </div>

      <!-- Form Kirim Pesan (2 Kolom) -->
      <div class="md:col-span-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 md:p-8 shadow-md">
        <h3 class="text-lg font-bold text-slate-800 dark:text-white mb-4">Kirim Pesan ke Admin</h3>
        
        <?= $msg; ?>

        <form action="" method="POST" class="space-y-4">
          <div>
            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Nama Lengkap</label>
            <input type="text" name="nama" required placeholder="Masukkan nama kamu..." class="w-full px-4 py-2.5 border border-slate-200 dark:border-slate-800 rounded-2xl bg-slate-50 dark:bg-slate-950 text-xs focus:ring-2 focus:ring-blue-500 focus:outline-none dark:text-white">
          </div>

          <div>
            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Email / Kontak (Opsional)</label>
            <input type="text" name="email" placeholder="No. WhatsApp / Email..." class="w-full px-4 py-2.5 border border-slate-200 dark:border-slate-800 rounded-2xl bg-slate-50 dark:bg-slate-950 text-xs focus:ring-2 focus:ring-blue-500 focus:outline-none dark:text-white">
          </div>

          <div>
            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Pesan / Kesan</label>
            <textarea name="pesan" rows="4" required placeholder="Tulis pesan atau masukan di sini..." class="w-full px-4 py-2.5 border border-slate-200 dark:border-slate-800 rounded-2xl bg-slate-50 dark:bg-slate-950 text-xs focus:ring-2 focus:ring-blue-500 focus:outline-none dark:text-white"></textarea>
          </div>

          <button type="submit" name="kirim_pesan" class="w-full bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-500 hover:to-cyan-500 text-white font-bold py-3 rounded-2xl transition text-xs shadow-lg shadow-blue-500/20 cursor-pointer">
            Kirim Pesan
          </button>
        </form>
      </div>

    </main>

    <!-- FOOTER -->
    <footer class="py-6 border-t border-slate-200 dark:border-slate-800 bg-white/80 dark:bg-slate-950/80 backdrop-blur-md text-center">
      <div class="max-w-6xl mx-auto px-4 text-slate-400 text-xs">
        <p>&copy; <?= date('Y'); ?> Alumni XII IPA 2. All rights reserved.</p>
      </div>
    </footer>
    
  </div>

  <!-- SCRIPT THEME & BINTANG -->
  <script>
    const canvas = document.getElementById('starfield');
    const ctx = canvas.getContext('2d');
    let stars = [];
    const numStars = 120;

    function resizeCanvas() {
      canvas.width = window.innerWidth;
      canvas.height = window.innerHeight;
    }
    window.addEventListener('resize', resizeCanvas);
    resizeCanvas();

    function initStars() {
      stars = [];
      for (let i = 0; i < numStars; i++) {
        stars.push({
          x: Math.random() * canvas.width,
          y: Math.random() * canvas.height,
          radius: Math.random() * 1.5,
          alpha: Math.random(),
          decay: 0.005 + Math.random() * 0.015,
          speedY: 0.05 + Math.random() * 0.15
        });
      }
    }
    initStars();

    function animateStars() {
      ctx.clearRect(0, 0, canvas.width, canvas.height);
      stars.forEach(star => {
        star.alpha += star.decay;
        if (star.alpha > 1 || star.alpha < 0.1) star.decay = -star.decay;
        star.y += star.speedY;
        if (star.y > canvas.height) { star.y = 0; star.x = Math.random() * canvas.width; }
        
        ctx.beginPath();
        ctx.arc(star.x, star.y, star.radius, 0, Math.PI * 2);
        ctx.fillStyle = `rgba(255, 255, 255, ${Math.abs(star.alpha)})`;
        ctx.fill();
      });
      requestAnimationFrame(animateStars);
    }
    animateStars();

    function initTheme() {
      if(localStorage.getItem('theme') === 'light') {
        document.documentElement.classList.remove('dark');
        document.getElementById('sunIcon').classList.remove('hidden');
        document.getElementById('moonIcon').classList.add('hidden');
      } else {
        document.documentElement.classList.add('dark');
        document.getElementById('sunIcon').classList.add('hidden');
        document.getElementById('moonIcon').classList.remove('hidden');
      }
    }
    initTheme();

    function toggleTheme() {
      const html = document.documentElement;
      html.classList.toggle('dark');
      const isDark = html.classList.contains('dark');
      localStorage.setItem('theme', isDark ? 'dark' : 'light');
      
      if(isDark) {
        document.getElementById('sunIcon').classList.add('hidden');
        document.getElementById('moonIcon').classList.remove('hidden');
      } else {
        document.getElementById('sunIcon').classList.remove('hidden');
        document.getElementById('moonIcon').classList.add('hidden');
      }
    }
  </script>
</body>
</html>