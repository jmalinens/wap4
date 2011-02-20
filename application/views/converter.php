<div class="step">
	<?=$this->lang->line('ffmpeg.1st')?>
</div>
	<div id="demo">
                
            <noscript>
                <?php echo form_open_multipart("/ffmpeg/val/server/php.php");?>

                <input type="file" name="qqfile"/>

                <p><?php echo form_submit('submit', lang('upload.file'));?></p>

                <?php echo form_close();?>
            </noscript>   
        </div>
<div class="step">
	<?=$this->lang->line('ffmpeg.1st2')?>
</div>
<?=youtube()?>
    

<ul id="separate-list"></ul>
        
<script type="text/javascript">
$(document).ready(function(){
    
    function getFormat(format) {
        if(format == "mp3-128kbps") {
            format = "mp3";
        }
        else if(format == "amr-mono-12kbps") {
            format = "amr";
        }
        else if(format == "3gp-176x144-amr") {
            format = "3gp";
        } else {
            format = "mp4";
        }

        return format;
    };
    
       $('#cut_field').hide();
       
       
       $('#cut').attr("unchecked");
       $('#cut').change(function () {
        if ($(this).attr("checked")) {
            $('#cut_field').show();
        }
            else
        {
            $('#cut_field').hide();
        }
        });
        
        $('#resize_field').hide();
        $('#resize').attr("unchecked");
        $('#resize').change(function () {
        if ($(this).attr("checked")) {
            $('#resize_field').show();
        }
            else
        {
            $('#resize_field').hide();
        }
        });
     var test;
     
     

     
     
     
     
     $('#conv').submit(function() {
     $('#converter2').hide("slow");
     $('.media').hide("slow");
     $('#bar_convert').show("slow");
     
     $("#percents").progressbar({value: 0});
	
         	 test = $.ajax({
			url: "/en/converter/change_body/" +faila_nosaukums,
			async: false
			}).responseText;
			
  refreshIntervalId = setInterval(function(){
      $("#percents").load("/en/converter/statuss/<?=$uniqid?>", function(response, status, xhr) {
      if (status == "error") {
        var msg = "Sorry but there was an error: ";
        $("#error").html(msg + xhr.status + " " + xhr.statusText);
        
        clearInterval(refreshIntervalId);
        
      }
      else if (response > 100)
      {
          $('#percents').html("100");
          $("#percents").append("%");
          $("#percents").progressbar({value: 100});
          $('#percents').width(300);
          var fails          = test+"-<?=$uniqid?>." + getFormat($('#format').val());
          var saite_uz_failu = '<a href="/files/converted/' + fails + '">' + fails + '</a>';

          $('#download').html(saite_uz_failu);
          $('#download').show("slow");
          clearInterval(refreshIntervalId);
      }
      else
      {
           $('#percents').html(response);
           $('#percents').width(response*3)
           $("#percents").append("%");
           $("#percents").progressbar({value: response});
		   
           if(response == 100)
               {
                 var fails          = test+"-<?=$uniqid?>." + getFormat($('#format').val());
                 var saite_uz_failu = '<a href="/files/converted/' + fails + '">' + fails + '</a>';
                 $('#percents').width(300);
                 $('#download').html(saite_uz_failu);
                 $('#download').show("slow");
                 clearInterval(refreshIntervalId); 
               }

      }
    });
},500);

        $.get("/en/converter/convert/<?=$uniqid?>/" + $('#format').val()+"/"+faila_nosaukums+"/"+$('input:checkbox:checked').val()+"/"+$('#s_hh').val()+"/"+$('#s_mm').val()+"/"+$('#s_ss').val()+"/"+$('#e_hh').val()+"/"+$('#e_mm').val()+"/"+$('#e_ss').val()+"/"+$('#apraksts').val()+"/"+$('#resize').val()+"/"+$('#width').val()+"/"+$('#heigth').val(), {
                input_file: faila_nosaukums,
                caur_ajax:   "true",
                key:    "<?=$uniqid?>",
                format: $('#format').val(),    //format to which convert
                cut:    $('input:checkbox:checked').val(),       //cut?
                s_hh:   $('#s_hh').val(),      //start hours
                s_mm:   $('#s_mm').val(),      //start minutes
                s_ss:   $('#s_ss').val(),      //start seconds
                e_hh:   $('#e_hh').val(),      //end hours
                e_mm:   $('#e_mm').val(),      //end minutes
                e_ss:   $('#e_ss').val(),      //end seconds
                descr:  $('#apraksts').val(),  //description
                resize: $('#resize').val(),    //resize?
                width:  $('#width').val(),     //video width
                heigth: $('#heigth').val()     //video heigth
               },

           function(data){
             //alert("Data Loaded: " + data);
             clearInterval(refreshIntervalId);
             $('#percents').html("100%"); //uztaisaam 100% arii tad, ja video tiek apgriezts
             $('#percents').width(300);

             var fails          = data + "-<?=$uniqid?>." + getFormat($('#format').val());
             var saite_uz_failu = '<a href="/files/converted/' + fails + '">' + fails + '</a>';

             $('#download').html(saite_uz_failu);
             $('#download').show("slow");
        });
      
       return false;
});
   
});

</script>

<div id="bar_convert">
<div id="percents">
</div>
</div>

    <div id="converter2">


	<div class="step">
	<?=$this->lang->line('ffmpeg.2nd')?>
        </div>
	<div id="infoMessage"><?php echo $message;?></div>
	
        <?php echo form_open_multipart("converter/convert", $attr);?>	
	
	<?=$this->lang->line('ffmpeg.formats')?>:<br/>
	<select name="format" id="format">
            
	<?php foreach($formats as $format => $key): ?>
	<option value="<?=$format?>"><?=str_replace("-"," ",$format)?></option>
	<?php endforeach; ?>
        
	</select><br/>
        
        <!--
        <?=$this->lang->line('ffmpeg.formats')?>:<br/>
	<select name="preset" id="preset">
            
	<?php /*print_r($presets[0]);*/ foreach($presets as $preset => $key): ?>
	<option value="<?=$preset?>"><?=$preset?></option>
	<?php endforeach; ?>
       
	</select><br/>
	-->
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
      
      
      <p><?php echo form_submit('submit', $this->lang->line('ffmpeg.saakt'));?></p>

      
    <?php echo form_close();?>



</div>

<div id="download"></div>