<div id="converter2">
    
	<div id="infoMessage">
            <?php echo $message;?>
        </div>
	
        <?php echo form_open_multipart("converter/convert/no_js", $attr);?>

        <?=$this->lang->line('ffmpeg.1st')?><br/>
        <input type="file" name="qqfile"/> <br/>
	
        <?=$this->lang->line('ffmpeg.2nd')?> <br/>
	<?=$this->lang->line('ffmpeg.video')?>: <br/>
	
	
	<?=$this->lang->line('ffmpeg.formats')?>:<br/>
	<select name="format" id="format">
            
	<?php foreach($formats as $format => $key): ?>
	<option value="<?=$format?>">
            <?=str_replace("-"," ",$format)?>
        </option>
	<?php endforeach; ?>
        
	</select>
        <br/>
        
	<!--cut length code-->
        <?=$this->lang->line('ffmpeg.cut')?><br/>
        <input type="checkbox" id="cut" name="cut" value="yes"/><br/>
        
        <div id="cut_field">
            <?=$this->lang->line('ffmpeg.start')?><br/>
            <input type="text" name="s_hh" id="s_hh" maxlength="2" size="2" value="00"/>
              : <input type="text" name="s_mm" id="s_mm" maxlength="2" size="2" value="00"/>
              : <input type="text" name="s_ss" id="s_ss" maxlength="2" size="2" value="00"/><br/>
            <?=$this->lang->line('ffmpeg.end')?><br/>

            <input type="text" name="e_hh" id="e_hh" maxlength="2" size="2" value="00"/>
              : <input type="text" name="e_mm" id="e_mm" maxlength="2" size="2" value="00"/>
              : <input type="text" name="e_ss" id="e_ss" maxlength="2" size="2" value="00"/><br/>
        </div>  
        
        <!--resize resolution code-->
        <!--
        <?=$this->lang->line('ffmpeg.resize')?><br/>
        <input type="checkbox" id="resize" name="resize" value="yes"/><br/>
        
        <div id="resize_field">
        <?=$this->lang->line('ffmpeg.width')?><br/>
        <input type="text" name="width" id="width" maxlength="4" size="4" value=""/><br/>
        <?=$this->lang->line('ffmpeg.heigth')?><br/>
        <input type="text" name="heigth" id="heigth" maxlength="4" size="4" value=""/>
        </div>  
        -->  
        <?=$this->lang->line('ffmpeg.descr')?>:
        <br/>
        <input type="text" name="apraksts" id="apraksts"/>
        <br/> 
        <input type="hidden" name="key" value="<?=$uniqid?>"/>
        
      <p><?php echo form_submit('submit', $this->lang->line('ffmpeg.saakt'));?></p>

      
    <?php echo form_close();?>


</div>