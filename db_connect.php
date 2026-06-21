<?php
// C:\xampp\htdocs\SOFWAUNDANGAN\db_connect.php

$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'db_sofwa_undangan';

$conn = null;
$db_error = null;

try {
    // 1. Hubungkan ke MySQL (tanpa memilih database terlebih dahulu)
    $pdo_init = new PDO("mysql:host=$host", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

    // 2. Buat database jika belum ada
    $pdo_init->exec("CREATE DATABASE IF NOT EXISTS `$dbname` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

    // 3. Hubungkan ke database yang baru dibuat/sudah ada
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

    // 4. Buat tabel templates jika belum ada
    $conn->exec("CREATE TABLE IF NOT EXISTS templates (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        category VARCHAR(50),
        thumbnail VARCHAR(255),
        price DECIMAL(10, 2),
        path VARCHAR(255) NOT NULL
    ) ENGINE=InnoDB");

    // 5. Buat tabel orders jika belum ada
    $conn->exec("CREATE TABLE IF NOT EXISTS orders (
        id INT AUTO_INCREMENT PRIMARY KEY,
        customer_name VARCHAR(100) NOT NULL,
        whatsapp_number VARCHAR(20) NOT NULL,
        template_id INT,
        order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        status ENUM('pending', 'processing', 'completed', 'cancelled') DEFAULT 'pending',
        FOREIGN KEY (template_id) REFERENCES templates(id) ON DELETE SET NULL
    ) ENGINE=InnoDB");

    // 6. Buat tabel guestbook jika belum ada
    $conn->exec("CREATE TABLE IF NOT EXISTS guestbook (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        message TEXT NOT NULL,
        is_present BOOLEAN DEFAULT TRUE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB");

    // 7. Cek apakah tabel templates kosong, jika ya, isi dengan data awal
    $stmt = $conn->query("SELECT COUNT(*) FROM templates");
    if ($stmt->fetchColumn() == 0) {
        $insert_sql = "INSERT INTO templates (name, category, thumbnail, price, path) VALUES (:name, :category, :thumbnail, :price, :path)";
        $insert_stmt = $conn->prepare($insert_sql);

        $initial_templates = [
            [
                'name' => 'Template Elegant',
                'category' => 'Pernikahan',
                'thumbnail' => 'img/cover_elegant.png',
                'price' => 150000,
                'path' => 'templates/pernikahan/undangan1/index.html'
            ],
            [
                'name' => 'Template Minimalis',
                'category' => 'Pernikahan',
                'thumbnail' => 'img/cover_minimalist.png',
                'price' => 100000,
                'path' => 'templates/pernikahan/undangan2/index.html'
            ],
            [
                'name' => 'Template Floral',
                'category' => 'Pernikahan',
                'thumbnail' => 'img/cover_floral.png',
                'price' => 125000,
                'path' => 'templates/pernikahan/undangan3/index.html'
            ],
            [
                'name' => 'Template Modern',
                'category' => 'Pernikahan',
                'thumbnail' => 'img/cover_modern.png',
                'price' => 130000,
                'path' => 'templates/pernikahan/undangan4/index.php'
            ],
            [
                'name' => 'Template Khitan Islamic',
                'category' => 'Khitanan',
                'thumbnail' => 'img/cover_khitan_islamic.png',
                'price' => 90000,
                'path' => 'templates/khitanan/undangankhitan/index.html'
            ],
            [
                'name' => 'Template Khitan Ceria',
                'category' => 'Khitanan',
                'thumbnail' => 'img/cover_khitan_ceria.png',
                'price' => 95000,
                'path' => 'templates/khitanan/undangankhitan2/index.html'
            ],
            [
                'name' => 'Template Khitan Modern',
                'category' => 'Khitanan',
                'thumbnail' => 'img/cover_khitan_modern.png',
                'price' => 85000,
                'path' => 'templates/khitanan/undangankhitan3/index.html'
            ]
        ];

        foreach ($initial_templates as $t) {
            $insert_stmt->execute($t);
        }
    } else {
        // 8. Jika tabel sudah ada, lakukan sinkronisasi thumbnail agar menggunakan versi cover terbaru
        $updates = [
            'Template Elegant' => 'img/cover_elegant.png',
            'Template Minimalis' => 'img/cover_minimalist.png',
            'Template Floral' => 'img/cover_floral.png',
            'Template Modern' => 'img/cover_modern.png',
            'Template Khitan Islamic' => 'img/cover_khitan_islamic.png',
            'Template Khitan Ceria' => 'img/cover_khitan_ceria.png',
            'Template Khitan Modern' => 'img/cover_khitan_modern.png'
        ];
        $update_stmt = $conn->prepare("UPDATE templates SET thumbnail = :thumbnail WHERE name = :name");
        foreach ($updates as $name => $thumb) {
            $update_stmt->execute(['thumbnail' => $thumb, 'name' => $name]);
        }
    }

} catch (PDOException $e) {
    // Simpan error koneksi agar bisa dibaca halaman utama
    $db_error = $e->getMessage();
    $conn = null;
}
?>
