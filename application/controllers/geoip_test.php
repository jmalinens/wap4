<?php

class Geoip_test extends CI_Controller {

    function __construct() {
    	parent::__construct();
        $this->load->library('geoip_lib');
    }

    function index()
    {
    	$this->load->view('main_menu');
    	/*
    	echo "Welcome to the geoip_lib library demo. Please look at the 'geoip_test.php' controller source code for view this examples.<br />";
        echo "Bienvenido a la demo de la librer�a geoip_lib. Por favor mire el c�digo fuente del controlador 'geoip_test.php' para ver estos ejemplos.";
        
        //SAMPLE 1
        echo '<h2>IP Query 24.24.24.24</h2>';
        echo '<p>--------------------------------------------------------------------------</p>';
        if($this->geoip_lib->InfoIP("24.24.24.24")) {
            echo '<br />City/Ciudad: '.$this->geoip_lib->result_city();
            echo '<br />Country Code/C�digo Pa�s: '.$this->geoip_lib->result_country_code();
            echo '<br />Country Name/Pa�s: '.$this->geoip_lib->result_country_name();
            echo '<br />Custom/Personalizado: '.$this->geoip_lib->result_custom("%CT , %RN (%C3)");
            echo '<br />Array: ';
            echo '<pre>';
            print_r($this->geoip_lib->result_array());
            echo '</pre>';
        } else {
            echo '<strong>IP ERROR</strong>';
        }
     
        //SAMPLE IP CURRENT (IF NOT LOCAL 127.0.0.1)            
        echo '<h2>IP Query Current</h2>';
        echo '<p>--------------------------------------------------------------------------</p>';
        if($this->geoip_lib->InfoIP()) {
            echo '<br />City/Ciudad: '.$this->geoip_lib->result_city();
            echo '<br />Country Code/C�digo Pa�s: '.$this->geoip_lib->result_country_code();
            echo '<br />Country Name/Pa�s: '.$this->geoip_lib->result_country_name();
            echo '<br />Custom/Personalizado: '.$this->geoip_lib->result_custom("%CT , %RN (%C3)");
            echo '<br />Array: ';
            echo '<pre>';
            print_r($this->geoip_lib->result_array());
            echo '</pre>';
        } else {
            echo '<strong>IP ERROR</strong>';
        }
        */
	
    }

}

?>
