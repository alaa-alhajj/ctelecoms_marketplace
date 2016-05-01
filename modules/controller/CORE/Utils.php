<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UTILS
 *
 * @author mohammed
 */
class utils {

    protected $fpdo;
    var $icon;
    var $field;

    function __construct() {

        global $fpdo;
        $this->fpdo = & $fpdo;

        $this->icons = new Icons();
        //$this->field = new field();
    }

    public function back($link, $classCss = "") {
        if (is_string($link)) {
            return "<a href='$link' class='btn btn-back $classCss'>" . $this->getConstant("Back") . "</a>&nbsp;";
        } else {
            return "<button type='button' class='btn btn-back $classCss' onClick='history.back();'>" . $this->getConstant("Back") . "</button>&nbsp;";
        }
    }

    public function randomStringUtil($length = 5) {
        $type = 'num';
        $randstr = '';
        srand((double) microtime() * 1000000);

        $chars = array('1', '2', '3', '4', '5', '6', '7', '8', '9', '0');
        if ($type == "alpha") {
            array_push($chars, '1');
        }

        for ($rand = 0; $rand < $length; $rand++) {
            $random = rand(0, count($chars) - 1);
            $randstr .= $chars[$random];
        }
        return $randstr;
    }

    public function SkipButton($link, $classCss = "") {
        if (is_string($link)) {
            return "<a href='$link' class='btn btn-back $classCss'>" . $this->getConstant("Skip") . "</a>&nbsp;";
        } else {
            return "<button type='button' class='btn btn-back $classCss' onClick='" . $link . "'>" . $this->getConstant("Skip") . "</button>&nbsp;";
        }
    }

    public function ADDButton($link, $name = "", $classCss = "") {
        if ($link !="") {
            return '<a href="'.$link.'" name="InsertNew" class="btn ' . $classCss . '" >'.$name.'</a>';
        }else{
             return '<input type="submit" name="InsertNew" class="btn ' . $classCss . '" value="' . $name . '">';
        }
    }

    public function getModuleName($module_id) {
        $query = $this->fpdo->from('cms_modules')->where("id='$module_id'")->fetch();
        if ($query['title'] != "") {
            return $query['title'];
        } else {
            return $this->getConstant("Dashboard");
        }
    }

    public function getModuleNameOperation() {
        $filename = basename($_SERVER['PHP_SELF']);
        if (strpos($filename, 'edit') !== false || strpos($filename, 'update') !== false) {
            return "Update Item";
        } elseif (strpos($filename, 'new') !== false || strpos($filename, 'insert') !== false || strpos($filename, 'enter') !== false) {

            return "New Item";
        } else {
            return "";
        }
    }

    public function make_tag_html($source, $tag = "div", $class = '') {
        return "<$tag class='$class'>" . $source . "</$tag>";
    }

    public function getConstant($string) {
        if (constant($string) != "") {
            return constant($string);
        } else {
            return $string;
        }
    }

    public function make_module_btns($btns) {
        $btns = explode(',', $btns);
        $return = "";
        foreach ($btns as $btn) {

            $btn = explode(':', $btn);
            if (strtolower($btn[0]) == 'delete') {
                $btn[1] = "javascript:void(0);";
                $id = "AskDelete";
            }
            if (strtolower($btn[0]) == 'add') {
                if ($btn[1] == "") {
                    if (strtolower(end(explode('.', $_SERVER['REQUEST_URI']))) == 'php') {
                        $btn[1] = $_SERVER['REQUEST_URI'] . "?action=add";
                    } else {
                        $btn[1] = str_replace("&action=add", "", $_SERVER['REQUEST_URI']) . "&action=add";
                    }
                    $id = "Add";
                }
            }
            $return.="<a id='$id' href='" . $btn[1] . "'>" . $btn[0] . "</a>";
        }
        return $return;
    }

    public function checkSave($module) {
        @session_start();
        echo "<script></script>";
        if ($module != "") {
            if ($_SESSION["saveFormStatus" . $module] != "") {
                $message = $_SESSION["saveFormMessage" . $module];
                if ($_SESSION["saveFormStatus" . $module] == "success") {
                    echo "<script>notificationMessage(true,'$message')</script>";
                } elseif ($_SESSION["saveFormStatus" . $module] == "failed") {
                    echo "<script>notificationMessage(false,'$message')</script>";
                }
                unset($_SESSION["saveFormStatus" . $module]);
            }
        }
    }

    public function redirect($url) {
        @session_start();

        if (isset($_SESSION['saveFormStatus' . $_SESSION['cmsMID']]) == true && $_SESSION['saveFormStatus' . $_SESSION['cmsMID']] != "") {
            $status = $_SESSION['saveFormStatus' . $_SESSION['cmsMID']];
            $message = $_SESSION["saveFormMessage" . $_SESSION['cmsMID']];
            if ($status == 'success') {
                echo "<script>setTimeout(function(){window.location.replace('$url');});</script>";
            } else {
                echo "<script>notificationMessage(false,'$message')</script>";
                unset($_SESSION["saveFormStatus" . $_SESSION['cmsMID']]);
            }
        } else {
            echo "<script>window.location.replace('$url');</script>";
        }
    }

    public function getValuesImplode($table, $field_id, $field_name, $values) {

        $query = $this->fpdo->from($table)->where("`$field_id` in ($values)")->fetchAll();
        $return = array();
        foreach ($query as $row) {
            array_push($return, $row[$field_name]);
        }
        return implode(",", $return);
    }

    public function create_pagination($tp, $pn, $LPP, $link, $lang = 'en', $class = "") {
        if ($_REQUEST['lpp'] != "") {
            $LPP = $_REQUEST['lpp'];
        }
        $first = "";
        $next = "";
        $last = "";
        $prev = "";
        $itemsCountText = "<span class='count-items-pagination'>($tp Items)</span>";
        if ($lang != 'ar') {
            $first = "glyphicon-backward";
            $next = "glyphicon-chevron-left";
            $last = "glyphicon-forward";
            $prev = "glyphicon-chevron-right";
        } else {
            $first = "glyphicon-forward";
            $next = "glyphicon-chevron-right";
            $last = "glyphicon-backward";
            $prev = "glyphicon-chevron-left";
        }

        $pageLang = $_SESSION[_langPref . 'pageLang'];

        $pages = ceil($tp / $LPP) + 1;

        if (!$pn)
            $pn = 0;

        $start = max(0, ($pn - 2));

        $end = min($pages - 1, ($start + 5));

        $start = max(0, ($end - 5));


        $iPagesOfText = "<span class='count-items-pagination'>Page " . ($pn + 1) . " Of " . ($pages - 1) . "</span>";
        $GoToPageDropDown = "<span class='count-items-pagination'>Go To</span>" . '<div class="dropup pull-left">
  <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' . ($pn + 1) . " <span class='caret'></span></button>";
        $GoToPageDropDown.='<ul class="dropdown-menu drop-pagination" aria-labelledby="dropdownMenu2">';
        for ($i = 1; $i < $pages; $i++) {
            $rewrite_link1 = str_replace('^', $i - 1, $link);
            $GoToPageDropDown.="<li><a href='$rewrite_link1'>$i</a></li>";
        }
        $GoToPageDropDown.='</ul></div>';
        if ($LPP == 10) {
            $t = 10;
        } elseif ($LPP == 20) {
            $t = 20;
        } elseif ($LPP == 50) {
            $t = 50;
        } elseif ($LPP == 100) {
            $t = 100;
        } elseif ($LPP == $tp + 1) {
            $t = "All";
        } else {
            $t = $LPP;
        }
        $DisplayDiv = "<span class='count-items-pagination befor-padding'>Show</span>" . '<div class="dropup pull-left">
  <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' . $t . " <span class='caret'></span></button>";
        $DisplayDiv.='<ul class="dropdown-menu drop-pagination" aria-labelledby="dropdownMenu3">';
        if ($tp > 10) {
            $rewrite_link = str_replace('^', floor($pn * $LPP / 10), $link) . "&lpp=10";
            $DisplayDiv .= " <li><a href='$rewrite_link'>" . (($LPP == 10) ? "<u>10</u>" : "10") . "</a></li>";
        }
        if ($tp > 20) {
            $rewrite_link = str_replace('^', floor($pn * $LPP / 20), $link) . "&lpp=20";
            $DisplayDiv .= " <li><a href='$rewrite_link'>" . (($LPP == 20) ? "<u>20</u>" : "20") . "</a></li>";
        }
        if ($tp > 50) {
            $rewrite_link = str_replace('^', floor($pn * $LPP / 50), $link) . "&lpp=50";
            $DisplayDiv .= " <li><a href='$rewrite_link'>" . (($LPP == 50) ? "<u>50</u>" : "50") . "</a></li>";
        }
        if ($tp > 100) {
            $rewrite_link = str_replace('^', floor($pn * $LPP / 100), $link) . "&lpp=100";
            $DisplayDiv .= " <li><a href='$rewrite_link'>" . (($LPP == 100) ? "<u>100</u>" : "100") . "</a></li>";
        }
        $rewrite_link = str_replace('^', floor($pn * $LPP / ($tp + 1)), $link) . "&lpp=" . ($tp + 1);
        $DisplayDiv .= " <li><a href='$rewrite_link'>" . (($LPP == $tp + 1) ? "<u>ALL</u>" : "ALL") . "</a>";
        $DisplayDiv.='</ul></div>';
        $res = '<div class="' . $class . '">' . $iPagesOfText . $itemsCountText . '<ul class="pagination">';
        if ($pn > 0) {

            $rewrite_link = str_replace('^', 0, $link);

            $res .= "<li><a href='$rewrite_link'><span class='glyphicon " . $first . "'></span></a></li>";

            $rewrite_link = str_replace('^', max(0, ($pn - 1)), $link);

            $res .= "<li><a href='$rewrite_link'><span class='glyphicon " . $next . "'></span></a></li>";
        } else {

            $res .= "<li class='disabled'><a><span class='glyphicon " . $first . "'></span></a></li>";

            $res .= "<li class='disabled'><a><span class='glyphicon " . $next . "'></span></a></li>";
        }
        $total_pages = ceil($tp / $LPP);

        if ($total_pages == 0) {
            $total_pages = 1;
        }
        for ($i = $start; $i < $end; $i++) {

            $rewrite_link = str_replace('^', ($i), $link);

            if ($i == $pn)
                $res .= '<li class="active"><a>' . ($i + 1) . '</a></li> ';
            else
                $res .= "<li><a href='$rewrite_link'>" . ($i + 1) . "</a></li> ";
        }
        if ($pn + 2 < $pages) {



            $rewrite_link = str_replace('^', ($pn + 1), $link);

            $res .= "<li><a href='$rewrite_link'><span class='glyphicon " . $prev . "'></span></a></li>";

            $rewrite_link = str_replace('^', ($pages - 2), $link);

            $res .= "<li><a href='$rewrite_link'><span class='glyphicon " . $last . "'></span></a></li>";
        } else {

            $res .= "<li class='disabled'><a><span class='glyphicon " . $prev . "'></span></a></li>";

            $res .= "<li class='disabled'><a><span class='glyphicon " . $last . "'></span></a></li>";
        }
        $pages = "";
        $res .= "</ul>$GoToPageDropDown $DisplayDiv</div>";

        if ($tp > 0) {

            if (($tp / $LPP) > 1) {

                return $res;
            } else {

                return '<div class="' . $class . '">' . $itemsCountText . $DisplayDiv . '</div>';
            }
        }
    }

    function lookupField($table, $id_field, $lookup_field, $id_value, $where = '') {

        if ($where) {
            $query = $this->fpdo->from($table)->select($lookup_field)->where($where)->fetch();
        } else {
            $query = $this->fpdo->from($table)->select($lookup_field)->where($id_field, $id_value)->fetch();
        }
        return $query[$lookup_field];
    }

    function limit($text, $limit) {
        if (strlen($text) > $limit) {
            $to = strpos($text, " ", $limit);
            if ($to !== false) {
                $text = substr($text, 0, $to);
            }
            $text.= ' ....';
        }
        return $text;
    }

    function getFileEx($file) {
        $ex = explode(".", $file);
        return strtolower(end($ex));
    }

    function Files($files, $first = 1) {
        $result = $this->fpdo->from('files')->where("id in ($files)")->orderBy("FIELD(id,$files)")->fetch();
        return $result['id'];
    }

    function getOrgFiles($files, $withFolder = 1, $path) {
        $str = '';
        if ($files != '' && $files != ',') {
            $result = $this->fpdo->from('files')->where("id in ($files)")->orderBy("FIELD(id,$files)")->execute();

            $i = 0;
            foreach ($result as $row) {
                $str = "";
                $file = $row['file'];
                $folder = $row['folder'];
                if ($withFolder == 1) {
                    $str.=$folder;
                }
                $str.=$file;

                if (file_exists($path . $str)) {
                    return $str;
                    break;
                }
                $i++;
            }
        }
        //return $str;
    }

    function selectFile($filedName, $files = '', $multiple = 1, $onlyImages = 1) {
        $str = '<link type="text/css" rel="stylesheet" href="../../includes/File_Manager/css/style.css"/>	
	<script src="../../includes/File_Manager/js/script.js"></script>	
	
	<script> $(document).ready(function(){loadSelFiles(\'' . $filedName . '\',\'' . $files . '\',' . $multiple . ',' . $onlyImages . ');})</script>
	<div id="sf_' . $filedName . '"></div>';
        return $str;
    }

    function resizeToFile($img, $w, $h, $newfilename) {
        if (file_exists($newfilename)) {
            return $newfilename;
        }
        //Check if GD extension is loaded
        if (!extension_loaded('gd') && !extension_loaded('gd2')) {
            trigger_error("GD is not loaded", E_USER_WARNING);
            return false;
        }
        //Get Image size info
        $imgInfo = getimagesize($img);
        switch ($imgInfo[2]) {
            case 1: $im = imagecreatefromgif($img);
                break;
            case 2: $im = imagecreatefromjpeg($img);
                break;
            case 3: $im = imagecreatefrompng($img);
                break;
            default: trigger_error('Unsupported filetype!', E_USER_WARNING);
                break;
        }
        //If image dimension is smaller, do not resize
        if ($imgInfo[0] <= $w && $imgInfo[1] <= $h) {
            $nHeight = $imgInfo[1];
            $nWidth = $imgInfo[0];
            return $img;
        } else {

            //yeah, resize it, but keep it proportional
            $rate = (($w / $imgInfo[0]) < ($h / $imgInfo[1])) ? ($w / $imgInfo[0]) : ($h / $imgInfo[1]);
            $nWidth = $imgInfo[0] * $rate;
            $nHeight = $imgInfo[1] * $rate;
        }
        $nWidth = round($nWidth);
        $nHeight = round($nHeight);

        $newImg = imagecreatetruecolor($nWidth, $nHeight);

        /* Check if this image is PNG or GIF, then set if Transparent */
        if (($imgInfo[2] == 1) OR ( $imgInfo[2] == 3)) {
            imagealphablending($newImg, false);
            imagesavealpha($newImg, true);
            $transparent = imagecolorallocatealpha($newImg, 255, 255, 255, 127);
            imagefilledrectangle($newImg, 0, 0, $nWidth, $nHeight, $transparent);
        }
        imagecopyresampled($newImg, $im, 0, 0, 0, 0, $nWidth, $nHeight, $imgInfo[0], $imgInfo[1]);

        //Generate the file, and rename it to $newfilename
        switch ($imgInfo[2]) {
            case 1: imagegif($newImg, $newfilename);
                break;
            case 2: imagejpeg($newImg, $newfilename, 100);
                break;
            case 3: imagepng($newImg, $newfilename);
                break;
            default: trigger_error('Failed resize image!', E_USER_WARNING);
                break;
        }

        return $newfilename;
    }

    function switcher($table, $id, $col, $value, $switch, $f_id = "id", $constant = "") {
        if ($value == 1 || $value == '1') {

            $icon = 'fa fa-check';
            $class = 'true';
        } elseif ($value == 0 || $value == '0') {

            $icon = 'fa fa-close';
            $class = 'false';
        }
        return '
       <a class="' . $class . '" title="' . $this->getConstant($col) . '" data-toggle="tooltip" data-switcher="' . $switch . '" data-fid="' . $f_id . '" data-id="' . $id . '" data-table="' . $table . '" data-col="' . $col . '" data-val="' . $value . '">
       <i class="' . $icon . '"></i>
           <span>' . $this->getConstant($constant) . '</span>
       </a>';
    }

    function ViewPhotos($photos, $n = 1, $total = 1, $w = 100, $h = 100) {
        $path = '../../../';
        $Ext_arr_images = array("jpg", "jpeg", "gif", "png");
        //$photos = $this->Files($photos);
        $photos = $this->getOrgFiles($photos, 1, $path);
        $file = '';
        $allPhotos = '';



        $reziseFolder = "uploads/cash/thumb_";
        $nophoto = '<div class=" listViewFileDiv noPhoto" style="width:' . $w . 'px; height:' . $h . 'px">&nbsp;</div>';


        $photo = $path . $photos;
        $photo;
        if (file_exists($photo) && $photos != "") {
            $photoPath = end(explode("/", $photos));
            $photoN = $this->resizeToFile($photo, $w, $h, "../../../" . $reziseFolder . $photoPath);
            return '<div class="imageView" style="width:' . $w . 'px;height:' . $h . 'px;overflow:hidden"><a href="' . $photo . '" class="html5lightbox" data-thumbnail="' . $photoN . '"><img src="' . $photoN . '" width="' . $w . '"  border="0" ></a></div>';
        } elseif ($photos != "" && !in_array(strtolower(end(explode(".", $photos))), $Ext_arr_images)) {
            return '<div class="imageView" style="width:' . $w . 'px;height:' . $h . 'px;overflow:hidden"><img src="../../includes/File_Manager/filemanager/img/ico/' . strtolower(end(explode(".", $photos))) . '.jpg" width="' . $w . '"  border="0" ></div>';
        } else {
            return '<div class="imageView" style="width:' . $w . 'px;height:' . $h . 'px;overflow:hidden"><img src="../../includes/File_Manager/filemanager/img/ico/no_img.jpg" width="' . $w . '"  border="0" ></div>';
        }

        return $allPhotos;
    }

    function youtube_id($url) {
        $pattern = '%^# Match any youtube URL
        (?:https?://)?  # Optional scheme. Either http or https
        (?:www\.)?      # Optional www subdomain
        (?:             # Group host alternatives
          youtu\.be/    # Either youtu.be,
        | youtube\.com  # or youtube.com
          (?:           # Group path alternatives
            /embed/     # Either /embed/
          | /v/         # or /v/
          | /watch\?v=  # or /watch\?v=
          )             # End path alternatives.
        )               # End host alternatives.
        ([\w-]{10,12})  # Allow 10-12 for 11 char youtube id.
        $%x'
        ;
        $result = preg_match($pattern, $url, $matches);
        if (false !== (bool) $result) {
            return $matches[1];
        }
        return false;
    }

    function Filtering($filter) {
        
    }

    function getNewItemOrder($table, $ordering_col, $unique_cond = '') {


        $unique_cond = str_replace(array('where', 'Where', 'WHERE'), ' ', $unique_cond);
        $result = $this->fpdo->from($table)->select("MAX($ordering_col) as mx")->where($unique_cond)->fetch();

        if ($result['mx'])
            return $max = $result['mx'] + 1;
        else
            return 1;
    }

    function getTableTitleField($table_id) {
        if (basename($_SERVER['PHP_SELF']) == 'listModule_fields.php') {
            $module = $this->fpdo->from('cms_modules')->where('id', $table_id)->fetch();
            $table = $module['title'];
            return "| Fields of table <b>$table</b>";
        }
    }

    function createComboBoxFiltered($table, $id_field, $value_field, $id_value, $field_name, $condition = '', $required = "", $class = "") {

        $result = "<select class=\"$class\" name=\"$field_name\" id=\"$field_name\" class=\"$required\">\n";

        $result .= "<option></option>\n";

        $query = $this->fpdo->from($table)->select(" $id_field , $value_field ")->orderBy("`$value_field` asc");


        if ($condition != null && $condition != "") {
            $query = $query->where($condition);
        }

        $query = $query->fetchAll();

        $i = 0;

        foreach ($query as $row) {

            $id = $row[$id_field];

            $filds = explode(",", $value_field);

            $ind = 0;
            $value = "";

            while ($ind < count($filds)) {

                $value.= $row[$filds[$ind]] . " ";

                $ind++;
            }

            $result .= "<option ";

            if ($id_value != null && $id_value != "" && $id_value == $id)
                $result .= "selected ";

            $result .= "value=\"$id\">" . stripslashes($value) . "</option>\n";

            $i++;
        }

        $result .= "</select>\n";

        return $result;
    }

    function getMenuLeftSideBar($group_id) {
        global $ob_roles;
        global $module_id;
        echo $this->childMenu(0, $module_id, $group_id);
        if ($group_id == 1) {
            // echo $ob_roles->getUserMenu($group_id, 0);
        } else {
            
        }
    }

    function childMenu($p_id = 0, $module_id, $group_id) {
        global $ob_roles;
        $query = $this->fpdo->from('cms_menu')->where('p_id', $p_id)->orderBy('item_order asc')->fetchAll();
        $count = count($query);
        $return = "";
        if ($count > 0) {
            if ($p_id != 0) {
                $return.="<ul class='treeview-menu'>";
            }
            foreach ($query as $row) {
                if ($row['icon'] != "" && $p_id == 0) {
                    $icon = "<i class='fa " . $row['icon'] . "'></i>";
                } else {
                    $icon = "<i class='fa fa-circle-o'></i>";
                }
                $mod_id = $row['module_id'];
                $static_module = $this->lookupField('cms_modules', 'id', 'is_static', $mod_id);
                $static_module_link = $this->lookupField('cms_modules', 'id', 'static_path', $mod_id);
                $publish_module = $this->lookupField('cms_modules', 'id', 'publish', $mod_id);
                $viewLink = 0;
                if ($group_id == 1) {
                    $publish_module = 1;
                    $viewLink = 1;
                } elseif ($mod_id == 0) {
                    $viewLink = 1;
                } elseif ($ob_roles->getUserRoles($group_id, $mod_id, 'list') != "" && $publish_module == 1) {
                    $viewLink = 1;
                }
                if ($static_module == 1) {
                    $module_link = '../../views/' . $static_module_link . '?cmsMID=' . $mod_id;
                } elseif ($mod_id == 0) {
                    $module_link = "#";
                } else {
                    $module_link = '../../views/cms_modules/listModules.php?cmsMID=' . $mod_id;
                }
                if ($mod_id == $module_id) {

                    $classActive = 'active';
                } elseif (count($this->fpdo->from('cms_menu')->where("p_id='" . $row['id'] . "' and module_id='$module_id'")->fetchAll()) > 0) {
                    $classActive = 'active';
                } else {
                    $classActive = '';
                }
                if (count($this->fpdo->from('cms_menu')->where("p_id='" . $row['id'] . "'")->fetchAll()) > 0) {
                    $leftSideIcon = '<i class="fa fa-angle-left pull-right"></i>';
                } else {
                    $leftSideIcon = "";
                }
                $title = $row['title'];
                if ($p_id == 0) {
                    $title = "<span>" . $row['title'] . "</span>";
                }
                if ($viewLink == 1) {
                    $return.="<li class='treeview $classActive'><a href='$module_link'>$icon" . $title . "$leftSideIcon</a>" . $this->childMenu($row['id'], $module_id, $group_id) . " </li>";
                }
            }
            if ($p_id != 0) {
                $return.="</ul>";
            }
        }
        // echo $return;
        return $return;
    }

    function getHome() {
        $link = "../home/home.php?cmsMID=30";
        return $string.='<li><a href="' . $link . '"><i class=""></i>' . $this->getConstant("Home") . '</a></li>';
    }

    function getPath() {
        global $module_id;
        $module_id;
        $query = $this->fpdo->from('cms_menu')->where("module_id='$module_id'")->fetch();
        $title = $query['title'];
        $icon = $query['icon'];
        $p_id = $query['p_id'];
        $module_id = $query['module_id'];
        $queryModule = $this->fpdo->from('cms_modules')->where("id='$module_id'")->fetch();
        $is_static = $queryModule['is_static'];
        $static_path = $queryModule['static_path'];
        if ($is_static && $static_path != "") {
            $link = "../" . $static_path . "?cmsMID=$module_id";
        } elseif ($module_id == 0) {
            $link = "#";
        } else {
            $link = "../cms_modules/listModules.php?cmsMID=" . $module_id;
        }
        if ($this->getModuleNameOperation()) {
            $CurrentModule = '<li><a href="' . $link . '"><i class="fa ' . $icon . '"></i> ' . $title . '</a></li>';
            $CurrentModule .= '<li class="active"> ' . $this->getModuleNameOperation() . '</li>';
        } else {
            $CurrentModule = '<li class="active"><i class="fa ' . $icon . '"></i> ' . $title . '</li>';
        }
        $s = $this->getParentPath($p_id);
        $s = explode(',', $s);

        $s = array_reverse($s, true);

        $s = implode('', $s);
        return $this->getHome() . $s . $CurrentModule;
    }

    function array_swap_forward($arr, $elem) {
        $ndx = array_search($elem, $arr) - 1;
        $b4 = array_slice($arr, 0, $ndx);
        $mid = array_reverse(array_slice($arr, $ndx, 2));
        $after = array_slice($arr, $ndx + 2);

        return array_merge($b4, $mid, $after);
    }

    function getParentPath($p_id) {
        if ($p_id != 0) {
            $string = "";
            $query = $this->fpdo->from("cms_menu")->where("id='$p_id'")->fetch();
            $title = $query['title'];
            $icon = $query['icon'];
            $pp_id = $query['p_id'];
            $module_id = $query['module_id'];
            $queryModule = $this->fpdo->from('cms_modules')->where("id='$module_id'")->fetch();
            $is_static = $queryModule['is_static'];
            $static_path = $queryModule['static_path'];
            if ($is_static && $static_path != "") {
                $link = "../" . $static_path . "?cmsMID=$module_id";
            } elseif ($module_id == 0) {
                $link = "#";
            } else {
                $link = "../cms_modules/listModules.php?cmsMID=" . $module_id;
            }
            return $string.='<li><a href="' . $link . '"><i class="fa ' . $icon . '"></i>' . $title . '</a></li>' . "," . $this->getParentPath($pp_id);
        }
    }

    function get_shortcuts() {
        $query = $this->fpdo->from('cms_menu')->where("short_cut='1'")->fetchAll();
        foreach ($query as $row) {
            $is_static = $this->lookupField('cms_modules', 'id', 'is_static', $row['module_id']);
            $link = $this->lookupField('cms_modules', 'id', 'static_path', $row['module_id']);
            if ($is_static == 1) {
                $link = "../" . $link;
            } else {
                $link = "../cms_modules/listModules.php?cmsMID=" . $row['module_id'];
            }
            $return .= '<div class="col-md-3 col-sm-6 col-xs-12">
              <a href="' . $link . '"><div class="info-box">
                <span class="info-box-icon bg-red"><i class="fa ' . $row['icon'] . '"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">' . $row['title'] . '</span>                
                </div>
              </div></a>
            </div>';
        }

        return "$return";
    }

    function lang_switcher() {

        $lang_result = $this->fpdo->from('cms_langs')->where(" active='1' ")->fetchAll();
        if (count($lang_result) > 1) {
            $out = '<div class="lang_switcher" >
			<select style="width:100px;"name="cmsMlang" onchange="window.location=\'?cmsMlang=\'+this.value">';
            foreach ($lang_result as $lang) {
                $out.='<option value="' . $lang['lang'] . '"';
                if ($_SESSION['cmsMlang'] == $lang['lang']) {
                    $out.=' selected ';
                }
                $out.='>' . $lang['title'] . '</option>';
            }
            $out.='</select></div>';
        }
        return $out;
    }

}
