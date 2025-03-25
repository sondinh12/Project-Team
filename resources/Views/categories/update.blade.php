@extends('layouts.layout')



@section('title','Sửa danh mục')

@section('content')
<h3 class="text-center">Sửa danh mục</h3>
<div class="container mt-4">
    <div class="card shadow-lg p-4">
        <form action="<?=$_ENV['BASE_URL'] . 'admin/categories/update/' . $cateOld['id_category']?>" enctype="multipart/form-data" method="post" class="needs-validation">
            <div class="mb-3">
                <label class="form-label">Tên danh mục</label>
                <input type="text" name="category_name" value="<?=$cateOld['category_name']?>" class="form-control" placeholder="Nhập tên danh mục">
                @if (!empty($errors['category_name']))
                    @foreach ($errors['category_name'] as $message)
                        <div class="invalid-feedback">
                            <p class="text-danger">{{ $message }}</p>
                        </div>
                    @endforeach
                @endif
            </div>
            <button type="submit" class="btn btn-primary w-100">Sửa</button>
        </form>
    </div>
</div>
@endsection