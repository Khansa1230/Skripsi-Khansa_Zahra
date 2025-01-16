<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Akun</title>
    <link rel="stylesheet" href="{{asset('templete/assets/css/main/app.css')}}">
    <link rel="stylesheet" href="{{asset('templete/assets/css/pages/auth.css')}}">
    <link rel="shortcut icon" href="{{asset('templete/assets/images/logo/favicon.svg" type="image/x-icon')}}">
    <link rel="shortcut icon" href="{{asset('templete/assets/images/logo/favicon.png')}}" type="image/png">
</head>

<body>
    <div id="auth">
        
<div class="row h-100">
    <div class="col-lg-5 col-12">
        <div id="auth-left">
            <div class="auth-logo">
                <a href="index.html"></a>
            </div>
            <h1 class="auth-title">Forgot Password</h1>
            <p class="auth-subtitle mb-5">Input your email and we will send you reset password link.</p>

            <form method="post" action="{{route('forgot.store')}}" >
                @csrf
                <div class="form-group position-relative has-icon-left mb-4">
                    <input type="text" id="email_uin" name="email_uin" 
                        class="form-control @error('email_uin') is-invalid @enderror form-control-xl" placeholder="Email">
                    <div class="form-control-icon">
                        <i class="bi bi-envelope"></i>
                    </div>
                    @error('email_uin')
                        <small class="btn btn-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="form-group position-relative has-icon-left mb-4">
                    <input type="password" id="password" name="password" 
                        class="form-control @error('password') is-invalid @enderror form-control-xl" placeholder="Password">
                    <div class="form-control-icon">
                        <i class="bi bi-shield-lock"></i>
                    </div>
                    @error('password')
                        <small class="btn btn-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group position-relative has-icon-left mb-4">
                    <input type="password" id="password_confirmation" name="password_confirmation" 
                        class="form-control @error('password_confirmation') is-invalid @enderror form-control-xl" placeholder="Konfirmasi Password">
                    <div class="form-control-icon">
                        <i class="bi bi-shield-lock"></i>
                    </div>
                    @error('password_confirmation')
                        <small class="btn btn-danger">{{ $message }}</small>
                    @enderror
                </div>
                <button class="btn btn-primary btn-block btn-lg shadow-lg mt-5">Send</button>
            </form>
            <div class="text-center mt-5 text-lg fs-4">
                <p class='text-gray-600'>Remember your account? <a href="{{ route('login') }}" class="font-bold">Log in</a>.
                </p>
            </div>
        </div>
    </div>
    <div class="col-lg-7 d-none d-lg-block">
        <div id="auth-right">

        </div>
    </div>
</div>

    </div>
</body>

</html>
