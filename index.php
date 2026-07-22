<?php
require_once 'db_connect.php';

$templates = [];
$using_fallback = false;

if ($conn !== null) {
    try {
        $stmt = $conn->query("SELECT * FROM templates ORDER BY category DESC, id ASC");
        $templates = $stmt->fetchAll();
    } catch (PDOException $e) {
        $using_fallback = true;
    }
} else {
    $using_fallback = true;
}

if (empty($templates)) {
    $templates = [
        ['id' => 1, 'nama' => 'Template Elegant', 'kategori' => 'Pernikahan', 'gambar' => 'img/cover_elegant.png', 'harga' => 150000, 'preview' => '#'],
        ['id' => 2, 'nama' => 'Template Minimalis', 'kategori' => 'Pernikahan', 'gambar' => 'img/cover_minimalist.png', 'harga' => 100000, 'preview' => '#'],
        ['id' => 3, 'nama' => 'Template Adat Jawa', 'kategori' => 'Pernikahan', 'gambar' => 'img/cover_adat_jawa.png', 'harga' => 125000, 'preview' => '#'],
    ];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Sofwa Undangan - Undangan Digital Premium & Estetik</title>
  <link rel="canonical" href="https://baiinst.my.id/halaman-utama" />
  <meta name="description" content="Sofwa Undangan menawarkan jasa pembuatan undangan digital estetik, elegan, dan interaktif untuk pernikahan dan khitanan dengan harga terjangkau." />
  <meta name="keywords" content="undangan digital, undangan online, undangan pernikahan, undangan khitanan, undangan murah, undangan estetik" />
  <meta name="author" content="Sofwa Undangan" />
  <link rel="icon" href="img/SF.png" type="image/png" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

  <style>
    :root {
      --primary: #8b5e3c;
      --primary-light: #b5835a;
      --primary-dark: #6b4226;
      --secondary: #e6d3c1;
      --bg: #f7f5f2;
      --bg-warm: #faf7f4;
      --text: #333;
      --text-muted: #777;
      --white: #fff;
      --shadow-sm: 0 4px 16px rgba(139, 94, 60, 0.06);
      --shadow: 0 8px 30px rgba(139, 94, 60, 0.08);
      --shadow-lg: 0 20px 50px rgba(139, 94, 60, 0.12);
      --shadow-hover: 0 16px 40px rgba(139, 94, 60, 0.18);
      --gradient: linear-gradient(135deg, #8b5e3c, #b5835a);
      --gradient-dark: linear-gradient(135deg, #6b4226, #8b5e3c);
      --gradient-warm: linear-gradient(135deg, #f7f0e8, #f0e4d7);
      --accent-wedding: #d4a373;
      --accent-khitan: #2a9d8f;
      --radius: 20px;
      --radius-sm: 14px;
      --radius-xs: 10px;
    }

    * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Poppins', sans-serif; }
    html { scroll-behavior: smooth; }
    body { background: var(--bg); color: var(--text); overflow-x: hidden; }

    /* ===== STATUS BANNER ===== */
    .status-banner {
      background: linear-gradient(135deg, #fef2f2, #fde8e8);
      color: #b71c1c;
      text-align: center;
      padding: 10px 16px;
      font-size: 13px;
      font-weight: 500;
      position: relative;
      z-index: 100;
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 8px;
      border-bottom: 1px solid #fecaca;
    }

    /* ===== HEADER ===== */
    header {
      background: rgba(255, 255, 255, 0.8);
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
      padding: 14px 40px;
      display: flex;
      flex-wrap: wrap;
      justify-content: space-between;
      align-items: center;
      position: sticky;
      top: 0;
      z-index: 90;
      border-bottom: 1px solid rgba(139, 94, 60, 0.06);
      transition: box-shadow 0.3s ease;
    }

    header.scrolled { box-shadow: 0 4px 30px rgba(0, 0, 0, 0.06); }

    header h1 {
      color: var(--primary);
      font-family: 'Playfair Display', serif;
      font-size: 22px;
      font-weight: 700;
      display: flex;
      align-items: center;
      gap: 10px;
      text-decoration: none;
    }

    header h1 img {
      height: 34px;
      width: auto;
      border-radius: 8px;
      box-shadow: 0 2px 8px rgba(139, 94, 60, 0.15);
    }

    nav { display: flex; flex-wrap: wrap; align-items: center; gap: 6px; }

    nav a.nav-link {
      text-decoration: none;
      color: var(--text-muted);
      font-weight: 500;
      font-size: 14px;
      padding: 8px 16px;
      border-radius: 30px;
      transition: all 0.3s ease;
    }

    nav a.nav-link:hover, nav a.nav-link.active {
      color: var(--primary);
      background: rgba(139, 94, 60, 0.06);
    }

    .btn-nav {
      background: var(--gradient) !important;
      color: var(--white) !important;
      padding: 10px 24px !important;
      border-radius: 30px !important;
      box-shadow: 0 4px 12px rgba(139, 94, 60, 0.25);
      transition: all 0.3s ease !important;
      font-size: 14px;
      cursor: pointer;
    }

    .btn-nav:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 20px rgba(139, 94, 60, 0.35);
    }

    .hamburger {
      display: none;
      cursor: pointer;
      z-index: 250;
      padding: 8px;
      border-radius: 10px;
      transition: background 0.2s;
    }

    .hamburger:hover { background: rgba(139, 94, 60, 0.06); }

    .hamburger div {
      width: 24px;
      height: 2.5px;
      background: var(--primary);
      margin: 5px 0;
      border-radius: 2px;
      transition: all 0.3s cubic-bezier(0.645, 0.045, 0.355, 1);
    }

    .hamburger.active div:nth-child(1) { transform: translateY(7.5px) rotate(45deg); }
    .hamburger.active div:nth-child(2) { opacity: 0; transform: translateX(-16px); }
    .hamburger.active div:nth-child(3) { transform: translateY(-7.5px) rotate(-45deg); }

    .sidebar-overlay {
      position: fixed; top: 0; left: 0; right: 0; bottom: 0;
      background: rgba(0, 0, 0, 0.3); backdrop-filter: blur(4px);
      -webkit-backdrop-filter: blur(4px);
      z-index: 150; opacity: 0; visibility: hidden; transition: all 0.3s ease;
    }
    .sidebar-overlay.active { opacity: 1; visibility: visible; }

    /* ===== HERO ===== */
    .hero {
      position: relative;
      overflow: hidden;
      padding: 100px 80px 80px;
      max-width: 1200px;
      margin: 0 auto;
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 60px;
      align-items: center;
    }

    .hero::before {
      content: '';
      position: absolute;
      top: -200px;
      right: -200px;
      width: 600px;
      height: 600px;
      background: radial-gradient(circle, rgba(139, 94, 60, 0.06) 0%, transparent 70%);
      border-radius: 50%;
      pointer-events: none;
    }

    .hero::after {
      content: '';
      position: absolute;
      bottom: -150px;
      left: -150px;
      width: 400px;
      height: 400px;
      background: radial-gradient(circle, rgba(181, 131, 90, 0.05) 0%, transparent 70%);
      border-radius: 50%;
      pointer-events: none;
    }

    .hero-content { position: relative; z-index: 2; }

    .hero-badge {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      background: rgba(139, 94, 60, 0.08);
      color: var(--primary);
      padding: 8px 18px;
      border-radius: 30px;
      font-size: 13px;
      font-weight: 500;
      margin-bottom: 24px;
      border: 1px solid rgba(139, 94, 60, 0.1);
    }

    .hero-badge i { font-size: 14px; }

    .hero-text h2 {
      font-family: 'Playfair Display', serif;
      font-size: 52px;
      color: var(--primary);
      margin-bottom: 20px;
      line-height: 1.15;
      letter-spacing: -0.5px;
    }

    .hero-text h2 span {
      background: var(--gradient);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }

    .hero-text p {
      font-size: 16px;
      color: var(--text-muted);
      margin-bottom: 36px;
      line-height: 1.8;
      max-width: 480px;
    }

    .hero-buttons { display: flex; flex-wrap: wrap; gap: 14px; align-items: center; }

    .btn {
      padding: 14px 30px;
      border-radius: 14px;
      border: none;
      cursor: pointer;
      font-size: 15px;
      font-weight: 600;
      transition: all 0.3s ease;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 10px;
      font-family: 'Poppins', sans-serif;
    }

    .btn-primary {
      background: var(--gradient);
      color: var(--white);
      box-shadow: 0 6px 20px rgba(139, 94, 60, 0.3);
    }

    .btn-primary:hover {
      transform: translateY(-3px);
      box-shadow: 0 10px 30px rgba(139, 94, 60, 0.4);
    }

    .btn-secondary {
      background: var(--white);
      color: var(--primary);
      border: 1.5px solid rgba(139, 94, 60, 0.2);
    }

    .btn-secondary:hover {
      border-color: var(--primary);
      background: rgba(139, 94, 60, 0.04);
      transform: translateY(-3px);
    }

    .hero-stats {
      display: flex;
      gap: 32px;
      margin-top: 40px;
      padding-top: 28px;
      border-top: 1px solid rgba(139, 94, 60, 0.1);
    }

    .hero-stat h4 {
      font-size: 24px;
      font-weight: 700;
      color: var(--primary);
    }

    .hero-stat p {
      font-size: 12px;
      color: var(--text-muted);
      margin-bottom: 0;
    }

    .hero-image {
      position: relative;
      z-index: 2;
    }

    .hero-image-wrapper {
      position: relative;
      border-radius: 28px;
      overflow: hidden;
      box-shadow: var(--shadow-lg);
      border: 6px solid var(--white);
    }

    .hero-image-wrapper img {
      width: 100%;
      display: block;
      transition: transform 0.6s ease;
    }

    .hero-image-wrapper:hover img { transform: scale(1.03); }

    .hero-float-card {
      position: absolute;
      bottom: -20px;
      left: -20px;
      background: var(--white);
      border-radius: 16px;
      padding: 16px 20px;
      box-shadow: var(--shadow);
      display: flex;
      align-items: center;
      gap: 12px;
      animation: floatUp 3s ease-in-out infinite;
    }

    .hero-float-card .icon {
      width: 44px;
      height: 44px;
      border-radius: 12px;
      background: rgba(42, 157, 143, 0.1);
      display: flex;
      align-items: center;
      justify-content: center;
      color: var(--accent-khitan);
      font-size: 18px;
    }

    .hero-float-card .text strong { display: block; font-size: 14px; color: var(--text); }
    .hero-float-card .text span { font-size: 12px; color: var(--text-muted); }

    @keyframes floatUp {
      0%, 100% { transform: translateY(0); }
      50% { transform: translateY(-8px); }
    }

    .hero-decor {
      position: absolute;
      border-radius: 50%;
      pointer-events: none;
      opacity: 0.6;
    }

    .hero-decor-1 {
      top: 30px; right: 40px;
      width: 12px; height: 12px;
      background: var(--accent-wedding);
      animation: decorFloat 4s ease-in-out infinite;
    }

    .hero-decor-2 {
      top: 60%; right: -10px;
      width: 8px; height: 8px;
      background: var(--accent-khitan);
      animation: decorFloat 5s ease-in-out infinite 1s;
    }

    .hero-decor-3 {
      bottom: 30%; left: 48%;
      width: 10px; height: 10px;
      background: var(--primary-light);
      animation: decorFloat 6s ease-in-out infinite 0.5s;
    }

    @keyframes decorFloat {
      0%, 100% { transform: translateY(0) scale(1); opacity: 0.6; }
      50% { transform: translateY(-12px) scale(1.2); opacity: 1; }
    }

    /* ===== SECTION SHARED ===== */
    section { padding: 80px 40px; max-width: 1200px; margin: 0 auto; position: relative; z-index: 2; }

    .section-header { text-align: center; margin-bottom: 48px; }

    .section-tag {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: rgba(139, 94, 60, 0.08);
      color: var(--primary);
      padding: 6px 16px;
      border-radius: 20px;
      font-size: 12px;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      margin-bottom: 14px;
    }

    section h3 {
      color: var(--primary);
      font-family: 'Playfair Display', serif;
      font-size: 36px;
      margin-bottom: 12px;
    }

    .section-subtitle {
      color: var(--text-muted);
      font-size: 15px;
      max-width: 500px;
      margin: 0 auto;
      line-height: 1.7;
    }

    /* ===== FEATURES ===== */
    .features-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 24px;
    }

    .feature-card {
      background: var(--white);
      border-radius: var(--radius);
      padding: 32px 28px;
      text-align: center;
      box-shadow: var(--shadow-sm);
      transition: all 0.35s ease;
      border: 1px solid rgba(255, 255, 255, 0.5);
      position: relative;
      overflow: hidden;
    }

    .feature-card::before {
      content: '';
      position: absolute;
      top: 0; left: 0; right: 0;
      height: 4px;
      background: var(--gradient);
      transform: scaleX(0);
      transition: transform 0.35s ease;
      transform-origin: left;
    }

    .feature-card:hover::before { transform: scaleX(1); }

    .feature-card:hover {
      transform: translateY(-6px);
      box-shadow: var(--shadow-hover);
    }

    .feature-icon {
      width: 60px;
      height: 60px;
      border-radius: 16px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 24px;
      margin: 0 auto 18px;
    }

    .feature-icon.warm { background: rgba(139, 94, 60, 0.08); color: var(--primary); }
    .feature-icon.teal { background: rgba(42, 157, 143, 0.08); color: var(--accent-khitan); }
    .feature-icon.gold { background: rgba(212, 163, 115, 0.12); color: #b8860b; }

    .feature-card h4 { font-size: 17px; color: var(--text); margin-bottom: 8px; font-weight: 600; }
    .feature-card p { font-size: 13px; color: var(--text-muted); line-height: 1.6; }

    /* ===== FILTER ===== */
    .filter-tabs {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 10px;
      margin-bottom: 40px;
    }

    .filter-btn {
      background: var(--white);
      border: 1.5px solid rgba(139, 94, 60, 0.15);
      color: var(--text-muted);
      padding: 10px 22px;
      border-radius: 30px;
      cursor: pointer;
      font-weight: 500;
      font-size: 14px;
      font-family: 'Poppins', sans-serif;
      transition: all 0.3s ease;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 6px;
    }

    .filter-btn:hover { border-color: var(--primary); color: var(--primary); }

    .filter-btn.active {
      background: var(--gradient);
      border-color: transparent;
      color: var(--white);
      box-shadow: 0 4px 12px rgba(139, 94, 60, 0.25);
    }

    /* ===== CARDS ===== */
    .templates-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
      gap: 28px;
    }

    .card {
      background: var(--white);
      border-radius: var(--radius);
      overflow: hidden;
      box-shadow: var(--shadow);
      transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
      display: flex;
      flex-direction: column;
      border: 1px solid rgba(255, 255, 255, 0.5);
    }

    .card:hover {
      transform: translateY(-8px);
      box-shadow: var(--shadow-hover);
    }

    .card-thumb-container {
      position: relative;
      height: 380px;
      overflow: hidden;
    }

    .card-thumb-container::after {
      content: '';
      position: absolute;
      bottom: 0; left: 0; right: 0;
      height: 50%;
      background: linear-gradient(to top, rgba(0,0,0,0.25), transparent);
      pointer-events: none;
      opacity: 0;
      transition: opacity 0.4s ease;
    }

    .card:hover .card-thumb-container::after { opacity: 1; }

    .card img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.6s cubic-bezier(0.165, 0.84, 0.44, 1);
    }

    .card:hover img { transform: scale(1.06); }

    .category-badge {
      position: absolute;
      top: 14px;
      left: 14px;
      padding: 6px 14px;
      border-radius: 20px;
      font-size: 11px;
      font-weight: 600;
      color: var(--white);
      text-transform: uppercase;
      letter-spacing: 0.5px;
      z-index: 2;
      backdrop-filter: blur(8px);
      -webkit-backdrop-filter: blur(8px);
    }

    .badge-pernikahan { background: rgba(212, 163, 115, 0.9); }
    .badge-khitanan { background: rgba(42, 157, 143, 0.9); }

    .card-quick-view {
      position: absolute;
      bottom: 14px;
      right: 14px;
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.9);
      backdrop-filter: blur(8px);
      -webkit-backdrop-filter: blur(8px);
      display: flex;
      align-items: center;
      justify-content: center;
      color: var(--primary);
      font-size: 16px;
      text-decoration: none;
      z-index: 2;
      opacity: 0;
      transform: translateY(8px);
      transition: all 0.3s ease;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .card:hover .card-quick-view { opacity: 1; transform: translateY(0); }
    .card-quick-view:hover { background: var(--gradient); color: var(--white); }

    .card-body {
      padding: 22px;
      display: flex;
      flex-direction: column;
      flex-grow: 1;
    }

    .card h4 {
      font-size: 17px;
      color: #2c2c2c;
      margin-bottom: 6px;
      font-weight: 600;
    }

    .card-price {
      font-size: 18px;
      color: var(--primary);
      font-weight: 700;
      margin-bottom: 18px;
    }

    .card-actions {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 10px;
      margin-top: auto;
    }

    .btn-card {
      padding: 11px;
      border-radius: var(--radius-xs);
      font-size: 13px;
      font-weight: 500;
      text-align: center;
      cursor: pointer;
      text-decoration: none;
      transition: all 0.25s ease;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 6px;
      font-family: 'Poppins', sans-serif;
      border: none;
    }

    .btn-card-preview {
      background: rgba(139, 94, 60, 0.06);
      border: 1.5px solid rgba(139, 94, 60, 0.15);
      color: var(--primary);
    }

    .btn-card-preview:hover {
      background: rgba(139, 94, 60, 0.12);
      border-color: var(--primary);
    }

    .btn-card-order {
      background: var(--gradient);
      color: var(--white);
      box-shadow: 0 4px 12px rgba(139, 94, 60, 0.2);
    }

    .btn-card-order:hover {
      box-shadow: 0 6px 18px rgba(139, 94, 60, 0.3);
      transform: translateY(-2px);
    }

    /* ===== ABOUT ===== */
    .about-section { background: var(--bg-warm); border-radius: 0; padding: 80px 0; max-width: 100%; }
    .about-section section { max-width: 1200px; margin: 0 auto; padding: 0 40px; }

    .about-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 60px;
      align-items: center;
    }

    .about-image {
      border-radius: 24px;
      overflow: hidden;
      box-shadow: var(--shadow-lg);
    }

    .about-image img { width: 100%; display: block; }

    .about-text h3 {
      font-size: 32px;
      margin-bottom: 18px;
      text-align: left;
    }

    .about-text p {
      color: var(--text-muted);
      line-height: 1.8;
      margin-bottom: 16px;
      font-size: 15px;
    }

    .about-features {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 16px;
      margin-top: 28px;
    }

    .about-feat {
      display: flex;
      align-items: center;
      gap: 10px;
      font-size: 14px;
      font-weight: 500;
      color: var(--text);
    }

    .about-feat i {
      width: 32px;
      height: 32px;
      border-radius: 8px;
      background: rgba(139, 94, 60, 0.08);
      color: var(--primary);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 14px;
      flex-shrink: 0;
    }

    /* ===== FOOTER ===== */
    footer {
      background: var(--primary-dark);
      color: var(--white);
      position: relative;
      z-index: 2;
      overflow: hidden;
    }

    footer::before {
      content: '';
      position: absolute;
      top: 0; left: 0; right: 0;
      height: 3px;
      background: var(--gradient);
    }

    .footer-content {
      max-width: 1200px;
      margin: 0 auto;
      padding: 50px 40px 30px;
      display: grid;
      grid-template-columns: 1.5fr 1fr 1fr;
      gap: 40px;
    }

    .footer-brand h3 {
      font-family: 'Playfair Display', serif;
      font-size: 22px;
      margin-bottom: 12px;
    }

    .footer-brand p {
      font-size: 14px;
      opacity: 0.7;
      line-height: 1.7;
      max-width: 320px;
    }

    .footer-col h4 {
      font-size: 14px;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 1px;
      margin-bottom: 16px;
      opacity: 0.6;
    }

    .footer-col a {
      display: block;
      color: var(--white);
      text-decoration: none;
      font-size: 14px;
      padding: 6px 0;
      opacity: 0.75;
      transition: all 0.2s;
    }

    .footer-col a:hover { opacity: 1; padding-left: 6px; }

    .footer-bottom {
      text-align: center;
      padding: 20px 40px;
      border-top: 1px solid rgba(255, 255, 255, 0.1);
      font-size: 13px;
      opacity: 0.5;
    }

    /* ===== FLOWERS ===== */
    .flower {
      position: fixed;
      left: -150px;
      z-index: 1;
      width: 180px;
      opacity: 0;
      transition: all 2s cubic-bezier(0.22, 1, 0.36, 1);
      pointer-events: none;
      filter: drop-shadow(0 5px 15px rgba(0, 0, 0, 0.08));
    }
    .flower.visible { opacity: 0.7; }
    .flower-left.visible { left: -20px; }
    .flower-right { left: auto; right: -150px; }
    .flower-right.visible { right: -20px; }
    .flower-1 { top: 200px; width: 220px; }
    .flower-2 { top: 550px; width: 180px; }
    .flower-3 { top: 280px; width: 220px; }
    .flower-4 { top: 650px; width: 180px; }

    @keyframes flowerSway { 0% { transform: rotate(0deg) translateY(0); } 50% { transform: rotate(5deg) translateY(-8px); } 100% { transform: rotate(0deg) translateY(0); } }
    @keyframes flowerSwayOpp { 0% { transform: rotate(0deg) translateY(0); } 50% { transform: rotate(-5deg) translateY(8px); } 100% { transform: rotate(0deg) translateY(0); } }

    .flower img { width: 100%; }
    .flower-1 img { animation: flowerSway 6s ease-in-out infinite; transform-origin: bottom left; }
    .flower-2 img { animation: flowerSwayOpp 7s ease-in-out infinite; transform-origin: bottom left; }
    .flower-3 img { animation: flowerSway 8s ease-in-out infinite; transform-origin: bottom right; }
    .flower-4 img { animation: flowerSwayOpp 9s ease-in-out infinite; transform-origin: bottom right; }

    /* ===== MODAL ===== */
    .modal-overlay {
      position: fixed; top: 0; left: 0; right: 0; bottom: 0;
      background: rgba(0, 0, 0, 0.45);
      backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px);
      display: flex; align-items: center; justify-content: center;
      z-index: 1000; opacity: 0; visibility: hidden; transition: all 0.3s ease;
    }
    .modal-overlay.active { opacity: 1; visibility: visible; }

    .modal-container {
      background: var(--white);
      border-radius: 24px;
      width: 90%; max-width: 460px;
      padding: 32px;
      box-shadow: 0 24px 60px rgba(0, 0, 0, 0.2);
      position: relative;
      transform: translateY(30px) scale(0.97);
      transition: all 0.35s cubic-bezier(0.175, 0.885, 0.32, 1.155);
    }
    .modal-overlay.active .modal-container { transform: translateY(0) scale(1); }

    .modal-close-btn {
      position: absolute; top: 16px; right: 16px;
      background: rgba(0, 0, 0, 0.04); border: none;
      width: 34px; height: 34px; border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
      cursor: pointer; color: var(--text); transition: background 0.2s;
    }
    .modal-close-btn:hover { background: rgba(0, 0, 0, 0.08); }

    .modal-header { margin-bottom: 24px; }
    .modal-header h3 { font-family: 'Playfair Display', serif; color: var(--primary); font-size: 22px; margin-bottom: 4px; }
    .modal-header p { font-size: 13px; color: var(--text-muted); }

    .selected-template-info {
      background: #faf8f5; border-left: 4px solid var(--primary);
      padding: 14px 16px; border-radius: 0 12px 12px 0;
      margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center;
    }
    .selected-template-name { font-weight: 600; font-size: 15px; color: #333; }
    .selected-template-price { font-weight: 700; color: var(--primary); font-size: 15px; }

    .form-group { margin-bottom: 18px; text-align: left; }
    .form-group label { display: block; font-size: 13px; font-weight: 500; color: #555; margin-bottom: 8px; }

    .input-wrapper { position: relative; }
    .input-wrapper i { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: var(--primary-light); font-size: 16px; }

    .form-control {
      width: 100%; padding: 13px 14px 13px 42px;
      border: 1.5px solid #e5e1dc; border-radius: 12px;
      font-size: 14px; color: var(--text); background: #faf9f8;
      transition: all 0.3s ease; font-family: 'Poppins', sans-serif;
    }
    .form-control:focus { outline: none; border-color: var(--primary); background: var(--white); box-shadow: 0 0 0 3px rgba(139, 94, 60, 0.1); }

    .modal-submit-btn {
      width: 100%; padding: 14px; border-radius: 12px;
      background: var(--gradient); color: var(--white); border: none;
      font-size: 15px; font-weight: 600; cursor: pointer;
      box-shadow: 0 6px 15px rgba(139, 94, 60, 0.25);
      transition: all 0.3s ease; display: flex; justify-content: center; align-items: center; gap: 10px;
      font-family: 'Poppins', sans-serif;
    }
    .modal-submit-btn:hover { transform: translateY(-2px); box-shadow: 0 10px 24px rgba(139, 94, 60, 0.35); }
    .modal-submit-btn:disabled { background: #ccc; box-shadow: none; cursor: not-allowed; }

    /* ===== MUSIC BTN ===== */
    .floating-music-btn {
      position: fixed; bottom: 28px; left: 28px;
      width: 52px; height: 52px; border-radius: 50%;
      background: var(--gradient); color: white; border: none;
      box-shadow: 0 6px 20px rgba(139, 94, 60, 0.4);
      cursor: pointer; z-index: 100;
      display: flex; align-items: center; justify-content: center; font-size: 22px;
      transition: all 0.3s ease;
    }
    .floating-music-btn .fa-compact-disc { animation: spin 4s linear infinite; }
    .floating-music-btn.paused .fa-compact-disc { animation-play-state: paused; }
    .floating-music-btn:hover { transform: scale(1.1); }
    @keyframes spin { 100% { transform: rotate(360deg); } }

    /* ===== SCROLL REVEAL ===== */
    .reveal {
      opacity: 0;
      transform: translateY(30px);
      transition: all 0.7s cubic-bezier(0.22, 1, 0.36, 1);
    }
    .reveal.visible {
      opacity: 1;
      transform: translateY(0);
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 992px) {
      .hero { padding: 60px 40px; gap: 40px; }
      .hero-text h2 { font-size: 40px; }
      .about-grid { gap: 40px; }
      .footer-content { grid-template-columns: 1fr 1fr; }
    }

    @media (max-width: 768px) {
      header { padding: 12px 16px; }
      header h1 { font-size: 19px; }

      nav {
        position: fixed; top: 0; right: -300px;
        width: 280px; height: 100vh;
        background: rgba(255, 255, 255, 0.97);
        backdrop-filter: blur(24px); -webkit-backdrop-filter: blur(24px);
        flex-direction: column; padding: 80px 20px 24px; gap: 4px;
        box-shadow: -4px 0 40px rgba(0,0,0,0.12); z-index: 200;
        transition: right 0.35s cubic-bezier(0.4, 0, 0.2, 1); opacity: 0;
      }
      nav.active { right: 0; opacity: 1; }

      nav a.nav-link {
        margin: 0; padding: 13px 18px; font-size: 15px;
        border-radius: 12px; color: var(--text);
        opacity: 0; transform: translateX(20px);
      }
      nav.active a.nav-link { opacity: 1; transform: translateX(0); }
      nav.active a.nav-link:nth-child(1) { transition: all 0.3s ease 0.08s; }
      nav.active a.nav-link:nth-child(2) { transition: all 0.3s ease 0.12s; }
      nav.active a.nav-link:nth-child(3) { transition: all 0.3s ease 0.16s; }
      nav.active a.nav-link:nth-child(4) { transition: all 0.3s ease 0.2s; }
      nav.active a.nav-link:nth-child(5) { transition: all 0.3s ease 0.24s; }

      nav a.nav-link:hover, nav.active a.nav-link:hover {
        background: rgba(139, 94, 60, 0.08); color: var(--primary);
      }

      .hamburger { display: block; }
      .btn-nav {
        display: flex !important; justify-content: center; align-items: center;
        background: var(--gradient); color: var(--white) !important;
        margin-top: 16px; box-shadow: 0 4px 12px rgba(139, 94, 60, 0.25);
        opacity: 0; transform: translateX(20px); padding: 14px 20px;
      }
      nav.active .btn-nav { opacity: 1; transform: translateX(0); transition: all 0.3s ease 0.28s; }

      .sidebar-overlay { display: block; }

      section { padding: 60px 16px; }
      .section-header { margin-bottom: 32px; }
      section h3 { font-size: 28px; }

      .hero {
        grid-template-columns: 1fr;
        text-align: center;
        padding: 48px 16px 40px;
        gap: 28px;
      }

      .hero::before, .hero::after { display: none; }

      .hero-text h2 { font-size: 30px; }
      .hero-text p { font-size: 14px; margin-left: auto; margin-right: auto; }
      .hero-buttons { justify-content: center; }
      .hero-stats { justify-content: center; gap: 24px; }
      .hero-stat h4 { font-size: 20px; }

      .hero-image-wrapper { border-radius: 20px; border-width: 4px; }
      .hero-float-card { bottom: -12px; left: 12px; padding: 12px 16px; }
      .hero-float-card .icon { width: 36px; height: 36px; font-size: 15px; }
      .hero-float-card .text strong { font-size: 13px; }
      .hero-float-card .text span { font-size: 11px; }

      .hero-decor { display: none; }

      .features-grid { grid-template-columns: 1fr; gap: 16px; }
      .feature-card { padding: 24px 22px; }

      .filter-tabs { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-bottom: 24px; }
      .filter-btn { padding: 10px 12px; font-size: 13px; justify-content: center; }

      .templates-grid { grid-template-columns: repeat(2, 1fr); gap: 14px; }
      .card-thumb-container { height: 200px; }
      .card-body { padding: 14px; }
      .card h4 { font-size: 13px; margin-bottom: 4px; }
      .card-price { font-size: 14px; margin-bottom: 12px; }
      .category-badge { font-size: 9px; padding: 4px 8px; top: 8px; left: 8px; }
      .card-quick-view { width: 34px; height: 34px; font-size: 14px; bottom: 8px; right: 8px; }
      .card-actions { gap: 6px; }
      .btn-card { padding: 8px 4px; font-size: 11px; border-radius: 8px; }

      .about-section { padding: 60px 0; }
      .about-section section { padding: 0 16px; }
      .about-grid { grid-template-columns: 1fr; gap: 32px; }
      .about-text h3 { text-align: center; font-size: 26px; }
      .about-text p { text-align: center; }
      .about-features { grid-template-columns: 1fr; }
      .about-feat { justify-content: center; }

      .footer-content { grid-template-columns: 1fr; gap: 28px; padding: 36px 16px 24px; }
      .footer-brand p { max-width: 100%; }

      .flower { width: 100px !important; }
      .flower-1, .flower-3 { width: 110px !important; }
      .flower-left.visible { left: -30px !important; }
      .flower-right.visible { right: -30px !important; }

      .modal-container { padding: 24px; border-radius: 20px; }
    }

    @media (max-width: 420px) {
      .hero-text h2 { font-size: 26px; }
      .hero-stats { gap: 16px; }
      .hero-stat h4 { font-size: 18px; }
      .btn { padding: 12px 22px; font-size: 14px; }
    }

    @media (max-width: 380px) {
      .templates-grid { grid-template-columns: 1fr; gap: 16px; }
      .card-thumb-container { height: 280px; }
      .filter-tabs { grid-template-columns: 1fr; }
    }
  </style>
</head>

<body>

  <?php if ($using_fallback): ?>
    <div class="status-banner">
      <i class="fas fa-exclamation-triangle"></i>
      <span>Mode Demo Offline: Database tidak aktif. Menggunakan data cadangan.</span>
    </div>
  <?php endif; ?>

  <div class="sidebar-overlay" id="sidebar-overlay"></div>

  <!-- Flowers -->
  <div class="flower flower-left flower-1"><img src="img/bunga1.png" alt=""></div>
  <div class="flower flower-left flower-2"><img src="img/bunga2.png" alt=""></div>
  <div class="flower flower-right flower-3"><img src="img/bunga1.png" alt=""></div>
  <div class="flower flower-right flower-4"><img src="img/bunga2.png" alt=""></div>

  <!-- Header -->
  <header id="main-header">
    <h1><img src="img/SF.png" alt="Sofwa"> Sofwa Undangan</h1>
    <div class="hamburger" id="hamburger-btn">
      <div></div><div></div><div></div>
    </div>
    <nav id="nav-menu">
      <a href="#home" class="nav-link active">Beranda</a>
      <a href="#keunggulan" class="nav-link">Keunggulan</a>
      <a href="#template" class="nav-link">Template</a>
      <a href="undangan-cetak.php" class="nav-link">Cetak</a>
      <a href="#tentang" class="nav-link">Tentang</a>
      <a href="#template" class="btn-nav"><i class="fas fa-paper-plane"></i> Pesan Sekarang</a>
    </nav>
  </header>

  <!-- Hero -->
  <section class="hero" id="home">
    <div class="hero-decor hero-decor-1"></div>
    <div class="hero-decor hero-decor-2"></div>
    <div class="hero-decor hero-decor-3"></div>

    <div class="hero-content">
      <div class="hero-text">
        <div class="hero-badge">
          <i class="fas fa-sparkles"></i> Undangan Digital Premium
        </div>
        <h2>Wujudkan Undangan Impian <span>Estetik & Elegan</span></h2>
        <p>Buat momen spesialmu lebih berkesan dengan undangan digital modern, praktis, dan penuh estetika. Tersedia desain premium untuk pernikahan dan khitanan.</p>
        <div class="hero-buttons">
          <a href="#template" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Pesan Sekarang</a>
          <a href="#template" class="btn btn-secondary"><i class="far fa-eye"></i> Lihat Template</a>
        </div>
        <div class="hero-stats">
          <div class="hero-stat">
            <h4>50+</h4>
            <p>Desain Tersedia</p>
          </div>
          <div class="hero-stat">
            <h4>200+</h4>
            <p>Pelanggan Puas</p>
          </div>
          <div class="hero-stat">
            <h4>24 Jam</h4>
            <p>Pengerjaan Cepat</p>
          </div>
        </div>
      </div>
    </div>

    <div class="hero-image">
      <div class="hero-image-wrapper">
        <img src="img/hero.png" alt="Contoh Undangan Digital">
      </div>
      <div class="hero-float-card">
        <div class="icon"><i class="fas fa-check"></i></div>
        <div class="text">
          <strong>Pesan Mudah</strong>
          <span>Chat langsung via WhatsApp</span>
        </div>
      </div>
    </div>
  </section>

  <!-- Features -->
  <section id="keunggulan">
    <div class="section-header reveal">
      <div class="section-tag"><i class="fas fa-star"></i> Kenapa Pilih Kami</div>
      <h3>Keunggulan Sofwa Undangan</h3>
      <p class="section-subtitle">Kami memberikan yang terbaik untuk momen spesial Anda</p>
    </div>
    <div class="features-grid">
      <div class="feature-card reveal">
        <div class="feature-icon warm"><i class="fas fa-palette"></i></div>
        <h4>Desain Estetik</h4>
        <p>Setiap desain dibuat dengan penuh perhatian untuk menghasilkan undangan yang elegan dan memukau.</p>
      </div>
      <div class="feature-card reveal">
        <div class="feature-icon teal"><i class="fas fa-mobile-alt"></i></div>
        <h4>Ramah Smartphone</h4>
        <p>Undangan dapat diakses dengan sempurna di semua perangkat, dari desktop hingga smartphone.</p>
      </div>
      <div class="feature-card reveal">
        <div class="feature-icon gold"><i class="fas fa-bolt"></i></div>
        <h4>Pengerjaan Cepat</h4>
        <p>Undangan digital Anda siap dalam 24 jam setelah data dan pembayaran dikonfirmasi.</p>
      </div>
    </div>
  </section>

  <!-- Templates -->
  <section id="template">
    <div class="section-header reveal">
      <div class="section-tag"><i class="fas fa-images"></i> Koleksi Template</div>
      <h3>Template Undangan</h3>
      <p class="section-subtitle">Pilih desain terbaik untuk merayakan hari kebahagiaan Anda</p>
    </div>

    <div class="filter-tabs reveal">
      <button class="filter-btn active" data-filter="semua"><i class="fas fa-th"></i> Semua</button>
      <button class="filter-btn" data-filter="pernikahan"><i class="fas fa-heart"></i> Pernikahan</button>
      <button class="filter-btn" data-filter="khitanan"><i class="fas fa-mosque"></i> Khitanan</button>
      <a href="undangan-cetak.php" class="filter-btn"><i class="fas fa-print"></i> Undangan Cetak</a>
    </div>

    <div class="templates-grid" id="templates-container">
      <?php foreach ($templates as $template): ?>
        <?php 
          $name = $template['nama'] ?? $template['name'] ?? 'Template';
          $price = $template['harga'] ?? $template['price'] ?? 0;
          $preview = $template['preview'] ?? $template['path'] ?? '#';
          $category = $template['kategori'] ?? $template['category'] ?? 'Pernikahan';
          $thumbnail = $template['gambar'] ?? $template['thumbnail'] ?? 'img/placeholder.png';
          $id = $template['id'] ?? 0;
          $formatted_price = 'Rp ' . number_format($price, 0, ',', '.');
          $category_class = strtolower($category);
          $badge_class = ($category === 'Pernikahan') ? 'badge-pernikahan' : 'badge-khitanan';
        ?>
        <div class="card reveal" data-category="<?php echo $category_class; ?>">
          <div class="card-thumb-container">
            <span class="category-badge <?php echo $badge_class; ?>"><?php echo htmlspecialchars($category); ?></span>
            <a href="<?php echo htmlspecialchars($preview); ?>" target="_blank" class="card-quick-view" title="Preview"><i class="far fa-eye"></i></a>
            <img src="<?php echo htmlspecialchars($thumbnail); ?>" alt="<?php echo htmlspecialchars($name); ?>" loading="lazy" />
          </div>
          <div class="card-body">
            <h4><?php echo htmlspecialchars($name); ?></h4>
            <div class="card-price"><?php echo $formatted_price; ?></div>
            <div class="card-actions">
              <a href="<?php echo htmlspecialchars($preview); ?>" target="_blank" class="btn-card btn-card-preview">
                <i class="far fa-eye"></i> Preview
              </a>
              <button onclick="openOrderModal(<?php echo $id; ?>, '<?php echo htmlspecialchars(addslashes($name)); ?>', '<?php echo $formatted_price; ?>')" class="btn-card btn-card-order">
                <i class="fas fa-shopping-cart"></i> Pesan
              </button>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- About -->
  <section class="about-section" id="tentang">
    <div class="about-grid">
      <div class="about-image reveal">
        <img src="img/hero.png" alt="Sofwa Undangan">
      </div>
      <div class="about-text reveal">
        <div class="section-tag"><i class="fas fa-info-circle"></i> Tentang Kami</div>
        <h3>Sofwa Undangan</h3>
        <p>Sofwa Undangan adalah penyedia jasa pembuatan undangan digital profesional dengan desain yang estetik, elegan, dan ramah pengguna.</p>
        <p>Kami berkomitmen membantu Anda membagikan kabar bahagia kepada kerabat dan keluarga secara praktis, ramah lingkungan, dan modern.</p>
        <div class="about-features">
          <div class="about-feat"><i class="fas fa-check"></i> Desain Premium</div>
          <div class="about-feat"><i class="fas fa-check"></i> Harga Terjangkau</div>
          <div class="about-feat"><i class="fas fa-check"></i> Pengerjaan Cepat</div>
          <div class="about-feat"><i class="fas fa-check"></i> Support WhatsApp</div>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer>
    <div class="footer-content">
      <div class="footer-brand">
        <h3>Sofwa Undangan</h3>
        <p>Penyedia jasa pembuatan undangan digital estetik, elegan, dan interaktif untuk pernikahan dan khitanan.</p>
      </div>
      <div class="footer-col">
        <h4>Navigasi</h4>
        <a href="#home">Beranda</a>
        <a href="#keunggulan">Keunggulan</a>
        <a href="#template">Template</a>
        <a href="undangan-cetak.php">Undangan Cetak</a>
      </div>
      <div class="footer-col">
        <h4>Kontak</h4>
        <a href="https://wa.me/6281234567890" target="_blank"><i class="fab fa-whatsapp"></i> WhatsApp</a>
        <a href="admin_login.php"><i class="fas fa-lock"></i> Admin Login</a>
      </div>
    </div>
    <div class="footer-bottom">
      &copy; 2026 Sofwa Undangan. Hak Cipta Dilindungi.
    </div>
  </footer>

  <!-- Order Modal -->
  <div class="modal-overlay" id="order-modal">
    <div class="modal-container">
      <button class="modal-close-btn" onclick="closeOrderModal()" aria-label="Tutup">
        <i class="fas fa-times"></i>
      </button>
      <div class="modal-header">
        <h3>Formulir Pemesanan</h3>
        <p>Isi formulir berikut untuk melanjutkan pemesanan.</p>
      </div>
      <div class="selected-template-info">
        <div>
          <span style="font-size: 12px; color: var(--text-muted); display: block; text-align: left;">Desain Terpilih</span>
          <span class="selected-template-name" id="modal-template-name">Nama Template</span>
        </div>
        <div class="selected-template-price" id="modal-template-price">Rp 0</div>
      </div>
      <form id="order-form" onsubmit="submitOrder(event)">
        <input type="hidden" id="order-template-id" name="template_id" value="">
        <div class="form-group">
          <label for="customer_name">Nama Lengkap</label>
          <div class="input-wrapper">
            <i class="far fa-user"></i>
            <input type="text" id="customer_name" name="customer_name" class="form-control" placeholder="Contoh: Ahmad Fauzi" required>
          </div>
        </div>
        <div class="form-group">
          <label for="whatsapp_number">Nomor WhatsApp</label>
          <div class="input-wrapper">
            <i class="fab fa-whatsapp"></i>
            <input type="tel" id="whatsapp_number" name="whatsapp_number" class="form-control" placeholder="Contoh: 081234567890" pattern="[0-9]{9,15}" title="Masukkan nomor telepon, 9-15 digit" required>
          </div>
        </div>
        <button type="submit" class="modal-submit-btn" id="submit-btn">
          <i class="fab fa-whatsapp"></i> Kirim & Lanjutkan ke WA
        </button>
      </form>
    </div>
  </div>

  <script>
    // Hamburger
    const hamburgerBtn = document.getElementById('hamburger-btn');
    const navMenu = document.getElementById('nav-menu');
    const sidebarOverlay = document.getElementById('sidebar-overlay');

    function toggleSidebar() {
      hamburgerBtn.classList.toggle('active');
      navMenu.classList.toggle('active');
      sidebarOverlay.classList.toggle('active');
      document.body.style.overflow = navMenu.classList.contains('active') ? 'hidden' : 'auto';
    }

    hamburgerBtn.addEventListener('click', toggleSidebar);
    sidebarOverlay.addEventListener('click', toggleSidebar);
    navMenu.querySelectorAll('a').forEach(link => {
      link.addEventListener('click', () => { if (navMenu.classList.contains('active')) toggleSidebar(); });
    });

    // Header scroll shadow
    window.addEventListener('scroll', () => {
      document.getElementById('main-header').classList.toggle('scrolled', window.scrollY > 20);
    });

    // Active nav
    window.addEventListener('scroll', () => {
      let scrollPos = window.scrollY + 120;
      document.querySelectorAll('section[id]').forEach(section => {
        if (scrollPos >= section.offsetTop && scrollPos < (section.offsetTop + section.offsetHeight)) {
          let id = section.getAttribute('id');
          document.querySelectorAll('nav a.nav-link').forEach(link => {
            link.classList.remove('active');
            if (link.getAttribute('href') === '#' + id) link.classList.add('active');
          });
        }
      });
    });

    // Filter
    const filterButtons = document.querySelectorAll('.filter-btn[data-filter]');
    const cards = document.querySelectorAll('.card[data-category]');

    filterButtons.forEach(button => {
      button.addEventListener('click', () => {
        filterButtons.forEach(btn => btn.classList.remove('active'));
        button.classList.add('active');
        const filterValue = button.getAttribute('data-filter');
        cards.forEach(card => {
          if (filterValue === 'semua' || card.getAttribute('data-category') === filterValue) {
            card.style.display = 'flex';
            setTimeout(() => { card.style.opacity = '1'; card.style.transform = 'scale(1)'; }, 30);
          } else {
            card.style.opacity = '0';
            card.style.transform = 'scale(0.95)';
            setTimeout(() => { card.style.display = 'none'; }, 300);
          }
        });
      });
    });

    // Flowers
    window.addEventListener('DOMContentLoaded', () => {
      setTimeout(() => {
        document.querySelectorAll('.flower').forEach((f, i) => {
          setTimeout(() => f.classList.add('visible'), i * 400);
        });
      }, 400);
    });

    // Scroll reveal
    const revealEls = document.querySelectorAll('.reveal');
    const revealObserver = new IntersectionObserver((entries) => {
      entries.forEach(entry => { if (entry.isIntersecting) { entry.target.classList.add('visible'); revealObserver.unobserve(entry.target); } });
    }, { threshold: 0.15 });
    revealEls.forEach(el => revealObserver.observe(el));

    // Modal
    const orderModal = document.getElementById('order-modal');
    const orderForm = document.getElementById('order-form');
    const submitBtn = document.getElementById('submit-btn');

    function openOrderModal(id, name, price) {
      document.getElementById('order-template-id').value = id;
      document.getElementById('modal-template-name').innerText = name;
      document.getElementById('modal-template-price').innerText = price;
      orderModal.classList.add('active');
      document.body.style.overflow = 'hidden';
    }

    function closeOrderModal() {
      orderModal.classList.remove('active');
      document.body.style.overflow = 'auto';
      orderForm.reset();
    }

    orderModal.addEventListener('click', (e) => { if (e.target === orderModal) closeOrderModal(); });

    function submitOrder(e) {
      e.preventDefault();
      submitBtn.disabled = true;
      submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
      fetch('submit_order.php', { method: 'POST', body: new FormData(orderForm) })
        .then(r => r.json())
        .then(data => {
          if (data.status === 'success') { window.open(data.redirect_url, '_blank'); closeOrderModal(); }
          else { alert('Kesalahan: ' + data.message); }
        })
        .catch(() => { alert('Gagal mengirim pesanan.'); })
        .finally(() => { submitBtn.disabled = false; submitBtn.innerHTML = '<i class="fab fa-whatsapp"></i> Kirim & Lanjutkan ke WA'; });
    }
  </script>

  <audio id="bg-music" loop>
    <source src="assets/Olivia.mp3" type="audio/mpeg">
  </audio>
  <button id="music-btn" class="floating-music-btn paused">
    <i class="fas fa-compact-disc"></i>
  </button>

  <script>
    const musicBtn = document.getElementById('music-btn');
    const bgMusic = document.getElementById('bg-music');
    let isPlaying = false;
    musicBtn.addEventListener('click', () => {
      if (isPlaying) { bgMusic.pause(); musicBtn.classList.add('paused'); }
      else { bgMusic.play().catch(e => {}); musicBtn.classList.remove('paused'); }
      isPlaying = !isPlaying;
    });
  </script>
</body>
</html>
