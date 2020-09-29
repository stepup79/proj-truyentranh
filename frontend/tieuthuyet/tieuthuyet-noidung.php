<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đọc truyện tranh tiểu thuyết online</title>
    <!-- Nhúng file Quản lý các Liên kết CSS dùng chung cho toàn bộ trang web -->
    <?php include_once(__DIR__ . '/../layouts/styles.php'); ?>
</head>
<body>
    
<main role="main">
    <!-- Block content -->
    <div class="alert alert-danger text-center" role="alert">
        <a href="/proj-truyentranh/frontend/index.php">Web tổng hợp truyện trinh thám và tiểu thuyết</a>
    </div>
    <?php
        // Truy vấn database để lấy danh sách
        // 1. Include file cấu hình kết nối đến database, khởi tạo kết nối $conn
        include_once(__DIR__ . '/../../dbconnect.php');
        /* --- 
        --- 2.Truy vấn dữ liệu Chương truyện tranh
        --- Lấy giá trị khóa chính được truyền theo dạng QueryString Parameter key1=value1&key2=value2...
        --- 
        */
        $truyen_id = $_GET['truyen_id'];
        $chuong_id = $_GET['chuong_id'];
        $sqlSelectTruyenTranh = <<<EOT
            SELECT tr.truyen_ten, 
            ch.chuong_sochuong, ch.chuong_ten, ch.chuong_noidung, ch.truyen_id, ch.chuong_id
        FROM truyen tr
        JOIN chuong ch ON tr.truyen_id = ch.truyen_id
        WHERE tr.truyen_id = $truyen_id AND ch.chuong_id = $chuong_id
EOT;

        // Thực thi câu truy vấn SQL để lấy về dữ liệu ban đầu của record 
        $resultSelectTruyenTranh = mysqli_query($conn, $sqlSelectTruyenTranh);

        // Khi thực thi các truy vấn dạng SELECT, dữ liệu lấy về cần phải phân tích để sử dụng
        // Thông thường, chúng ta sẽ sử dụng vòng lặp while để duyệt danh sách các dòng dữ liệu được SELECT
        // Ta sẽ tạo 1 mảng array để chứa các dữ liệu được trả về

        $truyentranhRow;
        while ($row = mysqli_fetch_array($resultSelectTruyenTranh, MYSQLI_ASSOC)) {
            $truyentranhRow = array(
                'truyen_ten' => $row['truyen_ten'],
                'chuong_sochuong' => $row['chuong_sochuong'],
                'chuong_ten' => $row['chuong_ten'],
                'chuong_noidung' => $row['chuong_noidung'],
                'truyen_id' => $row['truyen_id'],                               
                'chuong_id' => $row['chuong_id'],                                 
            );
        }
        /* --- End Truy vấn dữ liệu truyện tranh --- */
        // var_dump($truyentranhRow);die;

        /* --- 
        --- 3.Truy vấn dữ liệu Hình ảnh truyện 
        --- 
        */
        $sqlSelect = <<<EOT
            SELECT ha.hinhanh_id, ha.hinhanh_ten, ha.chuong_id
            FROM `hinhanh` ha
            WHERE ha.chuong_id = $chuong_id
EOT;

        // Thực thi câu truy vấn SQL để lấy về dữ liệu ban đầu của record 
        $result = mysqli_query($conn, $sqlSelect);

        // Khi thực thi các truy vấn dạng SELECT, dữ liệu lấy về cần phải phân tích để sử dụng
        // Thông thường, chúng ta sẽ sử dụng vòng lặp while để duyệt danh sách các dòng dữ liệu được SELECT
        // Ta sẽ tạo 1 mảng array để chứa các dữ liệu được trả về
        $danhsachhinhanh = [];
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $danhsachhinhanh[] = array(
                'hinhanh_id' => $row['hinhanh_id'],
                'hinhanh_ten' => $row['hinhanh_ten'],
                'chuong_id' => $row['chuong_id']
            );
        }
        // var_dump($danhsachhinhanh);die;
        /* --- End Truy vấn dữ liệu Hình ảnh truyện --- */

        // Hiệu chỉnh dữ liệu theo cấu trúc để tiện xử lý
        $truyentranhRow['danhsachhinhanh'] = $danhsachhinhanh;
    ?>

    <div class="container-fluid text-center">
        <div class="row">
            <div class="col">
                <h1><?= $truyentranhRow['truyen_ten'] ?></h1>
                <h3>Chương <?= $truyentranhRow['chuong_sochuong'] ?> - <?= $truyentranhRow['chuong_ten'] ?></h3>
                    <a href="" class="btn btn-primary">Chương trước</a>
                    <a href="tieuthuyet.php?truyen_id=<?= $truyentranhRow['truyen_id'] ?>" class="btn btn-outline-primary">Quay về danh sách chương</a>
                    <a href="" class="btn btn-primary">Chương sau</a>
                <div style="border: 1px dashed black;">
                    <p><?= $truyentranhRow['chuong_noidung'] ?></p>
                    <!-- Nếu có hình truyện tranh nào => duyệt vòng lặp để hiển thị các hình ảnh -->
                    <?php if (count($truyentranhRow['danhsachhinhanh']) > 0) : ?>
                        <div">
                            <?php foreach ($truyentranhRow['danhsachhinhanh'] as $hinhtruyentranh) : ?>
                                <div>
                                    <img src="/proj-truyentranh/assets/uploads/products/<?= $hinhtruyentranh['hinhanh_ten'] ?>" />
                                </div>
                            <?php endforeach; ?>
                        </div>                      
                        <!-- Không có hình truyện tranh nào => lấy ảnh mặc định -->
                    <?php else : ?>
                            <div>
                                <img src="/proj-truyentranh/assets/shared/img/default-image_600.png" />
                            </div>
                    <?php endif; ?>                   
                </div>
            </div>
        </div>
    </div>

    <!-- End block content -->
</main>

    <!-- Nhúng file quản lý phần SCRIPT JAVASCRIPT -->
    <?php include_once(__DIR__ . '/../layouts/scripts.php'); ?>

    <!-- Các file Javascript sử dụng riêng cho trang này, liên kết tại đây -->
</body>
</html>