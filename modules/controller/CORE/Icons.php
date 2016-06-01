<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Icons
 *
 * @author mohammed
 */
class Icons {

    var $ico;

    function __construct() {

        $this->ico = array();
        $this->ico['edit'] = "<i class='fa fa-pencil-square-o'></i>";
        $this->ico['seo'] = "<i class='fa fa-globe'></i>";
        $this->ico['widget'] = "<i class='fa fa-th-list'></i>";
        $this->ico['true'] = "<i class='fa fa-check'></i>";
        $this->ico['false'] = "<i class='fa fa-close'></i>";
        $this->ico['save'] = "<i class='fa fa-save'></i>";
        $this->ico['list'] = "<i class='fa fa-list-ol'></i>";
        $this->ico['view'] = "<i class='fa fa-search'></i>";
        $this->ico['dublicate'] = "<i class='fa fa-files-o'></i>";
         $this->ico['payment'] = "<i class='fa fa-usd'></i>";
    }

    function setIcon($key, $value) {
        $this->ico[$key] = $value;
    }

}
