<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- Nhúng file Quản lý các Liên kết CSS dùng chung cho toàn bộ trang web -->
    <?php include_once(__DIR__ . '/../../layouts/styles.php'); ?>
</head>
<body>

<main role="main">
    <!-- Block content -->
    <?php
        // Truy vấn database để lấy danh sách
        // 1. Include file cấu hình kết nối đến database, khởi tạo kết nối $conn
        include_once(__DIR__ . '/../../../dbconnect.php');
        /* --- 
        --- 2.Truy vấn dữ liệu truyện
        --- 
        */
        // Chuẩn bị câu truy vấn Loại sản phẩm
        $sqlTruyen = "SELECT * FROM truyen";
        // Thực thi câu lệnh sql
        $resultTruyen = mysqli_query($conn, $sqlTruyen);
        // Khi thực thi các truy vấn dạng SELECT, dữ liệu lấy về cần phải phân tích để sử dụng
        // Thông thường, chúng ta sẽ sử dụng vòng lặp while để duyệt danh sách các dòng dữ liệu được SELECT
        // Ta sẽ tạo 1 mảng array để chứa các dữ liệu được trả về
        $dataTruyen = [];
        while($rowTruyen = mysqli_fetch_array($resultTruyen, MYSQLI_ASSOC)) {
            $dataTruyen[] = array(
                'truyen_id' => $rowTruyen['truyen_id'],
                'truyen_ten' => $rowTruyen['truyen_ten'],
            );
        }
        /* --- End Truy vấn dữ liệu truyện --- */
    ?>
    <div class="container-fluid">
        <div class="row">
            <!-- sidebar -->
            <?php include_once(__DIR__ . '/../../layouts/partials/sidebar.php'); ?>
            <!-- End sidebar -->
            <div class="col-md-8">
                <h2>Thêm chương truyện tranh</h2>
                <form name="frmAdd" id="frmAdd" method="post" action="">
                    <div class="form-group">
                        <label for="chuong_sochuong">Chương số</label>
                        <input type="text" class="form-control" id="chuong_sochuong" name="chuong_sochuong" placeholder="Chương số">
                    </div>
                    <div class="form-group">
                        <label for="chuong_ten">Tên chương</label>
                        <input type="text" class="form-control" id="chuong_ten" name="chuong_ten" placeholder="Tên chương">
                    </div>
                    <div class="form-group">
                        <label for="chuong_noidung">Nội dung</label>
                        <input type="text" class="form-control" id="chuong_noidung" name="chuong_noidung" placeholder="Nội dung chương">
                    </div>
                    <div class="form-group">
                        <label for="truyen_id">Tên truyện</label>
                        <select class="form-control" id="truyen_id" name="truyen_id">
                            <?php foreach ($dataTruyen as $truyen) : ?>
                                <option value="<?= $truyen['truyen_id'] ?>"><?= $truyen['truyen_ten'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>                
                    <button class="btn btn-primary" name="btnSave">Lưu dữ liệu</button>
                </form>                                                    
            </div>
        </div>
    </div>

    <?php
        // 2. Nếu người dùng có bấm nút Lưu thì thực thi câu lệnh INSERT
        if (isset($_POST['btnSave'])) {           
            // Lấy dữ liệu người dùng hiệu chỉnh gởi từ REQUEST POST
            $chuong_sochuong = $_POST['chuong_sochuong'];
            $chuong_ten = $_POST['chuong_ten'];
            $chuong_noidung = $_POST['chuong_noidung'];
            $truyen_id = $_POST['truyen_id'];
            
            // Câu lệnh INSERT
            $sql = "INSERT INTO `chuong` (chuong_sochuong, chuong_ten, chuong_noidung, truyen_id) VALUES ('$chuong_sochuong', '$chuong_ten', '$chuong_noidung', $truyen_id);";
            // Thực thi INSERT
            mysqli_query($conn, $sql);
            // Đóng kết nối
            mysqli_close($conn);
            // Sau khi cập nhật dữ liệu, tự động điều hướng về trang Danh sách
            echo "<script>location.href = 'index.php';</script>";
        }
    ?>
    <!-- End block content -->
</main>

    <!-- Nhúng file quản lý phần SCRIPT JAVASCRIPT -->
    <?php include_once(__DIR__ . '/../../layouts/scripts.php'); ?>

    <!-- Các file Javascript sử dụng riêng cho trang này, liên kết tại đây -->


</body>
</html>