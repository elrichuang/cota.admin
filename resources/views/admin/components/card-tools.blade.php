<form class="form-inline" role="form" method="get">
    {{ $slot }}
    <div class="input-group input-group-sm" style="width: 150px;">
        <input type="text" name="q" class="form-control float-right" value="{{ request()->get('q') }}" placeholder="搜索">
        <div class="input-group-append">
            <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
        </div>
    </div>
</form>
