@extends('layouts.layout')



@section('title','Danh sách danh mục')

@section('content')
@php
    if(isset($_SESSION['message'])){
        echo $_SESSION['message'];
        unset($_SESSION['message']);
    }
@endphp
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Danh sách danh mục</h3>
        <a href="<?=$_ENV['BASE_URL'] . 'admin/categories/create'?>" class="btn btn-success">+ Thêm danh mục</a>
    </div>

    <div class="card shadow-lg p-3">
        <table class="table table-bordered table-hover text-center">
            <thead class="table-dark">
                <tr>
                    <th class="text-center">ID</th>
                    <th class="text-center">Tên danh mục</th>
                    <th class="text-center">Ngày tạo</th>
                    <th class="text-center">Ngày cập nhật</th>
                    <th class="text-center">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($categories as $cate)
                    <tr>
                        <td>{{$cate['id_category']}}</td>
                        <td>{{$cate['category_name']}}</td>
                        <td>{{$cate['created_at']}}</td>
                        <td>{{$cate['updated_at']}}</td>
                        <td>
                            {{-- <a href="#" class="btn btn-sm btn-primary">Chi tiết</a> --}}
                            <a href="<?=$_ENV['BASE_URL'] . 'admin/categories/update/' . $cate['id_category']?>" class="btn btn-sm btn-warning">Sửa</a>
                            <a href="<?=$_ENV['BASE_URL'] . 'admin/categories/destroy/' . $cate['id_category']?>" class="btn btn-sm btn-danger" onclick="return confirm('Bạn muốn xóa chứ?')">Xóa</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection