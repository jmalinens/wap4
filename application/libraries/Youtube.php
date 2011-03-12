<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *  Youtube mp3
 *
 * @author Juris
 */

    // Conversion Class
    class YouTube
    {
        public $_songFileName = '';
        public $_flvUrl = '';
        public $_audioQualities = array(64, 128, 320);
        public $_tempVidFileName;
        public $_vidSrcTypes = array('source_code', 'url');

        // Constants
        const _TEMPVIDDIR = '/home/wap4/public_html/videos/';
        const _SONGFILEDIR = '/home/wap4/public_html/files/converted/';
        const _FFMPEG = 'ffmpeg';
        
        #region Public Methods
        function __construct()
        {
            	$this->ci =& get_instance();
		$this->ci->load->config('ffmpeg');
		$this->ci->lang->load('ffmpeg');

		$this->messages = array();
		$this->errors   = array();
		$this->cut      = '';
                
                
		$this->ffmpeg_path	 = $this->ci->config->item('ffmpeg_path');
		$this->ffmpeg_prefix     = $this->ci->config->item('ffmpeg_prefix');
		$this->ffmpeg_suffix     = $this->ci->config->item('ffmpeg_suffix');
		$this->ffmpeg_before_dir = $this->ci->config->item('ffmpeg_before_dir');
		$this->ffmpeg_after_dir  = $this->ci->config->item('ffmpeg_after_dir');
		$this->ffmpeg_key_dir    = $this->ci->config->item('ffmpeg_key_dir');
		$this->ffmpeg_formats	 = $this->ci->config->item('ffmpeg_formats');
                $this->ffmpeg_extensions = $this->ci->config->item('ffmpeg_extensions');
        }
        
        public function DownloadVideo($youTubeUrl)
        {
            $file_contents = file_get_contents($youTubeUrl);
            if ($file_contents !== false)
            {
                $this->SetSongFileName($file_contents);
                $this->SetFlvUrl($file_contents);
                if ($this->GetSongFileName() != '' && $this->GetFlvUrl() != '')
                {
                    return $this->SaveVideo($this->GetFlvUrl());
                }
            }
            return false;
        } 
        
        public function GenerateMP3($audioQuality)
        {
            $qualities = $this->GetAudioQualities();
            $quality = (in_array($audioQuality, $qualities)) ? $audioQuality : $qualities[1];            
            $exec_string = self::_FFMPEG.' -i '.$this->GetTempVidFileName().' -y -acodec libmp3lame -ab '.$quality.'k '.$this->GetSongFileName();
            exec($exec_string);
            $this->DeleteTempVid();
             return is_file($this->GetSongFileName());
        }
        
        public function ExtractSongTrackName($vidSrc, $srcType)
        {
            $name = '';
            $vidSrcTypes = $this->GetVidSrcTypes();
            if (in_array($srcType, $vidSrcTypes))
            {
                $vidSrc = ($srcType == $vidSrcTypes[1]) ? file_get_contents($vidSrc) : $vidSrc;
                if ($vidSrc !== false && eregi('eow-title',$vidSrc))
                {
                    $name = end(explode('eow-title',$vidSrc));
                    $name = current(explode('">',$name));
                    $name = ereg_replace('[^-_a-zA-Z,"\' :0-9]',"",end(explode('title="',$name)));
                }
            }
            return $name;
        }        
        #endregion

        #region Private "Helper" Methods
        public function SaveVideo($url)
        {
            $this->SetTempVidFileName(time());
            $file = fopen($this->GetTempVidFileName(), 'w');
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_FILE, $file);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_COOKIEFILE, COOKIE);
            curl_setopt($ch, CURLOPT_COOKIEJAR, COOKIE);
            curl_exec($ch);
            curl_close($ch);
            fclose($file);
            return is_file($this->GetTempVidFileName());
        }
        
        public function DeleteTempVid()
        {
            if (is_file($this->GetTempVidFileName())) 
            {
                unlink($this->GetTempVidFileName());
            }        
        }
        #endregion
        
        #region Properties
        public function GetSongFileName()
        {
            return $this->_songFileName;
        }        
        public function SetSongFileName($file_contents)
        {
            $vidSrcTypes = $this->GetVidSrcTypes();
            $trackName = $this->ExtractSongTrackName($file_contents, $vidSrcTypes[0]);
            $this->_songFileName = (!empty($trackName)) ? self::_SONGFILEDIR . preg_replace('/_{2,}/','_',preg_replace('/ /','_',preg_replace('/[^A-Za-z0-9 _-]/','',$trackName))) . '.mp3' : '';
        }

        public function GetFlvUrl()
        {
            return $this->_flvUrl;
        }            
        public function SetFlvUrl($file_contents)
        { 
            $vidUrl = '';
            if (eregi('fmt_url_map',$file_contents))
            {
                $vidUrl = end(explode('&fmt_url_map=',$file_contents));
                $vidUrl = current(explode('&',$vidUrl));
                $vidUrl = current(explode('%2C',$vidUrl));
                $vidUrl = urldecode(end(explode('%7C',$vidUrl)));
            }
            $this->_flvUrl = $vidUrl;
        }
        
        public function GetAudioQualities()
        {
            return $this->_audioQualities;
        }    
        
        public function GetTempVidFileName()
        {
            return $this->_tempVidFileName;
        }
        public function SetTempVidFileName($timestamp)
        {
            $this->_tempVidFileName = self::_TEMPVIDDIR . $timestamp .'.flv';
        }
        
        public function GetVidSrcTypes()
        {
            return $this->_vidSrcTypes;
        }
        #endregion
    }

?>
