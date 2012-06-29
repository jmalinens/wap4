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
                load_settings();
	}

	function index()
	{
            
                if($this->uri->segment(4) && ctype_alnum($this->uri->segment(4)))
                {
                    $uniqid             = $this->uri->segment(4);
                } else {
                    $uniqid             = uniqid();
                }

                $xmlUrl = $this->config->item("ffmpeg_files_dir")."presets.xml"; // XML feed file/URL
                $xmlStr = file_get_contents($xmlUrl);

                $xmlObj = simplexml_load_string($xmlStr);
                $arrXml = objectsIntoArray($xmlObj);

                array_sort($arrXml, 'category', SORT_DESC);

                $this->data['presets']    = $arrXml;
            	$this->data['allowed']    = "'".implode("','", $this->config->item('ffmpeg_allowed'))."'";
                $this->data['uniqid']     = $uniqid;
                $this->data['message']    = '';
                $this->data['users']      = '';
                $this->data['attr']       = array('id' => 'conv');
                $this->data['formats']    = $this->ffmpeg->ffmpeg_formats;
                $this->data['max']        = $this->max_kb;
		$this->data['navigation'] = $this->config->item('navigation');
                $this->data['extensions'] = $this->config->item('ffmpeg_extensions');
                $this->data['lang']       = $this->lang->lang();
                
                $this->load->view('v2/includes/header', $this->data);
                if(!isMobile())
                    $this->load->view('v2/converter', $this->data);
                else
                    $this->load->view('v2/converter_no_js', $this->data);   
                $this->load->view('v2/includes/footer', $this->data);
                
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
