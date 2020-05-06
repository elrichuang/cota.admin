@extends('admin.layouts.default')

@section('breadcrumb')
    @component('admin.components.breadcrumb',['ability'=>$ability])
        <li class="breadcrumb-item active">文章管理</li>
    @endcomponent
@endsection

@section('content')
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="card card-default card-tabs">
            <div class="card-header">
                <h3 class="card-title">{{ $ability->name }}</h3>
            </div>
            <div class="card-body">
                <div class="sidebar">
                    <!-- Sidebar Menu -->
                    <nav class="mt-2">
                        <ul class="nav nav-pills nav-sidebar nav-child-indent flex-column" data-widget="treeview" role="menu" data-accordion="false">
                            <!-- Add icons to the links using the .nav-icon class
                                 with font-awesome or any other icon font library -->
                            @foreach ($items as $item)
                                @if(count($item->children))
                                    {{--子菜单--}}
                                    <li class="nav-item has-treeview menu-open">
                                        <div class="nav-link">
                                            <i class="nav-icon fas fa-caret-right"></i>
                                            <span>
                                                        {{ $item->title }}（{{ $item->num_sort }}）
                                                        <i class="fas fa-angle-left right"></i>
                                                    </span>
                                            <div class="right">
                                                <button class="btn btn-primary btn-xs btn-edit" data-id="{{ $item->id }}"><i class="fas fa-edit"></i> 编辑</button>
                                                <button class="btn btn-danger btn-xs btn-delete" data-id="{{ $item->id }}"><i class="fas fa-trash"></i> 删除</button>
                                            </div>
                                        </div>
                                        <ul class="nav nav-treeview">
                                            @foreach ($item->children as $childAbility)
                                                @if(count($childAbility->children))
                                                    {{--子菜单--}}
                                                    <li class="nav-item has-treeview menu-open">
                                                        <div class="nav-link">
                                                            <i class="nav-icon fas fa-caret-right"></i>
                                                            <span>
                                                                        {{ $childAbility->title }}（{{ $childAbility->num_sort }}）
                                                                        <i class="fas fa-angle-left right"></i>
                                                                    </span>
                                                            <div class="right">
                                                                <button class="btn btn-primary btn-xs btn-edit" data-id="{{ $childAbility->id }}"><i class="fas fa-edit"></i> 编辑</button>
                                                                <button class="btn btn-danger btn-xs btn-delete" data-id="{{ $childAbility->id }}"><i class="fas fa-trash"></i> 删除</button>
                                                            </div>
                                                        </div>
                                                        <ul class="nav nav-treeview">
                                                            @foreach ($childAbility->children as $lastChildAbility)
                                                                <li class="nav-item">
                                                                    <div class="nav-link">
                                                                        <i class="fas fa-caret-right nav-icon"></i>
                                                                        <span>{{ $lastChildAbility->title }}</span>（{{ $lastChildAbility->num_sort }}）
                                                                        <div class="right">
                                                                            <button class="btn btn-primary btn-xs btn-edit" data-id="{{ $lastChildAbility->id }}"><i class="fas fa-edit"></i> 编辑</button>
                                                                            <button class="btn btn-danger btn-xs btn-delete" data-id="{{ $lastChildAbility->id }}"><i class="fas fa-trash"></i> 删除</button>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </li>
                                                @else
                                                    <li class="nav-item">
                                                        <div class="nav-link">
                                                            <i class="nav-icon fas fa-caret-right"></i>
                                                            <span>
                                                                        {{ $childAbility->title }}（{{ $childAbility->num_sort }}）
                                                                    </span>
                                                            <div class="right">
                                                                <button class="btn btn-primary btn-xs btn-edit" data-id="{{ $childAbility->id }}"><i class="fas fa-edit"></i> 编辑</button>
                                                                <button class="btn btn-danger btn-xs btn-delete" data-id="{{ $childAbility->id }}"><i class="fas fa-trash"></i> 删除</button>
                                                            </div>
                                                        </div>
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </li>
                                @else
                                    {{--无子菜单--}}
                                    <li class="nav-item">
                                        <div class="nav-link">
                                            <i class="fas fa-caret-right nav-icon"></i>
                                            <span>{{ $item->title }}（{{ $item->num_sort }}）</span>
                                            <div class="right">
                                                <button class="btn btn-primary btn-xs btn-edit" data-id="{{ $item->id }}"><i class="fas fa-edit"></i> 编辑</button>
                                                <button class="btn btn-danger btn-xs btn-delete" data-id="{{ $item->id }}"><i class="fas fa-trash"></i> 删除</button>
                                            </div>
                                        </div>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </nav>
                    <!-- /.sidebar-menu -->
                </div>

            </div>
            <!-- /.card-body -->

            <!-- /.card-footer-->
        </div>
        <!-- /.card -->

    </section>
    <!-- /.content -->
@endsection

@section('javascript')
    <script>
        $(function () {
            $('.btn-edit').bind("click",function(e){
                e.preventDefault();
                var id = $(e.currentTarget).data('id');
                window.location.href = "{{ route('admin.article_categories.profile') }}"+"?id="+id;
            });

            $('.btn-delete').bind("click",function(e){
                e.preventDefault();
                var id = $(e.currentTarget).data('id');
                bootbox.confirm("真的要删除该项目吗？", function(result) {
                    if (result) {
                        $.ajax({
                            url:"{{ route('api.admin.article_categories.destroyMany') }}",
                            type:'POST',
                            data:{ids:id},
                            dataType:'json',
                            error:function() {
                                bootbox.alert('发生网络错误');
                            },
                            success:function(result) {
                                // ... Process the result ...
                                if(!result.success)
                                {
                                    bootbox.alert(result.message);
                                }else
                                {
                                    bootbox.alert(result.message,function(){
                                        window.location.reload();
                                    });
                                }
                            },
                        });
                    }
                });
            });
        });
    </script>
@endsection
