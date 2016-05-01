<?php

class DetailsRow extends utils {

    var $table;
    var $cols;
    var $class;
    var $condition;
    var $tableClass;
    var $extendTable;
    var $backButton = true;

    public function setTable($table) {
        $this->table = $table;
    }

    public function setCols($cols) {
        $this->cols = $cols;
    }

    public function setClass($class) {
        $this->class = $class;
    }

    public function setCondition($condition) {
        $this->condition = $condition;
    }

    public function setTableClass($tableClass) {
        $this->tableClass = $tableClass;
    }

    public function setExtendTable($extendTable) {
        $this->extendTable = $extendTable;
    }

    public function setBackButton($backButton) {
        $this->backButton = $backButton;
    }

    public function getDetails() {
       $result = "";

        $query = $this->fpdo->from($this->table)->where($this->condition)->limit('0,1')->fetch();
        $result.="<table class='$this->tableClass'>";
        foreach ($this->cols as $col) {
            $result.= "<tr>";
            $result.= "<td>" . $this->getConstant($col) . "</td>";
            if ($this->extendTable[$col] != "") {
                $result.="<td>".$this->lookupField($this->extendTable[$col][0], $this->extendTable[$col][1], $this->extendTable[$col][2], $query[$col])."</td>";
            }else{
                   $result.= "<td>" . $query[$col] . "</td>";
            }
            $result.= "</tr>";
        }
        $result.="</table>";
        if ($this->backButton) {
            $result.=$this->back();
        }
        return $result;
    }

    public function getTable() {

        $result = "";

        $query = $this->fpdo->from($this->table)->where($this->condition)->fetchAll();
        $result.="<table class='$this->tableClass'>";
      
        foreach ($query as $row) {
            $result.= "<tr>";
            foreach ($this->cols as $col) {

                if ($this->extendTable[$col] != "") {
                    $result.="<td>" . $this->lookupField($this->extendTable[$col][0], $this->extendTable[$col][1], $this->extendTable[$col][2], $row[$col]) . "</td>";
                } else {
                    $result.= "<td>" . $row[$col] . "</td>";
                }
            }
            $result.= "</tr>";
        }

        $result.="</table>";
        if ($this->backButton) {
            $result.=$this->back();
        }
        return $result;
    }

}
