<?php
/****************************************************************************************
* LiveZilla objects.external.inc.php
* 
* Copyright 2014 LiveZilla GmbH
* All rights reserved.
* LiveZilla is a registered trademark.
* 
* Improper changes to this file may cause critical errors.
***************************************************************************************/ 

if(!defined("IN_LIVEZILLA"))
	die();
	
class GroupBuilder
{
	public $InternalUsers;
	public $GroupAvailable = false;
	public $GroupValues = array();
	public $Result;
	public $ErrorHTML = "''";
	public $ReqGroup;
	public $ReqOperator;
	public $Parameters;
	
	function __construct($_reqGroup="",$_reqOperator="",$allowCom=true)
	{
        $reqGroup = UserGroup::ReadParams();
		$this->ReqGroup = (!empty($reqGroup)) ? $reqGroup : $_reqGroup;
		$this->ReqOperator = (!empty($_GET[GET_EXTERN_INTERN_USER_ID])) ? Encoding::Base64UrlDecode($_GET[GET_EXTERN_INTERN_USER_ID]) : $_reqOperator;
		$this->GroupValues["groups_online"] = Array();
		$this->GroupValues["groups_offline"] = Array();
		$this->GroupValues["groups_online_amounts"] = Array();
		$this->GroupValues["groups_output"] = Array();
		$this->GroupValues["groups_hidden"] = Array();
		$this->GroupValues["set_by_get_user"] = null;
		$this->GroupValues["set_by_get_group"] = null;
		$this->GroupValues["set_by_cookie"] = null;
		$this->GroupValues["set_by_standard"] = null;
		$this->GroupValues["set_by_online"] = null;
		$this->GroupValues["req_for_user"] = !empty($this->ReqOperator);
		$this->GroupValues["req_for_group"] = !empty($this->ReqGroup);
		$this->Parameters = Communication::GetTargetParameters($allowCom);

		if($this->Parameters["include_group"] != null || $this->Parameters["include_user"] != null)
		{
			foreach(Server::$Groups as $gid => $group)
				if(!($this->Parameters["include_group"] != null && in_array($gid,$this->Parameters["include_group"])))
				{
					if(!($this->Parameters["include_user"] != null && in_array($gid,Server::$Operators[Operator::GetSystemId($this->Parameters["include_user"])]->GetGroupList(false))))
						$this->GroupValues["groups_hidden"][] = $gid;
				}
		}
		if($this->Parameters["exclude"] != null)
			$this->GroupValues["groups_hidden"] = $this->Parameters["exclude"];
	}
	
	function GetTargetGroup(&$_operatorCount,$_prInternalId="",$_prGroupId="",$offdef = null,$offdefocunt=0)
	{
		$groups = array_merge($this->GroupValues["groups_output"],$this->GroupValues["groups_offline"]);
		if(!empty($_prInternalId) && !empty(Server::$Operators[$_prInternalId]) && Server::$Operators[$_prInternalId]->Status < USER_STATUS_OFFLINE)
        {
            if(!empty($_prGroupId) && Server::$Operators[$_prInternalId]->IsInGroup($_prGroupId))
                if(Server::$Groups[$_prGroupId]->IsExternal && !in_array($_prGroupId,$this->GroupValues["groups_hidden"]) && Server::$Groups[$_prGroupId]->IsOpeningHour(false))
                {
                    $_operatorCount = (!empty($this->GroupValues["groups_online_amounts"][$_prGroupId])) ? $this->GroupValues["groups_online_amounts"][$_prGroupId] : 0;
                    return $_prGroupId;
                }

			foreach(Server::$Operators[$_prInternalId]->GetGroupList(true) as $id)
				if(Server::$Groups[$id]->IsExternal && !in_array($id,$this->GroupValues["groups_hidden"]) && Server::$Groups[$id]->IsOpeningHour(false))
				{
					$_operatorCount = (!empty($this->GroupValues["groups_online_amounts"][$id])) ? $this->GroupValues["groups_online_amounts"][$id] : 0;
                    return $id;
				}
        }

		if(defined("IGNORE_WM") || empty($this->GroupValues["set_by_get_group"]))
		{
			$_operatorCount = 0;
			foreach($groups as $id => $values)
				if(Server::$Groups[$id]->IsExternal && !in_array($id,$this->GroupValues["groups_hidden"]) && Server::$Groups[$id]->IsOpeningHour(false) && Server::$Groups[$id]->IsHumanAvailable() /*&& !Server::$Groups[$id]->HasWelcomeManager()*/)
				{
					$_operatorCount = (!empty($this->GroupValues["groups_online_amounts"][$id])) ? $this->GroupValues["groups_online_amounts"][$id] : 0;
                    return $id;
				}
		}

		$_operatorCount = 0;
		foreach($groups as $id => $values)
			if(Server::$Groups[$id]->IsExternal && !in_array($id,$this->GroupValues["groups_hidden"]) && Server::$Groups[$id]->IsOpeningHour(false))
			{
				$_operatorCount = (!empty($this->GroupValues["groups_online_amounts"][$id])) ? $this->GroupValues["groups_online_amounts"][$id] : 0;
                return $id;
			}
			else if(Server::$Groups[$id]->IsStandard || empty($offdef))
			{
				$offdefocunt = (!empty($this->GroupValues["groups_online_amounts"][$id])) ? $this->GroupValues["groups_online_amounts"][$id] : 0;
                $offdef = $id;
			}
		$_operatorCount = $offdefocunt;
		return $offdef;
	}
	
	function GetHTML($_language)
	{
		$html_groups = "";
		foreach(Server::$Groups as $id => $group)
			if($group->IsExternal && !in_array($id,$this->GroupValues["groups_hidden"]))
				$html_groups .= "<option value=\"".$id."\">".$group->GetDescription($_language)."</option>";
		return $html_groups;
	}
	
	function Generate($_user=null,$_allowBots=false)
	{
		foreach(Server::$Operators as $operator)
		{
			if($operator->LastActive > (time()-Server::$Configuration->File["timeout_clients"]) && $operator->Status < USER_STATUS_OFFLINE && ($_allowBots || !$operator->IsBot) && !$operator->MobileSleep())
			{
                if(!$operator->IsExternal(Server::$Groups))
                    continue;

				$igroups = $operator->GetGroupList(true);
				for($count=0;$count<count($igroups);$count++)
				{
					if($operator->UserId == $this->ReqOperator)
						if(!($this->GroupValues["req_for_group"] && $igroups[$count] != $this->ReqGroup) || (isset($_GET[GET_EXTERN_PREFERENCE]) && Encoding::Base64UrlDecode($_GET[GET_EXTERN_PREFERENCE]) == "user"))
							$this->GroupValues["set_by_get_user"] = $igroups[$count];

					if(!isset($this->GroupValues["groups_online_amounts"][$igroups[$count]]))
						$this->GroupValues["groups_online_amounts"][$igroups[$count]] = 0;

					if($operator->IsBot)
						$this->GroupValues["groups_online_amounts"][$igroups[$count]]+=1;
					else if(isset(Server::$Groups[$igroups[$count]]))
                    {
                        if($operator->GetMaxChatAmountStatus(Server::$Groups[$igroups[$count]]) != USER_STATUS_AWAY)
						    $this->GroupValues["groups_online_amounts"][$igroups[$count]]+=2;
                    }
				}
			}
		}
		$counter = 0;
        if(is_array(Server::$Groups))
		    foreach(Server::$Groups as $id => $group)
            {
                if(!$group->IsExternal)
                    continue;

                $used = false;
                $language = LocalizationManager::GetBrowserLocalization();
                $language = $language[0];
                $amount = (isset($this->GroupValues["groups_online_amounts"]) && is_array($this->GroupValues["groups_online_amounts"]) && array_key_exists($id,$this->GroupValues["groups_online_amounts"]) && $group->IsOpeningHour()) ? $this->GroupValues["groups_online_amounts"][$id] : 0;
                $transport = base64_encode($id) . "," . base64_encode($amount) . "," . base64_encode($group->GetDescription($language)) . "," . base64_encode($group->Email);

                if($this->GroupValues["req_for_group"] && $id == $this->ReqGroup)
                    {$this->GroupValues["set_by_get_group"] = $id;$used=true;}
                elseif(Cookie::Get("login_group") != null && $id == Cookie::Get("login_group") && !isset($requested_group) && !empty(Server::$Configuration->File["gl_save_op"]))
                    {$this->GroupValues["set_by_cookie"] = $id;$used=true;}
                elseif($group->IsStandard)
                    {$this->GroupValues["set_by_standard"] = $id;$used=true;}
                elseif(empty($this->GroupValues["set_by_online"]))
                    {$this->GroupValues["set_by_online"] = $id;$used=true;}

                if(!in_array($id,$this->GroupValues["groups_hidden"]) && ($group->IsExternal || $used))
                {
                    $counter++;
                    if($amount > 0)
                    {
                        $this->GroupAvailable = true;
                        $this->GroupValues["groups_online"][$id] = $transport;
                    }
                    else
                    {
                        if($group->IsStandard)
                        {
                            $na[$id] = $transport;
                            $na = array_merge($na,$this->GroupValues["groups_offline"]);
                            $this->GroupValues["groups_offline"] = $na;
                        }
                        else
                            $this->GroupValues["groups_offline"][$id] = $transport;
                    }
                }
            }
		if(isset($_GET[GET_EXTERN_PREFERENCE]) && Encoding::Base64UrlDecode($_GET[GET_EXTERN_PREFERENCE]) == "group")
		{
			if(isset($this->GroupValues["groups_online_amounts"][$this->ReqGroup]) && $this->GroupValues["groups_online_amounts"][$this->ReqGroup] > 0)
			{
				$this->GroupValues["set_by_get_user"] = null;
				$this->GroupValues["req_for_user"] = false;
			}
		}

		if(!empty($this->GroupValues["set_by_get_user"]) && isset($this->GroupValues["groups_online"][$this->GroupValues["set_by_get_user"]]))
			$this->GroupValues["groups_output"][$this->GroupValues["set_by_get_user"]] = $this->GroupValues["groups_online"][$this->GroupValues["set_by_get_user"]];
		else if(!empty($this->GroupValues["set_by_get_group"]) && isset($this->GroupValues["groups_online"][$this->GroupValues["set_by_get_group"]]))
			$this->GroupValues["groups_output"][$this->GroupValues["set_by_get_group"]] = $this->GroupValues["groups_online"][$this->GroupValues["set_by_get_group"]];
		else if(!empty($this->GroupValues["set_by_cookie"]) && isset($this->GroupValues["groups_online"][$this->GroupValues["set_by_cookie"]]))
			$this->GroupValues["groups_output"][$this->GroupValues["set_by_cookie"]] = $this->GroupValues["groups_online"][$this->GroupValues["set_by_cookie"]];
		else if(!empty($this->GroupValues["set_by_standard"]) && isset($this->GroupValues["groups_online"][$this->GroupValues["set_by_standard"]]))
			$this->GroupValues["groups_output"][$this->GroupValues["set_by_standard"]] = $this->GroupValues["groups_online"][$this->GroupValues["set_by_standard"]];
		else if(!empty($this->GroupValues["set_by_online"]) && isset($this->GroupValues["groups_online"][$this->GroupValues["set_by_online"]]))
			$this->GroupValues["groups_output"][$this->GroupValues["set_by_online"]] = $this->GroupValues["groups_online"][$this->GroupValues["set_by_online"]];
		else if(!empty($this->GroupValues["set_by_cookie"]) && empty($this->GroupValues["groups_output"]) && !empty($this->GroupValues["groups_offline"][$this->GroupValues["set_by_cookie"]]))
			$this->GroupValues["groups_output"][$this->GroupValues["set_by_cookie"]] = $this->GroupValues["groups_offline"][$this->GroupValues["set_by_cookie"]];
		else if(!empty($this->GroupValues["set_by_get_group"]) && empty($this->GroupValues["groups_output"]) && !empty($this->GroupValues["groups_offline"][$this->GroupValues["set_by_get_group"]]))
			$this->GroupValues["groups_output"][$this->GroupValues["set_by_get_group"]] = $this->GroupValues["groups_offline"][$this->GroupValues["set_by_get_group"]];
		
		foreach($this->GroupValues["groups_online"] as $id => $transport)
			if(!isset($this->GroupValues["groups_output"][$id]))
				$this->GroupValues["groups_output"][$id] = $transport;

		if(empty($this->GroupValues["set_by_get_group"]) || empty($this->GroupValues["groups_online_amounts"][$this->GroupValues["set_by_get_group"]]))
		{
			$ngroups = array();
			foreach($this->GroupValues["groups_output"] as $id => $group)
			{
				$ngroups[$id] = (!empty($this->GroupValues["groups_online_amounts"][$id])) ? $this->GroupValues["groups_online_amounts"][$id] : 0;
				
				if($id == $this->GroupValues["set_by_standard"])
					$ngroups[$id] = 10000;
			}
			arsort($ngroups);
			$nsgroups = array();
			foreach($ngroups as $id => $amount)
				$nsgroups[$id] = $this->GroupValues["groups_output"][$id];
			$this->GroupValues["groups_output"] = $nsgroups;
		}

		$result = array_merge($this->GroupValues["groups_output"],$this->GroupValues["groups_offline"]);
		
		foreach($result as $key => $value)
		{
			$chat_input_fields = "new Array(";
			$count = 0;
			foreach(Server::$Groups[$key]->ChatInputsHidden as $index)
			{
				if($count > 0)$chat_input_fields.=",";
				$chat_input_fields.="'".$index."'";
				$count++;
			}
			$value .= ",".base64_encode($chat_input_fields . ");");
			$chat_input_fields = "new Array(";
			$count = 0;
			foreach(Server::$Groups[$key]->ChatInputsMandatory as $index)
			{
				if($count > 0)$chat_input_fields.=",";
				$chat_input_fields.="'".$index."'";
				$count++;
			}
			$value .= ",".base64_encode($chat_input_fields . ");");
		
			$ticket_input_fields = "new Array(";
			$count = 0;
			foreach(Server::$Groups[$key]->TicketInputsHidden as $index)
			{
				if($count > 0)$ticket_input_fields.=",";
				$ticket_input_fields.="'".$index."'";
				$count++;
			}
			$value .= ",".base64_encode($ticket_input_fields . ");");
			$ticket_input_fields = "new Array(";
			$count = 0;
			foreach(Server::$Groups[$key]->TicketInputsMandatory as $index)
			{
				if($count > 0)$ticket_input_fields.=",";
				$ticket_input_fields.="'".$index."'";
				$count++;
			}
			$value .= ",".base64_encode($ticket_input_fields . ");");
			$mes = PredefinedMessage::GetByLanguage(Server::$Groups[$key]->PredefinedMessages,(($_user != null) ? $_user->Language : ""));
			if($mes != null)
			{
				$value .= ",".base64_encode($mes->ChatInformation);
				$value .= ",".base64_encode($mes->CallMeBackInformation);
				$value .= ",".base64_encode($mes->TicketInformation);
			}
			else
			{
				$value .= ",".base64_encode("");
				$value .= ",".base64_encode("");
				$value .= ",".base64_encode("");
			}
			
			$count = 0;
			$com_tickets_allowed = "new Array(";
			foreach(Server::$Groups[$key]->ChatVouchersRequired as $cttid)
			{
				if($count > 0)$com_tickets_allowed.=",";
				$com_tickets_allowed.="'".$cttid."'";
				$count++;
			}
			$value .= ",".base64_encode($com_tickets_allowed. ");");
			
			if(!empty($this->Result))
				$this->Result .= ";" . $value;
			else
				$this->Result = $value;
		}
		if($counter == 0)
			$this->ErrorHTML = "lz_chat_data.Language.ClientErrorGroups";
	}

    static function GetLanguageSelects($_mylang,$tlanguages="")
    {
        foreach(Server::$Languages as $iso => $langar)
            if($langar[1])
                $tlanguages .= "<option value=\"".strtolower($iso)."\"".(($_mylang[0]==$iso || (strtolower($iso) == strtolower(Server::$Configuration->File["gl_default_language"]) && (empty($_mylang[0]) || (!empty($_mylang[0]) && isset(Server::$Languages[$_mylang[0]]) && !Server::$Languages[$_mylang[0]][1]))))?" SELECTED":"").">".$langar[0]."</option>";
        return $tlanguages;
    }
}

class ExternalChat
{
    static function ReadTextColor()
    {
        return Communication::ReadParameter("etc","#448800");
    }

    static function ReadBackgroundColor()
    {
        return Communication::ReadParameter("epc","#73be28");
    }

    static function Login($_user,$_group)
    {
        Server::InitDataBlock(array("INPUTS"));
        $_user->Browsers[0]->UserData->LoadFromLogin($_group);
        if(!empty($_POST["p_cmb"]))
        {
            $_user->Browsers[0]->CallMeBack = true;
            $_user->Browsers[0]->SetCallMeBackStatus(1);
        }

        $_user->Browsers[0]->ApplyUserData();
        $_user->AddFunctionCall("lz_chat_set_status(lz_chat_data.STATUS_INIT);",false);
    }

    static function Listen($_user,$init=false)
    {
        global $USER,$VOUCHER;
        $USER = $_user;
        if(!(IS_FILTERED && !FILTER_ALLOW_CHATS))
        {
            if(!empty($_POST["p_tid"]))
            {
                $VOUCHER = VisitorChat::GetMatchingVoucher(Encoding::Base64UrlDecode($_POST[POST_EXTERN_USER_GROUP]),Encoding::Base64UrlDecode($_POST["p_tid"]));
                if($VOUCHER != null)
                    $USER->Browsers[0]->ChatVoucherId = $VOUCHER->Id;
            }
            if(empty($USER->Browsers[0]->ChatId))
            {
                $USER->Browsers[0]->SetChatId();
                $init = true;
            }

            if($USER->Browsers[0]->Status == CHAT_STATUS_OPEN)
            {
                Server::InitDataBlock(array("INTERNAL"));
                if(!empty($_POST[POST_EXTERN_USER_GROUP]) && (empty($USER->Browsers[0]->DesiredChatGroup) || $init))
                    $USER->Browsers[0]->DesiredChatGroup = Encoding::Base64UrlDecode($_POST[POST_EXTERN_USER_GROUP]);

                $USER->Browsers[0]->SetCookieGroup();
                $USER->Browsers[0]->LoadForward(false,false); // <!---------------------------------------

                $result = $USER->Browsers[0]->FindOperator(VisitorChat::$Router,$USER,false,false,null,true,$USER->Browsers[0]->Forward != null);

                if(!$result && count(VisitorChat::$Router->OperatorsBusy) == 0)
                {
                    $USER->AddFunctionCall("lz_chat_add_system_text(8,null);",false);
                    $USER->AddFunctionCall("lz_chat_stop_system();",false);
                }
                else if((count(VisitorChat::$Router->OperatorsAvailable) + count(VisitorChat::$Router->OperatorsBusy)) > 0)
                {
                    $USER->AddFunctionCall("lz_chat_set_id('".$USER->Browsers[0]->ChatId."');",false);
                    $chatPosition = VisitorChat::$Router->GetQueuePosition($USER->Browsers[0]->DesiredChatGroup);
                    $chatWaitingTime = VisitorChat::$Router->GetQueueWaitingTime($chatPosition);
                    ExternalChat::Login($USER,Server::$Groups[$USER->Browsers[0]->DesiredChatGroup]);
                    $USER->Browsers[0]->SetWaiting(empty(VisitorChat::$DynamicGroup) && !($chatPosition == 1 && count(VisitorChat::$Router->OperatorsAvailable) > 0 && !(!empty($USER->Browsers[0]->DesiredChatPartner) && Server::$Operators[$USER->Browsers[0]->DesiredChatPartner]->Status == USER_STATUS_BUSY)));

                    if(!$USER->Browsers[0]->Waiting)
                    {
                        $USER->Browsers[0]->ShowConnecting($USER,!empty($_GET["acid"]));
                        $USER->AddFunctionCall("lz_chat_set_status(lz_chat_data.STATUS_ALLOCATED);",false);
                        if(Server::$Configuration->File["gl_alloc_mode"] != ALLOCATION_MODE_ALL || !empty($USER->Browsers[0]->DesiredChatPartner) || !empty(VisitorChat::$DynamicGroup))
                        {
                            if($USER->Browsers[0]->CreateSPAMFilter())
                            {
                                $USER->AddFunctionCall("lz_chat_add_system_text(8,null);",false);
                                $USER->AddFunctionCall("lz_chat_stop_system();",false);
                            }
                            else
                                $USER->Browsers[0]->CreateChat(Server::$Operators[$USER->Browsers[0]->DesiredChatPartner],$USER,true);
                        }
                        else
                        {
                            $run=0;
                            foreach(VisitorChat::$Router->OperatorsAvailable as $intid => $am)
                                $USER->Browsers[0]->CreateChat(Server::$Operators[$intid],$USER,false,"","",true,($run++==0));
                        }
                    }
                    else
                    {
                        if(!empty($_GET["acid"]))
                        {
                            $USER->Browsers[0]->ShowConnecting($USER,true);
                            $pchatid = Encoding::Base64UrlDecode($_GET["acid"]);
                            $result = DBManager::Execute(true,"SELECT * FROM `".DB_PREFIX.DATABASE_VISITOR_CHATS."` WHERE `visitor_id`='".DBManager::RealEscape($USER->Browsers[0]->UserId)."' AND `chat_id`='".DBManager::RealEscape($pchatid)."' AND (`exit` > ".(time()-60)." OR `exit`=0) LIMIT 1;");
                            if($result && DBManager::GetRowCount($result) == 1)
                            {
                                $row = DBManager::FetchArray($result);
                                if(!empty($row["request_operator"]) && !empty($row["request_group"]))
                                    $USER->Browsers[0]->TakeChat($row["request_operator"],$row["request_group"]);
                                if(!empty($row["waiting"]))
                                {
                                    $posts = unserialize($row["queue_posts"]);
                                    foreach($posts as $post)
                                        $USER->AddFunctionCall("lz_chat_repost_from_queue('".$post[0]."');",false);
                                    $USER->AddFunctionCall("lz_chat_data.QueuePostsAdded = true;",false);
                                }
                            }
                        }
                        if($USER->Browsers[0]->IsMaxWaitingTime(true))
                        {
                            displayDeclined();
                            return $USER;
                        }
                        if(empty($_GET["acid"]))
                        {
                            $USER->Browsers[0]->ShowQueueInformation($USER,$chatPosition,$chatWaitingTime,LocalizationManager::$TranslationStrings["client_queue_message"]);
                            $gqmt = $USER->Browsers[0]->ShowGroupQueueInformation($USER,$USER->Browsers[0]->QueueMessageShown);
                            if(!empty($gqmt))
                                $USER->AddFunctionCall("lz_chat_add_system_text(99,'".base64_encode($gqmt)."');",false);
                        }

                        if(!VisitorChat::$Router->WasTarget && !empty($USER->Browsers[0]->DesiredChatPartner))
                            $USER->Browsers[0]->DesiredChatPartner = "";

                        $USER->Browsers[0]->CreateArchiveEntry(null,$USER);
                    }
                }
            }
            else
            {
                $action = $USER->Browsers[0]->GetMaxWaitingTimeAction(false);
                if($action == "MESSAGE" || ($action == "FORWARD" && !$USER->Browsers[0]->CreateAutoForward($USER)))
                {
                    $USER->Browsers[0]->ExternalClose();
                    displayDeclined();
                }
                else
                {
                    if(empty($USER->Browsers[0]->ArchiveCreated) && !empty($USER->Browsers[0]->DesiredChatPartner))
                        $USER->Browsers[0]->CreateChat(Server::$Operators[$USER->Browsers[0]->DesiredChatPartner],$USER,true);
                    activeListen();
                }
            }

            if($USER->Browsers[0]->Status <= CHAT_STATUS_WAITING && empty($_POST["p_wls"]) && empty(VisitorChat::$DynamicGroup))
                $USER->AddFunctionCall("lz_chat_show_waiting_links('".base64_encode($wl = Server::$Groups[$USER->Browsers[0]->DesiredChatGroup]->GetWaitingLinks($USER->Browsers[0]->UserData->Text,Visitor::$BrowserLanguage))."');",false);
        }
        else
            displayFiltered();
        return $USER;
    }

    static function GetAllowedParameters()
    {
        $allowed = array("e"=>true,"acid"=>true,"kbo"=>true,"ofc"=>true,"nct"=>true,"hfc"=>true,"edg"=>true,"ckf"=>true,"hfk"=>true,"t"=>true,"cmb"=>true,"code"=>true,"en"=>true,"ee"=>true,"el"=>true,"ep"=>true,"eq"=>true,"ec"=>true,"eh"=>true,"mp"=>true,"dl"=>true,"grot"=>true,"rgs"=>true,"epc"=>true,"etc"=>true,"hcgs"=>true,"htgs"=>true,GET_EXTERN_GROUP=>true,"intid"=>true,"pref"=>true,"cboo"=>true,"hg"=>true,"cf0"=>true,"cf1"=>true,"cf2"=>true,"cf3"=>true,"cf4"=>true,"cf5"=>true,"cf6"=>true,"cf7"=>true,"cf8"=>true,"cf9"=>true,"f0"=>true,"f1"=>true,"f2"=>true,"f3"=>true,"f4"=>true,"f5"=>true,"f6"=>true,"f7"=>true,"f8"=>true,"f9"=>true,"f111"=>true,"f112"=>true,"f113"=>true,"f114"=>true,"f115"=>true,"f116"=>true);
        return Communication::GetTargetParameterString("",$allowed);
    }

    static Function ReplaceLogo($_html)
    {
        if(isset($_GET[GET_EXTERN_USER_HEADER]) && !empty($_GET[GET_EXTERN_USER_HEADER]))
            $_html = str_replace("<!--logo-->","<img src=\"".Encoding::Base64UrlDecode($_GET[GET_EXTERN_USER_HEADER])."\" border=\"0\"><br>",$_html);
        else if(!empty(Server::$Configuration->File["gl_cali"]))
            $_html = str_replace("<!--logo-->","<img src=\"".Server::$Configuration->File["gl_cali"]."\" border=\"0\"><br>",$_html);
        if(!empty(Server::$Configuration->File["gl_cahi"]))
            $_html = str_replace("<!--background-->","<img src=\"".Server::$Configuration->File["gl_cahi"]."\" border=\"0\"><br>",$_html);
        return $_html;
    }
}

class ChatRouter
{
    public $OperatorsBusy;
    public $OperatorsAvailable;
    public $TargetGroupId;
    public $TargetOperatorSystemId;
    public $PreviousOperatorSystemId;
    public $WasTarget = false;
    public $IsPredefined = false;

    public static $WelcomeManager;

    function Find($_visitor,$_allowBots=false,$_requireBot=false,$_exclude=null,$_isForward=false)
    {
        $util=0;
        $this->OperatorsAvailable = array();
        $this->OperatorsBusy = array();
        $backup_target = null;
        $direct_target = null;
        $result = true;
        $fromDepartment = $fromDepartmentBusy = false;
        $this->TargetOperatorSystemId = $this->PreviousOperatorSystemId;
        $predefined = $this->GetPredefinedOperator($_visitor,$direct_target,$_allowBots,$_requireBot);
        $this->WasTarget = (!empty($this->PreviousOperatorSystemId) || !empty($predefined));

        foreach(Server::$Groups as $id => $group)
            $utilization[$id] = 0;

        foreach(Server::$Operators as $systemId => $internal)
        {
            if(!empty($_exclude) && in_array($systemId,$_exclude))
                continue;

            if(!$internal->IsExternal(Server::$Groups,null,null,$_isForward))
                continue;

            if(!$internal->MobileSleep($_visitor->Browsers[0]) && !$internal->PrioritySleep($this->TargetGroupId) && $internal->Status != USER_STATUS_OFFLINE && ($_allowBots || !$internal->IsBot) && (!$_requireBot || $internal->IsBot))
            {
                $group_chats[$systemId] = $internal->GetExternalChatAmount();
                $group_names[$systemId] = $internal->Fullname;
                $group_available[$systemId] = GROUP_STATUS_UNAVAILABLE;
                if(in_array($this->TargetGroupId,$internal->GetGroupList(true)))
                {
                    $intstatus = $internal->GetMaxChatAmountStatus(Server::$Groups[$this->TargetGroupId]);
                    if(ChatRouter::$WelcomeManager && $internal->IsBot && $internal->WelcomeManager)
                        $this->TargetOperatorSystemId = $systemId;
                    if(($intstatus == USER_STATUS_ONLINE && ($internal->LastChatAllocation < (time()-10) || $internal->IsBot)) || ($intstatus == USER_STATUS_BUSY && !empty(VisitorChat::$DynamicGroup)))
                        $group_available[$systemId] = GROUP_STATUS_AVAILABLE;
                    elseif($intstatus == USER_STATUS_BUSY || ($internal->LastChatAllocation >= (time()-10) && !$internal->IsBot))
                    {
                        $group_available[$systemId] = GROUP_STATUS_BUSY;
                        $this->OperatorsBusy[$systemId] = $systemId;

                        if(empty($direct_target) && $predefined == $systemId)
                        {
                            if($this->TargetOperatorSystemId != $predefined)
                            {
                                $this->TargetOperatorSystemId = $predefined;
                                $this->IsPredefined = true;
                            }
                        }
                    }
                }
                else
                {
                    $intstatus = $internal->GetMaxChatAmountStatus();
                    if($intstatus == USER_STATUS_ONLINE)
                        $backup_target = $internal;
                    else if($intstatus == USER_STATUS_BUSY && empty($backup_target))
                        $backup_target = $internal;

                    if(!$this->IsPredefined && !empty($this->TargetOperatorSystemId) && $this->TargetOperatorSystemId == $systemId)
                        $this->TargetOperatorSystemId = null;
                }
                $igroups = $internal->GetGroupList(true);
                for($count=0;$count<count($igroups);$count++)
                {
                    if($this->TargetGroupId == $igroups[$count])
                    {
                        if(!is_array($utilization[$igroups[$count]]))
                            $utilization[$igroups[$count]] = Array();
                        if($group_available[$systemId] == GROUP_STATUS_AVAILABLE)
                            $utilization[$igroups[$count]][$systemId] = $group_chats[$systemId];
                    }
                }
            }
        }
        if(isset($utilization[$this->TargetGroupId]) && is_array($utilization[$this->TargetGroupId]))
        {
            arsort($utilization[$this->TargetGroupId]);
            reset($utilization[$this->TargetGroupId]);
            $util = end($utilization[$this->TargetGroupId]);
            $this->OperatorsAvailable = $utilization[$this->TargetGroupId];
        }
        if(isset($group_available) && is_array($group_available) && in_array(GROUP_STATUS_AVAILABLE,$group_available))
            $fromDepartment = true;
        elseif(isset($group_available) && is_array($group_available) && in_array(GROUP_STATUS_BUSY,$group_available))
            $fromDepartmentBusy = true;

        if(isset($group_chats) && is_array($group_chats) && isset($fromDepartment) && $fromDepartment)
            foreach($group_chats as $systemId => $amount)
                if(($group_available[$systemId] == GROUP_STATUS_AVAILABLE && $amount <= $util) || ((!empty($_visitor->Browsers[0]->Forward) && $_visitor->Browsers[0]->Forward->Processed) && isset($predefined) && $systemId == $predefined))
                    $available_internals[] = $systemId;

        if($fromDepartment && sizeof($available_internals) > 0)
        {
            if(is_array($available_internals))
            {
                if(!empty($predefined) && (in_array($predefined,$available_internals) || Server::$Operators[$predefined]->Status == USER_STATUS_ONLINE))
                    $matching_internal = $predefined;
                else
                {
                    if(!Is::Null($inv_sender = $_visitor->Browsers[0]->GetLastInvitationSender()) && in_array($inv_sender,$available_internals))
                    {
                        $matching_internal = $inv_sender;
                    }
                    else
                    {
                        $available_internals_prio = array();
                        $available_internals_prio_max = array();
                        $maxp = 0;

                        foreach($available_internals as $systemId)
                        {
                            $available_internals_prio[$systemId] = Server::$Groups[$this->TargetGroupId]->GetChatPriority($systemId);
                            $maxp = max($maxp,$available_internals_prio[$systemId]);
                        }

                        foreach($available_internals_prio as $systemId => $prio)
                        {
                            if($prio == $maxp)
                            {
                                $available_internals_prio_max[$systemId] = $prio;
                            }
                        }

                        if($maxp > 0)
                        {
                            $matching_internal = array_rand($available_internals_prio_max,1);
                        }
                        else
                        {
                            $matching_internal = array_rand($available_internals,1);
                            $matching_internal = $available_internals[$matching_internal];
                        }
                    }
                }
            }

            if((!$this->IsPredefined && Server::$Configuration->File["gl_alloc_mode"] != ALLOCATION_MODE_ALL) || $direct_target == $matching_internal || Server::$Operators[$matching_internal]->IsBot || !empty(VisitorChat::$DynamicGroup))
                $this->TargetOperatorSystemId = $matching_internal;
        }
        else if($fromDepartmentBusy)
        {
            //if(!$_visitor->Browsers[0]->Waiting)
              //  $_visitor->Browsers[0]->Waiting = true;
        }
        else
        {
            $result = false;
            $this->OperatorsAvailable = array();
        }
        if(!$this->IsPredefined && Server::$Configuration->File["gl_alloc_mode"] == ALLOCATION_MODE_ALL && (!empty(Server::$Configuration->File["gl_iada"]) || !empty(Server::$Configuration->File["gl_imda"])))
        {
            if(!empty($this->TargetOperatorSystemId) && count($_visitor->ChatRequests)>0 && !Server::$Operators[$this->TargetOperatorSystemId]->IsBot)
            {
                if((!empty(Server::$Configuration->File["gl_iada"]) && !empty($_visitor->ChatRequests[0]->EventActionId)) || (!empty(Server::$Configuration->File["gl_imda"]) && empty($_visitor->ChatRequests[0]->EventActionId)))
                {
                    $result = true;
                    $this->TargetOperatorSystemId = null;
                }
            }
        }
        return $result;
    }

    function GetPredefinedOperator($_user,&$direct_target,$_allowBots,$_requireBot,$desired="")
    {
        if(!empty($this->TargetOperatorSystemId) && isset(Server::$Operators[$this->TargetOperatorSystemId]) && Server::$Operators[$this->TargetOperatorSystemId]->Status < USER_STATUS_OFFLINE)
        {
            if(!(!empty($this->TargetGroupId) && !in_array($this->TargetGroupId,Server::$Operators[$this->TargetOperatorSystemId]->GetGroupList(true))))
                $desired = $this->TargetOperatorSystemId;
        }
        else
        {
            $this->TargetOperatorSystemId = null;
            $opParam = Operator::ReadParams();
            if(!empty($opParam))
                $desired = $direct_target = Operator::GetSystemId($opParam);
            else if(!Is::Null(Cookie::Get("internal_user")) && !empty(Server::$Configuration->File["gl_save_op"]))
            {
                $desired = Operator::GetSystemId(Cookie::Get("internal_user"));
                if(!empty(Server::$Operators[$desired]) && !(!empty($this->TargetGroupId) && !in_array($this->TargetGroupId,Server::$Operators[$desired]->GetGroupList(true))))
                    $direct_target = $desired;
                else
                    $desired = "";
            }
            else if(empty($desired) && !empty(Server::$Configuration->File["gl_save_op"]))
            {
                $desired = $_user->GetLastChatOperator(true);
            }
        }

        if(!empty($desired) && Server::$Operators[$desired]->MobileSleep($_user->Browsers[0]))
            $this->TargetOperatorSystemId = $desired = "";
        else if(!empty($desired) && !$_allowBots && Server::$Operators[$desired]->IsBot)
            $this->TargetOperatorSystemId = $desired = "";
        else if(!empty($desired) && $_requireBot && !Server::$Operators[$desired]->IsBot)
            $this->TargetOperatorSystemId = $desired = "";

        return $desired;
    }

    function GetQueuePosition($_targetGroup,$_startTime=0,$_position = 1)
    {
        global $USER;
        $USER->Browsers[0]->SetStatus(CHAT_STATUS_OPEN);
        DBManager::Execute(true,"UPDATE `".DB_PREFIX.DATABASE_VISITOR_CHATS."` SET `qpenalty`=`qpenalty`+60 WHERE `last_active`>".(time()-Server::$Configuration->File["timeout_chats"])." AND `status`=0 AND `exit`=0 AND `last_active`<" . DBManager::RealEscape(time()-max(20,(Server::$Configuration->File["poll_frequency_clients"]*2))));
        $result = DBManager::Execute(true,"SELECT `priority`,`request_operator`,`request_group`,`chat_id`,`first_active`,`qpenalty`+`first_active` as `sfirst` FROM `".DB_PREFIX.DATABASE_VISITOR_CHATS."` WHERE `status`='0' AND `exit`='0' AND `chat_id`>0 AND `last_active`>".(time()-Server::$Configuration->File["timeout_chats"])." ORDER BY `priority` DESC,`sfirst` ASC;");
        if($result)
        {
            while($row = DBManager::FetchArray($result))
            {
                if($row["chat_id"] == $USER->Browsers[0]->ChatId)
                {
                    $_startTime = $row["sfirst"];
                    break;
                }
                else if($row["request_group"]==$_targetGroup && $row["request_operator"]==$USER->Browsers[0]->DesiredChatPartner)
                {
                    $_position++;
                }
                else if($row["request_group"]==$_targetGroup && ($row["request_operator"]!=$USER->Browsers[0]->DesiredChatPartner && empty($row["request_operator"])))
                {
                    $_position++;
                }
                else if(!empty($USER->Browsers[0]->DesiredChatPartner) && $USER->Browsers[0]->DesiredChatPartner==$row["request_operator"])
                {
                    $_position++;
                }
            }
        }
        define("CHAT_START_TIME",$_startTime);
        return $_position;
    }

    function GetQueueWaitingTime($_position,$min=1)
    {
        $busy = max(1,count($this->OperatorsBusy));
        $result = DBManager::Execute(true,"SELECT AVG(`duration`) AS `waitingtime` FROM `".DB_PREFIX.DATABASE_CHAT_ARCHIVE."` WHERE `chat_type`=1 AND `duration`>30 AND `duration`<3600;");
        if($result)
        {
            $row = DBManager::FetchArray($result);
            if(!empty($row["waitingtime"]))
                $min = ($row["waitingtime"]/60)/$busy;
            else
                $min = $min/$busy;

            $minb = $min;
            for($i = 1;$i < $_position; $i++)
            {
                $minb *= 0.9;
                $min += $minb;
            }
            $min /= Server::$Configuration->File["gl_sim_ch"];
            $min -= abs((time() - CHAT_START_TIME) / 60);
            if($min <= 0)
                $min = 1;
        }
        return min(10,ceil($min));
    }
}
?>
