<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
ignore_user_abort(true);
set_time_limit(0);
setlocale(LC_ALL, 'en_US.UTF8');
class Converter extends CI_Controller {
    
    /**
     * Array of errors
     * @var array
     */
    var $aError;

    function __construct() 
    {
        parent::__construct();
        $this->load->helper('wap4');
        load_settings();
        
        $this->aError = array();

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
   
   /**
    * Destructor to log errors
    */
   function __destruct() {
       if(!empty($this->aError)) {
           file_put_contents($this->config->item("ffmpeg_files_dir")."error_log",
                             implode(" ** ", $this->aError)." ** ".date("Y-m-d H:i:s")."\n", FILE_APPEND);
       }
   }
    
    
    /**
     * Shows converter interface
     */
    function index()
    {

        if($this->uri->segment(4) && ctype_alnum($this->uri->segment(4)))
        {
            $uniqid             = $this->uri->segment(4);
        } else {
            $uniqid             = uniqid();
        }
        
	$this->data['message'] = '';
    	$this->data['users']   = '';
        $this->data['attr']    = array('id' => 'conv');
	$this->data['formats'] = $this->ffmpeg->ffmpeg_formats;
        $this->data['uniqid']  = $uniqid;
        $this->data['extensions'] = $this->config->item('ffmpeg_extensions');
        
	$this->datb['allowed'] = "'".implode("','", $this->config->item('ffmpeg_allowed'))."'";
        $this->datb['max']     = $this->max_kb;
        $this->datb['uniqid']  = $uniqid;
        

        $xmlUrl = "/home/wap4/public_html/files/presets.xml"; // XML feed file/URL
        $xmlStr = file_get_contents($xmlUrl);

        $xmlObj = simplexml_load_string($xmlStr);
        $arrXml = objectsIntoArray($xmlObj);

        array_sort($arrXml, 'category', SORT_DESC);

        $this->data['presets']    = $arrXml;
        
        
        $this->load->view('includes/header', $this->data);
        
        if($_SERVER["SERVER_NAME"] == "m.wap4.org" || $_SERVER["SERVER_NAME"] == "testm.wap4.org")
        $this->load->view('converter_no_js', $this->data);
        else
        $this->load->view('converter', $this->data);
        
        $this->load->view('includes/footer', $this->data);
    	
    }
   
    
    /**
     * Uploads video from Youtube
     */
    function upload_youtube()
    {
        $uniqid = isset($_POST["key"]) ? $_POST["key"] : $this->uri->segment(4);
        
        $link = $this->normalize_link("youtube");
        if($link === false) {
            echo lang('upload.fail');
        }
        
        $title = $this->get_youtube_title($link, true);
        if($title === false) {
            echo lang('upload.fail');
        }
        
        file_put_contents("/home/wap4/public_html/files/keys/$uniqid.name", $title);
        
        /**
         * Get direct link to Youtube .flv file
         */
        $_flvUrl = $this->get_youtube_video($link);
        if($_flvUrl  === false) {
            echo lang('upload.fail');
        }

        /**
         * Get youtube video length in bytes by HEAD request (found in stackoverflow)
         */
        $contentLength = $this->get_content_length($_flvUrl);
        if($contentLength  === false) {
            echo lang('upload.fail');
        }

        file_put_contents("/home/wap4/public_html/files/keys/".$uniqid.".youtube", $contentLength);

         /**
         * download youtube video and save
         */
        if(!$this->download_link($_flvUrl,
                "/home/wap4/public_html/files/uploaded/".$title.".flv")) {
            $this->aError[] = "Failed to download and save link: $_flvUrl";
        }


        if(is_file("/home/wap4/public_html/files/uploaded/".$title.".flv"))
            echo lang('upload.done');
        else {
            $this->aError[] = "File /home/wap4/public_html/files/uploaded/".$title.".flv does not exist";
            echo lang('upload.fail');
        }


    }
    
    /**
     * Get Youtube title from Youtube feed for ajax or mobile
     * @global string $title
     * @param string $link
     * @param boolean $return
     * @return boolean false if failure, string if title found 
     */
    function get_youtube_title($link = false, $return = false) {
        
        global $title;
        
        if(isset($_POST["link"]))
            $link   = $_POST["link"];
        
        if(!$link) {
            $this->aError[] = "Youtube link not found";
        }
        
        $query = parse_url($link,PHP_URL_QUERY);
        parse_str($query);
        
        if(!isset($v) || empty($v)) {
            $this->aError[] = "Can not parse \$v: $v from \$link: $link in get_youtube_title() function";
            if($return) return false;
        }
        
        $url = "http://gdata.youtube.com/feeds/api/videos/". $v;
        $doc = new DOMDocument;
        $doc->load($url);
        $title = $doc->getElementsByTagName("title")->item(0)->nodeValue;
        
        $title = translit(sanitize_name($title));
        
        if($title == "youtube-videos") {
            $this->aError[] = "File title `youtube-videos` not allowed";
            if($return) return false;
        }
        
        if(!$return)
            echo $title;
        else
            return $title;
    }
    
    /**
     * How much percents uploaded already for Youtube videos
     * @uses ajax
     */
    function youtube_upload_status($key, $title, $return = false)
    {
        $size_remote = file_get_contents("/home/wap4/public_html/files/keys/".$key.".youtube");
        $size_local  = filesize("/home/wap4/public_html/files/uploaded/".$title.".flv");
        
        if(!$return)
            echo round(($size_local/$size_remote)*100);
        else
            return round(($size_local/$size_remote)*100);

    }
    
    /**
     * Allows changing max upload sizes for administrator level users
     */
    function change_settings() {
        $this->load->library('form_validation');
        /**
         * validate form input
         */
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

        if ($this->form_validation->run() == true) {
        	
        	if ($this->site_model->update_sizes($this->input->post('unregistered'), $this->input->post('registered'))) {
                        /**
                         * if the change is successful
                         * redirect back to the home page
                         */
	        	$this->session->set_flashdata('message', $this->ion_auth->messages());
	        	redirect($this->config->item('base_url'), 'refresh');
	        } else {
                    /**
                     * if the setting change was un-successful
                     */
                    $this->session->set_flashdata('message', "bad values");
                    $this->data['message'] = "bad values";
                    $this->load->view('change_settings', $this->data);

	        }
        } else {
            /**
             * set the flash data error message if there is one
             */
            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
            
            $this->load->view('change_settings', $this->data);
            }
    }
    
    /**
     * Outputs progress of conversion for mobile devices
     * @param string $key - uniqid key
     * @param type $type 
     */
    function mobile_status($key, $type) {

        header("Cache-Control: no-cache, must-revalidate");
        header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
        header("Content-type: text/html");
        $title = file_get_contents("/home/wap4/public_html/files/keys/$key.name");
        
        $Youtube_percents_complete = $this->youtube_upload_status($key, $title, true);
        $Convert_percents_complete = $this->statuss($key, true);
        echo "<html>
        <head>
        <meta http-equiv=\"expires\" content=\"0\"/>
        <meta http-equiv=\"pragma\" content=\"no-cache\"/>
        <meta http-equiv=\"cache-control\" content=\"no-cache, must-revalidate\"/>
        <title>m.wap4.org</title></head>";
        echo lang('mobile.upl_perc').": $Youtube_percents_complete %<br/>\n";
        echo lang('mobile.conv_perc').": $Convert_percents_complete %<br/><br/>\n";
        
        if($Youtube_percents_complete == 100 && $Convert_percents_complete == 100) {
            $extension = "mp3";
            $file = file("/home/wap4/public_html/files/keys/$key.ffmpeg");
            foreach($file as $f) {
                if(substr($f, 0, 6) == "Output") {
                    $extension = substr(end(explode(".", $f)), 0, -3);
                    break;
                }
            }
            echo lang('mobile.download').": <br/>\n
                <a href=\"http://".$_SERVER["SERVER_NAME"]."/files/converted/file-".$title.".".$extension."\">http://".$_SERVER["SERVER_NAME"]."/files/converted/file-".$title.".".$extension."</a><br/><br/>\n";
        } else
            echo anchor('converter/mobile_status/'.$key, lang('mobile.reload'))."<br/><br/>\n";
        
        echo "<a href=\"http://".$_SERVER["SERVER_NAME"]."\">m.wap4.org</a>\n";
        
        echo "</html>";
    }
    
    /**
     * convert function which handles logic of converting videos
     * @global string $title - sanitized title of Youtube video
     */
    function convert() {
        
        /**
         * Youtube converter for mobile devices
         */
        if( isMobile() &&
            !isset($_POST["from"]) &&
            isset($_POST["youtube"]) &&
            !empty($_POST["youtube"])) {

            $encoded = '';
            foreach($_GET as $name => $value) {
              $encoded .= urlencode($name).'='.urlencode($value).'&';
            }
            foreach($_POST as $name => $value) {
              $encoded .= urlencode($name).'='.urlencode($value).'&';
            }
            // chop off last ampersand
            $encoded = substr($encoded, 0, strlen($encoded)-1);

            $proc_command = "wget --post-data '$encoded&from=wget' http://".$_SERVER["SERVER_NAME"]."/".$this->lang->lang()."/converter/convert/no_js -q -b >/dev/null 2>&1";

            $proc = popen($proc_command, "r");
            pclose($proc);

            redirect('converter/mobile_status/'.$_POST["key"], 'location');
            exit;

        }
        
        if($this->uri->segment(4) == "no_js")
        {
            if(isset($_FILES['qqfile']['tmp_name']) && !empty($_FILES['qqfile']['tmp_name'])) {
                if(intval($this->data['max']*1024) > intval($_FILES['qqfile']['size']))
                {
                    if(!move_uploaded_file($_FILES['qqfile']['tmp_name'],
                    $this->config->item('ffmpeg_before_dir').$_POST["key"].".".end(explode(".",$_FILES['qqfile']['name'])))) {
                        $this->aError[] = "move_uploaded_file error, when trying
                            to upload file in no_js";
                        die("fatal error, when trying to upload file");
                    }

                    $this->ffmpeg->SetKey($_POST["key"]);
                    //set format of  converted file
                    $this->ffmpeg->SetFormat(rawurldecode($_POST["format"]));
                    //set name of file which will be converted
                    $this->ffmpeg->SetInput_file($_POST["key"].".".end(explode(".",$_FILES['qqfile']['name'])), "no_js");

                    if(isset($_REQUEST['cut']) && $_REQUEST['cut'] == 'yes') 
                    $this->ffmpeg->Cut( $_REQUEST['s_hh'],
                                        $_REQUEST['s_mm'],
                                        $_REQUEST['s_ss'],
                                        $_REQUEST['e_hh'],
                                        $_REQUEST['e_mm'],
                                        $_REQUEST['e_ss']);

                    //if(isset($_REQUEST['resize']) && $_REQUEST['resize'] == 'yes') 
                    //$this->ffmpeg->Resize($_REQUEST['width'],$_REQUEST['heigth']);

                    $veids="no_js";
                } else {
                    $this->aError[] = "too big file {$_FILES['qqfile']['size']},
                    max filesize {$this->data['max']} MB";
                    die("too big file, max filesize {$this->data['max']} MB");
                }
            }

            if(isset($_POST["youtube"]) && !empty($_POST["youtube"])) {
                $this->upload_youtube();
                global $title;
                $this->ffmpeg->SetKey($_POST["key"]);
                //set format of  converted file
                $this->ffmpeg->SetFormat(rawurldecode($_POST["format"]));
                //set name of file which will be converted
                $this->ffmpeg->SetInput_file($title.".flv", "no_js");

                if(isset($_REQUEST['cut']) && $_REQUEST['cut'] == 'yes') 
                $this->ffmpeg->Cut( $_REQUEST['s_hh'],
                                    $_REQUEST['s_mm'],
                                    $_REQUEST['s_ss'],
                                    $_REQUEST['e_hh'],
                                    $_REQUEST['e_mm'],
                                    $_REQUEST['e_ss']);

                $veids="no_js";
            }
            
        } else {
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
    
    /**
     * Returns percents complete of conversion
     * @param string $unikaalais - uniqid string
     * @param boolean $return - true- returns result, false- outputs result
     * @return type 
     */
    function statuss($unikaalais, $return = false) {
        $this->ffmpeg->SetKey($unikaalais);
        if(!$return)
            echo $this->ffmpeg->GetPercentsComplete();
        else
            return $this->ffmpeg->GetPercentsComplete();
    }

    /**
     * removes everything after last dot symbol if dot is in string
     * and outputs result
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
    
    /**
     * Downloads and saves external file
     * @param string $link address of file
     * @param string $location location where to save
     * @return boolean true if success, false if failure
     */
    function download_link($link, $location) {
        /*
        $bgas = end(explode(".", $link));
       if(!in_array($bgas, $this->config->item("ffmpeg_allowed"))) {
           $this->aError[] = "Link $link has unsupported file extension";
       }
        $url   = trim(stripslashes($link));
        $video = file_get_contents($url);
        if(!$video) {
           $this->aError[] = "Can not download external file: $link";
       }
        if(!file_put_contents($location, $video)) {
            $this->aError[] = "Can put file content from external file: $link to location: $location";
        }
        */ 
        $file = fopen($location, 'w');
        if($file === false) {
            $this->aError[] = "Can not open file: $location for writing";
            return false;
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_FILE, $file);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_URL, $link);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        @curl_setopt($ch, CURLOPT_COOKIEFILE, COOKIE);
        @curl_setopt($ch, CURLOPT_COOKIEJAR, COOKIE);
        if(!curl_exec($ch)) {
            $this->aError[] = "Failed to download file: $link";
            return false;
        }
        curl_close($ch);
        fclose($file);

        return true;
    }
    
    /**
     * To run web pages in background with long loading time
     * @param string $link 
     */
    function ping_link($link) {
            $encoded = '';
            foreach($_GET as $name => $value) {
              $encoded .= urlencode($name).'='.urlencode($value).'&';
            }
            foreach($_POST as $name => $value) {
              $encoded .= urlencode($name).'='.urlencode($value).'&';
            }
            // chop off last ampersand
            $encoded = substr($encoded, 0, strlen($encoded)-1);

            $proc_command = "wget --post-data '$encoded&from=wget' http://".$_SERVER["SERVER_NAME"]."/".$this->lang->lang()."/converter/convert/no_js -q -b -O /dev/null -o /home/wap4/public_html/files/ping.status >/dev/null 2>&1";
            $proc = popen($proc_command, "r");
            pclose($proc);

            redirect('converter/mobile_status/'.$_POST["key"], 'location');
            exit;
    }
    
    /**
     * Get direct link to .flv file of Vimeo video
     * @param string $link
     * @return boolean on failure, string on success 
     */
    function get_vimeo_video($link) {
        
        if(substr($link, 0, 7) != "http://")
        $link = "http://".$link;
        
        
        if(is_numeric($link)) {
            $id = $link;
        } else {
            $pos = stripos($link, "/");
            if($pos !== false) {
                $id = end(explode($link));
                if(!is_numeric($link)) {
                    $this->aError[] = "Failed to parse Vimeo link";
                    return false;
                }
            }
        }
        
        $xmlstr = file_get_contents("http://www.vimeo.com/moogaloop/load/clip:".$id);
        
        $xml = new SimpleXMLElement($xmlstr);
        
        $req_sign = $xml->xml->request_signature;
        $req_sign_exp = $xml->xml->request_signature_expires;
        if(!empty($req_sign) && !empty($req_sign_exp)) {
            return "http://www.vimeo.com/moogaloop/play/clip:$id/$req_sign/$req_sign_exp/?q=sd";
        } else {
            $this->aError[] = "`$req_sign` or `$req_sign_exp` is empty";
                    return false;
        }
    }
    
    /**
     * Get direct link to .flv file of Youtube video
     * @param string  $link
     * @return string on success, boolean false on failure
     */
    function get_youtube_video($link) {
        
        if(substr($link, 0, 7) != "http://")
        $link = "http://".$link;
                
        $query = parse_url($link,PHP_URL_QUERY);
        
        parse_str($query);
        
        if(!isset($v) || empty($v)) {
            $this->aError[] = "Can not parse \$v: $v from \$link: $link in upload_youtube() function";
            return false;
        }
        
        $link = "http://www.youtube.com/watch?v=".$v;
        
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
            } else {
                $this->aError[] = "Can not get fmt_url_map";
                return false;
            }
            
            if(empty($vidUrl)) {
                $this->aError[] = "empty vidUrl";
                return false;
            } else
                return  $vidUrl;
            
        } else {
            $this->aError[] = "Can not get contents from `$link`";
            return false;
        }

    }
    
    /**
     * Reads headers of web page to get content size
     * @param string $link
     * @return integer on success, boolean false on failure 
     */
    function get_content_length($link) {
        
            $ch = curl_init($link);
            curl_setopt($ch, CURLOPT_NOBODY, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            $data = curl_exec($ch);
            curl_close($ch);

            //if (preg_match('/^HTTP\/1\.[01] (\d\d\d)/', $data, $matches)) {
            //  $status = (int)$matches[1];
            //}
            if (preg_match('/Content-Length: (\d+)/', $data, $matches)) {
              return (int)$matches[1];
            } else {
                $this->aError[] = "Unknown content length for url: $link";
                return false;
            }

    }
    
    /**
     * Parses url and makes standard url which can  be sent later to extract
     *  location of video file
     * @param string $type - vimeo, youtube etc.
     * @return string on success, boolean false on failure 
     */
    function normalize_link($type) {
        switch($type) {
            
        default: //youtube
            
            if(isset($_POST["link"]) && !empty($_POST["link"]))
                $link = $_POST["link"];
            elseif(isset($_POST["youtube"]) && !empty($_POST["youtube"]))
                $link = $_POST["youtube"];
            else
                $link = base64_decode($this->uri->segment(5));


            if(substr($link, 0, 7) != "http://")
                    $link = "http://".$link;

            $query = parse_url($link,PHP_URL_QUERY);

            parse_str($query);

            if(!isset($v) || empty($v)) {
                $this->aError[] = "Can not parse \$v: $v from \$link: $link in upload_youtube() function";
                return false;
            }

            $link = "http://www.youtube.com/watch?v=".$v;
        
        break;
        
        }
        
        return $link;
        
    }

}
