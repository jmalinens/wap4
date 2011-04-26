<div class="loginInfo" id="loginbox">

    <p><?php echo lang('login_pleaselog'); ?></p>

    <div id="infoMessage"><?php echo $message;?></div>
	
    <?php
    $attributes = array('id' => 'login_submit');
    echo form_open("auth/login", $attributes);?><div>
      <p>
      	<label for="username"><?php echo lang('login_username'); ?>:</label>
      	<?php echo form_input($username);?>
      </p>
      <p>
      	<label for="password"><?php echo lang('login_password'); ?>:</label>
      	<?php echo form_input($password);?>
      </p>
      <p>
        <?php echo lang('login_remember'); ?>:
       <?php echo form_checkbox('remember', '1', FALSE);?>
      </p>
      <p><?php echo form_submit('submit', lang('login_login'));?></p>

      
    </div><?php echo form_close();?>
      <div class="step">
      <?=lang("site.or")?>
      </div>

      <?=anchor("auth/create_user", lang("login_create"), 'id="id_create_user" style="font-size:11px;"')?>
      <?=lang("site.why")?>
    
    <?php if(!isMobile()) {?>
    <div class="step">      
    <iframe src="http://www.facebook.com/plugins/like.php?href=http%3A%2F%2Fwap4.org&amp;layout=box_count&amp;show_faces=false&amp;width=100&amp;action=like&amp;colorscheme=light&amp;height=65"
            scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:100px; height:65px;" allowTransparency="true">
    </iframe>
    </div>
    <?php } ?>
</div>

