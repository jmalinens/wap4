<?php
/**
 *
 * @author donny
 * @property CI_Loader $load
 * @property CI_Input $input
 * @property CI_Output $output
 * @property Wurfl $wurfl
 * @property CI_Email $email
 * @property CI_Form_validation $form_validation
 * @property CI_URI $uri
 * @property Firephp $firephp
 * @property ADOConnection $adodb
 * @property Content_model $content_model
 */

class Wurfl_controller extends CI_Controller {

	function index() {

		$this->load->library('wurfl');
		$this->wurfl->load($_SERVER);
		//echo $this->wurfl->getCapability('max_data_rate');
                $par = $this->wurfl->getAllCapabilities();
                var_dump($par["is_wireless_device"]);
                if($par["is_wireless_device"] == "true")
                {
                    echo"tas ir mobilais";
                }
                else
                {
                    echo"tas nav mobilais";
                }
		//print_r($this->wurfl->getAllCapabilities());

	}
	
}