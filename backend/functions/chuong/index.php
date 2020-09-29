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
            SELECT chuong_id, chuong_sochuong, chuong_ten, chuong_noidung, tr.truyen_ten
            FROM chuong ch
            JOIN truyen tr ON tr.truyen_id = ch.truyen_id
            ORDER BY chuong_id DESC
EOT;

        // Thực thi câu truy vấn SQL để lấy về dữ liệu ban đầu của record 
        $result = mysqli_query($conn, $sql);

        // Khi thực thi các truy vấn dạng SELECT, dữ liệu lấy về cần phải phân tích để sử dụng
        // Thông thường, chúng ta sẽ sử dụng vòng lặp while để duyệt danh sách các dòng dữ liệu được SELECT
        // Ta sẽ tạo 1 mảng array để chứa các dữ liệu được trả về
        $data = [];
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $data[] = array(
                'chuong_id' => $row['chuong_id'],
                'chuong_sochuong' => $row['chuong_sochuong'],
                'chuong_ten' => $row['chuong_ten'],
                'chuong_noidung' => $row['chuong_noidung'],
                'truyen_ten' => $row['truyen_ten'],
            );
        }
        /* --- End Truy vấn dữ liệu truyện tranh --- */
        // Test lấy dữ liệu
        // var_dump($data);die;
    ?>
    <div class="container-fluid">
        <div class="row">
            <!-- sidebar -->
            <?php include_once(__DIR__ . '/../../layouts/partials/sidebar.php'); ?>
            <!-- End sidebar -->
            <div class="col-md-8">
                <a href="create.php" class="btn btn-primary">Thêm mới</a>
                <table id="tblChuong" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Chương số</th>
                            <th>Tên chương</th>
                            <th>Nội dung</th>
                            <th>Tên truyện</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($data as $item):?>
                                <tr>
                                    <td><?= $item['chuong_sochuong']; ?></td>
                                    <td><?= $item['chuong_ten']; ?></td>
                                    <td><?= $item['chuong_noidung']; ?></td>
                                    <td><?= $item['truyen_ten']; ?></td>
                                    <td>
                                        <a href="edit.php?chuong_id=<?= $item['chuong_id'] ?>" class="btn btn-warning">Sửa</a>
                                        <button class="btn btn-danger btnDelete" data-chuong_id="<?= $item['chuong_id'] ?>">Xóa</button>
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
        $('#tblChuong').DataTable( {
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
                    var chuong_id = $(this).data('chuong_id');
                    var url = "delete.php?chuong_id=" + chuong_id;
                    // Điều hướng sang trang xóa với REQUEST GET cùng tham số chuong_id=...
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