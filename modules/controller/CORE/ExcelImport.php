<?php

include_once "Excel.Import/PHPExcel/IOFactory.php";

Class ExcelImport {

    var $fileToUpload;
    var $table;
    var $cols;
    var $orderCols;
    var $extraFields;
    var $extraFieldsValues;
    var $startRow;
    var $checkColsValues;
    var $excelColRequired;

    public function getExcelColRequired() {
        return $this->excelColRequired;
    }

    public function setExcelColRequired($excelColRequired) {
        $this->excelColRequired = $excelColRequired;
    }

    public function getCheckColsValues() {
        return $this->checkColsValues;
    }

    public function setCheckColsValues($checkColsValues) {
        $this->checkColsValues = $checkColsValues;
    }

    public function getStartRow() {
        return $this->startRow;
    }

    public function setStartRow($startRow) {
        $this->startRow = $startRow;
    }

    public function getExtraFields() {
        return $this->extraFields;
    }

    public function getExtraFieldsValues() {
        return $this->extraFieldsValues;
    }

    public function setExtraFields($extraFields) {
        $this->extraFields = $extraFields;
    }

    public function setExtraFieldsValues($extraFieldsValues) {
        $this->extraFieldsValues = $extraFieldsValues;
    }

    public function getFileToUpload() {
        return $this->fileToUpload;
    }

    public function getTable() {
        return $this->table;
    }

    public function getCols() {
        return $this->cols;
    }

    public function getOrderCols() {
        return $this->orderCols;
    }

    public function setFileToUpload($fileToUpload) {
        $this->fileToUpload = $fileToUpload;
    }

    public function setTable($table) {
        $this->table = $table;
    }

    public function setCols($cols) {
        $this->cols = $cols;
    }

    public function setOrderCols($orderCols) {
        $this->orderCols = $orderCols;
    }

    public function execute() {

        global $fpdo;
        try {
            $objPHPExcel = PHPExcel_IOFactory::load($this->fileToUpload);
        } catch (Exception $e) {
            die('Error loading file "' . pathinfo($this->fileToUpload, PATHINFO_BASENAME) . '": ' . $e->getMessage());
        }


        $allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
        $arrayCount = count($allDataInSheet);
        for ($i = $this->startRow; $i <= $arrayCount; $i++) {
            $colsInsert = array();

            if ($allDataInSheet[$i][$this->excelColRequired] != "") {

                foreach ($this->cols as $col) {
                    if ($this->checkColsValues[$col] != "") {

                        $queryCheck = $fpdo->from($this->checkColsValues[$col][0])
                                        ->where($this->checkColsValues[$col][2], trim($allDataInSheet[$i][$this->orderCols[$col]]))->fetch();

                        if ($queryCheck > 0) {
                            $colsInsert["`" . $col . "`"] = $queryCheck[$this->checkColsValues[$col][1]];
                        } else {
                            $queryInsert = $fpdo->InsertInto($this->checkColsValues[$col][0], array($this->checkColsValues[$col][2] => trim($allDataInSheet[$i][$this->orderCols[$col]])))->execute();
                            $colsInsert["`" . $col . "`"] = $queryInsert;
                        }
                    } else {
                        $colsInsert["`" . $col . "`"] = trim($allDataInSheet[$i][$this->orderCols[$col]]);
                    }
                }
                $q = $fpdo->insertInto($this->table, $colsInsert)->execute();
            }
        }
    }

}
