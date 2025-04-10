@extends('admin.layouts.layout')

@section('title','Chi tiết sản phẩm')

@section('content')
<div class="container mt-4">
    <div class="card shadow-lg p-4">
        <h3 class="text-center mb-4">Chi tiết sản phẩm</h3>
        <div class="row">
            <!-- Cột trái: Thông tin sản phẩm -->
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr>
                        <th>Tên sản phẩm:</th>
                        <td>{{ $detailPro['product_name'] }}</td>
                    </tr>
                    <tr>
                        <th>Giá sản phẩm:</th>
                        <td>{{ number_format($detailPro['price'], 0, ',', '.') }} VNĐ</td>
                    </tr>
                    <tr>
                        <th>Số lượng:</th>
                        <td>{{ $detailPro['quantity'] }}</td>
                    </tr>
                    <tr>
                        <th>Mô tả:</th>
                        <td>{{ $detailPro['description'] }}</td>
                    </tr>
                    <tr>
                        <th>Danh mục:</th>
                        <td>{{ $detailPro['cate_name'] }}</td>
                    </tr>
                    <tr>
                        <th>Trạng thái:</th>
                        <td>
                            <span class="badge {{ $detailPro['status'] == 'active' ? 'bg-success' : 'bg-danger' }}">
                                {{ $detailPro['status'] == 'active' ? 'Hoạt động' : 'Tạm dừng' }}
                            </span>
                        </td>
                    </tr>
                </table>
            </div>

            <!-- Cột phải: Ảnh sản phẩm -->
            <div class="col-md-6 text-center">
                <h5>Ảnh sản phẩm:</h5>
                <img src="{{ $_ENV['BASE_URL'] . $detailPro['product_img'] }}" 
                     class="img-fluid rounded shadow-sm" 
                     alt="Ảnh sản phẩm" 
                     style="max-width: 100%; height: auto;">
            </div>
        </div>

        <!-- Ngày tạo & cập nhật -->
        <div class="mt-4">
            <table class="table">
                <tr>
                    <th>Ngày tạo:</th>
                    <td>{{ $detailPro['created_at'] }}</td>
                </tr>
                <tr>
                    <th>Ngày cập nhật:</th>
                    <td>{{ $detailPro['updated_at'] }}</td>
                </tr>
            </table>
        </div>

        <!-- Nút quay lại -->
        <div class="text-center mt-3">
            <a href="{{ $_ENV['BASE_URL'] . 'admin/products' }}" class="btn btn-primary">
                 Quay lại
            </a>
        </div>
    </div>
</div>
@endsection
