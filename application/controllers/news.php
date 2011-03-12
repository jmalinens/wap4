<?php
class News extends CI_Controller {
  function __construct() {
    parent::__construct();
    $this->load->model('news_model');
    
    $this->load->helper('wap4', 'text');
    load_settings();
	
  }

  function index() {
    //if(irAjax()) echo "ir caur ajax!!!!!"; else echo "nav ajax";
    // load pagination class
    $this->load->library('pagination');
    $config['base_url'] = base_url().'/'.$this->lang->lang().'/news/index/';
    $this->db->where('lang', $this->lang->lang());
    $this->db->from('news');
    $config['total_rows'] = $this->db->count_all_results();
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
		
    //get results
    if(strlen($this->uri->segment(4)) > 5) $skaits = false; else $skaits = $this->uri->segment(4);
    $data['results'] = $this->news_model->get_news($config['per_page'],$skaits);
    $data['admin'] = $this->ion_auth->is_admin();
    // load the HTML Table Class
    $this->load->library('table');
		
    // load the view
    
    if(!irAjax())
    $this->load->view('includes/header', $this->data);
    $this->load->view('news', $data);
    if(!irAjax())
    $this->load->view('includes/footer', $this->data);

  }
  
  function archive() {
      
    if(!$this->uri->segment(4)) redirect("/"); 
    
    $data['results'] = $this->news_model->get_one_news($this->uri->segment(4));
 
    if(!irAjax())
    $this->load->view('includes/header', $this->data);
    
    $this->load->view('news', $data);
    
    if(!irAjax())
    $this->load->view('includes/footer', $this->data);    
  }
  
    function delete_news($id = NULL) 
    {
        		// no funny business, force to integer
		$id = (int) $id;
		
		$this->load->library('form_validation');
		$this->form_validation->set_rules('confirm', 'confirmation', 'required');
		$this->form_validation->set_rules('id', 'user ID', 'required|is_natural');
				
		if ( $this->form_validation->run() == FALSE )
		{
			// insert csrf check
			$this->data['csrf']	=	$this->_get_csrf_nonce();
			//$this->data['user']	=	$this->ion_auth->get_user($id);
                        $x  =       $this->news_model->get_one_news($id);
                        $this->data['results']  =       $x->result_array();
    		$this->load->view('delete_news', $this->data);
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

				// do we have the right userlevel?
				if ( $this->ion_auth->logged_in() && $this->ion_auth->is_admin() )
				{
					$this->news_model->delete_news($id);
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
