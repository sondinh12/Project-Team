@extends('admin.layouts.layout')

@section('title','Thêm tài khoản')

@section('content')
    <div class="card shadow-lg p-4" style="margin-bottom:50px" >
        <h3 class="text-center">Thêm tài khoản</h3>
        <form action="<?= $_ENV['BASE_URL'] . 'admin/users/create' ?>" enctype="multipart/form-data" method="post" class="needs-validation">

            <div class="mb-3">
                <label class="form-label">Họ và Tên</label>
                <input type="text" name="name" class="form-control" value="<?= $_SESSION['old']['name'] ?? '' ?>" placeholder="Nhập họ và tên">
                <?php if (!empty($errors['name'])): ?>
                    <p class="text-danger"><?= implode(', ', $errors['name']) ?></p>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label class="form-label">Tên đăng nhập</label>
                <input type="text" name="user_name" class="form-control" value="<?= $_SESSION['old']['user_name'] ?? '' ?>" placeholder="Nhập tên đăng nhập">
                <?php if (!empty($errors['user_name'])): ?>
                    <p class="text-danger"><?= implode(', ', $errors['user_name']) ?></p>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label class="form-label">Ảnh đại diện</label>
                <input type="file" name="user_img" class="form-control">
                <?php if (!empty($errors['user_img'])): ?>
                    <p class="text-danger"><?= implode(', ', $errors['user_img']) ?></p>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="<?= $_SESSION['old']['email'] ?? '' ?>" placeholder="Nhập email">
                <?php if (!empty($errors['email'])): ?>
                    <p class="text-danger"><?= implode(', ', $errors['email']) ?></p>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label class="form-label">Số điện thoại</label>
                <input type="text" name="phone" class="form-control" value="<?= $_SESSION['old']['phone'] ?? '' ?>" placeholder="Nhập số điện thoại">
                <?php if (!empty($errors['phone'])): ?>
                    <p class="text-danger"><?= implode(', ', $errors['phone']) ?></p>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label class="form-label">Địa chỉ</label>
                <input type="text" name="address" class="form-control" value="<?= $_SESSION['old']['address'] ?? '' ?>" placeholder="Nhập địa chỉ">
                <?php if (!empty($errors['address'])): ?>
                    <p class="text-danger"><?= implode(', ', $errors['address']) ?></p>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label class="form-label">Mật khẩu</label>
                <input type="password" name="password" class="form-control" placeholder="Nhập mật khẩu">
                <?php if (!empty($errors['password'])): ?>
                    <p class="text-danger"><?= implode(', ', $errors['password']) ?></p>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label class="form-label">Trạng thái</label>
                <select name="status" class="form-control">
                    <option value="active" <?= ($_SESSION['old']['status'] ?? '') == 'active' ? 'selected' : '' ?>>Active</option>
                    <option value="inactive" <?= ($_SESSION['old']['status'] ?? '') == 'inactive' ? 'selected' : '' ?>>Inactive</option>
                </select>
                <?php if (!empty($errors['status'])): ?>
                    <p class="text-danger"><?= implode(', ', $errors['status']) ?></p>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label class="form-label">Quyền</label>
                <select name="role" class="form-control">
                    <option value="user" <?= ($_SESSION['old']['role'] ?? '') == 'user' ? 'selected' : '' ?>>User</option>
                    <option value="admin" <?= ($_SESSION['old']['role'] ?? '') == 'admin' ? 'selected' : '' ?>>Admin</option>
                </select>
                <?php if (!empty($errors['role'])): ?>
                    <p class="text-danger"><?= implode(', ', $errors['role']) ?></p>
                <?php endif; ?>
            </div>

            <button type="submit" class="btn btn-primary w-100" style="margin-top:15px">Thêm tài khoản</button>
        </form>
        
    </div>
</div>
@endsection
