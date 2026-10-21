<?php
include 'koneksi.php';
?>

<!DOCTYPE html>
<html lang="id" class="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dukung Pengembang - Galeri Alumni XII IPA 2</title>
  
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = { darkMode: 'class' }
  </script>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  
  <style>
    body { font-family: 'Plus Jakarta Sans', sans-serif; overflow-x: hidden; }
    
    /* Animasi karakter muncul dari bawah */
    @keyframes slideUpFade {
      from { transform: translateY(50px); opacity: 0; }
      to { transform: translateY(0); opacity: 1; }
    }
    .animate-slide-up {
      animation: slideUpFade 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
  </style>
</head>
<body class="bg-slate-50 text-slate-800 dark:bg-slate-950 dark:text-slate-100 min-h-screen flex flex-col justify-between transition-colors duration-300 relative">

  <!-- AUDIO ELEMENT (Format diubah menjadi .mpeg) -->
  <audio id="donasiSound" src="assets/sound.mpeg" preload="auto"></audio>

  <!-- BACKGROUND OVERLAY -->
  <div class="fixed inset-0 z-0 pointer-events-none">
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-blue-500/10 dark:bg-cyan-500/10 rounded-full blur-[140px]"></div>
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
    <header class="relative w-full pt-20 pb-6 px-6 md:px-12">
      <?php include 'navbar.php'; ?>

      <div class="max-w-4xl mx-auto relative z-10 flex flex-col items-center justify-center text-center gap-2 mt-8">
        <span class="text-[10px] md:text-xs font-bold tracking-[0.2em] text-cyan-600 dark:text-cyan-400 uppercase bg-cyan-500/10 px-4 py-1.5 rounded-full border border-cyan-500/20 backdrop-blur-md">Official Developer Badge</span>
        <h1 class="text-3xl md:text-5xl font-black text-slate-900 dark:text-white tracking-tight leading-tight mt-2 relative z-20">
          Dukung <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-cyan-500 dark:from-cyan-400 dark:to-blue-400">Pengembang</span>
        </h1>
        <!-- TEKS INSTRUKSI BARU -->
        <p class="text-xs md:text-sm font-medium text-slate-500 dark:text-slate-400 mt-1 flex items-center gap-1.5 animate-pulse">
          <svg class="w-4 h-4 text-blue-500 dark:text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/></svg>
          Naikkan volume dan klik kolom di bawah
        </p>
      </div>
    </header>

    <!-- KONTEN UTAMA: HORIZONTAL BANNER CARD -->
    <main class="max-w-3xl mx-auto px-4 py-6 flex-grow w-full flex items-center justify-center relative z-20">
      
      <!-- Tambahan onclick="playSound()", cursor-pointer, dan hover:scale -->
      <div onclick="playSound()" class="w-full bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border border-slate-200 dark:border-slate-800 rounded-3xl p-6 md:p-8 shadow-2xl relative overflow-hidden flex flex-col md:flex-row items-center gap-6 cursor-pointer hover:scale-[1.02] hover:shadow-blue-500/20 transition-all duration-300 group">
        
        <!-- Aksen Garis Gradient -->
        <div class="absolute top-0 left-0 md:w-2 md:h-full w-full h-2 bg-gradient-to-r md:bg-gradient-to-b from-blue-600 via-cyan-500 to-teal-400"></div>

        <!-- Foto Profil Bulat -->
        <div class="relative shrink-0">
          <div class="absolute inset-0 bg-gradient-to-tr from-blue-600 to-cyan-400 rounded-full blur-md opacity-50 scale-105 group-hover:opacity-75 transition-opacity"></div>
          <img src="assets/sani.jpeg" alt="M. Sani" class="relative w-24 h-24 md:w-28 md:h-28 rounded-full object-cover border-4 border-white dark:border-slate-800 shadow-xl">
        </div>

        <!-- Informasi & Nomor E-Wallet -->
        <div class="flex-grow text-center md:text-left">
          <div class="flex flex-col md:flex-row md:items-center justify-between gap-2 mb-1">
            <h2 class="text-xl font-black text-slate-900 dark:text-white">M. Sani Satio R Hasibuan</h2>
            <span class="text-[9px] font-extrabold tracking-wider text-cyan-600 dark:text-cyan-400 uppercase bg-cyan-50 dark:bg-cyan-950/60 px-3 py-0.5 rounded-full border border-cyan-200 dark:border-cyan-500/30 w-fit mx-auto md:mx-0">Lead Developer</span>
          </div>
          <p class="text-xs text-slate-500 dark:text-slate-400 mb-4 leading-relaxed max-w-sm">
            Perancang sistem web Galeri Alumni XII IPA 2 Ponpes Al-Mukhlishin Sibuhuan.
          </p>

          <div class="bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 rounded-2xl p-3.5 flex flex-col sm:flex-row items-center justify-between gap-3 relative z-30 transition-colors group-hover:border-blue-500/30">
            <div>
              <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">DANA / OVO / GOPAY</p>
              <p class="text-base font-black text-blue-600 dark:text-cyan-400 font-mono tracking-wider select-all mt-0.5">
                0822-7413-8065
              </p>
            </div>
            <span class="text-[11px] text-slate-500 dark:text-slate-400 sm:text-right">
              Atas Nama: <strong class="text-slate-700 dark:text-slate-300 block sm:inline">M. Sani Satio R Hasibuan</strong>
            </span>
          </div>
        </div>

      </div>
    </main>

    <!-- FOOTER -->
    <footer class="py-6 border-t border-slate-200 dark:border-slate-800 bg-white/80 dark:bg-slate-950/80 backdrop-blur-md text-center relative z-20">
      <div class="max-w-6xl mx-auto px-4 text-slate-400 text-xs">
        <p>&copy; <?= date('Y'); ?> Alumni XII IPA 2. All rights reserved.</p>
      </div>
    </footer>
    
  </div>

  <!-- GAMBAR KARAKTER (BOCCHI) MUNCUL DARI KANAN BAWAH -->
  <div class="fixed bottom-[-10px] right-[-15px] md:right-[-5px] z-30 pointer-events-none drop-shadow-2xl animate-slide-up">
    <!-- Pastikan file gambar kamu diberi nama bocchi.png dan disimpan di folder assets -->
    <img src="assets/bocchi.png" alt="Anime Character" class="w-48 md:w-56 lg:w-72 object-contain opacity-95 transition-opacity duration-300">
  </div>

  <!-- SCRIPT THEME, BINTANG & SOUND -->
  <script>
    // FUNGSI UNTUK MEMAINKAN SUARA SAAT KARTU DONASI DIKLIK
    function playSound() {
      const audio = document.getElementById("donasiSound");
      audio.currentTime = 0; // Mengulang suara dari awal jika diklik cepat
      audio.play();
    }

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