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
    
    $aData['data'] = $this->data;
    
    if(!irAjax())
    $this->load->view('v2/includes/header', $aData);
    
    $this->load->view('v2/howto', $data);
    
    if(!irAjax())
    $this->load->view('v2/includes/footer', $aData);
  }
  
  function codecs() {
      
        $this->data["meta"] = "codecs";
        
        $aData['data'] = $this->data;
        
        $this->load->view("v2/includes/header", $aData);
        $this->load->view("v2/codecs");
        $this->load->view("v2/includes/footer", $aData);
    }
    
  function formats() {
        $this->data["meta"] = "formats";
        
        $aData['data'] = $this->data;
        
        $this->load->view("v2/includes/header", $aData);
        $this->load->view("v2/formats");
        $this->load->view("v2/includes/footer", $aData);
    }
}
