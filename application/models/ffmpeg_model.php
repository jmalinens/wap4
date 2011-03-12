<?php
class ffmpeg_model extends CI_Model {
  function __construct(){
    parent::__construct();
  }

  function add_video_to_db($user, $video, $apraksts) {

    $sql = "INSERT INTO `videos` (`id`, `users_id`, `file_name`, `description`, `date`) VALUES (NULL, '".intval($user)."', '".mysql_real_escape_string(htmlspecialchars($video))."', '".mysql_real_escape_string(htmlspecialchars($apraksts))."', NOW());";
	file_put_contents("/home/wap4/public_html/files/test.txt", "\nINSERT INTO `videos` (`id`, `users_id`, `file_name`, `description`, `date`) VALUES (NULL, '".intval($user)."', '".mysql_real_escape_string(htmlspecialchars($video))."', '".mysql_real_escape_string(htmlspecialchars($apraksts))."', NOW());", FILE_APPEND | LOCK_EX);
    $this->db->query($sql);

    //echo $this->db->affected_rows(); 

  }
}
