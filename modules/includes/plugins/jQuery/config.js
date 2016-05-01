function notificationMessage(type, message) {

    if (type !== "" && type !== undefined) {

        var typeText = "";
        var messageText = "";
        var icon = "";
        if (type === true) {
            typeText = "success";
            icon = "glyphicon glyphicon-ok";
        } else {
            typeText = "danger";
            icon = "glyphicon glyphicon-alert";
        }
        if (message !== "") {
            if (type === true) {
                messageText = "Saved successfully";
            } else {
                messageText = "Not saved successfully";
            }
        }

        $.notify({
            icon: icon,
            message: messageText
        }, {
            type: typeText,
            delay: 4000,
            offset: {y: 60},
            placement: {
                from: 'top',
                align: 'center'

            }, animate: {
                enter: "animated fadeInDown",
                exit: "animated fadeOutUp"
            }
        });


    }

}
$(document).ready(function() {
    $("#sortable").sortable({
        connectWith: "tr",
        cursor: "move",
        forcePlaceholderSize: true,
        opacity: 0.4,
        stop: function(event, ui) {
            var orderChanges = "";
            var sortorder = "";
            var itemorder = 0;
            $("#sortable tr").each(function() {
                var columnId = $(this).attr("id");
                itemorder++;
                if (columnId != ordIds[itemorder]) {
                    orderChanges += columnId + "," + ordIds[itemorder] + "|";
                }
                ordIds[itemorder] = columnId;
            });
            //alert(orderChanges);
            if (orderChanges != "") {
                $("tr").css("cursor", "wait");
                $.post("../ajax/order.php", {ot: order_table, of: order_filed, oi: order_id, ids: orderChanges}, function(data) {
                    alert(data);
                    $("tr").css("cursor", "default");
                    $("#info").html(data);
                });
            }
        }
    });
    $(".sidebar-menu li.active").parents("li")
            .map(function() {
                $(this).addClass('active');
            });


    /* $("[data-toggle='tooltip']").tooltip();*/
    $('.chosen-select').chosen();
    $('.iconpicker').iconpicker();
    $('.TagsInput').tagit();
    $(".table-fileds .TagsInput").hide();
    $(".table-fileds ul.tagit").hide();
    $var_name_field_puls = $(".table-fileds .TagsInput").attr('name');
    $val_field_plus = $(".table-fileds .TagsInput").val();

    $vv = $(".table-fileds [name='type']").val();
    if ($vv === "DynamicSelect") {

        $(".table-fileds ul.tagit").hide();
        $(".table-fileds .TagsInput").attr('name', "");
        $.ajax({url: "../ajax/getModuleDropDown.php"
            , type: 'post'
            , data: {field: $var_name_field_puls, class: "er-daw-qw", val: $val_field_plus}
            , success: function(data) {

                $(data).insertBefore(".table-fileds .TagsInput");
            }});
    }
    else if ($vv === 'radio' || $vv === 'checkbox' || $vv === 'select') {
        $(".table-fileds ul.tagit").show();
        $(".table-fileds .er-daw-qw").remove();
        $(".table-fileds .TagsInput").attr('name', $var_name_field_puls);

    } else {
        $(".table-fileds ul.tagit").hide();
        $(".table-fileds .er-daw-qw").remove();
        $(".table-fileds .TagsInput").attr('name', "");
    }

    $(".table-fileds [name='type']").change(function() {
        if ($(this).val() === "DynamicSelect") {

            $(".table-fileds ul.tagit").hide();
            $(".table-fileds .TagsInput").attr('name', "");
            $.ajax({url: "../ajax/getModuleDropDown.php"
                , type: 'post'
                , data: {field: $var_name_field_puls, class: "er-daw-qw"}
                , success: function(data) {
                    $(data).insertBefore(".table-fileds ul.tagit");
                }});
        }
        else if ($(this).val() === 'radio' || $(this).val() === 'checkbox' || $(this).val() === 'select') {
            $(".table-fileds ul.tagit").show();
            $(".table-fileds .er-daw-qw").remove();
            $(".table-fileds .TagsInput").attr('name', $var_name_field_puls);

        } else {
            $(".table-fileds ul.tagit").hide();
            $(".table-fileds .er-daw-qw").remove();
            $(".table-fileds .TagsInput").attr('name', "");
        }
    });
    
    $('#SelectAll').change(function() {
        if ($(this).prop('checked')) {
            $('input[name="DeleteRow[]"]').each(function() {
                $(this).prop('checked', true);
            });
        } else {
            $('input[name="DeleteRow[]"]').each(function() {
                $(this).prop('checked', false);
            });
        }
    });
    $('#SelectAll').on('ifChecked', function() {
        $('input[name="DeleteRow[]"]').iCheck('check');
    });
     $('#SelectAll').on('ifUnchecked', function() {
        $('input[name="DeleteRow[]"]').iCheck('uncheck');
    });
    $('#AskDelete').click(function() {
        $('#DeleteQuestionModal').modal();
    });
    $('#YesDelete').click(function() {


        $('form[name="TableForm"]').append('<input type="hidden" name="action" value="Delete">');
        $('form[name="TableForm"]').submit();
    });
    $('#NoDelete').click(function() {
        $('#DeleteQuestionModal').modal('hide');
    });
    $('.plus-add').click(function() {
        $this = $(this);
        if ($(this).prev().css('display') === 'none')
        {
            $(this).prev().show();
            $(this).html('<span class="fa fa-save"></span>');
        } else {
            if ($(this).prev().val() !== "") {
                $.ajax({url: '../ajax/addItem.php', data: {table: $(this).prev().data('table'), value: $(this).prev().val(), field: $(this).prev().data('field')}, success: function(data) {
                        $("[name='" + $this.prev().data('namef') + "']").append('<option selected value="' + data + '">' + $this.prev().val() + '</option>');
                        $this.prev().hide();
                        $this.prev().val('');
                        $this.html('<span class="fa fa-plus"></span>');
                    }});
            } else {
                $(this).prev().hide();
                $this.prev().val('');
                $(this).html('<span class="fa fa-plus"></span>');
            }

        }

    });
    $(".datepicker").datepicker({
        showInputs: false, format: 'yyyy-mm-dd'
    });

    $(".timepicker").timepicker({
        showInputs: false
    });

    //$("#sortable").sortable();



    $('a[data-switcher="SwitcherV"]').click(function() {

        $th = $(this);
        $.ajax({
            url: '../ajax/active.php',
            type: 'post',
            data: {table: $(this).attr('data-table'), value: $(this).attr('data-val'), col: $(this).attr('data-col'), id: $(this).attr('data-id'), fid: $(this).attr('data-fid')},
            dataType: 'json',
            success: function(data) {

                $th.attr('data-val', data[0]);
                $th.attr('class', data[2]);
                $th.children('i').attr('class', data[1]);
            }
        });
    });
});



function checkBoxStyle(){
       $('input').iCheck({
        checkboxClass: 'icheckbox_square-red',
        radioClass: 'iradio_square-red',
        increaseArea: '20%' // optional
    });
}
function makeTag(){
    $('.TagsInput').tagit();
}


$(function() {
 checkBoxStyle();
});