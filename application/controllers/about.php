<?php
class About extends CI_Controller {
 
	function index()
	{
                load_settings();

                $data['content']    = lang("about.content");
                $this->data["meta"] = "about";
                
                $aData['data'] = $this->data;
                
                if(!irAjax())
                $this->load->view('v2/includes/header', $aData);
                
		$this->load->view('v2/about', $data);
                
                if(!irAjax())
                $this->load->view('v2/includes/footer', $aData);
	}
        
        function remove_old_files()
        {
            
            exec('cd /home/wap4/public_html/files/uploaded/ && rm -rf *.mp4 && rm -rf *.flv && rm -rf *.wmv && rm -rf *.avi && rm -rf *.3gp', $aOutput, $nReturn);
            var_dump($aOutput);
            var_dump($nReturn);
            exec('cd /home/wap4/public_html/files/converted/ && rm -rf *.mp4 && rm -rf *.flv && rm -rf *.wmv && rm -rf *.avi && rm -rf *.3gp', $aOutput, $nReturn);
            var_dump($aOutput);
            var_dump($nReturn);
            
        }
}
 
/* End of file about.php */
/* Location: ./system/application/controllers/about.php */