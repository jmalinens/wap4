<?php

/*
 * Codec list
 * @author Juris Malinens <juris.malinens@inbox.lv>
 */

class Codecs Extends CI_Controller {
    function __construct() {
        parent::__construct();
        //$this->load->helper('wap4');
        //load_settings();
    }
    
    function index() {
        redirect("howto/codecs", "location", 301);
        exit;
    }


}

?>
