@extends('admin.layouts.default')

@section('breadcrumb')
    @component('admin.components.breadcrumb',['ability'=>$ability])
        <li class="breadcrumb-item active">管理员管理</li>
    @endcomponent
@endsection

@section('content')
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ $ability->name }}</h3>
                <div class="card-tools">
                    @component('admin.components.card-tools')
                    @endcomponent
                </div>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-bordered table-head-fixed table-hover table-sm">
                    <thead>
                        <tr>
                            <th width="60">&nbsp;</th>
                            <th width="80" style="text-align: center">ID</th>
                            <th>名称</th>
                            <th width="60" style="text-align: center">头像</th>
                            <th>Email</th>
                            <th width="110" style="text-align: center">超级管理员</th>
                            <th width="80" style="text-align: center">状态</th>
                            <th width="190">创建时间</th>
                            <th width="110">操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                        <tr>
                            <td style="text-align: center">
                                <div class="icheck-primary d-inline">
                                    <input type="checkbox" id="item{{ $item->id }}" value="{{ $item->id }}" name="check">
                                    <label for="item{{ $item->id }}"></label>
                                </div>
                            </td>
                            <td style="text-align: center">{{ $item->id }}</td>
                            <td>{{ $item->name }}</td>
                            <td>
                                <img src="{{ $item->avatar }}" class="img-circle img-sm elevation-1" alt="{{ $item->name }}">
                            </td>
                            <td><i class="fas fa-envelope"></i> {{ $item->email }}</td>
                            <td style="text-align: center">
                                @if($item->super_admin == 1)
                                    <i class="fas fa-check text-success"></i>
                                @else
                                    <i class="fas fa-times text-danger"></i>
                                @endif
                            </td>
                            <td style="text-align: center">
                                @if($item->status == 'activated')
                                    <span class="badge bg-success">已激活</span>
                                @else
                                    <span class="badge bg-danger">未激活</span>
                                @endif
                            </td>
                            <td>{{ $item->created_at }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.users.profile', ['user'=>$item]) }}" class="btn btn-primary"><i class="fas fa-edit"></i></a>
                                    <a href="{{ route('admin.users.log', ['user_id'=>$item->id]) }}" class="btn btn-info"><i class="fas fa-list"></i></a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
            @component('admin.components.pagination', ['items'=>$items,'isCanDelete' => true,'pathDel'=>route('api.admin.users.destroyMany')])
            @endcomponent
            <!-- /.card-footer-->
        </div>
        <!-- /.card -->

    </section>
    <!-- /.content -->
@endsection
