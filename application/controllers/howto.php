<?php
class Howto extends CI_Controller {
  function __construct() {
    parent::__construct();
    $this->load->helper('wap4');
    load_settings();
  }

  function index() {
    // load the view
    $data['content'] = lang("howto.content");

    if(!irAjax())
    $this->load->view('includes/header', $this->data);
    
    $this->load->view('howto', $data);
    
    if(!irAjax())
    $this->load->view('includes/footer', $this->data);
  }
}
