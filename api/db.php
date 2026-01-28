<?php
// api/db.php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *'); // Cho phép frontend gọi từ localhost
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

// Thông tin kết nối MySQL (thay đổi nếu cần)
$host = 'localhost';
$dbname = 'quan_ly_do_an_sinh_vien'; // Tên database của bạn
$username = 'root';     // Username MySQL (mặc định XAMPP là root)
$password = '';         // Password (mặc định XAMPP để trống)

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Kết nối database thất bại: ' . $e->getMessage()]);
    exit;
}

// Hàm trả về JSON chuẩn
function response($data, $status = 200) {
    http_response_code($status);
    echo json_encode($data);
    exit;
}
?>