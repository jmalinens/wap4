<h1><?=$this->lang->line('login_reset')?></h1>
<p><?=$this->lang->line('login_resetdescr')?></p>

<div id="infoMessage"><?php echo $message;?></div>

<?php echo form_open("auth/forgot_password");?>

      <p><?=$this->lang->line('login_email')?>:<br />
      <?php echo form_input($email);?>
      </p>
      
      <p><?php echo form_submit('submit', $this->lang->line('login_recover'));?></p>
      
<?php echo form_close();?>