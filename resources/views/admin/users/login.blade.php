<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>COTA ADMIN - 登录</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="/static/adminlte-3.0.4/plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/static/adminlte-3.0.4/dist/css/adminlte.min.css">
    <style>
        html {
            background: url("/static/adminlte-3.0.4/dist/img/photo4.jpg") no-repeat center center fixed;
            -webkit-background-size: cover;
            -moz-background-size: cover;
            -o-background-size: cover;
            background-size: cover;
        }

        body {
            background-color: transparent;
        }

        .login-card-body {
            background: rgba(255,255,255,0.3);
        }

        .login-page {
            background: transparent;
        }

        .product-name {
            text-decoration: overline underline;
        }

    </style>
</head>
<body class="hold-transition login-page text-sm">
<div class="login-box">
    <div class="login-logo">
        <b>COTA</b> <span class="product-name">admin</span>
    </div>
    <!-- /.login-logo -->
    <div class="card">
        <div class="card-body login-card-body">
            <p class="login-box-msg">请输入账号密码登录</p>

            <form id="loginForm" action="{{ route('api.admin.users.login') }}" method="post">
                @csrf
                <div class="input-group mb-3">
                    <input type="email" name="email" class="form-control" placeholder="邮箱">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" name="password" class="form-control" placeholder="密码">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-6">

                        <img src="{{ config('app.default_avatar') }}" id="captchaImg" width="153" height="38"/>
                    </div>
                    <!-- /.col -->
                    <div class="col-6">
                        <input type="hidden" name="captcha_key" id="captchaKey">
                        <input type="text" name="captcha_code" class="form-control" placeholder="验证码" required autocomplete="off" maxlength="5">
                    </div>
                    <!-- /.col -->
                </div>
                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary btn-block">登录</button>
                    </div>
                </div>
            </form>
        </div>
        <!-- /.login-card-body -->
    </div>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="/static/adminlte-3.0.4/plugins/jquery/jquery.min.js"></script>
<!-- jQuery Cookie -->
<script src="/static/js/jquery.cookie-1.4.1.min.js"></script>
<!-- bootbox -->
<script src="/static/js/bootbox.all.min.js"></script>
<!-- Bootstrap 4 -->
<script src="/static/adminlte-3.0.4/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="/static/adminlte-3.0.4/dist/js/adminlte.min.js"></script>
<script>
    $(function() {
        // 验证码
        function loadCaptchas() {
            $.get('/api/captchas',function(result){
                if(result.success)
                {
                    $('#captchaKey').val(result.data.captcha_key);
                    $('#captchaImg').attr('src',result.data.captcha_image_content);
                }
            },'json');
        }

        loadCaptchas();

        // 表单提交
        var form = $('#loginForm');
        form.on('submit',function(e){
            e.preventDefault();
            $.post(form.attr('action'), form.serialize(), function(result) {
                // ... Process the result ...
                if(!result.success)
                {
                    bootbox.alert(result.message, function() {
                        // 刷新验证码
                        loadCaptchas();
                    });
                }else
                {
                    var token = result.data.token;
                    var date = new Date();
                    date.setTime(date.getTime() + (result.data.expires_in * 1000));
                    $.cookie("{{ config('admin.api_cookie_name') }}", token, {expires: date, path:'/'});
                    window.location.href = "/admin";
                }
            }, 'json');
        });
    });
</script>
</body>
</html>
