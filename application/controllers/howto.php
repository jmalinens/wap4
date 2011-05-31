<?php
class Howto extends CI_Controller {
  function __construct() {
    parent::__construct();
    $this->load->helper('wap4');
    load_settings();
  }

  function index() {

    $data['content']    = lang("howto.content");
    $this->data["meta"] = "howto";
    
    if(!irAjax())
    $this->load->view('v2/includes/header', $this->data);
    
    $this->load->view('v2/howto', $data);
    
    if(!irAjax())
    $this->load->view('v2/includes/footer', $this->data);
  }
  
  function codecs() {
        $this->data["meta"] = "codecs";
        
        $this->load->view("v2/includes/header", $this->data);
        $this->load->view("v2/codecs");
        $this->load->view("v2/includes/footer", $this->data);
    }
    
  function formats() {
        $this->data["meta"] = "formats";
        
        $this->load->view("v2/includes/header", $this->data);
        $this->load->view("v2/formats");
        $this->load->view("v2/includes/footer", $this->data);
    }
}
