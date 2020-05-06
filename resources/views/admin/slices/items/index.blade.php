@extends('admin.layouts.default')

@section('breadcrumb')
    @component('admin.components.breadcrumb',['ability'=>$ability])
        <li class="breadcrumb-item active">幻灯片管理</li>
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
                        <div class="form-group input-sm">
                            <select id="slice_id" name="slice_id" class="select2 form-control-sm" data-allow-clear="true" data-placeholder="幻灯片" style="width: 200px">
                                <option value=""></option>
                                @foreach($allSlices as $sliceItem)
                                    @if($sliceItem->id == request()->get('slice_id'))
                                        <option value="{{ $sliceItem->id }}" selected="selected">{{ $sliceItem->name }}</option>
                                    @else
                                        <option value="{{ $sliceItem->id }}">{{ $sliceItem->name }}</option>
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
                            <th width="60">&nbsp;</th>
                            <th width="80" style="text-align: center">ID</th>
                            <th>幻灯片</th>
                            <th width="80">图片</th>
                            <th width="120">链接</th>
                            <th width="70">排序</th>
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
                            <td>@if($item->slice){{ $item->slice->name }}@endif</td>
                            <td><img src="{{ $item->image }}" class="img-thumbnail img-md"></td>
                            <td>
                                <a href="{{ $item->url }}" class="btn btn-info" target="_blank"><i class="fas fa-link"></i> 跳转链接</a>
                            </td>
                            <td>{{ $item->num_sort }}</td>
                            <td>{{ $item->created_at }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.slice_items.profile', ['sliceItem'=>$item]) }}" class="btn btn-primary"><i class="fas fa-edit"></i></a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
            @component('admin.components.pagination', ['items'=>$items,'isCanDelete' => true,'pathDel'=>route('api.admin.slice_items.destroyMany')])
            @endcomponent
            <!-- /.card-footer-->
        </div>
        <!-- /.card -->

    </section>
    <!-- /.content -->
@endsection
