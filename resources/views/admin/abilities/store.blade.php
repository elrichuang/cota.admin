@extends('admin.layouts.default')

@section('breadcrumb')
    @component('admin.components.breadcrumb',['ability'=>$ability])
        <li class="breadcrumb-item active">权限管理</li>
        <li class="breadcrumb-item"><a href="{{ route('admin.abilities.all') }}">权限列表</a></li>
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
            <form id="myForm" role="form" action="@isset($item){{ route('api.admin.abilities.update',['ability'=>$item]) }}@else{{ route('api.admin.abilities.store') }}@endisset">
                <div class="card-body">
                    <div class="form-group row">
                        <div class="icheck-primary d-inline">
                            <input type="radio" id="type_view" name="type" value="view" @if(isset($item) && $item->type == 'view')checked=""@endif>
                            <label for="type_view">
                                视图
                            </label>
                        </div>
                        <div class="icheck-primary d-inline">
                            <input type="radio" id="type_api" name="type" value="api" @if(isset($item) && $item->type == 'api')checked=""@endif>
                            <label for="type_api">
                                接口
                            </label>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="parent_id">父级</label>
                        <select id="parent_id" name="parent_id" class="select2" data-placeholder="请选择一个父级" style="width: 100%">
                            @if(isset($item) && $item->parent_id == 0)
                                <option value="0" selected="selected">顶级</option>
                            @else
                                <option value="0">顶级</option>
                            @endif;
                            @foreach ($allAbilities['view'] as $abilityItem)
                                @if(isset($item) && $item->parent_id == $abilityItem->id)
                                <option value="{{ $abilityItem->id }}" selected="selected">{{ $abilityItem->name }}</option>
                                @else
                                <option value="{{ $abilityItem->id }}">{{ $abilityItem->name }}</option>
                                @endif
                                @if(count($abilityItem->children))
                                    @foreach ($abilityItem->children as $childAbility)
                                        @if(isset($item) && $item->parent_id == $childAbility->id)
                                        <option value="{{ $childAbility->id }}" selected="selected">&lfloor;{{ $childAbility->name }}</option>
                                        @else
                                        <option value="{{ $childAbility->id }}">&lfloor;{{ $childAbility->name }}</option>
                                        @endif
                                    @endforeach
                                @endif
                            @endforeach
                        </select>
                        <small class="form-text text-muted">
                            类型为接口时忽略这个设置
                        </small>
                    </div>
                    <div class="form-group row">
                        <label for="name">名称</label>
                        <input type="text" class="form-control" name="name" id="name" @isset($item)value="{{ $item->name }}"@endisset placeholder="输入名称">
                        <small class="form-text text-muted">
                            长度不小于 2 个字符，不大于 20 个字符
                        </small>
                    </div>
                    <div class="form-group row">
                        <label for="icon">图标</label>
                        <input type="text" class="form-control" name="icon" id="icon" @isset($item)value="{{ $item->icon }}"@endisset placeholder="输入图标名称">
                        <small class="form-text text-muted">
                            fa-开头，所有图标搜索：<a href="https://fontawesome.com/icons?d=gallery&m=free" target="_blank">fontawesome</a>，类型为接口时忽略这个设置
                        </small>
                    </div>
                    <div class="form-group row">
                        <label for="alias">路由名称</label>
                        <select id="alias" name="alias" class="select2" data-placeholder="请选择一个路由" style="width: 100%">
                            <option value=""></option>
                            @foreach($allRoutes as $routeItem)
                                @if(isset($item) && $item->alias == $routeItem->getName())
                                    <option value="{{ $routeItem->getName() }}" selected="selected">{{ $routeItem->getName() }}</option>
                                @else
                                    <option value="{{ $routeItem->getName() }}">{{ $routeItem->getName() }}</option>
                                @endif
                            @endforeach
                        </select>
                        <small class="form-text text-muted">
                            路由配置文件中设置的路由名称，如果是有子级项目请留空
                        </small>
                    </div>
                    <div class="form-group row">
                        <div class="icheck-primary d-inline">
                            <input type="checkbox" value="1" @if(isset($item) && $item->use_url)checked=""@endif id="use_url" name="use_url">
                            <label for="use_url">
                                是否使用链接
                            </label>
                            <small class="text-muted">
                                勾选后将不用路由
                            </small>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="url">链接</label>
                        <input type="text" class="form-control" name="url" id="url" @isset($item)value="{{ $item->url }}"@endisset placeholder="http://或https://">
                        <small class="form-text text-muted">
                            http://或https://开头的链接
                        </small>
                    </div>
                    <div class="form-group row">
                        <label for="remark">备注</label>
                        <input type="text" class="form-control" name="remark" id="remark" @isset($item)value="{{ $item->remark }}"@endisset placeholder="备注">
                        <small class="form-text text-muted">
                            一句话说明
                        </small>
                    </div>
                    <div class="form-group row">
                        <label for="num_sort">排序</label>
                        <input type="text" class="form-control" name="num_sort" id="num_sort" @isset($item)value="{{ $item->num_sort }}"@else value="500"@endisset>
                        <small class="form-text text-muted">
                            默认 500，越小越靠前，整数
                        </small>
                    </div>
                    <div class="form-group row">
                        <div class="icheck-primary d-inline">
                            <input type="checkbox" value="1" @if(isset($item) && $item->show_on_menu)checked=""@endif id="show_on_menu" name="show_on_menu">
                            <label for="show_on_menu">
                                显示在后台菜单
                            </label>
                            <small class="text-muted">
                                是否在管理后台菜单中显示，类型为接口时忽略这个设置
                            </small>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="icheck-success d-inline">
                            <input type="radio" id="status_activated" name="status" value="activated" @if(isset($item) && $item->status == 'activated')checked=""@endif>
                            <label for="status_activated">
                                已激活
                            </label>
                        </div>
                        <div class="icheck-danger d-inline">
                            <input type="radio" id="status_deactivated" name="status" value="deactivated" @if(isset($item) && $item->status == 'deactivated')checked=""@endif>
                            <label for="status_deactivated">
                                未激活
                            </label>
                        </div>
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
                                            window.location.href = "{{ route('admin.abilities.store') }}";
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
                                            window.location.href = "{{ route('admin.abilities.all') }}";
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
                type: {
                    required: true,
                },
                name: {
                    required: true,
                    minlength: 2,
                    maxlength: 20
                },
                remark: {
                    maxlength: 50
                },
                url: {
                    required: true,
                },
                num_sort: {
                    required: true,
                    digits: true,
                },
                status: {
                    required: true
                },
            },
            messages: {
                type: {
                    required: "请输入选择类型",
                },
                name: {
                    required: "请输入姓名",
                    minlength: "长度不少于2个字",
                    maxlength: "长度不大于20个字",
                },
                remark: {
                    maxlength: "长度不大于50个字",
                },
                url: {
                    url: "链接格式不正确",
                },
                num_sort: {
                    required: "请输入排序",
                    digits: "请输入整数"
                },
                status: "请选择一个状态",
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
