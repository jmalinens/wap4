<div class='mainInfo'>

	<p><?=$this->lang->line('profile_edit')?></p>
	
	
    <?php echo form_open("auth/edit_profile")?>
     
        
      
      <p><?=$this->lang->line('login_male')?> <?=form_radio('gender', '0', TRUE)?> <?=$this->lang->line('login_female')?> <?=form_radio('gender', '1', FALSE)?> <span class="gender"><?=$this->lang->line('login_gender')?></span>
      
          
      <p>
      <?=form_dropdown('day', $day, 15);?> - <?=form_dropdown('month',$month,'06')?> - <?=form_dropdown('year',$year, 2000)?>  <?=$this->lang->line('login_birthday')?> 
      </p>
      
      <p><?=form_input($email)?> <?=$this->lang->line('login_email')?>
      </p>
      
      <p><?=form_input($old_password)?> <?=$this->lang->line('login_oldpasswd')?>
      </p>
      
      <p><?=form_input($password)?> <?=$this->lang->line('login_newpasswd')?>
      </p>
      
      <p><?=form_input($password_confirm)?> <?=$this->lang->line('login_confirm')?>
        
        
      <p>
      	<input type=checkbox name="reset_password"> <label for="reset_password"><?=$this->lang->line('login_changepasswd')?></label>
      </p>
      
      <?php echo form_input($user_id);?>
      <p><?php echo form_submit('submit', $this->lang->line('profile_update'));?></p>

      
    <?php echo form_close();?>

</div>
