<?php if(!isMobile()): ?>
<div id="location" xmlns:v="http://rdf.data-vocabulary.org/#">
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
<?php endif; ?>
<ul class="news">
    <?php foreach ($results->result() as $jaunumi):?>
    <li>
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
        <hr/>
    </li>
    <?php endforeach; ?>
</ul>
<?php if($this->uri->segment(3) != "archive") echo $this->pagination->create_links(); ?>


