<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of field
 *
 * @author mohammed
 */
class field extends utils {

    var $id;
    var $name;
    var $value;
    var $required;
    var $type; // Text filed, number , phone , password , email, brief, details, link, 
    //image, gallery, attached, List, checkbox, radio, true/false
    var $table;
    var $t_value;
    var $t_name;
    var $cols;
    var $row;
    var $length;
    var $extra;
    var $css_class;
    var $placeholder;
    var $where;
    var $WithAdd = false;
    var $withLabel;
    var $map_lng;
    var $map_lat;

    public function WithLabel($withLabel) {
        $this->withLabel = $withLabel;
    }

    public function getWithAdd() {
        return $this->WithAdd;
    }

    public function setWithAdd($WithAdd) {
        $this->WithAdd = $WithAdd;
    }

    public function getWhere() {
        return $this->where;
    }

    public function setWhere($where) {
        $this->where = $where;
    }

    public function setPlaceholder($placeholder) {
        $this->placeholder = $placeholder;
    }

    function __construct() {
        parent::__construct();
        $this->SetTextareaCols();
        $this->SetTextareaRows();
    }

    function SetExtra($extra) {
        $this->extra = $extra;
    }

    function SetTable($table) {
        $this->table = $table;
    }

    function SetTvalue($t_value) {
        $this->t_value = $t_value;
    }

    function SetTname($t_name) {
        $this->t_name = $t_name;
    }

    function SetTypeField($type) {
        $this->type = $type;
    }

    function SetTextareaRows($rows = 5) {
        $this->row = $rows;
    }

    function SetTextareaCols($cols = 25) {
        $this->cols = $cols;
    }

    function SetInputLength($length) {
        $this->length = $length;
    }

    function SetCssClass($css) {
        $this->css_class = $css;
    }

    function SetIdField($id) {
        $this->id = $id;
    }

    function SetNameField($name) {
        $this->name = $name;
    }

    function SetRequiredField($required) {
        $this->required = $required;
    }

    function SetValueField($value) {
        $this->value = stripcslashes($value);
    }

    function setMap($value) {
        $maps_value = $this->fpdo->from('maps')->where('id', $value)->fetch();
        $this->map_lat = $maps_value['lat'];
        $this->map_lng = $maps_value['lng'];
    }

    function GetField() {
        global $cmsMlang;
        switch ($this->type) {
            case "photos": {
                    $FileManager = new FileManager();
                    $res.= $FileManager->SelectPhotos($this->name, $this->value);
                    break;
                }
            case "videos": {
                    $FileManager = new FileManager();
                    $res.= $FileManager->SelectVideos($this->name, $this->value);
                    break;
                }
            case "attach": {
                    $FileManager = new FileManager();
                    $res.= $FileManager->SelectFiles($this->name, $this->value);
                    break;
                }
            case "filed": {
                    $res = $this->getField();

                    break;
                }
            case "date" : {
                    $this->type = 'text';
                    $this->css_class = "datepicker form-control";
                    $res = $this->getInputTextField();
                    break;
                }
            case "text":
            case "email":
            case "tel" :
            case "number" :
            case "password": {
                    $res = $this->getInputTextField();
                    break;
                }

            case "datepicker":
            case "timepicker": {

                    $this->css_class.=" form-control " . $this->type;
                    $res = $this->getInputTextField();
                    break;
                }
            case "date": {

                    $this->css_class.=" form-control datepicker";
                    $res = $this->getInputTextField();
                    break;
                }
            case 'tags': {
                    $this->css_class.=" TagsInput ";
                    $res = $this->getInputTextField();
                    break;
                }
            case "fontawesome": {
                    $this->css_class.=" iconpicker";
                    $res = $this->getInputTextField();
                    break;
                }
            case "FullTextEditor":
            case "textarea":
            case "SimpleTextEditor": {
                    $res = $this->getInputTextArea();
                    break;
                }
            case "DynamicSelect": {
                    $module = $this->fpdo->from('cms_modules')->where('id', $this->extra[0])->fetch();
                    $table = $module['table_name'];
                    $langType = $module['lang_type'];
                    $fileds = $this->fpdo->from('cms_module_fields')->where("table_id='" . $this->extra[0] . "' and is_main='1'")->fetch();

                    $fileds['title'];
                    $this->SetTable($table);
                    if ($langType == 'Field' && $fileds['is_lang_eff'] == '1') {
                        $this->SetTname($fileds['title'] . "_$cmsMlang");
                    } else {
                        $this->SetTname($fileds['title']);
                    }
                    $this->SetTvalue('id');
                    $res = $this->getInputListFromTable();
                    break;
                }
            case "select": {
                
                    $fileds = $this->fpdo->from('cms_module_fields')->where("table_id='" . $_SESSION['cmsMID'] . "' and plus !='' and type='select'")->fetch();
                    $plus_array = explode(',', $fileds['plus']);
                    if ($this->table && $this->table != "") {
                       
                        $res = $this->getInputListFromTable();
                    } else {
                       
                        $res = $this->getInputListPlus();
                    } 

                    break;
                }
            case "select+" : {
                    if ($this->table && $this->table != "") {
                        $res = $this->getInputListFromTable();
                    } else {

                        $res = $this->getInputList();
                    }
                    break;
                }


            case "id": {

                    $res = $this->getInputHiddenField($this->name, $this->value, "hidden");
                    break;
                }
            case "checkbox": {


                    if ($this->table && $this->table != "") {
                        $res = $this->getInputCheckboxFromTable();
                    } else {
                        $res = $this->getInputCheckbox();
                    }

                    break;
                }
            case "DynamicCheckbox": {
                    if ($this->table && $this->table != "") {
                        $res = $this->getInputCheckboxFromTable();
                    } else {
                        $res = $this->getInputCheckbox();
                    }

                    break;
                }
            case "checkboxPlus": {
                    $fileds = $this->fpdo->from('cms_module_fields')->where("table_id='" . $_SESSION['cmsMID'] . "' and plus !='' and type='checkboxPlus'")->fetch();
                    $plus_array = explode(',', $fileds['plus']);
                    if ($this->table && $this->table != "") {
                        $res = $this->getInputCheckboxFromTable();
                    } elseif ($fileds['plus'] != "") {
                        $res = $this->getInputCheckboxPlus();
                    } else {
                        $res = $this->getInputCheckbox();
                    }
                    break;
                }
            case "map": {
                    $res = $this->getMap();
                    break;
                }
            case "flag": {
                    $res = $this->getInputCheckboxFlag();
                    break;
                }

            default :
                $res = $this->getInputHiddenField("hidden");
                break;
        }
        return $res;
    }

    function getInputHiddenField($type = "hidden") {
        $res = '<input name="' . $this->name . '" value="' . $this->value . '" type="' . $type . '">';
        return $res;
    }

    function getLabel() {
        $res = '<input name="' . $this->name . '" value="' . $this->value . '" type="' . $type . '">';
    }

    function getInputTextField() {
        $res = '<input id="' . $this->id . '" name="' . $this->name . '" value="' . $this->value . '" type="' . $this->type . '" ' . $this->required . ' size="' . $this->length . '" class="' . $this->css_class . '" placeholder="' . $this->placeholder . '">';
        return $res;
    }

    function getInputCheckboxFromTable() {

        $query = $this->fpdo->from($this->table)->select(" $this->t_value , $this->t_name");

        if ($this->where != "") {
            $query->where($this->where);
        }
        $query->orderBy($this->t_name . " ASC")->execute();
        $values = explode(',', $this->value);
        $result = "";
        foreach ($query as $row) {
            $checked = "";
            if (in_array($row[$this->t_value], $values) == true) {
                $checked = "checked = 'checked'";
            }
            $result.= "<div  class='checkbox $this->css_class'>
            <label>
             <input type='checkbox' name='$this->name[]' $checked value='" . $row[$this->t_value] . "'>
              " . $row[$this->t_name] . "
            </label>
        </div>";
        }
        return $result;
    }

    function getInputCheckboxPlus() {
        $fileds = $this->fpdo->from('cms_module_fields')->where("table_id='" . $_SESSION['cmsMID'] . "' and plus !='' and type='checkboxPlus'")->fetch();
        $plus_array = explode(',', $fileds['plus']);
        $query = $this->fpdo->from($this->table)->select(" $this->t_value , $this->t_name");

        if ($this->where != "") {
            $query->where($this->where);
        }
        $query->orderBy($this->t_name . " ASC")->execute();
        $values = explode(',', $this->value);
        $result = "";
        foreach ($plus_array as $checkPlus) {
            $checked = "";
            if (in_array($checkPlus, $values) == true) {
                $checked = "checked = 'checked'";
            }
            $res.= '<input ' . $checked . ' id="' . $this->id . '" name="' . $this->name . '[]" value="' . $checkPlus . '" type="checkbox" ' . $this->required . ' size="' . $this->length . '" class=" ' . $this->css_class . '" >&nbsp;<lable>' . $checkPlus . '<lable><br>';
        }
        return $res;
    }

    function getInputCheckbox() {
        if ($this->value) {
            $checked = "checked";
        } else {
            $checked = "";
            $this->value = 1;
        }
        $res = '<input ' . $checked . ' id="' . $this->id . '" name="' . $this->name . '" value="' . $this->value . '" type="checkbox" ' . $this->required . ' size="' . $this->length . '" class=" ' . $this->css_class . '" >';
        return $res;
    }

    function getInputCheckboxFlag() {
        if ($this->value == '1' || $this->value == 1) {
            $checked = "checked='checked'";
        } else {
            $checked = "";
        }
        $res = '<input ' . $checked . ' id="' . $this->id . '" name="' . $this->name . '" value="1" type="checkbox" ' . $this->required . ' size="' . $this->length . '" class=" ' . $this->css_class . '" >';
        return $res;
    }

    function getInputTextArea() {
        $res = '<textarea id="' . $this->id . '" name="' . $this->name . '"  class=" ' . $this->type . ' ' . $this->css_class . ' " rows="' . $this->row . '" cols="' . $this->cols . '" placeholder="' . $this->placeholder . '">' . $this->value . '</textarea>';
        return $res;
    }

    function getInputListPlus() {
        $fileds = $this->fpdo->from('cms_module_fields')->where("table_id='" . $_SESSION['cmsMID'] . "' and plus !='' and type='select'")->fetch();
        $plus_array = explode(',', $fileds['plus']);
        $res = '<select class="' . $this->css_class . '" name="' . $this->name . '" id="' . $this->id . '" ' . $this->required . '>';
        if ($this->required == '') {
            $res.= '<option value="">------</option>';
        }
        $array = "";
        if ($plus_array != "") {
            $array = $plus_array;
        } else {
            $array = $this->extra;
        }
        foreach ($this->extra as $row) {
            if ($row == $this->value) {
                $selected = "selected='selected'";
            } else {
                $selected = "";
            }
            $res.= '<option value="' . $row . '" ' . $selected . '> ' . $row . ' </option>';
        }

        $res.= '</select>';
        return $res;
    }

    function getInputList() {


        $res = '<select class="' . $this->css_class . '" name="' . $this->name . '" id="' . $this->id . '" ' . $this->required . '>';
        if ($this->required == '') {
            $res.= '<option value="">------</option>';
        }

        foreach ($this->extra as $row) {
            if ($row == $this->value) {
                $selected = "selected='selected'";
            } else {
                $selected = "";
            }
            $res.= '<option value="' . $row . '" ' . $selected . '> ' . $row . ' </option>';
        }

        $res.= '</select>';
        return $res;
    }

    function getInputListFromTable() {

        if ($this->where != "") {
            $query = $this->fpdo->from($this->table)
                            ->select(" $this->t_value , $this->t_name")->
                            where($this->where)->
                            orderBy($this->t_name . " ASC")->execute();
        } else {
            $query = $this->fpdo->from($this->table)
                            ->select(" $this->t_value , $this->t_name")->
                            orderBy($this->t_name . " ASC")->execute();
        }


        $res = '<div style="position:relative"><select class="' . $this->css_class . '" name="' . $this->name . '" id="' . $this->id . '" ' . $this->required . '>';
        if ($this->required == '') {
            $res.= '<option value="">------</option>';
        }

        foreach ($query as $row) {
            if ($row[$this->t_value] == $this->value) {
                $selected = "selected";
            } else {
                $selected = "";
            }
            $res.= '<option value="' . $row[$this->t_value] . '" ' . $selected . '> ' . $row[$this->t_name] . ' </option>';
        }

        $res.= '</select>';

        $res.="</div>";
        return $res;
    }

    function getInputListFromTablePlus() {

        if ($this->where != "") {
            $query = $this->fpdo->from($this->table)
                            ->select(" $this->t_value , $this->t_name")->
                            where($this->where)->
                            orderBy($this->t_name . " ASC")->execute();

            //  ;
        } else {
            $query = $this->fpdo->from($this->table)
                    ->select(" $this->t_value , $this->t_name")->
                    orderBy($this->t_name . " ASC")->
                    execute();
        }


        $res = '<div style="position:relative"><select class="' . $this->css_class . '" name="' . $this->name . '" id="' . $this->id . '" ' . $this->required . '>';
        if ($this->required == '') {
            $res.= '<option value="">------</option>';
        }

        foreach ($query as $row) {
            if ($row[$this->t_value] == $this->value) {
                $selected = "selected";
            } else {
                $selected = "";
            }
            $res.= '<option value="' . $row[$this->t_value] . '" ' . $selected . '> ' . $row[$this->t_name] . ' </option>';
        }

        $res.= '</select>';

        $res.= '<input type="text" style="position:absolute;left:0px;width:50%;display:none;top:0px;" placeholder="Enter ' . $this->name . ' name" class="new-option" data-table="' . $this->table . '" data-field="' . $this->t_name . '" data-namef="' . $this->name . '">';
        $res.= '&nbsp;&nbsp;<a href="javascript:;" class="plus-add"><span class="fa fa-plus"></span></a>';

        $res.="</div>";
        return $res;
    }

    function getMap() {

        if ($this->map_lat && $this->map_lng) {
            $lat = $this->map_lat;
            $lng = $this->map_lng;
        } else {
            $lat = 33.5074755;
            $lng = 36.2828954;
        }



        $res .="
		<script>
		function initialize() {
			var latLng = new google.maps.LatLng($lat,$lng);
			var map = new google.maps.Map(document.getElementById('mapCanvas'), {zoom: 8,center: latLng,mapTypeId: google.maps.MapTypeId.ROADMAP});
			var marker = new google.maps.Marker({position: latLng,title: 'Point A',map: map,draggable: true});
			updateMarkerPosition(latLng);
			geocodePosition(latLng);
			google.maps.event.addListener(marker, 'dragstart', function() {updateMarkerAddress('Dragging...');});
			google.maps.event.addListener(marker, 'drag', function() {updateMarkerStatus('Dragging...');updateMarkerPosition(marker.getPosition());});
			google.maps.event.addListener(marker, 'dragend', function() {updateMarkerStatus('Drag ended');geocodePosition(marker.getPosition());});
		}
		</script>
		<script type='text/javascript' src='../../includes/plugins/jQuery/map.js'></script>";
        $res .= '<div id="mapCanvas" style="width:100%;height:300px; background:#bababa;"></div>
				<div id="infoPanel">
					<b>Marker status:</b>
					<div id="markerStatus"><i>Click and drag the marker.</i></div>
					<b>Current position:</b>
					<div id="info"></div>
					<b>Closest matching address:</b>
					<div id="address"></div>
				</div>
				<input type="hidden" id="lat" name="lat" value="' . $lat . '" />
				<input type="hidden" id="lng" name="lng" value="' . $lng . '" />';
        return $res;
    }

}
