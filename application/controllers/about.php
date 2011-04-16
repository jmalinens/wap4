<?php
class About extends CI_Controller {
 
	function index()
	{
                load_settings();

                $data['content']    = lang("about.content");
                $this->data["meta"] = "about";
                
                if(!irAjax())
                $this->load->view('includes/header', $this->data);
                
		$this->load->view('about', $data);
                
                if(!irAjax())
                $this->load->view('includes/footer', $this->data);
	}
}
 
/* End of file about.php */
/* Location: ./system/application/controllers/about.php */