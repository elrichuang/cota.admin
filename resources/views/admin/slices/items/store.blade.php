@extends('admin.layouts.default')

@section('breadcrumb')
    @component('admin.components.breadcrumb',['ability'=>$ability])
        <li class="breadcrumb-item active">幻灯片图片管理</li>
        <li class="breadcrumb-item"><a href="{{ route('admin.slice_items.all') }}">幻灯片图片列表</a></li>
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
                    @component('admin.components.go-back-button')
                    @endcomponent
                </div>
            </div>
            <!-- form start -->
            <form id="myForm" role="form" action="@isset($item){{ route('api.admin.slice_items.update',['slice_item'=>$item]) }}@else{{ route('api.admin.slice_items.store') }}@endisset">
                <div class="card-body">
                    <div class="form-group row">
                        <label for="slice_id">幻灯片</label>
                        <select id="slice_id" name="slice_id" class="select2" style="width: 100%" data-placeholder="请选择所属幻灯片">
                            <option value=""></option>
                            @foreach($allSlices as $sliceItem)
                                @if(isset($item) && $sliceItem->id == $item->slice_id)
                                    <option value="{{ $sliceItem->id }}" selected="selected">{{ $sliceItem->name }}</option>
                                @elseif(request()->get('slice_id') && request()->get('slice_id') == $sliceItem->id)
                                    <option value="{{ $sliceItem->id }}" selected="selected">{{ $sliceItem->name }}</option>
                                @else
                                    <option value="{{ $sliceItem->id }}">{{ $sliceItem->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group row">
                        <label for="image">缩略图</label>
                        @component('admin.components.single-image-upload-oss',['fieldName'=>'image','image'=>isset($item)?$item->image:null])
                        @endcomponent
                        <small class="text-muted">
                            文件大小200KB 以内
                        </small>
                    </div>
                    <div class="form-group row">
                        <label for="url">链接</label>
                        <input type="text" class="form-control" name="url" id="url" @isset($item)value="{{ $item->url }}"@endisset placeholder="输入URL">
                        <small class="form-text text-muted">
                            http或https开头
                        </small>
                    </div>
                    @component('admin.components.num-sort',['item'=>isset($item)?$item:null])
                    @endcomponent
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">保存</button>
                </div>
            </form>
        </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->
@endsection

@section('javascript')
    <script>
        // 表单提交
        var form = $('#myForm');

        $.validator.setDefaults({
            submitHandler: function () {
                // 验证通过
                $.ajax({
                    url:form.attr('action'),
                    @isset($item)
                    type:'PATCH',
                    @else
                    type:'POST',
                    @endisset
                    data:form.serialize(),
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
                            bootbox.dialog({
                                title: '成功',
                                message: result.message,
                                size: 'large',
                                closeButton: false,
                                buttons: {
                                    @if(!isset($item))
                                    //添加
                                    cancel: {
                                        label: "继续添加",
                                        className: 'btn-primary',
                                        callback: function(){
                                            window.location.href = "{{ route('admin.slice_items.store') }}";
                                            return false;
                                        }
                                    },
                                    @endif
                                    noclose: {
                                        label: "返回",
                                        className: 'btn-info',
                                        callback: function(){
                                            window.location.href = "{{ url()->previous() }}";
                                            return false;
                                        }
                                    },
                                    ok: {
                                        label: "返回列表",
                                        className: 'btn-default',
                                        callback: function(){
                                            window.location.href = "{{ route('admin.slice_items.all') }}";
                                            return false;
                                        }
                                    }
                                }
                            });
                        }
                    },
                });
            }
        });
        form.validate({
            rules: {
                url: {
                    required: true,
                    url: true
                },
                slice_id: {
                    required: true
                },
                image: {
                    required: true
                }
            },
            messages: {
                url: {
                    required: "请输入链接",
                    url:'输入的链接格式不对'
                },
                slice_id:"请选择一个幻灯片",
                image: "请上传一张图片"
            },
            errorElement: 'span',
            errorPlacement: function (error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function (element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });
    </script>
@endsection
