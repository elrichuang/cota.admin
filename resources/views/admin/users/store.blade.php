@extends('admin.layouts.default')

@section('breadcrumb')
    @component('admin.components.breadcrumb',['ability'=>$ability])
        <li class="breadcrumb-item active">管理员管理</li>
        <li class="breadcrumb-item"><a href="{{ route('admin.users.all') }}">管理员列表</a></li>
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
            <form id="userForm" role="form" action="@isset($user){{ route('api.admin.users.update',['user'=>$user]) }}@else{{ route('api.admin.users.store') }}@endisset">
                <div class="card-body">
                    <div class="form-group row">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" name="email" id="email" @isset($user)value="{{ $user->email }}"@endisset placeholder="输入Email" autocomplete="off">
                        <small class="form-text text-muted">
                            用于后台登录
                        </small>
                    </div>
                    <div class="form-group row">
                        <label for="password">密码</label>
                        <input type="password" class="form-control" name="password" id="password" placeholder="密码" autocomplete="off">
                        <small class="form-text text-muted">
                            长度不小于 6 个字符
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
                        <label for="name">名称</label>
                        <input type="text" class="form-control" name="name" id="name" @isset($user)value="{{ $user->name }}"@endisset placeholder="输入名称">
                        <small class="form-text text-muted">
                            长度不小于 2 个字符，不大于 20 个字符
                        </small>
                    </div>
                    <div class="form-group row">
                        <label for="avatar">头像</label>
                        @component('admin.components.single-image-upload-oss',['imageField'=>'avatar','image'=>isset($user)?$user->avatar:null])
                        @endcomponent
                        <small class="text-muted">
                            宽高比 1:1，文件大小200KB 以内
                        </small>
                    </div>
                    <div class="form-group row">
                        <div class="icheck-primary d-inline">
                            <input type="checkbox" value="1" @if(isset($user) && $user->super_admin)checked=""@endif id="super_admin" name="super_admin">
                            <label for="super_admin">
                                超级管理员
                            </label>
                            <small class="text-muted">
                                可以管理管理员账号，拥有所有权限，不受所属角色和权限约束
                            </small>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="icheck-success d-inline">
                            <input type="radio" id="status_activated" name="status" value="activated" @if(isset($user) && $user->status == 'activated')checked=""@endif>
                            <label for="status_activated">
                                已激活
                            </label>
                        </div>
                        <div class="icheck-danger d-inline">
                            <input type="radio" id="status_deactivated" name="status" value="deactivated" @if(isset($user) && $user->status == 'deactivated')checked=""@endif>
                            <label for="status_deactivated">
                                未激活
                            </label>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="roles_ids">角色</label>
                        <select id="roles_ids" name="roles_ids[]" class="select2" multiple="multiple" data-placeholder="请选择一个或多个角色" style="width: 100%">
                            @foreach($roles as $role)
                                @if(isset($user) && in_array($role->id,$user->roles()->allRelatedIds()->toArray()))
                                <option value="{{ $role->id }}" selected="selected">{{ $role->name }}</option>
                                @else
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group row">
                        <label for="introduction">
                            简介
                        </label>
                        <textarea id="introduction" name="introduction" class="form-control" rows="3" placeholder="请输入……">@isset($user){{ $user->introduction }}@endisset</textarea>
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
        var form = $('#userForm');

        $.validator.setDefaults({
            submitHandler: function () {
                // 验证通过
                $.ajax({
                    url:form.attr('action'),
                    @isset($user)
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
                                    @if(!isset($user))
                                    //添加
                                    cancel: {
                                        label: "继续添加",
                                        className: 'btn-primary',
                                        callback: function(){
                                            window.location.href = "{{ route('admin.users.store') }}";
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
                                            window.location.href = "{{ route('admin.users.all') }}";
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
                    @if(!isset($user))
                    required: true,
                    @endif
                    minlength: 6
                },
                password_confirmation: {
                    equalTo: "#password"
                },
                status: {
                    required: true
                },
                roles_ids: {
                    required: true
                },
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
                status: "请选择一个状态",
                roles_ids:"请选择一个角色"
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
