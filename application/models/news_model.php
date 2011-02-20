<?php
class news_model extends CI_Model {
  function __construct(){
    parent::__construct();
  }

  function get_news($num, $offset) {
    //if(!is_numeric($offset)) $offset = false;
    $this->db->select('id,username,news,date')->from('news')->where('lang', $this->lang->lang())->limit($num, $offset)->order_by("id", "desc");
    $query = $this->db->get();
    
    return $query;
  }
  
  function getRecentPosts()
  {
    $this->db->where('lang', $this->lang->lang());
    $this->db->order_by('date', 'desc');
    $this->db->limit(10);
    return $this->db->get('news');
  }
  
   function get_one_news($id) {
       
      $this->db->where('id', $id); 
      $query = $this->db->get('news');
    
    return $query;
  }
  
    function delete_news($id) {

    $query = $this->db->delete('news', array('id' => $id)); 
    
    return $query;
  }
}
?>
