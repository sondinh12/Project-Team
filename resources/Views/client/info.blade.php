{{-- filepath: c:\laragon\www\PMA1011_Agile\resources\Views\client\info.blade.php --}}
@extends('client.layouts.layout')

@section('title', 'Thông tin tài khoản')

@section('content')
<style>
    .status-pending {
    color: #f59e0b; /* vàng cam */
    font-weight: bold;
}

.status-confirmed {
    color: #3b82f6; /* xanh dương */
    font-weight: bold;
}

.status-delivering {
    color: #8b5cf6; /* tím */
    font-weight: bold;
}

.status-completed {
    color: #10b981; /* xanh lá */
    font-weight: bold;
}

.status-cancelled {
    color: #ef4444; /* đỏ */
    font-weight: bold;
}

    /* Định dạng chung cho modal */
    .modal {
        display: none; /* Ẩn modal mặc định */
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.6); /* Màu nền mờ */
        animation: fadeIn 0.3s ease-in-out; /* Hiệu ứng mở modal */
    }

    /* Hiệu ứng mở modal */
    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }

    /* Nội dung modal */
    .modal-content {
        background-color: #fff;
        margin: 10% auto;
        padding: 20px;
        border-radius: 10px;
        width: 40%;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        animation: slideIn 0.3s ease-in-out; /* Hiệu ứng trượt vào */
    }

    /* Hiệu ứng trượt vào modal */
    @keyframes slideIn {
        from {
            transform: translateY(-50px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    /* Nút đóng modal */
    .close {
        color: #333;
        float: right;
        font-size: 24px;
        font-weight: bold;
        cursor: pointer;
    }

    .close:hover,
    .close:focus {
        color: #ff0000;
        text-decoration: none;
    }

    /* Tiêu đề modal */
    .modal h3 {
        margin-bottom: 20px;
        font-size: 1.5rem;
        color: #333;
        text-align: center;
    }

    /* Nút trong modal */
    .modal-footer {
        text-align: right;
        margin-top: 20px;
    }

    .modal-footer .btn {
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 1rem;
    }

    .modal-footer .btn-secondary {
        background-color: #6c757d;
        color: #fff;
    }

    .modal-footer .btn-secondary:hover {
        background-color: #5a6268;
    }

    .modal-footer .btn-primary {
        background-color: #007bff;
        color: #fff;
    }

    .modal-footer .btn-primary:hover {
        background-color: #0056b3;
    }

    /* Định dạng input và textarea */
    .form-control {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 1rem;
    }

    .form-control:focus {
        border-color: #007bff;
        outline: none;
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
    }

    /* Định dạng ảnh đại diện */
    .modal-content img {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 50%;
        border: 2px solid #007bff;
        margin-bottom: 15px;
    }
    .modal-content {
    position: relative;
    top: 50%;
    transform: translateY(-50%);
    margin: 0 auto;
}

    /* Định dạng nhãn */
    .form-label {
        font-weight: bold;
        margin-bottom: 5px;
        display: block;
        color: #333;
    }
</style>
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Thông tin tài khoản</h4>
                </div>
                <div class="card-body">
                    {{-- Hiển thị ảnh đại diện --}}
                    <div class="text-center mb-4">
                        <img src="<?=$_ENV['BASE_URL']. $user['user_img'] ?>" 
                             alt="Ảnh đại diện" 
                             class="rounded-circle" 
                             style="width: 120px; height: 120px; object-fit: cover;">
                    </div>

                    {{-- Hiển thị thông tin người dùng --}}
                    <p><strong>Họ và tên:</strong> {{ $user['name'] }}</p>
                    <p><strong>Email:</strong> {{ $user['email'] }}</p>
                    <p><strong>Số điện thoại:</strong> {{ $user['phone'] }}</p>
                    <p><strong>Địa chỉ:</strong> {{ $user['address'] }}</p>
                    <p><strong>Vai trò:</strong> 
                        @if ($user['role'] === 'admin')
                            Quản trị viên
                        @else
                            Người dùng
                        @endif
                    </p>
                    <p><strong>Ngày tạo tài khoản:</strong> {{ $user['created_at'] }}</p>
                </div>
                <div class="card-footer text-end">
                    {{-- Nút sửa thông tin --}}
                    <button type="button" id="openModal" class="btn btn-warning me-2">Sửa thông tin</button>

                    {{-- Nút đăng xuất --}}
                    <a href="<?=$_ENV['BASE_URL'] . 'logout'?>" class="btn btn-danger">Đăng xuất</a>
                </div>
            </div>
        </div>
    </div>

    {{-- Lịch sử mua hàng --}}
    <div class="row justify-content-center mt-5">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header bg-secondary text-white">
                    <h4 class="mb-0">Lịch sử mua hàng</h4>
                </div>
                <div class="card-body">
                    @if (!empty($orders) && count($orders) > 0)
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Mã đơn hàng</th>
                                    <th>Ngày mua</th>
                                    <th>Tổng tiền</th>
                                    <th>Trạng thái</th>
                                    <th>Chi tiết</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $index => $order)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $order['id_order'] }}</td>
                                        <td>{{ $order['created_at'] }}</td>
                                        {{-- <td>{{ $order['order_date'] }}</td> --}}
                                        <td>{{ number_format($order['total']) }}₫</td>
                                        <td>
                                            @if ($order['status'] === 'pending')
                                            <span class="badge bg-warning text-dark">Chờ xử lý</span>
                                        @elseif ($order['status'] === 'confirmed')
                                            <span class="badge bg-primary">Đã xác nhận</span>
                                        @elseif ($order['status'] === 'delivering')
                                            <span class="badge bg-info text-dark">Đang giao hàng</span>
                                        @elseif ($order['status'] === 'completed')
                                            <span class="badge bg-success">Hoàn thành</span>
                                        @elseif ($order['status'] === 'cancelled')
                                            <span class="badge bg-danger">Đã hủy</span>
                                        @else
                                            <span class="badge bg-secondary">Không xác định</span>
                                        @endif
                                        </td>
                                        <td>
                                            {{-- <a href="{{ $_ENV['BASE_URL'] . 'info/'. $user['id_user']. '?id_order=' . $order['id_order'] }}" class="btn btn-sm btn-primary">Xem</a> --}}
                                            <form action="" method="POST">
                                                <input type="hidden" name="id_order" value="{{ $order['id_order'] }}">
                                                <button type="submit_view" class="btn btn-sm btn-primary" name="btn_view">Xem</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-center text-muted">Bạn chưa có đơn hàng nào.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
{{-- Popup chỉnh sửa tài khoản --}}
<div id="editAccountModal" class="modal">
    <div class="modal-content">
        <span class="close" id="closeModal">&times;</span>
        <form action="<?=$_ENV['BASE_URL'] . 'info/' .$user['id_user']?>" method="POST" enctype="multipart/form-data">
            <h3>Chỉnh sửa tài khoản</h3>
            {{-- Ảnh đại diện --}}
            <div class="mb-3 text-center">
                <label for="user_img" class="form-label">Ảnh đại diện</label>
                <div>
                    <img src="<?=$_ENV['BASE_URL']. $user['user_img'] ?>" 
                         alt="Ảnh đại diện" 
                         class="rounded-circle mb-3" 
                         style="width: 100px; height: 100px; object-fit: cover;">
                </div>
                <input type="file" class="form-control" id="user_img" name="user_img" accept="image/*" style="padding: 3px">
            </div>

            {{-- Số điện thoại --}}
            <div class="mb-3">
                <label for="phone" class="form-label">Số điện thoại</label>
                <input type="text" class="form-control" id="phone" name="phone" value="{{ $user['phone'] }}" required>
            </div>

            {{-- Địa chỉ --}}
            <div class="mb-3">
                <label for="address" class="form-label">Địa chỉ</label>
                <textarea class="form-control" id="address" name="address" rows="3" required>{{ $user['address'] }}</textarea>
            </div>

            {{-- Mật khẩu --}}
            <div class="mb-3">
                <label for="password" class="form-label">Mật khẩu mới</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Để trống nếu không muốn thay đổi">
            </div>
                        {{-- Xác nhận mật khẩu --}}
                        {{-- <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Xác nhận mật khẩu</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Nhập lại mật khẩu mới">
                        </div> --}}
            
                        <div class="modal-footer">
                            <button type="button" id="cancelButton" class="btn btn-secondary">Hủy</button>
                            <button type="submit" class="btn btn-primary" name="btn_changeinfo">Lưu thay đổi</button>
                        </div>
                    </form>
                </div>
            </div>
            @if (!empty($orderDetail))
<div id="orderDetailModal" class="modal" style="display:block;">
    <div class="modal-content" style="max-width: 600px;">
        <span class="close" onclick="document.getElementById('orderDetailModal').style.display='none'">&times;</span>
        <h3>Chi tiết đơn hàng</h3>
        <p><strong>Mã đơn:</strong> {{ $orderDetail['id_order'] }}</p>
        <p><strong>Ngày mua:</strong> {{ $orderDetail['created_at'] }}</p>
        {{-- <p><strong>Tổng tiền:</strong> {{ number_format($orderDetail['total']) }}₫</p> --}}
        <p><strong>Trạng thái:</strong>
            @if ($order['status'] === 'pending')
        <span class="badge bg-warning text-dark">Chờ xử lý</span>
    @elseif ($order['status'] === 'confirmed')
        <span class="badge bg-primary">Đã xác nhận</span>
    @elseif ($order['status'] === 'delivering')
        <span class="badge bg-info text-dark">Đang giao hàng</span>
    @elseif ($order['status'] === 'completed')
        <span class="badge bg-success">Hoàn thành</span>
    @elseif ($order['status'] === 'cancelled')
        <span class="badge bg-danger">Đã hủy</span>
    @else
        <span class="badge bg-secondary">Không xác định</span>
    @endif
        </p>
        
        <hr>
        <h5>Sản phẩm:</h5>
        <ul>
            @foreach ($orderItems as $item)
                <li>{{ $item['product_name'] }} - SL: {{ $item['quantity'] }} - {{ number_format($item['product_price']) }}₫ - Tổng: {{ number_format($item['quantity']*$item['product_price']) }}₫</li>
            @endforeach
        </ul>
        <p><strong>Tổng tiền:</strong> {{ number_format($orderDetail['total']) }}₫</strong></p>
        @if ($orderDetail['status'] === 'pending')
            <form action="<?=$_ENV['BASE_URL'] . 'info/' .$user['id_user']?>" method="POST">
                <input type="hidden" name="id_order" value="{{ $orderDetail['id_order'] }}">
                <button type="submit" class="btn btn-danger" name="btn_cancel">Hủy đơn hàng</button>
            </form>
        @endif
    </div>
</div>
@endif
    @if(isset($_SESSION['success']))
        <div id="custom-popup" style="
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 20px rgba(0,0,0,0.3);
            z-index: 99999;
            text-align: center;
            animation: popup-fadein 0.3s ease;
        ">
            <div class="checkmark-circle">
                <div class="background"></div>
                <div class="checkmark draw"></div>
            </div>
            <p id="popup-message" style="margin-top: 15px; font-weight: bold;">
                {{ $_SESSION['success'] }}
            </p>
        </div>
        @unset($_SESSION['success'])
    @endif
<script>
     document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('editAccountModal');
        const openModalButton = document.getElementById('openModal');
        const closeModalButton = document.getElementById('closeModal');
        const cancelButton = document.getElementById('cancelButton');

        openModalButton.addEventListener('click', function () {
            modal.style.display = 'block';
        });

        closeModalButton.addEventListener('click', function () {
            modal.style.display = 'none';
        });

        cancelButton.addEventListener('click', function () {
            modal.style.display = 'none';
        });

        window.addEventListener('click', function (event) {
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        });
    });
    // Tự động ẩn popup sau 3s
    setTimeout(() => {
            const popup = document.getElementById('custom-popup');
            if (popup) popup.style.display = 'none';
        }, 3000);
</script>
@endsection

