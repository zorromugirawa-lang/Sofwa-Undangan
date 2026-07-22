<?php
function handleUpload($fileInputName, $targetDir = 'assets/uploads/') {
    if (!isset($_FILES[$fileInputName]) || $_FILES[$fileInputName]['error'] === UPLOAD_ERR_NO_FILE) {
        return null; // No file uploaded
    }

    $file = $_FILES[$fileInputName];
    
    // Check for upload errors
    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new Exception("Upload error code: " . $file['error']);
    }

    // Validate file type
    $allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
    $mimeType = '';
    if (function_exists('finfo_open')) {
        $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($fileInfo, $file['tmp_name']);
        finfo_close($fileInfo);
    } elseif (function_exists('mime_content_type')) {
        $mimeType = mime_content_type($file['tmp_name']);
    } else {
        $mimeType = $file['type'];
    }

    if (!in_array($mimeType, $allowedTypes)) {
        throw new Exception("Format file tidak didukung. Hanya JPG, PNG, WEBP, dan GIF yang diperbolehkan. Terdeteksi: " . $mimeType);
    }

    // Ensure upload directory exists
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
    }

    // Generate unique filename
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid('img_') . '_' . time() . '.' . $extension;
    $targetFilePath = $targetDir . $filename;

    if (move_uploaded_file($file['tmp_name'], $targetFilePath)) {
        return $targetFilePath; // Return the path to save in DB
    } else {
        throw new Exception("Gagal menyimpan file yang diunggah.");
    }
}
