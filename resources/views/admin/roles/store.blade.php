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
                        <select id="view_abilities_ids" name="view_abilities_ids[]" class="form-control" multiple="multiple" size="10" style="width: 100%">
                            @foreach($allAbilities['view'] as $abilityItem)
                                @if(isset($item) && in_array($abilityItem->id,$item->abilities()->allRelatedIds()->toArray()))
                                    <option value="{{ $abilityItem->id }}" selected="selected">{{ $abilityItem->name }}</option>
                                @else
                                    <option value="{{ $abilityItem->id }}">{{ $abilityItem->name }}</option>
                                @endif
                                @if(count($abilityItem->children))
                                    @foreach ($abilityItem->children as $childAbility)
                                        @if(isset($item) && in_array($childAbility->id,$item->abilities()->allRelatedIds()->toArray()))
                                            <option value="{{ $childAbility->id }}" selected="selected">&lfloor;{{ $childAbility->name }}</option>
                                        @else
                                            <option value="{{ $childAbility->id }}">&lfloor;{{ $childAbility->name }}</option>
                                        @endif
                                    @endforeach
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group row">
                        <label for="api_abilities_ids">接口权限</label>
                        <select id="api_abilities_ids" name="api_abilities_ids[]" class="select2" multiple="multiple" data-placeholder="请选择一个或多个权限" style="width: 100%">
                            @foreach($allAbilities['api'] as $abilityItem)
                                @if(isset($item) && in_array($abilityItem->id,$item->abilities()->allRelatedIds()->toArray()))
                                    <option value="{{ $abilityItem->id }}" selected="selected">{{ $abilityItem->name }}</option>
                                @else
                                    <option value="{{ $abilityItem->id }}">{{ $abilityItem->name }}</option>
                                @endif
                            @endforeach
                        </select>
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
