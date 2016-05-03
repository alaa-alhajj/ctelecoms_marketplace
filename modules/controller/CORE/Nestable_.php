<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Nestable
 *
 * @author mohammed
 */
class nestable extends field {

    var $table;
    var $columns;
    var $class;
    var $source;
    var $active;
    var $field_active;
    var $static;
    var $cssClass;
    var $field_static;
    var $delete;
    var $field_id;
    var $field_order;
    var $editParameters;
    var $edit;
    var $field_Parent;
    var $parentValue;
    var $order;
    var $where;
    var $types;
    var $extraflag;
    public function setExtraflag($extraflag) {
        $this->extraflag = $extraflag;
    }
    public function setCssClass($cssClass) {
        $this->cssClass = $cssClass;
    }
    public function setField_order($field_order) {
        $this->field_order = $field_order;
    }
    public function setTypes($types) {
        $this->types = $types;
    }

    public function setWhere($where) {
        $this->where = $where;
    }
    public function setOrder($order) {
        $this->order = $order;
    }
    public function setField_Parent($field_Parent) {
        $this->field_Parent = $field_Parent;
    }
    public function setParentValue($parentValue) {
        $this->parentValue = $parentValue;
    }
    public function setEdit($edit) {
        $this->edit = $edit;
    }
    public function setTable($table) {
        $this->table = $table;
    }
    public function setColumns($columns) {
        $this->columns = $columns;
    }
    public function setClass($class) {
        $this->class = $class;
    }
    public function setSource($source) {
        $this->source = $source;
    }
    public function setActive($active) {
        $this->active = $active;
    }
    public function setField_active($field_active) {
        $this->field_active = $field_active;
    }
    public function setStatic($static) {
        $this->static = $static;
    }

    public function setField_static($field_static) {
        $this->field_static = $field_static;
    }

    public function setDelete($delete) {
        $this->delete = $delete;
    }

    public function setField_id($field_id) {
        $this->field_id = $field_id;
    }

    public function setEditParameters($editParameters) {
        $this->editParameters = $editParameters;
    }

    public function geInsertForm() {
        $ret = "";
        if ($_REQUEST['action'] == 'add') {
            $ret.="<form class='insertNeastd form-inline' action='" . basename($_SERVER['PHP_SELF']) . "'><dd class='form-inline'>"
                    . "";
            $arrayCols = array();
            foreach ($this->columns as $col) {
                if ($col !== $this->field_id && $col !== $this->field_Parent) {

                    $field_ob = new field();
                    $field_ob->SetIdField($col);
                    $field_ob->SetNameField($col);
                    $field_ob->SetValueField($row[$col]);
                    $field_ob->SetTypeField($this->types[$col]);

                    $field_ob->SetCssClass($this->cssClass[$col]);
                    $field_ob->SetTable($this->source[$col][0]);
                    $field_ob->SetTname($this->source[$col][1]);
                    $field_ob->SetTvalue($this->source[$col][2]);
					$field_ob->setWhere($this->source[$col][3]);
                    $column = $field_ob->getField();

                    array_push($arrayCols, "<div class='form-group'><label class='red'><strong class='red'>" . $this->getConstant($col) . ":</strong></label> " . $column . "</div>");
                }
            }
            $ret.=implode(" &nbsp; ", $arrayCols);

            $ret.="<div class='pull-right'>";
            $ret.="<input type='hidden' value='Insert' name='action'>";
            $ret.="<button type='submit' class='btn-e-r btn btn-danger btn-sm'>" . $this->icons->ico['save'] . "</button>";
            $ret.="</div>";
            $ret.="</dd></form>";
        }
        return $ret;
    }

    public function GetMainList() {

        $ret.= '<link href="../../includes/plugins/nestable/nestable.css" rel="stylesheet" type="text/css"/>';


        $ret.="<div class='dd' id='nestable3'>";
        $ret.= $this->geInsertForm();
        $ret.= $this->GetList();
        $ret.= $this->getActivity();
        $ret.= "</div>";
        return $ret;
    }

    public function getScript() {
        if ($_REQUEST['action'] == "") {
            $html = '<div style="height:56px;"><div class="callout callout-nesatable"><p><b>Warning!</b> Your Ordering changes will not be applied until save it <button id="SaveChange" type="button" class="btn btn-danger btn-xs">' . $this->getConstant("save_order") . '</button></p></div></div>';
        }
        if($_REQUEST['action']=="_edit"){
            $actionCollapse="expandAll";
        }else{
            $actionCollapse="collapseAll";
        }
        
        return "<script> $(document).ready(function()
    {
    var OldValue;
    var OldValueStatus=false;
        $('body').on('click','#SaveChange',function() {
      
            $.ajax({
                type: 'POST',
                url: '../ajax/neastedList.php',
                data: {output_data:$('#nestable-output').val(),table:'$this->table',parent:'$this->field_Parent',item_order:'$this->field_order',table_id:'$this->field_id'},
                success: function(data)
                {
                waitingDialog.hide();
            swal({
            title: '',
            text: 'Success Save Ordering',
            type:'success',
            showConfirmButton: false
            , showConfirmButton: false, timer: 2000
        });
        $('.callout.callout-danger').remove();
       
                }
            });
        });
       
        var updateOutput = function(e)
        {
            var list = e.length ? e : $(e.target),
                    output = list.data('output');
            if (window.JSON) {
                output.val(window.JSON.stringify(list.nestable('serialize'))); //, null, 2));                                              
            } else {
                output.val('JSON browser support required for this demo.');
            }
            if(OldValueStatus===false){
               OldValue=$('#nestable-output').val();
               OldValueStatus=true;
              
            }
        };
        $('#nestable3').nestable({
            group: 1,collapsedClass:'dd-collapsed'
        })
                .on('change',function(){updateOutput($('#nestable3').data('output', $('#nestable-output')));if($('.callout.callout-danger').length===0 && OldValueStatus!==$('#nestable-output').val());if($('.callout-nesatable').html()==undefined)$('.box-body').prepend('$html');if ($(window).scrollTop() > 170)$('.callout-nesatable').addClass('fixed');});
        updateOutput($('#nestable3').data('output', $('#nestable-output')).nestable('$actionCollapse'));
    });
    
$(function(){
  $(window).scroll(function () {
        if ($(this).scrollTop() > 170) {
           $('.callout-nesatable').addClass('fixed');
        }else{
           $('.callout-nesatable').removeClass('fixed');
        }
  });
  $('#expandAll').click(function(){ 
     $('.dd').nestable('expandAll');
  });
   $('#collapseAll').click(function(){ 
     $('.dd').nestable('collapseAll');
  });
})

</script>";
    }

    public function getActivity() {
        return '<textarea id="nestable-output" style="display:none;"></textarea>' . $this->getScript();
    }

    public function __construct() {
        parent::__construct();


        $this->setActive(true);
        $this->setField_active("active");
        $this->setEdit("");
        $this->setDelete(true);
    }

    function whereStrm() {
        if ($this->where == '') {
            $this->where = array();
        }
    }

    public function GetLabel() {
        $result = "<div class='nestable-label'>";
        $bool = false;
        $result.="<div class='first-item'>";
        $arrayCols = array();
        foreach ($this->columns as $col) {
            if ($col !== $this->field_id && $col !== $this->field_Parent) {
                array_push($arrayCols, $this->getConstant($col));
            }
        }
        $result.="</div>";
        $result.="<div class='second-item'>";
        if ($this->delete) {
            $result.="<div class='right-item'><input type='checkbox' id='SelectAll' value='$id' class='checkbox'></div>\n";
        }
        $result.="</div>";
        return $result;
    }

    public function checkEditParmeters($row) {
        $return = false;
        if ($_REQUEST['action'] == '_edit') {
            foreach ($this->editParameters as $parmeter) {
                if ($row[$parmeter] == $_REQUEST[$parmeter]) {
                    $return = true;
                } else {
                    $return = false;
                    return $return;
                }
            }
        }
        return $return;
    }

    function getModuleButtons() {
        global $module_id;
        $roles = new roles();
        $class_btn = "btn btn-default btn-sm";
        $btns.="<div class='pull-right'>";

        $link = "";
        if (strtolower(end(explode('.', $_SERVER['REQUEST_URI']))) == 'php') {
            $link = $_SERVER['REQUEST_URI'] . "";
        } else {
            $link = str_replace(array("&action=add", "action=_edit", "&action=add", "action=_edit"), "", $_SERVER['REQUEST_URI']);
        }
        $btns.="<a href='$link'  class='$class_btn'><i class='fa fa-refresh'></i></a>";

        $role_insert = $roles->getUserRoles($_SESSION['cms-grp-id'], $module_id, 'insert');
        echo $this->pageInsert;
        if ($role_insert != "") {
            $id = "Add";
            $link = $this->pageInsert;

            if (strtolower(end(explode('.', $_SERVER['REQUEST_URI']))) == 'php') {
                $link = $_SERVER['REQUEST_URI'] . "?action=add";
            } else {
                $link = str_replace(array("&action=add", "action=_edit", "&action=add", "action=add"), "", $_SERVER['REQUEST_URI']) . "&action=add";
            }

            $btns.="<a href='$link' id='$id' class='$class_btn'><i class='fa fa-plus'></i></a>";
        }
        $role_delete = $roles->getUserRoles($_SESSION['cms-grp-id'], $module_id, 'delete');
        if ($role_delete != "") {
            $id = "AskDelete";
            $link = "javascript:void(0);";
            $btns.="<a href='$link' id='$id' class='$class_btn'><i class='fa fa-trash'></i></a>";
        }
        $btns.="</div>";

        return $btns. '<button type="button" class="btn btn-sm btn-default" id="collapseAll">Collapse All</button><button class="btn btn-sm btn-default" type="button" id="expandAll">Expand All</button>';;
    }

    public function GetList($parent = '', $value = '') {

        if ($parent == '') {
            $parent = $this->field_Parent;
        }
        if ($value == '') {
            $value = $this->parentValue;
        }

        $columns = implode(',', $this->columns);
        $this->where[$this->field_Parent] = $value;
        $list = $this->fpdo->from($this->table)->select($columns)->where($this->where)->orderBy($this->order)->fetchAll();


        $result = "";

        if (count($list) > 0) {
			
            $result.='<ol class="dd-list" data-id="' . $value . '">' . "\r\n";
            foreach ($list as $row) {
                $bool = false;
                $result.='<li class="dd-item dd3-item" data-id="' . $row[$this->field_id] . '">' . "\r\n";

                $result.='<div class="dd-handle dd3-handle"></div>'
                        . '<div class="dd3-content form-inline">';
                if ($this->checkEditParmeters($row)) {
                    $result.="<form method='post' action='" . basename($_SERVER['PHP_SELF']) . "'>";
                }
                $result.="<div class='left-item'>";
                $arrayCols = array();
                $editLink = basename($_SERVER['PHP_SELF']) . "?action=_edit";

                foreach ($this->columns as $col) {

                    if ($col != $this->field_id && $col != $this->field_Parent && $col != $this->field_active && $col != $this->field_static) {
                        $column = "<label>" . $row[$col] . "</label>";
                        if ($_REQUEST['action'] == '_edit') {

                            if ($this->checkEditParmeters($row)) {
                                $field_ob = new field();
                                $field_ob->SetIdField($col);
                                $field_ob->SetNameField($col);
                                $field_ob->SetValueField($row[$col]);
                                $field_ob->SetTypeField($this->types[$col]);

                                $field_ob->SetCssClass($this->cssClass[$col]);
                                $field_ob->SetTable($this->source[$col][0]);
                                $field_ob->SetTname($this->source[$col][1]);
                                $field_ob->SetTvalue($this->source[$col][2]);
                                $field_ob->setWhere($this->source[$col][3]);
                                $column = $field_ob->getField();
                            }
                        }
                        array_push($arrayCols, "<div class='form-group'><label class='red'><strong class='red'>" . $this->getConstant($col) . ":</strong></label> " . $column . "</div>");
                    }
                }

                foreach ($this->editParameters as $parmeter) {
                    $editLink.="&" . $parmeter . "=" . $row[$parmeter];
                }
                $arrayCols[0] = "<b>" . $arrayCols[0] . "</b>";
                $result.=implode(" &nbsp; ", $arrayCols);
                $result.="</div>";
                $result.="<div class='second-item'>";

                if ($this->checkEditParmeters($row)) {
                    foreach ($this->editParameters as $parameter) {
                        $result.="<input type='hidden' value='" . $_REQUEST[$parameter] . "' name='$parameter'>";
                    }
                    $result.="<input type='hidden' value='Edit' name='action'>";
                    $result.="<button type='submit' class='btn-e-r btn btn-danger btn-sm'>" . $this->icons->ico['save'] . "</button>";
                } else {
                    if ($this->delete) {
                        $result.="<div class='right-item'><input type='checkbox' value='" . $row[$this->field_id] . "' name='DeleteRow[]' class='checkbox'></div>\n";
                    }
                    $result.='<div class="dropdown nestable-dropdown">
  <button class="btn-option dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
    <i class="glyphicon glyphicon-cog"></i></button>
  <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">';
                    if ($this->edit != "") {
                        $result.="<li><a href='$editLink'>" . $this->icons->ico['edit'] . " " . $this->getConstant("Edit") . "</a></li>";
                    }
                    if ($this->static == true) {
                        $result.="<div class='right-item'>" . _Static . "</div>";
                    }
                    if ($this->active == true) {
                        $result.="<li>" . $this->switcher($this->table, $row[$this->field_id], $this->field_active, $row[$this->field_active], "SwitcherV", $this->field_id, "Active") . "</li>";
                    }
                    foreach ($this->extraflag as $flag) {
                        $result.="<li>" . $this->switcher($this->table, $row[$this->field_id], $flag, $row[$flag], "SwitcherV", $this->field_id, $flag) . "</li>";
                    }
                    $result.= ' </ul>
                 </div>';
                }

                $result.="</div>";
                if ($this->checkEditParmeters($row)) {
                    $result.="</form>";
                }
                $result.="</div>";
                $result.=$this->GetList($this->field_Parent, $row[$this->field_id]);
                $result.='</li>' . "\r\n";
            }
            $result.='</ol> ' . "\r\n";

            return $result;
        } else {
            return false;
        }
    }

}
