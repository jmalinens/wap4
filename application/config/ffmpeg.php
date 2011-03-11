<?php
	/**
	 * Juris Malinens, 2010
	 */
	 

		//$config['ffmpeg_path']		   = "C:/ffmpeg.exe"; //ffmpeg windows path
		//$config['ffmpeg_prefix']	   = "cd C:/ & START \"converting in background...\" /B "; //to run process in background
		//$config['ffmpeg_suffix']	   = "";
		//$config['ffmpeg_before_dir']   = "C:/"; //directory where we store temp videos which we are going to convert
		//$config['ffmpeg_after_dir']    = "C:/"; //directory for converted videos
		//$config['ffmpeg_key_dir']      = "C:/"; //directory for key files
$config['ffmpeg_allowed']    = array('mp3','avi','flv','mov','mp4','3gp','ogg','flac','aac','wmv','mpg', 'ac3');
$config['ffmpeg_max']        = 150*1024; //in kilobytes
        
$config['ffmpeg_formats']    = array(
'mp3-128kbps'               => ' -ab 128000 -ar 44100 ',
'amr-mono-12kbps'           => ' -acodec libopencore_amrnb -ac 1 -ar 8000 -ab 12.2k ',
'3gp-176x144-amr'           => ' -f 3gp -s 176x144 -vcodec h263 -b 80k -r 15 -acodec libopencore_amrnb -ac 1 -ar 8000 -ab 12.2k -y ',
'mp4-320x240-aac'           => ' -f mp4 -s qvga -vcodec mpeg4 -b 256k -r 15 -acodec libfaac -ac 2 -ar 24000 -ab 48k -y ',
'ipod-320x240-4-3'          => ' -acodec libmp3lame -ab 128kb -ar 44100 -vcodec mpeg2video -s 320x240 -b 400kb -strict -1 -y ',
'ipod-320x240-16-9'         => ' -acodec libmp3lame -ab 128kb -ar 44100 -vcodec mpeg2video -s 320x176 -b 400kb -strict -1 -y ',
'ipod-nano-176x128'          => ' -acodec libmp3lame -ab 128kb -ar 44100 -vcodec mpeg2video -s 176x128 -b 256kb -strict -1 -y '
);

$config['ffmpeg_extensions'] = array(
'mp3-128kbps'               => "mp3",
'amr-mono-12kbps'           => "amr",
'3gp-176x144-amr'           => "3gp",
'mp4-320x240-aac'           => "mp4",
'ipod-320x240-4-3'          => "mp4",
'ipod-320x240-16-9'         => "mp4",
'ipod-nano-176x128'          => "mp4",
);

        
       /*
       $config['ffmpeg_formats']      = array(
       'mp3'    => ' -ab 128000 -ar 44100 ',
       'amr' 	=> ' -acodec libopencore_amrnb -ac 1 -ar 8000 -ab 12.2k ',
       '3gp' 	=> ' -f 3gp -vcodec h263 -b 80k -r 15 -acodec libopencore_amrnb -ac 1 -ar 8000 -ab 12.2k -y ',
       'mp4' 	=> ' -f mp4 -vcodec mpeg4 -b 256k -r 15 -acodec libfaac -ac 2 -ar 24000 -ab 48k -y ',
       'iphone' => ' -f mp4 -vcodec mpeg4 -b 256k -r 15 -acodec libfaac -ac 2 -ar 24000 -ab 48k -y ');
       */

		//for linux
		
        $config['ffmpeg_path']		   = "ffmpeg"; //ffmpeg linux path
        $config['ffmpeg_prefix']	   = "";
        $config['ffmpeg_suffix']	   = " &"; //to run process in background
        $config['ffmpeg_before_dir']   = "/home/wap4/public_html/files/uploaded/";
        $config['ffmpeg_after_dir']    = "/home/wap4/public_html/files/converted/";
        $config['ffmpeg_key_dir']      = "/home/wap4/public_html/files/keys/"; //directory for key files
		
	
