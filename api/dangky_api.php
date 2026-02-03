<?php
// api/dangky_api.php
require_once 'db.php';

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET': // Lấy danh sách
        $stmt = $pdo->query("SELECT id, de_tai_id, sinh_vien_id, dot_do_an_id, ngay_dang_ky, trang_thai, ghi_chu FROM dang_ky_de_tai");
        $dangky = $stmt->fetchAll(PDO::FETCH_ASSOC);
        response($dangky);
        break;

    case 'POST': // Thêm mới
        $data = json_decode(file_get_contents('php://input'), true);
        $stmt = $pdo->prepare("INSERT INTO dang_ky_de_tai (de_tai_id, sinh_vien_id, dot_do_an_id, trang_thai, ghi_chu) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['de_tai_id'],
            $data['sinh_vien_id'],
            $data['dot_do_an_id'],
            $data['trang_thai'] ?? 'cho_duyet',
            $data['ghi_chu'] ?? ''
        ]);
        response(['message' => 'Thêm thành công', 'id' => $pdo->lastInsertId()], 201);
        break;

    case 'PUT': // Sửa
        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data || !isset($data['id'])) {
            response(['error' => 'Thiếu ID'], 400);
        }

        $stmt = $pdo->prepare("UPDATE dang_ky_de_tai SET de_tai_id=?, sinh_vien_id=?, dot_do_an_id=?, trang_thai=?, ghi_chu=? WHERE id=?");
        $stmt->execute([
            $data['de_tai_id'],
            $data['sinh_vien_id'],
            $data['dot_do_an_id'],
            $data['trang_thai'],
            $data['ghi_chu'],
            $data['id']
        ]);
        response(['message' => 'Cập nhật thành công']);
        break;

    case 'DELETE': // Xóa
        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data || !isset($data['id'])) {
            response(['error' => 'Thiếu ID'], 400);
        }

        $stmt = $pdo->prepare("DELETE FROM dang_ky_de_tai WHERE id = ?");
        $stmt->execute([$data['id']]);
        response(['message' => 'Xóa thành công']);
        break;

    default:
        response(['error' => 'Phương thức không hỗ trợ'], 405);
}
?>