<?php
include 'koneksi.php';

$kategori_filter = isset($_GET['kategori']) ? $_GET['kategori'] : 'Semua';
$search_query = isset($_GET['search']) ? trim($_GET['search']) : '';

// Total Foto
$total_foto_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM galeri");
$total_foto_data = mysqli_fetch_assoc($total_foto_query);
$total_foto =$total_foto_data['total'];

// Query menggunakan Prepared Statement
$sql = "SELECT * FROM galeri WHERE 1=1";
$params = [];$types = "";

if ($kategori_filter !== 'Semua') {$sql .= " AND kategori = ?";
    $params[] =$kategori_filter;
    $types .= "s";
}

if ($search_query !== '') {$sql .= " AND (judul LIKE ? OR deskripsi LIKE ?)";
    $term = "%" . $search_query . "%";
    $params[] =$term;
    $params[] =$term;
    $types .= "ss";
}

$sql .= " ORDER BY id DESC";

$stmt = mysqli_prepare($conn,$sql);

if (!empty($params)) {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}

mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="id" class="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Galeri Alumni XII IPA 2 - Ponpes Al-Mukhlishin</title>
  
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = { darkMode: 'class' }
  </script>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  
  <style>
    /* Efek Transisi Antar Halaman */
    @keyframes slideInFromRight {
      from { transform: translateX(30px); opacity: 0; }
      to { transform: translateX(0); opacity: 1; }
    }
    @keyframes slideOutToLeft {
      from { transform: translateX(0); opacity: 1; }
      to { transform: translateX(-30px); opacity: 0; }
    }
    
    ::view-transition-old(root) {
      animation: 0.3s ease-in-out both slideOutToLeft;
    }
    ::view-transition-new(root) {
      animation: 0.3s ease-in-out both slideInFromRight;
    }
    body { font-family: 'Plus Jakarta Sans', sans-serif; overflow-x: hidden; }
    .gsap-hide { opacity: 0; visibility: hidden; }
    .gallery-card { transform-style: preserve-3d; }
    .js-tilt-glare { border-radius: 1rem; }

    /* Animasi Ambient Light Opening Screen */
    @keyframes pulseGlow {
      0%, 100% { transform: scale(1) translate(0px, 0px); opacity: 0.4; }
      50% { transform: scale(1.25) translate(25px, -20px); opacity: 0.7; }
    }
    .animate-glow-1 { animation: pulseGlow 8s infinite ease-in-out; }
    .animate-glow-2 { animation: pulseGlow 10s infinite ease-in-out 4s; }

    /* Animasi Bintang Kelap-Kelip */
    @keyframes twinkle {
      0%, 100% { opacity: 0.1; transform: scale(0.5); }
      50% { opacity: 1; transform: scale(1.2); box-shadow: 0 0 8px rgba(255, 255, 255, 0.8); }
    }
    .star-op {
      position: absolute;
      border-radius: 50%;
      background-color: white;
      animation: twinkle linear infinite;
    }
  </style>
</head>
<body class="bg-slate-50 text-slate-800 dark:bg-slate-950 dark:text-slate-100 min-h-screen flex flex-col justify-between transition-colors duration-300 selection:bg-blue-500 selection:text-white relative">

  <!-- BACKGROUND OVERLAY HALAMAN UTAMA (Memecah warna polos) -->
  <div class="fixed inset-0 z-0 pointer-events-none">
    <!-- Efek Cahaya di Sudut Layar -->
    <div class="absolute top-0 left-0 w-[500px] h-[500px] bg-blue-500/5 dark:bg-blue-600/10 rounded-full blur-[100px]"></div>
    <div class="absolute bottom-0 right-0 w-[500px] h-[500px] bg-cyan-500/5 dark:bg-cyan-600/10 rounded-full blur-[100px]"></div>
    <!-- Pola Titik (Dot Pattern) Transparan -->
    <div class="absolute inset-0 bg-[radial-gradient(#cbd5e1_1px,transparent_1px)] dark:bg-[radial-gradient(#334155_1px,transparent_1px)] [background-size:24px_24px] opacity-60 dark:opacity-40"></div>
  </div>

  <canvas id="starfield" class="fixed inset-0 pointer-events-none transition-opacity duration-1000 opacity-0 dark:opacity-100" style="z-index: 1;"></canvas>

  <!-- OPENING SCREEN -->
  <div id="openingScreen" class="fixed inset-0 z-[100] bg-slate-950 flex items-center justify-center overflow-hidden" style="display: none;">
    
    <!-- Animasi Background Ambient Glow & Bintang -->
    <div class="absolute inset-0 pointer-events-none overflow-hidden z-0">
      <!-- Ambient Glow -->
      <div class="absolute -top-32 -left-32 w-96 h-96 bg-blue-600/30 rounded-full blur-[120px] animate-glow-1"></div>
      <div class="absolute -bottom-32 -right-32 w-96 h-96 bg-cyan-500/25 rounded-full blur-[120px] animate-glow-2"></div>
      <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,rgba(15,23,42,0.4)_0%,rgba(2,6,23,0.95)_100%)]"></div>
      
      <!-- Bintang Kelap-Kelip (Disebar Acak) -->
      <div class="star-op w-1 h-1 top-[15%] left-[20%]" style="animation-duration: 3s; animation-delay: 0s;"></div>
      <div class="star-op w-1.5 h-1.5 top-[25%] left-[80%] bg-blue-200" style="animation-duration: 4s; animation-delay: 1s;"></div>
      <div class="star-op w-0.5 h-0.5 top-[50%] left-[10%]" style="animation-duration: 2.5s; animation-delay: 0.5s;"></div>
      <div class="star-op w-1 h-1 top-[70%] left-[75%] bg-cyan-200" style="animation-duration: 5s; animation-delay: 2s;"></div>
      <div class="star-op w-2 h-2 top-[85%] left-[30%] bg-blue-100" style="animation-duration: 3.5s; animation-delay: 1.5s;"></div>
      <div class="star-op w-1 h-1 top-[40%] left-[90%]" style="animation-duration: 2.8s; animation-delay: 0.2s;"></div>
      <div class="star-op w-1.5 h-1.5 top-[10%] left-[60%] bg-cyan-100" style="animation-duration: 4.2s; animation-delay: 0.8s;"></div>
      <div class="star-op w-1 h-1 top-[60%] left-[45%]" style="animation-duration: 3.2s; animation-delay: 1.2s;"></div>
      <div class="star-op w-1 h-1 top-[80%] left-[15%]" style="animation-duration: 4s; animation-delay: 0.7s;"></div>
      <div class="star-op w-1.5 h-1.5 top-[35%] left-[40%] bg-blue-100" style="animation-duration: 3.8s; animation-delay: 1.9s;"></div>
      <div class="star-op w-0.5 h-0.5 top-[5%] left-[85%]" style="animation-duration: 2s; animation-delay: 0.1s;"></div>
      <div class="star-op w-1 h-1 top-[90%] left-[65%] bg-cyan-200" style="animation-duration: 4.5s; animation-delay: 2.5s;"></div>
    </div>

    <!-- Area Konten (Logo & Teks) -->
    <div class="relative z-10 flex flex-col md:flex-row items-center justify-center w-full max-w-5xl px-6">
      <div id="openingLogo" class="relative">
        <div class="absolute inset-0 bg-blue-500/20 blur-3xl rounded-full scale-110"></div>
        <img src="assets/logo.png" alt="Logo Ponpes" class="relative h-40 md:h-52 w-auto object-contain drop-shadow-[0_0_25px_rgba(59,130,246,0.3)]">
      </div>
      <div class="text-center md:text-left flex flex-col items-center md:items-start md:ml-12 mt-6 md:mt-0">
        <div class="mb-2 opening-stagger">
          <span class="text-xs font-semibold tracking-wider text-cyan-300 uppercase bg-cyan-950/60 px-3.5 py-1 rounded-full shadow-sm border border-cyan-500/30 backdrop-blur-md">Ponpes Al-Mukhlishin Sibuhuan</span>
        </div>
        <h1 class="text-3xl md:text-5xl font-extrabold text-white tracking-tight opening-stagger">
          Galeri Alumni <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 via-cyan-300 to-blue-500">XII IPA 2</span>
        </h1>
        <p class="text-blue-300 font-semibold text-sm md:text-base mt-2 opening-stagger">Angkatan 2023 • Perjuangan & Kebersamaan</p>
        <p class="text-slate-300 mt-2 text-xs md:text-sm max-w-xl leading-relaxed opening-stagger">"Setiap sudut pondok punya cerita, setiap foto menyimpan kenangan abadi santri & santriwati XII IPA 2."</p>
        <button onclick="enterGallery()" class="opening-btn mt-6 bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-500 hover:to-cyan-500 text-white text-sm font-bold px-8 py-3 rounded-2xl shadow-lg shadow-blue-500/25 flex items-center gap-2 transition-transform hover:scale-105 active:scale-95 cursor-pointer">
          <span>Masuk ke Galeri</span>
          <svg width="16" height="16" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
        </button>
      </div>
    </div>
  </div>

  <!-- TOMBOL NAVIGASI (Tanpa Kotak) -->
  <div class="fixed top-5 right-6 md:right-12 z-50 flex items-center gap-5 nav-buttons gsap-hide">
    <a href="login.php" title="Login Admin" class="text-slate-300 hover:text-cyan-400 hover:scale-110 transition-all drop-shadow-lg">
      <svg width="24" height="24" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
    </a>
    <button onclick="toggleTheme()" id="themeBtn" aria-label="Toggle Theme" class="text-amber-400 hover:text-amber-300 hover:scale-110 transition-all cursor-pointer drop-shadow-lg">
      <svg width="24" height="24" id="sunIcon" class="w-6 h-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
      <svg width="24" height="24" id="moonIcon" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" /></svg>
    </button>
  </div>

  <div id="mainContentWrapper" class="relative z-10 flex flex-col flex-grow gsap-hide">
    
    <!-- HEADER -->
    <header class="relative w-full pt-20 pb-24 px-6 md:px-12 overflow-hidden shadow-xl header-container" style="background: #0b132b;">
      
      <!-- MENU NAVBAR DARI FILE TERPISAH -->
      <?php include 'navbar.php'; ?>

      <div class="absolute inset-0 pointer-events-none overflow-hidden flex items-center justify-center opacity-30">
        <img src="assets/splash.png" alt="Splash Effect" class="w-full h-full object-cover scale-125 blur-sm">
      </div>
      <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/80 to-transparent pointer-events-none"></div>
      <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,rgba(37,99,235,0.15)_0%,transparent_60%)] pointer-events-none"></div>

      <div class="max-w-4xl mx-auto relative z-10 flex flex-col items-center justify-center text-center gap-4 mt-2">
        <div class="relative header-logo">
          <div class="absolute inset-0 bg-blue-500/30 blur-[30px] rounded-full scale-125"></div>
          <img src="assets/logo.png" alt="Logo" class="relative h-24 md:h-32 w-auto object-contain drop-shadow-[0_0_15px_rgba(255,255,255,0.1)] hover:scale-105 transition-transform duration-500">
        </div>
        
        <div class="flex flex-col items-center header-text w-full">
          <div class="mb-3">
            <span class="text-[9px] md:text-xs font-bold tracking-[0.15em] text-cyan-300 uppercase bg-slate-900/50 backdrop-blur-md px-4 py-1.5 rounded-full border border-cyan-500/30">Ponpes Al-Mukhlishin Sibuhuan</span>
          </div>
          <h1 class="text-3xl md:text-5xl font-black text-white tracking-tight leading-tight">
            Galeri <span class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-300 via-amber-400 to-yellow-500 filter drop-shadow-md">Alumni</span><br/>XII IPA 2
          </h1>
          <p class="text-blue-200 font-medium text-xs md:text-base mt-3 tracking-widest uppercase">Angkatan 2023 • Perjuangan & Kebersamaan</p>
          <p class="text-slate-300 mt-3 text-xs md:text-sm max-w-xl leading-relaxed opacity-80 font-light italic px-4">"Setiap sudut pondok punya cerita, setiap foto menyimpan kenangan abadi."</p>
          
          <form action="index.php" method="GET" class="mt-6 w-full max-w-md flex flex-col sm:flex-row items-center gap-2 bg-white/10 backdrop-blur-md p-1.5 rounded-3xl border border-white/10 shadow-lg relative z-20">
            <?php if($kategori_filter != 'Semua'): ?>
              <input type="hidden" name="kategori" value="<?= htmlspecialchars($kategori_filter, ENT_QUOTES, 'UTF-8'); ?>">
            <?php endif; ?>
            <div class="relative w-full">
              <input type="text" name="search" value="<?= htmlspecialchars($search_query, ENT_QUOTES, 'UTF-8'); ?>" placeholder="Cari kenangan / judul foto..." class="w-full bg-transparent border-none px-4 py-2 pl-10 text-xs md:text-sm text-white placeholder-slate-300 focus:outline-none focus:ring-0">
              <svg width="16" height="16" class="w-4 h-4 text-slate-300 absolute left-3.5 top-2.5 md:top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <button type="submit" class="w-full sm:w-auto bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-500 hover:to-cyan-500 text-white text-xs md:text-sm font-bold px-6 py-2.5 rounded-full transition-all">Cari</button>
          </form>
        </div>
      </div>

      <div class="absolute bottom-0 left-0 w-full overflow-hidden leading-[0] z-10 pointer-events-none">
        <svg class="relative block w-full h-[30px] md:h-[45px]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
            <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V120H0V95.8C59.71,118.08,130.83,123.1,188.74,108.31Z" class="fill-slate-50 dark:fill-slate-950 transition-colors duration-300"></path>
        </svg>
      </div>
    </header>

    <!-- KATEGORI -->
    <div class="flex justify-center gap-2 my-8 px-4 flex-wrap max-w-5xl mx-auto filter-badge relative z-10">
      <?php 
        $kategori_list = ['Semua', 'Keseharian', 'Acara', 'Rihlah', 'Kelulusan'];
        foreach ($kategori_list as$kat) {
            $url = "index.php?kategori=" . urlencode($kat) . (!empty($search_query) ? "&search=" . urlencode($search_query) : "");
            $active_class = ($kategori_filter ==$kat) 
                ? 'bg-gradient-to-r from-blue-600 to-cyan-600 text-white shadow-md border-cyan-500' 
                : 'bg-white/80 dark:bg-slate-900/80 hover:bg-slate-200 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 border-slate-200 dark:border-slate-800';
            echo "<a href='$url' class='px-5 py-2 text-xs md:text-sm font-semibold rounded-2xl border transition duration-200 backdrop-blur-md $active_class'>$kat</a>";
        }
      ?>
    </div>

    <!-- GALLERY GRID -->
    <main class="max-w-6xl mx-auto px-4 pb-16 flex-grow w-full gallery-container relative z-10">
      <?php if (mysqli_num_rows($result) > 0): ?>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
          <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <div class="gallery-card group relative overflow-hidden rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-md transition-shadow">
              
              <!-- Area Foto yang bisa diklik untuk buka modal -->
              <div onclick="openModal('uploads/<?= htmlspecialchars($row['foto'], ENT_QUOTES, 'UTF-8'); ?>', <?= htmlspecialchars(json_encode($row['judul']), ENT_QUOTES, 'UTF-8'); ?>, <?= htmlspecialchars(json_encode($row['deskripsi'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>, <?= htmlspecialchars(json_encode($row['kategori']), ENT_QUOTES, 'UTF-8'); ?>)" class="aspect-square w-full overflow-hidden bg-slate-200 dark:bg-slate-950 relative cursor-pointer">
                <img src="uploads/<?= htmlspecialchars($row['foto'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?= htmlspecialchars($row['judul'], ENT_QUOTES, 'UTF-8'); ?>" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110">
                <span class="absolute top-3 left-3 bg-slate-950/80 backdrop-blur-md text-cyan-300 text-[10px] font-bold px-2.5 py-1 rounded-full uppercase z-10"><?= htmlspecialchars($row['kategori'], ENT_QUOTES, 'UTF-8'); ?></span>
              </div>

              <!-- Bagian Bawah Kartu (Judul & Tombol Like) -->
              <div class="p-4 bg-white/90 dark:bg-slate-900/90 backdrop-blur-sm border-t border-slate-100 dark:border-slate-800 flex items-center justify-between">
                <div class="cursor-pointer truncate pr-2" onclick="openModal('uploads/<?= htmlspecialchars($row['foto'], ENT_QUOTES, 'UTF-8'); ?>', <?= htmlspecialchars(json_encode($row['judul']), ENT_QUOTES, 'UTF-8'); ?>, <?= htmlspecialchars(json_encode($row['deskripsi'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>, <?= htmlspecialchars(json_encode($row['kategori']), ENT_QUOTES, 'UTF-8'); ?>)">
                  <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100 truncate group-hover:text-blue-600 transition"><?= htmlspecialchars($row['judul'], ENT_QUOTES, 'UTF-8'); ?></h3>
                  <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 line-clamp-1"><?= htmlspecialchars($row['deskripsi'] ?: 'Tanpa deskripsi.', ENT_QUOTES, 'UTF-8'); ?></p>
                </div>
                
                <!-- Tombol Like -->
                <button id="like-btn-<?= $row['id']; ?>" onclick="toggleLike(<?= $row['id']; ?>, this)" class="flex items-center gap-1.5 text-slate-500 dark:text-slate-400 bg-slate-100 dark:bg-slate-800 px-3 py-1.5 rounded-full transition-all transform active:scale-90">
                  <svg id="like-icon-<?= $row['id']; ?>" class="w-4 h-4 text-slate-400 dark:text-slate-500 transition-colors" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                  <span id="like-count-<?= $row['id']; ?>" class="text-xs font-bold"><?= (int)$row['likes']; ?></span>
                </button>
              </div>

            </div>
          <?php endwhile; ?>
        </div>
      <?php else: ?>
        <div class="text-center py-16 bg-white/80 dark:bg-slate-900/60 rounded-3xl border border-slate-200 dark:border-slate-800 max-w-md mx-auto p-6">
          <h3 class="text-slate-800 dark:text-slate-200 font-bold text-base">Tidak ada foto ditemukan</h3>
          <a href="index.php" class="inline-block mt-4 text-xs font-semibold text-blue-600 hover:underline">Reset Filter</a>
        </div>
      <?php endif; ?>
    </main>

    <!-- FOOTER -->
    <footer class="py-6 border-t border-slate-200 dark:border-slate-800 bg-white/80 dark:bg-slate-950/80 backdrop-blur-md footer-container relative z-10">
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

  <!-- MODAL -->
  <div id="imageModal" class="fixed inset-0 z-[200] hidden bg-black/95 backdrop-blur-md flex items-center justify-center p-4">
    <div class="relative bg-white dark:bg-slate-900 rounded-3xl overflow-hidden max-w-4xl w-full flex flex-col md:flex-row shadow-2xl border border-slate-700">
      <button onclick="closeModal()" class="absolute top-3 right-3 z-10 bg-slate-800/80 hover:bg-red-500 text-slate-200 rounded-full p-2 transition-colors">
        <svg width="20" height="20" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
      </button>

      <div class="md:w-3/5 bg-black flex items-center justify-center relative">
        <img id="modalImg" src="" alt="" class="max-h-[50vh] md:max-h-[80vh] w-full object-contain">
      </div>

      <div class="md:w-2/5 p-6 md:p-8 flex flex-col justify-between bg-slate-50 dark:bg-slate-900">
        <div>
          <span id="modalKat" class="text-[10px] font-bold text-blue-600 dark:text-cyan-400 bg-blue-100 dark:bg-cyan-900/30 border border-blue-200 dark:border-cyan-500/30 px-3 py-1 rounded-full uppercase tracking-wider"></span>
          <h3 id="modalTitle" class="text-2xl font-black text-slate-900 dark:text-white mt-4 leading-tight"></h3>
          <p id="modalDesc" class="text-slate-600 dark:text-slate-300 text-sm mt-3 leading-relaxed"></p>
        </div>

        <div class="mt-8 flex gap-3 border-t border-slate-200 dark:border-slate-700 pt-6">
          <a id="btnDownload" href="" download class="flex-1 bg-blue-600 hover:bg-blue-500 text-white text-sm font-bold py-3 px-4 rounded-xl flex items-center justify-center gap-2 transition-all shadow-lg hover:shadow-blue-500/30">
            <svg width="20" height="20" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            Unduh Foto
          </a>
          <button onclick="sharePhoto()" class="flex-none bg-slate-200 hover:bg-slate-300 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 py-3 px-4 rounded-xl flex items-center justify-center transition-all shadow-md">
            <svg width="20" height="20" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- SCRIPT -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-tilt/1.8.0/vanilla-tilt.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>

  <script>
    function initTiltEffect() {
      VanillaTilt.init(document.querySelectorAll(".gallery-card"), {
        max: 8,
        speed: 400,
        glare: true,
        "max-glare": 0.3,
        scale: 1.02
      });
    }

    document.addEventListener("DOMContentLoaded", () => {
      document.querySelectorAll("[id^='like-btn-']").forEach(btn => {
        const id = btn.id.replace('like-btn-', '');
        if (localStorage.getItem('liked_' + id) === 'true') {
          const icon = document.getElementById('like-icon-' + id);
          if (icon) {
            icon.setAttribute('fill', 'currentColor');
            icon.classList.remove('text-slate-400', 'dark:text-slate-500');
            icon.classList.add('text-red-500');
          }
        }
      });
    });

    function toggleLike(id, btnElement) {
      const countSpan = document.getElementById('like-count-' + id);
      const icon = document.getElementById('like-icon-' + id);
      
      const isLiked = localStorage.getItem('liked_' + id) === 'true';
      const action = isLiked ? 'unlike' : 'like';
      
      fetch('like.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `id=${id}&action=${action}`
      })
      .then(response => response.text())
      .then(newLikes => {
        if(newLikes.trim() !== "gagal") {
          countSpan.innerText = newLikes;
          
          if (action === 'like') {
            localStorage.setItem('liked_' + id, 'true');
            icon.setAttribute('fill', 'currentColor');
            icon.classList.remove('text-slate-400', 'dark:text-slate-500');
            icon.classList.add('text-red-500');
          } else {
            localStorage.removeItem('liked_' + id);
            icon.removeAttribute('fill');
            icon.classList.remove('text-red-500');
            icon.classList.add('text-slate-400', 'dark:text-slate-500');
          }
          
          btnElement.classList.add('scale-125');
          setTimeout(() => btnElement.classList.remove('scale-125'), 200);
        }
      })
      .catch(error => console.error('Error:', error));
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

    document.addEventListener("DOMContentLoaded", () => {
      const hasVisited = sessionStorage.getItem('visitedGallery');
      const urlParams = new URLSearchParams(window.location.search);
      const isFiltering = urlParams.has('kategori') || urlParams.has('search');
      
      const openingScreen = document.getElementById('openingScreen');
      const mainWrapper = document.getElementById('mainContentWrapper');
      const navBtns = document.querySelector('.nav-buttons');

      if (hasVisited || isFiltering) {
        sessionStorage.setItem('visitedGallery', 'true');
        if(openingScreen) openingScreen.remove();
        
        mainWrapper.classList.remove('gsap-hide');
        navBtns.classList.remove('gsap-hide');
        gsap.set([mainWrapper, navBtns], { opacity: 1, visibility: 'visible' });
        gsap.fromTo(".gallery-card", { opacity: 0, y: 30 }, { 
          opacity: 1, y: 0, duration: 0.5, stagger: 0.05, ease: "power2.out",
          onComplete: initTiltEffect 
        });
      } else {
        document.body.style.overflow = 'hidden';
        openingScreen.style.display = 'flex';
        
        gsap.set("#openingLogo", { scale: 0, opacity: 0 });
        gsap.set(".opening-stagger", { x: 50, opacity: 0 });
        gsap.set(".opening-btn", { y: 20, opacity: 0 });

        const tl = gsap.timeline();
        tl.to("#openingLogo", { scale: 1, opacity: 1, duration: 1, ease: "back.out(1.5)" })
          .to(".opening-stagger", { x: 0, opacity: 1, duration: 0.8, stagger: 0.15, ease: "power3.out" }, "-=0.6")
          .to(".opening-btn", { y: 0, opacity: 1, duration: 0.6, ease: "back.out(1.5)" }, "-=0.4");
      }
    });

    function enterGallery() {
      sessionStorage.setItem('visitedGallery', 'true');
      document.body.style.overflow = 'auto';

      const mainWrapper = document.getElementById('mainContentWrapper');
      const navBtns = document.querySelector('.nav-buttons');
      mainWrapper.classList.remove('gsap-hide');
      navBtns.classList.remove('gsap-hide');
      gsap.set([mainWrapper, navBtns], { visibility: 'visible' });

      const tl = gsap.timeline({
        onComplete: () => { 
          const os = document.getElementById('openingScreen'); 
          if(os) os.remove(); 
          initTiltEffect();
        }
      });

      tl.to("#openingScreen", { yPercent: -100, duration: 0.8, ease: "power3.inOut" })
        .fromTo(navBtns, { opacity: 0 }, { opacity: 1, duration: 0.4 }, "-=0.2")
        .fromTo(".header-container", { opacity: 0 }, { opacity: 1, duration: 0.6 }, "-=0.4")
        .fromTo(".header-logo, .header-text", { y: 30, opacity: 0 }, { y: 0, opacity: 1, duration: 0.8, stagger: 0.2, ease: "power3.out" }, "-=0.4")
        .fromTo(".filter-badge", { opacity: 0 }, { opacity: 1, duration: 0.4 }, "-=0.4")
        .fromTo(".gallery-card", { opacity: 0, y: 50 }, { opacity: 1, y: 0, duration: 0.6, stagger: 0.05, ease: "power2.out" }, "-=0.2")
        .fromTo(".footer-container", { opacity: 0 }, { opacity: 1, duration: 0.4 });
    }

    function openModal(imgSrc, title, desc, kat) {
      document.getElementById('modalImg').src = imgSrc;
      document.getElementById('modalTitle').innerText = title;
      document.getElementById('modalDesc').innerText = desc || 'Tidak ada deskripsi.';
      document.getElementById('modalKat').innerText = kat;
      document.getElementById('btnDownload').href = imgSrc;
      document.getElementById('btnDownload').setAttribute('download', title + '.jpg');
      document.getElementById('imageModal').classList.remove('hidden');
    }
    
    function closeModal() { 
      document.getElementById('imageModal').classList.add('hidden'); 
    }

    function sharePhoto() {
      const title = document.getElementById('modalTitle').innerText;
      const desc = document.getElementById('modalDesc').innerText;
      if (navigator.share) {
        navigator.share({ title: title, text: desc + '\nLihat di Galeri XII IPA 2: ', url: window.location.href }).catch(console.error);
      } else {
        alert("Fitur Share otomatis tidak didukung di browser ini. Silakan copy URL di baris atas.");
      }
    }
  </script>
  <script>
    // Efek geser halus saat mengklik menu navigasi
    document.addEventListener("click", event => {
      const link = event.target.closest("a");
      if (link && link.href && link.href.startsWith(window.location.origin) && !link.getAttribute('target') && !link.getAttribute('download')) {
        if (document.startViewTransition) {
          event.preventDefault();
          document.startViewTransition(() => {
            window.location.href = link.href;
          });
        }
      }
    });
  </script>
</body>
</html>