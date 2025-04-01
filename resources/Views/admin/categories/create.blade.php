@extends('admin.layouts.layout')

@section('title','Thêm danh mục')

@section('content')
<div class="container mt-4">
    <div class="card shadow-lg p-4">
        <h3 class="text-center">Thêm danh mục</h3>
        <form action="<?=$_ENV['BASE_URL'] . 'admin/categories/create'?>" enctype="multipart/form-data" method="post" class="needs-validation">
            <div class="mb-3">
                <label class="form-label">Tên danh mục</label>
                <input type="text" name="category_name" value="{{ isset($old['category_name']) ? $old['category_name'] : '' }}" class="form-control" placeholder="Nhập tên danh mục">
                @if (!empty($errors['category_name']))
                    @foreach ($errors['category_name'] as $message)
                        <div class="invalid-feedback">
                            <p class="text-danger">{{ $message }}</p>
                        </div>
                    @endforeach
                @endif
                {{-- {{var_dump($errors)}} --}}
            </div>
            <button type="submit" class="btn btn-primary w-100">Thêm</button>
        </form>
    </div>
</div>
@endsection