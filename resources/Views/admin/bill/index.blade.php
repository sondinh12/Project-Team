@extends('admin.layouts.layout')

@section('title', 'Đơn hàng')

@section('content')

<div class="container my-4">
    <h3 class="mb-4 text-primary">Quản lý đơn hàng</h3>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>ID User</th>
                    <th>Tổng tiền</th>
                    <th>Trạng thái</th>
                    <th>Ngày tạo</th>
                    <th>Ngày cập nhật</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                    <tr>
                        <td>{{ $order['id_order'] }}</td>
                        <td>{{ $order['id_user'] }}</td>
                        <td>{{ number_format($order['total']) }}₫</td>
                        <td>
                            @if ($order['status'] == 'pending')
                                <span class="badge bg-warning text-dark">Chờ xử lý</span>
                            @elseif ($order['status'] == 'confirmed')
                                <span class="badge bg-info text-dark">Đã xác nhận</span>
                            @elseif ($order['status'] == 'delivering')
                                <span class="badge bg-primary">Đang giao</span>
                            @elseif ($order['status'] == 'completed')
                                <span class="badge bg-success">Hoàn tất</span>
                            @elseif ($order['status'] == 'cancelled')
                                <span class="badge bg-danger">Đã hủy</span>
                            @endif
                        </td>
                        
                        <td>{{ date('d/m/Y H:i', strtotime($order['created_at'])) }}</td>
                        <td>{{ date('d/m/Y H:i', strtotime($order['updated_at'])) }}</td>
                        <td>
                            @if($order['status'] !== 'cancelled')
                            <a href="{{ $_ENV['BASE_URL'] . 'admin/bill/edit/' . $order['id_order'] }}" class="btn btn-sm btn-primary mb-1">Tùy chỉnh</a>
                            <a href="{{ $_ENV['BASE_URL'] . 'admin/bill/detail/' . $order['id_order'] }}" class="btn btn-sm btn-info">Chi tiết</a>
                            @else
                            <span class="text-danger">Đã hủy đơn hàng</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<div class="d-flex justify-content-end mt-4 mx-1">
    <nav>
        <ul class="pagination">
            @for ($i = 1; $i <= $totalPages; $i++)
                <li class="page-item {{ $i == $currentPage ? 'active' : '' }}">
                    <a class="page-link" href="?page={{ $i }}">{{ $i }}</a>
                </li>
            @endfor
        </ul>
    </nav>
</div>


@endsection
