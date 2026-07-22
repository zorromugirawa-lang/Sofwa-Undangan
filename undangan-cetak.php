<?php
require 'db_connect.php';

$templates = [];
$db_error_message = '';

if ($conn !== null) {
    try {
        $stmt = $conn->query("SELECT * FROM undangan_cetak ORDER BY id ASC");
        if ($stmt) {
            $templates = $stmt->fetchAll();
        }
    } catch (PDOException $e) {
        $db_error_message = 'Gagal mengambil data katalog cetak.';
    }
} else {
    $db_error_message = 'Koneksi database bermasalah.';
}

// Fallback data jika tabel kosong atau database bermasalah
if (empty($templates)) {
    $templates = [
        ['nama' => 'Indie Kode IN 83', 'bahan' => 'BC 150 gram', 'jenis_blangko' => 'Soft Cover', 'ukuran_terbuka' => '21 X 19.5 (Cm)', 'ukuran_terlipat' => '11.7 X 19.5 (Cm)', 'ukuran_plastik' => '12 X 24 (Cm)', 'harga' => 1500, 'thumbnail' => 'assets/indie.png'],
        ['nama' => 'Undangan Indie IN 86', 'bahan' => 'BC 150 gram', 'jenis_blangko' => 'Soft Cover', 'ukuran_terbuka' => '21 X 19.5 (Cm)', 'ukuran_terlipat' => '11.8 X 19.5 (Cm)', 'ukuran_plastik' => '12 X 22 (Cm)', 'harga' => 1500, 'thumbnail' => 'assets/indie86.png'],
        ['nama' => 'Premium Acrylic', 'bahan' => 'BC 150 gram', 'jenis_blangko' => 'Soft Cover', 'ukuran_terbuka' => '21 X 19.5 (Cm)', 'ukuran_terlipat' => '11.7 X 19.5 (Cm)', 'ukuran_plastik' => '12 X 24 (Cm)', 'harga' => 1500, 'thumbnail' => 'assets/indie81.png']
    ];
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Undangan Cetak & Fisik - Sofwa Undangan</title>

  <link rel="icon" href="img/SF.png" type="image/png" />

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,600;0,700;1,500&display=swap"
    rel="stylesheet">

  <!-- Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

  <style>
    :root {
      --primary: #8b5e3c;
      --primary-light: #b5835a;
      --secondary: #e6d3c1;
      --bg: #f7f5f2;
      --text: #333;
      --text-muted: #666;
      --white: #fff;
      --shadow: 0 8px 30px rgba(0, 0, 0, 0.06);
      --shadow-hover: 0 15px 35px rgba(139, 94, 60, 0.15);
      --gradient: linear-gradient(135deg, #8b5e3c, #b5835a);
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: 'Poppins', sans-serif;
    }

    body {
      background: var(--bg);
      color: var(--text);
      overflow-x: hidden;
    }

    header {
      background: rgba(255, 255, 255, 0.85);
      backdrop-filter: blur(12px);
      -webkit-backdrop-filter: blur(12px);
      padding: 15px 40px;
      display: flex;
      flex-wrap: wrap;
      justify-content: space-between;
      align-items: center;
      position: sticky;
      top: 0;
      z-index: 90;
      border-bottom: 1px solid rgba(255, 255, 255, 0.4);
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02);
    }

    header h1 {
      color: var(--primary);
      font-family: 'Playfair Display', serif;
      font-size: 24px;
      font-weight: 700;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    header h1 i {
      font-size: 20px;
    }

    header a.logo-link {
      text-decoration: none;
    }

    nav a {
      margin-left: 25px;
      text-decoration: none;
      color: var(--text-muted);
      font-weight: 500;
      font-size: 15px;
      transition: color 0.3s ease;
    }

    nav a:hover,
    nav a.active {
      color: var(--primary);
    }

    .btn-nav {
      background: var(--gradient);
      color: var(--white) !important;
      padding: 8px 22px;
      border-radius: 30px;
      box-shadow: 0 4px 10px rgba(139, 94, 60, 0.3);
      transition: all 0.3s ease;
      font-size: 14px;
      border: none;
      cursor: pointer;
      margin-left: 25px;
    }

    .btn-nav:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 15px rgba(139, 94, 60, 0.4);
    }

    .sidebar-overlay {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(0, 0, 0, 0.35);
      z-index: 150;
      opacity: 0;
      visibility: hidden;
      transition: all 0.3s ease;
    }

    .sidebar-overlay.active {
      opacity: 1;
      visibility: visible;
    }

    .hamburger {
      display: none;
      cursor: pointer;
      z-index: 250;
      position: relative;
    }

    .hamburger div {
      width: 25px;
      height: 3px;
      background-color: var(--primary);
      margin: 5px 0;
      transition: all 0.3s cubic-bezier(0.645, 0.045, 0.355, 1);
    }

    .hamburger.active div:nth-child(1) { transform: translateY(8px) rotate(45deg); }
    .hamburger.active div:nth-child(2) { opacity: 0; transform: translateX(-20px); }
    .hamburger.active div:nth-child(3) { transform: translateY(-8px) rotate(-45deg); }

    nav {
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      justify-content: center;
    }

    nav a {
      margin-left: 15px;
      text-decoration: none;
      color: var(--text-muted);
      font-weight: 500;
      font-size: 14px;
      transition: color 0.3s ease;
    }

    nav a:hover, nav a.active {
      color: var(--primary);
    }

    /* Mobile only adjustments */
    @media (max-width: 768px) {
      html, body {
        max-width: 100%;
        overflow-x: hidden;
      }

      header {
        flex-direction: column;
        padding: 15px 10px;
      }

      header h1 {
        margin-bottom: 15px;
      }

      .hamburger {
        display: none !important;
      }

      nav {
        position: static !important;
        width: 100% !important;
        height: auto !important;
        background: transparent !important;
        flex-direction: row !important;
        flex-wrap: wrap !important;
        justify-content: center !important;
        padding: 0 !important;
        box-shadow: none !important;
        border: none !important;
      }

      nav a {
        opacity: 1 !important;
        transform: none !important;
        margin: 4px !important;
        padding: 6px 12px !important;
        font-size: 12px !important;
        background: rgba(139, 94, 60, 0.08);
        border-radius: 20px !important;
        color: var(--primary) !important;
      }

      nav a:hover, nav a.active {
        background: var(--gradient) !important;
        color: var(--white) !important;
      }

      .mobile-only-icon {
        display: none !important;
      }

      .btn-nav {
        display: none !important;
      }
        color: var(--text);
        text-decoration: none;
        padding: 10px 15px;
        border-radius: 12px;
        transition: all 0.3s ease;
        opacity: 0;
        transform: translateX(20px);
      }

      nav.active a {
        opacity: 1;
        transform: translateX(0);
      }

      nav.active a:nth-child(1) { transition: opacity 0.3s ease 0.1s, transform 0.3s ease 0.1s; }
      nav.active a:nth-child(2) { transition: opacity 0.3s ease 0.15s, transform 0.3s ease 0.15s; }
      nav.active a:nth-child(3) { transition: opacity 0.3s ease 0.2s, transform 0.3s ease 0.2s; }

      nav a:hover, nav a.active {
        background-color: rgba(139, 94, 60, 0.08);
        color: var(--primary);
        padding-left: 20px;
      }

      .btn-nav {
        display: flex !important;
        justify-content: center;
        align-items: center;
        margin-top: 20px;
        opacity: 0;
        transform: translateX(20px);
      }

      nav.active .btn-nav {
        opacity: 1;
        transform: translateX(0);
        transition: opacity 0.3s ease 0.3s, transform 0.3s ease 0.3s;
      }

      .gallery-container {
        grid-template-columns: repeat(3, 1fr) !important;
        gap: 8px !important;
        padding: 10px 5px 30px !important;
      }
      .gallery-img-wrapper {
        height: 150px !important;
      }
      .gallery-info {
        padding: 8px !important;
      }
      .gallery-info h3 {
        font-size: 11px !important;
        margin-bottom: 4px !important;
      }
      .gallery-info p {
        font-size: 9px !important;
        line-height: 1.2 !important;
        margin-bottom: 8px !important;
      }
      .price-tag {
        padding: 3px 6px !important;
        font-size: 9px !important;
      }
      .gallery-info .btn-wa {
        padding: 6px !important;
        font-size: 9px !important;
        border-radius: 6px !important;
      }

      .page-header {
        padding: 40px 20px 20px;
      }

      .page-header h2 {
        font-size: 32px;
      }
    }

    .page-header {
      padding: 80px 40px 40px;
      text-align: center;
    }

    .page-header h2 {
      font-family: 'Playfair Display', serif;
      font-size: 42px;
      color: var(--primary);
      margin-bottom: 15px;
    }

    .page-header p {
      color: var(--text-muted);
      font-size: 16px;
      max-width: 650px;
      margin: 0 auto;
      line-height: 1.6;
    }

    .gallery-container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 20px 40px 80px;
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
      gap: 30px;
    }

    .gallery-item {
      background: var(--white);
      border-radius: 20px;
      overflow: hidden;
      box-shadow: var(--shadow);
      transition: all 0.4s ease;
      cursor: pointer;
    }

    .gallery-item:hover {
      transform: translateY(-10px);
      box-shadow: var(--shadow-hover);
    }

    .gallery-img-wrapper {
      position: relative;
      height: 350px;
      overflow: hidden;
      background: #f0f0f0;
    }

    .gallery-img-wrapper img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.5s ease;
      cursor: pointer;
    }

    .gallery-item:hover .gallery-img-wrapper img {
      transform: scale(1.05);
    }

    .gallery-info {
      padding: 25px;
      text-align: center;
    }

    .gallery-info h3 {
      font-family: 'Playfair Display', serif;
      color: var(--primary);
      font-size: 22px;
      margin-bottom: 8px;
    }

    .gallery-info p {
      color: var(--text-muted);
      font-size: 14px;
      margin-bottom: 15px;
    }

    .price-tag {
      display: inline-block;
      background: var(--secondary);
      color: var(--primary);
      padding: 5px 15px;
      border-radius: 20px;
      font-weight: 600;
      font-size: 14px;
    }

    .contact-banner {
      background: var(--gradient);
      color: var(--white);
      text-align: center;
      padding: 50px 20px;
      margin: 40px 0;
    }

    .contact-banner h3 {
      font-family: 'Playfair Display', serif;
      font-size: 32px;
      margin-bottom: 15px;
    }

    .contact-banner p {
      font-size: 16px;
      margin-bottom: 25px;
      opacity: 0.9;
    }

    .btn-wa {
      background: #25D366;
      color: white;
      padding: 12px 30px;
      border-radius: 30px;
      text-decoration: none;
      font-weight: 600;
      display: inline-flex;
      align-items: center;
      gap: 10px;
      transition: all 0.3s ease;
    }

    .btn-wa:hover {
      background: #128C7E;
      transform: translateY(-2px);
    }

    footer {
      background: var(--primary);
      color: var(--white);
      text-align: center;
      padding: 30px 20px;
    }

    /* Floating Music Button */
    .floating-music-btn {
      position: fixed;
      bottom: 30px;
      left: 30px;
      width: 50px;
      height: 50px;
      border-radius: 50%;
      background: var(--gradient);
      color: white;
      border: none;
      box-shadow: 0 4px 15px rgba(139, 94, 60, 0.4);
      cursor: pointer;
      z-index: 100;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 24px;
      transition: all 0.3s ease;
    }

    .floating-music-btn .fa-compact-disc {
      animation: spin 4s linear infinite;
    }

    .floating-music-btn.paused .fa-compact-disc {
      animation-play-state: paused;
    }

    .floating-music-btn:hover {
      transform: scale(1.1);
    }

    /* Lightbox Styles */
    .lightbox-overlay {
      position: fixed;
      top: 0; left: 0; right: 0; bottom: 0;
      background: rgba(0,0,0,0.85);
      z-index: 1000;
      display: flex;
      justify-content: center;
      align-items: center;
      opacity: 0;
      visibility: hidden;
      transition: all 0.3s ease;
      backdrop-filter: blur(5px);
      -webkit-backdrop-filter: blur(5px);
    }
    .lightbox-overlay.active {
      opacity: 1;
      visibility: visible;
    }
    .lightbox-img {
      max-width: 90%;
      max-height: 90vh;
      border-radius: 12px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.5);
      transform: scale(0.9);
      transition: transform 0.3s ease;
      object-fit: contain;
    }
    .lightbox-overlay.active .lightbox-img {
      transform: scale(1);
    }
    .lightbox-close {
      position: absolute;
      top: 20px;
      right: 20px;
      color: white;
      font-size: 32px;
      cursor: pointer;
      background: rgba(0,0,0,0.5);
      width: 44px; height: 44px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: background 0.3s;
      z-index: 1001;
    }
    .lightbox-close:hover {
      background: rgba(255,255,255,0.2);
    }

    @keyframes spin {
      100% {
        transform: rotate(360deg);
      }
    }
  </style>
</head>

<body>
  <header>
    <a href="index.php" class="logo-link">
      <h1><i class="fas fa-heart"></i> Sofwa Undangan</h1>
    </a>
    <nav id="nav-menu">
      <a href="index.php#home" class="nav-link">Beranda</a>
      <a href="index.php#template" class="nav-link">Undangan Digital</a>
      <a href="index.php#template" class="nav-link">Undangan Khitan</a>
      <a href="undangan-cetak.php" class="active nav-link">Undangan Cetak</a>
      <a href="index.php#tentang" class="nav-link">Tentang Kami</a>
    </nav>
  </header>

  <div class="page-header">
    <h2>Koleksi Undangan Cetak</h2>
    <p>Selain undangan digital, kami juga menyediakan layanan pembuatan undangan fisik dengan desain premium, elegan,
      bahan berkualitas tinggi, dan harga terjangkau.</p>
    <a href="index.php" class="btn-nav" style="display: inline-block; margin-top: 20px; margin-left: 0; padding: 10px 25px;"><i class="fas fa-arrow-left"></i> Kembali ke Beranda</a>
  </div>

  <div class="gallery-container">
    <?php foreach ($templates as $t): ?>
    <div class="gallery-item">
      <div class="gallery-img-wrapper">
        <img src="<?php echo htmlspecialchars($t['thumbnail']); ?>" alt="<?php echo htmlspecialchars($t['nama']); ?>">
      </div>
      <div class="gallery-info">
        <h3><?php echo htmlspecialchars($t['nama']); ?></h3>
        <p style="text-align: left; font-size: 14px; line-height: 1.6; margin-bottom: 15px;">
          Bahan : <?php echo htmlspecialchars($t['bahan']); ?><br>
          Jenis Blangko : <?php echo htmlspecialchars($t['jenis_blangko']); ?><br>
          Ukuran Terbuka : <?php echo htmlspecialchars($t['ukuran_terbuka']); ?><br>
          Ukuran Terlipat : <?php echo htmlspecialchars($t['ukuran_terlipat']); ?><br>
          Ukuran Plastik : <?php echo htmlspecialchars($t['ukuran_plastik']); ?>
        </p>
        <div class="price-tag">Rp <?php echo number_format($t['harga'], 0, ',', '.'); ?> / pcs</div>
        <div style="margin-top: 20px;">
          <a href="https://wa.me/6281393678911?text=Halo%20Admin%20Sofwa%20Undangan,%20saya%20ingin%20memesan%20undangan%20cetak%20dengan%20desain%20*<?php echo urlencode($t['nama']); ?>*.%20Mohon%20info%20lebih%20lanjut." target="_blank" style="display: block; width: 100%; padding: 12px; background: var(--gradient); color: var(--white); border-radius: 12px; text-decoration: none; font-weight: 600; font-size: 14px; transition: transform 0.2s ease, box-shadow 0.2s ease; box-shadow: 0 4px 10px rgba(139, 94, 60, 0.2);" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 15px rgba(139, 94, 60, 0.3)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 10px rgba(139, 94, 60, 0.2)';">
            <i class="fab fa-whatsapp"></i> Pesan via WhatsApp
          </a>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
  
  <div style="text-align: center; margin: 20px 0 50px;">
    <a href="index.php" class="btn-nav" style="display: inline-block; margin-left: 0; padding: 12px 30px; font-size: 15px;"><i class="fas fa-home"></i> Kembali ke Menu Utama</a>
  </div>

  <div class="contact-banner">
    <h3>Tertarik dengan Undangan Cetak Kami?</h3>
    <p>Hubungi admin kami untuk konsultasi desain, bahan, dan harga custom sesuai kebutuhan Anda.</p>
    <a href="https://wa.me/6281234567890" target="_blank" class="btn-wa">
      <i class="fab fa-whatsapp"></i> Hubungi Admin via WhatsApp
    </a>
  </div>

  <footer>
    <p>&copy; 2026 Sofwa Undangan. All Rights Reserved. | <a href="admin_login.php" style="color: #ccc; text-decoration: none;">Admin Login</a></p>
  </footer>

  <!-- Lightbox -->
  <div class="lightbox-overlay" id="lightbox">
    <div class="lightbox-close" id="lightbox-close">&times;</div>
    <img src="" alt="Preview" class="lightbox-img" id="lightbox-img">
  </div>

  <!-- Background Music -->
  <audio id="bg-music" loop>
    <source src="assets/Olivia.mp3" type="audio/mpeg">
  </audio>
  <button id="music-btn" class="floating-music-btn paused">
    <i class="fas fa-compact-disc"></i>
  </button>

  <script>
    // JS Logic
    const musicBtn = document.getElementById('music-btn');
    const bgMusic = document.getElementById('bg-music');
    let isPlaying = false;

    musicBtn.addEventListener('click', () => {
      if (isPlaying) {
        bgMusic.pause();
        musicBtn.classList.add('paused');
      } else {
        bgMusic.play().catch(e => console.log("Audio play failed:", e));
        musicBtn.classList.remove('paused');
      }
      isPlaying = !isPlaying;
    });

    // Lightbox Logic
    const lightbox = document.getElementById('lightbox');
    const lightboxImg = document.getElementById('lightbox-img');
    const lightboxClose = document.getElementById('lightbox-close');

    document.querySelectorAll('.gallery-img-wrapper img').forEach(img => {
      img.addEventListener('click', function() {
        lightboxImg.src = this.src;
        lightbox.classList.add('active');
        document.body.style.overflow = 'hidden';
      });
    });

    function closeLightbox() {
      lightbox.classList.remove('active');
      document.body.style.overflow = '';
      setTimeout(() => { lightboxImg.src = ''; }, 300);
    }

    lightboxClose.addEventListener('click', closeLightbox);
    lightbox.addEventListener('click', function(e) {
      if(e.target === lightbox) closeLightbox();
    });
  </script>
</body>

</html>
