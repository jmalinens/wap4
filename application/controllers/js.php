<?php
class Js extends CI_Controller {
  function __construct() {
    parent::__construct();
    header('Content-type: text/javascript');
    $this->data['extensions'] = $this->config->item('ffmpeg_extensions');
    $this->data['max']        = $this->max_kb;
    $this->data['allowed']    = "'".implode("','", $this->config->item('ffmpeg_allowed'))."'";
  }

  function index() {
    
      $this->load->view("js/wap4_new", $this->data);

  }
}
