<?php
include 'koneksi.php';
?>

<!DOCTYPE html>
<html lang="id" class="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Struktur Kelas XII IPA 2 - Ponpes Al-Mukhlishin</title>
  
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = { darkMode: 'class' }
  </script>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  
  <style>
    body { font-family: 'Plus Jakarta Sans', sans-serif; overflow-x: hidden; }
    .card-hover { transform-style: preserve-3d; transition: transform 0.3s ease, box-shadow 0.3s ease; }
    .card-hover:hover { transform: translateY(-5px); }
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
    
    <!-- NAVBAR & JUDUL TANPA KOTAK LATAR BELAKANG -->
    <header class="relative w-full pt-20 pb-10 px-6 md:px-12">
      <?php include 'navbar.php'; ?>

      <div class="max-w-4xl mx-auto relative z-10 flex flex-col items-center justify-center text-center gap-2 mt-8">
        <span class="text-[10px] md:text-xs font-bold tracking-[0.15em] text-cyan-600 dark:text-cyan-400 uppercase bg-cyan-500/10 px-4 py-1.5 rounded-full border border-cyan-500/20 backdrop-blur-md">Ponpes Al-Mukhlishin Sibuhuan</span>
        <h1 class="text-3xl md:text-5xl font-black text-slate-900 dark:text-white tracking-tight leading-tight mt-2">
          Struktur <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-cyan-500 dark:from-yellow-300 dark:to-amber-400">Kelas</span> XII IPA 2
        </h1>
        <p class="text-slate-600 dark:text-slate-400 font-medium text-xs md:text-sm tracking-widest uppercase mt-1">Bagan Kepengurusan & Keanggotaan Angkatan 2023</p>
      </div>
    </header>

    <!-- KONTEN STRUKTUR KELAS -->
    <main class="max-w-5xl mx-auto px-4 py-8 flex-grow w-full">
      
      <!-- 1. WALI KELAS -->
      <div class="flex justify-center mb-8">
        <div class="card-hover w-full max-w-sm bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 text-center shadow-xl relative overflow-hidden group">
          <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-emerald-500 to-teal-400"></div>
          <div class="w-24 h-24 mx-auto rounded-full overflow-hidden border-4 border-emerald-500/30 mb-4 shadow-md bg-slate-200 dark:bg-slate-800">
            <img src="assets/logo.png" alt="Wali Kelas" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
          </div>
          <span class="text-[10px] font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-500/10 px-3 py-1 rounded-full uppercase tracking-wider">Wali Kelas</span>
          <h3 class="text-lg font-black text-slate-800 dark:text-white mt-3">Nama Wali Kelas</h3>
          <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Pembimbing Kelas XII IPA 2</p>
        </div>
      </div>

      <!-- 2. KETUA KELAS -->
      <div class="flex justify-center mb-8">
        <div class="card-hover w-full max-w-sm bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 text-center shadow-xl relative overflow-hidden group">
          <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-blue-600 to-cyan-500"></div>
          <div class="w-24 h-24 mx-auto rounded-full overflow-hidden border-4 border-blue-500/30 mb-4 shadow-md bg-slate-200 dark:bg-slate-800">
            <img src="assets/logo.png" alt="Ketua Kelas" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
          </div>
          <span class="text-[10px] font-bold text-cyan-500 dark:text-cyan-400 bg-cyan-500/10 px-3 py-1 rounded-full uppercase tracking-wider">Ketua Kelas</span>
          <h3 class="text-lg font-black text-slate-800 dark:text-white mt-3">Nama Ketua Kelas</h3>
          <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Pimpinan Siswa Kelas XII IPA 2</p>
        </div>
      </div>

      <!-- 3. PENGURUS INTI (Wakil, Sekretaris, Bendahara) -->
      <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        
        <!-- Wakil Ketua -->
        <div class="card-hover bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5 text-center shadow-md">
          <div class="w-16 h-16 mx-auto rounded-full overflow-hidden border-2 border-slate-300 dark:border-slate-700 mb-3 bg-slate-200 dark:bg-slate-800">
            <img src="assets/logo.png" alt="Wakil" class="w-full h-full object-cover">
          </div>
          <span class="text-[9px] font-bold text-blue-600 dark:text-cyan-400 bg-blue-50 dark:bg-cyan-950/50 px-2.5 py-0.5 rounded-full uppercase">Wakil Ketua</span>
          <h4 class="text-sm font-bold text-slate-800 dark:text-white mt-2">Nama Wakil</h4>
        </div>

        <!-- Sekretaris -->
        <div class="card-hover bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5 text-center shadow-md">
          <div class="w-16 h-16 mx-auto rounded-full overflow-hidden border-2 border-slate-300 dark:border-slate-700 mb-3 bg-slate-200 dark:bg-slate-800">
            <img src="assets/logo.png" alt="Sekretaris" class="w-full h-full object-cover">
          </div>
          <span class="text-[9px] font-bold text-blue-600 dark:text-cyan-400 bg-blue-50 dark:bg-cyan-950/50 px-2.5 py-0.5 rounded-full uppercase">Sekretaris</span>
          <h4 class="text-sm font-bold text-slate-800 dark:text-white mt-2">Nama Sekretaris</h4>
        </div>

        <!-- Bendahara -->
        <div class="card-hover bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5 text-center shadow-md">
          <div class="w-16 h-16 mx-auto rounded-full overflow-hidden border-2 border-slate-300 dark:border-slate-700 mb-3 bg-slate-200 dark:bg-slate-800">
            <img src="assets/logo.png" alt="Bendahara" class="w-full h-full object-cover">
          </div>
          <span class="text-[9px] font-bold text-blue-600 dark:text-cyan-400 bg-blue-50 dark:bg-cyan-950/50 px-2.5 py-0.5 rounded-full uppercase">Bendahara</span>
          <h4 class="text-sm font-bold text-slate-800 dark:text-white mt-2">Nama Bendahara</h4>
        </div>

      </div>

    </main>

    <!-- FOOTER -->
    <footer class="py-6 border-t border-slate-200 dark:border-slate-800 bg-white/80 dark:bg-slate-950/80 backdrop-blur-md">
      <div class="max-w-6xl mx-auto px-4 flex flex-col md:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-3">
          <img src="assets/sani.jpeg" alt="M. Sani" class="w-10 h-10 rounded-full object-cover border-2 border-blue-500">
          <div class="text-left">
            <p class="text-sm font-bold">M. Sani Satio R Hasibuan</p>
            <p class="text-[10px] text-blue-600 font-semibold uppercase">Web Developer</p>
          </div>
        </div>
        <div class="text-center md:text-right text-slate-400 text-xs">
          <p>&copy; <?= date('Y'); ?> Alumni XII IPA 2. All rights reserved.</p>
        </div>
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