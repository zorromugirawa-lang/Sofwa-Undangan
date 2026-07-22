<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit;
}

require 'db_connect.php';

$stats = ['digital' => 0, 'cetak' => 0, 'orders' => 0];

if ($conn !== null) {
    try {
        $r = $conn->query("SELECT COUNT(*) as cnt FROM templates");
        $stats['digital'] = $r->fetch()['cnt'] ?? 0;
    } catch (Exception $e) {}
    try {
        $r = $conn->query("SELECT COUNT(*) as cnt FROM undangan_cetak");
        $stats['cetak'] = $r->fetch()['cnt'] ?? 0;
    } catch (Exception $e) {}
    try {
        $r = $conn->query("SELECT COUNT(*) as cnt FROM orders");
        $stats['orders'] = $r->fetch()['cnt'] ?? 0;
    } catch (Exception $e) {}
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Sofwa Undangan</title>
    <link rel="icon" href="img/SF.png" type="image/png" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
        :root {
            --primary: #8b5e3c;
            --primary-light: #b5835a;
            --primary-dark: #6b4226;
            --secondary: #e6d3c1;
            --bg: #f4f1ed;
            --text: #333;
            --text-muted: #777;
            --white: #fff;
            --shadow: 0 4px 24px rgba(139, 94, 60, 0.08);
            --shadow-lg: 0 12px 40px rgba(139, 94, 60, 0.12);
            --gradient: linear-gradient(135deg, #8b5e3c, #b5835a);
            --gradient-dark: linear-gradient(135deg, #6b4226, #8b5e3c);
            --radius: 20px;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 260px;
            height: 100vh;
            background: var(--white);
            box-shadow: 4px 0 24px rgba(0,0,0,0.04);
            z-index: 100;
            display: flex;
            flex-direction: column;
            transition: transform 0.3s ease;
        }

        .sidebar-brand {
            padding: 28px 24px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid #f0ece8;
        }

        .sidebar-brand img {
            width: 40px;
            height: 40px;
            border-radius: 12px;
        }

        .sidebar-brand h2 {
            font-family: 'Playfair Display', serif;
            font-size: 18px;
            color: var(--primary);
        }

        .sidebar-brand span {
            display: block;
            font-family: 'Poppins', sans-serif;
            font-size: 11px;
            color: var(--text-muted);
            font-weight: 400;
        }

        .sidebar-nav {
            padding: 20px 16px;
            flex: 1;
            overflow-y: auto;
        }

        .sidebar-nav-label {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #bbb;
            padding: 0 12px;
            margin-bottom: 10px;
            margin-top: 16px;
        }

        .sidebar-nav-label:first-child { margin-top: 0; }

        .sidebar-nav a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            border-radius: 12px;
            text-decoration: none;
            color: var(--text-muted);
            font-size: 14px;
            font-weight: 500;
            transition: all 0.25s ease;
            margin-bottom: 4px;
        }

        .sidebar-nav a i {
            width: 20px;
            text-align: center;
            font-size: 16px;
        }

        .sidebar-nav a:hover {
            background: rgba(139, 94, 60, 0.06);
            color: var(--primary);
        }

        .sidebar-nav a.active {
            background: var(--gradient);
            color: var(--white);
            box-shadow: 0 4px 12px rgba(139, 94, 60, 0.25);
        }

        .sidebar-footer {
            padding: 16px 20px;
            border-top: 1px solid #f0ece8;
        }

        .sidebar-footer a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 14px;
            border-radius: 12px;
            text-decoration: none;
            color: var(--text-muted);
            font-size: 13px;
            font-weight: 500;
            transition: all 0.25s ease;
        }

        .sidebar-footer a:hover {
            background: #fef2f2;
            color: #dc2626;
        }

        /* Main Content */
        .main {
            margin-left: 260px;
            min-height: 100vh;
            padding: 0;
        }

        .topbar {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            padding: 18px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 50;
            border-bottom: 1px solid rgba(0, 0, 0, 0.04);
        }

        .topbar-left h1 {
            font-family: 'Playfair Display', serif;
            font-size: 22px;
            color: var(--primary);
        }

        .topbar-left p {
            font-size: 13px;
            color: var(--text-muted);
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .topbar-right a {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.25s ease;
        }

        .btn-view {
            background: rgba(139, 94, 60, 0.08);
            color: var(--primary);
        }

        .btn-view:hover {
            background: var(--gradient);
            color: var(--white);
            box-shadow: 0 4px 12px rgba(139, 94, 60, 0.25);
        }

        .hamburger-btn {
            display: none;
            background: none;
            border: none;
            font-size: 22px;
            color: var(--primary);
            cursor: pointer;
            padding: 8px;
            border-radius: 10px;
            transition: background 0.2s;
        }

        .hamburger-btn:hover { background: rgba(139, 94, 60, 0.06); }

        .content {
            padding: 32px 40px;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: var(--white);
            border-radius: var(--radius);
            padding: 28px;
            box-shadow: var(--shadow);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.5);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 120px;
            height: 120px;
            border-radius: 50%;
            filter: blur(40px);
            opacity: 0.15;
        }

        .stat-card:nth-child(1)::before { background: #8b5e3c; }
        .stat-card:nth-child(2)::before { background: #2a9d8f; }
        .stat-card:nth-child(3)::before { background: #e9c46a; }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            margin-bottom: 18px;
        }

        .stat-icon.digital { background: rgba(139, 94, 60, 0.1); color: var(--primary); }
        .stat-icon.cetak { background: rgba(42, 157, 143, 0.1); color: #2a9d8f; }
        .stat-icon.orders { background: rgba(233, 196, 106, 0.15); color: #b8860b; }

        .stat-card h3 {
            font-size: 32px;
            font-weight: 700;
            color: var(--text);
            line-height: 1;
        }

        .stat-card p {
            font-size: 13px;
            color: var(--text-muted);
            margin-top: 6px;
        }

        /* Menu Grid */
        .menu-section h2 {
            font-family: 'Playfair Display', serif;
            font-size: 20px;
            color: var(--primary);
            margin-bottom: 20px;
        }

        .menu-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 24px;
        }

        .menu-card {
            background: var(--white);
            border-radius: var(--radius);
            padding: 36px 30px;
            text-decoration: none;
            color: var(--text);
            box-shadow: var(--shadow);
            transition: all 0.35s cubic-bezier(0.165, 0.84, 0.44, 1);
            display: flex;
            align-items: center;
            gap: 20px;
            border: 2px solid transparent;
            position: relative;
            overflow: hidden;
        }

        .menu-card::after {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 0;
            height: 100%;
            background: var(--gradient);
            opacity: 0.04;
            transition: width 0.4s ease;
        }

        .menu-card:hover::after {
            width: 100%;
        }

        .menu-card:hover {
            transform: translateY(-6px);
            box-shadow: var(--shadow-lg);
            border-color: rgba(139, 94, 60, 0.15);
        }

        .menu-card-icon {
            width: 64px;
            height: 64px;
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            flex-shrink: 0;
            position: relative;
            z-index: 1;
        }

        .menu-card-icon.digital {
            background: var(--gradient);
            color: var(--white);
            box-shadow: 0 6px 16px rgba(139, 94, 60, 0.25);
        }

        .menu-card-icon.cetak {
            background: linear-gradient(135deg, #2a9d8f, #3cc4b6);
            color: var(--white);
            box-shadow: 0 6px 16px rgba(42, 157, 143, 0.25);
        }

        .menu-card-body {
            position: relative;
            z-index: 1;
        }

        .menu-card-body h3 {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .menu-card-body p {
            font-size: 13px;
            color: var(--text-muted);
            line-height: 1.5;
        }

        .menu-card-arrow {
            margin-left: auto;
            font-size: 18px;
            color: #ccc;
            transition: all 0.3s ease;
            position: relative;
            z-index: 1;
        }

        .menu-card:hover .menu-card-arrow {
            color: var(--primary);
            transform: translateX(4px);
        }

        /* Mobile overlay */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.3);
            z-index: 90;
        }

        .sidebar-overlay.active { display: block; }

        /* Responsive */
        @media (max-width: 1024px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.open { transform: translateX(0); }
            .main { margin-left: 0; }
            .hamburger-btn { display: block; }
            .content { padding: 24px 20px; }
            .topbar { padding: 14px 20px; }
        }

        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: 1fr;
                gap: 16px;
            }
            .menu-grid {
                grid-template-columns: 1fr;
                gap: 16px;
            }
            .topbar-right .btn-view span { display: none; }
        }

        @media (max-width: 480px) {
            .stat-card { padding: 22px; }
            .stat-card h3 { font-size: 26px; }
            .menu-card { padding: 24px 20px; gap: 16px; }
            .menu-card-icon { width: 52px; height: 52px; font-size: 22px; border-radius: 14px; }
            .menu-card-body h3 { font-size: 16px; }
        }
    </style>
</head>
<body>

    <!-- Sidebar Overlay (Mobile) -->
    <div class="sidebar-overlay" id="sidebar-overlay"></div>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <img src="img/SF.png" alt="Logo">
            <div>
                <h2>Sofwa</h2>
                <span>Admin Panel</span>
            </div>
        </div>

        <nav class="sidebar-nav">
            <div class="sidebar-nav-label">Menu Utama</div>
            <a href="admin_index.php" class="active">
                <i class="fas fa-th-large"></i> Dashboard
            </a>
            <a href="admin_digital.php">
                <i class="fas fa-desktop"></i> Undangan Digital
            </a>
            <a href="admin_cetak.php">
                <i class="fas fa-print"></i> Undangan Cetak
            </a>

            <div class="sidebar-nav-label">Lainnya</div>
            <a href="index.php" target="_blank">
                <i class="fas fa-globe"></i> Lihat Website
            </a>
        </nav>

        <div class="sidebar-footer">
            <a href="admin_logout.php">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main">
        <div class="topbar">
            <div style="display: flex; align-items: center; gap: 12px;">
                <button class="hamburger-btn" id="hamburger-btn">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="topbar-left">
                    <h1>Dashboard</h1>
                    <p>Selamat datang, Admin</p>
                </div>
            </div>
            <div class="topbar-right">
                <a href="index.php" target="_blank" class="btn-view">
                    <i class="fas fa-external-link-alt"></i>
                    <span>Lihat Website</span>
                </a>
            </div>
        </div>

        <div class="content">
            <!-- Stats -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon digital">
                        <i class="fas fa-desktop"></i>
                    </div>
                    <h3><?php echo $stats['digital']; ?></h3>
                    <p>Template Digital</p>
                </div>
                <div class="stat-card">
                    <div class="stat-icon cetak">
                        <i class="fas fa-print"></i>
                    </div>
                    <h3><?php echo $stats['cetak']; ?></h3>
                    <p>Template Cetak</p>
                </div>
                <div class="stat-card">
                    <div class="stat-icon orders">
                        <i class="fas fa-shopping-bag"></i>
                    </div>
                    <h3><?php echo $stats['orders']; ?></h3>
                    <p>Total Pesanan</p>
                </div>
            </div>

            <!-- Menu -->
            <div class="menu-section">
                <h2>Kelola Bisnis</h2>
                <div class="menu-grid">
                    <a href="admin_digital.php" class="menu-card">
                        <div class="menu-card-icon digital">
                            <i class="fas fa-images"></i>
                        </div>
                        <div class="menu-card-body">
                            <h3>Undangan Digital</h3>
                            <p>Kelola katalog template undangan digital, harga, dan gambar.</p>
                        </div>
                        <div class="menu-card-arrow">
                            <i class="fas fa-chevron-right"></i>
                        </div>
                    </a>

                    <a href="admin_cetak.php" class="menu-card">
                        <div class="menu-card-icon cetak">
                            <i class="fas fa-file-invoice"></i>
                        </div>
                        <div class="menu-card-body">
                            <h3>Undangan Cetak</h3>
                            <p>Kelola katalog undangan cetak fisik, spesifikasi, dan harga.</p>
                        </div>
                        <div class="menu-card-arrow">
                            <i class="fas fa-chevron-right"></i>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </main>

    <script>
        const hamburger = document.getElementById('hamburger-btn');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');

        hamburger.addEventListener('click', () => {
            sidebar.classList.toggle('open');
            overlay.classList.toggle('active');
        });

        overlay.addEventListener('click', () => {
            sidebar.classList.remove('open');
            overlay.classList.remove('active');
        });
    </script>
</body>
</html>
