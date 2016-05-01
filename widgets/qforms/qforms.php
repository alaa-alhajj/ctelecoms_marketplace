<div class="qform-cls"> 
    
<?php
$item_count=0;
$sub = $_REQUEST['sub'];
$page_id=$_REQUEST['id'];

//------set labels------------
$_keycode_note='يرجى إدخال الرمز الموجود في الصورة ضمن المربع المخصص';
$_send="ارسل";
//----------------------------
                   

$Stmt1=" qforms_qforms.page_id  = '$id' AND  (qforms_fields.type <> 'File')";
$q1 = $this->fpdo->from('qforms_fields')->leftJoin('qforms_qforms ON qforms_fields.form_id = qforms_qforms.id')->where(" $Stmt1 ")->orderBy("item_order ASC")->fetchAll();
$otherNumrows = count($q1);

$Stmt2=" qforms_qforms.page_id  = '$id' AND  (qforms_fields.type = 'File')";
$q2 = $this->fpdo->from('qforms_fields')->leftJoin('qforms_qforms ON qforms_fields.form_id = qforms_qforms.id')->where(" $Stmt2 ")->orderBy("item_order ASC")->fetchAll();
$filesNumrows = count($q2);

$whereStmt1="  page_id  = '$id' and active='1'";
echo $query1 = $this->fpdo->from('qforms_qforms')->where(" $whereStmt1 ")->getQuery();
$query1 = $this->fpdo->from('qforms_qforms')->where(" $whereStmt1 ")->fetchAll();
$nums = count($query1);

if ($nums>0) {
	$i=0;
        
        foreach ($query1 as $row1) {
             //print_r($row1);
             $from_id= $row1['id']; 
             $form_name= $row1['form_name']; 
             $dir= $row1['dir']; 
             $send_mail_to= $row1['send_mail_to'];
             $map= $row1['map']; 
             $lat= $row1['lat']; 
             $lng= $row1['lng']; 
             $auto_response= $row1['auto_response']; 
             $details=$row1['details'];
        }
                                          
	$req_field=Required_field;
	$req_email=','.Xemail;
	$_keycode =write_code;
	$_Xkeycode=_Xkeycode;

	if($dir=='ltr'){
		$_ALI = 'left';
		$_SALI = 'right';
	}else{
		$_ALI = 'right';
		$_SALI = 'left';
	}
	
	
	
?>

	<?php if($map=='1'){?>
		<div class="col-sm-6">
			<input type='hidden' id='lat' value='<?php echo $lat?>'>
			<input type='hidden' id='lng' value='<?php echo $lng?>'>

			<div id='map-canvas' class="mapQform"></div>
		</div>
		 <div class="col-sm-12">
			<div class="page_content"><?=stripcslashes($details)?></div>
		</div>
	<?php }
	else{?>
		<div class="col-sm-12">
		<div class="page_content"><?=stripcslashes($details)?></div>
		</div>
	<?php }

	
	if($sub){
                             
                print_r($_REQUEST['message']);                                
		$result_msg='';
		$msg_sent=0;
		if( $_SESSION['security_code'] !=  $_REQUEST['code']){
                        $_Xkeycode='هناط خطأ في إدخال رمز الحماية، يرجى إعادة المحاولة لاحقاً ';
			$result_msg = $_Xkeycode;
		}else{
			 $other_no 	= $_REQUEST['other_no'];
			 $files_no 	= $_REQUEST['files_no'];
			 $admin_email     = $_REQUEST['send_mail_to'] ;
			 $form	= $_REQUEST['form_name'];
			 $user_email	= $_REQUEST['email_from'];
                                                
			$xml_code .= '<Row ss:AutoFitHeight="0" ss:Height="26.25">';
			for($i=0 ; $i < $other_no; $i++){
				$value	= $_REQUEST['f'.$i];	
				$xml_code .= '<Cell ss:StyleID="s63"><Data ss:Type="String">'.$value.'</Data></Cell>';
			}
                        
			$headers  = "MIME-Version: 1.0\r\n";
			$headers .= "Content-type: text/html; charset=utf-8\r\n";
			$headers .= "FROM:".$user_email."\r\n";
			$subject .= "$form";
			$message  ='
			<table width="600" border="0" cellspacing="0" cellpadding="3" dir="rtl" style="font-family:Tahoma, Geneva, sans-serif; float:right;">';
			
			for($i=0 ; $i < $other_no; $i++){
				$label 	= $_REQUEST['l'.$i];
				$value	= $_REQUEST['f'.$i];
				$message .='<tr><td width="200"><b>'.$label.':</b></td><td>'.$value.'</td></tr>';
				
			}
			if($files_no){
				$message .= '<tr><td colspan="2" width="200"><b>تم ارفاق الملفات التالية:</b></td></tr>';
				
				for($i=1 ; $i <= $files_no; $i++){
					$message .= '<tr><td colspan="2" width="200">'.$uploadedFiles.'</td></tr>';
				}
			}
			
			$message  .='</table>';
                        
			//echo $message;
			/* and now mail it */
			
			$s = @mail($admin_email, $subject, $message, $headers);
			if($s){
				$headers  = "MIME-Version: 1.0\r\n";
				$headers .= "Content-type: text/html; charset=utf-8\r\n";
				$headers .= "To:".$user_email."\r\n";
				$headers .= "From: ".$admin_email." \r\n";
				$subject .= $subject;
				@mail($user_email, $subject, $auto_msg, $headers);
				$result_msg = $ysend='تم إرسال رسالتك بنجاح';
				$msg_sent=1;
			}else{
				
				$result_msg = $xsend='عذراً! لم يتم إرسال الرسالة، يرجى إعادة المحاولة لاحقاً ';
				$msg_sent=0;	
			} 
		}
	}
	
	?>
	<div class='success-msg'><?=$result_msg?></div>
	
	<div class="col-sm-12">
	

		<div id="output1" style='width:100%'></div>
                <div class="form-details col-sm-12">
		<form name="subForm" action="<?=_PREF.$pLang.'/page'.$page_id.'/'.$from_id.'/'.$form_name?>" method='POST' enctype="multipart/form-data">
			

			<input type="hidden" name="form_name" value="<?=$form_name?>">
			<input type="hidden" name="id" value="<?=$id?>">
			<input type="hidden" name="send_mail_to" value="<?=$send_mail_to?>">
			<input type="hidden" name="other_no" value="<?=$otherNumrows?>">
			<input type="hidden" name="files_no" value="<?=$filesNumrows?>">

	

			<table   cellpadding="1" cellspacing="4" border="0"  width='70%'style='float:<?=_lang_algin?>;' dir='<?=DIR?>'>
                            <?php
				$i=0;
                                    $whereStmt2="  form_id  = '$id' and active='1'";
                                    $query2 = $this->fpdo->from('qforms_fields')->where(" $whereStmt2 ")->orderBy("item_order ASC")->fetchAll();
                                    $nums = count($query2);
                                    
                                    foreach ($query2 as $row2) {
                                         //print_r($row2);
                                        $label=$row2['field_label'];
					$type=ucfirst($row2['type']); // uppercast first latter
					$plus=$row2['plus'];
					$required=$row2['required'];
					$multi_rows=$row2['fi.multi_rows'];
					$field_desc=$row2['fi.field_desc'];
                                        
                                                 
					if(!$label) {$i++; continue;}
					$req= ($required)? 'required' : ''; 
					$req.= ($type=='Email')? ' email' : ''; 
					$rq= ($required)? '*' : '';
					if($type == 'Label'){ 
						$str.='<tr><td><b>'.$label.'</b></td><tr>';
						$i++;
						continue;
					}
						
					$str.= '<input type="hidden" name="l'.$item_count.'" value="'.$label.'" /></td>';
					if($multi_rows){$str.='<tr><td>';}else{$str.='<tr><Td>';}
					if($multi_rows){$str.='</tr><tr><td>';}else{$str.='<td  >';}

                                        
					switch ($type):

						case "Field":
							$str.='<input type="text" name="f'.$item_count.'" placeholder="'.$label.'" size="20" class="forms_input" '.$req.' />';			
							$str.='</td></tr>';
							$item_count++;
						break;

						case "Date":
							$str.='<input type="date" name="f'.$item_count.'" size="20" class="forms_input" '.$req.' placeholder="'.$label.'" />';
							$str.='</td></tr>';
							$item_count++;
						break;

						case "Email":
							$name='f'.$item_count;
							if($type=='Email'){$str.='<input type="hidden" name="email" value="'.$name.'"  />';}
							$str.='<input type="email" name="'.$name.'" placeholder="'.$label.'" size="20" class="forms_input email_from_val" '.$req.' />';	
							$str.='</td></tr>';
							$item_count++;
						break;

						case "File":
							$file_count++;
							if($plus!=''){
								$str.='اللواحق المسموح بها: '.$plus.'<br/>';
							}
							$str.='<input type="file" name="file'.$file_count.'" size="20" class="forms_input " '.$req.' />';
							$str.='</td></tr>';
							$str.='<input type="hidden" name="file_ex'.$file_count.'" value="'.$plus.'" />';
						break;

						

						case "Textarea":
							$str.='<textarea name="f'.$item_count.'" placeholder="'.$label.'" cols="40" rows="4" class="forms_input2" '.$req.'></textarea>';
							$str.='</td></tr>';
							$item_count++;
						break;

						case "Select":
							$str.='<label> <select name="f'.$item_count.'" class="forms_input" '.$req.'>';
							$str.='<option value="">--------------</option>';
							$values = explode(',',$plus);
							foreach($values as $val)$str.='<option value="'.$val.'">'.$val.'</option>';
							$str.='</select></label>';
							$str.='</td></tr>';
							$item_count++;
						break;

						case "Checkbox":			
							$values = explode(',',$plus);
							foreach($values as $val)$str.='<input type="checkbox" name="f'.$item_count.'" value="*" class=""  '.$req.'> '.$val;
							$str.='</td></tr>';
							$item_count++;
						break;
						
						default:
							$str.='<input type="Text" name="f'.$item_count.'" size="20" class="forms_input" '.$req.'/>';
							$str.='</td></tr>';
							$item_count++;
						break;

					endswitch;
					$i++;

				}// end while

				echo $str;?>



				<tr>
					<td></td>
					<td>
						<div class='key_code'><?=$_keycode_note?></div>
						<div class='key_code_text'>
							<img  src="<?=_PREF?>widgets/security_img/securityimages.php" border="0" align="<?=_left?>" />
							<input type="text" name="code" value=""  class="forms_input forms_input_code required  " onFocus="this.value=''" required />
							<input type="submit" name="sub" id='btn' style=""  value="<?=$_send?>">
							<input type='hidden' name="sub" value='send'/>
						</div>
					</td>
				</tr>		

			</table>
		</form>
                </div>    
	</div><?php
}//End if $numberOfRows
?>
</div>
<script>
	$('.document').ready(function(){
		$('.submit-form').click(function(){
			var email_from = $('.email_from_val').val();
			$('.email_from').val(email_from);	
		});
	});
</script>
