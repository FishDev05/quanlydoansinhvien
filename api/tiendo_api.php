<?php
// api/tiendo_api.php
require_once 'db.php';

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET': // Lấy danh sách
        $stmt = $pdo->query("SELECT id, dang_ky_id, moc, file_path, ngay_nop, nhan_xet, diem, trang_thai FROM tien_do");
        $tiendo = $stmt->fetchAll(PDO::FETCH_ASSOC);
        response($tiendo);
        break;

    case 'POST': // Thêm mới
        $data = json_decode(file_get_contents('php://input'), true);
        $stmt = $pdo->prepare("INSERT INTO tien_do (dang_ky_id, moc, file_path, nhan_xet, diem, trang_thai) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['dang_ky_id'],
            $data['moc'],
            $data['file_path'] ?? '',
            $data['nhan_xet'] ?? '',
            $data['diem'] ?? null,
            $data['trang_thai'] ?? 'chua_nop'
        ]);
        response(['message' => 'Thêm thành công', 'id' => $pdo->lastInsertId()], 201);
        break;

    case 'PUT': // Sửa
        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data || !isset($data['id'])) {
            response(['error' => 'Thiếu ID'], 400);
        }

        $stmt = $pdo->prepare("UPDATE tien_do SET dang_ky_id=?, moc=?, file_path=?, nhan_xet=?, diem=?, trang_thai=? WHERE id=?");
        $stmt->execute([
            $data['dang_ky_id'],
            $data['moc'],
            $data['file_path'],
            $data['nhan_xet'],
            $data['diem'],
            $data['trang_thai'],
            $data['id']
        ]);
        response(['message' => 'Cập nhật thành công']);
        break;

    case 'DELETE': // Xóa
        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data || !isset($data['id'])) {
            response(['error' => 'Thiếu ID'], 400);
        }

        $stmt = $pdo->prepare("DELETE FROM tien_do WHERE id = ?");
        $stmt->execute([$data['id']]);
        response(['message' => 'Xóa thành công']);
        break;

    default:
        response(['error' => 'Phương thức không hỗ trợ'], 405);
}
?>