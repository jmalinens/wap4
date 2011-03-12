<div id="infoMessage"><?php echo $message;?></div>

<?php echo form_open("converter/change_settings");?>

      <p>file_size_unregistered:<br />
      <?php echo form_input($unregistered);?>
      </p>
      
      <p>file_size_registered:<br />
      <?php echo form_input($registered);?>
      </p>
     
      <p><?php echo form_submit('submit', 'change settings');?></p>
      
<?php echo form_close();?>
