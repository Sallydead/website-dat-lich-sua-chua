<?php
include 'config.php';
function generateAvatarFilename($user_id, $extension) {
    return $user_id . '_' . date('YmdHis') . '.' . $extension;
} 

// Lấy màu cho trạng thái
function get_status_color($status) {
    switch($status) {
        case 0: return 'secondary'; // Chờ tiếp nhận
        case 1: return 'info';      // Đã tiếp nhận
        case 2: return 'primary';   // Đang xử lý
        case 3: return 'success';   // Đã hoàn thành
        case 4: return 'danger';    // Đã hủy
        default: return 'secondary';
    }
}

// Lấy text cho trạng thái
function get_status_text($status) {
    switch($status) {
        case 0: return 'Chờ tiếp nhận';
        case 1: return 'Đã tiếp nhận';
        case 2: return 'Đang xử lý';
        case 3: return 'Đã hoàn thành'; 
        case 4: return 'Đã hủy';
        default: return 'Không xác định';
    }
} 