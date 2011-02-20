<div class='mainInfo'>

	<div class="pageTitle">Delete News</div>
    <div class="pageTitleBorder"></div>
	<p>Are you sure you want to delete news with id: '<?php echo $results[0]['id']; ?>'</p>
	
    <?php echo form_open("news/delete_news/".$results[0]['id']);?>
    	
      <p>
      	<label for="confirm">Yes:</label>
		<input type="radio" name="confirm" value="yes" checked="checked" />
      	<label for="confirm">No:</label>
		<input type="radio" name="confirm" value="no" />
      </p>
      
      <?php echo form_hidden($csrf); ?>
      <?php echo form_hidden(array('id'=>$results[0]['id'])); ?>
      
      <p><?php echo form_submit('submit', 'Submit');?></p>

    <?php echo form_close();?>

</div>