<div id="bar_convert">
<div id="percents">
</div>
</div>

<div id="converter2">

    <div class="step">
        <?=$this->lang->line('ffmpeg.2nd')?>
    </div>

    <?=form_open_multipart("converter/convert", $attr)?>
    <div class="format_field">
        <?=$this->lang->line('ffmpeg.formats')?>:<br/>

            <?php foreach($formats as $format => $key): ?>
            <?php if($format == "ipod-320x240-16-9"): ?>
                </div>
                <div class="format_field"><br/>
            <?php endif; ?>
            <input type="radio" name="format" value="<?=$format?>" id="format_<?=$format?>"/> 
            <label for="format_<?=$format?>"><?=str_replace("-"," ",$format)?></label><br/>
            <?php endforeach; ?>

    </div>
    <div class="format_field"><?=lang("quality")?>:<br/>
            <input type="radio" name="quality" value="high" id="quality_high"/> 
            <label for="quality_high"><?=lang("quality.high")?></label><br/>
            <input type="radio" name="quality" value="normal" id="quality_normal"/> 
            <label for="quality_normal"><?=lang("quality.normal")?></label><br/>
            <input type="radio" name="quality" value="low" id="quality_low"/> 
            <label for="quality_low"><?=lang("quality.low")?></label><br/>
    </div><div class="format_field">
    <?=$this->lang->line('ffmpeg.cut')?><br/>
    <input type="checkbox" id="cut" name="cut" value="yes"/><br/>
    <div class="cut_field">
        <?=$this->lang->line('ffmpeg.start')?><br/>
        <input type="text" name="s_hh" id="s_hh" maxlength="2" size="2" value="00"/>
          : <input type="text" name="s_mm" id="s_mm" maxlength="2" size="2" value="00"/>
          : <input type="text" name="s_ss" id="s_ss" maxlength="2" size="2" value="00"/><br/>
         <?=$this->lang->line('ffmpeg.end')?><br/>

        <input type="text" name="e_hh" id="e_hh" maxlength="2" size="2" value="00"/>
          : <input type="text" name="e_mm" id="e_mm" maxlength="2" size="2" value="00"/>
          : <input type="text" name="e_ss" id="e_ss" maxlength="2" size="2" value="00"/><br/>
    </div></div>

    <div class="format_field"><?=$this->lang->line('ffmpeg.descr')?>
        :
        <br/>
        <textarea name="apraksts" id="apraksts" rows="6"></textarea>
    </div>
    <div class="submit_field">
        <?=form_submit('submit', $this->lang->line('ffmpeg.saakt'), 'class="file_submit"')?>
    </div>

    <?=form_close()?>

</div>