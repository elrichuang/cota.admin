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
                        <div class="form-group form-group-sm">
                            <select id="user_id" name="user_id" class="form-control select2 form-control-sm" style="width: 150px" data-allow-clear="true" data-placeholder="管理员">
                                <option></option>
                                @foreach($users as $user)
                                    @if($user->id == request()->get('user_id'))
                                        <option value="{{ $user->id }}" selected="selected">{{ $user->name }}</option>
                                    @else
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    @endcomponent
                </div>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-bordered table-head-fixed table-hover table-sm">
                    <thead>
                        <tr>
                            <th width="80" style="text-align: center">ID</th>
                            <th width="100">姓名</th>
                            <th width="60" style="text-align: center">头像</th>
                            <th width="150">uri</th>
                            <th width="80" style="text-align: center">方法</th>
                            <th width="80" style="text-align: center">IP</th>
                            <th style="text-align: center">设备</th>
                            <th style="text-align: center">请求数据</th>
                            <th style="text-align: center">返回数据</th>
                            <th width="190">创建时间</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                        <tr>
                            <td style="text-align: center">{{ $item->id }}</td>
                            <td>{{ $item->user->name }}</td>
                            <td style="padding-left: 15px">
                                <img src="{{ $item->user->avatar }}" class="img-circle img-sm elevation-1" alt="{{ $item->user->name }}">
                            </td>
                            <td>
                                <span class="badge badge-info">{{ $item->uri }}</span>
                            </td>
                            <td style="text-align: center">
                                @switch($item->request_method)
                                    @case('GET')
                                    <span class="badge badge-primary">{{ $item->request_method }}</span>
                                    @break
                                    @case('POST')
                                    <span class="badge badge-success">{{ $item->request_method }}</span>
                                    @break
                                    @case('PUT')
                                    <span class="badge badge-warning">{{ $item->request_method }}</span>
                                    @break
                                    @case('PATCH')
                                    <span class="badge badge-warning">{{ $item->request_method }}</span>
                                    @break
                                    @case('DELETE')
                                    <span class="badge badge-danger">{{ $item->request_method }}</span>
                                    @break
                                    @default
                                    <span class="badge badge-light">{{ $item->request_method }}</span>
                                @endswitch

                            </td>
                            <td style="text-align: center">
                                <span class="badge badge-light">{{ $item->ip_address }}</span>
                            </td>
                            <td>
                                <textarea class="form-control form-control-sm" readonly="readonly">{{ $item->user_agent }}</textarea>
                            </td>
                            <td>
                                <textarea class="form-control form-control-sm" readonly="readonly">{{ $item->request_data }}</textarea>
                            </td>
                            <td>
                                <textarea class="form-control form-control-sm" readonly="readonly">{{ $item->response_data }}</textarea>
                            </td>
                            <td>{{ $item->created_at }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
            @component('admin.components.pagination', ['items'=>$items])
            @endcomponent
            <!-- /.card-footer-->
        </div>
        <!-- /.card -->

    </section>
    <!-- /.content -->
@endsection
