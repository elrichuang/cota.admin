<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}" id="csrf-token">
    <title>{{ config('app.name') }}@isset($ability) - {{ $ability->name }}@endisset</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="/static/adminlte-3.0.4/plugins/fontawesome-free/css/all.min.css">
    <!-- Tempusdominus Bbootstrap 4 -->
    <link rel="stylesheet" href="/static/adminlte-3.0.4/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <!-- Select2 -->
    <link rel="stylesheet" href="/static/adminlte-3.0.4/plugins/select2/css/select2.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="/static/adminlte-3.0.4/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/static/adminlte-3.0.4/dist/css/adminlte.min.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="/static/adminlte-3.0.4/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="/static/adminlte-3.0.4/plugins/daterangepicker/daterangepicker.css">
    <!-- zTree -->
    <link rel="stylesheet" href="/static/css/zTreeStyle/zTreeStyle.css">
    @yield('style')
    <!-- jQuery -->
    <script src="/static/adminlte-3.0.4/plugins/jquery/jquery.min.js"></script>
    <!-- jQuery Cookie -->
    <script src="/static/js/jquery.cookie-1.4.1.min.js"></script>
    <!-- jQuery Md5 -->
    <script src="/static/js/jquery.md5.js"></script>
    <!-- bootbox -->
    <script src="/static/js/bootbox.all.min.js"></script>
    <!-- selectToDo -->
    <script src="/static/js/jquery.selectToDo.min.js"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="/static/adminlte-3.0.4/plugins/jquery-ui/jquery-ui.min.js"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $.widget.bridge('uibutton', $.ui.button);
    </script>
    <!-- Bootstrap 4 -->
    <script src="/static/adminlte-3.0.4/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- bs-custom-file-input -->
    <script src="/static/adminlte-3.0.4/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
    <!-- jquery-validation -->
    <script src="/static/adminlte-3.0.4/plugins/jquery-validation/jquery.validate.min.js"></script>
    <script src="/static/adminlte-3.0.4/plugins/jquery-validation/additional-methods.min.js"></script>
    <!-- Select2 -->
    <script src="/static/adminlte-3.0.4/plugins/select2/js/select2.full.min.js"></script>
    <!-- daterangepicker -->
    <script src="/static/adminlte-3.0.4/plugins/moment/moment.min.js"></script>
    <script src="/static/adminlte-3.0.4/plugins/moment/locale/zh-cn.js"></script>
    <script src="/static/adminlte-3.0.4/plugins/daterangepicker/daterangepicker.js"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="/static/adminlte-3.0.4/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
    <!-- overlayScrollbars -->
    <script src="/static/adminlte-3.0.4/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
    <!-- AdminLTE App -->
    <script src="/static/adminlte-3.0.4/dist/js/adminlte.js"></script>
    <script src="/static/js/lrz.bundle.js"></script>
    <!-- Neditor -->
    <script>
        // 定义编辑器路径常量
        window.UEDITOR_OSS_POLICY_URL = "{{ route('api.admin.images.ossPolicy') }}";
        window.UEDITOR_OSS_UPLOAD_URL = "{{ config('oss.host') }}";
    </script>
    <script src="/static/js/neditor/neditor.config.js"></script>
    <script src="/static/js/neditor/editor_api.js"></script>
    <script src="/static/js/neditor/neditor.service.js"></script>
    <script src="/static/js/neditor/i18n/zh-cn/zh-cn.js"></script>
    <!-- Neditor End -->
    <script src="/static/js/jquery.ztree.all.min.js"></script>
</head>
<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed" style="height: auto">
<div class="wrapper">
    @include('admin.layouts.navbar')

    @include('admin.layouts.sidebar')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">@isset($ability){{ $ability->name }}@endisset</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        @yield('breadcrumb')
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        @yield('content')
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <footer class="main-footer">
        <div class="float-right d-none d-sm-inline-block">
            <b>Version</b> 4.0.0
        </div>
        <strong>版权所有 &copy; 2006-2020 <a href="http://www.cootaa.com" target="_blank">阔达科技</a>.</strong>
    </footer>
</div>
<!-- ./wrapper -->
<script>
    $(function () {
        $('body').overlayScrollbars({ });

        bootbox.setDefaults({locale: "zh_CN"});
        //Initialize Select2 Elements
        $('.select2').select2();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Authorization':'Bearer '+$.cookie("{{ config('admin.api_cookie_name') }}")
            },
            beforeSend: function() {
                if ($.cookie("{{ config('admin.api_cookie_name') }}") === undefined) {
                    bootbox.alert('请先登录',function(){
                        window.location.href = "{{ route('admin.users.login') }}";
                    });
                }
            }
        });

        bsCustomFileInput.init();

        $('.mydaterangepicker').daterangepicker({
            locale: {
                format: 'YYYY-MM-DD',
                applyLabel : '确定',
                cancelLabel : '取消',
                fromLabel : '起始时间',
                toLabel : '结束时间',
                customRangeLabel : '自定义',
                daysOfWeek : [ '日', '一', '二', '三', '四', '五', '六' ],
                monthNames : [ '一月', '二月', '三月', '四月', '五月', '六月',
                    '七月', '八月', '九月', '十月', '十一月', '十二月' ],
                firstDay : 1
            }
        });
    });
</script>
@yield('javascript')
</body>
</html>
