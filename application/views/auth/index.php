<div class='mainInfo'>

	
	<p><a href="<?php echo site_url('auth/create_user');?>"><?=lang('login_create')?></a></p>
        <p><?=anchor("auth/add_news", lang('add_news'))?></p>
        <p><?=anchor("auth/user_list/0", lang('user.list'), array('class' => 'load_ajax'))?></p>
        <p><?=anchor("videos/video_list/0", lang('video.list'), array('class' => 'load_ajax'))?></p>
        <p><?=anchor("converter/change_settings", "change settings", array('class' => 'load_ajax'))?></p>
        <p><?=anchor("auth/status/".$status, " &lt;&lt; ".$status, array('class' => 'load_ajax'))?></p>
        <p><a href="<?php echo site_url('auth/logout'); ?>"><?=$this->lang->line('logout')?></a></p>
</div>
