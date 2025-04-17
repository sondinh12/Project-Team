<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập</title>

    <!-- CSS -->
    <link rel="stylesheet" href="./resources/Views/Auth/assets/css/style.css">
    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>

    <style>
        body {
            background-image: url('resources/Views/Auth/assets/images/background2.gif');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
        }
    </style>
</head>
<body>

<section class="container forms 
    <?= ($active_form ?? 'login') === 'register' ? 'show-signup' : '' ?>
    <?= ($active_form ?? 'login') === 'forgotpass' ? 'show-forgot' : '' ?>">

    {{-- Login Form --}}
    <div class="form login">
        <div class="form-content">
            <header>Đăng nhập</header>
            <form action="" method="POST">
                <div class="field input-field">
                    <input type="text" name="user_name" value="{{ $old['user_name'] ?? '' }}" placeholder="Tên đăng nhập" class="input">
                </div>
                @if (!empty($errors['user_name']))
                    <p class="text-danger">{{ implode(', ', $errors['user_name']) }}</p>
                @endif

                <div class="field input-field">
                    <input type="password" placeholder="Nhập mật khẩu" name="pass" class="password">
                    <i class='bx bx-hide eye-icon'></i>
                </div>
                @if (!empty($errors['pass']))
                    <p class="text-danger">{{ implode(', ', $errors['pass']) }}</p>
                @endif
                @if (!empty($errors['login']))
                    <p class="text-danger">{{ $errors['login'][0] }}</p>
                @endif

                <div class="form-link">
                    <a href="#" class="link forgot-link">Quên mật khẩu?</a>
                </div>

                <div class="field button-field">
                    <button type="submit" name="btn_login">Đăng nhập</button>
                </div>
            </form>

            <div class="form-link">
                <span>Nếu bạn chưa có tài khoản <a href="#" class="link signup-link">Đăng ký</a></span>
            </div>
        </div>

        <div class="line"></div>

        <div class="media-options">
            <a href="#" class="field facebook">
                <i class='bx bxl-facebook facebook-icon'></i>
                <span>Đăng nhập với Facebook</span>
            </a>
        </div>

        <div class="media-options">
            <a href="#" class="field google">
                <img src="./resources/Views/Auth/assets/images/google.png" alt="" class="google-img">
                <span>Đăng nhập với Google</span>
            </a>
        </div>
    </div>

    {{-- Register Form --}}
    <div class="form signup">
        <div class="form-content">
            <header>Đăng ký</header>
            <form action="" method="POST">
                <div class="field input-field">
                    <input type="text" placeholder="Nhập tên" name="name" class="input" value="{{ $old['name'] ?? '' }}">
                </div>
                @if (!empty($errors['name']))
                    <p class="text-danger">{{ implode(', ', $errors['name']) }}</p>
                @endif

                <div class="field input-field">
                    <input type="text" placeholder="Nhập tên đăng nhập" name="user_name" class="input" value="{{ $old['user_name'] ?? '' }}">
                </div>
                @if (!empty($errors['user_name']))
                    <p class="text-danger">{{ implode(', ', $errors['user_name']) }}</p>
                @endif

                <div class="field input-field">
                    <input type="email" placeholder="Nhập Email" name="email" class="input" value="{{ $old['email'] ?? '' }}">
                </div>
                @if (!empty($errors['email']))
                    <p class="text-danger">{{ implode(', ', $errors['email']) }}</p>
                @endif

                <div class="field input-field">
                    <input type="password" placeholder="Nhập mật khẩu" class="password" name="password">
                </div>
                @if (!empty($errors['password']))
                    <p class="text-danger">{{ implode(', ', $errors['password']) }}</p>
                @endif

                <div class="field input-field">
                    <input type="password" placeholder="Nhập lại mật khẩu" class="password" name="confirm_password">
                    <i class='bx bx-hide eye-icon'></i>
                </div>
                @if (!empty($errors['confirm_password']))
                    <p class="text-danger">{{ implode(', ', $errors['confirm_password']) }}</p>
                @endif

                <div class="field button-field">
                    <button type="submit" name="btn_register">Đăng ký</button>
                </div>
            </form>

            <div class="form-link">
                <span>Bạn đã có tài khoản? <a href="#" class="link login-link">Đăng nhập</a></span>
            </div>
        </div>
    </div>

    {{-- Get OTP Form --}}


    {{-- Change Password Form --}}
    @if (isset($form_change) && $form_change == '1')
    <div class="form forgotpass">
        <div class="form-content">
            <header>Đặt lại mật khẩu</header>
            @if (!empty($success))
            <p class="text-danger">{{ $success }}</p>
        @endif
            @if (!empty($message_reset))
                <p class="text-danger">{{ $message_reset }}</p>
            @endif

            <form method="POST" action="">
                <div class="field input-field">
                    <input type="text" name="otp" id="otp" placeholder="Nhập mã OTP" class="input">
                </div>
                @if (!empty($errors['otp']))
                    <p class="text-danger">{{ implode(', ', $errors['otp']) }}</p>
                @endif

                <div class="field input-field">
                    <input type="password" name="password" id="password" placeholder="Nhập mật khẩu mới" class="input">
                </div>
                @if (!empty($errors['password']))
                    <p class="text-danger">{{ implode(', ', $errors['password']) }}</p>
                @endif

                <div class="field button-field">
                    <button type="submit" name="btn_changepass">Đặt lại mật khẩu</button>
                </div>
            </form>

            <div class="form-link">
                <span><a href="#" class="link back-login-link">Quay lại đăng nhập</a></span>
            </div>
        </div>
    </div>
    @else
    <div class="form forgotpass">
        <div class="form-content">
            <header>Quên mật khẩu</header>
            <form method="POST" action="">
                <div class="field input-field">
                    <input type="email" name="email_getotp" value="{{ $old['email_getotp'] ?? '' }}" placeholder="Nhập Email" class="input">
                </div>
                    @if (!empty($errors['email_getotp']))
                    <p class="text-danger">{{ implode(', ', $errors['email_getotp']) }}</p>
                @endif
                <div class="field button-field">
                    <button type="submit" name="btn_getotp">Gửi mã OTP</button>
                </div>
            </form>

            <div class="form-link">
                <span><a href="#" class="link back-login-link">Quay lại đăng nhập</a></span>
            </div>
        </div>
    </div>
    @endif      

</section>

<!-- JavaScript -->
<script src="./resources/Views/Auth/assets/js/script.js"></script>

</body>
</html>
