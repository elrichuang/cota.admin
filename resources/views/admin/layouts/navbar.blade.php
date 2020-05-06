<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
{{--        <li class="nav-item d-none d-sm-inline-block">--}}
{{--            <a href="{{ route('admin.dashboard') }}" class="nav-link">仪表盘</a>--}}
{{--        </li>--}}
{{--        <li class="nav-item d-none d-sm-inline-block">--}}
{{--            <a href="#" class="nav-link">联系我们</a>--}}
{{--        </li>--}}
    </ul>

    <!-- SEARCH FORM -->
{{--    <form class="form-inline ml-3">--}}
{{--        <div class="input-group input-group-sm">--}}
{{--            <input class="form-control form-control-navbar" type="search" placeholder="搜索" aria-label="搜索">--}}
{{--            <div class="input-group-append">--}}
{{--                <button class="btn btn-navbar" type="submit">--}}
{{--                    <i class="fas fa-search"></i>--}}
{{--                </button>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </form>--}}

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- Messages Dropdown Menu -->
{{--        @include('admin.layouts.navbar.message')--}}

        <!-- Notifications Dropdown Menu -->
{{--        @include('admin.layouts.navbar.notification')--}}

        @if(auth('admin_web')->user())
        <li class="nav-item dropdown user-menu">
            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                <img src="{{ auth('admin_web')->user()->avatar }}" class="user-image img-circle elevation-1" alt="管理员头像">
                <span class="d-none d-md-inline">{{ auth('admin_web')->user()->name }}</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <!-- User image -->
                <li class="user-header bg-blue">
                    <img src="{{ auth('admin_web')->user()->avatar }}" class="img-circle elevation-1" alt="管理员头像">

                    <p>
                        {{ auth('admin_web')->user()->name }} - {{ auth('admin_web')->user()->email }}
                        <small>注册时间：{{ auth('admin_web')->user()->created_at }}</small>
                    </p>
                </li>
                <!-- Menu Footer-->
                <li class="user-footer">
                    <a href="{{ route('admin.users.profile',['user'=>auth('admin_web')->user()]) }}" class="btn btn-default btn-flat">编辑</a>
                    <button id="btnLogout" class="btn btn-default btn-flat float-right">退出登录</button>
                </li>
            </ul>
        </li>
        @endif
    </ul>
</nav>
<!-- /.navbar -->
<script>
    $("#btnLogout").bind("click",function(){
        bootbox.confirm("真的要退出登录吗？", function(result) {
            if(result)
            {
                $.post('{{ route('api.admin.users.logout') }}', function(result) {
                    // ... Process the result ...
                    var success = result.success;
                    if(!success)
                    {
                        bootbox.alert(result.message);
                    }else
                    {
                        bootbox.alert("退出登录成功！",function() {
                            $.removeCookie('my_cota_admin_token');
                            window.location.href = "{{ route('admin.users.login') }}";
                        });
                    }
                }, 'json');
            }
        });
    });
</script>
