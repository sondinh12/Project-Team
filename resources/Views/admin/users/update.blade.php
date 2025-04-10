@extends('admin.layouts.layout')

@section('title','Cập nhật tài khoản')

@section('content')
<div class="container mt-4">
    <div class="card shadow-lg p-4" style="margin-bottom:50px">
        <h3 class="text-center">Cập nhật tài khoản</h3>
        <form action="<?= $_ENV['BASE_URL'] . 'admin/users/update/' . $user['id_user'] ?>" enctype="multipart/form-data" method="post" class="needs-validation">
            <input type="hidden" name="id_user" value="<?= $user['id_user'] ?>">

            <div class="mb-3">
                <label class="form-label">Họ và Tên</label>
                <input type="text" name="name" class="form-control" value="<?= $_SESSION['old']['name'] ?? $user['name'] ?>">
                <?php if (!empty($errors['name'])): ?>
                    <p class="text-danger"><?= implode(', ', $errors['name']) ?></p>
                <?php endif; ?>
            </div>


            <div class="mb-3">
                <label class="form-label">Ảnh đại diện</label>
                <input type="file" name="user_img" class="form-control">
                <?php if (!empty($errors['user_img'])): ?>
                    <p class="text-danger"><?= implode(', ', $errors['user_img']) ?></p>
                <?php endif; ?>
                <br>
                <img src="<?= $_ENV['BASE_URL']  . $user['user_img'] ?>" width="100" alt="Ảnh đại diện">
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="<?= $_SESSION['old']['email'] ?? $user['email'] ?>">
                <?php if (!empty($errors['email'])): ?>
                    <p class="text-danger"><?= implode(', ', $errors['email']) ?></p>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label class="form-label">Số điện thoại</label>
                <input type="text" name="phone" class="form-control" value="<?= $_SESSION['old']['phone'] ?? $user['phone'] ?>">
                <?php if (!empty($errors['phone'])): ?>
                    <p class="text-danger"><?= implode(', ', $errors['phone']) ?></p>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label class="form-label">Địa chỉ</label>
                <input type="text" name="address" class="form-control" value="<?= $_SESSION['old']['address'] ?? $user['address'] ?>">
                <?php if (!empty($errors['address'])): ?>
                    <p class="text-danger"><?= implode(', ', $errors['address']) ?></p>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label class="form-label">Trạng thái</label>
                <select name="status" class="form-control">
                    <option value="active" <?= ($_SESSION['old']['status'] ?? $user['status']) == 'active' ? 'selected' : '' ?>>Active</option>
                    <option value="inactive" <?= ($_SESSION['old']['status'] ?? $user['status']) == 'inactive' ? 'selected' : '' ?>>Inactive</option>
                </select>
                <?php if (!empty($errors['status'])): ?>
                    <p class="text-danger"><?= implode(', ', $errors['status']) ?></p>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label class="form-label">Quyền</label>
                <select name="role" class="form-control">
                    <option value="user" <?= ($_SESSION['old']['role'] ?? $user['role']) == 'user' ? 'selected' : '' ?>>User</option>
                    <option value="admin" <?= ($_SESSION['old']['role'] ?? $user['role']) == 'admin' ? 'selected' : '' ?>>Admin</option>
                </select>
                <?php if (!empty($errors['role'])): ?>
                    <p class="text-danger"><?= implode(', ', $errors['role']) ?></p>
                <?php endif; ?>
            </div>

            <button type="submit" class="btn btn-primary w-100" style="margin-top:15px">Cập nhật tài khoản</button>
        </form>
    </div>
</div>
@endsection
