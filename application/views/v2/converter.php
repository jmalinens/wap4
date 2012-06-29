<div class="content_left">
    <div id="content_left_apvalks">
        <div class="left_top">
            <h3><?=$this->lang->line('ffmpeg.1st')?></h3>
            <div id="demo">
                <noscript>
                        <?=form_open_multipart("/ffmpeg/val/server/php.php")?>
                        <input type="file" name="qqfile"/>
                        <p>
                            <?=form_submit('submit', lang('upload.file'))?>
                        </p>
                          <?=form_close()?>
                </noscript>
            </div>
            <p><?=lang("upload.formats")?>:<br/>
                <em><?=str_replace("'"," ", $allowed)?></em><br/><br/>
                <?=lang("upload.max_size")?>:<br/>
                <em><?=$max/1024?> MB</em>
            </p>
        </div>
<ul id="separate-list"></ul>
    <div class="convert_options" id="converter_left">
        <div id="download"></div>
    </div>
    </div>
</div>

<div class="content_right">
   <div id="content_right_apvalks">
        <div class="right_top">
            <?php $attributes = array('class' => 'youtube_class', 'id' => 'youtube'); ?>
            <h3><?=$this->lang->line('ffmpeg.1st22')?></h3>
                <?=form_open_multipart("/en/converter/upload_youtube",$attributes)?>
                <div class="link">
                    <input type="text" name="link" id="youtube_link" value=""/>
                    <?=form_submit('submit', lang('upload.youtube'), 'class="file_submit"')?>
                </div>
                <?=form_close()?>
            <div class="clear"></div>
            <span class="lighter">Youtube example:</span><br/>
            <span class="bigger">http://www.youtube.com/watch?v=g2Xqo65uNCA <br/>
                (<a style="font-size: 0.6em; display: inline; padding: 4px;"
                    href="http://www.bouncycastle-lgk.co.uk">bouncycastle-lgk.co.uk</a>)</span>
            <br/><br/>
            <span class="lighter">Vimeo example:</span><br/>
            <span class="bigger">http://vimeo.com/21144035</span>
        </div>
        <div id="youtube_uploaded"></div>

        <div id="bar_youtube">
            <div id="youtube_uploaded2">
            </div>
        </div>
    <div class="convert_options" id="converter_right">

        <div id="download"></div>
    </div>

   </div>
</div>
<div class="clear"></div>
<?php
$this->c_opt["attr"]    = $attr;
$this->c_opt["formats"] = $formats;
$this->load->view("v2/converter_options", $this->c_opt);