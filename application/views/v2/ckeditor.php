

    <?php echo form_open("auth/add_news", $attr);?>
	
	
    <textarea name="content" id="content" ><p>Ziņas saturs šeit</p></textarea>
    <?php echo display_ckeditor($ckeditor); ?>
      
      
      <p><?php echo form_submit('submit', 'pievienot');?></p>

      
    <?php echo form_close();?>


