$(document).ready(function() {

    $('.seoModal').click(function() {
        $.ajax({
            url: '../../views/seo/get_seo.php',
            type: 'post',
            data: {page_id: $(this).data('id')},
            async: false,
            success: function(data) {
                $('.SEOMODAL .modal-body').html(data);
                $('.SEOMODAL').modal();
                makeTag();
            }
        });
        $('#seosave input[name="page_id"]').remove();
        var id = $(this).data('id');
        $('#seosave').append('<input type="hidden" name="page_id" id="page_id" value="' + id + '">');
    });
    $(document).on('submit', '#seosave', function(e) {
        e.preventDefault();
        var formData = $(this).serialize();
        $.ajax(
                {
                    type: 'post',
                    url: '../../views/seo/SaveSeoAjax.php',
                    data: formData,
                    dataType: 'json', async: false,
                    success: function(data)
                    {
                        waitingDialog.hide();
                        swal({
                            title: "",
                            text: 'success',
                            type: "success",
                            showConfirmButton: false
                            , showConfirmButton: false, timer: 2000
                        });
                        $('.SEOMODAL').modal('toggle');

                    }
                });
    });



    $('.dublicate-button').click(function() {
        $table = $(this).data('table');
        $id = $(this).data('id');
        $redirect = $(this).data('redirect');
        $cols = $(this).data('cols');
        $ajax_file = $(this).data('ajax');
        $module = $(this).data('module');
        if ($ajax_file !== "") {
            $url = $ajax_file;
        } else {
            $url = '../../views/ajax/SaveDublicatedRecord.php';
        }
        
        $.ajax({
            url: $url,
            data: {table: $table, id: $id, redirect: $redirect, cols: $cols, module: $module},
            type: 'post', dataType: 'json',
            success: function(data) {
                waitingDialog.hide();

                swal({
                    title: "",
                    text: 'success',
                    type: "success",
                    showConfirmButton: false
                    , showConfirmButton: false, timer: 2000
                });
                setTimeout(function() {
                    window.location = $redirect + "?id=" + data;
                }, 2000);

            }
        });
    });
});





