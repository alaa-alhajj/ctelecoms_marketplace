<?php

class textext_js {

    function get_header() {
        return '
            <link href="../../includes/plugins/textext/css/textext.core.css" rel="stylesheet" type="text/css"/>
            <link href="../../includes/plugins/textext/css/textext.plugin.autocomplete.css" rel="stylesheet" type="text/css"/>
            <link href="../../includes/plugins/textext/css/textext.plugin.tags.css" rel="stylesheet" type="text/css"/>
            <script src="../../includes/plugins/textext/js/textext.core.js" type="text/javascript"></script>
            <script src="../../includes/plugins/textext/js/textext.plugin.ajax.js" type="text/javascript"></script>
            <script src="../../includes/plugins/textext/js/textext.plugin.autocomplete.js" type="text/javascript"></script>
            <script src="../../includes/plugins/textext/js/textext.plugin.filter.js" type="text/javascript"></script>
            <script src="../../includes/plugins/textext/js/textext.plugin.tags.js" type="text/javascript"></script>';
    }

    function get_script($field, $file, $table, $id_field, $display_field, $value = "",$onetag='false') {
        if ($value != "") {
            $tagsItem = ",tagsItems:[$value]";
            $option = "autocomplete  tags ajax";
        } else {
            $option = "autocomplete filter tags ajax";
        }
        $wh = "table:'$table',id_field:'$id_field',display_field:'$display_field'";
        return " <script type='text/javascript'>
          
                    $('$field')
                            .textext({
                                plugins: '$option',
                                ajax: {
                                    url: '$file',
                                    dataType: 'json',
                                    cacheResults: true,
                                    data:{" . $wh . "}
                                }$tagsItem
                            }).bind('isTagAllowed', function(e, data) {
                        var formData = $(e.target).textext()[0].tags()._formData,
                                list = eval(formData);
                                if($onetag==false){
                        if (formData.length && list.indexOf(data.tag) >= 0) {
                            data.result = false;
                        }
                        }else if($onetag == true){
                            if (formData.length  >= 1) {
                        data.result = false;
                    }
                            }
                    });
                </script>";
    }

    function getForma($sourceTable, $getFild, $compareField, $value, $is_edit = false) {
        global $fpdo;
        $value = str_replace(array("[", "]"), '', $value);
        $query = $fpdo->from($sourceTable, array())->select($getFild)->where("$compareField in ($value)")->fetchAll();
        $return = array();
        if (count($query) > 0) {
            foreach ($query as $row) {
                if (is_int($row[$getFild])) {
                    array_push($return, $row[$getFild]);
                } else {
                    if (!$is_edit)
                        array_push($return, "" . $row[$getFild] . "");
                    else
                        array_push($return, "'" . $row[$getFild] . "'");
                }
            }
            return implode(',', $return);
        }
        return false;
    }

}
