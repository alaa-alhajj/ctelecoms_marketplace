<?php
$db_table="mailing_list";
$db_mailing_groups="mailing_groups";
$db_mailing_msg="mailing_msg";
$db_mailing_archive="mailing_archive";
$db_mailing_delivary_rep="mailing_delivary_rep";

$LPP = 8;

// mailing_list cols
$cols=array('full_name','email','address','date','grp_id');
$types=array('full_name'=>"text",'email'=>"email",'address'=>'text','date'=>'date','grp_id'=>'select');

$required=array("full_name"=>"required","email"=>"required","date"=>"required","address"=>"required","grp_id"=>"required");
$source=array('grp_id'=>array("0"=>"mailing_groups","1"=>"title_en","2"=>"id"));
$extend=array('grp_id'=>array('mailing_groups','title_en','id'));
// group cols 
$gcols=array('title_en');
$gSavecols=array('title_en');
$gtypes=array('title_en'=>"text");
$grequired=array("title_en"=>"required");
/*
$gcols=array('title_en','title_ar');
$gSavecols=array('title_en','title_ar');
$gtypes=array('title_en'=>"text",'title_ar'=>"text");
$grequired=array("title_en"=>"required","title_ar"=>"required");
*/
// messages cols
$mcols=array('subject','body','grp_ids');
$mtypes=array('subject'=>"text",'create_date'=>"date",'body'=>'FullTextEditor','grp_ids'=>'checkbox');
$m_source=array('grp_ids'=>array('mailing_groups','id','title_en',' active = 1 '));
$m_extend=array('grp_ids'=>array('mailing_groups','title_en','id',' active = 1 '));
$mSavecols=array('subject','body','create_date','grp_ids');
$mrequired=array("subject"=>"required","body"=>"required","grp_ids"=>"required");

// mailing_archive cols
$ma_cols=array('msg_id','send_date','grp_ids');
$ma_types=array('msg_id'=>"text",'send_date'=>"date",'grp_ids'=>'checkbox');
$ma_source=array('msg_id'=>array('mailing_msg','id','subject'),'grp_ids'=>array('mailing_groups','id','title_en'));
$ma_extend=array('grp_ids'=>array('mailing_groups','title_en','id'));

$ma_Savecols=array('msg_id','send_date','grp_ids');
$ma_required=array("msg_id"=>"required","send_date"=>"required","grp_ids"=>"required");

// mailing_delivary_rep
$md_cols=array('archive_id','recipient_ids','delivered_ids','opened_ids');
$md_types=array('archive_id'=>"text",'recipient_ids'=>"text",'delivered_ids'=>'text','opened_ids'=>'text');
$md_Savecols=array('archive_id','delivered_ids');

//Pages Links
$pageMailing_list="listMailing_list.php";
$pageInsert="insertMailingList.php";
$pageUpdate="updateMailingList.php";
$pageListGroup="listGroups.php";
$pageListMsgs="listAllMsgs.php";
$pageInsertMsg="create_msg.php";
$pageUpdateMsg="update_msg.php";
$pageListArchives="listArchives.php";


?>
