<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');


class Ffmpeg
{

/**
 * key generated by uniqid() command
 * @var string 
 */
public $key;

/**
 * File format to which file will be converted
 * @var string
 */
public $format;

/**
 * file name which will be converted
 * file is located in /files/uploaded folder
 * @var string 
 */
public $input_file;

/**
 * Filename without extension
 * @var string 
 */
public $file_body;

public $cut;

public $resize;

public $ffmpeg_files_dir;

public function __construct()
{
    //$this->key = $_REQUEST['key']; //default file key
    $this->ci =& get_instance();
    $this->ci->load->config('ffmpeg');
    $this->ci->lang->load('ffmpeg');
    //$this->ci->load->model('ffmpeg');

    $this->messages = array();
    $this->errors   = array();
    $this->cut      = '';

    $this->ffmpeg_path       = $this->ci->config->item('ffmpeg_path');
    $this->ffmpeg_files_dir  = $this->ci->config->item('ffmpeg_files_dir');
    $this->ffmpeg_prefix     = $this->ci->config->item('ffmpeg_prefix');
    $this->ffmpeg_suffix     = $this->ci->config->item('ffmpeg_suffix');
    $this->ffmpeg_before_dir = $this->ci->config->item('ffmpeg_before_dir');
    $this->ffmpeg_after_dir  = $this->ci->config->item('ffmpeg_after_dir');
    $this->ffmpeg_key_dir    = $this->ci->config->item('ffmpeg_key_dir');
    $this->ffmpeg_formats    = $this->ci->config->item('ffmpeg_formats');
    $this->ffmpeg_extensions = $this->ci->config->item('ffmpeg_extensions');

}

/**
 * set unique key
 * $key_x must be generated by uniqid() function
 * @assert ("sfdfg456tdfg") == true
 * @assert ("sEWdfF456tdf0") == true
 * @assert ("$%FDfd") == false
 * @param string $key_x 
 */
function setKey($key_x)
{
    
   if(!ctype_alnum ($key_x)) {
       log_message('error', 'security warning: illegal key');
       die("illegal key $key_x");
   }
   $this->key = $key_x;

}

/**
 * sets name of file which will be converted
 * @param string $input_x
 * @param string $type 
 */
function setInputFile($input_x, $type="js")
{
    $this->input_file = $input_x;
    $this->file_body  = current(explode(".", strtolower($this->input_file)));
}

/**
 * 
 * @param string $format_x
 */
function setFormat($format_x)
{
    
    $format_x = rawurldecode($format_x);
    $formats = array_keys($this->ffmpeg_formats);
    if (!in_array($format_x, $formats)) {
        log_message('error', 'security warning: illegal format');
        if(is_file($this->ffmpeg_before_dir.$this->input_file))
            unlink($this->ffmpeg_before_dir.$this->input_file);
        die("illegal format");
    }
    $this->format = $format_x;
    
}

/**
 * If quality is high or normal, it replaces values
 * in $this->ffmpeg_formats array
 * @param string $quality - normal, high or low
 */
public function setQuality($quality = "normal") {
    
    if($quality == "high" || $quality == "low") {
        
        $aReplaces = $this->ci->config->item('ffmpeg_'.$quality);
        //print_r($this->ffmpeg_formats);
        if(is_array($aReplaces) && !empty($aReplaces))
            if(isset($aReplaces[$this->format]))
                foreach($aReplaces[$this->format] as $from => $to)
                    $this->ffmpeg_formats[$this->format] = str_replace($from, $to, $this->ffmpeg_formats[$this->format]);
    }
    
}

//get so far encoded time
public function getEncodedTime(){
    
    $oVideo = $this->ci->ffmpeg_model->get_video($this->key);
    $sFfmpegLogFile = "ffmpeg-$oVideo->ffmpeg_log_date.log";
    $sFile = $this->ffmpeg_key_dir.$sFfmpegLogFile;
    if(is_file($sFile)) {
        
        $FFMPEGLog = file_get_contents($sFile);
        $times     = explode('time=', $FFMPEGLog);
        $ctime     = count($times)-1;
        $timed     = explode(' bitrate=', $times[$ctime]);
        //print_r($timed);
        $nEncTime  = $timed[0];
        list($h, $m, $s) = explode(":", $nEncTime);
        $s = ceil($s); // 21.40 seconds => 22 seconds
        $nEncTime = $this->hms2sec($h, $m, $s);
        
    } else {
        
        log_message('error', 'ffmpeg file '.$sFile.' not found for finding encoded time');
        $nEncTime = 0;
        
    }
    
    return $nEncTime;
    
}


public function hms2sec ($h, $m, $s) {
    
    //list($h, $m, $s) = explode (":", $hms);
    $seconds = 0;
    $seconds += (intval((string)$h) * 3600);
    $seconds += (intval((string)$m) * 60);
    $seconds += (intval((string)$s));
    return $seconds;
    
}


function cut ($h, $m, $s, $_h, $_m, $_s) {
    
    if(func_num_args() == 6) {
        $start_time = $this->hms2sec($h, $m, $s);
        $end_time   = $this->hms2sec($_h, $_m, $_s) - $start_time;
        $this->cut = " -ss $start_time -t $end_time ";
    } else {
        $this->cut = '';
    }
    
}

function resize ($width, $height) {
    
    if(func_num_args() == 2) {
        if(!is_numeric($width)
                || !is_numeric($height)
                || $width > 2000
                || $width < 1
                || $height > 2000
                || $height < 1) {
            log_message('error', 'security warning: illegal video width and/or heigth');
            die("illegal width and/or heigth");
        } else {
        $this->resize = " -s ".$width."x".$height." ";
        }
    } else {
        $this->resize = '';
    }
    
}



//Get ffmpeg options from selected format
 function getFfmpegOptions()
{
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
 function getExtension()
{
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
    if(!$this->format) {
        log_message('error', "failed to this->ffmpeg->getExtension because \$this->format is empty");
    }
    log_message('debug', "extension: ".$this->ffmpeg_extensions[$this->format]);
    return $this->ffmpeg_extensions[$this->format];
}

//get total length of file
public function getTotalTime()
{
    $play_time_sec = 0;
    
    $oVideo = $this->ci->ffmpeg_model->get_video($this->key);
    //echo "key: $this->key ";
    //print_r($oVideo);
    $sFfmpegLogFile = "ffmpeg-$oVideo->ffmpeg_log_date.log";
    $sFile = $this->ffmpeg_key_dir.$sFfmpegLogFile;
    //echo $sFile;
    if(is_file($sFile)) {
        //echo "testa";
        $lines = file($sFile);
        //print_r($lines);
        foreach ($lines as $line_num => $line) {
                //echo "Line #<b>{$line_num}</b> : " . htmlspecialchars($line) . "<br />\n";
                if(strpos($line, 'Duration') !== false) {
                        $line = explode("Duration: ", $line);
                        $line = explode(",", $line[1]);
                        $line = explode(":", $line[0]);
                        
                        //print_r($line);
                        
                        $play_time_sec = 0;
                        $play_time_sec += intval((string)$line[0]) * 60 * 60; // hour
                        $play_time_sec += intval((string)$line[1]) * 60; // minute
                        $play_time_sec += intval((string)round($line[2])); // second
                        break;
                }
        }
    } else {
        //echo "testb";
        log_message('error', 'ffmpeg file '.$sFile.' not found for finding total time');
    }
    //echo "DDD $play_time_sec  DDD";
    return $play_time_sec;
}


//get percents completed:
public function getPercentsComplete()
{
    
    $oVideo = $this->ci->ffmpeg_model->get_video($this->key);
    $sFfmpegLogFile = "ffmpeg-$oVideo->ffmpeg_log_date.log";
    $sFile = $this->ffmpeg_key_dir.$sFfmpegLogFile;
    
    if(is_file($sFile)) {
        
        $sFileContents = file_get_contents($sFile);
        if(stripos($sFileContents, 'No more inputs to read from, finishing') !== FALSE)
            return 100;
        
    }
    
    
    $nTotalTime = $this->getTotalTime();
    $nEncodedTime = $this->getEncodedTime();
    
    if($nEncodedTime <= 0)
        return 0;
    
    if($nEncodedTime >= $nTotalTime)
        return 100;
    
    $nPercentsComplete = round(($nEncodedTime/$nTotalTime)*100);
    log_message('debug', 'ffmpeg converter total time: '.$nTotalTime.', encoded time: '.$nEncodedTime.', complete percents round(($nEncodedTime/$nTotalTime)*100): '.$nPercentsComplete);
    return $nPercentsComplete;

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


public function startConvert($mode="js")
{
    
    /*$ffmpeg_command = "$this->ffmpeg_prefix $this->ffmpeg_path -i \"".
        urldecode($this->ffmpeg_before_dir.$this->input_file)."\" ".
        $this->getFfmpegOptions()." -report \"".
        urldecode($this->ffmpeg_after_dir.$this->file_body."-$this->key.".
        $this->getExtension())."\" 2> {$this->ffmpeg_key_dir}{$this->key}.ffmpeg";*/
    $sOriginalPath = getcwd();
    
    
    $sConvertTime = date("Ymd-His");
    $sExt = $this->getExtension();
    $sFfmpegOptions = $this->getFfmpegOptions();
    $ffmpeg_command = "$this->ffmpeg_prefix $this->ffmpeg_path -i {$this->ffmpeg_before_dir}{$this->key} $sFfmpegOptions -report {$this->ffmpeg_after_dir}{$this->key}.$sExt";
    
    $aParams = array(
        'uniqid' => $this->key,
        'ffmpeg_log_date' => $sConvertTime,
        'ffmpeg_command' => $ffmpeg_command);
    $this->ci->ffmpeg_model->set_video($aParams);
    
    chdir($this->ffmpeg_key_dir);
    $proc = popen($ffmpeg_command, "r");
    pclose($proc);
    chdir($sOriginalPath);
    //$gif_optimize = "gifsicle --batch --optimize ".urldecode($this->ffmpeg_after_dir).urldecode($this->file_body)."-".$this->key.".".$this->getExtension();
    //if($this->getExtension() == "gif") {
    //    $proc = popen($gif_optimize, "r");
    //    pclose($proc);
    //}

    if($mode=="js")
        return true;
    else {

        echo"<html>
            <head>
                <title>{$_SERVER["SERVER_NAME"]}</title>
            </head><body>";

        echo lang('mobile.download').": <br/>\n<a href=\"http://".
        $_SERVER["SERVER_NAME"]."/files/converted/$this->key.$sExt\">
        $this->key.$sExt</a><br/>";

        echo"<a href=\"http://{$_SERVER["SERVER_NAME"]}\">&lt;&lt; {$_SERVER["SERVER_NAME"]}</a>
            </body></html>";

    }

}

}