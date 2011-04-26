<?php
$bIsMobile = isMobile();
?>

<div class='mainInfo'>

<?php if(!$bIsMobile) { ?>
	<h1><?php echo $this->lang->line('login_create'); ?></h1>
	<p><?=$this->lang->line('login_enteracc')?></p>
	
	<div id="infoMessage"><?php echo $message;?></div>
	
      <?=form_open("auth/create_user")?>
      <p><?php echo form_input($username);?> <?=$this->lang->line('login_username')?>
      </p>
      
<p>
    <div class="gender"> 
        <?=$this->lang->line('login_male')?> 
        <?=form_radio('gender', 'male', TRUE)?> 
        <?=$this->lang->line('login_female')?> 
        <?=form_radio('gender', 'female', FALSE)?> 
    </div>
</p>
      <div class="gender2"><?=$this->lang->line('login_gender')?></div>
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

<?php } else {?>
      
<h1><?php echo $this->lang->line('login_create'); ?></h1>
<p>
    <?=$this->lang->line('login_enteracc')?>
</p>

<div id="infoMessage">
    <?php echo $message;?>
</div>

<?=form_open("auth/create_user")?>
<p>
    <?=$this->lang->line('login_username')?>:<br/>
    <?php echo form_input($username);?> 
</p>
      
<p>
    <?=$this->lang->line('login_gender')?>:<br/> 
    <?=$this->lang->line('login_male')?> 
    <?=form_radio('gender', 'male', TRUE)?> 
    <?=$this->lang->line('login_female')?> 
    <?=form_radio('gender', 'female', FALSE)?> 
</p>
      <div class="clear"></div>
          
<p>
    <?=$this->lang->line('login_birthday')?>:<br/> 
    <?=form_dropdown('day', $day, 15);?> - <?=form_dropdown('month',$month,'06')?> - <?=form_dropdown('year',$year, 2000)?>
</p>

<p>
    <?=$this->lang->line('login_email')?>:<br/>
    <?=form_input($email)?>
</p>

<p>
    <?=$this->lang->line('login_password')?>:<br/>
    <?=form_input($password)?>
</p>

<p>
    <?=$this->lang->line('login_confirm')?>:<br/>
    <?=form_input($password_confirm)?>
</p>
<?=$security?>

<p><?=form_submit('submit', $this->lang->line('login_create'))?></p>
      
<?php } ?>

<?php echo form_close();?>

</div>
