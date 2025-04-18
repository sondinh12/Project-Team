@extends('admin.layouts.layout')

@section('title','Thống kê')

@section('content')
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Thống kê doanh thu</h4>
        </div>                                  
        <div style="margin-left: 16px">
            <form action="<?=$_ENV['BASE_URL'] . 'admin/statistic'?>" method="post" class="mb-4">
                <label for="start_date">Từ ngày:</label>
                <input type="date" name="start_date" id="start_date" class="form-control w-25 mb-2">

                <label for="end_date">Đến ngày:</label>
                <input type="date" name="end_date" id="end_date" class="form-control w-25 mb-2">

                <button type="submit" class="btn btn-primary" name="btn_statistics">Xem thống kê</button>
            </form>
            @if (isset($data['total']))
            <h3>Kết quả thống kê từ <?= htmlspecialchars($_POST['start_date']) ?> đến <?= htmlspecialchars($_POST['end_date']) ?>:</h3>
                <p><strong>Doanh thu:</strong><?= htmlspecialchars(number_format($data['total'], 2)) ?> VNĐ</p>
                <p><strong>Tổng số đơn hàng:</strong> <?= htmlspecialchars($data['total_orders']) ?></p>
                <p><strong>Số đơn hàng bị hủy <?= htmlspecialchars($data['canceled_orders']) ?></strong></p>
            @elseif($_SERVER['REQUEST_METHOD'] === 'POST')
            <p><strong>Hãy chọn ngày để xem thống kê.</strong></p>
            @endif
        </div>
    </div>
@endsection