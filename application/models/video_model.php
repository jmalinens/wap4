<?php
class video_model extends CI_Model {
  function __construct(){
    parent::__construct();
  }

  function get_videos($num, $offset) {
      $non_admin = false;
      if (!$this->ion_auth->is_admin()) {
          $par_lietotaaju = $this->ion_auth->get_user_array();
          $non_admin = true;
      }
      
      $this->db->order_by("id", "desc");
      if($non_admin) $this->db->where('users_id', $par_lietotaaju['id']);
      $query = $this->db->get('videos', $num, $offset);
    
    return $query;
  }
  
    function delete_video($id) {
    $par_video = $this->get_video($id);
    $apraksts = $par_video->result_array();
    $faila_nosaukums = $apraksts[0]['file_name'];
    
    
    $this->config->load('ffmpeg');
    $celjsh_uz_failu = $this->config->item('ffmpeg_after_dir');
    if(is_file($celjsh_uz_failu.$faila_nosaukums)) unlink($celjsh_uz_failu.$faila_nosaukums); //dzeesham konverteeto failu
    
    $query = $this->db->delete('videos', array('id' => $id)); 
    
    return $query;
  }
  
   function get_video($id) {
       
      $this->db->where('id', $id); 
      $query = $this->db->get('videos');
    
    return $query;
  }
}
?>