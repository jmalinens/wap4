<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth extends CI_Controller {
    
    /**
     * Is it is mobile or web version?
     * @var boolean 
     */
    public $bIsMobile;
    
    public function __construct() 
    {
        parent::__construct();
        if (!$this->ion_auth->is_admin()) {
        $this->load->model('site_model');
        $this->data['status'] = $this->site_model->get_site_status();
        $this->data['status'] = $this->data['status'][0]['setting_value'];
        if($this->data['status'] == "offline") echo lang('site.offline');
        }
        
        $this->load->library('form_validation');

        
        $this->load->helper('wap4');
        load_settings();
        
        $this->bIsMobile = isMobile();
        
        $this->max_kb = $this->config->item('ffmpeg_max');
        
        $uniqid                   = uniqid();
        $this->data['allowed']    = "'".implode("','", $this->config->item('ffmpeg_allowed'))."'";
        $this->data['uniqid']     = $uniqid;
        $this->data['message']    = '';
        $this->data['users']      = '';
        $this->data['attr']       = array('id' => 'conv');
        $this->data['formats']    = $this->ffmpeg->ffmpeg_formats;
	$this->data['navigation'] = $this->config->item('navigation');
        $this->data['lang']       = $this->lang->lang();        
        
        $this->load->model('site_model');
        
        if (!$this->ion_auth->logged_in())
        $this->max_kb = $this->site_model->get_setting('file_size_unregistered')*1024;
        else
        $this->max_kb = $this->site_model->get_setting('file_size_registered')*1024;
		
	$this->data['max']     	= $this->max_kb;
        
        parse_str($_SERVER['QUERY_STRING'], $_GET);
        
    }
    //redirect if needed, otherwise display the user list
    function index() 
    {
    	if (!$this->ion_auth->logged_in()) {
	    	//redirect them to the login page
                        if(!isset($_REQUEST['username']))
                        {
                            if(irAjax()) $ca="caur_ajax"; else $ca="";
                            redirect('auth/login/'.$ca, 'location');
                        }
                        else
                        {
                            redirect('/'.$this->lang->lang(), 'location');
                        }
			
    	}
    	elseif (!$this->ion_auth->is_admin()) {
    		//redirect them to the home page because they must be an administrator to view this
			//redirect($this->config->item('base_url'), 'refresh');
                        $user = $this->ion_auth->get_user();
                        $this->datb["username"] = $user->username;
                        
                        if(!isset($_REQUEST['username']))
                        {
                            $this->load->view('auth/profile', $this->datb);
                        }
                        else
                        {
                            redirect('/'.$this->lang->lang(), 'location');
                        }

    	}
    	else {
	        //set the flash data error message if there is one
	        $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
	        
                if(!isset($_REQUEST['username']))
                {
                    $this->load->model('site_model');
                    $this->data['status'] = $this->site_model->get_site_status();
                    $this->data['status'] = $this->data['status'][0]['setting_value'];

                    $this->load->view('auth/index', $this->data);
                }
                else
                {
                    redirect('/'.$this->lang->lang(), 'location');
                }
    	}
    }
    
    function status()
    {
            if ($this->ion_auth->is_admin()) {
            $this->load->model('site_model');
            $this->site_model->change_status($this->uri->segment(4));
            redirect('/'.$this->lang->lang(), 'location');
            }
    }
    
     function user_list() 
    {
        $this->load->library('pagination');
        $config['base_url']        = base_url().'/'.$this->lang->lang().'/auth/user_list/';
        $config['total_rows']      = $this->db->count_all('users');
        $config['per_page']        = '3';
        $config['uri_segment']     = 4;
        $config['full_tag_open']   = '<div id="pagination">';
        $config['full_tag_close']  = '</div>';

        $config['prev_tag_open']   = '<div class="link">';
        $config['prev_tag_close']  = '<div class="link">';

        $config['first_tag_open']  = '<div class="link">';
        $config['first_tag_close'] = '<div class="link">';

        $config['last_tag_open']   = '<div class="link">';
        $config['last_tag_close']  = '<div class="link">';

        $config['next_tag_open']   = '<div class="link">';
        $config['next_tag_close']  = '<div class="link">';

        $config['cur_tag_open']    = '<div class="link">';
        $config['cur_tag_close']   = '<div class="link">';

        $config['num_tag_open']    = '<div class="link">';
        $config['num_tag_close']   = '<div class="link">';

        $this->pagination->initialize($config);
    
    	if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
    		//$this->data['users'] = $this->ion_auth->get_users_array();
                $this->load->model('user_model');
                $data['results'] = $this->user_model->get_users($config['per_page'],$this->uri->segment(4));
                
                $this->load->library('table');
    		$this->load->view('auth/user_list', $data);
    	}
    	else {
            die();
    	}
    }
    
    function add_news()
    {
        if($this->ion_auth->is_admin()) //piekljuve tikai adminam
        {
            $vai_pievieno = $this->input->post('content');
            if($vai_pievieno)
            {
                $author = $this->ion_auth->get_user();
                
                $this->db->query("INSERT INTO `news` (`id`, `username`, `news`, `date`, `lang`)
                                  VALUES (
                                  NULL,
                                  ".$this->db->escape($author->username).",
                                  ".$this->db->escape($this->input->post('content')).",
                                  NOW(),
                                  '".$this->lang->lang()."'
                                  );");
                redirect('/', 'refresh');
            }
            else
            {
            $this->load->helper('ckeditor');
            $this->data['ckeditor'] = array(
                            //ID of the textarea that will be replaced
                            'id' 	=> 	'content',
                            'path'	=>	'js/ckeditor',
                            //Optionnal values
                            'config' => array(
                                    'toolbar' 	=> 	"Full", 	//Using the Full toolbar
                                    'width' 	=> 	"500px",	//Setting a custom width
                                    'height' 	=> 	'200px',	//Setting a custom height
                            ),
                            //Replacing styles from the "Styles tool"
                            'styles' => array(
                                    //Creating a new style named "style 1"
                                    'style 1' => array (
                                            'name' 	=> 	'Blue Title',
                                            'element' 	=> 	'h2',
                                            'styles'    => array(
                                                    'color'       => 	'Blue',
                                                    'font-weight' => 	'bold'
                                            )
                                    ),
                                    //Creating a new style named "style 2"
                                    'style 2' => array (
                                            'name' 	=> 	'Red Title',
                                            'element' 	=> 	'h2',
                                            'styles' => array(
                                                    'color' 		=> 	'Red',
                                                    'font-weight' 	=> 	'bold',
                                                    'text-decoration'	=> 	'underline'
                                            )
                                    )
                            )
                    );
            $aData['data'] = $this->data;
            $this->load->view('includes/header', $aData);
            $this->load->view('ckeditor', $this->data);
            $this->load->view('includes/footer', $aData);
            }
        }
    }
    
        //redirect if needed, otherwise display the user list
    function edit_profile() 
    {
	//print_r($_REQUEST);
        $this->form_validation->set_rules('email', 'email', 'required|email');
        $user = $this->ion_auth->get_user();
        $username = $user->username;
        if ($this->form_validation->run() == true) { //check to see if the user is logging in
	
            $update = array(
                            'birthday' => mysql_real_escape_string($_REQUEST['year']."-".$_REQUEST['month']."-".$_REQUEST['day']),
                            'gender' => intval($_REQUEST['gender']),
                            'email' => mysql_real_escape_string($_REQUEST['email']),
                             );
            $this->ion_auth->update_user($user->id, $update);

                    //check for "reset password"
            if ($this->input->post('reset_password') == "on") {
                $reset = true;
                $identity = $this->session->userdata($this->config->item('identity', 'ion_auth'));

                $change = $this->ion_auth->change_password($identity, $this->input->post('old_password'), $this->input->post('password'));

                if ($change) { //if the password was successfully changed
                        $this->session->set_flashdata('message', $this->ion_auth->messages());
                        $this->logout();
                }
                else {
                        $this->session->set_flashdata('message', $this->ion_auth->errors());
                        redirect('auth/change_password', 'refresh');
                }


                if ($this->ion_auth->login($username, $this->input->post('password'), true)) { //if the login is successful
                    //redirect them back to the home page
                    $this->session->set_flashdata('message', $this->ion_auth->messages());
                    redirect($this->config->item('base_url'), 'refresh');
            }
            else { //if the login was un-successful
                    //redirect them back to the login page
                    $this->session->set_flashdata('message', $this->ion_auth->errors());
                    $aData['data'] = $this->data;
                    $this->load->view('v2/includes/header', $aData);
                    //redirect('auth/index/error', 'refresh'); //use redirects instead of loading views for compatibility with MY_Controller libraries
                    $this->data['message'] = $this->ion_auth->errors();

                    $this->load->view('auth/login', $this->data);
                    $this->load->view('v2/includes/footer', $aData);
            }



            }
            else {
                    $reset = false;
                            redirect($this->config->item('base_url'), 'refresh');
            }
        	
        }
        else {
        
    	if (!$this->ion_auth->logged_in()) {
	    	//redirect them to the login page
			redirect($this->config->item('base_url'), 'location');
    	}
    	else
        {
		                                             
			$days = 1;
			$day = array();
	        while ( $days <= '31'){
	            $day[$days]=$days;
	            $days++;
	        }
            $this->data['day']   = $day;
            $this->lang->load('calendar');
            $this->data['month'] = $month=array(
                                    '01'=>$this->lang->line('cal_january'),
                                    '02'=>$this->lang->line('cal_february'),
                                    '03'=>$this->lang->line('cal_march'),
                                    '04'=>$this->lang->line('cal_april'),
                                    '05'=>$this->lang->line('cal_may'),
                                    '06'=>$this->lang->line('cal_june'),
                                    '07'=>$this->lang->line('cal_july'),
                                    '08'=>$this->lang->line('cal_august'),
                                    '09'=>$this->lang->line('cal_september'),
                                    '10'=>$this->lang->line('cal_october'),
                                    '11'=>$this->lang->line('cal_november'),
                                    '12'=>$this->lang->line('cal_december')
                                );
            
            
			$years = 2005;
			$year = array();
	        while ( $years >= 1900){
	            $year[$years] = $years;
	            $years--;
	        }
                
            $user = $this->ion_auth->get_user();
            
            $this->data['year']               = $year;
            
            $this->data['email']              = array('name'    => 'email',
                                                      'id'      => 'email',
                                                      'type'    => 'email',
                                                      'value'   => $user->email,
                                                     );
            $this->data['gender']             = array('name'    => 'gender',
                                                      'id'      => 'gender',
                                                      'type'    => 'text',
                                                      'value'   => $this->form_validation->set_value('gender'),
                                                     );
            $this->data['password']           = array('name'    => 'password',
                                                      'id'      => 'password',
                                                      'type'    => 'password',
                                                      'value'   => $this->form_validation->set_value('password'),
                                                     );
            $this->data['old_password']       = array('name'    => 'old_password',
                                                      'id'      => 'old_password',
                                                      'type'    => 'password',
                                                      'value'   => $this->form_validation->set_value('old_password'),
                                                     );
            $this->data['password_confirm']   = array('name'    => 'password_confirm',
                                                      'id'      => 'password_confirm',
                                                      'type'    => 'password',
                                                      'value'   => $this->form_validation->set_value('password_confirm'),
                                                     );
            
            $this->data['navigation']         = $this->config->item('navigation');
            $this->data['uniqid']             = uniqid();
            $this->data['lang']               = $this->lang->lang();
            
            
            $this->data['user_id']            = array('name'    => 'user_id',
                                                      'id'      => 'user_id',
                                                      'type'    => 'hidden',
                                                      'value'   => $user->id,
                                                     );

            
            $aData['data'] = $this->data;
            $this->load->view('v2/includes/header', $aData);
            $this->load->view('v2/auth/edit_user', $this->data);
            $this->load->view('v2/includes/footer', $aData);
            
    	}
        
        }
    }
    
    //log the user in
    function login() 
    {
        $this->data['title'] = "Login";
        
        //validate form input
    	$this->form_validation->set_rules('username', 'Username', 'required');
	$this->form_validation->set_rules('password', 'Password', 'required');
        
        $this->data['username']   = array('name' => 'username',
                                      'id'       => 'username',
                                      'type'     => 'text',
                                      'value'    => $this->form_validation->set_value('username'),
                                     );
        $this->data['password']   = array('name' => 'password',
                                      'id'       => 'password',
                                      'type'     => 'password',
                                     );

        if ($this->form_validation->run() == true) { //check to see if the user is logging in
        	//check for "remember me"
        	if ($this->input->post('remember') == 1) {
        		$remember = true;
        	}
        	else {
        		$remember = false;
        	}
        	
        	if ($this->ion_auth->login(
                        $this->input->post('username'),
                        $this->input->post('password'),
                        $remember)) { //if the login is successful
	        	//redirect them back to the home page
	        	$this->session->set_flashdata('message', $this->ion_auth->messages());
	        	redirect($this->config->item('base_url'), 'refresh');
	        }
	        else { //if the login was un-successful
	        	//redirect them back to the login page
	        	$this->session->set_flashdata('message', $this->ion_auth->errors());
                        
                        /*
                        if(!irAjax())
                        $this->load->view('includes/header', $this->data);
                        
                        $this->data['message'] = $this->ion_auth->errors();
                        $this->load->view('auth/login', $this->data);
                        
                        if(!irAjax())
                        $this->load->view('includes/footer', $this->data);
                        */
                        redirect($this->config->item('base_url'), 'refresh');
                        
	        }
        } else {  //the user is not logging in so display the login page
	        //set the flash data error message if there is one
	        $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
		    
                $aData['data'] = $this->data;
	        if(!irAjax())
                $this->load->view('includes/header', $aData);
                

                //if($this->uri->segment(4) != "facebook")
    		$this->load->view('auth/login', $this->data);
                
                if(!irAjax())
                $this->load->view('includes/footer', $aData);
                
		}
               
    }
    
    //log the user out
	function logout() 
	{
        $this->data['title'] = "Logout";
        
        //log the user out
        $logout = $this->ion_auth->logout();
			    
        //redirect them back to the page they came from
        redirect($this->config->item('base_url'), 'refresh');
    }
    
    //change password
	function change_password() 
	{	    
	    $this->form_validation->set_rules('old', 'Old password', 'required');
	    $this->form_validation->set_rules('new', 'New Password',
            'required|min_length['.$this->config->item('min_password_length',
            'ion_auth').']|max_length['.$this->config->item('max_password_length',
            'ion_auth').']|matches[new_confirm]');
            
	    $this->form_validation->set_rules(
                    'new_confirm',
                    'Confirm New Password',
                    'required');
	   
	    if (!$this->ion_auth->logged_in()) {
	    	redirect('auth/login', 'refresh');
	    }
	    $user = $this->ion_auth->get_user($this->session->userdata('user_id'));
	    
	    if ($this->form_validation->run() == false) { //display the form
//set the flash data error message if there is one
$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			
$this->data['old_password']           = array('name'    => 'old',
                                              'id'      => 'old',
                                              'type'    => 'password',
                                             );
$this->data['new_password']           = array('name'     => 'new',
                                              'id'      => 'new',
                                              'type'    => 'password',
                                             );
$this->data['new_password_confirm']   = array('name'    => 'new_confirm',
                                              'id'      => 'new_confirm',
                                              'type'    => 'password',
                                             );
$this->data['user_id']                = array('name'    => 'user_id',
                                              'id'      => 'user_id',
                                              'type'    => 'hidden',
                                              'value'   => $user->id,
                                              );
	        
        	//render
        	$this->load->view('auth/change_password', $this->data);		        
	    }
	    else {
	        $identity = $this->session->userdata($this->config->item('identity', 'ion_auth'));
	        
	        $change = $this->ion_auth->change_password($identity, $this->input->post('old'), $this->input->post('new'));
		
    		if ($change) { //if the password was successfully changed
    			$this->session->set_flashdata('message', $this->ion_auth->messages());
    			$this->logout();
    		}
    		else {
    			$this->session->set_flashdata('message', $this->ion_auth->errors());
    			redirect('auth/change_password', 'refresh');
    		}
	    }
	}
	
	//forgot password
	function forgot_password() 
	{
		$this->form_validation->set_rules('email', 'Email Address', 'required');
	    if ($this->form_validation->run() == false) {
	    	//setup the input
	    	$this->data['email'] = array('name'    => 'email',
                                         'id'      => 'email',
        						   	    );
	    	//set any errors and display the form
        	$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
    		$this->load->view('auth/forgot_password', $this->data);
	    }
	    else {
	        //run the forgotten password method to email an activation code to the user
			$forgotten = $this->ion_auth->forgotten_password($this->input->post('email'));
			
			if ($forgotten) { //if there were no errors
				$this->session->set_flashdata('message', $this->ion_auth->messages());
	            redirect("auth/login", 'refresh'); //we should display a confirmation page here instead of the login page
			}
			else {
				$this->session->set_flashdata('message', $this->ion_auth->errors());
	            redirect("auth/forgot_password", 'refresh');
			}
	    }
	}
	
	//reset password - final step for forgotten password
	public function reset_password($code) 
	{
		$reset = $this->ion_auth->forgotten_password_complete($code);
		
		if ($reset) {  //if the reset worked then send them to the login page
			$this->session->set_flashdata('message', $this->ion_auth->messages());
            redirect("auth/login", 'refresh');
		}
		else { //if the reset didnt work then send them back to the forgot password page
			$this->session->set_flashdata('message', $this->ion_auth->errors());
            redirect("auth/forgot_password", 'refresh');
		}
	}

	//activate the user
	function activate($id, $code=false) 
	{        
		$activation = $this->ion_auth->activate($id, $code);
		
        if ($activation) {
			//redirect them to the auth page
	        $this->session->set_flashdata('message', $this->ion_auth->messages());
	        redirect("auth", 'refresh');
        }
        else {
			//redirect them to the forgot password page
	        $this->session->set_flashdata('message', $this->ion_auth->errors());
	        redirect("auth/forgot_password", 'refresh');
        }
    }
    
    //deactivate the user
	function deactivate($id = NULL) 
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
			$this->data['user']	=	$this->ion_auth->get_user($id);
    		$this->load->view('auth/deactivate_user', $this->data);
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
					$this->ion_auth->deactivate($id);
				}
			}
	
			//redirect them back to the auth page
			redirect('auth','refresh');
		}
    }
    
    //create a new user
	function create_user() 
	{
        if(!isMobile())
        $this->load->library('recaptcha');
        $this->load->library('form_validation');
        $this->lang->load('recaptcha');
        
        
        $this->data['title'] = "Create User";
        $this->data["meta"]  = "create_user";
              
		//if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
		//	redirect('auth', 'refresh');
		//}
		
        //validate form input
    	$this->form_validation->set_rules('username', 'Username', 'required|xss_clean');
    	$this->form_validation->set_rules('day', 'Day', 'required|xss_clean');
    	$this->form_validation->set_rules('month', 'Month', 'required|xss_clean');
    	$this->form_validation->set_rules('year', 'Year', 'required|xss_clean');
    	$this->form_validation->set_rules('email', 'Email Address', 'required|valid_email');
    	$this->form_validation->set_rules('gender', 'Gender', 'required|xss_clean');
    	$this->form_validation->set_rules('password', 'Password', 'required|min_length['.$this->config->item('min_password_length', 'ion_auth').']|max_length['.$this->config->item('max_password_length', 'ion_auth').']|matches[password_confirm]');
    	$this->form_validation->set_rules('password_confirm', 'Password Confirmation', 'required');

        if ($this->form_validation->run() == true) {
            $username  = strtolower($this->input->post('username'));
            $email     = $this->input->post('email');
            $password  = $this->input->post('password');
            
            $gender = $this->input->post('gender') == "male"? '0': '1';
            
            $additional_data = array('birthday' => $this->input->post('year').'-'.$this->input->post('month').'-'.$this->input->post('day'),
             				        'gender'  => $gender
        				       );
        }
        if ($this->form_validation->run() == true && $this->ion_auth->register($username,$password,$email,$additional_data)) { //check to see if we are creating the user
                //redirect them back to the admin page
        	
            if(!isMobile())
            $this->load->view('recaptcha_demo',array('recaptcha'=>'Yay! You got it right!'));
            
            
            
            
            
            $this->session->set_flashdata('message', "User Created");
       		//redirect("auth", 'refresh');
            $this->ion_auth->login($this->input->post('username'), $this->input->post('password'), true);
            
                redirect('/'.$this->lang->lang(), 'refresh');
		} 
		else { //display the create user form
	        //set the flash data error message if there is one
	        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
			
                $this->data['username']= array('name'  => 'username',
                                             'id'      => 'username',
                                             'type'    => 'text',
                                             'value'   => $this->form_validation->set_value('login'),
                                            );
		                                             
			$days = 1;
			$day = array();
	        while ( $days <= '31'){
	            $day[$days]=$days;
	            $days++;
	        }
    $this->data['day']   = $day;
    $this->lang->load('calendar');
    $this->data['month'] = $month=array(
                                '01'=>$this->lang->line('cal_january'),
                                '02'=>$this->lang->line('cal_february'),
                                '03'=>$this->lang->line('cal_march'),
                                '04'=>$this->lang->line('cal_april'),
                                '05'=>$this->lang->line('cal_may'),
                                '06'=>$this->lang->line('cal_june'),
                                '07'=>$this->lang->line('cal_july'),
                                '08'=>$this->lang->line('cal_august'),
                                '09'=>$this->lang->line('cal_september'),
                                '10'=>$this->lang->line('cal_october'),
                                '11'=>$this->lang->line('cal_november'),
                                '12'=>$this->lang->line('cal_december')
                            );
            
            
			$years = 2005;
			$year = array();
	        while ( $years >= 1900){
	            $year[$years] = $years;
	            $years--;
	        }
$this->data['year']               = $year;

if(!$this->bIsMobile)
    $sFieldType = 'password';
else
    $sFieldType = 'text';

$this->data['email']              = array('name'    => 'email',
                                          'id'      => 'email',
                                          'type'    => 'text',
                                          'value'   => $this->form_validation->set_value('email'),
                                         );
$this->data['gender']             = array('name'    => 'gender',
                                          'id'      => 'gender',
                                          'type'    => 'text',
                                          'value'   => $this->form_validation->set_value('gender'),
                                         );
$this->data['password']           = array('name'    => 'password',
                                          'id'      => 'password',
                                          'type'    => $sFieldType,
                                          'value'   => $this->form_validation->set_value('password'),
                                         );
$this->data['password_confirm']   = array('name'    => 'password_confirm',
                                          'id'      => 'password_confirm',
                                          'type'    => $sFieldType,
                                          'value'   => $this->form_validation->set_value('password_confirm'),
                                         );
if(!isMobile())
$this->data['security']           = $this->recaptcha->get_html();
$this->data['navigation']         = $this->config->item('navigation');

$this->data['uniqid']             = uniqid();
$this->data['lang']               = $this->lang->lang();
$aData['data'] = $this->data;
$this->load->view('v2/includes/header', $aData);
$this->load->view('v2/auth/create_user', $this->data);
$this->load->view('v2/includes/footer', $aData);
		}
          
          
          function check_captcha($val) {
	  if ($this->recaptcha->check_answer($this->input->ip_address(),$this->input->post('recaptcha_challenge_field'),$val)) {
	    return TRUE;
	  } else {
	    $this->form_validation->set_message('check_captcha',$this->lang->line('recaptcha_incorrect_response'));
	    return FALSE;
	  }
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
