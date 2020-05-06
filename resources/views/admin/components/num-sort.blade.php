<div class="form-group row">
    <label for="num_sort">排序</label>
    <input type="text" class="form-control" name="num_sort" id="num_sort" @isset($item)value="{{ $item->num_sort }}"@else value="500"@endisset placeholder="500">
</div>
