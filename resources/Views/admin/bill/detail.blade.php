@extends('admin.layouts.layout')

@section('title', 'Chi tiết đơn hàng')

@section('content')
<div class="container my-4">
    <h3 class="mb-4 text-primary">Chi tiết đơn hàng</h3>

    @foreach ($details as $detail)
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-dark text-white">
                Mã đơn hàng: #{{ $detail['order_id'] }} | Mã chi tiết: #{{ $detail['id_order_detail'] }}
            </div>
            <div class="card-body row">
                <div class="col-md-3 text-center">
                    <img src="{{ $_ENV['BASE_URL'] . $detail['product_img'] }}" class="img-fluid rounded" alt="Ảnh sản phẩm" style="max-width: 150px;">
                </div>
                <div class="col-md-9">
                    <h5 class="card-title">{{ $detail['product_name'] }}</h5>
                    <p><strong>Số lượng:</strong> {{ $detail['quantity'] }}</p>
                    <p><strong>Đơn giá:</strong> {{ number_format($detail['price']) }}₫</p>
                    <p><strong>Tổng tiền:</strong> <span class="text-danger">{{ number_format($detail['total']) }}₫</span></p>
                    <p><strong>Phương thức thanh toán:</strong> {{ ucfirst($detail['payment']) }}</p>
                </div>
            </div>
        </div>
    @endforeach

    <a href="{{ $_ENV['BASE_URL'] }}admin/bill" class="btn btn-secondary">Quay lại danh sách</a>
</div>
@endsection
    