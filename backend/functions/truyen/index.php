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
            SELECT truyen_id, truyen_ma, truyen_ten, truyen_hinhdaidien, truyen_loai, truyen_theloai, truyen_tacgia, truyen_motangan, truyen_ngaydang 
            FROM truyen
            ORDER BY truyen_id DESC
EOT;

        // Thực thi câu truy vấn SQL để lấy về dữ liệu ban đầu của record 
        $result = mysqli_query($conn, $sql);

        // Khi thực thi các truy vấn dạng SELECT, dữ liệu lấy về cần phải phân tích để sử dụng
        // Thông thường, chúng ta sẽ sử dụng vòng lặp while để duyệt danh sách các dòng dữ liệu được SELECT
        // Ta sẽ tạo 1 mảng array để chứa các dữ liệu được trả về

        $data = [];
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $data[] = array(
                'truyen_id' => $row['truyen_id'],
                'truyen_ma' => $row['truyen_ma'],
                'truyen_ten' => $row['truyen_ten'],
                'truyen_hinhdaidien' => $row['truyen_hinhdaidien'],
                'truyen_loai' => $row['truyen_loai'],
                'truyen_theloai' => $row['truyen_theloai'],
                'truyen_tacgia' => $row['truyen_tacgia'],
                'truyen_motangan' => $row['truyen_motangan'],
                'truyen_ngaydang' => date('d/m/Y', strtotime($row['truyen_ngaydang'])),
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
            <table id="tblTruyen" class="table table-bordered">
                <thead>
                    <tr>
                        <th>Mã truyện</th>
                        <th>Tên truyện</th>
                        <th>Hình đại diện</th>
                        <th>Loại truyện</th>
                        <th>Thể loại truyện</th>
                        <th>Tác giả</th>
                        <th>Mô tả</th>
                        <th>Ngày đăng</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($data as $item):?>
                            <tr>
                                <td><?= $item['truyen_ma']; ?></td>
                                <td><?= $item['truyen_ten']; ?></td>
                                <td>
                                <img src="/proj-truyentranh/assets/uploads/products/<?= $item['truyen_hinhdaidien']; ?>" class="img-fluid" width="100px" />               
                                </td>
                                <td><?= $item['truyen_loai']; ?></td>
                                <td><?= $item['truyen_theloai']; ?></td>
                                <td><?= $item['truyen_tacgia']; ?></td>
                                <td><?= $item['truyen_motangan']; ?></td>
                                <td><?= $item['truyen_ngaydang']; ?></td>
                                <td>
                                    <a href="edit.php?truyen_id=<?= $item['truyen_id'] ?>" class="btn btn-warning">Sửa</a>
                                    <button class="btn btn-danger btnDelete" data-truyen_id="<?= $item['truyen_id'] ?>">Xóa</button>
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
        $('#tblTruyen').DataTable( {
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
                    var truyen_id = $(this).data('truyen_id');
                    var url = "delete.php?truyen_id=" + truyen_id;
                    // Điều hướng sang trang xóa với REQUEST GET cùng tham số truyen_id=...
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