<?php
class ffmpeg_model extends CI_Model
{
    
function __construct()
{
    parent::__construct();
}

/**
 * Old function to insert info about video
 * @param int $user
 * @param string $video
 * @param string $apraksts 
 */
function add_video_to_db($user, $video, $apraksts)
{
    $sql = "INSERT INTO `videos` (`id`, `users_id`, `file_name`, `description`, `date`)
        VALUES
        (NULL, '".intval($user)."',
            '".mysql_real_escape_string(htmlspecialchars($video))."',
                '".mysql_real_escape_string(htmlspecialchars($apraksts))."',
                    NOW());";
    $this->db->query($sql);
}

/**
 * Add video to DB (not used, created as example)
 * @param string $uniqid
 * @param int $users_id
 * @param string $file_body
 * @param int $file_size
 * @param string $original_extension
 * @param string $converted_extension
 * @param bool $is_uploaded
 * @param bool $is_converted
 * @param string $source_type (upload, youtube, vimeo or direct)
 * @param string $description 
 */
function add_video_to_db_new(
        $uniqid,
        $users_id,
        $file_body,
        $file_size,
        $original_extension,
        $converted_extension,
        $is_uploaded,
        $is_converted,
        $source_type,
        $description)
{
    $data = array(
    'uniqid'                => $uniqid,
    'users_id'              => $users_id,
    'file_body'             => $file_body,
    'file_size'             => $file_size,
    'original_extension'    => $original_extension,
    'converted_extension'   => $converted_extension,
    'is_uploaded'           => $is_uploaded,
    'is_converted'          => $is_converted,
    'source_type'           => $source_type,
    'description'           => $description
    );
    $this->db->set('date', 'NOW()', FALSE);
    $this->db->insert('videos', $data); 
}

/**
 * Checks if there is already row for this uniqid in DB
 * @param string $uniqid
 * @return boolean 
 */
function isUniqidInDb($sUniqid)
{
    $query = $this->db->get_where('videos', array('uniqid' => $sUniqid));
    if ($query->num_rows() > 0)
        return true;
    else
        return false;
}

/**
 * Gets info from DB about video
 * @param string $sUniqid
 * @param array $aFields
 * @return array 
 */
function getInfo($sUniqid, $aFields = false)
{
    if(is_array($aFields))
        $this->db->select(implode(", ", $aFields));
    
    $query = $this->db->get_where('videos', array('uniqid' => $sUniqid),1);
    return $query->result_array();
}

/**
 * Sets video information in database
 * @param array $aInfo
 * @return boolean false on failure or object 
 */
function setInfo($aInfo)
{
    if(array_key_exists("uniqid", $aInfo)) {
        
        $sUniqid = $aInfo["uniqid"];
        
        /**
         * if uniqid exists in DB, perform update,
         * else insert new row
         */
        if(isUniqidInDb($aInfo["uniqid"])) {
            $this->db->where('uniqid', $sUniqid);
            return $this->db->update('videos', $aInfo); 
        } else {
            $this->db->set('date', 'NOW()', FALSE);
            return $this->db->insert('videos', $aInfo); 
        }
        
    } else {
        log_message('error', 'uniqid not present in array: '.implode("||", $aInfo));
        return false;
    }
}

function cleanAfterConverter($uniqid)
{
    $cfg_dir = $this->config->item("ffmpeg_key_dir").$uniqid;
    
    if(is_file($cfg_dir.".lala"))
        unlink($cfg_dir.".lala");
    if(is_file($cfg_dir.".wget"))
        unlink($cfg_dir.".wget");
    if(is_file($cfg_dir.".fail"))
        unlink($cfg_dir.".fail");
    if(is_file($cfg_dir.".title"))
        unlink($cfg_dir.".title");
    if(is_file($cfg_dir.".lenght"))
        unlink($cfg_dir.".lenght");
}


}
