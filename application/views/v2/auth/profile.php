<style>#login {height: 14px;} .top_links{list-style: none;height: 22px;margin-top:-4px;}
    .top_links, .top_links li{float:left;padding-left:5px; padding-right:5px;}
    .top_links li {height: 22px;}
    .top_links li:hover{background-color: #c11f1f;}
    .top_links a{display:inline;}
    .welcome{float:left;margin-top:-4px;}</style><div class="welcome"><?=
lang('welcome')?>, <?=$username?>! &#160;</div>
<ul class="top_links"><li><?=anchor("auth/edit_profile", $this->lang->line('profile_edit'))?></li>
<li><?=anchor("videos/video_list/0", lang('video.list'))?></li>
<li><?=anchor("auth/logout", lang('logout'))?></li></ul>