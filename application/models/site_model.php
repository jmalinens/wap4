<?php
class site_model extends CI_Model {
  function __construct(){
    parent::__construct();
  }
  
   function get_site_status() {
    $this->db->reconnect();
    $this->db->select('setting_value');
    $this->db->where('setting_name', 'status');
    $query = $this->db->get("site");
    return $query->result_array();
  }
  
   public function get_setting($setting) {
    $this->db->select('setting_value');
    $this->db->like('setting_name', $setting);
    $q = $this->db->get("site");
    $result = $q->result_array();
    return intval($result[0]['setting_value']);
  }
  
   public function update_sizes($unregistered, $registered) {
    $one_ok = false;
    $two_ok = false;
    $this->db->query("UPDATE site SET setting_value = '{$unregistered}' WHERE setting_name = 'file_size_unregistered'");
    if($this->db->affected_rows() > 0) $one_ok = true;
    $this->db->query("UPDATE site SET setting_value = '{$registered}' WHERE setting_name = 'file_size_registered'");
    if($this->db->affected_rows() > 0) $two_ok = true;
    
    return (bool)($one_ok && $two_ok);
  }
  
  function change_status($status) {
      if($status == "online") $new_status = "offline"; else $new_status = "online";
      $data = array(
               'setting_value' => $new_status
            );
    $this->db->reconnect();
    $this->db->where('setting_name', 'status');
    $this->db->where('setting_value', $status);
    $this->db->update('site', $data); 
  }
}
?>
