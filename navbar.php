<?php
// Mendapatkan nama file yang sedang dibuka
$current_page = basename($_SERVER['PHP_SELF']);

// Cek apakah halaman saat ini adalah Beranda (index.php atau kosong)
$is_home = ($current_page == 'index.php' || $current_page == '');
?>

<!-- navbar.php -->
<nav class="absolute top-0 left-0 w-full z-50 p-5 pr-28 md:px-12 flex items-center justify-start">
    <!-- Menu Kiri -->
    <div class="flex items-center gap-4 md:gap-8 flex-wrap">
        <a href="index.php" class="text-xs md:text-sm font-bold transition-colors drop-shadow-md pb-1 
            <?= ($current_page == 'index.php' || $current_page == '') 
                ? ($is_home ? 'text-cyan-400 border-b-2 border-cyan-400' : 'text-blue-600 dark:text-cyan-400 border-b-2 border-blue-600 dark:border-cyan-400') 
                : ($is_home ? 'text-slate-200 hover:text-cyan-400' : 'text-slate-800 dark:text-slate-200 hover:text-blue-600 dark:hover:text-cyan-400') ?>">
            Beranda
        </a>
        
        <a href="struktur.php" class="text-xs md:text-sm font-bold transition-colors drop-shadow-md pb-1 
            <?= ($current_page == 'struktur.php') 
                ? ($is_home ? 'text-cyan-400 border-b-2 border-cyan-400' : 'text-blue-600 dark:text-cyan-400 border-b-2 border-blue-600 dark:border-cyan-400') 
                : ($is_home ? 'text-slate-200 hover:text-cyan-400' : 'text-slate-800 dark:text-slate-200 hover:text-blue-600 dark:hover:text-cyan-400') ?>">
            Struktur Kelas
        </a>
        
        <a href="kontak.php" class="text-xs md:text-sm font-bold transition-colors drop-shadow-md pb-1 
            <?= ($current_page == 'kontak.php') 
                ? ($is_home ? 'text-cyan-400 border-b-2 border-cyan-400' : 'text-blue-600 dark:text-cyan-400 border-b-2 border-blue-600 dark:border-cyan-400') 
                : ($is_home ? 'text-slate-200 hover:text-cyan-400' : 'text-slate-800 dark:text-slate-200 hover:text-blue-600 dark:hover:text-cyan-400') ?>">
            Hubungi Kami
        </a>
        
        <a href="donasi.php" class="text-xs md:text-sm font-bold transition-colors drop-shadow-md pb-1 
            <?= ($current_page == 'donasi.php') 
                ? ($is_home ? 'text-cyan-400 border-b-2 border-cyan-400' : 'text-blue-600 dark:text-cyan-400 border-b-2 border-blue-600 dark:border-cyan-400') 
                : ($is_home ? 'text-slate-200 hover:text-cyan-400' : 'text-slate-800 dark:text-slate-200 hover:text-blue-600 dark:hover:text-cyan-400') ?>">
            Donasi
        </a>
    </div>
</nav>