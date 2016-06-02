<?php

include('PHPmailer/class.phpmailer.php');
include('PHPmailer/class.smtp.php');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UTILS
 *
 * @author Ahmad Mahmoud
 */
class utils {

    protected $fpdo;
    var $icon;
    var $field;
    var $val;

    function __construct() {

        global $fpdo;
        $this->fpdo = & $fpdo;
    }

    function sendMailC($from, $to, $subject, $body, $idTemplate = "", $tags = "",$attachment) {
        
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=utf-8\r\n";

        $headers .= "From: " . $from . " \r\n";
        if ($idTemplate != "") {
            $query = $this->fpdo->from('mails')->where('id', $idTemplate)->fetch();
            if ($query['id'] != "") {
                $desc = $query['description'];
                if ($subject == "") {
                    $title = $query['title'];
                    if ($title != "") {
                        $subject = $title;
                    }
                }
                
                $desc = str_replace("../../../uploads", "http://" . _SITE . _PREF . "ui", $desc);
                $desc = str_replace("../../", "http://" . _SITE . _PREF, $desc);
                foreach ($tags as $key => $val) {
                    $desc = str_replace("$key", $val, $desc);
                }
                if ($idTemplate != '0' && $idTemplate != 0) {
                    
                }
                $body = $desc . $body;
                if ($idTemplate != '0' && $idTemplate != 0) {
                    $query = $this->fpdo->from('mails')->where("id='0'")->fetch();

                    if ($query['id'] != "") {
                        $desc = $query['description'];
                        $desc = str_replace("../../../uploads", "http://" . _SITE . _PREF . "ui", $desc);
                        $desc = str_replace("../../", "http://" . _SITE . _PREF, $desc);
                        $desc = str_replace("{body}", $body, $desc);
                        $body = $desc;
                    }
                }
            }
        }
   $query2 = $this->fpdo->from('mails')->where('id', $idTemplate)->fetch();
   if($query2['recipient_email']!=""){
       $to=$query2['recipient_email'];
   }
        $sname = "=?UTF-8?B?" . base64_encode($from) . "?=\n"; //  
        $rname = "=?UTF-8?B?" . base64_encode("") . "?=\n"; //  
        $sub = "=?UTF-8?B?" . base64_encode($subject) . "?=\n"; //  
        $smail = $from;
        $rmail = $from;

        $mail = new PHPMailer();
        $mail->IsSMTP(); // نختار الارسال عن طريق SMTP
        $mail->Host = mail_host; // اسم سيرفر SMTP - ممكن ان يكون mail.yourdomain.com / smtp.yourdomain.com
        $mail->SMTPSecure = mail_auth; // secure transfer enabled REQUIRED for GMail
        //$mail->Host = "ssl://smtp.gmail.com:465";
        $mail->Port = mail_port;
        $mail->SMTPAuth = true;
        $mail->Username = $query2['username']; // البريد الخاص بموقعك يجب ان ينتهي باسم موقعك
        $mail->Password = $query2['password']; // كلمة مرور هذا البريد

        $mail->AddReplyTo($smail); // نختار وجهة ارسال الرد في حال ارسل واسم مستقبل الرد
        $mail->AddAddress($to); // بريد المستقبل واسمه
           foreach ($attachment as $attach) {
            $mail->addAttachment($attach);         // Add attachments
            // echo $attach;
        }
        //$mail->AddAddress("eng.lara@gmail.com");
        $mail->From = $smail; // بريد المرسل
        $mail->FromName = $sname; // اسم المرسل

        $mail->Subject = $sub; // موضوع الرسالة


        $mail->MsgHTML($body); // نص الرسالة - يمكن ان يكون كود html

        $mail->IsHTML(true); // send as HTML

        if ($mail->Send()) {
           // return 1;
        } else {
          //  return 0;
        }
    }

    public function rewriteFilter($val) {
        $chrs = array('/', '&', '$', '_', ' ');

        for ($i = 0; $i < count($chrs); $i++) {

            $val = str_replace($chrs[$i], '-', $val);
        }

        $chrs2 = array(':', '?', '^', '%', '(', ')', '"', "'");

        for ($i = 0; $i < count($chrs2); $i++) {

            $val = str_replace($chrs2[$i], '', $val);
        }

        return $val;
    }

    public function back($link) {
        if (is_string($link)) {
            return "<a href='$link' class='btn btn-back'>" . $this->getConstant("Back") . "</a>&nbsp;";
        } else {
            return "<button type='button' class='btn btn-back' onClick='history.back();'>" . $this->getConstant("Back") . "</button>&nbsp;";
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

    public function redirect($url) {
        echo "<script>window.location.replace('$url'); a</script>";
    }

    public function getValuesImplode($table, $field_id, $field_name, $values) {

        $query = $this->fpdo->from($table)->where("`$field_id` in ($values)")->fetchAll();
        $return = array();
        foreach ($query as $row) {
            array_push($return, $row[$field_name]);
        }
        return implode(",", $return);
    }

    public function create_pagination($tp, $pn, $LPP, $link, $lang = 'ar', $class = "") {

        $first = "";
        $next = "";
        $last = "";
        $prev = "";

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

        $pages = ceil((int) $tp / (int) $LPP) + 1;

        if (!$pn)
            $pn = 0;

        $start = max(0, ($pn - 2));

        $end = min($pages - 1, ($start + 5));

        $start = max(0, ($end - 5));



        $res = '<div class="' . $class . '"><ul class="pagination">';
        if ($pn > 0) {

            $rewrite_link = str_replace('^', 0, $link);

            $res .= "<li><a href='$rewrite_link'><span class='glyphicon " . $first . "'></span></a></li>";

            $rewrite_link = str_replace('^', max(0, ($pn - 1)), $link);

            $res .= "<li><a href='$rewrite_link'><span class='glyphicon " . $next . "'></span></a></li>";
        } else {

            $res .= "<li class='disabled'><a><span class='glyphicon " . $first . "'></span></a></li>";

            $res .= "<li class='disabled'><a><span class='glyphicon " . $next . "'></span></a></li>";
        }
        $total_pages = ceil((int) $tp / (int) $LPP);

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

        $res .= "</ul></div>";

        if ($tp > 0) {

            if (((int) $tp / (int) $LPP) > 1) {

                return $res;
            } else {

                return '';
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
        $text = strip_tags($text);
        if (strlen($text) > $limit) {
            $to = strpos($text, " ", $limit);
            if ($to !== false) {
                $text = substr($text, 0, $to);
            }
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

    function getOrgFiles($files, $withFolder = 1) {
        $str = '';
        if ($files != '' && $files != ',') {
            $result = $this->fpdo->from('files')->where("id in ($files)")->orderBy("FIELD(id,$files)")->execute();

            $i = 0;
            foreach ($result as $row) {
                if ($i != 0) {
                    $str.=',';
                }
                $file = $row['file'];
                $folder = $row['folder'];
                if ($withFolder == 1) {
                    $str.=$folder;
                }
                $str.=$file;
                $i++;
            }
        }
        return $str;
    }

    function selectFile($filedName, $files = '', $multiple = 1, $onlyImages = 1) {
        $str = '<link type="text/css" rel="stylesheet" href="../../includes/file_selector/css/style.css"/>	
	<script src="../../includes/file_selector/js/script.js"></script>	
	
	<script> $(document).ready(function(){loadSelFiles(\'' . $filedName . '\',\'' . $files . '\',' . $multiple . ',' . $onlyImages . ');})</script>
	<div id="sf_' . $filedName . '"></div>';
        return $str;
    }

    function resizeToFile($img, $w, $h, $newfilename, $rewrite = '') {

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

    function switcher($table, $id, $col, $value, $switch, $f_id = "id") {
        if ($value == 1 || $value == '1') {

            $icon = 'fa fa-check';
            $class = 'true';
        } elseif ($value == 0 || $value == '0') {

            $icon = 'fa fa-close';
            $class = 'false';
        }
        return '
       <a class="' . $class . '" data-switcher="' . $switch . '" data-fid="' . $f_id . '" data-id="' . $id . '" data-table="' . $table . '" data-col="' . $col . '" data-val="' . $value . '">
       <i class="' . $icon . '"></i>
       </a>';
    }

    function Crop($img, $w, $h, $newfilename, $rewrite = '') {
        if (file_exists($newfilename)) {
            if ($rewrite !== '') {
                return $rewrite;
            } else {
                return $newfilename;
            }
        } else {
            $strY = 0;
            $strX = 0;
//Check if GD extension is loaded
            if (!extension_loaded('gd') && !extension_loaded('gd2')) {
                trigger_error("GD is not loaded", E_USER_WARNING);
                return false;
            }
//Get Image size info
            $imgInfo = @getimagesize($img);
            $nHeight = $imgInfo[1];
            $nWidth = $imgInfo[0];
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
                if ($rewrite !== '') {
                    if (copy($img, $newfilename)) {
                        return$rewrite;
                    }
                } else {
                    return $img;
                }
            } else {
//yeah, resize it, but keep it proportional
                if ($nWidth / $w < $nHeight / $h) {
                    $ww = $w;
                    $hh = ($nHeight * $w) / $nWidth;
                    $strY = ($hh - $h) / 2;
                    $side2 = ($nWidth * $h) / $w;
                    $side = $nWidth;
                } else {
                    $hh = $h;
                    $ww = ($nWidth * $h) / $nHeight;
                    $strX = ($ww - $w) / 2;
                    $side = ($nHeight * $w) / $h;
                    $side2 = $nHeight;
                }
            }
            $newImg = imagecreatetruecolor($w, $h);
            /* Check if this image is PNG or GIF, then set if Transparent */
            if (($imgInfo[2] == 1) OR ( $imgInfo[2] == 3)) {
                imagealphablending($newImg, false);
                imagesavealpha($newImg, true);
                $transparent = imagecolorallocatealpha($newImg, 255, 255, 255, 127);
                imagefilledrectangle($newImg, 0, 0, $w, $h, $transparent);
            }
            imagecopyresampled($newImg, $im, 0, 0, $strX, $strY, $w, $h, $side, $side2);
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
            if ($rewrite !== '') {
                return $rewrite;
            } else {
                return $newfilename;
            }
        }
    }

    function forceResize($img, $rate, $newfilename, $rewrite = '') {
//	if(file_exists($newfilename)){	
//		if($rewrite!==''){
//			return $rewrite;
//		}else{
//			return $newfilename;
//		}
//	}	
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

        $nWidth = $imgInfo[0] * $rate;
        $nHeight = $imgInfo[1] * $rate;

        $nWidth = ceil($nWidth) + 2;
        $nHeight = ceil($nHeight) + 2;

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

        if ($rewrite !== '') {
            return $rewrite;
        } else {
            return $newfilename;
        }
    }

    function forceCrop($img, $w, $h, $newfilename, $rewrite = '') {

        $imgInfo = @getimagesize($img);
        $nHeight = $imgInfo[1];
        $nWidth = $imgInfo[0];
        if ($imgInfo[0] <= $w && $imgInfo[1] <= $h) {
            $ratew = $nWidth / $w;
            $rateh = $nHeight / $h;
            if ($ratew < $rateh) {
                $rate = $ratew;
            } else {
                $rate = $rateh;
            }
            $nW = $w * $rate;
            $nH = $h * $rate;
            $res = $this->Crop($img, $nW, $nH, $newfilename, $rewrite);
        } else {
            $res = $this->Crop($img, $w, $h, $newfilename, $rewrite);
        }

        return $res;
    }

    function viewThumbnail($photos, $resizeType, $width, $height, $outType, $firstPhotoOnly = 1, $path = '../../', $isThumb = "", $withTitle = 1) {

        $photos = $this->Files($photos, $firstPhotoOnly);
        $photo = explode(',', $photos);
        $photo = $photo[0];

        if ($isThumb != '') {
            $lang = $_SESSION['pLang'];
            $query = $this->fpdo->from("files")->where("id", $photo)->fetch();

            if (count($query) > 0) {

                $file = $query['file'];
                $title = $query['name_' . $lang];
                $folder = str_replace("uploads/", "", $query['folder']);
            }

            $photo_rewrite = _PREF . "ui/" . $folder . $file;

            $newfile = "m" . str_replace('.', '', $isThumb['scale']) . str_replace('.', '', $isThumb['x']) . str_replace('.', '', $isThumb['y']) . $module . $module_id . $file;

            $imgResize = $this->cropManual($folder . $file, $newfile, $isThumb['scale'], $isThumb['x'], $isThumb['y'], $isThumb['w'], $isThumb['h'], "", $path);
            if ($imgResize != '') {
                $imgResize = str_replace('../../../', _PREF, $imgResize);
                $imgResize = str_replace('../../', _PREF, $imgResize);
                $out = $imgResize;
            } else {
                $out = $this->viewPhoto($photos, $resizeType, $width, $height, "css", $firstPhotoOnly, $path, 0, $withTitle, "");
            }
            return $out;
        } else {
            return $this->viewPhoto($photos, $resizeType, $width, $height, "css", $firstPhotoOnly, $path, 0, $withTitle, $class);
        }
    }

    function cropManual($img, $NewImg, $scale, $offsetX, $offsetY, $nWidth, $nHeight, $type_img, $dots = "../../") {
        $cash = $dots . "uploads/cash/";
        $NewImg = $cash . $NewImg;
        list($w, $h) = getimagesize($img);
        $imgInfo = @getimagesize($img);

        switch ($imgInfo[2]) {
            case 1: $ext = 'gif';
                break;
            case 2: $ext = 'jpg';
                break;
            case 3: $ext = 'png';
                break;
            default: trigger_error('Unsupported filetype!', E_USER_WARNING);
                break;
        }

        $Swidth = ceil($w * $scale);
        $sHeight = ceil($h * $scale);
        $offsetX = $offsetX * $scale;
        $offsetY = $offsetY * $scale;
        if ($Swidth < $nWidth) {
            $val = $nWidth - $Swidth;
            $Swidth = $Swidth + ($val);
            $sHeight = $sHeight + ($val);
        }
        if ($sHeight < $nHeight) {
            $val = $nHeight - $sHeight;
            $sHeight = $sHeight + ($val);
            $Swidth = $Swidth + ($val);
        }
        $Swidth = $Swidth + 1;
        $sHeight = $sHeight + 1;
        $offsetX;

        $ScaleFileName = $cash . "scale" . str_replace('.', '', $scale) . $nWidth . $nHeight . "." . $ext;
        $resizefile = $this->forceResize($img, $scale, $ScaleFileName, '');

        switch ($imgInfo[2]) {
            case 1: $im = imagecreatefromgif($resizefile);
                break;
            case 2: $im = imagecreatefromjpeg($resizefile);
                break;
            case 3: $im = imagecreatefrompng($resizefile);
                break;
            default: trigger_error('Unsupported filetype!', E_USER_WARNING);
                break;
        }

        $tci = imagecreatetruecolor($nWidth, $nHeight);
        imagecopyresampled($tci, $im, 0, 0, $offsetX, $offsetY, $nWidth, $nHeight, $nWidth, $nHeight);
        imagejpeg($tci, $NewImg, 100);
        switch ($imgInfo[2]) {
            case 1: imagegif($tci, $NewImg, 100);
                break;
            case 2: imagejpeg($tci, $NewImg, 100);
                break;
            case 3: imagepng($tci, $NewImg, 100);
                break;
            default: trigger_error('Unsupported filetype!', E_USER_WARNING);
                break;
        }

//  unlink($resizefile);
        return $NewImg;
    }

    function viewPhoto($photos, $resizeType, $width, $height, $outType, $firstPhotoOnly = 1, $path = '../../', $withLink = 1, $withTitle = 0, $class = '', $settings = "") {
        global $pLang;

        if ($settings['withLink'] != "") {
            $withLink = $settings['withLink'];
        }
        if ($settings['class'] != "") {
            $class = $settings['class'];
        }
        if ($settings['w'] != "") {
            $width = $settings['w'];
        }
        if ($settings['h'] != "") {
            $height = $settings['h'];
        }
        $photo = $path . 'view/includes/css/images/no_photo.png';
        $file = 'no_photo.png';
        $reStr = 'C';
        $imge = $this->forceCrop($photo, $width, $height, $path . 'uploads/cash/' . $reStr . $width . $height . $file, _PREF . 'ui/cash/' . $reStr . $width . $height . $file);
        if ($outType == 'css') {
            $out = 'background-image:url(' . $imge . ');';
        } else {
            $out = "<img src='$imge' class='$class' alt='NoIMG' />";
        }
        if ($firstPhotoOnly == 1) {
            $allOut = '';
        } else {
            $allOut = array();
        }
        if ($photos != '') {
            $arr1 = explode(',', $photos);
            $order_array = 'ORDER BY';
            foreach ($arr1 as $item) {
                $order_array .= ' id = ' . $item . ' DESC,';
            }
            $order_array = trim($order_array, ',');
            $limit = '0,1000';
            if ($firstPhotoOnly) {
                $limit = "0,1";
            }

            $isgallery = 'data-group="mygroup"';

            $query = $this->fpdo->from('files')->where("id IN($photos)")->limit($limit)->fetchAll();

            $item_html = $template_item_html;
            foreach ($query as $row) {
                $file = $row['file'];
                $ext = $row['ext'];
                $title = $row['name_' . $pLang];
                $alt = $row['name_' . $pLang];
                $folder = $row['folder'];
                $folder = str_replace('uploads/', '', $folder);
                if (strpos($file, 'http') !== false) {
                    $photo = $photo_rewrite = $file;
                } else {
                    $photo = $path . 'uploads/' . $folder . $file;
                    $photo_rewrite = _PREF . 'ui/' . $folder . $file;
                }
                if (file_exists($photo)) {
                    if (exif_imagetype($photo)) {
                        if ($resizeType == 'resize') {
                            $reStr = 'R';
                            $imgResize = $this->resizeToFile($photo, $width, $height, $path . 'uploads/cash/' . $reStr . $width . $height . $file, _PREF . 'ui/cash/' . $reStr . $width . $height . $file);
                        } elseif ($resizeType == 'crop') {
                            $reStr = 'C';
                            $imgResize = $this->forceCrop($photo, $width, $height, $path . 'uploads/cash/' . $reStr . $width . $height . $file, _PREF . 'ui/cash/' . $reStr . $width . $height . $file);
                        } elseif ($resizeType == 'full') {
                            $imgResize = $photo;
                        } elseif ($settings['type'] == 'manualCrop' && $settings != "") {

                            $reStr = 'M';

                            $newfile = $reStr . str_replace('.', '', $settings['scale']) . str_replace('.', '', $settings['x']) . str_replace('.', '', $settings['y']) . $file;
                            $imgResize = $this->cropManual($photo, $newfile, $settings['scale'], $settings['x'], $settings['y'], $settings['w'], $settings['h'], "", $path);
                        }
                    } else {
                        
                    }
                }
                if ($withTitle == 1) {
                    $divtitle = '<div class="title_gal">' . $title . '</div>';
                }
                if ($outType == 'css') {
                    $out = 'background-image:url(' . $imgResize . ');';
                }
                if ($outType == 'img') {

                    if ($withLink)
                        $out = '<a href="' . $photo_rewrite . '" class="html5lightbox" ' . $isgallery . ' title="' . $title . '"><img src="' . $imgResize . '" class="' . $class . '"  alt="' . $alt . '" "/>' . $divtitle . $video . '</a>';
                    else
                        $out = '<img src="' . $imgResize . '" class="' . $class . '" alt="' . $alt . '"  />' . $divtitle;
                }
                if ($firstPhotoOnly == 1) {
                    $allOut = $out;
                } else {
                    $allOut[] = $out;
                }
            }
        } else {
            if ($firstPhotoOnly == 1) {
                $allOut = $out;
            } else {
                $allOut[] = $out;
            }
        }
        return $allOut;
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

    function createPagination_load_more($widget_id) {

        $result = "<div class='load-more-area'><button type='button' class='load_more_items btn btn-primary btn-lg ' id='" . $widget_id . "' data-loading-text=\"<i class='fa fa-circle-o-notch fa-spin'></i> " . processing_order . "\">" . load_more . "</button></div>";
//$result = "<div class='load-more-area'><input type='button' value='".load_more."' id='$widget_id' class='load_more_items' /></div>";
        return $result;
    }

    function checkLogin() {
        if (!isset($_SESSION['CUSTOMER_ID']) || (isset($_SESSION['CUSTOMER_ID']) && $_SESSION['CUSTOMER_ID'] == '' )) {
            $this->redirect(_PREF);
        } else {
            return $_SESSION['CUSTOMER_ID'];
        }
    }

    function getCategoriesMenu() {
        $categories = $this->fpdo->from("product_category")->fetchAll();
        $cat_manu = '<nav>'
                . '<ul class="nav cat-menu">'
                . '<li class="title-nav"><a href="javascript:;">CATEGORIES</a></li>';
        foreach ($categories as $cat) {
            $sub_categories = $this->fpdo->from("product_sub_category")->where("cat_id='" . $cat['id'] . "'")->fetchAll();
            if (count($sub_categories) > 0) {
                foreach ($sub_categories as $subcat) {
                    $cat_manu.='<li><a href="#" id="sub-' . $subcat['id'] . '" data-toggle="collapse" data-target="#submenu' . $subcat['id'] . '" aria-expanded="true">' . $cat['title'] . ' <i class="fa fa-caret-down" aria-hidden="true"></i></a>
			<ul class="nav collapse" id="submenu' . $subcat['id'] . '" role="menu" aria-labelledby="sub-' . $subcat['id'] . '">
				<li><a href="#">' . $subcat['title'] . '</a></li>
				
			</ul>
		</li>';
                }
            } else {
                $cat_manu.='<li><a href="#" >' . $cat['title'] . '</a></li>';
            }
        }
        $cat_manu.="</ul></nav>";
        return $cat_manu;
    }

    function getProductsBoxes($where, $limit = '', $col = '') {
        if ($col != "") {
            $class_col = 'col-sm-6';
        } else {
            $class_col = 'col-sm-4';
        }
        $featured_products = $this->fpdo->from("products")->where($where)->orderBy("id desc")->limit($limit)->fetchAll();
        $featured_block = "<div class='row'>";
        $fade_block = 300;
        foreach ($featured_products as $products) {
            $product_id = $products['id'];
            $get_dynamic_price = $this->fpdo->from('product_dynamic_price')->where("product_id='$product_id'")->fetch();
            $dynamic_price_id = $get_dynamic_price['id'];
            $get_groups = explode(',', rtrim($get_dynamic_price['group_ids'], ','));
            if ($get_dynamic_price['type_id'] === '2') {
                $groups_select = "<select style='width:65%;float:left;margin-bottom: 10px;' name='groups' id='groups' class='form-control ProductGroups groups_cart_$product_id ProductGroups_$product_id' data-type='group' data-dynamic='" . $dynamic_price_id . "' data-product='" . $product_id . "'>";
                foreach ($get_groups as $groups) {
                    $get_title_g = $this->fpdo->from('pro_price_groups')->where("id='$groups'")->fetch();
                    $get_unit_name = $this->fpdo->from('pro_price_units')->where("id", $get_dynamic_price['unit_id'])->fetch();
                    $groups_select.="<option value='" . $groups . "'>" . $get_title_g['title'] . "</option>";
                }
                $groups_select.="</select> <span style='float:right;margin-top:6px'>  " . $get_unit_name['title'] . "</span>";
            } elseif ($get_dynamic_price['type_id'] === '1') {
                foreach ($get_groups as $groups) {
                    $get_groupName = $this->fpdo->from('pro_price_groups')->where("id='$groups'")->fetch();
                    $get_unit_name = $this->fpdo->from('pro_price_units')->where("id", $get_dynamic_price['unit_id'])->fetch();
                    $get_max_min = $this->fpdo->from('pro_price_groups')
                                    ->select("max(title) as maxval,min(title) as minval")->where('id in (' . rtrim($get_dynamic_price['group_ids'], ',') . ')')->fetch();
                    $groups_select = "<input type='number' max='" . $get_max_min['maxval'] . "' min='0' value='" . $get_groupName['title'] . "' name='groups_cart' class='form-control groups_cart groups_cart_" . $product_id . "' data-duration='$duration_id' data-dynamic='$dynamic_price_id' data-product='$product_id' data-type='unit' style='width:65%;float:left;margin-bottom: 10px;'> <span style='float:right;margin-top:6px'>" . $get_unit_name['title'] . "</span>";
                }
            }




            $get_durations = explode(',', rtrim($get_dynamic_price['duration_ids'], ','));
            $durations_select = "<select name='durations' id='durations' class='form-control ProductDurations ProductDurations_$product_id' data-dynamic='" . $dynamic_price_id . "' data-product='" . $product_id . "'>";

            foreach ($get_durations as $duration) {
                $get_title_du = $this->fpdo->from('pro_price_duration')->where("id='$duration'")->fetch();
                $durations_select.="<option value='" . $duration . "'>" . $get_title_du['title'] . "</option>";
            }
            $durations_select.="</select>";
            $pro_photo = $this->viewPhoto($products['photos'], 'crop', 104, 156, 'img', 1, $_SESSION['dots'], 1, '', 'img-responsive');

            $check_session_ShoppinCart = $_SESSION['Shopping_Cart'];

            if ($check_session_ShoppinCart[$product_id] != "") {
                $button_class = "RemovefromCart";
                $cart_icon = '  <i class="demo-icon iconc-cart icon-style shopCartIcon"></i>';
            } else {
                $button_class = "addToCartSmall";
                $cart_icon = '<i class="demo-icon iconc-cart-plus icon-style shopCartIcon"></i>';
            }
            $check_session_compare = $_SESSION['compareIDs'];

            if ($check_session_compare[$product_id] != "") {
                $class_compare = 'removeFromCompare';
                $compare_icon = '<i class="demo-icon iconc-compare icon-style "></i>';
            } else {
                $class_compare = "addToCompare";
                $compare_icon = '<i class="demo-icon iconc-compare-plus icon-style "></i>';
            }


            $link = _PREF . $_SESSION['pLang'] . "/page" . $products['page_id'] . "/" . $this->rewriteFilter($products['title']);
            $featured_block.=' <div class="col-sm-4">
                        <div class="product-block wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="' . $fade_block . 'ms">
                            <div class="title"><a href="' . $link . '">' . $products['title'] . '</a></div>
                            <div class="row row-nomargin product-details">
                                <div class="col-xs-6">
                                    <a href=""> ' . $pro_photo . '</a>
                                </div>
                                <div class="col-xs-6 select-price">
                                    <div class="col-xs-12 nopadding">
                                       ' . $durations_select . '
                                    </div>
                                    <div class="col-xs-12 nopadding">
                                      ' . $groups_select . '
                                    </div>
                                    <span class="product_price_' . $product_id . '  relative price">
                                         
</span> <div class="load-price-img" style="display:none; margin-top: 16px;" data-id="' . $product_id . '">
                    <img src="' . _ViewIMG . 'loading_small.gif" alt="" class="img-responsive" />
                </div>    
                                </div>
                                <div class="col-xs-12 nopadding">
                                    <div class="product-brief">
                                      ' . $this->limit(strip_tags($products['brief']), 66) . '
                                    
                                    </div>
                                </div>
                             <div class="product-buttons">
                                <div class="row row-nomargin">
                                    <div class="col-xs-4 nopadding block-btn">
                                        <a href="' . $link . '">
                                       <div class="icon">                                            
                                  <i class="demo-icon iconc-more1 icon-style"></i>
                                   </div>
                                     </a>
                                    </div>
                                    <div class="col-xs-4 nopadding block-btn">
                                     <a href="javascript:;" class="' . $class_compare . '"  data-id="' . $product_id . '">
                                               <div class="load-img" style="display:none; margin-top: 16px;" data-id="' . $product_id . '">
                    <img src="' . _ViewIMG . 'loading_small.gif" alt="" class="img-responsive" />
                </div>
                                    <div class="icon">
                                       
                                  ' . $compare_icon . ' 
                                      
                                    
                                    </div></a>
                                    </div>
                                    <div class="col-xs-4 nopadding block-btn relative" style="overflow: hidden">
                                    
                                        <a href="javascript:;" class="' . $button_class . '"  data-id="' . $product_id . '">
                                             <div class="load-img" style="display:none; margin-top: 16px;" data-id="' . $product_id . '">
                    <img src="' . _ViewIMG . 'loading_small.gif" alt="" class="img-responsive" />
                </div>
                                             <div class="icon">
                                            ' . $cart_icon . '
                                                </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div> </div>
                    </div>';

            $fade_block = $fade_block + 300;

// unset($_SESSION['Shopping_Cart']);
        }
        $featured_block.="</div>";
        return $featured_block;
    }

}
