<?php

class Welcome extends CI_Controller {

	function __construct()
	{
		parent::__construct();
                $this->load->library('ffmpeg');
                $this->config->load('ffmpeg');
                $this->load->model('site_model');
        
                if (!$this->ion_auth->logged_in())
                $this->max_kb = $this->site_model->get_setting('file_size_unregistered')*1024;
                else
                $this->max_kb = $this->site_model->get_setting('file_size_registered')*1024;
                parse_str($_SERVER['QUERY_STRING'], $_GET);
	}

	function index()
	{
                $uniqid                = uniqid();
            	$this->data['allowed'] = "'".implode("','", $this->config->item('ffmpeg_allowed'))."'";
                $this->data['uniqid']  = $uniqid;
                $this->data['message'] = '';
                $this->data['users']   = '';
                $this->data['attr']    = array('id' => 'conv');
                $this->data['formats'] = $this->ffmpeg->ffmpeg_formats;
                $this->data['max']     	= $this->max_kb;
		$this->data['navigation'] = $this->config->item('navigation');
                $this->data['lang'] = $this->lang->lang();
                $this->load->view('includes/header', $this->data);
		//$this->load->view('main_menu', $this->data);
                $this->load->view('includes/footer', $this->data);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
