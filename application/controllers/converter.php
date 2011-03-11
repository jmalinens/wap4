<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Converter extends CI_Controller {
 
    function __construct() 
    {
        parent::__construct();
        $this->load->helper('wap4');
        //$this->load->library('youtube');
        load_settings();


        $this->load->model('site_model');
        $this->data['status'] = $this->site_model->get_site_status();
        $this->data['status'] = $this->data['status'][0]['setting_value'];
        if($this->data['status'] == "offline") die(lang('site.offline'));
        
        
        $this->load->model('site_model');
        
        if (!$this->ion_auth->logged_in())
        $this->max_kb = $this->site_model->get_setting('file_size_unregistered')*1024;
        else
        $this->max_kb = $this->site_model->get_setting('file_size_registered')*1024;
        
        parse_str($_SERVER['QUERY_STRING'], $_GET);
    }
    //redirect if needed, otherwise display the user list
    function index() 
    {

        if($this->uri->segment(4) && ctype_alnum($this->uri->segment(4)))
        {
            $uniqid             = $this->uri->segment(4);
        }
        else {
            $uniqid             = uniqid();
        }
                
        
	$this->data['message'] = '';
    	$this->data['users']   = '';
        $this->data['attr']    = array('id' => 'conv');
	$this->data['formats'] = $this->ffmpeg->ffmpeg_formats;
        $this->data['uniqid']  = $uniqid;
                
	$this->datb['allowed'] = "'".implode("','", $this->config->item('ffmpeg_allowed'))."'";
        $this->datb['max']     = $this->max_kb;
        $this->datb['uniqid']  = $uniqid;
        

        $xmlUrl = "/home/wap4/public_html/files/presets.xml"; // XML feed file/URL
        $xmlStr = file_get_contents($xmlUrl);

        $xmlObj = simplexml_load_string($xmlStr);
        $arrXml = objectsIntoArray($xmlObj);

        array_sort($arrXml, 'category', SORT_DESC);

        $this->data['presets']    = $arrXml;
        
        
        if(!irAjax())
        $this->load->view('includes/header', $this->data);
        
    	//$this->load->view('uploader', $this->datb);
        if(irAjax())
        $this->load->view('converter', $this->data);
        else
        $this->load->view('converter_no_js', $this->data);
        
        if(!irAjax())
        $this->load->view('includes/footer', $this->data);
    	
    }
   
    
    /**
     * uploads video from Youtube
     */
    function upload_youtube()
    {
        
        $uniqid = $this->uri->segment(4);
        $link   = $_POST["link"];
        
        $query = parse_url($link,PHP_URL_QUERY);
        parse_str($query);
        
        $url = "http://gdata.youtube.com/feeds/api/videos/". $v;
        $doc = new DOMDocument;
        $doc->load($url);
        $title = $doc->getElementsByTagName("title")->item(0)->nodeValue;

        $title = sanitize_name($title);
        
        $file_contents = file_get_contents(trim($link));
        if ($file_contents !== false)
        {

            $vidUrl = '';
            if (preg_match("/fmt_url_map/i",$file_contents))
            {
                if (preg_match("/&amp;fmt_url_map/i",$file_contents))
                $vidUrl = end(explode('&amp;fmt_url_map=',$file_contents));
                
                if (preg_match("/&fmt_url_map/i",$file_contents))
                $vidUrl = end(explode('&fmt_url_map=',$file_contents));
                
                $vidUrl = current(explode('&',$vidUrl));
                $vidUrl = current(explode('%2C',$vidUrl));
                $vidUrl = urldecode(end(explode('%7C',$vidUrl)));
            }
            $_flvUrl = $vidUrl;
            

            /**
             * get youtube video length in bytes by HEAD request (found in stackoverflow)
             */
            $ch = curl_init($_flvUrl);
            curl_setopt($ch, CURLOPT_NOBODY, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); //not necessary unless the file redirects (like the PHP example we're using here)
            $data = curl_exec($ch);
            curl_close($ch);

            $contentLength = 'unknown';
            $status = 'unknown';
            if (preg_match('/^HTTP\/1\.[01] (\d\d\d)/', $data, $matches)) {
              $status = (int)$matches[1];
            }
            if (preg_match('/Content-Length: (\d+)/', $data, $matches)) {
              $contentLength = (int)$matches[1];
            }
            //echo 'Content-Length: ' . $contentLength;
            file_put_contents("/home/wap4/public_html/files/keys/".$uniqid.".youtube", $contentLength);

             /**
             * download youtube video and save
             */
            $file = fopen("/home/wap4/public_html/files/uploaded/".$title.".flv", 'w');
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_FILE, $file);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_URL, $_flvUrl);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            @curl_setopt($ch, CURLOPT_COOKIEFILE, COOKIE);
            @curl_setopt($ch, CURLOPT_COOKIEJAR, COOKIE);

            //curl_setopt($ch, CURLOPT_NOPROGRESS, false);
            //curl_setopt($ch, CURLOPT_PROGRESSFUNCTION, 'callback');
            
            curl_exec($ch);
            curl_close($ch);
            fclose($file);
            
            if(is_file("/home/wap4/public_html/files/uploaded/".$title.".flv"))
            echo lang('upload.done');
            else
            echo lang('upload.fail');

        }
        else {
            echo lang('upload.fail');
        }

    }
    
    /**
     * get Youtube title from Youtube feed for ajax
     */
    function get_youtube_title() {
        $link   = $_POST["link"];
        $query = parse_url($link,PHP_URL_QUERY);
        parse_str($query);
        //echo $v;
        $url = "http://gdata.youtube.com/feeds/api/videos/". $v;
        $doc = new DOMDocument;
        $doc->load($url);
        $title = $doc->getElementsByTagName("title")->item(0)->nodeValue;
        echo sanitize_name($title);
    }
    
    /**
     * how much percents uploaded already
     * @uses ajax
     */
    function youtube_upload_status()
    {
        $size_remote = file_get_contents("/home/wap4/public_html/files/keys/".$this->uri->segment(4).".youtube");
        $size_local  = filesize("/home/wap4/public_html/files/uploaded/".$this->uri->segment(5).".flv");
        echo round(($size_local/$size_remote)*100);

    }
    
    function change_settings() {
        $this->load->library('form_validation');
        //validate form input
    	$this->form_validation->set_rules('unregistered', 'unregistered', 'numeric|min_length[1]|max_length[3]');
		$this->form_validation->set_rules('registered', 'registered', 'numeric|min_length[1]|max_length[3]');
        
        $this->data['unregistered']   = array('name'    => 'unregistered',
                                      'id'      => 'unregistered',
                                      'type'    => 'text',
                                      'value'   => $this->form_validation->set_value('unregistered'),
                                     );
        $this->data['registered']   = array('name'    => 'registered',
                                      'id'      => 'registered',
                                      'type'    => 'text',
                                      'value'   => $this->form_validation->set_value('registered'),
                                     );

        if ($this->form_validation->run() == true) { //check to see if changing settings

        	
        	if ($this->site_model->update_sizes($this->input->post('unregistered'), $this->input->post('registered'))) { //if the change is successful
	        	//redirect them back to the home page
	        	$this->session->set_flashdata('message', $this->ion_auth->messages());
	        	redirect($this->config->item('base_url'), 'refresh');
	        }
	        else { //if the setting change was un-successful
                    
	        	$this->session->set_flashdata('message', "bad vaues");
                      
                        
                        $this->data['message'] = "bad values";
                        
                        $this->load->view('change_settings', $this->data);

	        }
        }
		else {  //the user is not logging in so display the login page
	        //set the flash data error message if there is one
	        $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
		    

    		$this->load->view('change_settings', $this->data);
		}
    }
	
	
    function convert() {
        
        if($this->uri->segment(4) == "no_js")
        {
            if(intval($this->data['max']*1024) > intval($_FILES['qqfile']['size']))
            {
                if(!move_uploaded_file($_FILES['qqfile']['tmp_name'],
                $this->config->item('ffmpeg_before_dir').$_POST["key"].".".end(explode(".",$_FILES['qqfile']['name']))))
                die("fatal error, when trying to upload file");
                
                $this->ffmpeg->SetKey($_POST["key"]);
                //set format of  converted file
                $this->ffmpeg->SetFormat(rawurldecode($_POST["format"]));
                //set name of file which will be converted
                $this->ffmpeg->SetInput_file($_POST["key"].".".end(explode(".",$_FILES['qqfile']['name'])), "no_js");
                
                if(isset($_REQUEST['cut']) && $_REQUEST['cut'] == 'yes') 
                $this->ffmpeg->Cut($_REQUEST['s_hh'],$_REQUEST['s_mm'],$_REQUEST['s_ss'],$_REQUEST['e_hh'],$_REQUEST['e_mm'],$_REQUEST['e_ss']);
                
                //if(isset($_REQUEST['resize']) && $_REQUEST['resize'] == 'yes') 
                //$this->ffmpeg->Resize($_REQUEST['width'],$_REQUEST['heigth']);

                $veids="no_js";
            }
            else
            {
                die("too big file, max filesize {$this->data['max']} MB");
            }
            
        
        }
        else
        {
	//print_r($_REQUEST);
        //set unique key
        $this->ffmpeg->SetKey($this->uri->segment(4));
        //set format of  converted file
        $this->ffmpeg->SetFormat(rawurldecode($this->uri->segment(5)));
        //set name of file which will be converted
        $this->ffmpeg->SetInput_file($this->uri->segment(6));

	if($this->uri->segment(7) == 'yes') 
        $this->ffmpeg->Cut($this->uri->segment(8),$this->uri->segment(9),$this->uri->segment(10),$this->uri->segment(11),$this->uri->segment(12),$this->uri->segment(13));
        
        //if($this->uri->segment(15) == 'yes') 
        //$this->ffmpeg->Resize($this->uri->segment(16),$this->uri->segment(17));
        
        $veids="js";
        
        }
        
        //start converting
        $this->ffmpeg->StartConvert($veids);
        
        //add info in DB
        if ($this->ion_auth->logged_in()) {
            $this->load->model('ffmpeg_model');
            $user = $this->ion_auth->get_user();

            if($this->uri->segment(4) != "no_js")
            {
            $extension = $this->data['formats'][$this->uri->segment(5)][1];
            //$this->uri->segment(5) == "iphone"? $extension = "mp4": $extension = $this->uri->segment(5);
            $file_name = substr(current(explode(".", strtolower($this->uri->segment(6)))), 0, -4)."-".$this->uri->segment(4).".".$extension;
            $apraksts  = $this->uri->segment(14);
            }
             else {
            $extension = $this->data['formats'][$_POST["format"]][1];
            //$_POST["format"] == "iphone"? $extension = "mp4": $extension = $_POST["format"];
            $file_name = "file-".$_POST["key"].".".$extension;
            $apraksts  = $_POST["apraksts"];
            }
            
            $this->ffmpeg_model->add_video_to_db($user->id, $file_name, $apraksts);
        }
        
        echo current(explode(".", strtolower($this->uri->segment(6))));
    }
    
    function statuss($unikaalais) {
        $this->ffmpeg->SetKey($unikaalais);
        echo $this->ffmpeg->GetPercentsComplete();
    }

    /**
     * removes everything after last dot symbol if dot is in string
     */
    function change_body() {
	//$gabals = substr($this->uri->segment(4), 0, -4);
        
        $pos2 = stripos($this->uri->segment(4), ".");
        if ($pos2 !== false) {
            echo current(explode(".", strtolower($this->uri->segment(4))));
        } else {
            echo strtolower($this->uri->segment(4));
        }

    }
    
 
    function upload()
    {
        //include_once "/home/juris/Dropbox/xampp/htdocs/ffmpeg/val/server/php.php";
        /*
        //$this->load->library("uploader");
        // list of valid extensions, ex. array("jpg", "png", "jpeg", "xml", "bmp")
        $allowedExtensions = array();
        // max file size in bytes
        $sizeLimit = 150 * 1024 * 1024;
        include"../libraries/Uploader.php";
        $uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
        $result = $uploader->handleUpload($this->config->item('ffmpeg_before_dir'));
        // to pass data through iframe you will need to encode all html tags
        echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
        //file_put_contents('/home/juris/Dropbox/xampp/htdocs/files/test.txt', $this->config->item('ffmpeg_before_dir').htmlspecialchars(json_encode($result), ENT_NOQUOTES));
            
            $config['upload_path']   = $this->config->item('ffmpeg_before_dir');
            $allowed                 = implode("|", $this->config->item('ffmpeg_allowed'));
            $config['allowed_types'] = $allowed;
            $config['max_size']      = $this->max_kb;
            //print_r($config);
            //echo $allowed;
            
            
            $this->load->library('upload', $config);
            if ( ! $this->upload->do_upload())
            {
                    $error = array('error' => $this->upload->display_errors());

                    //$this->load->view('uploader', $error);
                    //file_put_contents('/home/juris/Dropbox/xampp/htdocs/files/test.txt', implode(",", $error));
                    //echo'{success:false}';
                    
            }	
            else
            {
                    $data = array('upload_data' => $this->upload->data());
                    //echo'{success:true}';        
                    //$this->load->view('converter', $data);
            }
*/
    }


}
