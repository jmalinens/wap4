<?php
/**
 * Juris Malinens, 2010-2011
 * @version 0.1 19.08.2011
 */

if(strtolower(substr(PHP_OS, 0, 3)) == "win") {
    //for windows
    $config['downloader_python'] = "python";
    $config['downloader_script'] = "C:/youtube-dl.py";
} else {
    //for linux
    $config['downloader_python'] = "/usr/local/bin/python2.7";
    $config['downloader_script'] = "/home/wap4/youtube-dl.py";
}

		
	
