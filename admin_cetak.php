<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit;
}

require 'db_connect.php';
require_once 'upload_helper.php';

$message = '';
$messageType = 'success';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'update') {
        $id = $_POST['id'];
        $nama = $_POST['nama'];
        $bahan = $_POST['bahan'];
        $jenis_blangko = $_POST['jenis_blangko'];
        $ukuran_terbuka = $_POST['ukuran_terbuka'];
        $ukuran_terlipat = $_POST['ukuran_terlipat'];
        $ukuran_plastik = $_POST['ukuran_plastik'];
        $harga = $_POST['harga'];
        $thumbnail = $_POST['thumbnail'];
        
        try {
            $uploadedPath = handleUpload('thumbnail_file');
            if ($uploadedPath) {
                $thumbnail = $uploadedPath;
            }
            
            $sql = "UPDATE undangan_cetak SET nama=:nama, bahan=:bahan, jenis_blangko=:jenis_blangko, 
                    ukuran_terbuka=:ukuran_terbuka, ukuran_terlipat=:ukuran_terlipat, ukuran_plastik=:ukuran_plastik, harga=:harga, thumbnail=:thumbnail 
                    WHERE id=:id";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                'nama' => $nama, 'bahan' => $bahan, 'jenis_blangko' => $jenis_blangko,
                'ukuran_terbuka' => $ukuran_terbuka, 'ukuran_terlipat' => $ukuran_terlipat,
                'ukuran_plastik' => $ukuran_plastik, 'harga' => $harga, 'thumbnail' => $thumbnail, 'id' => $id
            ]);
            $message = 'Data berhasil diupdate!';
        } catch (Exception $e) {
            $message = 'Gagal mengupdate: ' . $e->getMessage();
            $messageType = 'error';
        }
    } elseif (isset($_POST['action']) && $_POST['action'] === 'add') {
        $nama = $_POST['nama'];
        $bahan = $_POST['bahan'];
        $jenis_blangko = $_POST['jenis_blangko'];
        $ukuran_terbuka = $_POST['ukuran_terbuka'];
        $ukuran_terlipat = $_POST['ukuran_terlipat'];
        $ukuran_plastik = $_POST['ukuran_plastik'];
        $harga = $_POST['harga'];
        $thumbnail = '';
        
        try {
            $uploadedPath = handleUpload('thumbnail_file');
            if ($uploadedPath) {
                $thumbnail = $uploadedPath;
            } else {
                throw new Exception("Gambar thumbnail harus diunggah untuk item baru!");
            }
            
            $sql = "INSERT INTO undangan_cetak (nama, bahan, jenis_blangko, ukuran_terbuka, ukuran_terlipat, ukuran_plastik, harga, thumbnail) 
                    VALUES (:nama, :bahan, :jenis_blangko, :ukuran_terbuka, :ukuran_terlipat, :ukuran_plastik, :harga, :thumbnail)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                'nama' => $nama, 'bahan' => $bahan, 'jenis_blangko' => $jenis_blangko,
                'ukuran_terbuka' => $ukuran_terbuka, 'ukuran_terlipat' => $ukuran_terlipat,
                'ukuran_plastik' => $ukuran_plastik, 'harga' => $harga, 'thumbnail' => $thumbnail
            ]);
            $message = 'Data berhasil ditambahkan!';
        } catch (Exception $e) {
            $message = 'Gagal menambah: ' . $e->getMessage();
            $messageType = 'error';
        }
    } elseif (isset($_POST['action']) && $_POST['action'] === 'delete') {
        $id = $_POST['id'];
        $sql = "DELETE FROM undangan_cetak WHERE id=:id";
        $stmt = $conn->prepare($sql);
        try {
            $stmt->execute(['id' => $id]);
            $message = 'Data berhasil dihapus!';
        } catch (PDOException $e) {
            $message = 'Gagal menghapus data: ' . $e->getMessage();
            $messageType = 'error';
        }
    }
}

$templates = [];
if ($conn !== null) {
    try {
        $stmt = $conn->query("SELECT * FROM undangan_cetak ORDER BY id ASC");
        if ($stmt) {
            $templates = $stmt->fetchAll();
        }
    } catch (Exception $e) {
        $message = 'Gagal mengambil data: ' . $e->getMessage();
        $messageType = 'error';
    }
} else {
    $message = 'Koneksi database bermasalah.';
    $messageType = 'error';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Undangan Cetak</title>
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
            --radius: 20px;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
        }

        .sidebar {
            position: fixed; top: 0; left: 0; width: 260px; height: 100vh;
            background: var(--white); box-shadow: 4px 0 24px rgba(0,0,0,0.04);
            z-index: 100; display: flex; flex-direction: column; transition: transform 0.3s ease;
        }

        .sidebar-brand {
            padding: 28px 24px; display: flex; align-items: center; gap: 12px;
            border-bottom: 1px solid #f0ece8;
        }

        .sidebar-brand img { width: 40px; height: 40px; border-radius: 12px; }
        .sidebar-brand h2 { font-family: 'Playfair Display', serif; font-size: 18px; color: var(--primary); }
        .sidebar-brand span { display: block; font-family: 'Poppins', sans-serif; font-size: 11px; color: var(--text-muted); font-weight: 400; }

        .sidebar-nav { padding: 20px 16px; flex: 1; overflow-y: auto; }

        .sidebar-nav-label {
            font-size: 11px; font-weight: 600; text-transform: uppercase;
            letter-spacing: 1px; color: #bbb; padding: 0 12px; margin-bottom: 10px; margin-top: 16px;
        }
        .sidebar-nav-label:first-child { margin-top: 0; }

        .sidebar-nav a {
            display: flex; align-items: center; gap: 12px; padding: 12px 16px;
            border-radius: 12px; text-decoration: none; color: var(--text-muted);
            font-size: 14px; font-weight: 500; transition: all 0.25s ease; margin-bottom: 4px;
        }

        .sidebar-nav a i { width: 20px; text-align: center; font-size: 16px; }
        .sidebar-nav a:hover { background: rgba(139, 94, 60, 0.06); color: var(--primary); }
        .sidebar-nav a.active { background: var(--gradient); color: var(--white); box-shadow: 0 4px 12px rgba(139, 94, 60, 0.25); }

        .sidebar-footer { padding: 16px 20px; border-top: 1px solid #f0ece8; }
        .sidebar-footer a {
            display: flex; align-items: center; gap: 10px; padding: 10px 14px;
            border-radius: 12px; text-decoration: none; color: var(--text-muted);
            font-size: 13px; font-weight: 500; transition: all 0.25s ease;
        }
        .sidebar-footer a:hover { background: #fef2f2; color: #dc2626; }

        .main { margin-left: 260px; min-height: 100vh; }

        .topbar {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px);
            padding: 18px 40px;
            display: flex; justify-content: space-between; align-items: center;
            position: sticky; top: 0; z-index: 50;
            border-bottom: 1px solid rgba(0, 0, 0, 0.04);
        }

        .topbar-left h1 { font-family: 'Playfair Display', serif; font-size: 22px; color: var(--primary); }
        .topbar-left p { font-size: 13px; color: var(--text-muted); }
        .topbar-right { display: flex; align-items: center; gap: 10px; }

        .hamburger-btn {
            display: none; background: none; border: none; font-size: 22px;
            color: var(--primary); cursor: pointer; padding: 8px; border-radius: 10px;
        }

        .topbar-right a {
            display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px;
            border-radius: 12px; font-size: 13px; font-weight: 500; text-decoration: none; transition: all 0.25s ease;
        }

        .btn-view { background: rgba(139, 94, 60, 0.08); color: var(--primary); }
        .btn-view:hover { background: var(--gradient); color: var(--white); box-shadow: 0 4px 12px rgba(139, 94, 60, 0.25); }
        .btn-back { background: #f0ece8; color: var(--text-muted); }
        .btn-back:hover { background: #e5e1dc; color: var(--text); }

        .content { padding: 32px 40px; }

        .alert {
            padding: 14px 20px; border-radius: 14px; margin-bottom: 24px;
            font-size: 14px; font-weight: 500; display: flex; align-items: center; gap: 10px;
        }
        .alert.success { background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; }
        .alert.error { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }

        .table-card {
            background: var(--white); border-radius: var(--radius); box-shadow: var(--shadow);
            overflow: hidden; margin-bottom: 32px; border: 1px solid rgba(255, 255, 255, 0.5);
        }

        .table-header {
            padding: 24px 28px; display: flex; justify-content: space-between; align-items: center;
            border-bottom: 1px solid #f0ece8;
        }

        .table-header h2 { font-family: 'Playfair Display', serif; font-size: 18px; color: var(--primary); }

        .table-scroll { overflow-x: auto; }

        table { width: 100%; border-collapse: collapse; }

        th {
            padding: 14px 20px; text-align: left; font-size: 12px; font-weight: 600;
            text-transform: uppercase; letter-spacing: 0.5px; color: var(--text-muted);
            background: #faf9f8; border-bottom: 2px solid #f0ece8; white-space: nowrap;
        }

        td {
            padding: 16px 20px; border-bottom: 1px solid #f5f3f0;
            font-size: 14px; vertical-align: middle;
        }

        tr:last-child td { border-bottom: none; }
        tr:hover td { background: rgba(139, 94, 60, 0.02); }

        td img.thumb {
            width: 56px; height: 56px; border-radius: 12px; object-fit: cover;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        td input[type="text"], td input[type="number"] {
            width: 100%; padding: 8px 12px; border: 1.5px solid #e5e1dc;
            border-radius: 10px; font-size: 13px; font-family: 'Poppins', sans-serif;
            transition: border-color 0.2s;
        }

        td input:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px rgba(139, 94, 60, 0.08); }

        td input[type="file"] { font-size: 12px; max-width: 160px; }

        .btn-action {
            padding: 8px 14px; border-radius: 10px; border: none; font-size: 12px;
            font-weight: 500; font-family: 'Poppins', sans-serif; cursor: pointer;
            transition: all 0.25s ease; display: inline-flex; align-items: center; gap: 6px;
        }

        .btn-update { background: var(--gradient); color: var(--white); }
        .btn-update:hover { box-shadow: 0 4px 10px rgba(139, 94, 60, 0.25); transform: translateY(-1px); }
        .btn-delete { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }
        .btn-delete:hover { background: #dc2626; color: var(--white); border-color: #dc2626; }

        .btn-group { display: flex; gap: 6px; flex-wrap: wrap; }

        .form-card {
            background: var(--white); border-radius: var(--radius); box-shadow: var(--shadow);
            overflow: hidden; border: 1px solid rgba(255, 255, 255, 0.5);
        }

        .form-card-header { padding: 24px 28px; border-bottom: 1px solid #f0ece8; }
        .form-card-header h2 { font-family: 'Playfair Display', serif; font-size: 18px; color: var(--primary); }

        .form-card-body { padding: 28px; }

        .form-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 24px; }

        .form-group { display: flex; flex-direction: column; }

        .form-group label {
            font-size: 13px; font-weight: 500; color: #555; margin-bottom: 8px;
        }

        .form-group input, .form-group select {
            padding: 12px 14px; border: 1.5px solid #e5e1dc; border-radius: 12px;
            font-size: 14px; font-family: 'Poppins', sans-serif; transition: all 0.3s ease;
            background: rgba(250, 249, 248, 0.7);
        }

        .form-group input:focus, .form-group select:focus {
            outline: none; border-color: var(--primary); background: var(--white);
            box-shadow: 0 0 0 3px rgba(139, 94, 60, 0.08);
        }

        .btn-add {
            padding: 14px 28px; border-radius: 14px; border: none;
            background: linear-gradient(135deg, #2a9d8f, #3cc4b6); color: var(--white); font-size: 14px;
            font-weight: 600; font-family: 'Poppins', sans-serif; cursor: pointer;
            box-shadow: 0 4px 12px rgba(42, 157, 143, 0.25); transition: all 0.3s ease;
            display: inline-flex; align-items: center; gap: 8px;
        }

        .btn-add:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(42, 157, 143, 0.35); }

        .sidebar-overlay { display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, 0.3); z-index: 90; }
        .sidebar-overlay.active { display: block; }

        @media (max-width: 1024px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .main { margin-left: 0; }
            .hamburger-btn { display: block; }
            .content { padding: 24px 20px; }
            .topbar { padding: 14px 20px; }
        }

        @media (max-width: 768px) {
            .form-grid { grid-template-columns: repeat(2, 1fr); }
            .table-header { flex-direction: column; align-items: flex-start; gap: 12px; }
            .topbar-right .btn-view span, .topbar-right .btn-back span { display: none; }
        }

        @media (max-width: 480px) {
            .form-grid { grid-template-columns: 1fr; }
            .content { padding: 16px 12px; }
            td { padding: 12px; }
            th { padding: 10px 12px; font-size: 11px; }
        }
    </style>
</head>
<body>

    <div class="sidebar-overlay" id="sidebar-overlay"></div>

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
            <a href="admin_index.php"><i class="fas fa-th-large"></i> Dashboard</a>
            <a href="admin_digital.php"><i class="fas fa-desktop"></i> Undangan Digital</a>
            <a href="admin_cetak.php" class="active"><i class="fas fa-print"></i> Undangan Cetak</a>
            <div class="sidebar-nav-label">Lainnya</div>
            <a href="undangan-cetak.php" target="_blank"><i class="fas fa-globe"></i> Lihat Halaman Cetak</a>
        </nav>
        <div class="sidebar-footer">
            <a href="admin_logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </aside>

    <main class="main">
        <div class="topbar">
            <div style="display: flex; align-items: center; gap: 12px;">
                <button class="hamburger-btn" id="hamburger-btn"><i class="fas fa-bars"></i></button>
                <div class="topbar-left">
                    <h1>Undangan Cetak</h1>
                    <p>Kelola katalog undangan cetak fisik</p>
                </div>
            </div>
            <div class="topbar-right">
                <a href="admin_index.php" class="btn-back"><i class="fas fa-arrow-left"></i> <span>Dashboard</span></a>
                <a href="undangan-cetak.php" target="_blank" class="btn-view"><i class="fas fa-external-link-alt"></i> <span>Lihat Halaman Cetak</span></a>
            </div>
        </div>

        <div class="content">
            <?php if ($message): ?>
                <div class="alert <?php echo $messageType; ?>">
                    <i class="fas <?php echo $messageType === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'; ?>"></i>
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <!-- Table -->
            <div class="table-card">
                <div class="table-header">
                    <h2><i class="fas fa-list" style="margin-right: 8px; opacity: 0.5;"></i>Daftar Undangan Cetak</h2>
                    <span style="font-size: 13px; color: var(--text-muted);"><?php echo count($templates); ?> item</span>
                </div>
                <div class="table-scroll">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Gambar</th>
                                <th>Nama/Judul</th>
                                <th>Bahan</th>
                                <th>Jenis Blangko</th>
                                <th>Uk. Terbuka</th>
                                <th>Uk. Terlipat</th>
                                <th>Uk. Plastik</th>
                                <th>Harga (Rp)</th>
                                <th>Upload</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($templates)): ?>
                                <tr>
                                    <td colspan="11" style="text-align: center; padding: 40px; color: var(--text-muted);">
                                        <i class="fas fa-inbox" style="font-size: 32px; display: block; margin-bottom: 12px; opacity: 0.3;"></i>
                                        Belum ada data cetak
                                    </td>
                                </tr>
                            <?php endif; ?>
                            <?php foreach ($templates as $t): ?>
                            <tr>
                                <td><strong>#<?php echo $t['id']; ?></strong></td>
                                <td>
                                    <img src="<?php echo htmlspecialchars($t['thumbnail']); ?>" alt="" class="thumb">
                                </td>
                                <td>
                                    <form method="POST" enctype="multipart/form-data" id="update_form_<?php echo $t['id']; ?>" style="display:none;">
                                        <input type="hidden" name="action" value="update">
                                        <input type="hidden" name="id" value="<?php echo $t['id']; ?>">
                                        <input type="hidden" name="thumbnail" value="<?php echo htmlspecialchars($t['thumbnail'] ?? ''); ?>">
                                    </form>
                                    <input type="text" name="nama" value="<?php echo htmlspecialchars($t['nama']); ?>" form="update_form_<?php echo $t['id']; ?>">
                                </td>
                                <td><input type="text" name="bahan" value="<?php echo htmlspecialchars($t['bahan']); ?>" form="update_form_<?php echo $t['id']; ?>"></td>
                                <td><input type="text" name="jenis_blangko" value="<?php echo htmlspecialchars($t['jenis_blangko']); ?>" form="update_form_<?php echo $t['id']; ?>"></td>
                                <td><input type="text" name="ukuran_terbuka" value="<?php echo htmlspecialchars($t['ukuran_terbuka']); ?>" form="update_form_<?php echo $t['id']; ?>"></td>
                                <td><input type="text" name="ukuran_terlipat" value="<?php echo htmlspecialchars($t['ukuran_terlipat']); ?>" form="update_form_<?php echo $t['id']; ?>"></td>
                                <td><input type="text" name="ukuran_plastik" value="<?php echo htmlspecialchars($t['ukuran_plastik']); ?>" form="update_form_<?php echo $t['id']; ?>"></td>
                                <td><input type="number" name="harga" value="<?php echo htmlspecialchars($t['harga']); ?>" form="update_form_<?php echo $t['id']; ?>"></td>
                                <td><input type="file" name="thumbnail_file" accept="image/*" form="update_form_<?php echo $t['id']; ?>"></td>
                                <td>
                                    <div class="btn-group">
                                        <button type="submit" form="update_form_<?php echo $t['id']; ?>" class="btn-action btn-update">
                                            <i class="fas fa-save"></i> Update
                                        </button>
                                        <form method="POST" id="delete_form_<?php echo $t['id']; ?>" onsubmit="return confirm('Yakin ingin menghapus item ini?');" style="display:none;">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id" value="<?php echo $t['id']; ?>">
                                        </form>
                                        <button type="submit" form="delete_form_<?php echo $t['id']; ?>" class="btn-action btn-delete">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Add Form -->
            <div class="form-card">
                <div class="form-card-header">
                    <h2><i class="fas fa-plus-circle" style="margin-right: 8px; opacity: 0.5;"></i>Tambah Undangan Cetak Baru</h2>
                </div>
                <div class="form-card-body">
                    <form method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="action" value="add">
                        <div class="form-grid">
                            <div class="form-group">
                                <label>Nama/Judul</label>
                                <input type="text" name="nama" placeholder="Contoh: Undangan Pernikahan Elegan" required>
                            </div>
                            <div class="form-group">
                                <label>Bahan</label>
                                <input type="text" name="bahan" value="BC 150 gram" required>
                            </div>
                            <div class="form-group">
                                <label>Jenis Blangko</label>
                                <input type="text" name="jenis_blangko" value="Soft Cover" required>
                            </div>
                            <div class="form-group">
                                <label>Uk. Terbuka</label>
                                <input type="text" name="ukuran_terbuka" value="21 X 19.5 (Cm)" required>
                            </div>
                            <div class="form-group">
                                <label>Uk. Terlipat</label>
                                <input type="text" name="ukuran_terlipat" value="11.7 X 19.5 (Cm)" required>
                            </div>
                            <div class="form-group">
                                <label>Uk. Plastik</label>
                                <input type="text" name="ukuran_plastik" value="12 X 24 (Cm)" required>
                            </div>
                            <div class="form-group">
                                <label>Harga (Rp)</label>
                                <input type="number" name="harga" placeholder="25000" required>
                            </div>
                            <div class="form-group">
                                <label>Upload Gambar</label>
                                <input type="file" name="thumbnail_file" accept="image/*" required>
                            </div>
                        </div>
                        <button type="submit" class="btn-add">
                            <i class="fas fa-plus"></i> Tambah Item Baru
                        </button>
                    </form>
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
