<div class="media">
                <?php
                $attributes = array('class' => 'youtube_class', 'id' => 'youtube');
                echo form_open_multipart("/en/converter/upload_youtube",$attributes);
                ?>

                <input type="text" name="youtube" id="youtube_link"/>
                
                <!--<div class="apvalks">
                    <div class="youtube_submit">-->
                        <?php echo form_submit('submit', lang('upload.youtube'));?>
                    <!--</div>
                </div>-->
                
                <?php echo form_close();?>
                <blockquote>
                    <?=lang("upload.example")?>:
                    <div class="step">
                    http://www.youtube.com/watch?v=oyXEeZ4h0W4
                    </div>
                </blockquote>
</div>
<div id="youtube_uploaded"></div>

<div id="bar_youtube">
    <div id="youtube_uploaded2">
    </div>
</div>