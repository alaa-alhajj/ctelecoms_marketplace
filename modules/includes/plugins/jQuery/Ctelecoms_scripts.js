$(document).ready(function() {
    var pathname = window.location.pathname;
    arr = pathname.split('/');
    urlWindow = arr[arr.length - 1];

    /* Category scripts*/

    $("body").on('click', '.addCategoryFeature', function() {
        $title = $('.catFeatures input').val();
        $this = $('.catFeatures input');
        $cat_id = $(this).data('cat');
        $thisButton = $(this);

        if ($title !== "") {
            $action = "Insert";


            $this.attr('readonly', true);
            $this.prop('readonly', true);
            $this.parent().append("<div class='center-text spin-waiting'><i class='fa fa-spinner fa-spin spinner-style' ></i></div>");
            $this.attr("placeholder", 'Entering New Feature');
            $(this).addClass('stop-button');

            $.ajax({
                url: "AddFeatureAjax.php",
                type: 'post',
                data: {title: $title, cat: $cat_id, action: $action},
                dataType: 'json',
                success: function(data) {

                    $('#TableCategoryFeatures tbody').append(data);
                    $this.val('');
                    $this.attr('readonly', false);
                    $this.prop('readonly', false);
                    $this.parent().find(".spin-waiting").remove();
                    $this.attr("placeholder", 'Enter Feature');
                    $thisButton.removeClass('stop-button');
                  
                    location.reload();
              
                }
            });
        }
    });





    $var_name_field_puls = 'plus';
    $(".table-fileds-costum .TagsInput").hide();
    $(".table-fileds-costum ul.tagit").hide();
    $(".table-fileds-costum [name='type']").change(function() {

        $this_id = $(this).parent().data('id');

        if ($(this).val() === "DynamicSelect") {

            $(".table-fileds-costum ul.tagit").hide();
            $(".table-fileds-costum .TagsInput").attr('name', "");
            $.ajax({url: "../../views/ajax/getCostumeDropDown.php"
                , type: 'post'
                , data: {field: $var_name_field_puls, class: "er-daw-qw"}
                , success: function(data) {

                    $(".table-fileds-costum #plus_" + $this_id).html(data);
                }});
        } else if ($(this).val() === 'radio' || $(this).val() === 'checkbox' || $(this).val() === 'select') {

            $('.tagit').tagit();
            $(".table-fileds-costum #plus_" + $this_id + " ul.tagit").show();
            $(".table-fileds-costum .er-daw-qw").remove();
            $(".table-fileds-costum .tagit").attr('name', $var_name_field_puls);
            $('.tagit').tagit();

        } else {
            $(".table-fileds-costum ul.tagit").hide();
            $(".table-fileds-costum .er-daw-qw").remove();
            $(".table-fileds-costum .TagsInput").attr('name', "");
        }
    });


    $(".table-fileds-costum .TagsInput").hide();
    $(".table-fileds-costum ul.tagit").hide();


    $("body").on('click', '.editFeature', function() {
        $id = $(this).data('id');
        $('#f_' + $id + " input").removeAttr('readonly');
        $('#f_' + $id + " input").attr('readonly', false);
        $('#f_' + $id + " input").prop('readonly', false);
        $('#f_' + $id + " #type" + $id).show();
        $this_type = $('#f_' + $id + " #sp" + $id).remove();

        $this_plus = $('#f_' + $id + " #plus_" + $id + " span").hide();

        $(this).parent().html("<a href='javascript:;' data-id='" + $id + "' class='SaveEditFeature'><i class='fa fa-floppy-o' aria-hidden='true'></i></a>");


        $var_name_field_puls = 'plus';
        $val_field_plus = $(".table-fileds-costum .TagsInput").val();

        $vv = $('#f_' + $id + " [name='type']").val();
        $vv_sel = $('#f_' + $id + " [name='type']");
        $this_id = $vv_sel.parent().data('id');
        if ($vv === "DynamicSelect") {
            $this_plus = $('#f_' + $id + " #plus_" + $id).data('plus');
            $(".table-fileds-costum ul.tagit").hide();
            $(".table-fileds-costum .TagsInput").attr('name', "");
            $.ajax({url: "../../views/ajax/getCostumeDropDown.php"
                , type: 'post'
                , data: {field: $var_name_field_puls, class: "er-daw-qw", val: $this_plus}
                , success: function(data) {

                    $(".table-fileds-costum #plus_" + $this_id).html(data);
                }});
        }
        else if ($vv === 'radio' || $vv === 'checkbox' || $vv === 'select') {
            $('.tagit').tagit();
               $('#f_' + $id + " #plus_" + $id + " .tagit .tagit-label").each(function()
            {
                $this_tag = $(this).html() ;
                $a=$this_tag.replace('×','');
                 $(this).html($a);
          

            });
            $(".table-fileds-costum #plus_" + $this_id + " ul.tagit").show();
            $(".table-fileds-costum .er-daw-qw").remove();
            $(".table-fileds-costum .tagit").attr('name', $var_name_field_puls);
            $('.tagit').tagit();

        } else {
            $(".table-fileds-costum ul.tagit").hide();
            $(".table-fileds-costum .er-daw-qw").remove();
            $(".table-fileds-costum .TagsInput").attr('name', "");
        }

        makeTag();


    });


    $("body").on('click', '.SaveEditFeature', function() {
        $id = $(this).data('id');
        $this = $(this).parent();
        $input = $('#f_' + $id + " input");
        $title = $('#f_' + $id + " input").val();
        $type = $('#f_' + $id + "  #type" + $id).val();
        $this_type = $('#f_' + $id + " #type" + $id);
        $plus_val = $('#f_' + $id + " #plus_" + $id + " .er-daw-qw").val();
        $plus = "";
        if (typeof $plus_val !== "undefined") {
            $plus = $plus_val;
        } else {
            $this_tag = "";
            $('#f_' + $id + " #plus_" + $id + " .tagit .tagit-label").each(function()
            {
                $this_tag += $(this).html() + ",";
                $plus = $this_tag;

            });
        }


        $this_plus = $('#f_' + $id + " #plus_" + $id);

        $action = "Edit";
        $.ajax({
            url: "AddFeatureAjax.php",
            type: 'post',
            data: {title: $title, id: $id, action: $action, type: $type, plus: $plus},
            dataType: 'json',
            success: function(data) {

                $input.attr('readonly', true);
                $input.prop('readonly', true);
                $this_type.parent().html(data[0]);
                $this_type.hide();

                $this_plus.children().hide();
                $this_plus.find('span').show();
                $this_plus.find('span').html(data[1]);
                $this.html("<a href='javascript:;' data-id='" + $id + "' class='editFeature'><i class='fa fa-pencil-square-o' aria-hidden='true'></i></a>");
             
                    location.reload();
              
            }
        });
    });

    $("body").on('click', '.DeleteFeature', function() {
        $id = $(this).data('id');
        $title = $('#f_' + $id + " input").val();
        $this = $(this).parent();
        $input = $('#f_' + $id);

        $action = "Delete";
        $.ajax({
            url: "AddFeatureAjax.php",
            type: 'post',
            data: {title: $title, id: $id, action: $action},
            dataType: 'json',
            success: function(data) {

                $input.fadeOut();
            }
        });
    });
    /* End Category scripts*/

    /* Dynamic Pricing scripts*/
    $("body").on('click', '.addPricingSetting', function() {

        $table = $(this).data('table');
        $title = $('.catFeatures input').val();
        $this = $('.catFeatures input');

        if ($title !== "") {
            $action = "Insert";

            $thisButton = $(this);
            $this.attr('readonly', true);
            $this.prop('readonly', true);
            $this.parent().append("<div class='center-text spin-waiting'><i class='fa fa-spinner fa-spin spinner-style' ></i></div>");

            $(this).addClass('stop-button');
            $.ajax({
                url: "AddPricingAjax.php",
                type: 'post',
                data: {table: $table, title: $title, action: $action},
                dataType: 'json',
                success: function(data) {

                    $('#TablePricingDuration tbody').append(data);
                    $this.val('');
                    $this.attr('readonly', false);
                    $this.prop('readonly', false);
                    $this.parent().find(".spin-waiting").remove();

                    $thisButton.removeClass('stop-button');

                }
            });
        }
    });

    $("body").on('click', '.editPricingSetting', function() {
        $id = $(this).data('id');
        $table = $(this).data('table');
        var arr = $table.split('_');

        $('#' + arr[2] + '_' + $id + " input").removeAttr('readonly');
        $('#' + arr[2] + '_' + $id + " input").attr('readonly', false);
        $('#' + arr[2] + '_' + $id + " input").prop('readonly', false);

        $(this).parent().html("<a href='javascript:;' data-id='" + $id + "' data-table='" + $table + "' class='SavePricingSetting'><i class='fa fa-floppy-o' aria-hidden='true'></i></a>");
    });


    $("body").on('click', '.SavePricingSetting', function() {
        $id = $(this).data('id');
        $table = $(this).data('table');
        var arr = $table.split('_');
        $this = $(this).parent();
        $input = $('#' + arr[2] + '_' + $id + " input");
        $title = $('#' + arr[2] + '_' + $id + " input").val();
        $action = "Edit";
        $input.attr('readonly', true);
        $input.prop('readonly', true);
        $input.parent().append("<div class='center-text spin-waiting'><i class='fa fa-spinner fa-spin spinner-style' ></i></div>");
        $.ajax({
            url: "AddPricingAjax.php",
            type: 'post',
            data: {table: $table, title: $title, id: $id, action: $action},
            dataType: 'json',
            success: function(data) {

                $input.parent().find(".spin-waiting").remove();
                $this.html("<a href='javascript:;' data-id='" + $id + "' data-table='" + $table + "' class='editPricingSetting'><i class='fa fa-pencil-square-o' aria-hidden='true'></i></a>");
            }
        });
    });
    $("body").on('click', '.DeletePricingSetting', function() {
        $id = $(this).data('id');
        $table = $(this).data('table');

        var arr = $table.split('_');
        $title = $('#' + arr[2] + '_' + " input").val();
        $this = $(this).parent();
        $input = $('#' + arr[2] + '_' + $id);
        $action = "Delete";
        $.ajax({
            url: "AddPricingAjax.php",
            type: 'post',
            data: {table: $table, title: $title, id: $id, action: $action},
            dataType: 'json',
            success: function(data) {
                if (data[0] === 1 || data[0] === '1') {
                    $input.fadeOut();
                }
                else {
                    $('#ErrorModal .modal-body').html(data[1]);
                    $('#ErrorModal').modal();
                }
            }
        });
    });



    /* Addons Search */
    $('#AllAddOns').css("width", "100%");
    var config = {'#AllAddOns': {}};
    for (var selector in config) {
        $(selector).chosen(config[selector]);
    }

    $('#AllAddOns').val(0);
    $('#AllAddOns').trigger("chosen:updated");



    if (urlWindow === 'insertProductAddOns.php' || urlWindow === 'insertPackageAddOns.php') {
        $('#AllAddOns_chosen .chosen-single span').html("Add Add-Ons ...");
    } else if (urlWindow === 'insertProductRelated.php' || urlWindow === 'insertPackageRelated.php') {
        $('#AllAddOns_chosen .chosen-single span').html("Add related product ...");
    } else if (urlWindow === 'insertPackageProducts.php') {
        $('#AllAddOns_chosen .chosen-single span').html("Add package product ...");
    } else if (urlWindow === 'insertOfferProducts.php' || urlWindow === 'insertPromoOfferProducts.php') {
        $('#AllAddOns_chosen .chosen-single span').html("Add offer products ...");
    }
    $('#AllAddOns').change(function() {
        $thisSelect = $(this).val();
        $this = $(this);
        if (!$('#AddonsSelect' + $thisSelect).length) {
            $.ajax({url: '../../views/ajax/AjaxAddProduct.php', type: 'post', data: {id: $thisSelect},
                success: function(data) {
                    $('.AddAddOnsTo').append(data);
                    //   $this.find('option:selected').remove();
                    $('#AllAddOns').val(0);
                    $('#AllAddOns').trigger("chosen:updated");
                    if (urlWindow === 'insertProductAddOns.php' || urlWindow === 'insertPackageAddOns.php') {
                        $('#AllAddOns_chosen .chosen-single span').html("Add Add-Ons ...");
                    } else if (urlWindow === 'insertProductRelated.php' || urlWindow === 'insertPackageRelated.php') {
                        $('#AllAddOns_chosen .chosen-single span').html("Add related product ...");
                    } else if (urlWindow === 'insertPackageProducts.php') {
                        $('#AllAddOns_chosen .chosen-single span').html("Add package product ...");
                    } else if (urlWindow === 'insertOfferProducts.php' || urlWindow === 'insertPromoOfferProducts.php') {
                        $('#AllAddOns_chosen .chosen-single span').html("Add offer products ...");
                    }

                }});
        }
    });
    $('#buttonSearchAddOns').click(function() {
        $('#searchWordAddOns').val('');
        $('#resultSearchAddOns').html('');
        $placeholder = $(this).data('place');
        $('#SearchModalAddOns .modal-title ').html($placeholder);
        $('#SearchModalAddOns .modal-body input').attr("placeholder", $placeholder);
        $('#SearchModalAddOns').modal();
    });
    $('body').on('click', '#GoSearchAddOns', function() {
        $ids_selected = "";
        $('.AddAddOnsTo div').each(function() {
            $selected = $(this).data('id');
            $ids_selected = $selected + ',';
        });

        $word = $('#searchWordAddOns').val();
        $not_id = $('#ReqProduct').val();
        $('#resultSearchAddOns').html("<div class='center-text'><i class='fa fa-spinner fa-spin spinner-style' ></i></div>");
        if ($word !== "") {

            $.ajax({
                url: "../../views/ajax/AjaxSearchProduct.php",
                data: {word: $word, selected: $ids_selected, id: $not_id},
                type: 'post',
                success: function(data)
                {
                    $('#resultSearchAddOns').html(data);
                }
            });
        }
    });

    $('#searchWordAddOns').keypress(function(e) {
        if (e.which === 13) {
            $ids_selected = "";
            $('.AddAddOnsTo div').each(function() {
                $selected = $(this).data('id');

                $ids_selected += $selected + ',';
            });

            $word = $('#searchWordAddOns').val();
            $not_id = $('#ReqProduct').val();
            if ($word !== "") {
                $('#resultSearchAddOns').html("<div class='center-text'><i class='fa fa-spinner fa-spin spinner-style' ></i></div>");
                $.ajax({
                    url: "../../views/ajax/AjaxSearchProduct.php",
                    data: {word: $word, selected: $ids_selected, id: $not_id},
                    type: 'post',
                    success: function(data)
                    {
                        $('#resultSearchAddOns').html(data);
                    }
                });
            }
        }
    });

    $('body').on('click', '.addFromSearchAddons', function() {
        $(".overlay").html('<div id="loading-img"></div><div class="center-text" style="padding-top: 255px;font-size: 35px; position: absolute;z-index: 1; margin: auto;width: 100%;"><i class="fa fa-spinner fa-spin spinner-style" ></i></div>');
        $(".overlay").show();
        $thisSelect = $(this).data('id');
        $this = $(this);
        $(this).addClass('addedFromModal');

        if (!$('#AddonsSelect' + $thisSelect).length) {
            $.ajax({url: '../../views/ajax/AjaxAddProduct.php', type: 'post', data: {id: $thisSelect}, success: function(data) {

                    $(".overlay").empty();
                    $(".overlay").hide();

                    $('.AddAddOnsTo').append(data);
                    $('#AllAddOns').val(0);
                    $('#AllAddOns').trigger("chosen:updated");
                    if (urlWindow === 'insertProductAddOns.php' || urlWindow === 'insertPackageAddOns.php') {
                        $('#AllAddOns_chosen .chosen-single span').html("Add Add-Ons ...");
                    } else if (urlWindow === 'insertProductRelated.php' || urlWindow === 'insertPackageRelated.php') {
                        $('#AllAddOns_chosen .chosen-single span').html("Add related product ...");
                    } else if (urlWindow === 'insertPackageProducts.php') {
                        $('#AllAddOns_chosen .chosen-single span').html("Add package product ...");
                    } else if (urlWindow === 'insertOfferProducts.php' || urlWindow === 'insertPromoOfferProducts.php') {
                        $('#AllAddOns_chosen .chosen-single span').html("Add offer products ...");
                    }

                }});
        } else {
            $(".overlay").empty();
            $(".overlay").hide();
        }
    });
    $('body').on('click', '.remove-AddOns', function() {
        $('#AddonsSelect' + $(this).data('id')).remove();

    });
    /*End Addons Search */

    $('body').on('click', '#GenerateCode', function() {
        $code = $('#code').val();
        $.ajax({
            url: '../../views/ajax/GenerateCode.php',
            type: 'post',
            data: {code: $code},
            dataType: 'json',
            success: function(data) {
                $('#code').val(data);
            }
        });
    });

    $('body').on('click', '.saveFAQ', function() {
        $id = $(this).data('id');
        $question = $(this).parents().find('#question' + $id + '_ifr').contents().find('body').html();
        $answer = $(this).parents().find('#answer' + $id + '_ifr').contents().find('body').html();
        $this = $(this);
        $(".overlay").html('<div id="loading-img"></div><div class="center-text" style="padding-top: 255px;font-size: 35px; position: absolute;z-index: 1; margin: auto;width: 100%;"><i class="fa fa-spinner fa-spin spinner-style" ></i></div>');
        $(".overlay").show();
        $.ajax({
            url: '../../views/ajax/EditFaqAjax.php',
            type: 'post',
            data: {id: $id, question: $question, answer: $answer},
            dataType: 'json',
            success: function(data) {
                if (data === 1 || data === '1') {
                    $(".overlay").empty();
                    $(".overlay").hide();
                    var StrippedString = $question.replace(/(<([^>]+)>)/ig, "");
                    $this.parents().find('.faq_' + $id + " .panel-title").html(StrippedString);

                    waitingDialog.hide();
                    swal({
                        title: "",
                        text: 'success',
                        type: "success",
                        showConfirmButton: false
                        , showConfirmButton: false, timer: 2000
                    });
                }
            }
        });
    });


    $("body").on('click', '.remove-FAQ', function() {
        $id = $(this).data('id');
        $this = $(this);
        $.ajax({
            url: '../../views/ajax/DeleteFAQ.php',
            type: 'post',
            data: {id: $id},
            dataType: 'json',
            success: function(data) {
                if (data === 1 || data === '1') {
                    //  $this.parents().find('.faq_' + $id).fadeOut();
                   
                    location.reload();
                }
            }
        });
    });



    /* Customer Fields*/

    $("body").on('click', '.addCustomerField', function() {
        $title = $('.customerField input').val();
        $this = $('.customerField input');

        $thisButton = $(this);

        if ($title !== "") {
            $action = "Insert";
            $ajax_url = $(this).data('ajax');

            $this.attr('readonly', true);
            $this.prop('readonly', true);
            $this.parent().append("<div class='center-text spin-waiting'><i class='fa fa-spinner fa-spin spinner-style' ></i></div>");
            $this.attr("placeholder", 'Entering New Feature');
            $(this).addClass('stop-button');

            $.ajax({
                url: $ajax_url,
                type: 'post',
                data: {title: $title, action: $action},
                dataType: 'json',
                success: function(data) {

                    $('#TableCustomerFields tbody').append(data);
                    checkBoxStyle();
                    $this.val('');
                    $this.attr('readonly', false);
                    $this.prop('readonly', false);
                    $this.parent().find(".spin-waiting").remove();
                    $this.attr("placeholder", 'Enter Feild');
                    $thisButton.removeClass('stop-button');
               
                    location.reload();
               
                }
            });
        }
    });


    $("body").on('click', '.editCustomerField', function() {
        $id = $(this).data('id');
        $ajax_url = $(this).data('ajax');
        $('#f_' + $id + " input").removeAttr('readonly');
        $('#f_' + $id + " input").attr('readonly', false);
        $('#f_' + $id + " input").prop('readonly', false);
        $this_type = $('#f_' + $id + " #sp" + $id).remove();

        $this_plus = $('#f_' + $id + " #plus_" + $id + " span").hide();
        $(this).parent().html("<a href='javascript:;' data-id='" + $id + "' class='SaveEditCustomerField' data-ajax='" + $ajax_url + "'><i class='fa fa-floppy-o' aria-hidden='true'></i></a>");
        $('#f_' + $id + " #type" + $id).show();
     
        $var_name_field_puls = 'plus';
        $val_field_plus = $(".table-fileds-costum .TagsInput").val();

        $vv = $('#f_' + $id + " [name='type']").val();
        $vv_sel = $('#f_' + $id + " [name='type']");
        $this_id = $vv_sel.parent().data('id');
        if ($vv === "DynamicSelect") {
            $this_plus = $('#f_' + $id + " #plus_" + $id).data('plus');
           
            $(".table-fileds-costum ul.tagit").hide();
            $(".table-fileds-costum .TagsInput").attr('name', "");
            $.ajax({url: "../../views/ajax/getCostumeDropDown.php"
                , type: 'post'
                , data: {field: $var_name_field_puls, class: "er-daw-qw", val: $this_plus}
                , success: function(data) {

                    $(".table-fileds-costum #plus_" + $this_id).html(data);
                }});
        }
        else if ($vv === 'radio' || $vv === 'checkbox' || $vv === 'select') {
            $('.tagit').tagit();
             $('#f_' + $id + " #plus_" + $id + " .tagit .tagit-label").each(function()
            {
                $this_tag = $(this).html() ;
                $a=$this_tag.replace('×','');
                 $(this).html($a);
          

            });
            $(".table-fileds-costum #plus_" + $this_id + " ul.tagit").show();
            $(".table-fileds-costum .er-daw-qw").remove();
            $(".table-fileds-costum .tagit").attr('name', $var_name_field_puls);
            $('.tagit').tagit();

        } else {
            $(".table-fileds-costum ul.tagit").hide();
            $(".table-fileds-costum .er-daw-qw").remove();
            $(".table-fileds-costum .TagsInput").attr('name', "");
        }

        makeTag();
    });


    $("body").on('click', '.SaveEditCustomerField', function() {
        $id = $(this).data('id');
        $this = $(this).parent();
        $input = $('#f_' + $id + " input");
        $title = $('#f_' + $id + " input").val();
        $ajax_url = $(this).data('ajax');
        $type = $('#f_' + $id + "  #type" + $id).val();
        $this_type = $('#f_' + $id + " #type" + $id);
        $plus_val = $('#f_' + $id + " #plus_" + $id + " .er-daw-qw").val();
        $plus = "";
        if (typeof $plus_val !== "undefined") {
            $plus = $plus_val;
        } else {
            $this_tag = "";
            $('#f_' + $id + " #plus_" + $id + " .tagit .tagit-label").each(function()
            {
                $this_tag += $(this).html() + ",";
                $plus = $this_tag;

            });
        }


        $this_plus = $('#f_' + $id + " #plus_" + $id);
        $action = "Edit";
        $input.parent().append("<div class='center-text spin-waiting'><i class='fa fa-spinner fa-spin spinner-style' ></i></div>");
        $.ajax({
            url: $ajax_url,
            type: 'post',
            data: {title: $title, id: $id, action: $action, type: $type, plus: $plus},
            dataType: 'json',
            success: function(data) {

                $input.attr('readonly', true);
                $input.prop('readonly', true);
                $input.parent().find(".spin-waiting").remove();
                $this_type.parent().html(data[0]);
                $this_type.hide();

                $this_plus.children().hide();
                $this_plus.find('span').show();
                $this_plus.find('span').html(data[1]);
                $this.html("<a href='javascript:;' data-id='" + $id + "' class='editCustomerField' data-ajax='" + $ajax_url + "'><i class='fa fa-pencil-square-o' aria-hidden='true'></i></a>");
             
                    location.reload();
               
            }
        });
    });

    $("body").on('click', '.DeleteCustomerField', function() {
        $id = $(this).data('id');
        $title = $('#f_' + $id + " input").val();
        $this = $(this).parent();
        $input = $('#f_' + $id);

        $action = "Delete";
        $ajax_url = $(this).data('ajax');
        $.ajax({
            url: $ajax_url,
            type: 'post',
            data: {title: $title, id: $id, action: $action},
            dataType: 'json',
            success: function(data) {

                $input.fadeOut();
            }
        });
    });
    /* End  Customer Fields*/
    $("body").on('click', '.SaveProductField', function() {
        $redirect = $(this).data('redirect');
        $id_pro = $(this).data('id');
        $ids = [];

        $('.Fieldtr').each(function() {
            $this = $(this);
            $child = $(this).children().find('.icheckbox_square-red');

            if ($child.hasClass('checked') === true) {
                $id = $this.data('id');
                $ids.push($id);
            }

        });
        $ids_selected = $ids.join(",");

        $.ajax({
            url: '../../views/ajax/UpdateReqProductField.php',
            type: 'post',
            data: {id: $id_pro, selected: $ids_selected},
            dataType: 'json',
            success: function(data) {

                    window.location = $redirect;
              
            }
        });

    });

    $("#sortable_a").sortable({
        connectWith: "tr",
        cursor: "move",
        forcePlaceholderSize: true,
        opacity: 0.4,
        stop: function(event, ui) {
            var orderChanges = "";
            var sortorder = "";
            var itemorder = 0;
            $("#sortable_a tr").each(function() {
                var columnId = $(this).attr("id");

                itemorder++;
                if (columnId != ordIds[itemorder]) {
                    orderChanges += columnId + "," + ordIds[itemorder] + "|";
                    //alert(columnId);
                }
                ordIds[itemorder] = columnId;
            });
            //alert(orderChanges);
            if (orderChanges != "") {
                $("tr").css("cursor", "wait");

                $.post("../ajax/orderFields.php", {ot: order_table, of: order_filed, oi: order_id, ids: orderChanges}, function(data) {
                    // alert(data);
                    $("tr").css("cursor", "default");
                    $("#info").html(data);
                });
            }
        }
    });
});