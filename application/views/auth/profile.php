<p><?=$this->lang->line('welcome')?>, <?=$username?>!</p>
<p><?= anchor("auth/edit_profile", $this->lang->line('profile_edit'))?></p>
<p><?=anchor("videos/video_list/0", lang('video.list'), array('class' => 'load_ajax'))?></p>
<p><a href="<?php echo site_url('auth/logout'); ?>"><?=$this->lang->line('logout')?></a></p>
