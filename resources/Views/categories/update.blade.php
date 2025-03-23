@extends('layouts.layout')

@section('title','Sửa danh mục')

@section('content')
<h3>Sửa danh mục</h3>

<form action="<?=$_ENV['BASE_URL'] . 'admin/categories/update/' . $cateOld['id_category']?>" enctype="multipart/form-data" method="post">
    <label>Tên danh mục</label>
    <input type="text" name="category_name" value="<?=$cateOld['category_name']?>">
    <button type="submit">Sửa</button>
</form>
@endsection