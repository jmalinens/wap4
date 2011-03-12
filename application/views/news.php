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
            <?=$jaunumi->news?>
        </div>
        <?php if(isset($admin)) echo anchor("news/delete_news/".$jaunumi->id, 'Delete!');?>
        <div class="perma">
        <?=anchor('news/archive/'.$jaunumi->id, "http://wap4.org/".$this->lang->lang()."/news/archive/".$jaunumi->id)?> (<?=lang('news.perma')?>) 
        </div>

    </li>

    <?php endforeach; ?>
</ul>
    
<?php if($this->uri->segment(3) != "archive") echo $this->pagination->create_links(); ?>


