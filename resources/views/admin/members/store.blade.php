@extends('admin.layouts.default')

@section('breadcrumb')
    @component('admin.components.breadcrumb',['ability'=>$ability])
        <li class="breadcrumb-item active">会员管理</li>
        <li class="breadcrumb-item"><a href="{{ route('admin.members.all') }}">会员列表</a></li>
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
            <form id="myForm" role="form" action="@isset($item){{ route('api.admin.members.update',['member'=>$item]) }}@else{{ route('api.admin.members.store') }}@endisset">
                <div class="card-body">
                    <div class="form-group row">
                        <label for="phone">手机</label>
                        <input type="text" class="form-control" name="phone" id="phone" @isset($item)value="{{ $item->phone }}"@endisset placeholder="输入手机号" autocomplete="off">
                        <small class="form-text text-muted">
                            平台唯一，用于登录
                        </small>
                    </div>
                    <div class="form-group row">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" name="email" id="email" @isset($item)value="{{ $item->email }}"@endisset placeholder="输入Email" autocomplete="off">
                        <small class="form-text text-muted">
                            平台唯一，用于登录
                        </small>
                    </div>
                    <div class="form-group row">
                        <label for="nickname">昵称</label>
                        <input type="text" class="form-control" name="nickname" id="nickname" @isset($item)value="{{ $item->nickname }}"@endisset placeholder="输入昵称" autocomplete="off">
                        <small class="form-text text-muted">
                            平台唯一
                        </small>
                    </div>
                    <div class="form-group row">
                        <label for="name">姓名</label>
                        <input type="text" class="form-control" name="name" id="name" @isset($item)value="{{ $item->name }}"@endisset placeholder="输入名称">
                        <small class="form-text text-muted">
                            长度不小于 2 个字符，不大于 20 个字符
                        </small>
                    </div>
                    <div class="form-group row">
                        <label for="password">密码</label>
                        <input type="password" class="form-control" name="password" id="password" placeholder="密码" autocomplete="off">
                        <small class="form-text text-muted">
                            不修改请留空，长度不小于 6 个字符
                        </small>
                    </div>
                    <div class="form-group row">
                        <label for="password_confirmation">确认密码</label>
                        <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" placeholder="确认密码" autocomplete="off">
                        <small class="form-text text-muted">
                            长度不小于 6 个字符
                        </small>
                    </div>
                    <div class="form-group row">
                        <label for="avatar">头像</label>
                        @component('admin.components.single-image-upload-oss',['fieldName'=>'avatar','image'=>isset($item)?$item->avatar:null])
                        @endcomponent
                        <small class="text-muted">
                            宽高比 1:1，文件大小200KB 以内
                        </small>
                    </div>
                    <div class="form-group row">
                        <div class="icheck-success d-inline">
                            <input type="radio" id="sex_0" name="sex" value="0" @if(isset($item) && $item->sex == 0)checked=""@endif>
                            <label for="sex_0">
                                未知
                            </label>
                        </div>
                        <div class="icheck-danger d-inline">
                            <input type="radio" id="sex_1" name="sex" value="1" @if(isset($item) && $item->sex == 1)checked=""@endif>
                            <label for="sex_1">
                                男
                            </label>
                        </div>
                        <div class="icheck-danger d-inline">
                            <input type="radio" id="sex_2" name="sex" value="2" @if(isset($item) && $item->sex == 2)checked=""@endif>
                            <label for="sex_2">
                                女
                            </label>
                        </div>
                    </div>
                    <div>
                        <label>注册 IP</label>
                        @isset($item)
                        <p>{{ $item->ip_address }}</p>
                        @endisset
                    </div>
                    <div>
                        <label>注册地区</label>
                        @isset($item)
                        <p>{{ $item->country }} {{ $item->province }} {{ $item->city }}</p>
                        @endisset
                    </div>
                    <div>
                        <label>注册设备</label>
                        @isset($item)
                        <p>{{ $item->user_agent }}</p>
                        @endisset
                    </div>
                    <div>
                        <label>注册时间</label>
                        @isset($item)
                        <p>{{ $item->created_at }}</p>
                        @endisset
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
                                            window.location.href = "{{ route('admin.members.store') }}";
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
                                            window.location.href = "{{ route('admin.members.all') }}";
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
                email: {
                    required: true,
                    email: true,
                },
                name: {
                    required: true,
                    minlength: 2,
                    maxlength: 20
                },
                password: {
                    @if(!isset($item))
                    required: true,
                    @endif
                    minlength: 6
                },
                password_confirmation: {
                    equalTo: "#password"
                },
                sex: {
                    required: true
                }
            },
            messages: {
                email: {
                    required: "请输入Email地址",
                    email: "输入的Email格式不正确"
                },
                name: {
                    required: "请输入姓名",
                    minlength: "长度不少于2个字",
                    maxlength: "长度不大于2个字"
                },
                password: {
                    required: "请输入密码",
                    minlength: "密码长度不少于 6 位"
                },
                password_confirmation: {
                    equalTo: "两次输入的密码不正确"
                },
                sex: "请选择一个性别",
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
