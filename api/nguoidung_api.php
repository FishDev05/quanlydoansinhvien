<?php
// api/nguoidung_api.php
require_once 'db.php';

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET': // Lấy tất cả người dùng
        $stmt = $pdo->query("SELECT id, ma_so, ho_ten, email, vai_tro, khoa FROM nguoi_dung");
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($users);
        break;

    case 'POST': // Thêm người dùng
        $data = json_decode(file_get_contents('php://input'), true);
        $stmt = $pdo->prepare("INSERT INTO nguoi_dung (ma_so, ho_ten, email, vai_tro, khoa) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$data['ma_so'], $data['ho_ten'], $data['email'], $data['vai_tro'], $data['khoa']]);
        echo json_encode(['message' => 'Thêm thành công', 'id' => $pdo->lastInsertId()]);
        break;

    case 'PUT': // Sửa người dùng
        $data = json_decode(file_get_contents('php://input'), true);
        $stmt = $pdo->prepare("UPDATE nguoi_dung SET ma_so=?, ho_ten=?, email=?, vai_tro=?, khoa=? WHERE id=?");
        $stmt->execute([$data['ma_so'], $data['ho_ten'], $data['email'], $data['vai_tro'], $data['khoa'], $data['id']]);
        echo json_encode(['message' => 'Cập nhật thành công']);
        break;

    case 'DELETE': // Xóa người dùng
        $data = json_decode(file_get_contents('php://input'), true);
        $stmt = $pdo->prepare("DELETE FROM nguoi_dung WHERE id = ?");
        $stmt->execute([$data['id']]);
        echo json_encode(['message' => 'Xóa thành công']);
        break;

    default:
        echo json_encode(['error' => 'Phương thức không hỗ trợ']);
}
?>
