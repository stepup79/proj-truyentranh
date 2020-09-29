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
        --- 2.Truy vấn dữ liệu truyện tranh
        --- Lấy giá trị khóa chính được truyền theo dạng QueryString Parameter key1=value1&key2=value2...
        --- 
        */
        $truyen_id = $_GET['truyen_id'];
        $sqlSelectTruyenTranh = <<<EOT
            SELECT truyen_id, truyen_ma, truyen_ten, truyen_hinhdaidien, truyen_theloai, truyen_tacgia, truyen_motangan, truyen_ngaydang 
            FROM `truyen` tt
            WHERE truyen_id = $truyen_id
    EOT;

        // Thực thi câu truy vấn SQL để lấy về dữ liệu ban đầu của record 
        $resultSelectTruyenTranh = mysqli_query($conn, $sqlSelectTruyenTranh);

        // Khi thực thi các truy vấn dạng SELECT, dữ liệu lấy về cần phải phân tích để sử dụng
        // Thông thường, chúng ta sẽ sử dụng vòng lặp while để duyệt danh sách các dòng dữ liệu được SELECT
        // Ta sẽ tạo 1 mảng array để chứa các dữ liệu được trả về

        $truyentranhRow;
        while ($row = mysqli_fetch_array($resultSelectTruyenTranh, MYSQLI_ASSOC)) {
            $truyentranhRow = array(
                'truyen_id' => $row['truyen_id'],
                'truyen_ma' => $row['truyen_ma'],
                'truyen_ten' => $row['truyen_ten'],
                'truyen_hinhdaidien' => $row['truyen_hinhdaidien'],
                'truyen_theloai' => $row['truyen_theloai'],
                'truyen_tacgia' => $row['truyen_tacgia'],
                'truyen_motangan' => $row['truyen_motangan'],
                'truyen_ngaydang' => date('d/m/Y', strtotime($row['truyen_ngaydang'])),
            );
        }
        /* --- End Truy vấn dữ liệu truyện tranh --- */

        /* --- 
        --- 3.Truy vấn dữ liệu Chương truyện 
        --- 
        */
        $sqlSelectChuong = <<<EOT
            SELECT chuong_id, chuong_sochuong, chuong_ten, chuong_noidung
            FROM `chuong` 
            WHERE truyen_id = $truyen_id
    EOT;

        // Thực thi câu truy vấn SQL để lấy về dữ liệu ban đầu của record 
        $resultSelectChuong = mysqli_query($conn, $sqlSelectChuong);

        // Khi thực thi các truy vấn dạng SELECT, dữ liệu lấy về cần phải phân tích để sử dụng
        // Thông thường, chúng ta sẽ sử dụng vòng lặp while để duyệt danh sách các dòng dữ liệu được SELECT
        // Ta sẽ tạo 1 mảng array để chứa các dữ liệu được trả về
        $danhsachchuong = [];
        while ($row = mysqli_fetch_array($resultSelectChuong, MYSQLI_ASSOC)) {
            $danhsachchuong[] = array(
                'chuong_id' => $row['chuong_id'],
                'chuong_sochuong' => $row['chuong_sochuong'],
                'chuong_ten' => $row['chuong_ten'],
                'chuong_noidung' => $row['chuong_noidung']
            );
        }
        /* --- End Truy vấn dữ liệu Chương truyện --- */

        // Hiệu chỉnh dữ liệu theo cấu trúc để tiện xử lý
        $truyentranhRow['danhsachchuong'] = $danhsachchuong;
        // var_dump($truyentranhRow);die;
    ?>

        <div class="container-fluid">
        <h3>Thông tin</h3>
            <div class="row" style="border: 1px solid green; margin-left: 10px; margin-right: 10px;">
                <div class="col-md-4">
                    <img src="/proj-truyentranh/assets/uploads/products/<?= $truyentranhRow['truyen_hinhdaidien'] ?>" class="img-fluid"/>
                    <p><b>Tác giả:</b> <?= $truyentranhRow['truyen_tacgia'] ?></p>
                    <p><b>Thể loại:</b> <?= $truyentranhRow['truyen_theloai'] ?></p>
                    <p><b>Ngày đăng truyện:</b> <?= $truyentranhRow['truyen_ngaydang'] ?></p>
                </div>
                <div class="col-md-8">
                    <h2 class="text-center"><?= $truyentranhRow['truyen_ten'] ?></h2>
                    <div style="border: 1px dashed black;">
                        <p><?= $truyentranhRow['truyen_motangan'] ?></p>
                    </div>
                </div>
            </div>
        </div>

    <div class="card">
        <div class="container-fluid">
            <h3>Danh sách chương truyện <?= $truyentranhRow['truyen_ten'] ?></h3>
            <div class="row">
                <div class="col">
                    <ul>
                        <?php foreach($danhsachchuong as $chuong): ?>
                            <a href="/proj-truyentranh/frontend/truyentranh/truyentranh-noidung.php?truyen_id=<?= $truyentranhRow['truyen_id'] ?>&chuong_id=<?= $chuong['chuong_id'] ?>">
                                <li>Chương <?= $chuong['chuong_sochuong'] ?>: <?= $chuong['chuong_ten'] ?> - <?= $chuong['chuong_noidung'] ?></li>
                        <?php endforeach; ?>
                    </ul>
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