@extends('admin.layouts.layout')

@section('title','Thêm sản phẩm')

@section('content')
<div class="container mt-4">
    <div class="card shadow-lg p-4">
        <h3 class="text-center">Thêm sản phẩm</h3>
        <form action="<?=$_ENV['BASE_URL'] . 'admin/products/create'?>" enctype="multipart/form-data" method="post" class="needs-validation">
            <div class="mb-3">
                <label class="form-label">Tên sản phẩm</label>
                <input type="text" name="product_name" value="{{ isset($old['product_name']) ? $old['product_name'] : '' }}" class="form-control" placeholder="Nhập tên sản phẩm">
                @if (!empty($errors['product_name']))
                    @foreach ($errors['product_name'] as $message)
                        <div class="invalid-feedback">
                            <p class="text-danger">{{ $message }}</p>
                        </div>
                    @endforeach
                @endif
            </div>
            <div class="mb-3">
                <label class="form-label">Ảnh sản phẩm</label>
                <input type="file" name="product_img" class="form-control">
                @if (!empty($errors['product_name']))
                    @foreach ($errors['product_name'] as $message)
                        <div class="invalid-feedback">
                            <p class="text-danger">{{ $message }}</p>
                        </div>
                    @endforeach
                @endif
            </div>
            <div class="mb-3">
                <label class="form-label">Giá</label>
                <input type="text" name="price" value="{{ isset($old['price']) ? $old['price'] : '' }}" class="form-control" placeholder="Nhập giá danh mục">
                @if (!empty($errors['price']))
                    @foreach ($errors['price'] as $message)
                        <div class="invalid-feedback">
                            <p class="text-danger">{{ $message }}</p>
                        </div>
                    @endforeach
                @endif
            </div>
            <div class="mb-3">
                <label class="form-label">Số lượng</label>
                <input type="text" name="quantity" value="{{ isset($old['quantity']) ? $old['quantity'] : '' }}" class="form-control" placeholder="Nhập số lượng danh mục">
                @if (!empty($errors['quantity']))
                    @foreach ($errors['quantity'] as $message)
                        <div class="invalid-feedback">
                            <p class="text-danger">{{ $message }}</p>
                        </div>
                    @endforeach
                @endif
            </div>
            <div class="mb-3">
                <label class="form-label">Mô tả</label>
                <input type="text" name="description" value="{{ isset($old['description']) ? $old['description'] : '' }}" class="form-control" placeholder="Nhập mô tả danh mục">
            </div>
            <div class="mb-3">
                <label class="form-label">Tên danh mục</label>
                <select name="category_id" class="form-control">
                    <option value="">Chọn danh mục</option>
                    @foreach ($categories as $cate)
                        <option value="<?=$cate['id_category']?>"><?=$cate['category_name']?></option>
                    @endforeach
                </select>
                @if (!empty($errors['category_id']))
                    @foreach ($errors['category_id'] as $message)
                        <div class="invalid-feedback">
                            <p class="text-danger">{{ $message }}</p>
                        </div>
                    @endforeach
                @endif
            </div>
            <button type="submit" class="btn btn-primary w-100">Thêm</button>
        </form>
    </div>
</div>
@endsection