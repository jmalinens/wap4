<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of error
 *
 * @author Juris Malinens <juris.malinens@inbox.lv>
 */
class error extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('wap4');
        load_settings();
    }

    function index() {


        if (!irAjax()) {
            $this->load->view('includes/header', $this->data);
        }

        $this->load->view('error', $data);

        if (!irAjax()) {
            $this->load->view('includes/footer', $this->data);
        }
    }
    
    function file_not_found_404() {


        if (!irAjax()) {
            $this->load->view('includes/header', $this->data);
        }
        
        $this->load->view('error_404');

        if (!irAjax()) {
            $this->load->view('includes/footer', $this->data);
        }
    }

}

?>
