

<div class='mainInfo'>

	<h1><?php echo $this->lang->line('login_create'); ?></h1>
	<p><?=$this->lang->line('login_enteracc')?></p>
	
	<div id="infoMessage"><?php echo $message;?></div>
	
      <?=form_open("auth/create_user")?>
      <p><?php echo form_input($username);?> <?=$this->lang->line('login_username')?>
      </p>
      
      <p><div class="gender"> 
 <?=$this->lang->line('login_male')?> 
 <?=form_radio('gender', 'male', TRUE)?> 
 <?=$this->lang->line('login_female')?> 
 <?=form_radio('gender', 'female', FALSE)?> 
      </div> <div class="gender2"><?=$this->lang->line('login_gender')?></div>
      <div class="clear"></div>
      
          
      <p>
      <?=form_dropdown('day', $day, 15);?> - <?=form_dropdown('month',$month,'06')?> - <?=form_dropdown('year',$year, 2000)?>  <?=$this->lang->line('login_birthday')?> 
      </p>
      
      <p><?=form_input($email)?> <?=$this->lang->line('login_email')?>
      </p>
      
      <p><?=form_input($password)?> <?=$this->lang->line('login_password')?>
      </p>
      
      <p><?=form_input($password_confirm)?> <?=$this->lang->line('login_confirm')?>
      </p>
      <?=$security?>
      
      <p><?=form_submit('submit', $this->lang->line('login_create'))?></p>

      
    <?php echo form_close();?>

</div>
