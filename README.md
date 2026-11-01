# 🏨 Hệ Thống Quản Lý Khách Sạn (Hotel Management System)

> Dự án bài tập lớn / đồ án môn **Công nghệ Web (CSE485)** xây dựng bằng **Laravel Framework 12** và cơ sở dữ liệu **MySQL**.

---

## 🛠 Yêu cầu môi trường (Prerequisites)

Trước khi tiến hành cài đặt, máy tính của bạn cần có sẵn:
* **PHP >= 8.2**
* **Composer** (bản 2.x trở lên)
* **MySQL / MariaDB** (thông qua XAMPP, Laragon hoặc MySQL Server)
* **Git**

---

## 🚀 Hướng dẫn cài đặt và khởi chạy dự án

### Bước 1: Clone dự án về máy tính
Mở Git Bash hoặc Terminal và chạy lệnh:
```bash
git clone https://github.com/TruongD205/CSE485_CongNgheWeb_QuanLyKhachSan.git
cd CSE485_CongNgheWeb_QuanLyKhachSan
```

### Bước 2: Cài đặt các thư viện phụ thuộc (Composer)
```bash
composer install
```

### Bước 3: Cấu hình file môi trường (.env)
1. Sao chép file cấu hình mẫu `.env.example` thành `.env`:
   ```bash
   cp .env.example .env
   ```
2. Mở file `.env` bằng trình soạn thảo (VS Code, Notepad...) và kiểm tra cấu hình kết nối CSDL:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=qlks
   DB_USERNAME=root
   DB_PASSWORD=
   ```
   > ⚠️ **Lưu ý:** Hãy mở **XAMPP Control Panel**, nhấn **Start MySQL**, sau đó vào phpMyAdmin (`http://localhost/phpmyadmin`) tạo một cơ sở dữ liệu mới có tên là **`qlks`** (nếu chưa có).

### Bước 4: Tạo khóa bí mật cho ứng dụng (Application Key)
```bash
php artisan key:generate
```

### Bước 5: Chạy Migration và nạp dữ liệu mẫu (Seeder)
Lệnh này sẽ tự động khởi tạo tất cả các bảng trong CSDL và nạp sẵn dữ liệu mẫu về phòng, đơn đặt phòng và tài khoản quản trị:
```bash
php artisan migrate:fresh --seed
```

### Bước 6: Khởi chạy ứng dụng
```bash
php artisan serve
```
Sau khi server chạy, truy cập vào trình duyệt theo đường dẫn:  
👉 **http://127.0.0.1:8000** (hoặc `http://localhost:8000`)

---

## 🔑 Tài khoản đăng nhập thử nghiệm

Hệ thống đã có sẵn 3 tài khoản mẫu được nạp từ Seeder:

| Tên đăng nhập | Mật khẩu | Email | Vai trò |
| :--- | :--- | :--- | :--- |
| `truong` | `123456` | truong@gmail.com | Quản trị viên |
| `dat` | `123456` | dat@gmail.com | Quản trị viên |
| `kien` | `123456` | kien@gmail.com | Quản trị viên |

*(Bạn cũng có thể bấm vào nút **"Đăng ký ngay"** trên giao diện để tạo một tài khoản mới).*

---

## ✨ Các chức năng chính của hệ thống

1. **Xác thực & Kiểm soát truy cập (Authentication & Access Control):**
   * Đăng ký tài khoản mới, Đăng nhập, Đăng xuất an toàn.
   * Middleware `CheckLogin` (`dangnhap`): Tự động chặn người chưa đăng nhập truy cập vào các trang nội bộ (phòng, đặt phòng).
   * Middleware `CheckGuest` (`khach`): Tự động ngăn người đã đăng nhập quay lại form đăng nhập/đăng ký.

2. **Quản lý phòng (Room Management - CRUD):**
   * Xem danh sách tất cả phòng (có phân trang).
   * Xem chi tiết từng phòng.
   * Thêm phòng mới (Validate số phòng 3 chữ số, không trùng lặp).
   * Sửa thông tin phòng (Số phòng, Loại phòng, Giá phòng, Sức chứa, Mô tả).
   * Xóa phòng an toàn: Có cảnh báo và kiểm tra nếu phòng đang có khách ở thì không được xóa.

3. **Quản lý đặt phòng (Booking Management - CRUD):**
   * Xem danh sách các đơn đặt phòng.
   * Thêm đơn đặt phòng mới: Tự động kiểm tra trùng lịch đặt phòng (tránh 2 khách cùng đặt 1 phòng trong cùng khoảng thời gian).
   * Cập nhật trạng thái đơn đặt phòng (Đã đặt / Đang ở / Đã trả phòng).
   * **Cơ chế tự động hóa:** Trạng thái phòng (`RoomStatus`) sẽ tự động cập nhật đồng bộ tương ứng theo trạng thái của các đơn đặt phòng.

---

## 📚 Thông tin công nghệ sử dụng
* **Framework:** [Laravel 12](https://laravel.com)
* **Frontend:** Blade Template, Bootstrap 5
* **Database:** MySQL
* **Kiến trúc:** MVC (Model - View - Controller)
