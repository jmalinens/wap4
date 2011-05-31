<?php
class Videos extends CI_Controller {
    
    	public function __construct() {
		parent::__construct();
                $this->load->model('video_model');
                if (!$this->ion_auth->logged_in()) die();
                parse_str($_SERVER['QUERY_STRING'], $_GET);
                load_settings();
	}
 
    
    function index()
    {
        redirect("/");
    }

function video_list() 
{
    $this->load->library('pagination');
    $config['base_url'] = base_url().'/'.$this->lang->lang().'/videos/video_list/';
    
    if (!$this->ion_auth->is_admin()) {
    $par_lietotaaju = $this->ion_auth->get_user_array();
    $query = $this->db->query("SELECT COUNT(*) AS count FROM videos WHERE users_id = '{$par_lietotaaju['id']}'");
    $row = $query->row();
    $config['total_rows'] = $row->count;
    }
    else {
    $config['total_rows'] = $this->db->count_all('videos');
    }
    $config['per_page'] = '3';
    $config['uri_segment'] = 4;
    $config['full_tag_open'] = '<div id="pagination">';
    $config['full_tag_close'] = '</div>';
    
    $config['prev_tag_open'] = '<div class="link">';
    $config['prev_tag_close'] = '<div class="link">';
    
    $config['first_tag_open'] = '<div class="link">';
    $config['first_tag_close'] = '<div class="link">';
    
    $config['last_tag_open'] = '<div class="link">';
    $config['last_tag_close'] = '<div class="link">';
    
    $config['next_tag_open'] = '<div class="link">';
    $config['next_tag_close'] = '<div class="link">';
    
    $config['cur_tag_open'] = '<div class="link">';
    $config['cur_tag_close'] = '<div class="link">';
    
    $config['num_tag_open'] = '<div class="link">';
    $config['num_tag_close'] = '<div class="link">';

    $this->pagination->initialize($config);         
         
         
         
         
    	if ($this->ion_auth->logged_in()) {
                
                $data['results'] = $this->video_model->get_videos($config['per_page'],$this->uri->segment(4));
                //echo $this->db->last_query();
                // load the HTML Table Class
                $this->load->library('table');
                $this->load->view('v2/includes/header', $this->data);
    		$this->load->view('video_list', $data);
                $this->load->view('v2/includes/footer', $this->data);
    	}
    	else {
            
            die();
    	}

}
    
    function delete_video($id = NULL) 
    {
        		// no funny business, force to integer
		$id = (int) $id;
		
		$this->load->library('form_validation');
		$this->form_validation->set_rules('confirm', 'confirmation', 'required');
		$this->form_validation->set_rules('id', 'user ID', 'required|is_natural');
                
                $x  =       $this->video_model->get_video($id);
                $this->data['results']  =       $x->result_array();
				
		if ( $this->form_validation->run() == FALSE )
		{
			// insert csrf check
			$this->data['csrf']	=	$this->_get_csrf_nonce();
			//$this->data['user']	=	$this->ion_auth->get_user($id);

    		$this->load->view('delete_video', $this->data);
		}
		else
		{
			// do we really want to deactivate?
			if ( $this->input->post('confirm') == 'yes' )
			{
				// do we have a valid request?
				if ( $this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id') )
				{
					show_404();
				}

				// do we have the right userlevel and user?
                                $par_lietotaaju = $this->ion_auth->get_user_array();
				if ($this->ion_auth->logged_in() && $par_lietotaaju['id'] == $this->data['results'][0]['users_id'])
				{
					$this->video_model->delete_video($id);
				}
			}
	
			//redirect them back to the auth page
			redirect('/'.$this->lang->lang(),'refresh');
		}
    }
    
    
    function _get_csrf_nonce()
    {
	    $this->load->helper('string');
            $key	= random_string('alnum', 8);
            $value	= random_string('alnum', 20);
            $this->session->set_flashdata('csrfkey', $key);
            $this->session->set_flashdata('csrfvalue', $value);

            return array($key=>$value);
    }
    
    function _valid_csrf_nonce()
    {
                    if ( $this->input->post($this->session->flashdata('csrfkey')) !== FALSE &&
                             $this->input->post($this->session->flashdata('csrfkey')) == $this->session->flashdata('csrfvalue'))
                    {
                            return TRUE;
                    }
                    else
                    {
                            return FALSE;
                    }
    }

}