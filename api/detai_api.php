<?php
// api/detai_api.php
require_once 'db.php';

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET': // Lấy danh sách
        $stmt = $pdo->query("SELECT id, ma_de_tai, ten_de_tai, mo_ta, linh_vuc_id, so_luong_sv_toi_da, giang_vien_id, dot_do_an_id, trang_thai FROM de_tai");
        $detai = $stmt->fetchAll(PDO::FETCH_ASSOC);
        response($detai);
        break;

    case 'POST': // Thêm mới
        $data = json_decode(file_get_contents('php://input'), true);
        $stmt = $pdo->prepare("INSERT INTO de_tai (ma_de_tai, ten_de_tai, mo_ta, linh_vuc_id, so_luong_sv_toi_da, giang_vien_id, dot_do_an_id, trang_thai) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['ma_de_tai'],
            $data['ten_de_tai'],
            $data['mo_ta'] ?? '',
            $data['linh_vuc_id'],
            $data['so_luong_sv_toi_da'] ?? 1,
            $data['giang_vien_id'],
            $data['dot_do_an_id'],
            $data['trang_thai'] ?? 'mo'
        ]);
        response(['message' => 'Thêm thành công', 'id' => $pdo->lastInsertId()], 201);
        break;

    case 'PUT': // Sửa
        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data || !isset($data['id'])) {
            response(['error' => 'Thiếu ID'], 400);
        }

        $stmt = $pdo->prepare("UPDATE de_tai SET ma_de_tai=?, ten_de_tai=?, mo_ta=?, linh_vuc_id=?, so_luong_sv_toi_da=?, giang_vien_id=?, dot_do_an_id=?, trang_thai=? WHERE id=?");
        $stmt->execute([
            $data['ma_de_tai'],
            $data['ten_de_tai'],
            $data['mo_ta'],
            $data['linh_vuc_id'],
            $data['so_luong_sv_toi_da'],
            $data['giang_vien_id'],
            $data['dot_do_an_id'],
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

        $stmt = $pdo->prepare("DELETE FROM de_tai WHERE id = ?");
        $stmt->execute([$data['id']]);
        response(['message' => 'Xóa thành công']);
        break;

    default:
        response(['error' => 'Phương thức không hỗ trợ'], 405);
}
?>