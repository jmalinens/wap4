<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
 * Codeigniter library for youtube-dl.py Python script
 * 
 * example usage:
 * $this->load->library('downloader');
 * $this->downloader->sLink = 'http://www.youtube.com/watch?v=gA9lPZSkuDE';
 * $this->downloader->sUniqueId = 'bdfgtu452sbEr';
 * $bIsDownloaded = $this->downloader->download_file();
 * if($bIsDownloaded === TRUE) {
 *      //download complete
 * }
 * 
 * $nPercentsComplete = $this->downloader->get_info("percents");
 * $nDownloadSpeed = $this->downloader->get_info("speed");
 * $nFileSize = $this->downloader->get_info("filesize");
 * $nTimeLeft = $this->downloader->get_info("time_left");
 * $sTitle = $this->downloader->get_title();
 * 
 * @author Juris Malinens<juris.malinens@inbox.lv>
 * @version 0.1  19.08.2011
 */

class Downloader
{
    
    /**
     * CodeIgniter global
     * @var string
     **/
    protected $ci;
    
    /**
     * Path to Python 2.5
     * @var string 
     */
    private $sPythonExecutable;
    
    /**
     * Youtube downloader python script
     * @var string 
     */
    private $sDownloaderScript;
    
    /**
     * Start of cmd: python + script
     * @var string 
     */
    private $sCmdStart;
    
    /**
     * Full path to download progress output file
     * @var type 
     */
    private $sDownloadOutputFile;
    
    /**
     * Extension for output file of download
     * @var string 
     */
    private $sOutputExt;
    
    /**
     * Link to Youtube, Vimeo, metacafe.com, Google Video, Photobucket videos,
     * Yahoo! video, Dailymotion, DepositFiles videos
     * @var string 
     */
    public $sLink;
    
    /**
     * Unique id
     * @var string 
     */
    public $sUniqueId;
    
    public $sUploadPath;
    /**
     * File name without extension
     * @var string
     */
    public $sFileBody;

    public $aYoutubeFormats = array(
        37 => array(
                'container' => 'mp4',
                'resolution' => '1080x1920'
            ),
        22 => array(
                'container' => 'mp4',
                'resolution' => '720x1280'
            ),
        45 => array(
                'container' => 'webm',
                'resolution' => '720x1280'
            ),
        35 => array(
                'container' => 'flv',
                'resolution' => '480x854'
            ),
        44 => array(
                'container' => 'webm',
                'resolution' => '480x854'
            ),
        34 => array(
                'container' => 'flv',
                'resolution' => '360x640'
            ),
        18 => array(
                'container' => 'mp4',
                'resolution' => '360x640'
            ),
        43 => array(
                'container' => 'webm',
                'resolution' => '360x640'
            ),
        5 => array(
                'container' => 'flv',
                'resolution' => '240x400'
            )
        );

    public function __construct()
    {
        
        //load codeigniter instance
        $this->ci =& get_instance();
        
        $this->ci->load->config('downloader', TRUE);
        
        //create beginning of cmd code
        $config = $this->ci->config->item("downloader");
        $this->sPythonExecutable = $config["downloader_python"];
        $this->sDownloaderScript = $config["downloader_script"];
        
        //create full path of file where progress of download is saved
        $this->sOutputExt = "youtube_dl";
        
    }
    
    /**
     * helper method to create variables for command line
     */
    private function _set_cmd_vars() {
        
        $this->sCmdStart = $this->sPythonExecutable.' '.$this->sDownloaderScript;
        
        $this->sDownloadOutputFile = $this->ci->config->item("ffmpeg_key_dir").
                $this->sUniqueId.".".$this->sOutputExt;
        
    }
    
    /**
     * Downloads media file
     * @param bool $bInBackground run is background or run till uploaded for mobile
     * @param int $nYoutubeFormat = 5 (240x400)
     * @return bool true if successful, otherwise- false
     */
    function download_file($bRunInBackground = TRUE, $nYoutubeFormat = 5)
    {
        
        $this->_set_cmd_vars();
        
        $nYoutubeFormat = (int)$nYoutubeFormat;
        
        //set uniqid for file body if file body not set
        
        //run proccess in background or not
        $sCmdEnd = " &";
        if($bRunInBackground === FALSE)
            $sCmdEnd = "";
        
        $sFormatCmd = "";
        if(stripos($this->sLink, 'youtube') !== FALSE)
            $sFormatCmd = " --format $nYoutubeFormat ";

        $sCmd = $this->sCmdStart.' '.' '.$sFormatCmd.' --console-title -o "'.$this->sUploadPath.
                $this->sUniqueId.'" "'.$this->sLink.'" > '.$this->sDownloadOutputFile.$sCmdEnd;
        //echo $sCmd;
        log_message('debug', $sCmd);
        //exec($sCmd, $aOutput, $nReturnCode);
        
        return $nReturnCode == 0 ? TRUE : FALSE;
    }
    
    /**
     * Checks if link downloads has already started
     * @param string $sUniqueId
     * @return bool
     */
    function is_download_started($sUniqueId = FALSE) {
        
        $this->_set_cmd_vars();
        
        if($sUniqueId ===FALSE)
            $sUniqueId = $this->sUniqueId;
        
        if(is_file($this->sDownloadOutputFile))
            return TRUE;
        else
            return FALSE;
        
    }
    
    /**
     * Checks if python script recognizes URL and can get video
     * @param string $sLink
     * @return bool
     */
    public function is_link_recognized($sLink = FALSE) {
        
        if($sLink === FALSE)
            $sLink = $this->sLink;
        
        $this->_set_cmd_vars();
        
        //$nContentLength = $this->get_web_content_length($sLink);
        //if($nContentLength === FALSE) {
        //    $sErrMsg = 'Video size of '.$sLink.' is empty';
        //    log_message('error', $sErrMsg);
        //    return FALSE;
        //}
        
        //if($nContentLength > $this->ci->data['max']*1024) {
        //    $sErrMsg = 'Video size of '.$nContentLength.' too big, max is: '.($this->ci->data['max']*1024);
        //    log_message('error', $sErrMsg);
        //    $aParams = array('uniqid' => $this->sUniqueId,
        //        'is_failed' => 1,
        //        'fail_log' => $sErrMsg);
        //    $this->ci->ffmpeg_model->set_video($aParams);
        //    return FALSE;
        //}
            
        
        $sCmd = $this->sCmdStart.' '.' --get-url "'.$sLink.'"';
        //exec($sCmd, $aOutput, $nReturnCode);
        if($nReturnCode == 0) {
            
            $nPos = stripos($aOutput[0], "Invalid URL");
            if($nPos !== FALSE)
                return FALSE;
            else {
                return TRUE;
            }
            
        } else
            return FALSE;
        
    }
    
    /**
     * Reads headers of web page to get content size
     * @param string $link
     * @return integer on success, boolean false on failure 
     */
    function get_web_content_length($link) {
        
        $ch = curl_init($link);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $data = curl_exec($ch);
        curl_close($ch);

        if (preg_match('/Content-Length: (\d+)/', $data, $matches)) {
          return (int)$matches[1];
        } else {
            $sErrMsg = "Unknown content length in downloader for url: $link";
            log_message('error', $sErrMsg);
            return FALSE;
        }

    }
    
    
    function get_available_formats()
    {
        $this->_set_cmd_vars();
        
        $sCmd = $this->sCmdStart.' '.' --list-formats "'.$this->sLink.'"';
        exec($sCmd, $aOutput, $nReturnCode);
        
        $bProcess = FALSE;
        
        $aFormats = array();
        
        foreach($aOutput as $nId => $sLine) {
            
            if($bProcess) {
                
                preg_match('/(?P<video_id>\d+)\s*:\s*(?P<container>\w+)\s*\[(?P<resolution>\w+)\]/',
                        $sLine,
                        $matches);
                
                
                $aFormats[$matches['video_id']] = array(
                    'container' => $matches['container'],
                    'resolution' => $matches['resolution']
                    );
                
                
            }
            
            
            if(stripos($sLine, 'Available formats') !== FALSE)
                    $bProcess = TRUE;
            
        }
        
        print_r($aFormats);
        return $aFormats;
        
    }
    
    /**
     * Returns multimedia file title (not SEO friendly!)
     * @return string
     */
    function get_title()
    {
        $this->_set_cmd_vars();
        
        $sCmd = $this->sCmdStart.' '.' --get-title "'.$this->sLink.'"';
        exec($sCmd, $aOutput, $nReturnCode);
        
        if($nReturnCode == 0)
            return $aOutput[0];
        else
            return FALSE;
        
    }
    
    /**
     * Returns all liness from output file
     * @return array $aFileLines
     */
    private function get_script_output()
    {
        if(!$this->sDownloadOutputFile) {
            log_message('debug', 'variable $this->sDownloadOutputFile not declared, running $this->_set_cmd_vars();');
            $this->_set_cmd_vars();
        }
        
        if(!is_file($this->sDownloadOutputFile)) {
                
            log_message('error', 'file for script output '.$this->sDownloadOutputFile.' not found');
            return FALSE;
                
        }
        
        $aFileLines = file($this->sDownloadOutputFile);
        
        return $aFileLines;
    }
    
    /**
     * Gets file extension
     * @return string
     */
    public function get_extension() {
        
        //[download] Destination: bdfgtu452sbEr.flv
        $aLines = $this->get_script_output();
        if(!empty($aLines)) {
            foreach($aLines as $nLineId => $sLine) {
                //file_put_contents("/home/wap4/public_html/files/g", $sLine.PHP_EOL, FILE_APPEND);
                $nPos = strpos($sLine, "Destination");
                if($nPos === FALSE)
                    continue;
                //file_put_contents("/home/wap4/public_html/files/g", "line we needed".$sLine.PHP_EOL, FILE_APPEND);
                $sExtension = trim(end(explode(".", $sLine)));
                log_message('debug', 'upload extension: '.$sExtension);
                return strtolower($sExtension);
            }
        }
        //this should not happen
        log_message('error', 'python script line with text "Destination" not found in downloader->get_extension()');
        return;
        
    }
    
    /**
     * Gets file extension
     * @return string
     */
    public function get_error() {
        
        //[download] Destination: bdfgtu452sbEr.flv
        $aLines = $this->get_script_output();
        foreach($aLines as $nLineId => $sLine) {
            //file_put_contents("/home/wap4/public_html/files/g", $sLine.PHP_EOL, FILE_APPEND);
            $nPos = strpos($sLine, "ERROR");
            if($nPos === FALSE)
                continue;

            $sError = str_replace('ERROR: ', '', $sLine);
            
            return $sError;
        }
        //this should not happen
        log_message('error', 'python script line with text "Destination" not found');
        return FALSE;
        
    }
    
    /**
     * Returns array of useful line's unparsed values for get_info() function
     * @return array 
     */
    private function get_useful_array_from_output()
    {
        
        $aLines = $this->get_script_output();
        if(!empty($aLines)) {
            foreach($aLines as $nLineId => $sLine) {

                $nPos = strpos($sLine, " ETA ");
                if($nPos === FALSE)
                    continue;

                //removes spaces
                $sLine = preg_replace("'\s+'", ' ', $sLine);
                $aParts = explode(" ", $sLine);
                
                file_put_contents($this->ci->config->item('public_html').'files/uploaded/filesizes.txt', implode(" | ", $aParts)."\r\n", FILE_APPEND);
                //error_log(serialize($aParts));
                return $aParts;

            }
        } else {
            log_message('error', 'no lines from this->get_script_output()');
        }
        //this should not happen
        return;
        
    }
    
    /**
     * Get useful info from output
     * @param string $sInfoType percents, filesize, speed, time_left, percents
     * @return int 
     */
    function get_info($sInfoType = 'percents') {
        
        /*
Array
(
    [0] => 
    [1] => [download]
    [2] => 0.0%
    [3] => of
    [4] => 12.52M
    [5] => at
    [6] => 9.35k/s
    [7] => ETA
    [8] => 22:51
    [9] => [download]
    [10] => 0.0%
    [11] => of
    [12] => 12.52M
    [13] => at
    [14] => 27.78k/s
    [15] => ETA
    [16] => 07:41
    [17] => [download]
    [18] => 0.1%
    [19] => of
    [20] => 12.52M
    [21] => at
    [22] => 64.81k/s
    [23] => ETA
    [24] => 03:17
    [25] => [download]
    [26] => 0.1%
    [27] => of
    [28] => 12.52M
    [29] => at
    [30] => 138.89k/s
    [31] => ETA
    [32] => 01:32
    [33] => [download]
    [34] => 0.2%
    [35] => of
    [36] => 12.52M
    [37] => at
    [38] => 142.86k/s
    [39] => ETA
    [40] => 01:29
    [41] => [download]
    [42] => 0.5%
    [43] => of
    [44] => 12.52M
    [45] => at
    [46] => 189.76k/s
    [47] => ETA
    [48] => 01:07
    [49] => [download]
    [50] => 1.0%
    [51] => of
    [52] => 12.52M
    [53] => at
    [54] => 282.85k/s
    [55] => ETA
    [56] => 00:44
    [57] => [download]
    [58] => 2.0%
    [59] => of
    [60] => 12.52M
    [61] => at
    [62] => 380.60k/s
    [63] => ETA
    [64] => 00:33
    [65] => [download]
    [66] => 4.0%
    [67] => of
    [68] => 12.52M
    [69] => at
    [70] => 570.95k/s
    [71] => ETA
    [72] => 00:21
    [73] => [download]
    [74] => 8.0%
    [75] => of
    [76] => 12.52M
    [77] => at
    [78] => 919.14k/s
    [79] => ETA
    [80] => 00:12
    [81] => [download]
    [82] => 16.0%
    [83] => of
    [84] => 12.52M
    [85] => at
    [86] => 1.08M/s
    [87] => ETA
    [88] => 00:09
    [89] => [download]
    [90] => 26.8%
    [91] => of
    [92] => 12.52M
    [93] => at
    [94] => 838.05k/s
    [95] => ETA
    [96] => 00:11
    [97] => [download]
    [98] => 32.2%
    [99] => of
    [100] => 12.52M
    [101] => at
    [102] => 763.41k/s
    [103] => ETA
    [104] => 00:11
    [105] => [download]
    [106] => 36.3%
    [107] => of
    [108] => 12.52M
    [109] => at
    [110] => 733.46k/s
    [111] => ETA
    [112] => 00:11
    [113] => [download]
    [114] => 40.7%
    [115] => of
    [116] => 12.52M
    [117] => at
    [118] => 691.01k/s
    [119] => ETA
    [120] => 00:11
    [121] => [download]
    [122] => 44.3%
    [123] => of
    [124] => 12.52M
    [125] => at
    [126] => 645.52k/s
    [127] => ETA
    [128] => 00:11
    [129] => [download]
    [130] => 47.2%
    [131] => of
    [132] => 12.52M
    [133] => at
    [134] => 594.37k/s
    [135] => ETA
    [136] => 00:11
    [137] => [download]
    [138] => 49.3%
    [139] => of
    [140] => 12.52M
    [141] => at
    [142] => 556.40k/s
    [143] => ETA
    [144] => 00:11
    [145] => [download]
    [146] => 51.1%
    [147] => of
    [148] => 12.52M
    [149] => at
    [150] => 537.67k/s
    [151] => ETA
    [152] => 00:11
    [153] => [download]
    [154] => 53.3%
    [155] => of
    [156] => 12.52M
    [157] => at
    [158] => 518.43k/s
    [159] => ETA
    [160] => 00:11
    [161] => [download]
    [162] => 55.5%
    [163] => of
    [164] => 12.52M
    [165] => at
    [166] => 502.56k/s
    [167] => ETA
    [168] => 00:11
    [169] => [download]
    [170] => 57.7%
    [171] => of
    [172] => 12.52M
    [173] => at
    [174] => 491.78k/s
    [175] => ETA
    [176] => 00:11
    [177] => [download]
    [178] => 60.2%
    [179] => of
    [180] => 12.52M
    [181] => at
    [182] => 464.20k/s
    [183] => ETA
    [184] => 00:10
    [185] => [download]
    [186] => 61.8%
    [187] => of
    [188] => 12.52M
    [189] => at
    [190] => 449.38k/s
    [191] => ETA
    [192] => 00:10
    [193] => [download]
    [194] => 63.4%
    [195] => of
    [196] => 12.52M
    [197] => at
    [198] => 436.05k/s
    [199] => ETA
    [200] => 00:10
    [201] => [download]
    [202] => 65.0%
    [203] => of
    [204] => 12.52M
    [205] => at
    [206] => 423.55k/s
    [207] => ETA
    [208] => 00:10
    [209] => [download]
    [210] => 66.5%
    [211] => of
    [212] => 12.52M
    [213] => at
    [214] => 406.20k/s
    [215] => ETA
    [216] => 00:10
    [217] => [download]
    [218] => 67.7%
    [219] => of
    [220] => 12.52M
    [221] => at
    [222] => 400.50k/s
    [223] => ETA
    [224] => 00:10
    [225] => [download]
    [226] => 69.4%
    [227] => of
    [228] => 12.52M
    [229] => at
    [230] => 392.58k/s
    [231] => ETA
    [232] => 00:09
    [233] => [download]
    [234] => 71.1%
    [235] => of
    [236] => 12.52M
    [237] => at
    [238] => 379.76k/s
    [239] => ETA
    [240] => 00:09
    [241] => [download]
    [242] => 72.4%
    [243] => of
    [244] => 12.52M
    [245] => at
    [246] => 376.23k/s
    [247] => ETA
    [248] => 00:09
    [249] => [download]
    [250] => 74.4%
    [251] => of
    [252] => 12.52M
    [253] => at
    [254] => 354.10k/s
    [255] => ETA
    [256] => 00:09
    [257] => [download]
    [258] => 75.3%
    [259] => of
    [260] => 12.52M
    [261] => at
    [262] => 350.31k/s
    [263] => ETA
    [264] => 00:09
    [265] => [download]
    [266] => 76.8%
    [267] => of
    [268] => 12.52M
    [269] => at
    [270] => 344.17k/s
    [271] => ETA
    [272] => 00:08
    [273] => [download]
    [274] => 78.3%
    [275] => of
    [276] => 12.52M
    [277] => at
    [278] => 337.70k/s
    [279] => ETA
    [280] => 00:08
    [281] => [download]
    [282] => 79.6%
    [283] => of
    [284] => 12.52M
    [285] => at
    [286] => 332.97k/s
    [287] => ETA
    [288] => 00:07
    [289] => [download]
    [290] => 81.0%
    [291] => of
    [292] => 12.52M
    [293] => at
    [294] => 326.83k/s
    [295] => ETA
    [296] => 00:07
    [297] => [download]
    [298] => 82.2%
    [299] => of
    [300] => 12.52M
    [301] => at
    [302] => 323.15k/s
    [303] => ETA
    [304] => 00:07
    [305] => [download]
    [306] => 83.7%
    [307] => of
    [308] => 12.52M
    [309] => at
    [310] => 319.97k/s
    [311] => ETA
    [312] => 00:06
    [313] => [download]
    [314] => 85.3%
    [315] => of
    [316] => 12.52M
    [317] => at
    [318] => 315.38k/s
    [319] => ETA
    [320] => 00:05
    [321] => [download]
    [322] => 86.7%
    [323] => of
    [324] => 12.52M
    [325] => at
    [326] => 313.55k/s
    [327] => ETA
    [328] => 00:05
    [329] => [download]
    [330] => 88.5%
    [331] => of
    [332] => 12.52M
    [333] => at
    [334] => 310.21k/s
    [335] => ETA
    [336] => 00:04
    [337] => [download]
    [338] => 90.1%
    [339] => of
    [340] => 12.52M
    [341] => at
    [342] => 307.38k/s
    [343] => ETA
    [344] => 00:04
    [345] => [download]
    [346] => 91.7%
    [347] => of
    [348] => 12.52M
    [349] => at
    [350] => 305.75k/s
    [351] => ETA
    [352] => 00:03
)
         */
        
        
        $aInfo = $this->get_useful_array_from_output();
        //remove first empty element if it exists
        if(!empty($aInfo) && empty($aInfo[0]))
            array_shift($aInfo);
        
        switch($sInfoType) {
            
            case 'filesize':
                
                return $this->parse_filesize($aInfo[3]);
                
                break;
            
            case 'speed':
                
                return $this->parse_speed($aInfo[5]);
                
                break;
            
            case 'time_left':
                
                return $this->parse_time_left($aInfo[7]);
                
                break;
            
            default: //percents
                
                if(!$aInfo)
                    return 0;
                
                $aInfoRev = array_reverse($aInfo);
                
                return $this->parse_percents($aInfoRev[6]);
            
        }
        
    }
    
    /**
     * Parse file size to byte format
     * @param string $sSize
     * @return file size in bytes 
     */
    private function parse_filesize($sSize)
    {
        //one of these: b k M G T P E Z Y
        $sUnitType = substr($sSize, -1);
        $nFilesize = substr($sSize, 0, -1);
        
        if($sUnitType == "b")
            return round($nFilesize); // Bytes
        elseif($sUnitType == "k")
            return round($nFilesize*1024); // Kbytes
        elseif($sUnitType == "M")
            return round($nFilesize*1024*1024); // Mbytes
        else
            return round($nFilesize*1024*1024*1024); // Gbytes
        
    }
    
    /**
     * Parse percents downloaded to integer value
     * @param string $sPercents
     * @return int percents downloaded 
     */
    private function parse_percents($sPercents)
    {
        
        $nWithoutPercentSign = substr($sPercents, 0, -1);
        $nWithoutPercentSign = round($nWithoutPercentSign);
        log_message('debug', 'upload percents complete: '.$nWithoutPercentSign);
        
        return round($nWithoutPercentSign);
        
    }
    
    /**
     * Converts time left from mm:ss format to seconds 
     * @param string $sTimeLeft
     * @return int time in seconds
     */
    private function parse_time_left($sTimeLeft)
    {
        
        $aTimeParts = explode(":", $sTimeLeft);
        
        $nTimeInSeconds = (int)(($aTimeParts[0]*60)+$aTimeParts[1]);
        
        return $nTimeInSeconds;
    }
    
    /**
     * Returns current file download speed in bytes per second
     * @param string $sSpeed
     * @return int 
     */
    private function parse_speed($sSpeed) {
        
        //one of these: b k M G T P E Z Y- third character from the end
        $sDataRateType = substr($sSpeed, -3, 1);
        
        //remove M/s part at the end
        $nSpeed = (double)substr($sSpeed, 0, -3);
        
        if($sDataRateType == "b")
            return round($nSpeed); // Bps
        elseif($sDataRateType == "k")
            return round($nSpeed*1024); // Kbps
        elseif($sDataRateType == "M")
            return round($nSpeed*1024*1024); // Mbps
        else
            return round($nSpeed*1024*1024*1024); // Gbps
        
    }
    
    
}
