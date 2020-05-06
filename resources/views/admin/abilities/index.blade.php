@extends('admin.layouts.default')

@section('breadcrumb')
    @component('admin.components.breadcrumb',['ability'=>$ability])
        <li class="breadcrumb-item active">权限管理</li>
    @endcomponent
@endsection

@section('content')
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="card card-default card-tabs">
            <div class="card-header p-0 pt-1">
                <ul class="nav nav-tabs" id="custom-tabs-two-tab" role="tablist">
                    <li class="pt-2 px-3"><h3 class="card-title">{{ $ability->name }}</h3></li>
                    <li class="nav-item">
                        <a class="nav-link active" id="custom-tabs-two-view-tab" data-toggle="pill" href="#view-tab" role="tab" aria-controls="view-tab" aria-selected="true">视图</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="custom-tabs-two-api-tab" data-toggle="pill" href="#api-tab" role="tab" aria-controls="api-tab" aria-selected="false">接口</a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="custom-tabs-two-tabContent">
                    <div class="tab-pane fade show active" id="view-tab" role="tabpanel" aria-labelledby="view-tab">
                        <!-- Sidebar Menu -->
                        <nav class="mt-2">
                            <ul class="nav nav-pills nav-sidebar nav-child-indent flex-column" data-widget="treeview" role="menu" data-accordion="false">
                                <!-- Add icons to the links using the .nav-icon class
                                     with font-awesome or any other icon font library -->
                                @foreach ($items['view'] as $item)
                                    @if(!$item->use_url && (count($item->children) || !$item->alias))
                                        {{--子菜单--}}
                                        <li class="nav-item has-treeview menu-open">
                                            <div class="nav-link">
                                                <i class="nav-icon fas {{ $item->icon }}"></i>
                                                <span>
                                                    {{ $item->name }}（{{ $item->num_sort }}）
                                                    <i class="fas fa-angle-left right"></i>
                                                </span>
                                                <div class="right">
                                                    <button class="btn btn-primary btn-xs btn-edit" data-id="{{ $item->id }}"><i class="fas fa-edit"></i> 编辑</button>
                                                    <button class="btn btn-danger btn-xs btn-delete" data-id="{{ $item->id }}"><i class="fas fa-trash"></i> 删除</button>
                                                </div>
                                            </div>
                                            <ul class="nav nav-treeview">
                                                @foreach ($item->children as $childAbility)
                                                    @if(count($childAbility->children) || !$childAbility->alias)
                                                        {{--子菜单--}}
                                                        <li class="nav-item has-treeview menu-open">
                                                            <div class="nav-link">
                                                                <i class="nav-icon fas {{ $childAbility->icon }}"></i>
                                                                <span>
                                                                    {{ $childAbility->name }}（{{ $childAbility->num_sort }}）
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
                                                                            <i class="fas {{ $lastChildAbility->icon }} nav-icon"></i>
                                                                            <span>{{ $lastChildAbility->name }}</span>（{{ $lastChildAbility->num_sort }}）
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
                                                                <i class="nav-icon fas {{ $childAbility->icon }}"></i>
                                                                <span>
                                                                    {{ $childAbility->name }}（{{ $childAbility->num_sort }}）
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
                                                <i class="fas {{ $item->icon }} nav-icon"></i>
                                                <span>{{ $item->name }}（{{ $item->num_sort }}）</span>
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
                    <div class="tab-pane fade" id="api-tab" role="tabpanel" aria-labelledby="api-tab">
                        <div class="sidebar">
                            <!-- Sidebar Menu -->
                            <nav class="mt-2">
                                <ul class="nav nav-pills nav-sidebar nav-child-indent flex-column" data-widget="treeview" role="menu" data-accordion="false">
                                    <!-- Add icons to the links using the .nav-icon class
                                         with font-awesome or any other icon font library -->
                                    @foreach ($items['api'] as $item)
                                        <li class="nav-item">
                                            <div class="nav-link">
                                                <i class="fas fa-plug nav-icon"></i>
                                                <span>{{ $item->name }}（{{ $item->num_sort }}）</span>
                                                <div class="right">
                                                    <button class="btn btn-primary btn-xs" href="{{ route('admin.abilities.profile',['ability'=>$item]) }}"><i class="fas fa-edit"></i> 编辑</button>
                                                    <button class="btn btn-danger btn-xs btn-delete" data-id="{{ $item->id }}"><i class="fas fa-trash"></i> 删除</button>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </nav>
                            <!-- /.sidebar-menu -->
                        </div>
                    </div>
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
                var ability_id = $(e.currentTarget).data('id');
                window.location.href = "{{ route('admin.abilities.profile') }}"+"?id="+ability_id;
            });

            $('.btn-delete').bind("click",function(e){
                e.preventDefault();
                var ability_id = $(e.currentTarget).data('id');
                bootbox.confirm("真的要删除该项目吗？", function(result) {
                    if (result) {
                        $.ajax({
                            url:"{{ route('api.admin.abilities.destroyMany') }}",
                            type:'POST',
                            data:{ids:ability_id},
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
