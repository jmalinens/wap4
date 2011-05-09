</div>
<div class="right"><?php
$bIsMobile = isMobile();

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
        } else {
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
?></div>
<div class="bottom">
<p><strong>&#160;</strong></p>
</div>
<div class="footer">
	<div id="footer2">
		<div id="footer_top">
			<div class="footer_section" id="footer_about">
                                <?php if($bIsMobile):?>
                                    <h3><?=lang('title.h3')?></h3>
                                <?php endif;?>
                                
				<h4><?=lang('footer.features')?></h4>
				<ul>
					<li><?=lang('footer.support')?></li>
					<li><?=lang('footer.allows')?></li>
					<li><?=lang('footer.three')?></li>
					<li><?=lang('footer.free')?></li>
				</ul>
			</div>
			<div class="footer_section" id="footer_follow">
				<h4><?=lang('footer.follow')?></h4>
				<ul>
					<li class="social_twitter"><a href="http://twitter.com/wap4org"<?=(!$bIsMobile) ? ' target="_blank"' : ''?>>Twitter</a></li>
					<li class="social_facebook"><a href="http://www.facebook.com/pages/wap4org/160222834034783"<?=(!$bIsMobile) ? ' target="_blank"' : ''?>>Facebook</a></li>
					<li class="social_rss"><a href="http://wap4.org/en/feed/rss"<?=(!$bIsMobile) ? ' target="_blank"' : ''?>>RSS</a></li>
				</ul>
			</div>
<?php if(!$bIsMobile): ?>
			<div class="footer_section" id="footer_download">
				<h4><?=lang('footer.download')?></h4>
				<ul>
					<li><a href="https://github.com/jmalinens/wap4"<?=(!$bIsMobile) ? ' target="_blank"' : ''?>>@GitHub</a></li>
					<li><a href="https://sourceforge.net/p/wap4/home/"<?=(!$bIsMobile) ? ' target="_blank"' : ''?>>@SourceForge</a></li>
				</ul>
			</div>
<?php endif; ?>
		</div>
		<div id="footer_bot">
                    <?php
                    if(isMobile()) {
                            echo lang('mobile.w_vers'), ": <a href=\"http://wap4.org\">http://wap4.org</a>";
                        } else {
                            echo lang('mobile.m_vers'), ": <a href=\"http://m.wap4.org\">http://m.wap4.org</a>";
                        }
?><br/><?=lang('footer.created')?>, &#169; 2011
		</div>
	</div><?php
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
