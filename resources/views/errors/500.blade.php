@extends('admin.layouts.default')

@section('content')
    <section class="content">
        <div class="error-page">
            <h2 class="headline text-danger">500</h2>

            <div class="error-content">
                <h3><i class="fas fa-exclamation-triangle text-danger"></i> 发生错误了。</h3>

                <p>
                    您要访问的页面发生了错误。
                    请联系管理员解决。
                </p>
                <p>
                    <a href="{{ route('admin.dashboard') }}">返回首页</a>
                </p>
            </div>
        </div>
        <!-- /.error-page -->
    </section>
    <!-- /.content -->
@endsection
