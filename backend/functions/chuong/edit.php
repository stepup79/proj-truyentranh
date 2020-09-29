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
        /* --- 
        --- 3. Truy vấn dữ liệu Chương theo khóa chính
        --- 
        */
        // Chuẩn bị câu truy vấn $sqlSelect, lấy dữ liệu ban đầu của record cần update
        // Lấy giá trị khóa chính được truyền theo dạng QueryString Parameter key1=value1&key2=value2...
        $chuong_id = $_GET['chuong_id'];
        $sqlSelect = "SELECT * FROM `chuong` WHERE chuong_id=$chuong_id;";
        // Thực thi câu truy vấn SQL để lấy về dữ liệu ban đầu của record cần update
        $resultSelect = mysqli_query($conn, $sqlSelect);
        $chuongRow = mysqli_fetch_array($resultSelect, MYSQLI_ASSOC); // 1 record
        /* --- End Truy vấn dữ liệu Chương theo khóa chính --- */
    ?>
    <div class="container-fluid">
        <div class="row">
            <!-- sidebar -->
            <?php include_once(__DIR__ . '/../../layouts/partials/sidebar.php'); ?>
            <!-- End sidebar -->
            <div class="col-md-8">
                <h2>Thêm chương truyện tranh</h2>
                <form name="frmUpdate" id="frmUpdate" method="post" action="">
                    <div class="form-group">
                        <label for="chuong_sochuong">Chương số</label>
                        <input type="text" class="form-control" id="chuong_sochuong" name="chuong_sochuong" value="<?= $chuongRow['chuong_sochuong'] ?>">
                    </div>
                    <div class="form-group">
                        <label for="chuong_ten">Tên chương</label>
                        <input type="text" class="form-control" id="chuong_ten" name="chuong_ten" value="<?= $chuongRow['chuong_ten'] ?>">
                    </div>
                    <div class="form-group">
                        <label for="chuong_noidung">Nội dung</label>
                        <input type="text" class="form-control" id="chuong_noidung" name="chuong_noidung" value="<?= $chuongRow['chuong_noidung'] ?>">
                    </div>
                    <div class="form-group">
                        <label for="truyen_id">Tên truyện</label>
                        <select class="form-control" id="truyen_id" name="truyen_id">
                            <?php foreach ($dataTruyen as $truyen) : ?>                                
                                <?php if ($truyen['truyen_id'] == $chuongRow['truyen_id']) : ?>
                                    <option value="<?= $truyen['truyen_id'] ?>" selected><?= $truyen['truyen_ten'] ?></option>
                                <?php else : ?>
                                    <option value="<?= $truyen['truyen_id'] ?>"><?= $truyen['truyen_ten'] ?></option>
                                <?php endif; ?>
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
            
            // Câu lệnh UPDATE
            $sql = "UPDATE `chuong` SET chuong_sochuong='$chuong_sochuong', chuong_ten='$chuong_ten', chuong_noidung='$chuong_noidung', truyen_id='$truyen_id' WHERE chuong_id=$chuong_id;";
            // Thực thi UPDATE
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