@extends('admin.layouts.layout')



@section('title','Danh sách sản phẩm')

@section('content')
@php
    if(isset($_SESSION['message'])){
        echo $_SESSION['message'];
        unset($_SESSION['message']);
    }
@endphp
<form action="<?= $_ENV['BASE_URL'] . 'admin/products' ?>" method="GET" class="mb-4 d-flex">
    <input type="text" name="pro_name" value="{{ $keyword }}" class="form-control me-2" placeholder="Nhập tên sản phẩm...">
    <button type="submit" class="btn btn-primary">Tìm kiếm</button>
</form>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Danh sách danh mục</h3>
        <a href="<?=$_ENV['BASE_URL'] . 'admin/products/create'?>" class="btn btn-success">+ Thêm sản phẩm</a>
    </div>

    <div class="card shadow-lg p-3">
        <table class="table table-bordered table-hover text-center">
            <thead class="table-dark">
                <tr>
                    <th class="text-center">ID</th>
                    <th class="text-center">Tên sản phẩm</th>
                    <th class="text-center">ảnh sản phẩm</th>
                    <th class="text-center">Giá sản phẩm</th>
                    <th class="text-center">Số lượng</th>
                    <th class="text-center">Mô tả</th>
                    <th class="text-center">Tên danh mục</th>
                    <th class="text-center">Trạng thái</th>
                    <th class="text-center">Ngày tạo</th>
                    <th class="text-center">Ngày cập nhật</th>
                    <th class="text-center">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @if(!empty($products) && count($products) > 0)
                    @foreach ($products as $pro)
                        <tr>
                            <td>{{$pro['id_product']}}</td> 
                            <td>{{$pro['product_name']}}</td>
                            <td>
                                <img src="<?=$_ENV['BASE_URL'] . $pro['product_img']?>" alt="Ảnh sản phẩm" width="120px">
                            </td>
                            <td>{{$pro['price']}}</td>
                            <td>{{$pro['quantity']}}</td>
                            <td>{{$pro['description']}}</td>
                            <td>{{$pro['cate_name']}}</td>
                            <td>{{$pro['status']}}</td>
                            <td>{{$pro['created_at']}}</td>
                            <td>{{$pro['updated_at']}}</td>
                            <td>
                                <a href="<?=$_ENV['BASE_URL'] . 'admin/products/show/' . $pro['id_product']?>" class="btn btn-sm btn-primary">Chi tiết</a>
                                <a href="<?=$_ENV['BASE_URL'] . 'admin/products/update/' . $pro['id_product']?>" class="btn btn-sm btn-warning">Sửa</a>
                                <a href="<?=$_ENV['BASE_URL'] . 'admin/products/destroy/' . $pro['id_product']?>" class="btn btn-sm btn-danger" onclick="return confirm('Bạn muốn xóa chứ?')">Xóa</a>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="9" class="text-center text-danger">Không tìm thấy sản phẩm nào.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection