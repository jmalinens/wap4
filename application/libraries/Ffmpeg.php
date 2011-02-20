<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');


class Ffmpeg
{

public $key;
public $format;
public $input_file;
public $file_body;
public $cut;
public $resize;

	public function __construct()
	{
		//$this->key = $_REQUEST['key']; //default file key
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
   
        
        function SetKey($key_x)
        {
           if(!ctype_alnum ($key_x))
           {
               log_message('error', 'security warning: illegal key');
               die("illegal key $key_x");
           }
           $this->key = $key_x;
               
        }
        
        
        function SetInput_file($input_x, $type="js")
        {
               $this->input_file = $input_x;
               $this->file_body  = current(explode(".", strtolower($this->input_file)));
        }
        
        function SetFormat($format_x)
        {
           $formats = array_keys($this->ffmpeg_formats);
           if (!in_array($format_x, $formats)) {
                log_message('error', 'security warning: illegal format');
                unlink($this->ffmpeg_before_dir."".$this->input_file);
                die("illegal format");
            }
           $this->format = $format_x;
        }
	
	//get so far encoded time
	public function GetEncodedTime(){
		$FFMPEGLog = file_get_contents($this->ffmpeg_key_dir.$this->key.'.ffmpeg');
		$times     = explode('time=', $FFMPEGLog);
		$ctime     = count($times)-1;
		$timed     = explode(' bitrate=', $times[$ctime]);
		$tt        = $timed[0];
		return $tt;
	}

        
        public function hms2sec ($h, $m, $s) {
                //list($h, $m, $s) = explode (":", $hms);
                $seconds = 0;
                $seconds += (intval((string)$h) * 3600);
                $seconds += (intval((string)$m) * 60);
                $seconds += (intval((string)$s));
                return $seconds;
        }
        
        
        function Cut ($h, $m, $s, $_h, $_m, $_s) {
                if(func_num_args() == 6)
                {
                    $start_time = $this->hms2sec($h, $m, $s);
                    $end_time   = $this->hms2sec($_h, $_m, $_s) - $start_time;
                    $this->cut = " -ss $start_time -t $end_time ";
                }
                    else
                {
                    $this->cut = '';
                }
        }
        
        function Resize ($width, $heigth) {
                if(func_num_args() == 2)
                {
                    if(!is_numeric($width) || !is_numeric($heigth) || $width > 2000 || $width < 1 || $heigth > 2000 || $heigth < 1)
                    {
                        log_message('error', 'security warning: illegal video width and/or heigth');
                        die("illegal width and/or heigth");
                    }
                    else
                    {
                    $this->resize = " -s ".$width."x".$heigth." ";
                    }
                }
                    else
                {
                    $this->resize = '';
                }
        }
        
        
        
	//Get ffmpeg options from selected format
	 function GetFfmpegOptions(){
                /*
		switch($this->format) {
		case 'mp3':
		return ' -ab 128000 -ar 44100 ';
		case 'amr':
		return ' -acodec libopencore_amrnb -ac 1 -ar 8000 -ab 12.2k ';
		default:
		return ' -ab 128000 -ar 44100 ';
		}
                */
                log_message('error', "format: ".$this->ffmpeg_formats["$this->format"]);
                return $this->cut.''.$this->ffmpeg_formats["$this->format"];

                
	}
        
	//Get fextension from selected format
	 function GetExtension(){
                /*
		switch($this->format) {
		case 'mp3-128kbps':
		return 'mp3';
                case 'amr-mono,12.2kbps':
		return 'amr';
                case '3gp-176x144,AMR':
		return '3gp';
		default:
		return "mp4";
		}
                */
                log_message('error', "extension: ".$this->ffmpeg_extensions[$this->format]);
                return $this->ffmpeg_extensions[$this->format];
	}

	//get total length of file
	public function GetTotalTime(){
		$lines = file($this->ffmpeg_key_dir.$this->key.'.ffmpeg');
		foreach ($lines as $line_num => $line) {
			//echo "Line #<b>{$line_num}</b> : " . htmlspecialchars($line) . "<br />\n";
			if(strpos($line, 'Duration') !== false)
			{
				$line = explode("Duration: ", $line);
				$line = explode(",", $line[1]);
				$line = explode(":", $line[0]);
				
				$play_time_sec = 0;
				$play_time_sec += intval((string)$line[0]) * 60 * 60; // hour
				$play_time_sec += intval((string)$line[1]) * 60; // minute
				$play_time_sec += intval((string)round($line[2])); // second
				break;
			}
		}
		return $play_time_sec;
	}
	
	
	//get percents completed:
	public function GetPercentsComplete(){
		return round($this->GetEncodedTime()/$this->GetTotalTime()*100);
	}

	//convert seconds into hours:minutes:seconds format
	public function sec2hms($sekunden)
	{
		$stunden  = floor($sekunden / 3600);
		$minuten  = floor(($sekunden - ($stunden * 3600)) / 60);
		$sekunden = round($sekunden - ($stunden * 3600) - ($minuten * 60), 0);

		if ($stunden <= 9) {
			$strStunden = "0" . $stunden;
		} else {
			$strStunden = $stunden;
		}

		if ($minuten <= 9) {
			$strMinuten = "0" . $minuten;
		} else {
			$strMinuten = $minuten;
		}

		if ($sekunden <= 9) {
			$strSekunden = "0" . $sekunden;
		} else {
			$strSekunden = $sekunden;
		}

		return "$strStunden:$strMinuten:$strSekunden";
	}

	
	public function StartConvert($mode="js")
	{

            if($mode=="js")
            {
            pclose(popen("".$this->ffmpeg_prefix." ".$this->ffmpeg_path." -i ".$this->ffmpeg_before_dir."".$this->input_file." ".$this->GetFfmpegOptions()." ".$this->ffmpeg_after_dir."".$this->file_body."-".$this->key.".".$this->GetExtension()." 2> ".$this->ffmpeg_key_dir."".$this->key.".ffmpeg", "r"));
            @file_put_contents('/home/wap4/public_html/files/test.txt', "".$this->ffmpeg_prefix." ".$this->ffmpeg_path." -i ".$this->ffmpeg_before_dir."".$this->input_file." ".$this->GetFfmpegOptions()." ".$this->ffmpeg_after_dir."".$this->file_body."-".$this->key.".".$this->GetExtension()." 2> ".$this->ffmpeg_key_dir."".$this->key.".ffmpeg");       
            }
             else
            {
            pclose(popen("".$this->ffmpeg_prefix." ".$this->ffmpeg_path." -i ".$this->ffmpeg_before_dir."".$this->input_file." ".$this->GetFfmpegOptions()." ".$this->ffmpeg_after_dir."file-".$this->file_body.".".$this->GetExtension()." 2> ".$this->ffmpeg_key_dir."".$this->key.".ffmpeg", "r"));
            echo"<html><title>wap4.org</title>";
            echo "<a href=\"http://wap4.org/files/converted/file-{$this->file_body}.{$this->GetExtension()}\">file-{$this->file_body}.{$this->GetExtension()}</a><br/>";
            echo"<a href=\"http://wap4.org\">&lt;&lt; wap4.org</a></html>";
            @file_put_contents('/home/wap4/public_html/files/test.txt', "".$this->ffmpeg_prefix." ".$this->ffmpeg_path." -i ".$this->ffmpeg_before_dir."".$this->input_file." ".$this->GetFfmpegOptions()." ".$this->ffmpeg_after_dir."".$this->file_body."-".$this->key.".".$this->GetExtension()." 2> ".$this->ffmpeg_key_dir."".$this->key.".ffmpeg");       
            }
            return true;
	}


}