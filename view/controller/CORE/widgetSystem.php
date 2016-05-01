<?php

/**
 * Description of voila
 *
 * @author Ahmad mahmoud
 */
class widgets extends utils {

    function getFormulaWidget($widget_id, $condition, $img_settings) {
        if ($condition != "") {
            $conditionForma = "##wid_con_start##$condition##wid_con_end##";
        }
        if ($img_settings != "") {
            $img_settingsForma = "##wid_img_settings_start##$img_settings##wid_img_settings_end##";
        }
        return " ##wid_start####wid_id_start##$widget_id##wid_id_end##$conditionForma" . "" . "$img_settingsForma##wid_end##​ ";
    }

    function replaceFormulaWithWidget($formula, $page_id = "") {
        $widget_idA = $this->getContents($formula, '##wid_id_start##', '##wid_id_end##');
        $widget_id = $widget_idA[0];
        $widget_conditionA = $this->getContents($formula, '##wid_con_start##', '##wid_con_end##');
        $widget_condition = $widget_conditionA[0];
        $img_settings = $this->getContents($formula, '##wid_img_settings_start##', '##wid_img_settings_end##');
        $img_settingsA = $img_settings[0];
        $template_html = $this->getWidget($widget_id, $page_id, $widget_condition, false, $img_settingsA);
        return $template_html;
    }

    function getFilters($settings_id) {
        $condition = '';
        $fields = '';
        $param_field = 0;
        $extra_param = $_REQUEST['extra_param'];
        if ($extra_param) {
            $param_arr = explode('|', $extra_param);
            foreach ($param_arr as $param) {
                $param_vals = explode(':', $param);
                $param_field = $param_vals['0'];
                $param_value = $param_vals['1'];
                $params[$param_field] = $param_value;
            }
        }
        $query = $this->fpdo->from('cms_filter_fields')->where(" settings_id='$settings_id' ")->fetchAll();
        $s = 1;
        foreach ($query as $row) {
            $field_label = $row['title'];
            $field_id = $row['field_id'];
            $query2 = $this->fpdo->from('cms_module_fields')->where(" id='$field_id' ")->fetchAll();

            foreach ($query2 as $row2) {
                $field_title = $row2['title'];
                $field_type = $row2['type'];
                $field_plus = $row2['plus'];

                if ($field_type == 'date' || $field_type == 'datepicker') {
                    $fields.="<tr><td> $field_label: &nbsp;</td></tr><tr><td> <input type='text' name='filter_$field_title' id='datepicker' value='" . $_REQUEST['filter_' . $field_title] . $params[$field_title] . "'/></td></tr>";
                } elseif ($field_type == 'DynamicSelect') {
                    $dynamic_table_name = $this->lookupField('cms_modules', 'id', 'table_name', $field_plus);
                    $dynamic_table_field = $this->lookupField('cms_module_fields', 'id', 'title', '', " table_id='$field_plus' AND is_main='1' ");
                    $query3 = $this->fpdo->from($dynamic_table_name)->fetchAll();
                    $fields .="<tr><td>$field_label: &nbsp;</td></tr><tr><td> <select name='filter_$field_title'>
						<option value=''>الكل</option>";
                    foreach ($query3 as $row3) {
                        $combo_title = $row3[$dynamic_table_field];
                        $combo_id = $row3['id'];
                        if ($_REQUEST['filter_' . $field_title] == $combo_id) {
                            $selected = 'selected';
                        } else {
                            $selected = '';
                        }
                        $fields .= "<option value='$combo_id' $selected>$combo_title</option>";
                    }
                    $fields .="</select></td></tr>";
                } else {
                    $fields.="<tr><td> $field_label: &nbsp;</td></tr><tr><td> <input type='text' name='filter_$field_title' value='" . $_REQUEST['filter_' . $field_title] . $params[$field_title] . "'/></td></tr>";
                }
                if ((isset($_REQUEST['filter_' . $field_title]) && $_REQUEST['filter_' . $field_title] != '') || $params['filter_' . $field_title]) {
                    if ($s == 1) {
                        $and = '';
                    } else {
                        $and = 'AND';
                    }
                    $condition.=" $and $field_title Like '%" . $_REQUEST['filter_' . $field_title] . $params['filter_' . $field_title] . "%' ";
                    $extar_link .= "$field_title:" . $_REQUEST['filter_' . $field_title] . $params['filter_' . $field_title] . "|";
                    $s++;
                }
            }
        }

        if ($fields) {
            $fields = "<form action='" . $_SESSION['_PREF'] . $_SESSION['pLang'] . "/page" . $_REQUEST['id'] . "/" . $_REQUEST['title'] . "' method='post'>
				<table border='0px' class='filters-table'>
				$fields
				<tr><td><input type='submit' value='بحث' name='action'/></td></tr>
				</table>
				<input type='hidden' value='" . $_REQUEST['id'] . "' name='id'/>
				<input type='hidden' value='" . $_REQUEST['title'] . "' name='title'/>
			</form>";
        }
        if (!$extar_link) {
            $extar_link = '0' . '|';
        }
        return array('condition' => $condition, 'fields' => $fields, 'extar_link' => $extar_link);
    }

    function getWidgetSettings($widget_id) {
        $result = '';
        $query = $this->fpdo->from('cms_widget_settings')->where(" widget_id='$widget_id' ")->fetchAll();
        foreach ($query as $row) {
            $settings_id = $row['id'];
            $setting_filters = $this->getFilters($settings_id);
            $filter_con = $setting_filters['condition'];
            $filter_fields = $setting_filters['fields'];
            $extar_link = $setting_filters['extar_link'];
            $condition = str_replace('where', '', $row['condition']);
            if ($condition && $filter_con) {
                $condition = $filter_con . ' AND ' . $condition;
            } elseif ($filter_con) {
                $condition = $filter_con;
            }
            $lpp = $row['limit'];
            $lpp2 = $row['limit'];
            if (!$lpp) {
                $lpp = 3;
            }
            $pn = 0;
            if ($_REQUEST['pn']) {
                $pn = $_REQUEST['pn'];
            }
            $start = $pn * $lpp;
            $limit = "$start, $lpp";
            $template_id = $row['template_id'];
            $pagination_id = $row['pagination_id'];
            $result = array(
                'condition' => $condition,
                'limit' => $limit,
                'limit_num' => $lpp2,
                'template_id' => $template_id,
                'filter_fields' => $filter_fields,
                'extar_link' => $extar_link,
                'pagination_id' => $pagination_id
            );
        }

        return $result;
    }

    function getWidgetInfo($widget_id) {
        $result = '';
        $query = $this->fpdo->from('cms_widgets')->where(" id='$widget_id' ")->fetchAll();
        foreach ($query as $row) {

            $module_id = $row['module_id'];
            $title = $row['title'];
            $static_path = $row['static_path'];
            $html = stripslashes($row['html']);
            $result = array(
                'title' => $title,
                'module_id' => $module_id,
                'html' => $html,
                'static_path' => $static_path
            );
        }

        return $result;
    }

    function getTempFieldSettings($field_id) {
        $result = '';
        $query = $this->fpdo->from('cms_tem_field_settings')->where(" field_id='$field_id' ")->fetchAll();
        foreach ($query as $row) {
            $limit = $row['limit'];
            $width = $row['width'];
            $height = $row['height'];
            $resize_type = $row['resize_type'];
            $lpp = $row['limit'];
            $result = array(
                'limit' => $limit,
                'width' => $width,
                'height' => $height,
                'resize_type' => $resize_type
            );
        }

        return $result;
    }

    function getTemplate($widget_id, $template_id) {
        $query = $this->fpdo->from('cms_templates')->where(" id='$template_id' ")->fetchAll();


        $i = 1;

        foreach ($query as $row) {

            $temp_main_html = stripslashes($row['main_html']);
            $temp_item_html = stripslashes($row['item_html']);

            $query2 = $this->fpdo->from('cms_template_settings')->where(" template_id='$template_id' ")->fetchAll();
            foreach ($query2 as $row2) {

                $temp_setting_id = $row2['id'];
                $default_value = $row2['default_value'];
                $replace_stm = $row2['replace_stm'];
                if ($replace_stm) {
                    $replace_stm = '@@' . $replace_stm . '@@';
                }
                $wid_sit_id = $this->lookupField('cms_widget_settings', 'id', 'id', '', "widget_id='$widget_id'");
                $query2 = $this->fpdo->from('cms_widget_temp_settings')->select('value')->where(" template_settings_id='$temp_setting_id' AND widget_settings_id = '$wid_sit_id' ")->fetch();
                $replace_value = $query2['value'];
                if (!$replace_value) {
                    $replace_value = $default_value;
                }

                $temp_main_html = str_replace($replace_stm, $replace_value, $temp_main_html);
                $temp_item_html = str_replace($replace_stm, $replace_value, $temp_item_html);
            }


            $i++;
        }

        return array(
            'main_html' => $temp_main_html,
            'item_html' => $temp_item_html);
    }

    function getWidget($widget_id, $page_id, $condition, $ajax = false, $img_settings = "") {

        $months_ar = array("يناير", "فبراير", "مارس", "إبريل", "مايو", "يونيو", "يوليو", "أغسطس", "سبتمبر", "أكتوبر", "نوفمبر", "ديسمبر");
        $wid_settings = $this->getWidgetSettings($widget_id);
        $wid_info = $this->getWidgetInfo($widget_id);
        $widget_title = $wid_info['title'];
        $module_id = $wid_info['module_id'];
        $static_path = $wid_info['static_path'];
        $wid_html = $wid_info['html'];

        if ($wid_html) {
            $final_html = $this->getExtraWidgets($wid_html, $row);
        } elseif ($static_path) {
            ob_start();
            include $_SESSION['dots'] . $static_path;
            $final_html = ob_get_contents();
            ob_end_clean();
        } else {
            $table_name = $this->lookupField('cms_modules', 'id', 'table_name', $module_id);
            $has_order = $this->lookupField('cms_modules', 'id', 'has_order', $module_id);
            $lang_type = $this->lookupField('cms_modules', 'id', 'lang_type', $module_id);
            $temp_html = $this->getTemplate($widget_id, $wid_settings['template_id']);
            $template_main_html = $temp_html['main_html'];
            $template_item_html = $temp_html['item_html'];
            $final_condition = $wid_settings['condition'];
            if ($final_condition && $condition) {
                $final_condition = $wid_settings['condition'] . ' AND ' . $condition;
            } elseif ($condition) {
                $final_condition = $condition;
            }


            $template_main_html = str_replace('##curr_year##', date('Y'), $template_main_html);
            $template_item_html = str_replace('##curr_year##', date('Y'), $template_item_html);

            $session_names_arr = $this->getContents($template_main_html . $template_item_html . $final_condition, '##session_name_s##', '##session_name_e##');
            foreach ($session_names_arr as $session_name) {
                $template_main_html = str_replace('##session_name_s##' . $session_name . '##session_name_e##', $_SESSION[$session_name], $template_main_html);
                $template_item_html = str_replace('##session_name_s##' . $session_name . '##session_name_e##', $_SESSION[$session_name], $template_item_html);
                $final_condition = str_replace('##session_name_s##' . $session_name . '##session_name_e##', $_SESSION[$session_name], $final_condition);
            }





            $order_field = 'id';
            if ($has_order) {
                $order_field = 'item_order';
            }

            $final_condition = stripslashes($final_condition);
            if ($lang_type == 'Table') {
                if ($final_condition != '') {
                    $final_condition.=' AND ';
                }$final_condition.=" lang='" . $_SESSION['pLang'] . "' ";
            }
            $query = $this->fpdo->from($table_name)->where($final_condition)->orderBy($order_field . ' DESC')->limit($wid_settings['limit']);
            //echo $query->getQuery();
            $query = $this->fpdo->from($table_name)->where($final_condition)->orderBy($order_field . ' DESC')->limit($wid_settings['limit'])->fetchAll();

            $widget_total_numrows = count($this->fpdo->from($table_name)->where($final_condition)->fetchAll());
            $counter = 1;
            foreach ($query as $row) {
                //$query2 = $this->fpdo->from('cms_widget_fields AS wid_fields, cms_template_fields AS temp_fields ')->where("wid_fields.widget_id='$widget_id' and temp_fields.id=wid_fields.template_field_id'")->fetchAll();
                $query2 = $this->fpdo->from('cms_widget_fields')->where("widget_id='$widget_id'")->fetchAll();
                $item_html = $template_item_html;
                foreach ($query2 as $row2) {
                    $template_field_id = $row2['template_field_id'];
                    //$template_field_title=$row['temp_fields.title'];
                    $template_field_title = $this->lookupField('cms_template_fields', 'id', 'title', $template_field_id);
                    $temp_feild_settings = $this->getTempFieldSettings($template_field_id);
                    $module_field_id = $row2['module_field_id'];

                    $module_field_info = $this->fpdo->from('cms_module_fields')->where("id='$module_field_id'")->fetch();
                    $module_field_title = $module_field_info['title'];
                    $module_field_type = $module_field_info['type'];
                    $module_field_plus = $module_field_info['plus'];
                    $module_field_lang_eff = $module_field_info['is_lang_eff'];

                    /*                     * ********Check if field is lang effected i.e. it is title or title_en ********* */
                    if ($module_field_lang_eff == 1) {
                        $dVal = $item_val = $item_val2 = $row[$module_field_title . '_' . $_SESSION['pLang']];
                    } else {
                        $dVal = $item_val = $item_val2 = $row[$module_field_title];
                    }
                    /*                     * ***************************************************************************** */

                    /*                     * ********Get only page ID of current lang********* */
                    $sub_page_id = $row['page_id'];
                    $sub_page_check = $this->fpdo->from('cms_pages')->where("id in($sub_page_id) AND lang='" . $_SESSION['pLang'] . "' ")->fetch();
                    $sub_page_id = $sub_page_check['id'];
                    /*                     * ************************************************* */

                    $sub_page_title = $this->lookupField('cms_pages', 'id', 'title', $sub_page_id);
                    $sub_page_link = $_SESSION['_PREF'] . $_SESSION['pLang'] . '/page' . $sub_page_id . '/' . str_replace('/', '_', str_replace(' ', '_', $sub_page_title));
                    if ($module_field_type == 'attach') {
                        $file_info = $this->fpdo->from('files')->where("id='$item_val2'")->fetch();
                        $ext = $file_info['ext'];
                        $file = $file_info['file'];
                        $folder = $file_info['folder'];
                        $file_path = $_SESSION['_PREF'] . $folder . $file;
                        if ($ext) {
                            $item_val = $_SESSION['_PREF'] . 'view/includes/css/images/filesTypes/' . $ext . '.png';
                        }
                        $item_html = str_replace('##file_path##', $file_path, $item_html);
                    } elseif ($module_field_type == 'DynamicSelect') {
                        $dynamic_table_name = $this->lookupField('cms_modules', 'id', 'table_name', $module_field_plus);
                        $dynamic_table_field = $this->lookupField('cms_module_fields', 'id', 'title', '', " table_id='$module_field_plus' AND is_main='1' ");
                        $item_val = $this->lookupField($dynamic_table_name, 'id', $dynamic_table_field, $item_val);
                    } elseif ($module_field_type == 'photos') {
                        if ($img_settings != "") {
                            
                        } else {
                            if ($temp_feild_settings['resize_type'] == 'crop' || $temp_feild_settings['resize_type'] == 'resize') {
                                $thumb = $this->viewPhoto($item_val, $temp_feild_settings['resize_type'], $temp_feild_settings['width'], $temp_feild_settings['height'], 'css', 1, $_SESSION['dots'], 0);
                            } else {

                                $thumb = $this->viewPhoto($item_val, 'full', '', '', 'css', 1, $_SESSION['dots'], 0);
                            }
                        }

                        $thumb = str_replace(');', '', str_replace('background-image:url(', '', $thumb));
                        $item_val = $thumb;
                    } elseif ($module_field_type == 'date' || $module_field_type == 'datepicker' || $module_field_type == 'datepicker') {
                        $timestamp = strtotime($item_val);
                        if ($item_val && $item_val != '0000-00-00 00:00:00') {
                            if ($template_field_title == 'date_day') {
                                $item_val = date("d", $timestamp);
                            } elseif ($template_field_title == 'date_month') {
                                $month_num = date("m", $timestamp);
                                //Activate this line for month name
                                //$item_val = $months_ar[$month_num];
                                $item_val = $month_num;
                            } elseif ($template_field_title == 'date_year') {
                                $item_val = date("Y", $timestamp);
                            } else {
                                $item_val = date("d-m-Y", $timestamp);
                            }
                        } else {
                            $item_val = '';
                        }
                    } elseif ($module_field_type == 'map') {

                        $map_query = $this->fpdo->from('maps')->where("id='$item_val2'")->fetch();
                        $lat = $map_query['lat'];
                        $lng = $map_query['lng'];
                        $item_val = "<div class='map col-sm-12'>
							<input type='hidden' id='lat' value='$lat'>
							<input type='hidden' id='lng' value='$lng'>
							<div id='map-canvas' class='page-map' style='width:100%; height:300px;'></div>
						</div>";
                    } else {
                        if ($temp_feild_settings['limit']) {
                            $item_val = $this->limit($item_val, $temp_feild_settings['limit']);
                        }
                        $item_val = str_replace('uploads/', $_SESSION['_PREF'] . 'uploads/', $item_val);
                    }


                    $item_html = str_replace('$$' . $template_field_title . '$$', $item_val, $item_html);
                    $item_html = str_replace("##sub_link##", $sub_page_link, $item_html);
                    $item_html = str_replace("##counter##", $counter, $item_html);
                    $template_main_html = str_replace('$$' . $template_field_title . '$$', $item_val2, $template_main_html);
                    /* Start code for photo setting */
                    if ($img_settings != "") {
                        $settingsArray = explode(";", $img_settings);

                        if (count($settingsArray) > 0) {
                            $settingsArrayValues = array();
                            foreach ($settingsArray as $setting) {
                                $setArray = explode(":", $setting);
                                $settingsArrayValues[$setArray[0]] = $setArray[1];
                            }
                            file_put_contents("CropMaual.txt", $item_val);
                            //$thumb = $this->viewThumbnail($item_val, "crop", $settingsArrayValues['w'], $settingsArrayValues['h'], "img", 1, $_SESSION['dots'], $settingsArrayValues);
                            $item_html = $this->viewPhoto($dVal, $settingsArrayValues['type'], $settingsArrayValues['w'], $settingsArrayValues['h'], "img", 1, $_SESSION['dots'], $settingsArrayValues['withLink'], 0, $settingsArrayValues['class'], $settingsArrayValues);
                        }
                    }
                    /* End code for photo setting */
                }
                $item_html = $this->getExtraWidgets($item_html, $row);
                $widget_items_html .= $item_html;
                $counter++;
            }
            $template_main_html = $this->getExtraWidgets($template_main_html, $row);

            $main_page_title = $this->lookupField('cms_pages', 'id', 'title', $page_id);
            $link = $_SESSION['_PREF'] . $_SESSION['pLang'] . '/page' . $page_id . '/pn^/ext_param' . $wid_settings['extar_link'] . '/' . str_replace(' ', '_', $main_page_title);


            $final_html = str_replace("##main_page_title##", $main_page_title, $template_main_html);

            $final_html = str_replace("##template_filters##", $wid_settings['filter_fields'], $final_html);
            $final_html = str_replace("##site_pref##", $_SESSION['_PREF'], $final_html);

            $final_html = str_replace("##template_items##", $widget_items_html, $final_html);
            $final_html = str_replace("##site_pref##", $_SESSION['_PREF'], $final_html);
            $final_html = str_replace('##site_lang##', $_SESSION['pLang'], $final_html);

            if ($wid_settings['pagination_id'] && $ajax == false) {
                $final_html .= $this->getPagination($wid_settings['pagination_id'], $widget_total_numrows, $wid_settings['limit_num'], $link, $widget_id);
            }
        }
        $final_html = $this->getLabels($final_html);
        if (!$final_html) {
            $final_html = '';
        }

        $final_html = stripslashes($final_html);
        return $final_html;
    }

    function getLabels($html) {
        $labels_list = $this->getContents($html, '**', '**');
        foreach ($labels_list as $label) {
            //$label_val = lookupField('langs_keys','id','lang_en','',"l_key='**".$label."**'");
            $label_val = constant($label);
            $html = str_replace('**' . $label . '**', $label_val, $html);
        }
        return $html;
    }

    function getExtraWidgets($html, $row) {
        $extra_widgets_arr = $this->getContents($html, '##wid_start##', '##wid_end##');
        foreach ($extra_widgets_arr as $widget_content) {
            $widget_id = $this->getContents($widget_content, '##wid_id_start##', '##wid_id_end##')[0];

            $widget_condition = $this->getContents($widget_content, '##wid_con_start##', '##wid_con_end##')[0];
            $con_fields_arr = $this->getContents($widget_condition, '##', '##');
            foreach ($con_fields_arr as $field) {
                //$widget_condition = str_replace("##$field##",$row[$field],$widget_condition);
            }

            $extra_widgets = $this->getWidget($widget_id, '', $widget_condition);

            $html = $this->replaceContents($html, '##ws' . $widget_id . '##', '##we' . $widget_id . '##', $extra_widgets);
            $html = str_replace('##ws' . $widget_id . '##', '', str_replace('##we' . $widget_id . '##', '', $html));
        }
        return $html;
    }

    function printWidget($widget_id) {
        $widget = $this->getWidget($widget_id, '', '');
        $isStatic = $this->lookupField('cms_widgets', 'id', 'static_path', $widget_id);
        if ($isStatic) {
            echo $widget = str_replace('##wid_start##', '', str_replace('##wid_end##', '', $widget));
        } else {
            echo $widget = str_replace('##wid_start##', '', str_replace('##wid_end##', '', $widget));
        }
    }

    function getPagination($paging_id, $tp, $LPP, $link, $widget_id = '', $count = '') {

        if ($_REQUEST['pn']) {
            $pn = $page_items = $_REQUEST['pn'];
        } else {
            $pn = 0;
            $page_items = 1;
        }

        $left_items = $tp - ($page_items * $LPP);
        if ($paging_id) {
            switch ($paging_id) {
                case 1:
                    return $this->create_pagination($tp, $pn, $LPP, "$link");
                    break;
                case 2:
                    if ($LPP > 1) {
                        return $this->createPagination_load_more($widget_id);
                    }

                    break;
                default : return '';
                    break;
            }
        } else {
            return '';
        }
    }

    function getPage($page_id) {
        $query = $this->fpdo->from('cms_pages')->where("id='$page_id'")->fetchAll();

        foreach ($query as $row) {
            $page_title = $row['title'];
            $page_html = $row['html'];
            $page_type = $row['type'];
            $widget_id = $row['widget_id'];
            $module_id = $row['module_id'];
            $views = 1 + $row['views'];
            $this->fpdo->update('cms_pages')->set(array('views' => $views))->where("id='$page_id'")->execute();

            $widget_arr = $this->getContents($page_html, '##wid_start##', '##wid_end##');
            $final_html = $item_val = str_replace('uploads/', $_SESSION['_PREF'] . 'uploads/', $page_html);


            foreach ($widget_arr as $widget_content) {
                $widget_id = $this->getContents($widget_content, '##wid_id_start##', '##wid_id_end##')[0];
                $widget_condition = $this->getContents($widget_content, '##wid_con_start##', '##wid_con_end##')[0];

                $template_html = $this->getWidget($widget_id, $page_id, $widget_condition);
                $final_html = str_replace('##wid_start##', '', str_replace('##wid_end##', '', $this->replaceContents($final_html, '##wid_start##', '##wid_end##', $template_html)));
            }

            if (!$module_id && $page_type != 'generated') {
                $final_html = "<div class='page-title single-title'>$page_title</div>" . $final_html;
            }
            $final_html = str_replace('##site_pref##', $_SESSION['_PREF'], $final_html);
            $final_html = str_replace('##site_lang##', $_SESSION['pLang'], $final_html);
            $final_html = stripslashes($final_html);



            return $final_html;
        }
    }

    function getPageSeo($page_id) {

        $query = $this->fpdo->from('cms_pages')->where("id='$page_id'")->fetchAll();

        foreach ($query as $row) {

            $seo_title = $row['seo_title'];
            $seo_keywords = $row['seo_keywords'];
            $seo_description = $row['seo_description'];
            $seo_img = $row['seo_img'];
            if ($seo_img) {
                $thumb = $this->viewPhoto($item_val, 'full', '', '', 'css', 1, $_SESSION['dots'], 0);
                $thumb = str_replace(');', '', str_replace('background-image:url(', '', $thumb));
            }


            $seo = '<title>' . $seo_title . '</title>
					<meta name="title" content="' . $seo_title . '" />
					<meta name="description" content="' . $seo_keywords . '" />
					<meta name="keywords" content="' . $seo_img . '" />
					<meta property="og:title" content="' . $seo_title . '">
					<meta property="og:image" content="' . $seo_img . '">
					<meta property="og:description" content="' . $seo_description . '">
					';

            return $seo;
        }
    }

    function getContents($str, $startDelimiter, $endDelimiter) {
        $contents = array();
        $startDelimiterLength = strlen($startDelimiter);
        $endDelimiterLength = strlen($endDelimiter);
        $startFrom = $contentStart = $contentEnd = 0;
        while (false !== ($contentStart = strpos($str, $startDelimiter, $startFrom))) {
            $contentStart += $startDelimiterLength;
            $contentEnd = strpos($str, $endDelimiter, $contentStart);
            if (false === $contentEnd) {
                break;
            }
            $contents[] = substr($str, $contentStart, $contentEnd - $contentStart);
            $startFrom = $contentEnd + $endDelimiterLength;
        }
        return $contents;
    }

    function replaceContents($str, $needle_start, $needle_end, $replacement) {

        $pos = strpos($str, $needle_start);
        $start = $pos === false ? 0 : $pos + strlen($needle_start);

        $pos = strpos($str, $needle_end, $start);
        $end = $pos === false ? strlen($str) : $pos;

        return substr_replace($str, $replacement, $start, $end - $start);
    }

    function get_string_between($string, $start, $end) {
        $string = " " . $string;
        $ini = strpos($string, $start);
        if ($ini == 0)
            return "";
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        return substr($string, $ini, $len);
    }

    function getFileExtention($file_path) {
        $path_arr = explode('.', $file_path);
        $ext = end($path_arr);
        $ext = $_SESSION['_PREF'] . 'view/includes/css/images/filesTypes/' . $ext . '.png';
        return $ext;
    }

}
?>