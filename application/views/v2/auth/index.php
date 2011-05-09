
<style>#login {height: 14px;}</style><?
echo anchor("auth/create_user", lang('login_create'));
echo " &#160; ";
echo anchor("auth/add_news", lang('add_news'));
echo " &#160; ";
echo anchor("auth/user_list/0", lang('user.list'));
echo " &#160; ";
echo anchor("videos/video_list/0", lang('video.list'));
echo " &#160; ";
echo anchor("converter/change_settings", "change settings");
echo " &#160; ";
echo anchor("auth/status/".$status, $status);
echo " &#160; ";
echo anchor("auth/logout", lang('logout'));
