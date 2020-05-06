{{--
isCanSelect ：是否可以多选
isCanDelete ：是否可以删除
--}}
<div class="card-footer clearfix">
    <div class="row">
        <div class="col-md-6">
            <span>Page <span class="badge badge-info">{{ $items->currentPage() }} / {{ ceil($items->total()/$items->perPage()) }}</span></span>
            <span>共 <span class="badge badge-info">{{ $items->total() }}</span>条记录</span>
            @if((isset($isCanSelect) && $isCanSelect)||(isset($isCanDelete) && $isCanDelete))
            <button type="button" id="selectAll" class="btn btn-default btn-sm">全选</button>
            <button type="button" id="selectNone" class="btn btn-default btn-sm">取消选择</button>
            <button type="button" id="selectInv" class="btn btn-default btn-sm">反选</button>
            <script type="text/javascript">
                var selectToDo = $("input[name='check']").selectToDo({
                    "selectAllButton"    : $("#selectAll"),
                    "selectNoneButton"   : $("#selectNone"),
                    "selectInvertButton" : $("#selectInv")
                });
            </script>
            @endif
            @if(isset($isCanDelete) && $isCanDelete)
            <button type="button" id="actionButton" class="btn btn-danger btn-sm">删除</button>
            <script>
                $("#actionButton").bind("click",function(){
                    if(selectToDo.result())
                    {
                        bootbox.confirm("真的要删除该项目吗？", function(result) {
                            if(result)
                            {
                                $.post('{{ $pathDel }}', {'ids':selectToDo.result()}, function(result) {
                                    // ... Process the result ...
                                    var success = result.success;
                                    if(!success)
                                    {
                                        bootbox.alert(result.msg);
                                    }else
                                    {
                                        bootbox.alert("删除成功！",function() {
                                            selectToDo.selectNone();
                                            window.location.reload();
                                        });
                                    }
                                }, 'json');
                            }
                        });
                    }
                });
            </script>
            @endif
        </div>
        <div class="col-md-6">
            <ul class="pagination pagination-sm m-0 float-right">
                {{ $items->links() }}
            </ul>
        </div>
    </div>
</div>
