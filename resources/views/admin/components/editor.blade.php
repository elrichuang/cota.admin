{{--
fieldName ：编辑器字段名

表单验证示例：
form.submit(function() {
    UE.getEditor('content').sync();
}).validate({
    ...验证规则等
});
--}}
<script type="text/plain" id="{{ $fieldName }}" name="{{ $fieldName }}">
    @isset($item){!! $item->content !!}@endisset
</script>

<script>
    $(function () {
        UE.getEditor('{{ $fieldName }}', {
            initialFrameHeight: 500,
        }).setPlaceholder('请输入内容');
    });
</script>
