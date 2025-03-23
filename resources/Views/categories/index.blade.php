@extends('layouts.layout')

@section('title','Danh sách danh mục')

@section('content')
<h3>Danh sách danh mục</h3>
<a href="<?=$_ENV['BASE_URL'] . 'admin/categories/create'?>" class="btn btn-primary">Thêm</a>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Created at</th>
                <th>Updated at</th>
                <th>Action</th>
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
                        <a href="" class="btn btn-primary">Chi tiết</a>
                        <a href="<?=$_ENV['BASE_URL'] . 'admin/categories/update/' . $cate['id_category']?>" class="btn btn-info">Sửa</a>
                        <a href="<?=$_ENV['BASE_URL'] . 'admin/categories/destroy/' . $cate['id_category']?>"><button class="btn btn-danger" onclick="return confirm('Bạn muốn xóa chứ?')">Xóa</button></a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection