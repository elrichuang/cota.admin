@extends('admin.layouts.default')

@section('breadcrumb')
    @component('admin.components.breadcrumb',['ability'=>$ability])
        <li class="breadcrumb-item active">页面管理</li>
        <li class="breadcrumb-item"><a href="{{ route('admin.pages.all') }}">页面列表</a></li>
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
            <form id="myForm" role="form" action="@isset($item){{ route('api.admin.pages.update',['page'=>$item]) }}@else{{ route('api.admin.pages.store') }}@endisset">
                <div class="card-body">
                    <div class="form-group row">
                        <label for="title">标题</label>
                        <input type="text" class="form-control" name="title" id="title" @isset($item)value="{{ $item->title }}"@endisset placeholder="输入标题">
                        <small class="form-text text-muted">
                            页面标题，长度不小于 2 个字符
                        </small>
                    </div>
                    <div class="form-group row">
                        <label for="content">内容</label>
                        @component('admin.components.editor',['fieldName' => 'content'])
                        @endcomponent
                    </div>
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
        $(function(){
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
                                                window.location.href = "{{ route('admin.pages.store') }}";
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
                                                window.location.href = "{{ route('admin.pages.all') }}";
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
            form.submit(function() {
                UE.getEditor('content').sync();
            }).validate({
                rules: {
                    title: {
                        required: true,
                        minlength: 2,
                    },
                    content: {
                        required: true
                    }
                },
                messages: {
                    title: {
                        required: "请输入标题",
                        minlength: "长度不少于2个字",
                    },
                    content: "请输入内容",
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
        });
    </script>
@endsection
