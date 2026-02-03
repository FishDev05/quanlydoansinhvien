<?php
// api/dotdoan_api.php
require_once 'db.php';

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET': // Lấy danh sách
        $stmt = $pdo->query("SELECT id, ten_dot, nam_hoc, ngay_mo_dang_ky, ngay_dong_dang_ky, ngay_nop_cuoi, ngay_bao_ve, trang_thai FROM dot_do_an");
        $dotdoan = $stmt->fetchAll(PDO::FETCH_ASSOC);
        response($dotdoan);
        break;

    case 'POST': // Thêm mới
        $data = json_decode(file_get_contents('php://input'), true);
        $stmt = $pdo->prepare("INSERT INTO dot_do_an (ten_dot, nam_hoc, ngay_mo_dang_ky, ngay_dong_dang_ky, ngay_nop_cuoi, ngay_bao_ve, trang_thai) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['ten_dot'],
            $data['nam_hoc'],
            $data['ngay_mo_dang_ky'] ?? null,
            $data['ngay_dong_dang_ky'] ?? null,
            $data['ngay_nop_cuoi'] ?? null,
            $data['ngay_bao_ve'] ?? null,
            $data['trang_thai'] ?? 'dang_mo'
        ]);
        response(['message' => 'Thêm thành công', 'id' => $pdo->lastInsertId()], 201);
        break;

    case 'PUT': // Sửa
        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data || !isset($data['id'])) {
            response(['error' => 'Thiếu ID'], 400);
        }

        $stmt = $pdo->prepare("UPDATE dot_do_an SET ten_dot=?, nam_hoc=?, ngay_mo_dang_ky=?, ngay_dong_dang_ky=?, ngay_nop_cuoi=?, ngay_bao_ve=?, trang_thai=? WHERE id=?");
        $stmt->execute([
            $data['ten_dot'],
            $data['nam_hoc'],
            $data['ngay_mo_dang_ky'],
            $data['ngay_dong_dang_ky'],
            $data['ngay_nop_cuoi'],
            $data['ngay_bao_ve'],
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

        $stmt = $pdo->prepare("DELETE FROM dot_do_an WHERE id = ?");
        $stmt->execute([$data['id']]);
        response(['message' => 'Xóa thành công']);
        break;

    default:
        response(['error' => 'Phương thức không hỗ trợ'], 405);
}
?>