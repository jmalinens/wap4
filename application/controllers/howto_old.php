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
    $this->load->view('includes/header', $this->data);
    
    $this->load->view('howto', $data);
    
    if(!irAjax())
    $this->load->view('includes/footer', $this->data);
  }
  
  function codecs() {
        $this->data["meta"] = "codecs";
        
        $this->load->view("includes/header", $this->data);
        $this->load->view("codecs");
        $this->load->view("includes/footer", $this->data);
    }
    
  function formats() {
        $this->data["meta"] = "formats";
        
        $this->load->view("includes/header", $this->data);
        $this->load->view("formats");
        $this->load->view("includes/footer", $this->data);
    }
}
