<?php header("Content-type: text/html"); ?>
<!DOCTYPE html>
<html lang="<?=$this->lang->lang()?>">
<head>
<title><?=getTitle()?> - <?=lang('title.mobile')?></title>
<meta name="description" content="<?=(isset($meta)) ? lang('meta.'.$meta) : lang('title.mmeta')?>" />
<meta name="viewport" content="width=device-width,initial-scale=1,initial-zoom=1"/>
<meta name="HandheldFriendly" content="true" />
<link rel="stylesheet" href="<?php echo base_url();?>css/mobile-optimized.css"/>
</head>
<body>
<div class="container"> 
<?php if (!$this->ion_auth->logged_in()): ?>
<a href="/<?=$this->lang->lang()?>/auth/login"><?=lang('login_pleaselog')?> / <?=lang('login_create')?></a><br/>
<?php else: ?>
<a href="/<?=$this->lang->lang()?>/videos/video_list/0"><?=lang('video.list')?> (test)</a><br/>
<?php endif; ?>
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- wap4 new mobile header -->
<ins class="adsbygoogle"
     style="display:inline-block;width:320px;height:50px"
     data-ad-client="ca-pub-2212583322739900"
     data-ad-slot="6372065116"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>
<!--<a href="/wap4.jad" title="<?=lang('bookmark.us')?>"><?=lang('bookmark.us')?></a><br/>-->
<?php include_once dirname(__FILE__)."/../../../../MkhojAd_m_wap4_simple.php"; ?>
<br/>
<a href="http://topwapi.com/?id=mwap4org">New Downloads</a><br/>
&gt; <a href="/<?=$this->lang->switch_uri('en')?>" title="<?=lang('lang.english')?>"><?=lang('lang.english')?></a> 
&gt; <a href="/<?=$this->lang->switch_uri('ru')?>" title="<?=lang('lang.russian')?>"><?=lang('lang.russian')?></a> 
&gt; <a href="/<?=$this->lang->switch_uri('lv')?>" title="<?=lang('lang.latvian')?>"><?=lang('lang.latvian')?></a> 
<br/>
<div class="header">
	<h1>wap4.org - <?=lang('title.mheader')?></h1>
         <ul id="nav">
            <?php if($this->router->class != "welcome"): ?>
             
            <li>
                <?=anchor("/".$this->lang->lang(), lang('main'))?>
            </li>
             
            <?php else: ?>
             
                <?php foreach ($navigation as $nav): ?>
                <li>
                <?=anchor($nav, lang($nav))?>
                </li>
                <?php endforeach; ?>
                
            <?php endif; ?>
         </ul>
        <div class="clear"></div>
</div>
<div class="wrapper">
<div class="content">