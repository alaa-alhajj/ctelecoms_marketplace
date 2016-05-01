<?php

class GenerateFormField extends utils {

    var $idForm;
    var $nameForm;
    var $cssForm;
    var $columns;
    var $types;
    var $values;
    var $requireds;
    var $extendTables;
    var $classes;
    var $extra;
    var $textAreaRows;
    var $textAreaCols;
    var $legths;
    var $ClassMain;
    var $submit;
    var $SubMain;
    var $AsForm;
    var $AppendToForm;
    var $countCell;
    var $cellClassWithCount;
    var $backBtn = true;
    var $skipBtn = false;
    var $urlSkip;
    var $submitName;
    var $AddBtn = false;
    var $AddButtonName;
    var $urlAdd;
    var $AddButtonClass;
    var $SaveCloseBtn = false;
    var $SaveCloseButtonName;

    public function getBackBtn() {
        return $this->backBtn;
    }

    public function setBackBtn($backBtn) {
        $this->backBtn = $backBtn;
    }

    public function setSkipBtn($skipBtn, $urlSkip) {
        $this->BtnSkip = $skipBtn;
        $this->SkipURL = $urlSkip;
    }

    public function setAddBtn($AddBtn, $urlAdd, $AddButtonName, $AddButtonClass) {
        $this->BtnADD = $AddBtn;
        $this->AddButtonURL = $urlAdd;
        $this->AddButtonName = $AddButtonName;
        $this->AddButtonClass = $AddButtonClass;
    }

    public function setSaveCloseBtn($SaveCloseBtn, $SaveCloseButtonName) {
        $this->BtnSaveClose = $SaveCloseBtn;
        $this->SaveCloseButtonName = $SaveCloseButtonName;
    }

    public function getCellClassWithCount() {
        return $this->cellClassWithCount;
    }

    public function setCellClassWithCount($cellClassWithCount) {
        $this->cellClassWithCount = $cellClassWithCount;
    }

    public function getCountCell() {
        return $this->countRow;
    }

    public function setCountCell($countCell) {
        $this->countCell = $countCell;
    }

    public function getAppendToForm() {
        return $this->AppendToForm;
    }

    public function setAppendToForm($AppendToForm) {
        $this->AppendToForm = $AppendToForm;
    }

    public function getAsForm() {
        return $this->AsForm;
    }

    public function setAsForm($AsForm) {
        $this->AsForm = $AsForm;
    }

    public function getSubMain() {
        return $this->SubMain;
    }

    public function setSubMain($SubMain) {
        $this->SubMain = $SubMain;
    }

    public function getSubmit() {
        return $this->submit;
    }

    public function setSubmit($submit) {
        $this->submit = $submit;
    }

    public function setSubmitName($submitName) {
        $this->submitName = $submitName;
    }

    public function getLegths() {
        return $this->legths;
    }

    public function getClassMain() {
        return $this->ClassMain;
    }

    public function setLegths($legths) {
        $this->legths = $legths;
    }

    public function setClassMain($ClassMain) {
        $this->ClassMain = $ClassMain;
    }

    public function setTextAreaRows($textAreaRows) {
        $this->textAreaRows = $textAreaRows;
    }

    public function setTextAreaCols($textAreaCols) {
        $this->textAreaCols = $textAreaCols;
    }

    public function setIdForm($idForm) {
        $this->idForm = $idForm;
    }

    public function setNameForm($nameForm) {
        $this->nameForm = $nameForm;
    }

    public function setCssForm($cssForm) {
        $this->cssForm = $cssForm;
    }

    public function setColumns($columns) {
        $this->columns = $columns;
    }

    public function setTypes($types) {
        $this->types = $types;
    }

    public function setValues($values) {
        $this->values = $values;
    }

    public function setRequireds($requireds) {
        $this->requireds = $requireds;
    }

    public function setExtendTables($extendTables) {
        $this->extendTables = $extendTables;
    }

    public function setClasses($classes) {
        $this->classes = $classes;
    }

    public function setExtra($extra) {
        $this->extra = $extra;
    }

    function __construct() {
        $this->setClassMain(ClassMain);
        $this->setSubmit(true);
        $this->setAsForm(true);
        $this->setSubMain(array(LabelSubCss, ReqSubCss, FIELDSubCss));
    }

    public function getForm($action) {
        $res = "";
        $res.="<div class='box box-danger form-horizontal'><div class='box-body'>";
        if ($this->AsForm == true) {
            $res.="<form method='post' class='' name='$this->nameForm' id='$this->idForm'><input type='hidden' name='backLink' value='" . $_SERVER['HTTP_REFERER'] . "' novalidate>";
        }

        $res.="<div class='Form-Field form-generate-voila $this->cssForm'>";
        $countColumns = 0;

        if ($this->countCell > 0) {

            $countColumns = round(count($this->columns) / $this->countCell);

            $res.="<div class='$this->cellClassWithCount'>";
        }

        $i = 0;

        foreach ($this->columns as $column) {
            if (!$countColumns) {

                $res.="<div class='$this->ClassMain'>";
            }
            if ($countColumns > 0) {

                if (fmod($i, $countColumns) == 0 && $i != 0) {

                    $res.="</div><div class='$this->cellClassWithCount'>";
                }
            }
            $i++;

            $res.="<div class='form-group'>";

            $span = "";
            if ($this->requireds[$column] == 'required' || in_array($column, $this->requireds)) {
                $span = "<span class='red  required' >* </span>";
            } else {
                $span = "<span class=' req'>&nbsp; </span>";
            }

            $res.="<label class='" . $this->SubMain[0] . " control-label'>" . $span . $this->getConstant($column) . ": </label>";





            $field_ob = new field();
            if ($this->types[$column] == 'map') {
                $field_ob->setMap($this->values[$column]);
            }

            $field_ob->SetIdField($column);
            $field_ob->SetNameField($column);
            $field_ob->SetCssClass($this->classes[$column] . " form-control");
            $field_ob->SetValueField($this->values[$column]);
            $field_ob->SetTypeField($this->types[$column]);
            $field_ob->SetTable($this->extendTables[$column][0]);
            $field_ob->SetTname($this->extendTables[$column][1]);
            $field_ob->SetTvalue($this->extendTables[$column][2]);
            $field_ob->setWhere($this->extendTables[$column][3]);
            $field_ob->SetRequiredField($this->requireds[$column]);
            if (!$this->extendTables[$column][4]) {
                $this->extendTables[$column][4] = true;
            }

            $field_ob->setWithAdd($this->extendTables[$column][4]);
            $field_ob->SetExtra($this->extra[$column]);
            $field_ob->SetInputLength($this->legths[$column]);
            $res.="<div class='" . $this->SubMain[2] . "'>";
            $res.= $field_ob->getField();

            $res.="</div></div>";
            $res.="<div class='hr'><hr></div>";
            if (!$countColumns) {

                $res.="</div>";
            }
        }

        if ($this->countCell) {
            $res.="</div>";
        }


        $res.=$this->AppendToForm;
        $res.="  <div class='col-sm-12'>";



        $res.="   <input type='hidden' value='$action' name='action' id='action'>";

        if ($this->backBtn == true) {
            $res.=$this->back();
        }
        if ($this->BtnSkip == true) {

            $res.=$this->SkipButton($this->SkipURL);
        }
        if ($this->BtnADD == true) {

            $res.=$this->ADDButton($this->AddButtonURL, $this->AddButtonName, $this->AddButtonClass);
        }
        if($this->BtnSaveClose==true){
            $submit_name = $this->SaveCloseButtonName;
            if ($submit_name != "") {
                $val = "value='$submit_name'";
            }
            $res.="<input type='submit' name='saveClose' class='btn btn-new' $val>";
        }
        if ($this->submit == true) {
            $submit_name2 = $this->submitName;
            if ($submit_name2 != "") {
                $val2 = "value='$submit_name2'";
            }
            $res.="<input type='submit' class='btn btn-submit' $val2>";
        }
        
        $res.="  </div>";
        $res.=" </div>";
        if ($this->AsForm == true) {
            $res.="</form>";
        }
        $res.="</div></div>";
        return $res;
    }

}
