<h1><?=$this->lang->line('login_passwdchange')?></h1>

<div id="infoMessage"><?php echo $message;?></div>

<?php echo form_open("auth/change_password");?>

      <p><?=$this->lang->line('login_oldpasswd')?>:<br />
      <?php echo form_input($old_password);?>
      </p>
      
      <p><?=$this->lang->line('login_newpasswd')?>:<br />
      <?php echo form_input($new_password);?>
      </p>
      
      <p><?=$this->lang->line('login_newpasswdag')?>:<br />
      <?php echo form_input($new_password_confirm);?>
      </p>
      
      <?php echo form_input($user_id);?>
      <p><?php echo form_submit('submit', $this->lang->line('login_changepasswd'));?></p>
      
<?php echo form_close();?>
