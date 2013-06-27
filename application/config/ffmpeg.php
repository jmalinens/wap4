<?php
/**
 * Juris Malinens, 2010-2012
 */

$config['ffmpeg_allowed']    = array('mp3','avi','flv','mov','mp4','3gp','amr','ogg','flac','aac','wmv','mpg','mpeg', 'ac3', 'mkv', 'webm');
$config['ffmpeg_max']        = 300*1024; //in kilobytes

$config['ffmpeg_max_processes'] = 2;

$config['ffmpeg_extensions'] = array(
'mp3' => 'mp3',
'amr' => 'amr',
'3gp-176x144-amr' => '3gp',
'3gp-352x288-amr' => '3gp',
'mp4-176x144-aac' => 'mp4',
'mp4-320x240-aac' => 'mp4',
//'mp4-320x240-aac-96k' => 'mp4',
'mp4-400x240-aac' => 'mp4',
'mp4-640x360-aac' => 'mp4',
'avi-320x240'     => 'avi',
'ipod-320x240-4-3' => 'mp4',
'ipod-320x240-16-9' => 'mp4',
'ipod-nano-176x128' => 'mp4',
'avi-320x240' => 'avi',
'psp' => 'mp4',
//'gif' => "gif",
'aac' => 'aac',
'ogg-audio-video' => 'ogg',
);

$config['ffmpeg_formats']    = array(
    
'mp3'               => ' -ab 128k -ar 44100 -y -threads 1 ',
'amr'               => ' -acodec libopencore_amrnb -ac 1 -ar 8000 -ab 7.95k -y -threads 1 ',
'3gp-176x144-amr'   => ' -f 3gp -s 176x144 -vcodec h263 -b 118k -r 15 -acodec libopencore_amrnb -ac 1 -ar 8000 -ab 12.2k -y -threads 1 ',
//'3gp-320x240-aac'   => ' -f 3gp -s qvga -vcodec libx264 -preset veryfast -b 300k -r 15 -acodec libfaac -ac 2 -ar 24000 -ab 64k -y -threads 1 ',
'3gp-352x288-amr'   => ' -f 3gp -s 352x288 -vcodec h263 -b 350k -r 15 -acodec libopencore_amrnb -ac 1 -ar 8000 -ab 7.95k -y -threads 1 ',
'mp4-176x144-aac'   => ' -f mp4 -s 176x144 -vcodec mpeg4 -b 128k -r 15 -acodec aac -strict experimental -ac 1 -ar 22050 -ab 32k -y -threads 1 ',
'mp4-320x240-aac'   => ' -f mp4 -s qvga -vcodec mpeg4 -b 256k -r 15 -acodec aac -strict experimental -ac 2 -ar 22050 -ab 64k -y -threads 1 ',
'mp4-400x240-aac'   => ' -s 400x240 -vcodec mpeg4 -b 450k -acodec aac -strict experimental -r 14 -ac 1 -ar 16000 -ab 32k -aspect 16:9 -y -threads 1 ',
'mp4-640x360-aac'   => ' -f mp4 -s 640x360 -vcodec libx264 -preset veryfast -b 750k -r 15 -acodec aac -strict experimental -ac 2 -ar 22050 -ab 64k -y -threads 1 ',
'avi-320x240'       => ' -f avi -s 320x240 -vcodec mpeg4 -b 500k -r 15 -acodec libmp3lame -ac 2 -ar 44100 -ab 64k -y -threads 1 ',
'ipod-320x240-4-3'  => ' -acodec libmp3lame -ab 128kb -ar 44100 -vcodec mpeg2video -s 320x240 -b 400kb -strict -1 -y -threads 1 ',
'ipod-320x240-16-9' => ' -acodec libmp3lame -ab 128kb -ar 44100 -vcodec mpeg2video -s 320x176 -b 400kb -strict -1 -y -threads 1 ',
'ipod-nano-176x128' => ' -acodec libmp3lame -ab 128kb -ar 44100 -vcodec mpeg2video -s 176x128 -b 200kb -strict -1 -y -threads 1 ',
'psp'               => ' -b 300 -s 320x240 -vcodec mpeg4 -ab 64kb -ar 44100 -acodec aac -strict experimental -y -threads 1 ',
//'gif'               => ' -pix_fmt rgb24 -y -threads 1 ',
'aac'               => ' -acodec aac -strict experimental -ab 64k -ar 44100 -y -threads 1 ',
'ogg-audio-video'   => ' -acodec libvorbis -ac 2 -ab 64k -ar 44100 -y -threads 1 ',
    
);

$config['ffmpeg_low'] = array(
'mp3'               => array("128" => "64"),
'amr'               => array("7.95" => "5.9"),
'3gp-176x144-amr'   => array("118" => "78", "15" => "12", "12.2" => "7.95"),
'3gp-352x288-amr'   => array("350" => "280", "7.95" => "6.70"),
'mp4-176x144-aac'   => array("64" => "48", "256" => "128"),
'mp4-320x240-aac'   => array("32" => "24", "128" => "100"),
'mp4-400x240-aac'   => array("450" => "350", "32" => "24"),
'mp4-640x360-aac'   => array("750" => "500", "64" => "48"),
'avi-320x240'       => array("500" => "350", "64" => "32"),
'ipod-320x240-4-3'  => array("400" => "300", "128" => "64"),
'ipod-320x240-16-9' => array("400" => "300", "128" => "64"),
'ipod-nano-176x128' => array("200" => "160", "128" => "64"),
'psp'               => array("300" => "256", "64" => "32"),
//'gif'               => array(),
'aac'               => array("128" => "48"),
'ogg-audio-video'   => array("128" => "48"),
);

$config['ffmpeg_high'] = array(
'mp3'               => array("128" => "192"),
'amr'               => array("7.95" => "12.2"),
'3gp-176x144-amr'   => array("15" => "25", "118" => "138"),
'3gp-352x288-amr'   => array("15" => "25", "7.95" => "12.2"),
'mp4-176x144-aac'   => array("15" => "25", "48" => "64", "128" => "150"),
'mp4-320x240-aac'   => array("15" => "25", "64" => "128", "256" => "320"),
'mp4-400x240-aac'   => array("450" => "580", "32" => "64"),
'mp4-640x360-aac'   => array("15" => "25", "64" => "128", "750" => "1000", "24000" => "44100"),
'avi-320x240'       => array("500" => "650", "64" => "128", "15" => "25"),
'ipod-320x240-4-3'  => array("400" => "500", "128" => "192"),
'ipod-320x240-16-9' => array("400" => "500", "128" => "192"),
'ipod-nano-176x128' => array("200" => "256", "128" => "192"),
'psp'               => array("300" => "400", "64" => "128"),
//'gif'               => array(),
'aac'               => array("128" => "192"),
'ogg-audio-video'   => array("128" => "192"),
);


if(strtolower(substr(PHP_OS, 0, 3)) == "win") {
    //for windows
    $config['public_html'] = "F:/xampp/htdocs/wap4.org/";
    $config['ffmpeg_path'] = "C:/ffmpeg.exe"; //ffmpeg linux path
    $config['ffmpeg_prefix'] = "START \"converting in background...\" /B "; //to run process in background
    $config['ffmpeg_suffix'] = "";
    $config['ffmpeg_prefix']     = "";
    
} else {
    //for linux
    $config['public_html'] = "/home/wap4/public_html/";
    $config['ffmpeg_path'] = " nice -n 12 /usr/local/bin/ffmpeg";
    $config['ffmpeg_suffix'] = " &"; //to run process in background
    
}

$config['ffmpeg_files_dir']  = $config['public_html']."files/";
$config['ffmpeg_before_dir'] = $config['public_html']."files/uploaded/";
$config['ffmpeg_after_dir']  = $config['public_html']."files/converted/";
$config['ffmpeg_key_dir']    = $config['public_html']."files/keys/"; //directory for key files
		
	
