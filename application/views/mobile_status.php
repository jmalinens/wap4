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
<?php
if(isset($warning))
    echo "<p>$warning</p>\n";

if($Upload_percents_complete == 0 && $Convert_percents_complete == 100) {

    echo "Maybe there is an error. Please reload after a while to get error message.<br/>\n";
    echo anchor('converter/mobile_status/'.$key.'/'.uniqid(), lang('mobile.reload'));

} elseif(!isset($fail_array) || empty($fail_array)) { ?>
<?=lang('mobile.upl_perc')?>: <?=$Upload_percents_complete?> %
<br/>
<?=lang('mobile.conv_perc')?>: <?=$Convert_percents_complete?> %
<br/><br/>
<?php if($Convert_percents_complete >= 98): ?>
    <?=lang('mobile.download')?>: <br/>
    <a href="http://<?=$download_url?>">http://<?=$download_url?></a><br/><br/>
<?php include_once "/home/wap4/public_html/mobgold_m_wap4.php";?><br/>
<?php include_once "/home/wap4/public_html/MkhojAd_m_wap4_simple.php";?><br/>
<?php
else:
    echo anchor('converter/mobile_status/'.$key.'/'.uniqid(), lang('mobile.reload'));
endif;
?>
<br/>
<?=lang("mobile.whyreload");?>
<br/><br/>
<?php } else { ?>
    <?php foreach($fail_array as $fail):?>
        <?=$fail?><br/>
    <?php endforeach;?>
    <br/>
    <?=anchor('converter/mobile_status/'.$key.'/'.uniqid(), lang('mobile.reload'))?>
<?php } 
?><br/>
<a href="http://<?=$_SERVER["SERVER_NAME"]?>"><?=$this->config->item("mobile_host")?></a>
</body>
</html>