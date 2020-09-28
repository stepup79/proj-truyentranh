<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đọc truyện tranh online</title>
    <link href="/proj-truyentranh/assets/vendor/bootstrap/css/bootstrap.min.css" type="text/css" rel="stylesheet"/>
</head>
<body>

<?php
    // Truy vấn database để lấy danh sách
    // 1. Include file cấu hình kết nối đến database, khởi tạo kết nối $conn
    include_once(__DIR__ . '/../dbconnect.php');

    // 2. Chuẩn bị câu truy vấn sql lấy danh sách tiểu thuyết
    $sqlDanhSachTieuThuyet = <<<EOT
        SELECT truyen_id, truyen_ma, truyen_ten, truyen_hinhdaidien, truyen_motangan
    FROM truyen
    WHERE truyen_loai = 1
EOT;

    // 3. Thực thi câu truy vấn SQL để lấy về dữ liệu
    $result = mysqli_query($conn, $sqlDanhSachTieuThuyet);

    // 4. Khi thực thi các truy vấn dạng SELECT, dữ liệu lấy về cần phải phân tích để sử dụng
    // Thông thường, chúng ta sẽ sử dụng vòng lặp while để duyệt danh sách các dòng dữ liệu được SELECT
    // Ta sẽ tạo 1 mảng array để chứa các dữ liệu được trả về
    $dataDanhSachTieuThuyet = [];
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $dataDanhSachTieuThuyet[] = array(
            'truyen_id' => $row['truyen_id'],
            'truyen_ma' => $row['truyen_ma'],
            'truyen_ten' => $row['truyen_ten'],
            'truyen_hinhdaidien' => $row['truyen_hinhdaidien'],
            'truyen_motangan' => $row['truyen_motangan'],
        );
    }
    // var_dump($dataDanhSachTieuThuyet);die;

    // 2. Chuẩn bị câu truy vấn sql lấy danh sách trinh thám
    $sqlDanhSachTruyenTranh = <<<EOT
        SELECT truyen_id, truyen_ma, truyen_ten, truyen_hinhdaidien, truyen_motangan
    FROM truyen
    WHERE truyen_loai = 2
EOT;

    // 3. Thực thi câu truy vấn SQL để lấy về dữ liệu
    $result = mysqli_query($conn, $sqlDanhSachTruyenTranh);

    // 4. Khi thực thi các truy vấn dạng SELECT, dữ liệu lấy về cần phải phân tích để sử dụng
    // Thông thường, chúng ta sẽ sử dụng vòng lặp while để duyệt danh sách các dòng dữ liệu được SELECT
    // Ta sẽ tạo 1 mảng array để chứa các dữ liệu được trả về
    $dataDanhSachTruyenTranh = [];
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $dataDanhSachTruyenTranh[] = array(
            'truyen_id' => $row['truyen_id'],
            'truyen_ma' => $row['truyen_ma'],
            'truyen_ten' => $row['truyen_ten'],
            'truyen_hinhdaidien' => $row['truyen_hinhdaidien'],
            'truyen_motangan' => $row['truyen_motangan'],
        );
    }
    // var_dump($dataDanhSachTruyenTranh);die;
?>


    <main role="main">
        <div class="alert alert-danger text-center" role="alert">
        <a href="">Web tổng hợp truyện trinh thám và tiểu thuyết</a>
        </div>
        <div class="container">
            <div class="alert alert-primary" role="alert">
                Danh sách truyện tiểu thuyết
            </div>
            <div class="row row-cols-3">                                         
                    <?php foreach($dataDanhSachTieuThuyet as $tieuthuyet) : ?>
                        <div class="col">
                            <div class="card" style="width: 18rem;">
                                <img src="<?= $tieuthuyet['truyen_hinhdaidien'] ?>" class="card-img-top" alt="...">
                                <div class="card-body">
                                    <h5 class="card-title"><?= $tieuthuyet['truyen_ten'] ?></h5>
                                    <p class="card-text"><?= $tieuthuyet['truyen_motangan'] ?></p>
                                    <a href="/proj-truyentranh/frontend/tieuthuyet/tieuthuyet.php?truyen_id=<?= $tieuthuyet['truyen_id'] ?>" class="btn btn-primary">Xem tiểu thuyết</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach ;?>               
            </div>
        </div>
        <div class="container">
            <div class="alert alert-primary" role="alert">
                Danh sách truyện tranh
            </div>
            <div class="row row-cols-3">                                         
                    <?php foreach($dataDanhSachTruyenTranh as $truyentranh) : ?>
                        <div class="col">
                            <div class="card" style="width: 18rem;">
                                <img src="<?= $truyentranh['truyen_hinhdaidien'] ?>" class="card-img-top" alt="...">
                                <div class="card-body">
                                    <h5 class="card-title"><?= $truyentranh['truyen_ten'] ?></h5>
                                    <p class="card-text"><?= $truyentranh['truyen_motangan'] ?></p>
                                    <a href="/proj-truyentranh/frontend/truyentranh/truyentranh.php?truyen_id=<?= $truyentranh['truyen_id'] ?>" class="btn btn-primary">Xem truyện</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach ;?>               
            </div>
        </div>
    </main>

    <script src="/proj-truyentranh/assets/vendor/jquery/jquery.min.js"></script>
    <script src="/proj-truyentranh/assets/vendor/bootstrap/js/bootstrap.min.js"></script>

</body>
</html>