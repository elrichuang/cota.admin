@extends('admin.layouts.default')

@section('content')
    <section class="content">
        <div class="error-page">
            <h2 class="headline text-warning"> 404</h2>

            <div class="error-content">
                <h3><i class="fas fa-exclamation-triangle text-warning"></i> 找不到页面。</h3>

                <p>
                    找不到您要访问的页面。
                </p>
                <p>
                    <a href="{{ route('admin.dashboard') }}">返回首页</a>
                </p>
            </div>
            <!-- /.error-content -->
        </div>
        <!-- /.error-page -->
    </section>
    <!-- /.content -->
@endsection
