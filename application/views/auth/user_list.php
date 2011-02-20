<h1><?=lang('user.list')?></h1>
	
	
	<table cellpadding=0 cellspacing=10>
		<tr>
			<th><?=lang('login_gender')?></th>
			<th><?=lang('login_birthday')?></th>
			<th><?=lang('login_email')?></th>
			<th>Group</th>
			<th>Status</th>
		</tr>
		<?php foreach ($results->result_array() as $user):?>
			<tr>
				<td><?php echo $user['gender'] == "0"? lang('login_male'):lang('login_female');?></td>
				<td><?php echo $user['birthday']?></td>
				<td><?php echo $user['email'];?></td>
				<td><?php echo $user['description'];?></td>
				<td><?php echo ($user['active']) ? anchor("auth/deactivate/".$user['id'], 'Active') : anchor("auth/activate/". $user['id'], 'Inactive');?></td>
			</tr>
		<?php endforeach;?>
	</table>
        
        <?php echo $this->pagination->create_links();?>
