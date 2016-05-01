<?
echo $sub = $_REQUEST['sub'];
$id = $_REQUEST['id'];
$sql = "SELECT * FROM qforms_qforms fo LEFT JOIN qforms_fields fi ON (fi.form_id = fo.id ) WHERE fo.id  = '$id' ORDER BY item_order ASC";
$result = MYSQL_QUERY($sql);
$numberOfRows = MYSQL_NUMROWS($result);

if ($numberOfRows > 0) {
    $i = 0;
    $from_id = MYSQL_RESULT($result, $i, "id");
    $form_name = MYSQL_RESULT($result, $i, "form_name");
    $details = MYSQL_RESULT($result, $i, "details");
    $dir = MYSQL_RESULT($result, $i, "dir");
    $send_mail_to = MYSQL_RESULT($result, $i, "send_mail_to");
    $map = MYSQL_RESULT($result, $i, "map");

    $req_field = Required_field;
    $req_email = ',' . Xemail;
    $_keycode = write_code;
    $_Xkeycode = _Xkeycode;
    if ($dir == 'ltr') {
        $_ALI = 'left';
        $_SALI = 'right';
    } else {
        $_ALI = 'right';
        $_SALI = 'left';
    }
    ?>
    </div>
    </div>
    <div class="block inner-page">
        <h1 class="title_e2"><?= $form_name ?></h1>
   
        <div class="container">


            <div class="page_content_forms"><?= stripcslashes($details) ?></div>
            <?
            if ($sub) {
                echo "<div class='col-sm-12'><div class='col-sm-12'>";
                if ($_SESSION['security_code'] != $_REQUEST['code']) {
                    echo "<div style='clear:both; width:100%;color:#ee0000'  >" . $_Xkeycode . "</div>";
                    $xcode = 1;
                } else {
                    $num = $_REQUEST['no'];
                    $form = $_REQUEST['form_name'];

                    $xml_code .= '<Row ss:AutoFitHeight="0" ss:Height="26.25">';
                    for ($i = 0; $i < $num; $i++) {
                        $value = $_REQUEST['f' . $i];
                        $xml_code .= '<Cell ss:StyleID="s63"><Data ss:Type="String">' . $value . '</Data></Cell>';
                    }
                    $xml_code .= '</Row>';


                    $headers = "MIME-Version: 1.0\r\n";
                    $headers .= "Content-type: text/html; charset=utf-8\r\n";
                    //$headers .= "To:".$send_mail_to."\r\n";
                    $headers .= "From: info@" . _SITE . " \r\n";
                    $subject = " $form_name";
                    $message = '
			<table  border="0" cellspacing="0" cellpadding="3" dir="' . DIR . '" 
			style="font-family:Tahoma, Geneva, sans-serif">
			<tr><td colspan="2" stl="stl">' . _SITE . ' (' . $form_name . ')</td></tr>';
                    $i = 0;
                    while ($i < $numberOfRows) {
                        $id = MYSQL_RESULT($result, $i, "fi.id");
                        $label = MYSQL_RESULT($result, $i, "field_label");
                        $type = MYSQL_RESULT($result, $i, "type");
                        $required = MYSQL_RESULT($result, $i, "required");
                        $value = addslashes(strip_tags($_REQUEST['f' . $id]));

                        if ($type == 'Email') {
                            if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                                ?><script>document.location = "/"</script><?
                                exit;
                            }
                        }
                        if ($required == 1) {
                            if ($value == '') {
                                ?><script>document.location = "/"</script><?
                                exit;
                            }
                        }
                        $message .='<tr><td><b>' . $label . ':</b></td><td>' . $value . '</td></tr>';
                        if ($type == 'Email') {
                            $yourEmail = $value;
                        }
                        $i++;
                    }
                    $message .='</table>';

                    $s = mysql_query("INSERT INTO query_forms_xml (form_id,xml_code) VALUES ($form_id,'$xml_code')");
                    //$message;
                    /* and now mail it */
                    //$send_mail_to='ahmsaj@gmail.com';
                    $s = mail($send_mail_to, $subject, $message, $headers);
                    if ($s) {
                        if ($form_id == 7) {
                            $mes = 'Thank you for your interest to become a dealer for Odyne brand .<br /> 
					Please be assured that your enquiry is very important to us and we will endeavor to contact you within the next 24 hours ..';
                        } else {
                            $mes = 'We received your message,please be assured that your enquiry is very important to us, <br>
					and we will endeavor to contact you within the next 24 hours ';
                        }

                        echo '<br><div><div style="font-size:25px;color:#d71a20">Thank You !</div>' . $mes . '</div>';
                        if ($yourEmail) {
                            $ss = mail($yourEmail, $subject, $mes, $headers);
                        }
                    } else {
                        echo "<div style='clear:both; width:100%;color:#c00'>ERROR: Sorry we could not receive your message, please try again</div>";
                        $xsend = 1;
                    }
                }
                $rand = rand();
                $_SESSION['security_code'] = $rand;
                echo "<br></div></div>";
            }
            if (!$sub || $xsend || $xcode) {
                ?>

                <div id="output1" style='width:100%'><?=$mes?></div>
                <form name="subForm" action="<?= _PREF . $pLang . '/Form' . $from_id . '/' . rewriteFilter($form_name) ?>" method='POST'>
                    <input type="hidden" name="id" value="<?= $from_id ?>">	
                    <?
                    $i = 0;

                    while ($i < $numberOfRows) {
                        $id = MYSQL_RESULT($result, $i, "fi.id");
                        $label = MYSQL_RESULT($result, $i, "field_label");
                        $type = MYSQL_RESULT($result, $i, "type");
                        $plus = MYSQL_RESULT($result, $i, "plus");
                        $required = MYSQL_RESULT($result, $i, "required");
                        $multi_rows = MYSQL_RESULT($result, $i, "fi.multi_rows");

                        $value = addslashes(strip_tags($_REQUEST['f' . $id]));

                        if (!$label) {
                            $i++;
                            continue;
                        }
                        $req = ($required) ? ' required ' : '';
                        $rq = ($required) ? '<font size="4" color="#cc0000">*</font>' : '';
                        $req.= ($type == 'Email') ? ' email' : '';
                        if ($type == 'Label') {
                            $str.='<div class="col-sm-4"><b>' . $label . '</b></div>';
                            $i++;
                            continue;
                        }
                        $multi_rows = 1;
                        if ($multi_rows) {
                            //$str.='<tr><td valign="top" class="forms_titles font1">';
                        } else {
                            //$str.='<tr><td  style="width:150px; max-width:150px;" valign="top" class="forms_titles font1">';
                        }
                        //$str.= $label.':'.$rq.'</td>';
                        if ($multi_rows) {//$str.='</tr><tr><td  colspan="3">';}else{$str.='<td  >';}
                        }

                        switch ($type):
                            case "Email":

                                $str.='<div class="col-sm-3"><div class="form-group form-group-sm">
            <label class="col-sm-12 control-label" for="formGroupInputSmall">' . $label . $rq . ':</label>
            <div class="col-sm-12">      
                <input type="email" name="f' . $id . '"  ' . $req . ' class="form-control" value="' . $value . '"/>
            </div>
        </div></div>';
                                break;
                            case "Field":

                                $str.='<div class="col-sm-3"><div class="form-group form-group-sm">
            <label class="col-sm-12 control-label" for="formGroupInputSmall">' . $label . $rq . ':</label>
            <div class="col-sm-12">      
                <input type="text" name="f' . $id . '"  ' . $req . ' class="form-control" value="' . $value . '"/>
            </div>
        </div></div>';
                                break;

                            case "Area":
                                $str.='<div class="col-sm-12"><label class="col-sm-12 control-label" for="formGroupInputSmall" >' . $label . $rq . ':</label><div class="form-group form-group-sm"><div class="col-sm-12"><textarea name="f' . $id . '" class="form-control  ' . $req . '" '.$req.'>' . $value . '</textarea><p id="f' . $id . '"></p></div></div></div>';
                                break;

                            case "List":
                                $str.='<select name="f' . $id . '" class="forms_input ' . $req . '">';
                                $values = split(',', $plus);
                                foreach ($values as $val) {
                                    $sel = '';
                                    if ($value == $val) {
                                        $sel = 'selected';
                                    }
                                    $str.='<option value="' . $val . '" ' . $sel . ' >' . $val . '</option>';
                                }
                                $str.='</select><span class="req_msg">' . $req_field . '</span></td></tr>';
                                break;

                            case "Checkbox":
                                $values = split(',', $plus);
                                foreach ($values as $val)
                                    $str.='<input type="checkbox" name="f' . $id . '" value="*" class="' . $req . '"> ' . $val;
                                $str.='</td></tr>';
                                break;

                            default:
                                $str.='<div class="col-sm-3"><div class="form-group form-group-sm">
            <label class="col-sm-12 control-label" for="formGroupInputSmall">' . $label . $rq . ':</label>
            <div class="col-sm-12">      
                <input type="text" name="f' . $id . '"  ' . $req . ' class="form-control" value="' . $value . '"/>
            </div>
        </div></div>';
                                break;

                        endswitch;
                        $i++;
                    }// end while
                    echo $str;
                    ?>
                    <div class='col-sm-12'>
                        <div class="form-group">
                            <p class="help-block col-sm-12"><?= _keycode_note ?></p>

                            <div class="col-sm-12">      
                                <img  src="<?= _PREF ?>widgets/security_img/securityimages.php" border="0" />
                                <br><input type="text" required="" name="code" value=""  class="forms_input forms_input_code required  " onFocus="this.value = ''" />

                            </div>


                        </div>
                        <div class="form-group">
                            <div class='col-sm-12'>
                                <button type="submit" id='btn' class="btn btn-default btn-qform" name="sub" onclick='validate_form();' value=""><?= _Send ?></button>
                            </div> 
                        </div>
                    </div>

                    
                </form></div><?
            }
        } //number Of Row?>