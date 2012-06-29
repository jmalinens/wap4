<?php
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
header("Content-type: text/html");
?><html>
<head>
<meta http-equiv="expires" content="0"/>
<meta http-equiv="pragma" content="no-cache"/>
<meta http-equiv="cache-control" content="no-cache, must-revalidate"/>
<link rel="stylesheet" href="/css/mobile.css"/>
<title><?=$_SERVER["SERVER_NAME"]?></title></head><body>
    <p>Youtube upload works but upload progress will show 0%.</p>
<?php if(!isset($fail_array) || empty($fail_array)) { ?>
<?=lang('mobile.upl_perc')?>: <?=$Upload_percents_complete?> %
<br/>
<?=lang('mobile.conv_perc')?>: <?=$Convert_percents_complete?> %
<br/><br/>
<?php if($Convert_percents_complete >= 98):?>
    <?=lang('mobile.download')?>: <br/>
    <a href="http://<?=$download_url?>">http://<?=$download_url?></a>
<?php else:?>
    <?=anchor('converter/mobile_status/'.$key.'/'.uniqid(), lang('mobile.reload'))?>
<?php endif;?>
<br/>
<?=lang("mobile.whyreload");?>
<br/><br/>
<?php } else { ?>
    <?php foreach($fail_array as $fail):?>
        <?=$fail?><br/>
    <?php endforeach;?>
    <br/>
    <?=anchor('converter/mobile_status/'.$key.'/'.uniqid(), lang('mobile.reload'))?>
<?php } ?>
<br/>
<a href="http://<?=$_SERVER["SERVER_NAME"]?>"><?=$this->config->item("mobile_host")?></a>
</body>
</html>