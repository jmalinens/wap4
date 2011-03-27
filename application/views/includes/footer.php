</div>

<div class="right">
<?php
if (!$this->ion_auth->logged_in()) {

if($this->uri->segment(4) != "facebook") {

    $this->data['title'] = "Login";

    //validate form input
    $this->form_validation->set_rules('username', 'Username', 'required');
    $this->form_validation->set_rules('password', 'Password', 'required');
        
    $this->data['username']   = array('name'    => 'username',
                                      'id'      => 'username',
                                      'type'    => 'text',
                                      'value'   => $this->form_validation->set_value('username'),
                                     );
    $this->data['password']   = array('name'    => 'password',
                                      'id'      => 'password',
                                      'type'    => 'password',
                                     );

if ($this->form_validation->run() == true) { //check to see if the user is logging in
        //check for "remember me"
        if ($this->input->post('remember') == 1) {
                $remember = true;
        }
        else {
                $remember = false;
        }

        if ($this->ion_auth->login($this->input->post('username'), $this->input->post('password'), $remember)) { //if the login is successful
                //redirect them back to the home page
                $this->session->set_flashdata('message', $this->ion_auth->messages());
                redirect($this->config->item('base_url'), 'refresh');
        }
        else { //if the login was un-successful
                //redirect them back to the login page
                $this->session->set_flashdata('message', $this->ion_auth->errors());



                if(!irAjax())
                $this->load->view('includes/header', $this->data);

                $this->data['message'] = $this->ion_auth->errors();
                $this->load->view('auth/login', $this->data);

                if(!irAjax())
                $this->load->view('includes/footer', $this->data);
        }
} else {  //the user is not logging in so display the login page
//set the flash data error message if there is one
$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

if($this->uri->segment(4) != "facebook")
$this->load->view('auth/login', $this->data);
}
    
}

} elseif (!$this->ion_auth->is_admin()) {

    $user = $this->ion_auth->get_user();
    $this->datb["username"] = $user->username;
    $this->load->view('auth/profile', $this->datb);

} else {
    
    //set the flash data error message if there is one
    $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
    $this->load->model('site_model');
    $this->data['status'] = $this->site_model->get_site_status();
    $this->data['status'] = $this->data['status'][0]['setting_value'];
    $this->load->view('auth/index', $this->data);

}
?>
</div>
<div class="bottom">
<p><strong>&copy; Juris Malinens 2011</strong></p>
</div>
<div class="footer">
    <?php
    /*
		<!--<a href="http://validator.w3.org/check?uri=referer">
			<img src="/img/valid-xhtml10-converter.png" alt="Valid XHTML 1.0 Strict" height="31" width="88" />
		</a>
		<a href="http://jigsaw.w3.org/css-validator/check/referer">
			<img src="/img/vcss-converter.png" alt="Valid CSS!" width="88" height="31" />
		</a>
		<a href="http://ffmpeg.org">
			<img src="/img/ffmpeg-logo-converter.png" alt="Powered by FFMPEG" width="123" height="31" />
		</a>
		<a href="http://www.apache.org">
			<img src="/img/powered-by-apache-converter.png" alt="Powered by Apache WebServer" width="88" height="31" />
		</a>
		<a href="http://www.mysql.com">
			<img src="/img/button_mysql-converter.png" alt="Powered by MySQL Database" width="88" height="31" />
		</a>-->
    */
    ?>
</div>
</div>
</div>
</body>
</html>
