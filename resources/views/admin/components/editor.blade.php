{{--
fieldName ：编辑器字段名
article ：文章
toolbar ：工具栏 ['fullscreen', 'source', 'undo', 'redo', 'bold']

表单验证示例：
form.submit(function() {
    UE.getEditor('content').sync();
}).validate({
    ...验证规则等
});
--}}
<script type="text/plain" id="{{ $fieldName }}" name="{{ $fieldName }}">
    @isset($article){!! $article->$fieldName !!}@endisset
</script>

<script>
    $(function () {
        UE.getEditor('{{ $fieldName }}', {
            @isset($toolbar)
            toolbars: @json($toolbar, JSON_PRETTY_PRINT),
            @endisset
            initialFrameHeight: 500,
        }).setPlaceholder('请输入内容');
    });
</script>
