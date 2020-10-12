@extends('admin.layouts.default')

@section('breadcrumb')
    @component('admin.components.breadcrumb',['ability'=>$ability])
        <li class="breadcrumb-item active">角色管理</li>
        <li class="breadcrumb-item"><a href="{{ route('admin.roles.all') }}">角色列表</a></li>
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
            <form id="userForm" role="form" action="@isset($item){{ route('api.admin.roles.update',['role'=>$item]) }}@else{{ route('api.admin.roles.store') }}@endisset">
                <div class="card-body">
                    <div class="form-group row">
                        <label for="name">名称</label>
                        <input type="text" class="form-control" name="name" id="name" @isset($item)value="{{ $item->name }}"@endisset placeholder="输入名称">
                        <small class="form-text text-muted">
                            长度不小于 2 个字符，不大于 20 个字符
                        </small>
                    </div>
                    <div class="form-group row">
                        <label for="view_abilities_ids">视图权限</label>
                    </div>
                    <div class="form-group row">
                        <ul id="treeMenu" class="ztree"></ul>
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
        var setting = {
            check: {
                enable: true
            },
            data: {
                simpleData: {
                    enable: true
                }
            }
        };

        var zNodes = @json($treeData, JSON_PRETTY_PRINT);
        $(document).ready(function(){
            $.fn.zTree.init($("#treeMenu"), setting, zNodes);
        });

        // 表单提交
        var form = $('#userForm');

        $.validator.setDefaults({
            submitHandler: function () {
                var zTreeObj = $.fn.zTree.getZTreeObj("treeMenu");
                var checkedNodes = zTreeObj.getCheckedNodes();
                var view_abilities_ids = [];
                for (var i = 0; i < checkedNodes.length; i++) {
                    var node = checkedNodes[i];
                    view_abilities_ids.push(node.id);
                }

                // 验证通过
                $.ajax({
                    url:form.attr('action'),
                    @isset($item)
                    type:'PATCH',
                    @else
                    type:'POST',
                    @endisset
                    data:form.serialize()+'&view_abilities_ids='+JSON.stringify(view_abilities_ids),
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
                                            window.location.href = "{{ route('admin.roles.store') }}";
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
                                            window.location.href = "{{ route('admin.roles.all') }}";
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
                name: {
                    required: true,
                    minlength: 2,
                    maxlength: 20
                },
                view_abilities_ids: {
                    required: true
                }
            },
            messages: {
                name: {
                    required: "请输入姓名",
                    minlength: "长度不少于2个字",
                    maxlength: "长度不大于2个字"
                },
                view_abilities_ids:"请选择至少一个视图权限"
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
