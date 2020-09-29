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
    <div class="container-fluid">
        <div class="row">
            <!-- sidebar -->
            <?php include_once(__DIR__ . '/../../layouts/partials/sidebar.php'); ?>
            <!-- End sidebar -->
            <div class="col-md-8">
                <h2>Thêm truyện tranh</h2>
                <form name="frmAdd" id="frmAdd" method="post" action="" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="truyen_ma">Mã truyện</label>
                        <input type="text" class="form-control" id="truyen_ma" name="truyen_ma" placeholder="Mã truyện">
                    </div>
                    <div class="form-group">
                        <label for="truyen_ten">Tên truyện</label>
                        <input type="text" class="form-control" id="truyen_ten" name="truyen_ten" placeholder="Tên truyện">
                    </div>
                    <div class="form-group">
                        <label for="truyen_hinhdaidien">Tập tin ảnh</label>
                        <!-- Tạo khung div hiển thị ảnh cho người dùng Xem trước khi upload file lên Server -->
                        <div class="preview-img-container">
                        <img src="/proj-truyentranh/assets/shared/img/default-image-600.png" id="preview-img" width="200px" />
                        </div>
                        <!-- Input cho phép người dùng chọn FILE -->
                        <input type="file" class="form-control" id="truyen_hinhdaidien" name="truyen_hinhdaidien">
                    </div>
                    <div class="form-group">
                        <label for="truyen_loai">Loại truyện</label>
                        <select class="form-control" id="truyen_loai" name="truyen_loai">
                                <option value="1">Tiểu thuyết</option>
                                <option value="2">Truyện tranh</option>                   
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="truyen_theloai">Thể loại truyện</label>
                        <input type="text" class="form-control" id="truyen_theloai" name="truyen_theloai" placeholder="Thể loại truyện">
                    </div>
                    <div class="form-group">
                        <label for="truyen_tacgia">Tác giả</label>
                        <input type="text" class="form-control" id="truyen_tacgia" name="truyen_tacgia" placeholder="Tác giả">
                    </div>
                    <div class="form-group">
                        <label for="truyen_motangan">Mô tả truyện</label>
                        <input type="text" class="form-control" id="truyen_motangan" name="truyen_motangan" placeholder="Mô tả truyện">
                    </div>
                    <div class="form-group">
                        <label for="truyen_ngaydang">Ngày đăng truyện</label>
                        <input type="date" class="form-control" id="truyen_ngaydang" name="truyen_ngaydang" placeholder="Ngày đăng truyện">
                    </div>
                    <button class="btn btn-primary" name="btnSave">Lưu dữ liệu</button>
                </form>                                                    
            </div>
        </div>
    </div>

    <?php
        // Truy vấn database để lấy danh sách
        // 1. Include file cấu hình kết nối đến database, khởi tạo kết nối $conn
        include_once(__DIR__ . '/../../../dbconnect.php');

        // 2. Nếu người dùng có bấm nút Lưu thì thực thi câu lệnh INSERT
        if (isset($_POST['btnSave'])) {           
            // Lấy dữ liệu người dùng hiệu chỉnh gởi từ REQUEST POST
            $truyen_ma = $_POST['truyen_ma'];
            $truyen_ten = $_POST['truyen_ten'];
            // Nếu người dùng có chọn file để upload
            if (isset($_FILES['truyen_hinhdaidien'])) {
                // Đường dẫn để chứa thư mục upload trên ứng dụng web của chúng ta. Các bạn có thể tùy chỉnh theo ý các bạn.
                // Ví dụ: các file upload sẽ được lưu vào thư mục ../../../assets/uploads
                $upload_dir = __DIR__ . "/../../../assets/uploads/";
                // Các hình ảnh sẽ được lưu trong thư mục con `products` để tiện quản lý
                $subdir = 'products/';
                // Đối với mỗi file, sẽ có các thuộc tính như sau:
                // $_FILES['hsp_tentaptin']['name']     : Tên của file chúng ta upload
                // $_FILES['hsp_tentaptin']['type']     : Kiểu file mà chúng ta upload (hình ảnh, word, excel, pdf, txt, ...)
                // $_FILES['hsp_tentaptin']['tmp_name'] : Đường dẫn đến file tạm trên web server
                // $_FILES['hsp_tentaptin']['error']    : Trạng thái của file chúng ta upload, 0 => không có lỗi
                // $_FILES['hsp_tentaptin']['size']     : Kích thước của file chúng ta upload
                // 3.1. Chuyển file từ thư mục tạm vào thư mục Uploads
                // Nếu file upload bị lỗi, tức là thuộc tính error > 0
                if ($_FILES['truyen_hinhdaidien']['error'] > 0) {
                    echo 'File Upload Bị Lỗi'; die;
                } else {
                    // Để tránh trường hợp có 2 người dùng cùng lúc upload tập tin trùng tên nhau
                    // Ví dụ:
                    // - Người 1: upload tập tin hình ảnh tên `hoahong.jpg`
                    // - Người 2: cũng upload tập tin hình ảnh tên `hoahong.jpg`
                    // => dẫn đến tên tin trong thư mục chỉ còn lại tập tin người dùng upload cuối cùng
                    // Cách giải quyết đơn giản là chúng ta sẽ ghép thêm ngày giờ vào tên file
                    $truyen_hinhdaidien = $_FILES['truyen_hinhdaidien']['name'];
                    $tentaptin = date('YmdHis') . '_' . $truyen_hinhdaidien; //20200530154922_hoahong.jpg
                    // Tiến hành di chuyển file từ thư mục tạm trên server vào thư mục chúng ta muốn chứa các file uploads
                    // Ví dụ: move file từ C:\xampp\tmp\php6091.tmp -> C:/xampp/htdocs/learning.nentang.vn/php/twig/assests/uploads/hoahong.jpg
                    // var_dump($_FILES['truyen_hinhdaidien']['tmp_name']);
                    // var_dump($upload_dir . $subdir . $tentaptin);
                    move_uploaded_file($_FILES['truyen_hinhdaidien']['tmp_name'], $upload_dir . $subdir . $tentaptin);
            }
            $truyen_loai = $_POST['truyen_loai'];
            $truyen_theloai = $_POST['truyen_theloai'];
            $truyen_tacgia = $_POST['truyen_tacgia'];
            $truyen_motangan = $_POST['truyen_motangan'];
            $truyen_ngaydang = $_POST['truyen_ngaydang'];
            // Câu lệnh INSERT
            $sqlInsert = "INSERT INTO `truyen` (truyen_ma, truyen_ten, truyen_hinhdaidien, truyen_loai, truyen_theloai, truyen_tacgia, truyen_motangan, truyen_ngaydang) VALUES ('$truyen_ma', '$truyen_ten', '$tentaptin', '$truyen_loai', '$truyen_theloai', '$truyen_tacgia', '$truyen_motangan', '$truyen_ngaydang');";          
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
    const fileInput = document.getElementById("truyen_hinhdaidien");
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