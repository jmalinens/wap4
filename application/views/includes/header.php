<?php
$bIsMobile = isMobile();
if($bIsMobile) {
    $sMetaTitle="mmeta";
    header("Content-type: application/xhtml+xml");
    echo'<?xml version="1.0" encoding="UTF-8"?>';?>
<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.2//EN"
"http://www.openmobilealliance.org/tech/DTD/xhtml-mobile12.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?=$this->lang->lang()?>">
<?php } else {
    $sMetaTitle="meta";
    ?><!DOCTYPE html>
<html lang="<?=strtolower($this->lang->lang())?>">
<?php } ?>
<head>
<title><?=getTitle()?> - <?=(!$bIsMobile) ? lang('title') : lang('title.mobile')?></title>
<meta name="description" content="<?=(isset($meta)) ? lang('meta.'.$meta) : lang('title.'.$sMetaTitle)?>" />
<?php if(!$bIsMobile){ ?>
<meta charset="utf-8"/>
<link rel="stylesheet" href="<?php echo base_url();?>css/style.css"/>
<link rel="stylesheet" href="<?php echo base_url();?>css/css3.css"/>
<link rel="stylesheet" href="<?php echo base_url();?>css/fileuploader.css"/>
<link rel="stylesheet" href="<?php echo base_url();?>css/le-frog/jquery-ui-1.8.9.custom.css"/>
<?php if(in_array("index", $this->uri->segment_array())) {?>
<link rel="canonical" href="http://wap4.org/<?=str_replace("index", "", $this->uri->uri_string())?>"/>
<?php } ?>
<script src="<?php echo base_url();?>js/fileuploader.js"></script>
<script src="<?php echo base_url();?>js/jquery.min.js"></script>
<script src="<?php echo base_url();?>js/jquery-ui-1.8.9.custom.min.js"></script>

<?=$this->load->view('js/wap4', $this->data)?>
<?php } else { ?>
<link rel="stylesheet" href="<?php echo base_url();?>css/mobile-optimized.css"/>
<?php } ?>
</head>
<body> 
<div class="container"> 
<?php if($bIsMobile) {
?>
<a href="/wap4.jad" title="<?=lang('bookmark.us')?>"><?=lang('bookmark.us')?></a><br/>
<?php
include_once "/home/wap4/public_html/mobgold_m_wap4.php";
include_once "/home/wap4/public_html/MkhojAd_m_wap4_simple.php";
echo "<br/>Ad: <a href=\"http://topwapi.com/?id=mwap4org\">New Downloads</a><br/>";
?>
&gt; <a href="/<?=$this->lang->switch_uri('en')?>" title="<?=lang('lang.english')?>"><?=lang('lang.english')?></a> 
&gt; <a href="/<?=$this->lang->switch_uri('ru')?>" title="<?=lang('lang.russian')?>"><?=lang('lang.russian')?></a> 
&gt; <a href="/<?=$this->lang->switch_uri('lv')?>" title="<?=lang('lang.latvian')?>"><?=lang('lang.latvian')?></a> 
<br/>
<?php
} ?>
<div class="header">
	<h1>wap4.org - <?=(!$bIsMobile) ? lang('title.header') : lang('title.mheader')?></h1>
        <?php if(!$bIsMobile):?>
        <h3><?=lang('title.h3')?></h3>
        <?php endif;?>
        <ul id="nav">
            <?php
            if($this->router->class != "welcome" && $bIsMobile) {
            ?>
                <li>
                    <?=anchor("/".$this->lang->lang(), lang('main'))?>
                </li>
            <?php
            } else { 
            foreach ($navigation as $nav) {
            ?>
                <li>
                    <?=anchor($nav, lang($nav), $nav =="news"? 'class="id_converter"': 'class="load_ajax"')?>
                </li>
            <?php
            }
            }
            ?>
        </ul>
        <div class="clear"></div>

<?php if(!$bIsMobile) {?>
<div class="languages_apvalks">
<div class="languages">
    <dl class="dropdown">
        <dt><a href="#" id="langSelector"><span><?=lang('lang.languages')?>...</span></a></dt>
        <dd>
            <ul>
                <li>
                    <a href="/<?=$this->lang->switch_uri('en')?>"><?=lang('lang.english')?>
                        <span class="value"><?=lang('lang.english')?></span></a>
                    <div class="karogs"><img class="flag" src="/img/UK.png" alt="GBR" /></div><div class="clear"></div>
                </li>
                <li>
                    <a href="/<?=$this->lang->switch_uri('ru')?>"><?=lang('lang.russian')?>
                        <span class="value"><?=lang('lang.russian')?></span></a>
                    <div class="karogs"><img class="flag" src="/img/Russia.png" alt="RUS" /></div><div class="clear"></div>
                </li>
                <li>
                    <a href="/<?=$this->lang->switch_uri('lv')?>"><?=lang('lang.latvian')?>
                        <span class="value"><?=lang('lang.latvian')?></span></a>
                    <div class="karogs"><img class="flag" src="/img/Latvia.png" alt="LAT" /></div><div class="clear"></div>
                </li>
            </ul>
        </dd>
    </dl>
</div>
</div>
<?php } ?>
</div>
<div class="wrapper">
<div class="content">
<?php if(!$bIsMobile) {?>
<script type="text/javascript"><!--
google_ad_client = "ca-pub-2212583322739900";
/* wap4.org test */
google_ad_slot = "9205790398";
google_ad_width = 468;
google_ad_height = 60;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
<?php }?>