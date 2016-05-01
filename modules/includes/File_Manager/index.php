<?php
include '../../../config.php';
$fieldName = stripcslashes($_REQUEST['filedName']);

$type = stripcslashes($_REQUEST['type']);
$value = ($_REQUEST['value']);

$site = _PREF;
if ($value != '')
    
    ?>   

<??>

<script>
    function ImageExist(url)
    {

        var http = new XMLHttpRequest();
        http.open('HEAD', url, false);
        http.send();

        return http.status !== 404;
    }
    function isInArray(value, array) {

        return array.indexOf(value) > -1;
    }

    $.post('../../includes/File_Manager/filemanager/selectFile.php', {id: '<?php echo $value ?>'}, function(data) {
        var idval = ($('[rel=<?php echo $fieldName ?>]').val());
        var arrval = idval.split(',');
        var thumb = "";
        $('[rel=json<?php echo $fieldName ?>]').val(data);
        var va = $('[rel=json<?php echo $fieldName ?>]').val();

        var array = JSON.parse(va);


        $.each(array, function(index, result) {
            console.log(result['url']);
            if (result['url'] != '--') {
                var title = result['url'];

                if (title !== '' && title !== undefined) {
                    var last2 = title.lastIndexOf('.');
                    var ext = title.substring(last2 + 1);
                    var titleArr = title.split('.');
                    title = title.substring(0, title.length - 4);

                    var id = result['id'];//arrval[index];
                    var realThumb = result['url'].split('uploads/');
                    console.log(realThumb);
                    title = title.split('uploads/');
                    title = title[1];


                    if (isInArray(ext, ['jpg', 'png', 'bmp', 'gif', 'tif']) && ImageExist('' + '<?php echo _PREF ?>' + 'uploads/cash/' + realThumb[1]))
                    {
                        thumb = '<img src="<?php echo _PREF; ?>uploads/cash/' + (((realThumb[1]))) + '" class="file-preview-image" title="' + title + '" alt="' + title + '" style="width:auto;height:90px;" >';

                    } else {
                        thumb = '<img src="<?php echo _PREF . MODULES_FOLDER; ?>/includes/File_Manager/filemanager/img/ico/' + ext + '.jpg" class="file-preview-image" title="' + title + '" alt="' + title + '" style="width:auto;height:90px;" >';

                    }

                    var newImg = $('<div class="<?php echo $fieldName ?>img file-preview-frame" rel="' + id + '" id="' + id + '" data-fileindex="0">\n\
<a class="close <?php echo $fieldName ?>hiding" id="' + id + '" ><small>×</small></a>\n\
      ' + thumb + '\n\
<div class="file-thumbnail-footer" >\n\
</div></div>');
                    $('#here<?php echo $fieldName ?>').append(newImg);
                }
            }
        });
    });

</script>   <?php
?>

<meta name="http-equiv" content="Content-type: text/html; charset=UTF-8"/>
<link href="../../includes/File_Manager/assets/css/bootstrap.css" rel="stylesheet">
<link href="../../includes/File_Manager/assets/css/bootstrap-responsive.css" rel="stylesheet">
<link href="../../includes/File_Manager/assets/css/animate.min.css" rel="stylesheet">
<link href="../../includes/File_Manager/assets/css/style.css" rel="stylesheet">
<noscript>
<link href="../../includes/File_Manager/assets/css/noscript.css" rel="stylesheet">
</noscript>
<link rel="stylesheet" type="text/css" href="../../includes/File_Manager/assets/fancybox/jquery.fancybox-1.3.4.css" media="screen" />

<link href="../../includes/File_Manager/assets/css/prettyPhoto.css" rel="stylesheet" type="text/css" />


<link href="../../includes/File_Manager/assets/css/fileinput.css" media="all" rel="stylesheet" type="text/css" />
<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

<script type="text/javascript">var switchTo5x = false;</script>
<body>
    <div class="container" style='padding:0px;margin-top:10px;margin-bottom:10px;'>

        <div class="row-fluid">
            <div class="span12">
                <div class="alerting">

                    <?php if ($type == 1) { ?>  <div class="input-append1">
                            <input rel="json<?php echo $fieldName ?>" id="fieldID5" name="<?php echo $fieldName ?>-json" type="hidden" value="" >
                            <input rel="<?php echo $fieldName ?>" id="ids<?php echo $fieldName ?>" name="<?php echo $fieldName ?>" type="hidden" value="<?php echo $value ?>" >
                            <a href="../../includes/File_Manager/filemanager/dialog.php?type=<?php echo $type ?>&field_id=fieldID5&crossdomain=1&json=" 
                               class="btn btn-default iframe-btn clb" id="<?php echo $fieldName ?>" type="button">Browse</a>
                        </div>
                        <?php
                    } if ($type == 2) {
                        $value = stripcslashes($_REQUEST['value']);
                        ?>
                        <div class="input-append1">
                            <input rel="json<?php echo $fieldName ?>" id="fieldID3" name="<?php echo $fieldName ?>-json" type="hidden" value="" >
                            <input rel="<?php echo $fieldName ?>" id="ids<?php echo $fieldName ?>" name="<?php echo $fieldName ?>" type="hidden" value="<?php echo $value ?>" >
                            <a href="../../includes/File_Manager/filemanager/dialog.php?type=<?php echo $type ?>&field_id=fieldID3&crossdomain=1&json=" 
                               class="btn btn-default iframe-btn clb" id="<?php echo $fieldName ?>" type="button">Browse</a>
                        </div>
                        <?php
                    } if ($type == 3) {
                        $value = stripcslashes($_REQUEST['value']);
                        ?>   
                        <div class="input-append1">
                            <input rel="json<?php echo $fieldName ?>" id="fieldID2" name="<?php echo $fieldName ?>-json" type="hidden" value="" >
                            <input rel="<?php echo $fieldName ?>" id="ids<?php echo $fieldName ?>" name="<?php echo $fieldName ?>" type="hidden" value="<?php echo $value ?>" >
                            <a href="../../includes/File_Manager/filemanager/dialog.php?type=<?php echo $type ?>&field_id=fieldID2&crossdomain=1&json=" 
                               class="btn btn-default iframe-btn clb" id="<?php echo $fieldName ?>" type="button">Browse</a>
                        </div>


                        <?php
                    } if ($type == 4) {
                        $value = stripcslashes($_REQUEST['value']);
                        ?>


                        <div class="input-append1">
                            <input rel="json<?php echo $fieldName ?>" id="fieldID3" name="<?php echo $fieldName ?>-json" type="hidden" value="" >
                            <input rel="<?php echo $fieldName ?>" id="ids<?php echo $fieldName ?>" name="<?php echo $fieldName ?>" type="hidden" value="<?php echo $value ?>" >
                            <a href="../../includes/File_Manager/filemanager/dialog.php?type=<?php echo $type ?>&field_id=fieldID3&crossdomain=1&json=" 
                               class="btn btn-default iframe-btn clb" id="<?php echo $fieldName ?>" type="button">Browse</a>
                        </div>

                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <script>
        function responsive_filemanager_callback(field_id) {
            console.log(field_id);
            var url = jQuery('#' + field_id).val();
            var n = url.lastIndexOf('/');
            var result = url.substring(n + 1);
            $.post('../../includes/File_Manager/filemanager/selectId.php', {name: result}, function(data) {
                jQuery('#' + field_id).val(data);
            });
            //your code
        }
        jQuery(document).ready(
                function() {
                    var link = "";
                    $('.clb#<?php echo $fieldName ?>').click(function() {
                        var val = encodeURIComponent(JSON.stringify($('input[rel="json<?php echo $fieldName ?>"]').val()));
                        if (link == "") {
                            link = $(this).attr('href');
                        }
                        $link = link + val;
                        $(this).attr('href', $link);
                    });
                    
                }
        );
    </script>




    <script type="text/javascript">
        function findAndRemove(array, property, value) {

            var c = 2;
            try {
                if (typeof value === 'undefined') {
                } else {
                    $.each(array, function(index, result) {
                        index = parseInt(index);

                        if (result[property] == value) {
                            $('.<?php echo $fieldName ?>img').remove('#' + value);
                            array.splice(index, 1);
                            c = 1;
                            return false;
                        }
                    });
                }
                return c;
            }
            catch (err) {
                console.log(err.message + value);
            }
        }
        function removeit(array, property, value, fn) {

            var c = 2;
            var v = 0;
            try {
                if (typeof value === 'undefined') {
                } else {
                    $.each(array, function(index, result) {
                        index = parseInt(index);

                        if (result[property] == value) {
                            $('.' + fn + 'img').remove('#' + value);

                            array.splice(index, 1);
                            c = 1;
                            return array;
                        }
                    });
                }
                return array;
            }
            catch (err) {
                //    $('.<?php echo $fieldName ?>img').remove('#' + value);
                return array;
            }
        }

        function findandok(array, property, value) {
            $.each(array, function(index, result) {

                if (result[property] == value) {
                    //Remove from array
                    return 1;
                }
            });
        }

        // jQuery.noConflict();
        //jquery stuff

        jQuery(document).ready(function($) {

            $('body').on('click', '.<?php echo $fieldName ?>hiding', function() {
                //    $(this).parent().remove();
                var id = $(this).attr('id')
                var va = $('input[rel=json<?php echo $fieldName ?>]').val();
                if (va != '')
                {
                    out.output = JSON.parse(va);

                    var ret = removeit(out.output, "id", id, '<?php echo $fieldName ?>');

                }
                $('input[rel=json<?php echo $fieldName ?>]').val(JSON.stringify(ret));
                var Ids = [];
                $.each(ret, function(index, result) {

                    if (result['id'] !== undefined) {
                        Ids.push(result['id']);
                    }
                });
                $('#ids<?php echo $fieldName ?>').val(Ids);
            });



            $('#<?php echo $fieldName ?>.iframe-btn').fancybox({
                'width': 880,
                'height': 570,
                'type': 'iframe',
                'autoScale': false,
                onClosed: function() {
                    $(window).off('message', OnMessage);
                    $.ajax({url: '../../includes/File_Manager/unset.php'});

                }
            });
            var out = {
                output: []
            };
            var va = $('input[rel=json<?php echo $fieldName ?>]').val();
            if (va != '')
            {
                out.output = JSON.parse(va);

                $.each(out.output, function(index, result) {

                });
            }


            //
            // Handles message from ResponsiveFilemanager
            //
            function ImageExist(url)
            {

                var http = new XMLHttpRequest();
                http.open('HEAD', url, false);
                http.send();

                return http.status != 404;
            }
            function OnMessage(e) {

                var event = e.originalEvent;
                // Make sure the sender of the event is trusted
                if (event.data.sender === 'responsivefilemanager') {
                    if (event.data.field_id) {

                        var fieldID = event.data.field_id;

                        var url = event.data.url;
                        var nameImage = event.data;
                        var c = 0;
                        var n = url.lastIndexOf('/');
                        var result = url.substring(n + 1);
                        result = result.split('.').join("");
                        result = result.replace(/%20/g, "");
                        result = result.replace("(", "");
                        result = result.replace(")", "");
                        var Ids = [];

                        var va = $('input[rel=json<?php echo $fieldName ?>]').val();
                        if (va != '')
                        {
                            out.output = JSON.parse(va);

                        }
                        var id = $('#fancybox-content iframe').contents().find('#clicked' + result).attr('rel');
                        var ret = findAndRemove(out.output, "id", id);

                        if (ret == 1) {

                            console.log('has red');

                            $('input[rel="json<?php echo $fieldName ?>"]').val(JSON.stringify(out.output));
                            $.each(out.output, function(index, result) {

                                if (result['id'] !== undefined) {
                                    Ids.push(result['id']);
                                }
                            });

                            $('#ids<?php echo $fieldName ?>').val((Ids));
                            $('#fancybox-content iframe').contents().find('#clicked' + result).removeClass('red');
                            $('#fancybox-content iframe').contents().find('#clicked' + result).css({'background-color': 'white'});
                        } else
                        {
                            console.log('not has red');
                            out.output.push({
                                "url": url,
                                "id": id
                            });
                            $('#fancybox-content iframe').contents().find('#clicked' + result).addClass('red');
                            $('#fancybox-content iframe').contents().find('#clicked' + result).css({'background-color': 'red', 'color': 'white !importmant'});
                            $('#fancybox-content iframe').contents().find('[rel=' + result['id'] + ']').children('.ellipsis').children().css({'color': 'white !importmant'});
                            $('input[rel="json<?php echo $fieldName ?>"]').val(JSON.stringify(out.output));
                            $.each(out.output, function(index, result) {
                                if (result['id'] !== undefined) {
                                    Ids.push(result['id']);
                                }

                            });

                            $('#ids<?php echo $fieldName ?>').val((Ids));
                            var last = url.lastIndexOf('/');
                            var title = url.substring(last + 1);
                            var last2 = title.lastIndexOf('.');
                            var ext = title.substring(last2 + 1);
                            titleArr = title.split('.');
                            title = title.substring(0, title.length - 4);
                            var realThumb = url.split('uploads/');
                            var thumb = '';
                            if (isInArray(ext, ['jpg', 'png', 'bmp', 'gif', 'tif']) && ImageExist('<?php echo _PREF ?>' + 'uploads/cash/' + realThumb[1]))
                            {
                                thumb = '<img src="<?php echo _PREF ?>uploads/cash/' + (((realThumb[1]))) + '" class="file-preview-image" title="' + title + '" alt="' + title + '" style="width:auto;height:90px;" >';
                            } else if (!ImageExist('<?php echo _PREF ?>uploads/cash/' + realThumb[1])) {
                                thumb = '<img src="<?php echo _PREF . MODULES_FOLDER ?>/includes/File_Manager/filemanager/img/ico/' + ext + '.jpg" class="file-preview-image" title="' + title + '" alt="' + title + '" style="width:auto;height:90px;" >';

                            } else {
                                thumb = '<img src="<?php echo _PREF . MODULES_FOLDER ?>/includes/File_Manager/filemanager/img/ico/' + ext + '.jpg" class="file-preview-image" title="' + title + '" alt="' + title + '" style="width:auto;height:90px;" >';


                            }

                            var newImg = $('<div class="<?php echo $fieldName ?>img file-preview-frame" rel="' + id + '" id="' + id + '" data-fileindex="0">\n\
                                    <a class="close <?php echo $fieldName ?>hiding" id="' + id + '" ><small>×</small></a>\n\
                                                    ' + thumb + '\n\
                                        <div class="file-thumbnail-footer" >\n\
                                       </div></div>');
                            $('#here<?php echo $fieldName ?>').append(newImg);
                            ret = 2;
                        }
                        $.ajax({url: '../../includes/File_Manager/setSession.php', data: {json: JSON.stringify($('input[rel=json<?php echo $fieldName ?>]').val())}, success: function() {

                            }});
                    }
                }     // $(window).off('message', OnMessage);
            }

            // Handler for a message from ResponsiveFilemanager
            $('.iframe-btn#<?php echo $fieldName ?>').on('click', function() {
                $(window).off('message', OnMessage);
                $(window).on('message', OnMessage);
                var va = $('input[rel=json<?php echo $fieldName ?>]').val();
                if (va != '')
                {
                    out.output = JSON.parse(va);

                    $.each(out.output, function(index, result) {

                        $('#fancybox-content iframe').contents().find('[rel=' + result['id'] + ']').addClass('red');
                        $('#fancybox-content iframe').contents().find('[rel=' + result['id'] + ']').css({'background-color': 'red', 'color': 'white !importmant'});
                        $('#fancybox-content iframe').contents().find('[rel=' + result['id'] + ']').children('.ellipsis').children().css({'color': 'white !importmant'});
                        console.log();

                    });


                }
            });
            $('#download-button').on('click', function() {
                ga('send', 'event', 'button', 'click', 'download-buttons');
            });
            $('.toggle').click(function() {
                var _this = $(this);
                $('#' + _this.data('ref')).toggle(200);
                var i = _this.find('i');
                if (i.hasClass('icon-plus')) {
                    i.removeClass('icon-plus');
                    i.addClass('icon-minus');
                } else {
                    i.removeClass('icon-minus');
                    i.addClass('icon-plus');
                }
            });
        });
        function open_popup(url)
        {
            var w = 880;
            var h = 570;
            var l = Math.floor((screen.width - w) / 2);
            var t = Math.floor((screen.height - h) / 2);
            var win = window.open(url, 'ResponsiveFilemanager', "scrollbars=1,width=" + w + ",height=" + h + ",top=" + t + ",left=" + l);
        }

    </script>

    <script type="text/javascript">
        jQuery('#my-tab a').click(function(e) {
            e.preventDefault();
            jQuery(this).tab('show');
        });
    </script>
<!--  <form name="form<?php echo $fieldName ?>">-->
    <span class="file-input"><div class="file-preview">
            <div class="file-preview-thumbnails">
                <div class="sortable" id="here<?php echo $fieldName ?>" rel="<?php echo $fieldName ?>" >   <div class="file-preview-thumbnails sortable" id="<?php echo $fieldName ?>">

                    </div>
                </div>
            </div>
            <div class="clearfix"></div>   
            <div class="kv-fileinput-error file-error-message" style="display: none;"></div>

    </span>
    <!--    </form>-->

    <script>
        $(document).ready(function() {
            $('.sortable').sortable({
                update: function(event, ui) {

                    var newOrder = $(this).sortable('toArray').toString();
                    newOrder = newOrder.split(',').slice(1);

                    $('#ids<?php echo $fieldName ?>').val(newOrder);
//                $.get('saveSortable.php', {order: newOrder});
                }
            });
        });

    </script>

</body>
