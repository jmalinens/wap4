<?php
/**
 * @author Juris Malinens, 2010-2013
 */

$config['navigation']          =  array('converter',
					'news',
					'howto',
					'about');

if(strtolower(substr(PHP_OS, 0, 3)) == 'win') {
    $config['mobile_host'] = "m.wap4.localhost";
    $config['web_host']  = "wap4.localhost";
} else {
    $config['mobile_host'] = "m.wap4.org";
    $config['web_host'] = "wap4.org";
}

//for linux
$config['ffmpeg_path']		   = " nice -n 12 /usr/local/bin/ffmpeg"; //ffmpeg linux path
$config['ffmpeg_prefix']	   = "";
$config['ffmpeg_suffix']	   = " &"; //to run process in background
