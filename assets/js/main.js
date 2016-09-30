$(document).ready(function (e) {

    $('#fileupload').on('click', function () {

        var file_data = $('#upload_loadsheet').prop('files')[0];
        var form_data = new FormData();
        form_data.append('file', file_data);
        $.ajax({
            url: 'tarantool/uploadfile',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            success: function (response) {
                console.log(response);
                var resObj = $.parseJSON(response);
                $('#msg').html(resObj.statusMsg);
                if(resObj.status == 'success') {
                    $('#uploaded_loadsheet').html(resObj.data);
                } else if(resObj.status == 'error') {
                    $('#uploaded_loadsheet').html('');
                }
                $('#upload_loadsheet').val('');
            },
            error: function (response) {
                $('#msg').html(response);
            }
        });

    });

});