@extends('admin.layouts.default')

@section('breadcrumb')
    @component('admin.components.breadcrumb',['ability'=>$ability])
        <li class="breadcrumb-item active">文章管理</li>
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
                            <select id="category_id" name="category_id" class="select2 form-control-sm" data-allow-clear="true" data-placeholder="文章分类" style="width: 200px">
                                <option value=""></option>
                                @foreach($allCategories as $categoryItem)
                                    @if($categoryItem->id == request()->get('category_id'))
                                        <option value="{{ $categoryItem->id }}" selected="selected">{{ $categoryItem->title }}</option>
                                    @else
                                        <option value="{{ $categoryItem->id }}">{{ $categoryItem->title }}</option>
                                    @endif
                                    @if(count($categoryItem->children))
                                        @foreach ($categoryItem->children as $childAbility)
                                            @if($childAbility->id == request()->get('category_id'))
                                                <option value="{{ $childAbility->id }}" selected="selected">&lfloor;{{ $childAbility->title }}</option>
                                            @else
                                                <option value="{{ $childAbility->id }}">&lfloor;{{ $childAbility->title }}</option>
                                            @endif
                                        @endforeach
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
                            <th>标题</th>
                            <th>分类</th>
                            <th>作者</th>
                            <th width="80">缩略图</th>
                            <th>数据</th>
                            <th width="50">排序</th>
                            <th width="50">置顶</th>
                            <th width="50">推荐</th>
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
                            <td>{{ $item->title }}</td>
                            <td>
                                @if($item->category)
                                    {{ $item->category->title }}
                                @endif
                            </td>
                            <td>{{ $item->author }}</td>
                            <td><img src="{{ $item->thumb }}" class="img-thumbnail img-md" alt="{{ $item->name }}"></td>
                            <td>
                                <p>浏览数：{{ $item->num_view }}</p>
                                <p>点赞数：{{ $item->num_like }}</p>
                            </td>
                            <td>{{ $item->num_sort }}</td>
                            <td>
                                @if($item->on_top_at)
                                    <i class="fas fa-check text-success"></i>
                                @else
                                    <i class="fas fa-times text-danger"></i>
                                @endif
                            </td>
                            <td>
                                @if($item->recommend_at)
                                    <i class="fas fa-check text-success"></i>
                                @else
                                    <i class="fas fa-times text-danger"></i>
                                @endif
                            </td>
                            <td>{{ $item->created_at }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.articles.profile', ['article'=>$item]) }}" class="btn btn-primary"><i class="fas fa-edit"></i></a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
            @component('admin.components.pagination', ['items'=>$items,'isCanDelete' => true,'pathDel'=>route('api.admin.articles.destroyMany')])
            @endcomponent
            <!-- /.card-footer-->
        </div>
        <!-- /.card -->

    </section>
    <!-- /.content -->
@endsection
