<?php (defined('BASEPATH') OR defined('SYSPATH')) or die('No direct access allowed.');

    /*
     * GeoIP Library for CodeIgniter - Version 1.0
     * Writted By Miguel A. Carrascosa (macrvk@gmail.com)
     *
     * (English)
     * This library use the GeoLite Country (binary format) database
     * from the ip address returned country_name, country_code, region, city, 
     * latitude, longitude, postal_code, metro_code (USA) and area_code.
     *
     * The database is from a company called Maxmind specializes in professional GeoIP solutions.
     * They also provide some free databases and free code. 
     * 
     * You should then be able to use the following code in your page: 
     *
     *
     * (Español)
     * Esta librería usa la base de datos Geolite Country (formato binario) 
     * desde la dirección ip devuelve el país, código de país, provincia, ciudad,
     * latitud, longitud, código postal, código metropolitano (USA), y código de area.
     *
     * La base de datos pertenece a la empresa Maxmind especializada en soluciones Geoip profesionales.
     * Ellos también proveen bases de datos gratuitas y código gratuito.
     * 
     * Puedes probar su funcionamiento usando el siguiente código en tu página: 
     *
     * $this->load->library('geoip_lib');
     *
     * $this->geoip_lib->InfoIP("24.24.24.24"); //For the "XXX.XXX.XXX.XXX" ip address        
     * $this->geoip_lib->InfoIP(); //For the current ip address
     *
     * $array_all_data = $this->geoip_lib->result_array();
     * $city           = $this->geoip_lib->result_city();          // Return Syracuse
     * $area_code      = $this->geoip_lib->result_area_code();     // Return 315
     * $country_code   = $this->geoip_lib->result_country_code();  // Return US
     * $country_code3  = $this->geoip_lib->result_country_code3(); // Return USA 
     * $country_name   = $this->geoip_lib->result_country_name();  // Return United States
     * $metro_code     = $this->geoip_lib->result_metro_code();    // Return 555
     * $postal_code    = $this->geoip_lib->result_postal_code();   // Return
     * $latitude       = $this->geoip_lib->result_latitude();      // Return 43.0514
     * $longitude      = $this->geoip_lib->result_longitude();     // Return -76.1495 
     * $region         = $this->geoip_lib->result_region();        // Return NY
     * $region_name    = $this->geoip_lib->result_region_name();   // Return New York
     *
     * Custom vars
     * 
     *  También puedes usar la función personalizada, para devolver la cadena formateada
     *
     *   %IP -> Ip Address
     *   %CO -> Country_code
     *   %C3 -> Country_code3
     *   %CN -> Country_name
     *   %RE -> Region
     *   %RN -> Region name
     *   %CT -> City
     *   %LA -> Latitude
     *   %LO -> Longitude
     *   %PC -> Postal code
     *   %MC -> Metro code
     *   %AC -> Area code
     *
     *   $custon = $this->geoip_lib->result_custom("%CT , %RN (%C3)"); // Return  "Madrid , Madrid (ESP)""
     *
     *
     *
     *
     * Changelog:
     *
     * 2010-10-16   Last code revision.
     *
     */
     
    if (!defined('GEOIP_FILEDATA')) define('GEOIP_FILEDATA', dirname(__FILE__)."/geoip/GeoLiteCity.dat");

    global $GEOIP_REGION_NAME;
    include("geoip/geoipcity.inc");
    require_once 'geoip/geoipregionvars.php';
    
    class Geoip_lib{
        
        protected $CI;
        protected $_Ip;
        protected $_Data;
    	protected $_gi;

    	/**
    	 * Geoip_lib::__construct()
    	 * 
    	 * @return
    	 */
    	function __construct()
    	{
    		if (!isset($this->CI))
    		{
    			$this->CI =& get_instance();
    		}
    	}
        
       /**
        * Geoip_lib::__destruct()
        * 
        * @return
        */
       function __destruct() {
        
      		if (isset($this->_gi))
    		{
    			geoip_close($this->_gi);
    		}   
       }
        
        /**
         * Geoip_lib::_Set_IP()
         * 
         * Set IP from query post
         * 
         * @param mixed $ip
         * @return boolean
         */
        private function _Set_IP($ip=null){
            
            if($ip==null)
            {
                $ip = $this->CI->input->ip_address();
            }
            
            $this->_Ip = $ip;           
            return $this->CI->input->valid_ip($this->_Ip);    
        }
        
        /**
         * Geoip_lib::InfoIP()
         * 
         * Looks up IP in geoip data from database.
         * 
         * @param string $ip
         * @return boolean
         */
        public function InfoIP($ip=null){
            
            if (!$this->_Set_IP($ip))
            {
                $this->_Data = array();
                return false;
            }
            
            return $this->_Query();
                
        }
        
        /**
         * Geoip_lib::_Query()
         *
         * Query to the database ip
         *  
         * @return boolean
         */
        private function _Query() {    
       
      		if (!isset($this->_gi))
    		{
    			$this->_gi = geoip_open(GEOIP_FILEDATA,GEOIP_STANDARD);
    		}

            $this->_Data = geoip_record_by_addr($this->_gi,$this->_Ip);
            return true;
            
        }
        
    	/**
    	 * Geoip_lib::result_array()
    	 *
         * Return array whith all fields
         *  
    	 * @return array
    	 */
    	function result_array()
    	{
            $dev = array(
                'ip'            => $this->result_ip(),
                'country_code'  => $this->result_country_code(),
                'country_code3' => $this->result_country_code3(),
                'country_name'  => $this->result_country_name(),
                'region'        => $this->result_region(),
                'region_name'   => $this->result_region_name(),
                'city'          => $this->result_city(),
                'latitude'      => $this->result_latitude(),    
                'longitude'     => $this->result_longitude(),
                'postal_code'   => $this->result_postal_code(),
                'metro_code'    => $this->result_metro_code(),    
                'area_code'     => $this->result_area_code(),           
            );
            
            return $dev;
    	}

        
        /**
         * Geoip_lib::result_region_name()
         * 
         * Return the Region Name
         * 
         * @return string
         */
        function result_region_name(){
            global $GEOIP_REGION_NAME;
            $code = $this->result_country_code();
            $region = $this->result_region();
            if(!empty($code)&&!empty($region))
            {
                if(isset($GEOIP_REGION_NAME[$code][$region]))
                    return $GEOIP_REGION_NAME[$code][$region];
            }
            return '';
        }

        /**
         * Geoip_lib::result_region()
         * 
         * Return the Region Code
         * 
         * @return string
         */
        function result_region(){
            if(isset($this->_Data->region)){
                return $this->_Data->region;    
            }else{
                return '';
            }
            
        }
        
        /**
         * Geoip_lib::result_city()
         * 
         * Return the city name
         * 
         * @return string
         */
        function result_city(){
            if(isset($this->_Data->city)){
                return $this->_Data->city;
            }else{
                return '';
            }
        }

        /**
         * Geoip_lib::result_country_name()
         * 
         * Return the Country Name
         * 
         * @return string
         */
        function result_country_name(){
            if(isset($this->_Data->country_name)){
                return $this->_Data->country_name;
            }else{
                return '';
            }
        }
        
        /**
         * Geoip_lib::result_country_code()
         *
         * Return the Country Code with 2 digits
         *  
         * @return string
         */
        function result_country_code(){
            if(isset($this->_Data->country_code)){
                return $this->_Data->country_code;
            }else{
                return '';
            }
        }
        
        /**
         * Geoip_lib::result_country_code3()
         * 
         * Return the Country Code with 3 digits
         * 
         * @return string
         */
        function result_country_code3(){
            if(isset($this->_Data->country_code3)){
                return $this->_Data->country_code3;
            }else{
                return '';
            }
        }
        
        /**
         * Geoip_lib::result_latitude()
         *
         * Return the Latitude
         *  
         * @return string
         */
        function result_latitude(){
            if(isset($this->_Data->latitude)){    
                return $this->_Data->latitude;
            }else{
                return '';
            }
        }
        
        /**
         * Geoip_lib::result_longitude()
         * 
         * Return the Longitude
         * 
         * @return string
         */
        function result_longitude(){
            if(isset($this->_Data->longitude)){
                return $this->_Data->longitude;
            }else{
                return '';
            }
        }        

        /**
         * Geoip_lib::result_postal_code()
         * 
         * Return the postal Code
         * 
         * @return string
         */
        function result_postal_code(){
            if(isset($this->_Data->postal_code)){
                return $this->_Data->postal_code;
            }else{
                return '';
            }
        }
        
        /**
         * Geoip_lib::result_metro_code()
         * 
         * Return the Metro Code
         * 
         * @return string
         */
        function result_metro_code(){
            if(isset($this->_Data->metro_code)){
                return $this->_Data->metro_code;
            }else{
                return '';
            }
        }
        
        /**
         * Geoip_lib::result_area_code()
         * 
         * Return the Area Code
         * 
         * @return string
         */
        function result_area_code(){
            if(isset($this->_Data->area_code)){
                return $this->_Data->area_code;
            }else{
                return '';
            }
        }           

        /**
         * Geoip_lib::result_ip()
         * 
         * Return de IP Address
         * 
         * @return string
         */
        function result_ip(){
            if(isset($this->_Ip)){
                return $this->_Ip;
            }else{
                return '';
            }
        }
        
        /**
         * Geoip_lib::result_custom()
         * 
         * Return
         * 
         * @param mixed $cadena
         * @return string
         */
        function result_custom($custom){
            
            $custom = str_ireplace("%IP", $this->result_ip() , $custom);
            $custom = str_ireplace("%CO", $this->result_country_code() , $custom);
            $custom = str_ireplace("%C3", $this->result_country_code3() , $custom);
            $custom = str_ireplace("%CN", $this->result_country_name() , $custom);
            $custom = str_ireplace("%RE", $this->result_region() , $custom);
            $custom = str_ireplace("%RN", $this->result_region_name() , $custom);
            $custom = str_ireplace("%CT", $this->result_city() , $custom);
            $custom = str_ireplace("%LA", $this->result_latitude() , $custom);
            $custom = str_ireplace("%LO", $this->result_longitude() , $custom);
            $custom = str_ireplace("%PC", $this->result_postal_code() , $custom);
            $custom = str_ireplace("%MC", $this->result_metro_code(), $custom);
            $custom = str_ireplace("%AC", $this->result_area_code() , $custom);
                                 
            return $custom;
        }
               

 
    }


    
?>