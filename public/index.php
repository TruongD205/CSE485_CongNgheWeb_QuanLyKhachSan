<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__ . '/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__ . '/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__ . '/../bootstrap/app.php';

$app->handleRequest(Request::capture());

/*
1. Người dùng bấm URL (VD: /rooms)
2. File public/index.php tiếp nhận đầu tiên và gọi bootstrap/app.php
3. Trong app.php: Đăng ký alias ('dangnhap' => CheckLogin) và nạp routes/web.php
4. Router đọc URL /rooms và thấy route này có gắn middleware 'dangnhap'
5. Laravel tra alias 'dangnhap' ra CheckLogin và nhảy vào hàm handle()
6. Kiểm tra điều kiện:
   - Nếu ĐÃ ĐĂNG NHẬP (có Session AccountID) -> Cho đi tiếp vào RoomController@index để hiển thị danh sách phòng.
   - Nếu CHƯA ĐĂNG NHẬP -> Chặn lại và chuyển hướng (redirect) về trang /login.
*/