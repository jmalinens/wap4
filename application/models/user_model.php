<?php
class user_model extends CI_Model {
  function __construct(){
    parent::__construct();
  }
  
  function get_users($num, $offset) {

    //$this->db->order_by("id", "desc"); 
    //$query = $this->db->get('users', $num, $offset);
      
    $this->db->select('*');
    $this->db->from('users');
    $this->db->limit($num, $offset);
    $this->db->join('meta', 'meta.user_id = users.id');
    $this->db->join('groups', 'groups.id = users.group_id');
    $query = $this->db->get();

    return $query;
  }
  
}
?>
