@extends('admin.layouts.default')

@section('breadcrumb')
    @component('admin.components.breadcrumb',['ability'=>$ability])
        <li class="breadcrumb-item active">文章管理</li>
        <li class="breadcrumb-item"><a href="{{ route('admin.articles.all') }}">文章列表</a></li>
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
            <form id="userForm" role="form" action="@isset($item){{ route('api.admin.articles.update',['article'=>$item]) }}@else{{ route('api.admin.articles.store') }}@endisset">
                <div class="card-body">
                    <div class="form-group row">
                        <label for="title">标题</label>
                        <input type="text" class="form-control" name="title" id="title" @isset($item)value="{{ $item->title }}"@endisset placeholder="输入标题">
                        <small class="form-text text-muted">
                            长度不小于 2 个字符
                        </small>
                    </div>
                    <div class="form-group row">
                        <label for="sub_title">副标题</label>
                        <input type="text" class="form-control" name="sub_title" id="sub_title" @isset($item)value="{{ $item->sub_title }}"@endisset placeholder="输入副标题">
                    </div>
                    <div class="form-group row">
                        <label for="author">作者</label>
                        <input type="text" class="form-control" name="author" id="author" @isset($item)value="{{ $item->author }}"@endisset placeholder="输入作者">
                    </div>
                    <div class="form-group row">
                        <label for="category_id">文章分类</label>
                        <select id="category_id" name="category_id" class="select2" style="width: 100%" data-placeholder="请选择分类">
                            <option value=""></option>
                            @foreach($allCategories as $categoryItem)
                                @if(isset($item) && $categoryItem->id == $item->category_id)
                                    <option value="{{ $categoryItem->id }}" selected="selected">{{ $categoryItem->title }}</option>
                                @else
                                    <option value="{{ $categoryItem->id }}">{{ $categoryItem->title }}</option>
                                @endif
                                @if(count($categoryItem->children))
                                    @foreach ($categoryItem->children as $childAbility)
                                        @if(isset($item) && $childAbility->id == $item->category_id)
                                            <option value="{{ $childAbility->id }}" selected="selected">&lfloor;{{ $childAbility->title }}</option>
                                        @else
                                            <option value="{{ $childAbility->id }}">&lfloor;{{ $childAbility->title }}</option>
                                        @endif
                                    @endforeach
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group row">
                        <label for="thumb">缩略图</label>
                        @component('admin.components.single-image-upload-oss',['fieldName'=>'thumb','image'=>isset($item)?$item->thumb:null])
                        @endcomponent
                        <small class="text-muted">
                            文件大小200KB 以内
                        </small>
                    </div>
                    <div class="form-group row">
                        <label for="num_view">浏览数</label>
                        <input type="text" class="form-control" name="num_view" id="num_view" @isset($item)value="{{ $item->num_view }}"@endisset placeholder="0">
                    </div>
                    <div class="form-group row">
                        <label for="num_like">点赞数</label>
                        <input type="text" class="form-control" name="num_like" id="num_like" @isset($item)value="{{ $item->num_like }}"@endisset placeholder="0">
                    </div>
                    <div class="form-group row">
                        <label for="num_sort">排序</label>
                        <input type="text" class="form-control" name="num_sort" id="num_sort" @isset($item)value="{{ $item->num_sort }}"@else value="500"@endisset placeholder="500">
                    </div>
                    <div class="form-group row">
                        <div class="icheck-primary d-inline">
                            <input type="checkbox" value="1" @if(isset($item) && $item->on_top_at)checked=""@endif id="on_top" name="on_top">
                            <label for="on_top">
                                是否置顶
                            </label>
                        </div>
                        <div class="icheck-primary d-inline">
                            <input type="checkbox" value="1" @if(isset($item) && $item->recommend_at)checked=""@endif id="recommend" name="recommend">
                            <label for="recommend">
                                是否推荐
                            </label>
                        </div>
                    </div>
                    @isset($item)
                        @component('admin.components.form.datetimepicker',['label'=>'发布时间','fieldName' => 'published_at','item'=>$item])
                        @endcomponent
                    @else
                        @component('admin.components.form.datetimepicker',['label'=>'发布时间','fieldName' => 'published_at'])
                        @endcomponent
                    @endisset
                    <div class="form-group row">
                        <label for="summary">摘要</label>
                        <textarea class="form-control" rows="5" name="summary" id="summary" placeholder="请输入摘要">@isset($item){{ $item->summary }}@endisset</textarea>
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
                                            window.location.href = "{{ route('admin.articles.store') }}";
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
                                            window.location.href = "{{ route('admin.articles.all') }}";
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
                name: {
                    required: true,
                    minlength: 2
                },
                category_id: {
                    required: true
                },
                content: {
                    required: true
                }
            },
            messages: {
                name: {
                    required: "请输入姓名",
                    minlength: "长度不少于2个字"
                },
                category_id:"请选择一个分类",
                content: "请输入内容"
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
