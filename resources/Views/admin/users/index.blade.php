@extends('admin.layouts.layout')



@section('title','Danh sách tài khoản')

@section('content')
<style>
    .custom-message {
        padding: 12px 20px;
        background-color: #e6ffed;
        color: #2d7a36;
        border: 1px solid #a1e1af;
        border-radius: 6px;
        font-size: 16px;
        margin-bottom: 20px;
        animation: fadeIn 0.3s ease;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }
    
    /* Optional: fade-in animation */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-5px); }
        to { opacity: 1; transform: translateY(0); }
    }
    </style>
@php
if (isset($_SESSION['message'])) {
    echo "<div class='custom-message'>" . $_SESSION['message'] . "</div>";
    unset($_SESSION['message']);
}
@endphp

<div class="container mt-4" >
    <div class="d-flex justify-content-between align-items-center mb-3" >
        <h3>Danh sách tài khoản</h3>
        <form action="<?= $_ENV['BASE_URL'] . 'admin/users' ?>" method="GET" class="mb-4 d-flex">
            <input type="text" name="username" value="{{ $keyword }}" class="form-control me-2" placeholder="Nhập tên đăng nhập.">
            <button type="submit" class="btn btn-primary">Tìm kiếm</button>
        </form>
        <a href="<?=$_ENV['BASE_URL'] . 'admin/users/create'?>" class="btn btn-success" style="margin-top:20px">+ Thêm tài khoản</a>
    </div>

    <div class="card shadow-lg p-3" style="margin-bottom:50px">
        <table class="table table-bordered table-hover text-center" id="userTable">
            <thead class="table-dark">
                <tr>
                    <th class="text-center">ID</th>
                    <th class="text-center">Tên người dùng</th>
                    <th class="text-center">Username</th>
                    <th class="text-center">Ảnh</th>
                    <th class="text-center">Email</th>
                    <th class="text-center">Phone</th>
                    <th class="text-center">Address</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Role</th>
                    <th class="text-center">Ngày tạo</th>
                    <th class="text-center">Ngày cập nhật</th>
                    <th class="text-center">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{$user['id_user']}}</td>
                        <td>{{$user['name']}}</td>
                        <td>{{$user['user_name']}}</td>
                        <td><img src="<?=$_ENV['BASE_URL']. $user['user_img'] ?>" alt="No Image" width="130"></td>
                        <td>{{$user['email']}}</td>
                        <td>{{$user['phone']}}</td>
                        <td>{{$user['address']}}</td>
                        <td>{{$user['status']}}</td>
                        <td>{{$user['role']}}</td>
                        <td>{{$user['created_at']}}</td>
                        <td>{{$user['updated_at']}}</td>
                        <td>
                            @if ($user['role'] !== 'admin')
                                @if ($user['status'] == 'active')
                                    <a href="<?= $_ENV['BASE_URL'] . 'admin/users/changestatus/' . $user['id_user'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Bạn muốn ban chứ?')">Ban</a>
                                @else 
                                    <a href="<?= $_ENV['BASE_URL'] . 'admin/users/changestatus/' . $user['id_user'] ?>" class="btn btn-sm btn-success" onclick="return confirm('Bạn muốn mở ban chứ?')">Mở</a>
                                @endif
                                <a href="<?= $_ENV['BASE_URL'] . 'admin/users/update/' . $user['id_user'] ?>" class="btn btn-sm btn-primary">Sửa</a>
                            @else
                                <a href="<?= $_ENV['BASE_URL'] . 'admin/users/changerole/' . $user['id_user'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Bạn muốn hạ cấp chứ?')">Hạ cấp</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
            
        </table>
        <ul class="pagination justify-content-center" id="pagination"></ul>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        let table = document.getElementById("userTable");
        let rows = table.getElementsByTagName("tbody")[0].getElementsByTagName("tr");
        let rowsPerPage = 5; // Số hàng mỗi trang
        let currentPage = 1;
        let totalPages = Math.ceil(rows.length / rowsPerPage);
        let pagination = document.getElementById("pagination");
    
        function showPage(page) {
            let start = (page - 1) * rowsPerPage;
            let end = start + rowsPerPage;
    
            for (let i = 0; i < rows.length; i++) {
                rows[i].style.display = (i >= start && i < end) ? "" : "none";
            }
        }
    
        function renderPagination() {
            pagination.innerHTML = "";
            
            if (currentPage > 1) {
                pagination.innerHTML += `<li class="page-item"><a class="page-link" href="#" onclick="changePage(${currentPage - 1})">«</a></li>`;
            }
    
            for (let i = 1; i <= totalPages; i++) {
                pagination.innerHTML += `<li class="page-item ${currentPage === i ? 'active' : ''}">
                    <a class="page-link" href="#" onclick="changePage(${i})">${i}</a>
                </li>`;
            }
    
            if (currentPage < totalPages) {
                pagination.innerHTML += `<li class="page-item"><a class="page-link" href="#" onclick="changePage(${currentPage + 1})">»</a></li>`;
            }
        }
    
        window.changePage = function (page) {
            currentPage = page;
            showPage(page);
            renderPagination();
        }
    
        showPage(currentPage);
        renderPagination();
    });
    </script>
    
@endsection