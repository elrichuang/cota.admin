@extends('admin.layouts.default')

@section('breadcrumb')
    @component('admin.components.breadcrumb',['ability'=>$ability])
        <li class="breadcrumb-item active">页面管理</li>
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
                            <th>标题</th>
                            <th width="190">创建时间</th>
                            <th width="60">操作</th>
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
                            <td>{{ $item->title }}</td>
                            <td>{{ $item->created_at }}</td>
                            <td>
                                <a href="{{ route('admin.pages.profile', ['page'=>$item]) }}" class="btn btn-primary"><i class="fas fa-edit"></i></a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
            @component('admin.components.pagination', ['items'=>$items,'isCanDelete' => true,'pathDel'=>route('api.admin.pages.destroyMany')])
            @endcomponent
            <!-- /.card-footer-->
        </div>
        <!-- /.card -->

    </section>
    <!-- /.content -->
@endsection
