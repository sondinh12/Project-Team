@extends('admin.layouts.layout')

@section('title','Sửa trạng thái')

@section('content')
<div class="container mt-4">
    <h3 class="text-center text-primary mb-4">Cập nhật trạng thái đơn hàng</h3>

    <form action="{{ $_ENV['BASE_URL'] . 'admin/bill/edit/' . $oldStatus['id_order'] }}" method="post" class="mx-auto" style="max-width: 600px;">
        <div class="mb-3">
            <label for="status" class="form-label fw-bold">Trạng thái đơn hàng</label>
            <select class="form-select" name="status" id="statusChange" onchange="handleStatusChange()">
                <option value="pending" {{ $oldStatus['status'] == "pending" ? 'selected' : '' }}>Chờ xử lý</option>
                <option value="confirmed" {{ $oldStatus['status'] == "confirmed" ? 'selected' : '' }}>Đã xác nhận</option>
                <option value="delivering" {{ $oldStatus['status'] == "delivering" ? 'selected' : '' }}>Đang giao</option>
                <option value="completed" {{ $oldStatus['status'] == "completed" ? 'selected' : '' }}>Hoàn tất</option>
                <option value="cancelled" {{ $oldStatus['status'] == "cancelled" ? 'selected' : '' }}>Đã hủy</option>
            </select>
            @if (!empty($_SESSION['errors']['status']))
                <div class="text-danger mt-1">{{ $_SESSION['errors']['status'] }}</div>
            @endif
        </div>

        <input type="hidden" name="id_order" value="{{ $oldStatus['id_order'] }}">

        <div class="text-center">
            <button type="submit" class="btn btn-success">Cập nhật</button>
            <a href="{{ $_ENV['BASE_URL'] }}admin/bill" class="btn btn-secondary ms-2">Quay lại</a>
        </div>
    </form>
</div>
@endsection

@section('script')
<script>
    function handleStatusChange() {
        const statusOrder = ['pending', 'confirmed', 'delivering', 'completed', 'cancelled'];

        const previousStatus = "{{ $oldStatus['status'] }}";
        const statusSelect = document.getElementById("statusChange");
        const selectedStatus = statusSelect.value;

        const prevIndex = statusOrder.indexOf(previousStatus);
        const selectedIndex = statusOrder.indexOf(selectedStatus);

        // Nếu chọn sai (lùi trạng thái)
        if (selectedIndex < prevIndex && selectedStatus !== 'cancelled') {
            alert("Bạn không thể quay về trạng thái trước đó.");
            statusSelect.value = previousStatus;
        }

        // Nếu chọn 'cancelled', xác nhận
        if (selectedStatus === 'cancelled') {
            const confirmCancel = confirm("Bạn có chắc chắn muốn hủy đơn hàng?");
            if (!confirmCancel) {
                statusSelect.value = previousStatus;
            }
        }
    }
</script>
@endsection

