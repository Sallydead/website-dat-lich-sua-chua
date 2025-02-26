# Website Quản lý Yêu cầu Sửa chữa Máy Tính

Website quản lý yêu cầu sửa chữa máy tính được phát triển cho cửa hàng sửa chữa máy tính minhgiangpc.com. Website cho phép khách hàng dễ dàng gửi yêu cầu sửa chữa trực tuyến và theo dõi tiến độ xử lý. Đồng thời cung cấp công cụ quản lý hiệu quả cho nhân viên.

## Tính năng nổi bật

### Dành cho khách hàng
- **Đặt yêu cầu sửa chữa không cần đăng nhập**
  - Điền thông tin cơ bản
  - Mô tả vấn đề cần sửa
  - Đặt lịch hẹn sửa chữa
  - Nhận mã đơn để theo dõi

- **Tra cứu tiến độ**
  - Tra cứu qua mã đơn
  - Xem chi tiết trạng thái xử lý
  - Xem ghi chú từ nhân viên
  - Xem thông tin người phụ trách

- **Tài khoản thành viên**
  - Quản lý thông tin cá nhân
  - Xem lịch sử yêu cầu sửa chữa
  - Theo dõi tất cả yêu cầu
  - Tạo yêu cầu nhanh chóng với thông tin có sẵn

### Dành cho nhân viên
- **Quản lý yêu cầu**
  - Xem danh sách yêu cầu mới
  - Phân công xử lý
  - Cập nhật trạng thái
  - Thêm ghi chú cho khách hàng

- **Phân quyền người dùng**
  - Admin: Quản lý toàn bộ hệ thống
  - CSKH: Tiếp nhận và phân công yêu cầu
  - Kỹ thuật viên: Xử lý yêu cầu được phân công

- **Thống kê báo cáo**
  - Thống kê số lượng yêu cầu theo thời gian
  - Phân tích trạng thái xử lý
  - Đánh giá hiệu quả nhân viên
  - Xuất báo cáo

## Hướng dẫn cài đặt

### Yêu cầu hệ thống
- PHP 7.4 trở lên
- MySQL 5.7 trở lên
- Apache/Nginx
- Composer (để quản lý dependencies)

### Các bước cài đặt

1. Clone repository
```bash
git clone https://github.com/Sallydead/website-dat-lich-sua-chua.git
cd website-dat-lich-sua-chua
```

2. Tạo database và import cấu trúc
```bash
Nhập file database.sql vào database mysql
```

3. Cấu hình kết nối database
```php
// include/config.php
$host = 'localhost';
$dbname = 'database_name';
$username = 'username';
$password = 'password';
```

## Công nghệ sử dụng

- PHP 7.4+
- MySQL 5.7+
- Bootstrap 5

## Trạng thái yêu cầu

- 0: Chờ tiếp nhận
- 1: Đã tiếp nhận
- 2: Đang xử lý
- 3: Đã hoàn thành
- 4: Đã hủy

## Phân quyền người dùng

- 0: Khách hàng
- 1: Admin
- 2: CSKH
- 3: Kỹ thuật viên

## Đóng góp

Mọi đóng góp đều được chào đón. Vui lòng:

1. Fork dự án
2. Tạo branch mới (`git checkout -b feature/AmazingFeature`)
3. Commit thay đổi (`git commit -m 'Add some AmazingFeature'`)
4. Push lên branch (`git push origin feature/AmazingFeature`)
5. Tạo Pull Request

## Tác giả

- **Vũ Chí Linh** - [GitHub](https://github.com/Sallydead)

## License

[MIT License](LICENSE)

## Liên hệ

Nếu bạn có bất kỳ câu hỏi hoặc góp ý nào, vui lòng liên hệ:
- Email: luvtinno123@gmail.com
- Facebook: [Vũ Chí Linh](https://www.facebook.com/profile.php?id=100011454136446)
- Github: [Sallydead](https://github.com/Sallydead)
