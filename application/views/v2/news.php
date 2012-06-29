<?php if(!isMobile()): ?>
<div id="location" xmlns:v="http://rdf.data-vocabulary.org/#">
    <div class="crumb">
        <span typeof="v:Breadcrumb">
        <a href="/<?=$this->lang->lang()?>" rel="v:url" property="v:title"><?=lang("main")?></a>
    </span> &gt; 
    <?php if(isset($archive)) { ?>
    <span typeof="v:Breadcrumb">
        <a href="/<?=$this->lang->lang()?>/news" rel="v:url" property="v:title"><?=lang("news")?></a>
    </span> &gt; 
    <span typeof="v:Breadcrumb">
        <?=lang("news.archive")?>
    </span>
    <?php } else { ?>
    <?=lang("news")?>
    <?php } ?>
    </div>
</div>
<div class="clear"></div>
<div id="news">
    <?php endif; ?>
<!--<?if(in_array("draugiem", $this->uri->segment_array())):?>
<style>.content_left, .content_right {height: 200px; font-size: 0.8em;}</style>
<?endif;?>-->
    <?php foreach ($results->result() as $id=>$jaunumi):?>
    <div class="content_<?=($id & 1) ? 'right' : 'left'?> relative">
        <div class="news_top">
            <div class="news_username">
                <?=$jaunumi->username?>
            </div>
            <div class="news_date">
                <?=$jaunumi->date?>
            </div>
        </div>
        <div class="news_content">
            <?=str_replace("&nbsp;","&#160;",$jaunumi->news)?>
        </div>
        <?php if($admin === true) echo anchor("news/delete_news/".$jaunumi->id, 'Delete!');?>
        <div class="perma">
        <?=anchor('news/archive/'.$jaunumi->id, "http://".$_SERVER["SERVER_NAME"]."/".$this->lang->lang()."/news/archive/".$jaunumi->id)?> (<?=lang('news.perma')?>) 
        </div>
    </div>
    <?php endforeach; ?>
</div>
    <div class="clear"></div>
<?php if($this->uri->segment(3) != "archive") echo $this->pagination->create_links(); ?>


