<!DOCTYPE html>
<html lang="<?=strtolower($this->lang->lang())?>">
<head>
<title><?=getTitle()?> - <?=lang('title')?></title>
<meta name="description" content="<?=(isset($meta)) ? lang('meta') : lang('title.meta')?>" />
<meta charset="utf-8"/>
<link rel="stylesheet" href="/css/new_style.min.css"/>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>

<?php if($this->uri->segment(2) == 'news'): ?>
<script src="/js/jquery.equalheights.js"></script>
<script>$(function(){ $('#news').equalHeights(); });</script>
<?php endif; ?>

<script src="/js/fileuploader.min.js"></script>
<?php if(in_array("index", $this->uri->segment_array())): ?>
<link rel="canonical" href="/<?=str_replace("index", "", $this->uri->uri_string())?>"/>
<?php endif; ?>
<script></script>

<script>
function getFormat(format) {
    <?php foreach($extensions as $name => $ext):?>
    if(format == "<?=$name?>") {
        format = "<?=$ext?>";
    }
    <?php endforeach;?>
    return format;
};
//end function getFormat()
var opt_uniqid = '<?=$uniqid?>';
var opt_allowed = [<?=$allowed?>];
var opt_max = '<?=$max?>';
var opt_done = '<?=lang('upload.done')?>';
var opt_download = '<?=lang('mobile.download')?>';
var opt_file = '<?=lang('upload.file')?>';

var opt_fail_extension = '<?=lang("fail.extension")?>';
var opt_fail_size = '<?=lang("fail.size")?>';
var opt_fail_link = '<?=lang("link.incorrect")?>';
var opt_upload_wait = '<?=lang("upload.wait")?>';
var opt_upload_sorry = '<?=lang("upload.sorry")?>';
var opt_mobile_download = '<?=lang("mobile.download")?>';

var opt_filename;
var opt_upload_extension;
var tube_title;
var opt_link_type;
</script>

<script src="/js/converter_new.js"></script>

<!--<script src="/<?=$this->lang->lang()?>/js"></script>-->
</head>
<body>
<div id="top_line">
    <!--Youtube upload works but upload progress will show 0%.-->
</div>
<div id="container">
    <div id="login">
        <?php if (!$this->ion_auth->logged_in())
            $this->load->view("v2/auth/login");
        elseif (!$this->ion_auth->is_admin())
            $this->load->view('v2/auth/profile', $this->datb);
        else
            $this->load->view('v2/auth/index', $this->data);
        ?>
    </div>

<div id="header">
	<div class="header_title">
		<h1>WAP4.org</h1>
		<h2><?=lang('title.header')?></h2>
	</div>
	<div class="header_ad">
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- wap4 new top -->
<ins class="adsbygoogle"
     style="display:inline-block;width:468px;height:60px"
     data-ad-client="ca-pub-2212583322739900"
     data-ad-slot="1941865515"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>
	</div>
</div>
<div id="top_navigation">
        <ul id="nav_list">
            <?php foreach ($navigation as $nav): ?>
            <li>
            <?=anchor($nav, lang($nav), (in_array($nav, $this->uri->segment_array())) ? 'class="selected"': '')?>
            </li>
            <?php endforeach; ?>
        </ul>
    <ul id="language_list">
		<li class="language"><?=lang("lang.languages")?>:
		</li>
        <li<?=($this->lang->lang() == "en") ? ' class="selected"' : ''?>>
            <a href="/<?=$this->lang->switch_uri('en')?>" title="<?=lang('lang.english')?>"><div class="en"></div></a>
        </li>
        <li<?=($this->lang->lang() == "ru") ? ' class="selected"' : ''?>>
            <a href="/<?=$this->lang->switch_uri('ru')?>" title="<?=lang('lang.russian')?>"><div class="ru"></div></a>
        </li>
        <li<?=($this->lang->lang() == "lv") ? ' class="selected"' : ''?>>
            <a href="/<?=$this->lang->switch_uri('lv')?>" title="<?=lang('lang.latvian')?>"><div class="lv"></div></a>
        </li>
    </ul>
</div>
<div class="pad_top"></div>
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- wap4 new big -->
<ins class="adsbygoogle"
     style="display:inline-block;width:728px;height:90px"
     data-ad-client="ca-pub-2212583322739900"
     data-ad-slot="3418598719"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>
	<div id="content">