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
        --- 2.Truy vấn dữ liệu chương
        --- 
        */
        // Chuẩn bị câu truy vấn Loại sản phẩm
        $sqlChuong = "SELECT * FROM chuong";
        // Thực thi câu lệnh sql
        $resultChuong = mysqli_query($conn, $sqlChuong);
        // Khi thực thi các truy vấn dạng SELECT, dữ liệu lấy về cần phải phân tích để sử dụng
        // Thông thường, chúng ta sẽ sử dụng vòng lặp while để duyệt danh sách các dòng dữ liệu được SELECT
        // Ta sẽ tạo 1 mảng array để chứa các dữ liệu được trả về
        $dataChuong = [];
        while($rowChuong = mysqli_fetch_array($resultChuong, MYSQLI_ASSOC)) {
            $dataChuong[] = array(
                'chuong_id' => $rowChuong['chuong_id'],
                'chuong_ten' => $rowChuong['chuong_ten'],
            );
        }
        /* --- End Truy vấn dữ liệu chương --- */
    ?>

    <div class="container-fluid">
        <div class="row">
            <!-- sidebar -->
            <?php include_once(__DIR__ . '/../../layouts/partials/sidebar.php'); ?>
            <!-- End sidebar -->
            <div class="col-md-8">
                <h2>Thêm hình ảnh truyện tranh</h2>
                <form name="frmAdd" id="frmAdd" method="post" action="" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="hinhanh_ten">Tập tin ảnh</label>
                        <!-- Tạo khung div hiển thị ảnh cho người dùng Xem trước khi upload file lên Server -->
                        <div class="preview-img-container">
                        <img src="/proj-truyentranh/assets/shared/img/default-image-600.png" id="preview-img" width="200px" />
                        </div>
                        <!-- Input cho phép người dùng chọn FILE -->
                        <input type="file" class="form-control" id="hinhanh_ten" name="hinhanh_ten">
                    </div>
                    <div class="form-group">
                        <label for="chuong_id">Tên chương</label>
                        <select class="form-control" id="chuong_id" name="chuong_id">
                            <?php foreach ($dataChuong as $chuong) : ?>
                                <option value="<?= $chuong['chuong_id'] ?>"><?= $chuong['chuong_ten'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>                 
                    <button class="btn btn-primary" name="btnSave">Lưu dữ liệu</button>
                </form>                                                    
            </div>
        </div>
    </div>

    <?php
        // 3. Nếu người dùng có bấm nút Lưu thì thực thi câu lệnh INSERT
        if (isset($_POST['btnSave'])) {           
            // Lấy dữ liệu người dùng hiệu chỉnh gởi từ REQUEST POST
            // Nếu người dùng có chọn file để upload
            if (isset($_FILES['hinhanh_ten'])) {
                // Đường dẫn để chứa thư mục upload trên ứng dụng web của chúng ta. Các bạn có thể tùy chỉnh theo ý các bạn.
                // Ví dụ: các file upload sẽ được lưu vào thư mục ../../../assets/uploads
                $upload_dir = __DIR__ . "/../../../assets/uploads/";
                // Các hình ảnh sẽ được lưu trong thư mục con `products` để tiện quản lý
                $subdir = 'products/';
                // Đối với mỗi file, sẽ có các thuộc tính như sau:
                // $_FILES['hinhanh_ten']['name']     : Tên của file chúng ta upload
                // $_FILES['hinhanh_ten']['type']     : Kiểu file mà chúng ta upload (hình ảnh, word, excel, pdf, txt, ...)
                // $_FILES['hinhanh_ten']['tmp_name'] : Đường dẫn đến file tạm trên web server
                // $_FILES['hinhanh_ten']['error']    : Trạng thái của file chúng ta upload, 0 => không có lỗi
                // $_FILES['hinhanh_ten']['size']     : Kích thước của file chúng ta upload
                // 3.1. Chuyển file từ thư mục tạm vào thư mục Uploads
                // Nếu file upload bị lỗi, tức là thuộc tính error > 0
                if ($_FILES['hinhanh_ten']['error'] > 0) {
                    echo 'File Upload Bị Lỗi'; die;
                } else {
                    // Để tránh trường hợp có 2 người dùng cùng lúc upload tập tin trùng tên nhau
                    // Ví dụ:
                    // - Người 1: upload tập tin hình ảnh tên `hoahong.jpg`
                    // - Người 2: cũng upload tập tin hình ảnh tên `hoahong.jpg`
                    // => dẫn đến tên tin trong thư mục chỉ còn lại tập tin người dùng upload cuối cùng
                    // Cách giải quyết đơn giản là chúng ta sẽ ghép thêm ngày giờ vào tên file
                    $hinhanh_ten = $_FILES['hinhanh_ten']['name'];
                    $tentaptin = date('YmdHis') . '_' . $hinhanh_ten; //20200530154922_hoahong.jpg
                    // Tiến hành di chuyển file từ thư mục tạm trên server vào thư mục chúng ta muốn chứa các file uploads
                    // Ví dụ: move file từ C:\xampp\tmp\php6091.tmp -> C:/xampp/htdocs/learning.nentang.vn/php/twig/assests/uploads/hoahong.jpg
                    // var_dump($_FILES['hinhanh_ten']['tmp_name']);
                    // var_dump($upload_dir . $subdir . $tentaptin);
                    move_uploaded_file($_FILES['hinhanh_ten']['tmp_name'], $upload_dir . $subdir . $tentaptin);
            }
                $chuong_id = $_POST['chuong_id'];
                // Câu lệnh INSERT
                $sqlInsert = "INSERT INTO `hinhanh` (hinhanh_ten, chuong_id) VALUES ('$tentaptin', '$chuong_id');";          
                // var_dump($sqlInsert);die;
                // Thực thi INSERT
                mysqli_query($conn, $sqlInsert);
                // Đóng kết nối
                mysqli_close($conn);
                // Sau khi cập nhật dữ liệu, tự động điều hướng về trang Danh sách
                echo "<script>location.href = 'index.php';</script>";
            }
        }
    ?>
    <!-- End block content -->
</main>

    <!-- Nhúng file quản lý phần SCRIPT JAVASCRIPT -->
    <?php include_once(__DIR__ . '/../../layouts/scripts.php'); ?>

    <!-- Các file Javascript sử dụng riêng cho trang này, liên kết tại đây -->

    <script>  
    // Hiển thị ảnh preview (xem trước) khi người dùng chọn Ảnh
    const reader = new FileReader();
    const fileInput = document.getElementById("hinhanh_ten");
    const img = document.getElementById("preview-img");
    reader.onload = e => {
      img.src = e.target.result;
    }
    fileInput.addEventListener('change', e => {
      const f = e.target.files[0];
      reader.readAsDataURL(f);
    })
    </script>
</body>
</html>