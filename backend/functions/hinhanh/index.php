<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- Nhúng file Quản lý các Liên kết CSS dùng chung cho toàn bộ trang web -->
    <?php include_once(__DIR__ . '/../../layouts/styles.php'); ?>
    <!-- DataTable CSS -->
    <link href="/proj-truyentranh/assets/vendor/DataTables/datatables.css" type="text/css" rel="stylesheet"/>
    <link href="/proj-truyentranh/assets/vendor/DataTables/Buttons-1.6.3/css/buttons.bootstrap4.css" type="text/css" rel="stylesheet"/>
</head>
<body>

<main role="main">
    <!-- Block content -->
    <?php
        // Truy vấn database để lấy danh sách
        // 1. Include file cấu hình kết nối đến database, khởi tạo kết nối $conn
        include_once(__DIR__ . '/../../../dbconnect.php');
        /* --- 
        --- 2.Truy vấn dữ liệu truyện tranh
        --- 
        */
        $sql = <<<EOT
            SELECT hinhanh_id, hinhanh_ten, ch.chuong_ten
            FROM hinhanh ha
            JOIN chuong ch ON ch.chuong_id = ha.chuong_id
            ORDER BY hinhanh_id DESC
EOT;

        // Thực thi câu truy vấn SQL để lấy về dữ liệu ban đầu của record 
        $result = mysqli_query($conn, $sql);

        // Khi thực thi các truy vấn dạng SELECT, dữ liệu lấy về cần phải phân tích để sử dụng
        // Thông thường, chúng ta sẽ sử dụng vòng lặp while để duyệt danh sách các dòng dữ liệu được SELECT
        // Ta sẽ tạo 1 mảng array để chứa các dữ liệu được trả về
        $data = [];
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $data[] = array(
                'hinhanh_id' => $row['hinhanh_id'],
                'hinhanh_ten' => $row['hinhanh_ten'],
                'chuong_ten' => $row['chuong_ten'],
            );
        }
        /* --- End Truy vấn dữ liệu truyện tranh --- */
    ?>
    <div class="container-fluid">
        <div class="row">
            <!-- sidebar -->
            <?php include_once(__DIR__ . '/../../layouts/partials/sidebar.php'); ?>
            <!-- End sidebar -->
            <div class="col-md-8">
                <a href="create.php" class="btn btn-primary">Thêm mới</a>
                <table id="tblHinhanh" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Hình ảnh truyện</th>
                            <th>Tên chương</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($data as $item):?>
                                <tr>                       
                                    <td>
                                    <img src="/proj-truyentranh/assets/uploads/products/<?= $item['hinhanh_ten']; ?>" class="img-fluid" width="100px" />               
                                    </td>
                                    <td><?= $item['chuong_ten']; ?></td>
                                    <td>
                                        <a href="edit.php?hinhanh_id=<?= $item['hinhanh_id'] ?>" class="btn btn-warning">Sửa</a>
                                        <button class="btn btn-danger btnDelete" data-hinhanh_id="<?= $item['hinhanh_id'] ?>">Xóa</button>
                                    </td>
                                </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- End block content -->
</main>

    <!-- Nhúng file quản lý phần SCRIPT JAVASCRIPT -->
    <?php include_once(__DIR__ . '/../../layouts/scripts.php'); ?>

    <!-- Các file Javascript sử dụng riêng cho trang này, liên kết tại đây -->
    <!-- DataTable JS -->
    <script src="/proj-truyentranh/assets/vendor/DataTables/datatables.min.js"></script>
    <script src="/proj-truyentranh/assets/vendor/DataTables/Buttons-1.6.3/js/buttons.bootstrap4.min.js"></script>
    <script src="/proj-truyentranh/assets/vendor/DataTables/pdfmake-0.1.36/pdfmake.min.js"></script>
    <script src="/proj-truyentranh/assets/vendor/DataTables/pdfmake-0.1.36/vfs_fonts.js"></script>
    <!-- SweetAlert JS -->
    <script src="/proj-truyentranh/assets/vendor/sweetalert/sweetalert.min.js"></script>

    <script>
    $(document).ready(function() {
        // Xử lý DataTable
        $('#tblHinhanh').DataTable( {
            dom: 'Bfrtip',
            buttons: [
                'copy', 'excel', 'pdf'
            ]
        });
        $('.btnDelete').click(function() {
            swal({
                title: "Bạn có chắc muốn xóa?",
                text: "Khi xóa sẽ không thể phục hồi!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
                })
                .then((willDelete) => {
                    // debugger;
                if (willDelete) {
                    var hinhanh_id = $(this).data('hinhanh_id');
                    var url = "delete.php?hinhanh_id=" + hinhanh_id;
                    // Điều hướng sang trang xóa với REQUEST GET cùng tham số hinhanh_id=...
                    location.href = url;
                } else {
                    swal("Bạn hãy cẩn thận hơn!");
                }
                });
            })
        });
    </script>
</body>
</html>