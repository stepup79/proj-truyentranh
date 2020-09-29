<nav class="col-md-2 d-none d-md-block sidebar">
  <div class="sidebar-sticky">
    <ul class="nav flex-column">
      <!-- #################### Menu các trang Quản lý #################### -->
      <li class="nav-item sidebar-heading"><span>Quản lý</span></li>
      <li class="nav-item">
        <a href="/backend/pages/dashboard.php">Bảng tin <span class="sr-only">(current)</span></a>
      </li>
      <hr style="border: 1px solid red; width: 80%;" />
      <!-- #################### End Menu các trang Quản lý #################### -->
      <!-- #################### Menu chức năng Danh mục #################### -->
      <li class="nav-item sidebar-heading">
        <span>Danh mục</span>
      </li>
      <!-- Menu Danh mục truyện tranh -->
      <li class="nav-item">
        <a href="#truyenSubMenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
          Danh mục truyện tranh
        </a>
        <ul class="collapse" id="truyenSubMenu">
          <li class="nav-item">
            <a href="/proj-truyentranh/backend/functions/truyen/index.php">Danh sách</a>
          </li>
          <li class="nav-item">
            <a href="/proj-truyentranh/backend/functions/truyen/create.php">Thêm mới</a>
          </li>
        </ul>
      </li>
      <!-- End Menu Danh mục truyện tranh -->
      <!-- Menu Danh mục Chương -->
      <li class="nav-item">
        <a href="#chuongSubMenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
          Danh mục chương
        </a>
        <ul class="collapse" id="chuongSubMenu">
          <li class="nav-item">
            <a href="/proj-truyentranh/backend/functions/chuong/index.php">Danh sách</a>
          </li>
          <li class="nav-item">
            <a href="/proj-truyentranh/backend/functions/chuong/create.php">Thêm mới</a>
          </li>
        </ul>
      </li>
      <!-- End Menu Danh mục Chương -->
      <!-- Menu Hình ảnh truyện tranh -->
      <li class="nav-item">
        <a href="#hinhanhSubMenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
          Danh mục hình ảnh
        </a>
        <ul class="collapse" id="hinhanhSubMenu">
          <li class="nav-item">
            <a href="/proj-truyentranh/backend/functions/hinhanh/index.php">Danh sách</a>
          </li>
          <li class="nav-item">
            <a href="/proj-truyentranh/backend/functions/hinhanh/create.php">Thêm mới</a>
          </li>
        </ul>
      </li>
      <!-- End Hình ảnh truyện tranh -->
      <!-- #################### End Menu chức năng Danh mục #################### -->
    </ul>
  </div>
</nav>