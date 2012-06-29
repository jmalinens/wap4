<script>
var faila_nosaukums;
    var tube_title;
        function createUploader(){
            document.getElementById('converter2').style.visibility = 'hidden';
            var uploader = new qq.FileUploader({
                element: document.getElementById('demo'),
                listElement: document.getElementById('separate-list'),
                action: '/ffmpeg/val/server/php.php',
                multiple: false,
                params: {
                    caur_ajax: 'true',
                    key: '<?=$uniqid?>',
                    lang: '<?=$this->lang->lang()?>'
                },  
                // ex. ['jpg', 'jpeg', 'png', 'gif'] or []
                allowedExtensions: [<?=$allowed?>],        
                sizeLimit: <?=$max?>*1024, 
                minSizeLimit: 1,
                debug: true,
                onSubmit: function(id, fileName){
                    $('.qq-upload-button').hide('fast');
		},
                onProgress: function(id, fileName, loaded, total){},
                onComplete: function(id, fileName, responseJSON){
                    
                document.getElementById('demo').style.display = 'none';
                $('.step').hide('slow');
                $('.media').hide('slow');
                document.getElementById('converter2').style.visibility = 'visible';
                document.getElementById('converter2').style.display = 'block';
                faila_nosaukums = fileName;
                    
                },
                onCancel: function(id, fileName){},

                messages: {
                    typeError: "{file} has invalid extension. Only {extensions} are allowed.",
                    sizeError: "{file} is too large, maximum file size is {sizeLimit}.",
                    minSizeError: "{file} is too small, minimum file size is {minSizeLimit}.",
                    emptyError: "{file} is empty, please select files again without it.",
                    onLeave: "The files are being uploaded, if you leave now the upload will be cancelled."            
                },
                showMessage: function(message){ alert(message); }

            });           
        } 
    
    
    
$(document).ready(function(){

var size = 20;
$("#langSelector").live("click", function(){
    if ( size == 20 ) {
        size = 100;
      $('.right').css('padding-top', '100px');
    } else {
        size = 20;
      $('.right').css('padding-top', '20px');
    }
});


$("#converter2").hide();

$(".dropdown dt a").click(function() {
    $(".dropdown dd ul").toggle();
});

$(".dropdown dd ul li a").click(function() {
    var text = $(this).html();
    $(".dropdown dt a span").html(text);
    $(".dropdown dd ul").hide();
});

function getSelectedValue(id) {
    return $("#" + id).find("dt a span.value").html();
}

$(document).bind('click', function(e) {
    var $clicked = $(e.target);
    if (! $clicked.parents().hasClass("dropdown"))
        $(".dropdown dd ul").hide();
});



$(function() {
  var timer = setInterval( showDiv, 100);
  function showDiv() {
	$(".upload_file_button").html("<?=lang('upload.file')?>");
        clearInterval(timer);
  }
});


$("#youtube").live("submit", function(){

    if($('#youtube_link').val().indexOf("youtube.com") == -1 && $('#youtube_link').val().indexOf("vimeo.com") == -1) {
        alert("Incorrect link! Link must start with: http://www.youtube.com, http://m.youtube.com, http://vimeo.com");
        return false;
    }
    var website_type;
    if($('#youtube_link').val().indexOf("youtube.com") != -1) {
        website_type = "youtube";
    }
    
    if($('#youtube_link').val().indexOf("vimeo.com") != -1) {
        website_type = "vimeo";
    }

    $('#bar_youtube').show("slow");
    $(':button').attr("disabled", "disabled"); // To disable
    $("#youtube_uploaded2").progressbar({value: 0});
    $('#demo').hide("slow");
      
         $.ajax({
           type: "POST",
           url: "/en/converter/get_title/<?=$uniqid?>/",
           data: ({youtube : $('#youtube_link').val(), website : website_type}),
           success: function(msg){  
           tube_title = msg;
           }
         });
         
         
         $.ajax({
           type: "POST",
           url: "/en/converter/ajax_upload/<?=$uniqid?>/",
           data: ({youtube : $('#youtube_link').val(), website : website_type}),
           success: function(msg){  
           $('#youtube_uploaded').html(msg);
           $('.media').hide("slow");
           $('.step').hide("slow");
           $(':button').removeAttr("disabled"); // To enable
           document.getElementById('converter2').style.visibility = 'visible';
           $('#converter2').show();
           faila_nosaukums = tube_title + ".flv";
           $('#youtube_uploaded2').html("100%");
           $('#youtube_uploaded2').width(300);
           clearInterval(refreshIntervalId2);
           }
         });

         
         $('#youtube_uploaded').html("Uploading video from Youtube. Please wait...");
         
         
          refreshIntervalId2 = setInterval(function(){
          $("#youtube_uploaded2").load("/en/converter/upload_status/<?=$uniqid?>/" + tube_title, function(response, status, xhr) {
          if (status == "error") {
            var msg = "Sorry but there was an error: ";
            $("#error").html(msg + xhr.status + " " + xhr.statusText);

            clearInterval(refreshIntervalId2);

          }
          else
          {
              $("#youtube_uploaded2").progressbar( "option", "value", response);
              $('#youtube_uploaded2').html(response + "%");
              $('#youtube_uploaded2').width(response*3);
              
              if(response == 100 || response == 99)
              {
                   $('#youtube_uploaded').html("<?=lang('upload.done')?>");    
                   clearInterval(refreshIntervalId2);
                   $('.media').hide("slow");
                   $('.step').hide("slow");
                   $(':button').removeAttr("disabled"); // To enable
                   document.getElementById('converter2').style.visibility = 'visible';
                   $('#converter2').show();
                   faila_nosaukums = tube_title + ".flv";
                   $('#youtube_uploaded2').html("100%");
                   $('#youtube_uploaded2').width(300);
                   clearInterval(refreshIntervalId2);
              }
              
          }
        });
        },2000);
   
       return false;  
     });

    $('.header h1').click(function() {
        
        window.location = "<?php echo base_url();?><?=$this->lang->lang()?>";
        
    });
 
   createUploader();

    function getFormat(format) {
        <?php foreach($extensions as $name => $ext):?>
        if(format == "<?=$name?>") {
            format = "<?=$ext?>";
        }      
        <?php endforeach;?>
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
     $("#conv").live("submit", function(){
     //$('#conv').submit(function() {
     $('#converter2').hide("slow");
     $('.media').hide("slow");
     $('#youtube_uploaded').hide("slow");
     $('#bar_youtube').hide("slow");
     $('#bar_convert').show("slow");
     
     $("#percents").progressbar({value: 0});
	
         	 test = $.ajax({
			url: "/en/converter/change_body/" +faila_nosaukums,
			async: false
			}).responseText;
			
  refreshIntervalId = setInterval(function(){
      $("#percents").load("/en/converter/convert_status/<?=$uniqid?>", function(response, status, xhr) {
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
          var saite_uz_failu = '<?=lang('mobile.download')?>: <a href="/files/converted/' + fails + '">' + fails + '</a>';

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
		   
           if(response == 100 || response == 99)
               {
                 var fails          = test+"-<?=$uniqid?>." + getFormat($('#format').val());
                 var saite_uz_failu = '<?=lang('mobile.download')?>: <a href="/files/converted/' + fails + '">' + fails + '</a>';
                 $('#percents').width(300);
                 $('#download').html(saite_uz_failu);
                 $('#download').show("slow");
                 clearInterval(refreshIntervalId); 
               }

      }
    });
},2000);

        $.get("/en/converter/convert/<?=$uniqid?>/" + $('#format').val()+"/"+faila_nosaukums+"/"+$('input:checkbox:checked').val()+"/"+$('#s_hh').val()+"/"+$('#s_mm').val()+"/"+$('#s_ss').val()+"/"+$('#e_hh').val()+"/"+$('#e_mm').val()+"/"+$('#e_ss').val()+"/"+$('#apraksts').val()+"/"+$('#resize').val()+"/"+$('#width').val()+"/"+$('#height').val(), {
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
                height: $('#height').val()     //video height
               },

           function(data){
             clearInterval(refreshIntervalId);
             $('#percents').html("100%"); //uztaisaam 100% arii tad, ja video tiek apgriezts
             $('#percents').width(300);

             var fails          = data + "-<?=$uniqid?>." + getFormat($('#format').val());
             var saite_uz_failu = '<?=lang('mobile.download')?>: <a href="/files/converted/' + fails + '">' + fails + '</a>';

             $('#download').html(saite_uz_failu);
             $('#download').show("slow");
        });
      
       return false;
});


});
    
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-4906479-3']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>