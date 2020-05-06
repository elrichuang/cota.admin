@extends('admin.layouts.default')

@section('breadcrumb')
    @component('admin.components.breadcrumb',['ability'=>$ability])
        <li class="breadcrumb-item active">文章管理</li>
        <li class="breadcrumb-item"><a href="{{ route('admin.article_categories.all') }}">文章分类列表</a></li>
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
            <form id="myForm" role="form" action="@isset($item){{ route('api.admin.article_categories.update',['article_category'=>$item]) }}@else{{ route('api.admin.article_categories.store') }}@endisset">
                <div class="card-body">
                    <div class="form-group row">
                        <label for="parent_id">父级</label>
                        <select id="parent_id" name="parent_id" class="select2" data-placeholder="请选择一个父级" style="width: 100%">
                            @if(isset($item) && $item->parent_id == 0)
                                <option value="0" selected="selected">顶级</option>
                            @else
                                <option value="0">顶级</option>
                            @endif;
                            @foreach ($allCategories as $categoryItem)
                                @if(isset($item) && $item->parent_id == $categoryItem->id)
                                    <option value="{{ $categoryItem->id }}" selected="selected">{{ $categoryItem->title }}</option>
                                @else
                                    <option value="{{ $categoryItem->id }}">{{ $categoryItem->title }}</option>
                                @endif
                                @if(count($categoryItem->children))
                                    @foreach ($categoryItem->children as $childCategory)
                                        @if(isset($item) && $item->parent_id == $childCategory->id)
                                            <option value="{{ $childCategory->id }}" selected="selected">&lfloor;{{ $childCategory->title }}</option>
                                        @else
                                            <option value="{{ $childCategory->id }}">&lfloor;{{ $childCategory->title }}</option>
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
                        <label for="title">标题</label>
                        <input type="text" class="form-control" name="title" id="title" @isset($item)value="{{ $item->title }}"@endisset placeholder="输入标题">
                        <small class="form-text text-muted">
                            页面标题，长度不小于 2 个字符
                        </small>
                    </div>
                    <div class="form-group row">
                        <label for="num_sort">排序</label>
                        <input type="text" class="form-control" name="num_sort" id="num_sort" @isset($item)value="{{ $item->num_sort }}"@else value="500"@endisset>
                        <small class="form-text text-muted">
                            默认 500，越小越靠前，整数
                        </small>
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
                                                window.location.href = "{{ route('admin.article_categories.store') }}";
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
                                                window.location.href = "{{ route('admin.article_categories.all') }}";
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
                    title: {
                        required: true,
                        minlength: 2,
                    },
                    num_sort: {
                        required: true,
                        digits: true,
                    }
                },
                messages: {
                    title: {
                        required: "请输入标题",
                        minlength: "长度不少于2个字",
                    },
                    num_sort: {
                        required: "请输入排序",
                        digits: "请输入整数"
                    }
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
