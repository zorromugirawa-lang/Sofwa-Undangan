<?php
// C:\xampp\htdocs\SOFWAUNDANGAN\submit_order.php
header('Content-Type: application/json');

// Include database connection
require_once 'db_connect.php';

// Fallback hardcoded templates array if database is offline
$fallback_templates = [
    1 => ['name' => 'Template Elegant', 'category' => 'Pernikahan', 'price' => 150000],
    2 => ['name' => 'Template Minimalis', 'category' => 'Pernikahan', 'price' => 100000],
    3 => ['name' => 'Template Adat Jawa', 'category' => 'Pernikahan', 'price' => 125000],
    4 => ['name' => 'Template Modern', 'category' => 'Pernikahan', 'price' => 130000],
    5 => ['name' => 'Template Khitan Islamic', 'category' => 'Khitanan', 'price' => 90000],
    6 => ['name' => 'Template Khitan Ceria', 'category' => 'Khitanan', 'price' => 95000],
    7 => ['name' => 'Template Khitan Modern', 'category' => 'Khitanan', 'price' => 85000]
];

// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method Not Allowed']);
    exit;
}

// Get and sanitize POST inputs
$customer_name = isset($_POST['customer_name']) ? trim(strip_tags($_POST['customer_name'])) : '';
$whatsapp_number = isset($_POST['whatsapp_number']) ? trim(strip_tags($_POST['whatsapp_number'])) : '';
$template_id = isset($_POST['template_id']) ? intval($_POST['template_id']) : 0;

// Validation
if (empty($customer_name) || empty($whatsapp_number) || $template_id <= 0) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Semua kolom formulir wajib diisi dengan benar!']);
    exit;
}

$template_name = '';
$template_category = '';
$template_price = 0;
$db_saved = false;

// 1. Get Template Details & Save Order
if ($conn !== null) {
    try {
        // Query template info
        $stmt = $conn->prepare("SELECT name, category, price FROM templates WHERE id = :id");
        $stmt->execute(['id' => $template_id]);
        $template = $stmt->fetch();

        if ($template) {
            $template_name = $template['name'];
            $template_category = $template['category'];
            $template_price = $template['price'];

            // Insert into orders table
            $insert_stmt = $conn->prepare("INSERT INTO orders (customer_name, whatsapp_number, template_id, status) VALUES (:name, :whatsapp, :template_id, 'pending')");
            $insert_stmt->execute([
                'name' => $customer_name,
                'whatsapp' => $whatsapp_number,
                'template_id' => $template_id
            ]);
            $db_saved = true;
        } else {
            http_response_code(404);
            echo json_encode(['status' => 'error', 'message' => 'Template tidak ditemukan!']);
            exit;
        }
    } catch (PDOException $e) {
        // If DB query fails, fallback to using array (but log error internally)
        $db_saved = false;
    }
}

// 2. Fallback to hardcoded array if DB is offline or not found
if (!$db_saved) {
    if (isset($fallback_templates[$template_id])) {
        $template_name = $fallback_templates[$template_id]['name'];
        $template_category = $fallback_templates[$template_id]['category'];
        $template_price = $fallback_templates[$template_id]['price'];

        // Save order to json file as fallback storage
        $fallback_file = 'orders_fallback.json';
        $orders = [];
        if (file_exists($fallback_file)) {
            $orders_content = file_get_contents($fallback_file);
            $orders = json_decode($orders_content, true);
            if (!is_array($orders)) {
                $orders = [];
            }
        }
        
        $new_order = [
            'id' => uniqid(),
            'customer_name' => $customer_name,
            'whatsapp_number' => $whatsapp_number,
            'template_id' => $template_id,
            'template_name' => $template_name,
            'order_date' => date('Y-m-d H:i:s'),
            'status' => 'pending'
        ];
        $orders[] = $new_order;
        file_put_contents($fallback_file, json_encode($orders, JSON_PRETTY_PRINT));
        $db_saved = 'fallback_file';
    } else {
        http_response_code(404);
        echo json_encode(['status' => 'error', 'message' => 'Template tidak ditemukan di sistem!']);
        exit;
    }
}

// 3. Format WhatsApp Redirect Link
$admin_phone = '6281393678911';
$formatted_price = 'Rp ' . number_format($template_price, 0, ',', '.');
$message = "Halo Sofwa Undangan, saya *{$customer_name}* (No. WA: {$whatsapp_number}).\n\nSaya ingin memesan undangan digital dengan tema *{$template_name}*.\n- Kategori: {$template_category}\n- Harga: {$formatted_price}\n\nMohon petunjuk selanjutnya untuk pengisian data acara. Terima kasih!";

$whatsapp_url = "https://wa.me/{$admin_phone}?text=" . urlencode($message);

// Return response
echo json_encode([
    'status' => 'success',
    'message' => 'Pesanan berhasil dicatat!',
    'db_status' => $db_saved === true ? 'database' : 'fallback_file',
    'redirect_url' => $whatsapp_url
]);
exit;
?>
