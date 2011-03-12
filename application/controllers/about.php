<?php
class About extends CI_Controller {
 
	function index()
	{
                //$this->load->helper('wap4');
                load_settings();

		// you might want to just autoload these two helpers
                $data['content'] = lang("about.content");
                
                if(!irAjax())
                $this->load->view('includes/header', $this->data);
                
		$this->load->view('about', $data);
                
                if(!irAjax())
                $this->load->view('includes/footer', $this->data);
	}
}
 
/* End of file about.php */
/* Location: ./system/application/controllers/about.php */