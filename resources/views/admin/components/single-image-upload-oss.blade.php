{{--
fieldName ：图片字段名
image ：现有图片路径，为null即为未有图片
--}}
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" id="singleImage{{ $fieldName }}">
        <label class="custom-file-label" for="singleImage{{ $fieldName }}">点击选择图片</label>
    </div>
    <div class="input-group-append">
        <input type="hidden" id="image{{ $fieldName }}" @isset($image)value="{{ $image }}"@endisset name="{{ $fieldName }}">
        <button class="btn btn-danger" id="btnImageTrash{{ $fieldName }}"><i class="fas fa-trash"></i> 清除图片</button>
        <button class="btn btn-primary" id="btnUpload{{ $fieldName }}"><i class="fas fa-upload"></i> 点击上传</button>
    </div>
</div>
<div id="previewImage{{ $fieldName }}" class="input-group">
    @isset($image)
    <img src="{{ $image }}" class="img-thumbnail img-md">
    @else
    <img src="{{ config('app.default_avatar') }}" class="img-thumbnail img-md">
    @endisset
</div>
<script>
    // 清除图片
    $('#btnImageTrash{{ $fieldName }}').on('click', function(e) {
        e.preventDefault();
        bootbox.confirm("真的要清除图片吗？", function(result) {
            if(result)
            {
                var imgUrl = "{{ config('app.default_avatar') }}";
                var img = new Image();
                img.src = imgUrl;
                img.className = "img-thumbnail img-md";
                img.onload = function () {
                    $("#previewImage{{ $fieldName }}").empty().append(img);
                };

                $('#image{{ $fieldName }}').val(imgUrl);
            }
        });
    });

    // 图片压缩上传
    $('#btnUpload{{ $fieldName }}').on('click', function(e) {
        e.preventDefault();

        var file = $('#singleImage{{ $fieldName }}').get(0).files[0]; //获取图片资源
        var filename = file.name;

        var pos = filename.lastIndexOf(".");
        var suffix = '';
        if(pos !== -1) {
            suffix = filename.substring(pos);
        }

        // 只选择图片文件
        if (!file.type.match('image.*')) {
            bootbox.alert('只能选择图片');
        }

        var loadingDialog = bootbox.dialog({
            message: '<i class="fa fa-spin fa-spinner"></i> 正在上传，请稍后……',
            closeButton: false
        });

        // LocalResizeIMG写法：
        lrz(file, {width: 800,quality:0.9})
            .then(function (rst) {
                // OSS要求把上传文件放到最后一项，但是用LocalResizeIMG输出的FormData，就只能放在
                // 第一项，所以这里要自己new个出来
                var ossData = new FormData();
                // 先请求授权，然后回调
                $.getJSON('{{ route('api.admin.images.ossPolicy') }}', function (result) { //签名用的PHP
                    if(!result.success)
                    {
                        loadingDialog.modal('hide');
                        bootbox.alert(result.message);
                    }else
                    {
                        var timestamp = $.md5(new Date().getTime());
                        var ossFilename = timestamp + suffix;
                        var imageUrl = result.data.imgHost + '/'+ result.data.dir + ossFilename;
                        // 添加签名信息
                        ossData.append('OSSAccessKeyId', result.data.accessId);
                        ossData.append('policy', result.data.policy);
                        ossData.append('Signature', result.data.signature);
                        ossData.append('key', result.data.dir + ossFilename);
                        // 添加文件
                        ossData.append('file', rst.file);

                        $.ajax({
                            url: result.data.host,
                            data: ossData,
                            processData: false,
                            contentType: false,
                            type: 'POST'
                        }).done(function(){
                            loadingDialog.find('.bootbox-body').html('完成！');
                            // 成功后显示图片预览
                            var img = new Image();
                            img.src = rst.base64;
                            img.className = "img-thumbnail img-md";
                            img.onload = function () {
                                $("#previewImage{{ $fieldName }}").empty().append(img);
                            };

                            $('#image{{ $fieldName }}').val(imageUrl);

                            loadingDialog.modal('hide');
                        });
                    }
                });
                return rst;
            })
            .catch(function (err) {
                // 万一出错了，这里可以捕捉到错误信息
                // 而且以上的then都不会执行
                loadingDialog.modal('hide');
                bootbox.alert('ERROR:' + err);
            })
            .always(function () {
                // 不管是成功失败，这里都会执行
            });
    });
</script>
