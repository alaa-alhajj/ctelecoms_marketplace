<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of voila
 *
 * @author mohammed
 */
class VoilaController extends ExtensionBridge {

    protected $fpdo;

    function __construct() {

        parent::__construct();
        
        global $fpdo;
        $this->fpdo = $fpdo;
    }

    function ObField() {
        return new field();
    }

    function ObUtils() {
        return new utils();
    }

    function ObIcons() {
        return new Icons();
    }

    function ObGenerateFormField() {
        return new GenerateFormField();
    }

    function ObSaveForm() {
        return new SaveForm();
    }

    function ObListTable() {
        return new ListTable();
    }

	function ObMailBox() {
		return new MailBox();
	}
 
	function ObComplaints() {
		return new Complaints();
	}
   
    function obRoles() {
        return new roles();
    }
	
    function obTemplateMaker() {
        return new tempMaker();
    }
    function ObMailList() {
	return new MailList();
    }
    function obcms_lang() {
	return new cms_lang();
    }
}
