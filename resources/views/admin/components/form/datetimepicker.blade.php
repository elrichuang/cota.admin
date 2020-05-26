{{--
label ：标签
fieldName ：字段名
--}}
<div class="form-group row">
    <label for="{{ $fieldName }}">{{ $label }}</label>
    <div class="input-group date" id="{{ $fieldName }}" data-target-input="nearest">
        <input type="text" class="form-control datetimepicker-input" value="@isset($item){{ $item->$fieldName }}@endisset" name="{{ $fieldName }}" data-target="#{{ $fieldName }}"/>
        <div class="input-group-append" data-target="#{{ $fieldName }}" data-toggle="datetimepicker">
            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
        </div>
    </div>
</div>
<script>
    $(function () {
        $('#{{ $fieldName }}').datetimepicker();
    });
</script>
