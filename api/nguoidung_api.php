<?php
// api/nguoidung_api.php
require_once 'db.php';

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET': // Lấy danh sách người dùng
        $stmt = $pdo->query("SELECT id, ma_so, ho_ten, email, vai_tro, khoa FROM nguoi_dung");
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        response($users);
        break;

    case 'POST': // Thêm người dùng mới
        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data || empty($data['ma_so']) || empty($data['ho_ten']) || empty($data['email'])) {
            response(['error' => 'Thiếu thông tin bắt buộc'], 400);
        }

        $stmt = $pdo->prepare("INSERT INTO nguoi_dung (ma_so, ho_ten, email, vai_tro, khoa) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['ma_so'],
            $data['ho_ten'],
            $data['email'],
            $data['vai_tro'] ?? 'sinh_vien',
            $data['khoa'] ?? 'Công nghệ Thông tin'
        ]);

        response(['message' => 'Thêm người dùng thành công', 'id' => $pdo->lastInsertId()], 201);
        break;

    case 'PUT': // Sửa người dùng
        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data || !isset($data['id'])) {
            response(['error' => 'Thiếu ID'], 400);
        }

        $stmt = $pdo->prepare("UPDATE nguoi_dung SET ma_so=?, ho_ten=?, email=?, vai_tro=?, khoa=? WHERE id=?");
        $stmt->execute([
            $data['ma_so'],
            $data['ho_ten'],
            $data['email'],
            $data['vai_tro'],
            $data['khoa'],
            $data['id']
        ]);

        response(['message' => 'Cập nhật thành công']);
        break;

    case 'DELETE': // Xóa người dùng
        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data || !isset($data['id'])) {
            response(['error' => 'Thiếu ID'], 400);
        }

        $stmt = $pdo->prepare("DELETE FROM nguoi_dung WHERE id = ?");
        $stmt->execute([$data['id']]);

        response(['message' => 'Xóa thành công']);
        break;

    default:
        response(['error' => 'Phương thức không hỗ trợ'], 405);
}
?>