@extends('layouts.layout')

@section('title','Thêm danh mục')

@section('content')
<h3>Thêm danh mục</h3>

<form action="<?=$_ENV['BASE_URL'] . 'admin/categories/create'?>" enctype="multipart/form-data" method="post">
    <label>Tên danh mục</label>
    <input type="text" name="category_name">
    <button type="submit">Thêm</button>
</form>
@endsection