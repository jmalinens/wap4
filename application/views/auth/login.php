<?php //$this->load->view('includes/header'); ?>
<div class="loginInfo" id="loginbox">


<?php
/*
function get_facebook_cookie($app_id, $app_secret) {
  $args = array();
  parse_str(trim($_COOKIE['fbs_' . $app_id], '\\"'), $args);
  ksort($args);
  $payload = '';
  foreach ($args as $key => $value) {
    if ($key != 'sig') {
      $payload .= $key . '=' . $value;
    }
  }
  if (md5($payload . $app_secret) != $args['sig']) {
    return null;
  }
  return $args;
}
$cookie = get_facebook_cookie('187627851270954', '30a377c4f6221f9f73e9513d28f8a1df');
$user = json_decode(file_get_contents('https://graph.facebook.com/me?access_token=' . $cookie['access_token']));
*/


//if(!$this->facebook->logged_in()){?>

    
	<p><?php echo lang('login_pleaselog'); ?></p>
	
	<div id="infoMessage"><?php echo $message;?></div>
	
    <?php
    $attributes = array('id' => 'login_submit');
    echo form_open("auth/login", $attributes);?>
    	
      <p>
      	<label for="email"><?php echo lang('login_username'); ?>:</label>
      	<?php echo form_input($username);?>
      </p>
      
      <p>
      	<label for="password"><?php echo lang('login_password'); ?>:</label>
      	<?php echo form_input($password);?>
      </p>
      
      <p>
	      <label for="remember"><?php echo lang('login_remember'); ?>:</label>
	      <?php echo form_checkbox('remember', '1', FALSE);?>
	  </p>
      
      
      <p><?php echo form_submit('submit', lang('login_login'));?></p>

      
    <?php echo form_close();?>
      <div class="step">
      <?=lang("site.or")?>
      </div>

      <?=anchor("auth/create_user", lang("login_create"), 'id="id_create_user"')?>
      
    <div class="step">      
    <iframe src="http://www.facebook.com/plugins/like.php?href=http%3A%2F%2Fwap4.org&amp;layout=box_count&amp;show_faces=false&amp;width=100&amp;action=recommend&amp;colorscheme=light&amp;height=65"
            scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:100px; height:65px;" allowTransparency="true">
    </iframe>
    </div>    
</div>

<!--      
<?php //} ?>
<?=anchor("#", "Login with Facebook", 'id="fb_log"')?>
<?=anchor("#", "Register with Facebook", 'id="fb_reg"')?>


<div class="facebook">
           <div align="center">
           <img id="image"/>
           <div id="name"></div>
           </div>

</div>


<fb:login-button show-faces="false" width="200" max-rows="1"></fb:login-button>
-->
<?php //$this->load->view('includes/footer'); ?>
