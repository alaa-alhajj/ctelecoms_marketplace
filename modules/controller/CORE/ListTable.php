<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ListTable
 *
 * @author voila
 */
class ListTable extends utils {

    var $db_table;
    var $where_str;
    var $orderBy;
    var $columns; //will be appeare in list//    
    var $seo;
    var $widget;
    var $edit;
    var $class;
    var $static;
    var $delete;
    var $order;
    var $active;
    var $f_active;
    var $special;
    var $f_special;
    var $f_order;
    var $f_static;
    var $f_id;
    var $editParametar;
    var $limit;
    var $extraLinks;
    var $filter;
    var $source;
    var $isGridList = false; //for insert & update inline//
    var $types; //for insert & update inline//
    var $extendTables; //for insert & update inline//
    var $classes; //for insert & update inline//
    var $extra; //for insert & update inline//
    var $parentAttr;
    var $flags;
    var $backBtn = false;
    var $extraHtml;
    var $debug = false;
    var $header_box;
    var $footer_box;
    var $pageInsert = true;
    var $pageList;
    var $modules_releated;
    var $requireds;
    var $orderCondition;
    var $page_id;
    var $table;
    var $record_id;
    var $redirect;
    var $display = false;
    var $colsInsert;
    var $module;
    var $PurchaseOrderSearch;
    var $Add;
    var $val;
    var $moreButton = false;
    var $ReviewSearch;

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

    public function setRequireds($requireds) {
        $this->requireds = $requireds;
    }

    public function _ModulesReleated($modules_releated) {
        $this->modules_releated = $modules_releated;
    }

    public function _PageInsert($pageInsert) {
        $this->pageInsert = $pageInsert;
    }

    public function _PageList($pageList) {
        $this->pageList = $pageList;
    }

    public function _Debug($debug) {
        $this->debug = $debug;
    }

    public function ExtraHtml($extraHtml) {
        $this->extraHtml = $extraHtml;
    }

    public function setBackBtn($backBtn) {
        $this->backBtn = $backBtn;
    }

    public function setFlags($flags) {
        $this->flags = $flags;
    }

    public function setParentAttr($parentAttr) {
        $this->parentAttr = $parentAttr;
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

    public function getTypes() {
        return $this->types;
    }

    public function _Types($types) {
        $this->types = $types;
    }

    public function getIsGridList() {
        return $this->isGridList;
    }

    public function IsGridList($isGridList) {
        $this->isGridList = $isGridList;
    }

    public function getFilter() {
        return $this->filter;
    }

    public function setFilter($filter) {
        $this->filter = $filter;
    }

    public function getExtraLinks() {
        return $this->extraLinks;
    }

    public function setExtraLinks($extraLinks, $moreButton = false) {
        $this->extraLinks = $extraLinks;
        $this->moreButton = $moreButton;
    }

    public function setLimit($limit) {
        $this->limit = $limit;
    }

    public function setOrderBy($orderBy) {
        $this->orderBy = $orderBy;
    }

    function __construct() {

        parent::__construct();
    }

    function _active($active = true, $f_active = 'active') {
        $this->active = $active;
        $this->f_active = $f_active;
    }

    function _special($special = true, $f_special = 'special') {
        $this->special = $special;
        $this->f_special = $f_special;
    }

    function _static($static = false, $f_static = 'static') {
        $this->static = $static;
        $this->f_static = $f_static;
    }

    function _order($order = false, $f_order = 'ord', $orderCondition = '') {
        $this->order = $order;
        $this->f_order = $f_order;
        $this->orderCondition = $orderCondition;
    }

    function _delete($delete = true) {
        $this->delete = $delete;
    }

    function _Add($Add = true) {
        $this->Add = $Add;
    }

    function _class($class) {
        $this->class = $class;
    }

    function _id($id = 'id') {
        $this->f_id = $id;
    }

    function _seo_page($page_id) {
        $this->page_id = $page_id;
    }

    public function _PurchaseOrderSearch($PurchaseOrderSearch) {
        $this->purchaseOrderSearch = $PurchaseOrderSearch;
    }

    public function _ReviewSearch($ReviewSearch) {
        $this->reviewSearch = $ReviewSearch;
    }

    function _dublicate($table, $record_id, $redirect, $colsInsert, $module, $display, $ajaxFile = "") {
        $this->table_name = $table;
        $this->record_id = $record_id;
        $this->redirect = $redirect;
        $this->cols_insert = $colsInsert;
        $this->module_id = $module;
        $this->display = $display;
        $this->ajaxFile = $ajaxFile;
    }

    function _widget($widget) {
        $this->widget = $widget;
    }

    function _view_page($view_page) {
        $this->view_page = $view_page;
    }

    function _seo($seo) {
        $this->seo = $seo;
    }

    function _edit($edit, $parametars) {
        $this->edit = $edit;
        $this->editParametar = $parametars;
    }

    function _table($table) {

        $this->db_table = $table;
        $this->_default();
    }

    function _condition($cond) {
        $this->where_str = $cond;
    }

    function _source($source) {
        $this->source = $source;
    }

    public function _columns($columns) {

        $this->columns = $columns;
    }

    public function getParametersToPassIt($hidden = true) {

        $parameters = array();
        foreach ($this->parentAttr as $para) {
            if ($_REQUEST[$para] != "") {
                $parameters[$para] = $_REQUEST[$para];
            }
        }
        foreach ($this->filter as $para) {
            if ($_REQUEST[$para[0]] != "") {
                $parameters[$para[0]] = $_REQUEST[$para[0]];
            }
        }
        if ($_REQUEST['pn'] != "") {
            $parameters['pn'] = $_REQUEST['pn'];
        }

        if ($hidden) {
            foreach ($parameters as $key => $para) {
                return "<input type='hidden' name='_params[]' value='$key=$para' />";
            }
        } else {
            return $parameters;
        }
    }

    function _default() {
        $this->_active();
        $this->_special();
        $this->_static();
        $this->_order();
        $this->_delete();
        $this->_Add();
        $this->_id();
        $this->_seo_page();
        $this->_dublicate();
    }

    function GetQueryForReview($customer, $product) {
        $customer = trim($customer);
        $product = trim($product);
        $where = "";
        if ($customer != "") {
            $where.=" and customers.`name` like '%$customer%'";
        }
        if ($product != "") {
            $where.=" and products.`title` like '%$product%'";
        }
        $query = $this->fpdo->from('customer_reviews')
                ->leftJoin('customers on customers.id=customer_reviews.customer_id ')
                ->leftJoin('products on products.id=customer_reviews.product_id ')
                ->where("customer_reviews.id !='0' $where");
        $query->limit($this->limit);
        return $query;
    }

    function GetQueryForSerachPurchaseOrder($customer, $date, $product, $from, $to, $pid) {
        $customer = trim($customer);
        $where = "";
        if ($customer != "") {
            $where.=" and customers.`name` like '%$customer%'";
        }
        if ($date != "") {
            $where.=" and purchase_order.`order_date` like '%$date%'";
        }
        if ($product != "") {
            $where.=" and products.title like '%$product%'";
        }
        if ($pid != "") {
            $where.=" and products.id='$pid'";
        }
        if ($from != "" || $to != "") {
            $from = date('Y-m-d', strtotime($_REQUEST['From']));
            $to = date('Y-m-d', strtotime($_REQUEST['To']));
            $where.=" and purchase_order.`order_date` BETWEEN '$from' AND '$to'";
        }
        $query = $this->fpdo->from('purchase_order')
                ->leftJoin('customers on customers.id=purchase_order.customer_id ')
                ->leftJoin('purchase_order_products on purchase_order_products.purchase_order_id=purchase_order.id ')
                ->leftJoin('products on products.id=purchase_order_products.product_id ')->groupBy("purchase_order_products.purchase_order_id")
                ->where("purchase_order.id !='0' $where");
        $query->limit($this->limit);
        return $query;
    }

    function getCount() {
        $query = $this->fpdo->from($this->db_table)->where($this->where_str);
        if ($this->purchaseOrderSearch != "") {
            $query = $this->GetQueryForSerachPurchaseOrder($this->purchaseOrderSearch[0], $this->purchaseOrderSearch[1], $this->purchaseOrderSearch[2], $this->purchaseOrderSearch[3], $this->purchaseOrderSearch[4]);
        } elseif ($this->reviewSearch != "") {
            $query = $this->GetQueryForReview($this->reviewSearch[0], $this->reviewSearch[1]);
        }
        return count($query->fetchAll());
    }

    function getModuleButtons() {
        global $module_id;
        $roles = new roles();
        $class_btn = "btn btn-default btn-sm";
        $btns.="<div class='pull-right'>";
        $btns.= $this->getRelatedModulesButtons($class_btn);
        $link = "";
        if (strtolower(end(explode('.', $_SERVER['REQUEST_URI']))) == 'php') {
            $link = $_SERVER['REQUEST_URI'] . "";
        } else {
            $link = str_replace(array("&action=add", "action=_edit", "&action=add", "action=_edit"), "", $_SERVER['REQUEST_URI']);
        }
        $btns.="<a href='$link' title='" . $this->getConstant("refresh") . "' data-toggle='tooltip' class='$class_btn'><i class='fa fa-refresh'></i></a>";

        $role_insert = $roles->getUserRoles($_SESSION['cms-grp-id'], $module_id, 'insert');

        if ($role_insert != "" && $this->Add == true) {
            $id = "Add";
            $link = $this->pageInsert;
            if ($this->isGridList == true) {
                if (strtolower(end(explode('.', $_SERVER['REQUEST_URI']))) == 'php') {
                    $link = $_SERVER['REQUEST_URI'] . "?action=add";
                } else {
                    $link = str_replace(array("&action=add", "action=add"), "", $_SERVER['REQUEST_URI']) . "&action=add";
                }
            }
            $btns.="<a href='$link' id='$id' title='" . $this->getConstant("add") . "' data-toggle='tooltip' class='$class_btn'><i class='fa fa-plus'></i></a>";
        }
        $role_delete = $roles->getUserRoles($_SESSION['cms-grp-id'], $module_id, 'delete');
        if ($role_delete != "" && $this->delete == true) {
            $id = "AskDelete";
            $link = "javascript:void(0);";
            $btns.="<a href='$link' id='$id' class='$class_btn' title='" . $this->getConstant("delete") . "' data-toggle='tooltip'><i class='fa fa-trash'></i></a>";
        }
        $btns.="</div>";
        return $btns;
    }

    function getRelatedModulesButtons($class_btn) {
        $btns = "";
        $modules = "";

        if (!is_string($this->modules_releated)) {
            $modules = implode(',', $this->modules_releated);
        } else {
            $modules = $this->modules_releated;
        }
        $query = $this->fpdo->from('cms_modules')->where("id in ($modules)")->fetchAll();
        foreach ($query as $row) {
            $btns.="<a title='" . $row['title'] . "' data-toggle='tooltip' href='../" . $row['static_path'] . "'  class='btn btn-sm btn-danger'>" . $row['title'] . "</a>";
        }
        return $btns;
    }

    function getParentAttr() {

        if ($this->parentAttr != "") {
            $res = "";
            foreach ($this->parentAttr as $attr) {

                $res.="<input type='hidden' name='$attr' value='$_REQUEST[$attr]'>";
            }

            return $res;
        }
    }

    function getLinkBackGridList() {

        if ($_REQUEST['_params']) {
            $_get = array();
            foreach ($_REQUEST['_params'] as $param) {
                array_push($_get, $param);
            }
            $_get = implode('&', $_get);
            return $_SERVER['PHP_SELF'] . ($_get != "" ? "?$_get" : "");
        } else {

            return $_SERVER['PHP_SELF'];
        }
    }

    function getWhereFromFilter($conditions = "", $conditionsGet = "", $forLink = false) {
        $where = array();
        if (count($this->filter) > 0) {
            foreach ($this->filter as $filter) {
                if ($_REQUEST[$filter[0]] != "") {
                    if (!$forLink) {
                        if (is_numeric($_REQUEST[$filter[0]])) {
                            array_push($where, "`" . $filter[0] . "` ='" . $_REQUEST[$filter[0]] . "'");
                        } else {
                            array_push($where, "`" . $filter[0] . "` like'%" . $_REQUEST[$filter[0]] . "%'");
                        }
                    } else {
                        array_push($where, "" . $filter[0] . "=" . $_REQUEST[$filter[0]] . "");
                    }
                }
            }
        }
        foreach ($conditionsGet as $field => $req) {
            if ($_REQUEST[$req] != "") {
                if (!$forLink) {
                    if (is_numeric($_REQUEST[$req])) {
                        array_push($where, "`" . $field . "` ='" . $_REQUEST[$req] . "'");
                    } else {
                        array_push($where, "`" . $field . "` like'%" . $_REQUEST[$req] . "%'");
                    }
                } else {
                    array_push($where, "" . $req . "=" . $_REQUEST[$req] . "");
                }
            }
        }
        $whereQuery = "";
        if (count($where) > 0 || $conditions != "") {
            $whereQuery = implode(' and ', $where);
            if ($conditions != "") {
                if ($whereQuery != "") {
                    $whereQuery.=" AND $conditions";
                } else {
                    $whereQuery.=$conditions;
                }
            }
        }
        if (!$forLink) {
            return $whereQuery;
        } else {
            return "&" . implode('&', $where);
        }
    }

    function FilterTable() {
        if (count($this->filter) > 0) {
            if ((basename($_SERVER['PHP_SELF']) === 'listPurchaseOrder.php')) {
                $br = "<br>";
            } else {
                $br = "";
            }
            $res.= "<form method='get' class='form-inline' action='" . $_SERVER['PHP_SELF'] . "'>";
            $res.= "<div class='form-inline'>";
            $res.= "<div class='form-group'><label class='btn btn-danger'>Filter By: &nbsp;</label></div>" . $br;
            $res.=$this->getParentAttr();

            foreach ($this->filter as $filter) {
                if ($filter['0']) {
                    $field_ob = new field();
                    $field_ob->SetIdField($filter[0]);
                    $field_ob->SetNameField($filter[0]);
                    $field_ob->SetCssClass($filter);
                    $field_ob->SetTypeField($filter[1]);
                    $field_ob->SetValueField($_REQUEST[$filter[0]]);
                    $field_ob->SetTable($filter[2][0]);
                    $field_ob->SetTname($filter[2][1]);


                    $field_ob->SetCssClass('form-control input-sm');
                    if ($_REQUEST['action'] != 'Insert' && $_REQUEST['action'] != 'Edit') {
                        $field_ob->SetTvalue($filter[2][2]);
                    }
                    $field_ob->setWhere($filter[2][3]);
                    $res.= "<div class='form-group'><label >" . $this->getConstant($filter[0]) . ": </label>" . $field_ob->getField() . "</div>";
                }
            }

            $res.="<button type='submit' class='btn btn-danger btn-sm'>Go</button>";
            $res.= "</div>";
            $res.="</form>";
            return $res;
        }
        return false;
    }

    //this function for change item feild value when order_field value is 0 or NULL
    function updateOrderItems() {
        $where = "(`$this->f_order` = '0' or `$this->f_order` IS NULL)";
        $where_new = "";
        if ($this->where_str != "") {

            $where.=" and " . $this->where_str;
            $where_new.=$this->where_str;
        }
        if ($this->orderCondition != "") {


            $where.=" and " . $this->orderCondition;
            if ($where_new == "") {
                $where_new.=$this->orderCondition;
            } else {
                $where_new.=" and " . $this->orderCondition;
            }
        }
        $query = $this->fpdo->from($this->db_table)->where($where)->fetchAll();
        foreach ($query as $row) {
            $newOrder = $this->getNewItemOrder($this->db_table, $this->f_order, $where_new);
            $this->fpdo->update($this->db_table)->set($this->f_order, $newOrder)->where($this->f_id, $row[$this->f_id])->execute();
        }
    }

    function GetListTable() {
        $this->updateOrderItems();
        $query = $this->fpdo->from($this->db_table)->where($this->where_str);
        if ($this->purchaseOrderSearch != "") {
            $query = $this->GetQueryForSerachPurchaseOrder($this->purchaseOrderSearch[0], $this->purchaseOrderSearch[1], $this->purchaseOrderSearch[2], $this->purchaseOrderSearch[3], $this->purchaseOrderSearch[4]);
        } elseif ($this->reviewSearch != "") {
            $query = $this->GetQueryForReview($this->reviewSearch[0], $this->reviewSearch[1]);
        }

        if ($this->orderBy != '') {
            $query->orderBy($this->orderBy);
        }

        if ($this->limit != '') {
            $query->limit($this->limit);
        }

        if ($this->debug == true) {
            echo $query->getQuery();
        }
        // echo $query->getQuery(); die();
        $count = $query->execute();
        $countTable = count($query->execute());
        if ($_REQUEST['action'] == "add") {
            $countTable = 1;
        }
        if ($countTable > 0) {
            if ($this->order == true) {
                $soratble = "sortable";
                $script = ""
                        . " var order_table='$this->db_table';\n"
                        . " var order_filed='$this->f_order';\n"
                        . " var order_id='$this->f_id';\n"
                        . " ordIds=new Array();\n";
            } else {
                $soratble = "";
            }
            if ($_REQUEST['action'] == "_edit") {
                $linkAction = $_SERVER['PHP_SELF'] . "?action=Edit";
            } elseif ($_REQUEST['action'] == "add") {
                $linkAction = $_SERVER['PHP_SELF'] . "?action=Insert";
            }


            $result.=$this->getParentAttr();

            $result.=" <div class='box box-danger'>\n";
            $result.=" <div class='box-header with-border'>\n";
            $result.=$this->FilterTable();
            $result.=" </div>\n";
            $result.=" <div class='box-header with-border'>\n";
            $result.=$this->getModuleButtons();
            $result.=" </div>\n";

            $result.=" <div class='box-body'>\n";
            $result.="<form name='TableForm' action='$linkAction' method='post'>";
            $result.="<table id='TableForm' class='$this->class  table table-bordered table-hover' >\n";
            $result.="<thead>\n";
            $result.="<tr>\n";

            foreach ($this->columns as $column) {
                $result.="<th >";
                if (constant($column)) {
                    $result.=constant($column);
                } else {
                    $result.=$column;
                }
                $result.="</th>";
            }

            $ext = 0;

            if ($this->moreButton == true && count($this->extraLinks) > 0) {
                $result.="<th width=30 align='center'>" . $this->getConstant("More") . "</th>\n";
            } elseif ($this->moreButton == false && count($this->extraLinks) > 0) {

                foreach ($this->extraLinks as $exlink) {

                    $result.="<th width=30>" . $exlink[0] . "</th>\n";
                }
            }




            if ($this->active == true) {
                $result.="<th width=30 align='center'>" . active . "</th>\n";
            }
             if ($this->special == true) {
                $result.="<th width=30>" . special . "</th>\n";
            }
            if ($this->static != '') {
                $result.="<th width=30>" . _static . "</th>\n";
            }
            $i_page = 0;
            foreach ($query as $row_seo) {
                if ($i_page < 1) {
                    if ($row_seo[$this->page_id] > 0) {

                        $result.="<th width=30>" . seo . "</th>\n";
                        $i_page++;
                    }
                }
            }


           
            if ($this->seo == true) {
                $result.="<th width=30>" . seo . "</th>\n";
            }
            $i_dublicate = 0;
            foreach ($query as $row_dublicate) {
                if ($i_dublicate < 1) {
                    if ($this->display == true) {
                        $result.="<th width=30>" . dublicate . "</th>\n";
                        $i_dublicate++;
                    }
                }
            }
            if ($this->widget == true) {
                $result.="<th width=30>" . widget . "</th>\n";
            }
            if ($this->view_page == true) {
                $result.="<th width=30>" . view . "</th>\n";
            }
            if ($this->edit != '') {
                $result.="<th width=30>" . edit . "</th>\n";
            }
            if ($this->delete == true) {
                $result.="<th width=30><input type='checkbox' id='SelectAll'></th>\n";
            }
            $result.="</tr>\n";
            $result.="</thead>\n";
            $result.="<tbody id='$soratble' class='sortable ui-sortable'>\n";
            $m = 1;
            if ($_REQUEST['action'] == 'add' && $this->isGridList == true) {

                if (count($this->types) > 0) {

                    $result.="<tr>";
                    $result.=$this->getParametersToPassIt(true);
                    $result.=$this->getParametersToPassIt();

                    foreach ($this->columns as $col) {
                        $field_ob = new field();
                        $field_ob->SetIdField($col);
                        $field_ob->SetNameField($col);
                        $field_ob->SetCssClass($this->classes[$col]);
                        $field_ob->SetRequiredField($this->requireds[$col]);

                        $field_ob->SetValueField($this->source[$col][3]);
                        $field_ob->SetTypeField($this->types[$col]);
                        $field_ob->SetTable($this->extendTables[$col][0]);
                        $field_ob->SetTname($this->extendTables[$col][1]);
                        $field_ob->SetTvalue($this->extendTables[$col][2]);
                        $field_ob->setWhere($this->extendTables[$col][3]);
                        $field_ob->WithLabel(false);
                        if (!$this->extendTables[$col][4]) {
                            $this->extendTables[$col][4] = true;
                        }
                        if (in_array($col, $this->parentAttr)) {
                            $field_ob->SetValueField($_REQUEST[$col]);
                        }
                        $field_ob->setWithAdd($this->extendTables[$col][4]);
                        $field_ob->SetExtra($this->extra[$col]);
                        $field_ob->SetInputLength($this->legths[$col]);
                        $result.= "<td>" . $field_ob->getField() . "</td>";
                    }
                    $ext = 0;
                    foreach ($this->extraLinks as $exlink) {
                        if ($this->moreButton === true && $exlink != "") {
                            if ($ext < 1) {
                                $result.="<td></td>\n";
                            }
                        } else {
                            $result.="<td></td>\n";
                        }
                        $ext++;
                    }
                    if ($this->active == true) {
                        $result.="<td></td>\n";
                    }
                    if ($this->special == true) {
                        $result.="<td></td>\n";
                    }
                    $i_page = 0;
                    foreach ($query as $row_seo) {
                        if ($i_page < 1) {
                            if ($row_seo[$this->page_id] > 0) {

                                $result.="<td></td>\n";
                                $i_page++;
                            }
                        }
                    }
                    $i_dublicate = 0;
                    foreach ($query as $row_dublicate) {
                        if ($i_dublicate < 1) {
                            if ($this->display == true) {
                                $result.="<td></td>\n";
                                $i_dublicate++;
                            }
                        }
                    }

                    if ($this->static != '') {
                        $result.="<td></td>\n";
                    }
                    if ($this->seo == true) {
                        $result.="<td></td>\n";
                    }
                    if ($this->widget == true) {
                        $result.="<td></td>\n";
                    }
                    if ($this->view_page == true) {
                        $result.="<td></td>\n";
                    }
                    if ($this->edit != '') {
                        $result.="<td><button type='submit' class='btn-e-r btn btn-danger btn-sm'>" . $this->icons->ico['save'] . "</button></td>\n";
                    }
                    if ($this->delete == true) {
                        $result.="<td></td>\n";
                    }
                    $result.="</tr>";
                }
            }

            foreach ($query as $row) {

                $id = stripcslashes($row[$this->f_id]);
                if ($id == $_REQUEST['id'] && $_REQUEST['action'] == '_edit') {

                    $result.="<tr><input type='hidden' name='$this->f_id' value='" . $_REQUEST[$this->f_id] . "'>";
                    $result.= $this->getParametersToPassIt();
                    $result.=$this->getParentAttr();
                    $script.="ordIds[$m]='$id';";
                    $m++;
                    foreach ($this->columns as $column) {
                        $value = "";
                        $value = stripcslashes($row[$column]);

                        if (isset($this->source[$column]) && $this->source[$column] != '') {

                            $value = $this->lookupField($this->source[$column][0], $this->source[$column][1], $this->source[$column][2], $row[$column]);
                        }

                        if ($this->types[$column] == 'photos') {
                            $value = $this->ViewPhotos($value, 1, 1, 50, 50);
                        }
                        $field_ob = new field();
                        $field_ob->SetIdField($column);
                        $field_ob->SetNameField($column);
                        $field_ob->SetCssClass($this->classes[$column]);
                        $field_ob->SetTypeField($this->types[$column]);
                        $field_ob->SetTable($this->extendTables[$column][0]);
                        $field_ob->SetRequiredField($this->requireds[$col]);
                        $field_ob->SetTname($this->extendTables[$column][1]);
                        $field_ob->SetTvalue($this->extendTables[$column][2]);
                        $field_ob->SetValueField(stripcslashes($row[$column]));
                        $field_ob->setWhere($this->extendTables[$column][3]);
                        $field_ob->WithLabel($withLabel);
                        if (!$this->extendTables[$column][4]) {
                            $this->extendTables[$column][4] = true;
                        }
                        $field_ob->setWithAdd($this->extendTables[$column][4]);
                        $field_ob->SetExtra($this->extra[$column]);
                        $field_ob->SetInputLength($this->legths[$column]);
                        if ($this->types[$column] != "") {
                            $result.= "<td>" . $field_ob->getField() . "</td>";
                        } else {
                            $result.= "<td>" . $value . $field_ob->getField() . "</td>";
                        }
                    }

                    $ext = 0;

                    if ($this->moreButton == true && count($this->extraLinks) > 0) {
                        $result.="<td></td>\n";
                    } elseif (count($this->extraLinks) > 0) {
                        foreach ($this->extraLinks as $exlink) {
                            $result.="<td></td>\n";
                        }
                    }



                    if ($this->active == true) {
                        $result.="<td></td>\n";
                    }
                    if ($this->special == true) {
                        $result.="<td></td>\n";
                    }
                      if ($this->static != '') {
                        $result.="<td></td>\n";
                    }
                    $i_page = 0;
                    foreach ($query as $row_seo) {
                        if ($i_page < 1) {
                            if ($row_seo[$this->page_id] > 0) {

                                $result.="<td></td>\n";
                                $i_page++;
                            }
                        }
                    }
                  
                    if ($this->seo == true) {
                        $result.="<td></td>\n";
                    }
                      $i_dublicate = 0;
                    foreach ($query as $row_dublicate) {
                        if ($i_dublicate < 1) {
                            if ($this->display == true) {

                                $result.="<td></td>\n";
                                $i_dublicate++;
                            }
                        }
                    }
                    if ($this->widget == true) {
                        $result.="<td></td>\n";
                    }
                    if ($this->view_page == true) {
                        $result.="<td></td>\n";
                    }
                    if ($this->edit != '') {
                        $result.="<td><button type='submit' class='btn-e-r btn btn-danger btn-sm'>" . $this->icons->ico['save'] . "</button></td>\n";
                    }
                    if ($this->delete == true) {
                        $result.="<td></td>\n";
                    }
                    $result.="</tr>\n";
                } else {

                    $result.="<tr id=" . $id . ">\n";
                    $script.="ordIds[$m]='$id';";
                    $m++;
                    foreach ($this->columns as $column) {
                        $value = "";

                        $value = stripcslashes($row[$column]);

                        if (isset($this->source[$column]) && $this->source[$column] != '') {

                            $v = $this->lookupField($this->source[$column][0], $this->source[$column][1], $this->source[$column][2], $row[$column]);
                            if ($v != "") {
                                $value = $v;
                            }
                        }

                        if ($this->types[$column] == 'photos') {

                            $value = $this->ViewPhotos($value, 1, 1, 50, 50);
                        }
                        $align = "";
                        if ($this->types[$column] == 'flag') {
                            $value = "" . $this->switcher($this->db_table, $row[$this->f_id], $column, $row[$column], "SwitcherV") . "";
                            $align = "align='center'";
                        }

                        if ($this->types[$column] == 'checkbox' && $this->source[$column][0] != "") {

                            $value = $this->getValuesImplode($this->source[$column][0], $this->source[$column][1], $this->source[$column][2], stripcslashes($row[$column]));
                        }
                        $result.="<td $align>" . $value . "</td>\n";
                    }
                    $ext = 0;

                    if ($this->moreButton === true && count($this->extraLinks) > 0) {
                        $result.='<td> <div class="btn-group"><button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false"> MORE  <span class="caret"></span></button><ul class="dropdown-menu ss" role="menu">';
                        foreach ($this->extraLinks as $exlink) {
                            $linkAttr = $exlink[3];

                            $linkO = array();
                            foreach ($linkAttr as $key => $attr) {
                                array_push($linkO, "$key=" . $row[$attr]);
                            }
                            $linkO = implode('&', $linkO);
                            $result.="<li><a title='" . $exlink[0] . "' data-toggle='tooltip' href='$exlink[2]?$linkO' target='$exlink[4]'>$exlink[1]$exlink[0]</a></li>\n";
                        }
                        $result.='</ul></div></td>';
                    } elseif ($this->moreButton === false && count($this->extraLinks) > 0) {

                        foreach ($this->extraLinks as $exlink) {
                            $linkAttr = $exlink[3];

                            $linkO = array();
                            foreach ($linkAttr as $key => $attr) {
                                array_push($linkO, "$key=" . $row[$attr]);
                            }
                            $linkO = implode('&', $linkO);
                            $result.="<td align='center'><a title='" . $exlink[0] . "' data-toggle='tooltip' href='$exlink[2]?$linkO' target='$exlink[4]'>$exlink[1]</a></td>\n";
                        }
                    }




                    if ($this->active == true) {
                        $result.="<td align='center'>" . $this->switcher($this->db_table, $row[$this->f_id], $this->f_active, $row[$this->f_active], "SwitcherV") . "</td>\n";
                    }
                    if ($this->special == true) {
                        $result.="<td align='center'>" . $this->switcher($this->db_table, $row[$this->f_id], $this->f_special, $row[$this->f_special], "SwitcherV") . "</td>\n";
                    }
                    if ($this->static != '') {
                        $result.="<td align='center'>" . $this->switcher($this->db_table, $row[$this->f_id], $this->f_static, $row[$this->f_static], "SwitcherV") . "</td>\n";
                    }


                    if ($row_seo[$this->page_id] > 0) {
                        $id_seo = $row[$this->page_id];
                        $result.="<td align='center'><a href='javascript:;' data-id='$id_seo' class='seoModal'>" . $this->icons->ico['seo'] . "</a></td>\n";
                        $i_page++;
                    }
                    if ($this->display == true) {

                        $cols_pass = "";
                        foreach ($this->cols_insert as $col_insert) {
                            $cols_pass.=$col_insert . ",";
                        }
                        $result.="<td align='center'><a href='javascript:;' data-id='" . $row[$this->record_id] . "' data-redirect='" . $this->redirect . "' data-table='" . $this->table_name . "' data-cols='" . $cols_pass . "' data-module='" . $_SESSION['cmsMID'] . "' data-ajax='" . $this->ajaxFile . "' class='dublicate-button'>" . $this->icons->ico['dublicate'] . "</a></td>\n";
                    }

                    if ($this->seo == true) {
                        $result.="<td align='center'><a href='$this->seo'>" . $this->icons->ico['seo'] . "</a></td>\n";
                    }
                    if ($this->widget == true) {
                        $result.="<td align='center'><a href='$this->widget'>" . $this->icons->ico['widget'] . "</a></td>\n";
                    }

                    if ($this->view_page == true) {
                        $get_page_title = $this->fpdo->from('cms_pages')->where("id='$id'")->fetch();

                        $link_page = $this->view_page . "page" . $id . '/' . $this->rewriteFilter($get_page_title['title']);
                        $result.="<td align='center'><a href='$link_page' target='_blank'>" . $this->icons->ico['view'] . "</a></td>\n";
                    }
                    if ($this->edit != '') {
                        $editLink = $this->edit;
                        $i = 0;
                        foreach ($this->editParametar as $par) {
                            if ($i > 0) {
                                $editLink.="&";
                            } else {
                                $editLink.="?";
                            }
                            $editLink.=$par . "=" . $row[$par];
                            $i++;
                        }
                        if ($this->isGridList) {
                            $con = "";
                            foreach ($_REQUEST as $key => $v) {
                                if ($key != "PHPSESSID" && $key != "site_id" && $key != "action" && $key != $this->f_id) {
                                    $con.= "&$key=$v";
                                }
                            }


                            $editLink = $_SERVER['PHP_SELF'] . "?action=_edit&$this->f_id=$id" . $con;
                        }
                        $result.="<td align='center'><a href='$editLink' title='" . $this->getConstant("edit") . "' data-toggle='tooltip'>" . $this->icons->ico['edit'] . "</a></td>\n";
                    }
                    if ($this->delete == true) {
                        $result.="<td><input type='checkbox' name='DeleteRow[]' value='$id' class='checkbox'></td>\n";
                    }
                    $result.="</tr>\n";
                }
            }
            $result.="</tbody>\n";
            $result.="</table>\n";
            $result.="</form>\n";
            $result.=" </div>\n";
            $result.="<div class='box-footer'>";
            if ($this->extraHtml != "") {
                $result.=$this->extraHtml;
            }
            if ($this->backBtn != "" || $this->backBtn != false) {
                $result.=$this->back($this->backBtn, 'pull-left');
            }

            global $pn, $LPP;
            $pageLink = $this->pageList . "?pn=^";

            foreach ($this->filter as $f) {
                if ($_REQUEST[$f[0]] != "") {
                    $pageLink.="&$f[0]=" . $_REQUEST[$f[0]];
                }
            }
            foreach ($this->parentAttr as $f) {
                if ($_REQUEST[$f] != "") {
                    $pageLink.="&$f=" . $_REQUEST[$f];
                }
            }
            if ($_REQUEST["lpp"] != "") {
                $pageLink.="&lpp=" . $_REQUEST["lpp"];
            }
            $result.=$this->create_pagination($this->getCount(), $pn, $LPP, $pageLink, 'en', 'pull-right');
            $result.="</div>";
            $result.=" </div>\n";
        } else {
            echo $this->getConstant("NoResult");
        }

        $result.="<script>$script</script>\n";
        return $result;
    }

}
