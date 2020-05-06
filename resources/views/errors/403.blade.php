@extends('admin.layouts.default')

@section('content')
    <section class="content">
        <div class="error-page">
            <h2 class="headline text-gray"> 403</h2>

            <div class="error-content">
                <h3><i class="fas fa-exclamation-triangle text-gray"></i> 没有权限。</h3>

                <p>
                    您没有权限访问该页面。
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
