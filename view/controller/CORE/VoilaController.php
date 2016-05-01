<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of voila
 *
 * @author Ahmad mahmoud
 */
class VoilaController  {

    protected $fpdo;

    function __construct() {

        global $fpdo;
        $this->fpdo = $fpdo;
    }

    function ObUtils() {
        return new utils();
    }
    function ObWidgets() {
        return new widgets();
    }
	
    function obPath() {
        return new path();
    }
}
