<?php $bIsMobile = isMobile(); ?>
<div id="converter2">
    
	<div id="infoMessage2">
            <?php echo $message;?>
        </div>
	
        <?php echo form_open_multipart("converter/convert/no_js", $attr);?><div>
        <!-- file upload-->
        <?=$this->lang->line('ffmpeg.1st')?><br/>
        <?=lang("upload.formats")?>: <em><?=str_replace("'"," ", $allowed)?></em><br/>
        <?=lang("upload.max_size")?>: <?=$max/1024?> MB<br/>
        <input type="file" name="qqfile"/> <br/>
        
        Link upload (Youtube, Google, Yahoo, Dailymotion, Vimeo, Metacafe, Direct link):<br/>
        <input type="text" name="link"<?=(!$bIsMobile) ? ' id="youtube_link"' : ''?>/><br/>
        <?php
        /*
        <!-- youtube link-->
	<?=$this->lang->line('ffmpeg.1st2')?>:<br/>
        <input type="text" name="youtube"<?=(!$bIsMobile) ? ' id="youtube_link"' : ''?>/><br/>
        
        <!-- vimeo link-->
	<?=$this->lang->line('ffmpeg.1st3')?>:<br/>
        <input type="text" name="vimeo"<?=(!$bIsMobile) ? ' id="vimeo_link"' : ''?>/><br/>
        
        <!-- direct link-->
	<?=$this->lang->line('ffmpeg.1st4')?>:<br/>
        <input type="text" name="direct"<?=(!$bIsMobile) ? ' id="direct_link"' : ''?>/><br/>
	*/
        ?>
        
        <?=$this->lang->line('ffmpeg.2nd')?> <br/>
	<?=$this->lang->line('ffmpeg.video')?>: <br/>
	
        <div class="format_field"><?=lang("quality")?>:<br/><?
                ?><input type="radio" name="quality" value="high" id="quality_high"/> <?
                ?><label for="quality_high"><?=lang("quality.high")?></label><br/><?
                ?><input type="radio" name="quality" value="normal" id="quality_normal"/> <?
                ?><label for="quality_normal"><?=lang("quality.normal")?></label><br/><?
                ?><input type="radio" name="quality" value="low" id="quality_low"/> <?
                ?><label for="quality_low"><?=lang("quality.low")?></label><br/><?
        ?></div>
        
	
	<?=$this->lang->line('ffmpeg.formats')?>:<br/>
	<select name="format"<?=(!$bIsMobile) ? ' id="format"' : ''?>>
            
	<?php foreach($formats as $format => $key): ?>
	<option value="<?=$format?>"<?=($format == "mp3-128kbps") ? ' selected="selected"' : ''?>>
            <?=str_replace("-"," ",$format)?>
        </option>
	<?php endforeach; ?>
        
	</select>
        <br/>
        
	<!--cut length code-->
        <?=$this->lang->line('ffmpeg.cut')?><br/>
        <input type="checkbox" id="cut" name="cut" value="yes"/><br/>
        
        <div id="cut_field">
            <?php if(!$bIsMobile) {?>
            <?=$this->lang->line('ffmpeg.start')?><br/>
            <input type="text" name="s_hh" id="s_hh" maxlength="2" size="2" value="00"/>
              : <input type="text" name="s_mm" id="s_mm" maxlength="2" size="2" value="00"/>
              : <input type="text" name="s_ss" id="s_ss" maxlength="2" size="2" value="00"/><br/>
            <?=$this->lang->line('ffmpeg.end')?><br/>

            <input type="text" name="e_hh" id="e_hh" maxlength="2" size="2" value="00"/>
              : <input type="text" name="e_mm" id="e_mm" maxlength="2" size="2" value="00"/>
              : <input type="text" name="e_ss" id="e_ss" maxlength="2" size="2" value="00"/><br/>
              <?php } else { ?>
            <input type="text" name="s_hh" maxlength="2" size="2" value="00"/>
              : <input type="text" name="s_mm" maxlength="2" size="2" value="00"/>
              : <input type="text" name="s_ss" maxlength="2" size="2" value="00"/><br/>
            <?=$this->lang->line('ffmpeg.end')?><br/>

            <input type="text" name="e_hh" maxlength="2" size="2" value="00"/>
              : <input type="text" name="e_mm" maxlength="2" size="2" value="00"/>
              : <input type="text" name="e_ss" maxlength="2" size="2" value="00"/><br/>
              <?php } ?>
        </div>  
        
        <?=$this->lang->line('ffmpeg.descr')?>:
        <br/>
        <input type="text" name="apraksts" id="apraksts"/>
        <br/> 
        <input type="hidden" name="key" value="<?=$uniqid?>"/>
        
        <p><?php echo form_submit('submit', $this->lang->line('ffmpeg.saakt'));?></p>

        </div><?php echo form_close();?>


</div>