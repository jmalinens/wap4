<h1><?=lang('video.list')?></h1>

	
	<table cellpadding=0 cellspacing=10>
		<tr>
			<th>id</th>
			<th>users_id</th>
			<th>file_name</th>
			<th>date</th>
			<th>Delete?</th>
		</tr>
		<?php foreach ($results->result_array() as $video):?>
			<tr>
				<td><?php echo $video['id'];?></td>
				<td><?php echo $video['users_id'];?></td>
				<td><?php echo $video['file_name'];?></td>
				<td><?php echo $video['date'];?></td>
				<td><?php echo anchor("videos/delete_video/".$video['id'], 'Delete!');?></td>
			</tr>
		<?php endforeach;?>
	</table>
        
        <?php echo $this->pagination->create_links();?>