<?php
class About extends CI_Controller {
 
	function index()
	{
                load_settings();

                $data['content']    = lang("about.content");
                $this->data["meta"] = "about";
                
                if(!irAjax())
                $this->load->view('v2/includes/header', $this->data);
                
		$this->load->view('v2/about', $data);
                
                if(!irAjax())
                $this->load->view('v2/includes/footer', $this->data);
	}
}
 
/* End of file about.php */
/* Location: ./system/application/controllers/about.php */