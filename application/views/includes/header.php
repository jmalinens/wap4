<!DOCTYPE html>
<html lang="<?=strtolower($this->lang->lang())?>">
<head>
<title><?=lang('title')?> - <?=getTitle()?></title>
<meta charset="utf-8"/>
<meta name="description" content="<?=lang('title.meta')?>" />
<link rel="stylesheet" href="<?php echo base_url();?>css/style.css"/>
<link rel="stylesheet" href="<?php echo base_url();?>css/css3.css"/>
<link rel="stylesheet" href="<?php echo base_url();?>css/fileuploader.css"/>
<link rel="stylesheet" href="<?php echo base_url();?>css/le-frog/jquery-ui-1.8.9.custom.css"/>
<?php if(end($this->uri->segment_array()) == "index"){?>
<link rel="canonical" href="http://wap4.org/<?=str_replace("index", "", $this->uri->uri_string())?>"/>
<?}?>
<script src="<?php echo base_url();?>js/fileuploader.js"></script>
<script src="<?php echo base_url();?>js/jquery.min.js"></script>
<script src="<?php echo base_url();?>js/jquery-ui-1.8.9.custom.min.js"></script>
<!--
<?php if($this->lang->lang() == "en"){
echo '<script src="http://connect.facebook.net/en_US/all.js"></script>';
}else{
echo'<script src="http://connect.facebook.net/'.$this->lang->lang().'_'.strtoupper($this->lang->lang()).'/all.js"></script>';
}?>-->

<script>
    var faila_nosaukums;
    var tube_title;
        function createUploader(){
            document.getElementById('converter2').style.visibility = 'hidden';
            var uploader = new qq.FileUploader({
                element: document.getElementById('demo'),
                listElement: document.getElementById('separate-list'),
                //action: '<?php echo site_url('converter/upload');?>',
                action: '/ffmpeg/val/server/php.php',
                multiple: false,
                
                // additional data to send, name-value pairs
                params: {
                    caur_ajax: 'true',
                    key: '<?=$uniqid?>',
                    lang: '<?=$this->lang->lang()?>'
                },

                // validation    
                // ex. ['jpg', 'jpeg', 'png', 'gif'] or []
                allowedExtensions: [<?=$allowed?>],        
                // each file size limit in bytes
                // this option isn't supported in all browsers
                sizeLimit: <?=$max?>*1024, // max size   
                minSizeLimit: 1, // min size

                // set to true to output server response to console
                debug: true,

                // events         
                // you can return false to abort submit
                onSubmit: function(id, fileName){
				
				$('.qq-upload-button').hide('fast');
				
				},
                onProgress: function(id, fileName, loaded, total){},
                onComplete: function(id, fileName, responseJSON){
                    
                document.getElementById('demo').style.display = 'none';
                $('.step').hide('slow');
                $('.media').hide('slow');
                document.getElementById('converter2').style.visibility = 'visible';
                faila_nosaukums = fileName;
                    
                },
                onCancel: function(id, fileName){},

                messages: {
                    // error messages, see qq.FileUploaderBasic for content
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

<?php
if($this->uri->segment(4) != "facebook")
{
?>
$.ajax({
  url: '/<?=$lang?>/auth/index/<?=$uniqid?>/caur_ajax',
  data: "caur_ajax=true&amp;unikaalais=<?=$uniqid?>",
  success: function(data) {
    $('.right').html(data);
    //FB.XFBML.parse();
  }
});
<?php
}
?>

$(function() {
  var timer = setInterval( showDiv, 100);
  function showDiv() {
	$(".upload_file_button").html("<?=lang('upload.file')?>");
        clearInterval(timer);
  }
});
/*
$("#fb_log").live("click", function(){
         FB.api('/me', function(user) {
           if(user != null) {
              var image = document.getElementById('image');
              image.src = 'http://graph.facebook.com/' + user.id + '/picture';
              var name = document.getElementById('name');
              name.innerHTML = user.name
              $("#loginbox").hide("fast");
           }
         });
         
      $.ajax({
      url: '/<?=$lang?>/converter/index/<?=$uniqid?>/caur_ajax',
      data: "caur_ajax=true&amp;unikaalais=<?=$uniqid?>",
      success: function(data) {
        $('.content').html(data);
        createUploader();
      }
    });
         
         return false;
});

$("#fb_reg").live("click", function(){
    $(".content").load("/en/auth/facebook_reg");
    //FB.XFBML.parse();    
    return false;
});
*/

$("#youtube").live("submit", function(){
    $('#bar_youtube').show("slow");
    $(':button').attr("disabled", "disabled"); // To disable
    $("#youtube_uploaded2").progressbar({value: 0});
    $('#demo').hide("slow");
         $.ajax({
           type: "POST",
           url: "/en/converter/get_youtube_title/<?=$uniqid?>/",
           data: ({link : $('#youtube_link').val()}),
           success: function(msg){  
           tube_title = msg;
           }
         });
         
         $.ajax({
           type: "POST",
           url: "/en/converter/upload_youtube/<?=$uniqid?>/",
           data: ({link : $('#youtube_link').val()}),
           success: function(msg){  
           $('#youtube_uploaded').html(msg);
           $('.media').hide("slow");
           $(':button').removeAttr("disabled"); // To enable
           document.getElementById('converter2').style.visibility = 'visible';
           faila_nosaukums = tube_title + ".flv";
           $('#youtube_uploaded2').html("100%");
           $('#youtube_uploaded2').width(300);
           clearInterval(refreshIntervalId2);
           }
         });
         $('#youtube_uploaded').html("Uploading video from Youtube. Please wait...");
         
         
          refreshIntervalId2 = setInterval(function(){
          $("#youtube_uploaded").load("/en/converter/youtube_upload_status/<?=$uniqid?>/" + tube_title, function(response, status, xhr) {
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
              
              if(response == 100)
              {
                  $('#youtube_uploaded').html("<?=lang('upload.done')?>");    
                  clearInterval(refreshIntervalId2);
              }
              
          }
        });
        },1500);
   
       return false;  
     });
    

if(window.location.href == "<?php echo base_url();?>"
|| window.location.href == "<?php echo base_url();?>en"
|| window.location.href == "<?php echo base_url();?>lv"
|| window.location.href == "<?php echo base_url();?>ru"
|| window.location.href == "<?php echo base_url();?>en/welcome/index/facebook/"
|| window.location.href == "<?php echo base_url();?>lv/welcome/index/facebook"
|| window.location.href == "<?php echo base_url();?>ru/welcome/index/facebook")
{
    $.ajax({
      url: '/<?=$lang?>/converter/index/<?=$uniqid?>/caur_ajax',
      data: "caur_ajax=true&amp;unikaalais=<?=$uniqid?>",
      success: function(data) {
        $('.content').html(data);
        createUploader();
      }
    });
};

    $('.header h1').click(function() {
        
        window.location = "<?php echo base_url();?><?=$this->lang->lang()?>";
        
    }); 
 
 
    $('#id_converter').click(function() {

    $.ajax({
      url: '/<?=$lang?>/converter/index/<?=$uniqid?>/caur_ajax',
      data: "caur_ajax=true&amp;unikaalais=<?=$uniqid?>",
      success: function(data) {
        $('.content').html(data);
        createUploader();
      }
    });
           return false;
    });



$(".load_ajax").live("click", function(){

    $.ajax({
      url: this.href + "/<?=$uniqid?>/caur_ajax",
      success: function(data) {
        $('.content').html(data);
        $(".link a").addClass("load_ajax"); //pievieno load_ajax klasi prieksh pagination
        /*$(".link a").attr("rel","nofollow");*/
      }
    });
           return false;
});


});

</script>

<script>

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-4906479-3']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
</head>
<body> 

      <!--<div id="fb-root"></div>
      <script src="http://connect.facebook.net/en_US/all.js">
      </script>
      <script>
         FB.init({ 
            appId:'187627851270954',
            cookie:true, 
            status:true,
            xfbml:true 
         });

       </script>-->

    
    
    
<div class="container">
<div class="header">
	<h1>wap4.org - <?=lang('title.header')?></h1>
        <h3><?=lang('title.h3')?></h3>
        <ul id="nav">
            <?php foreach ($navigation as $nav):?>

            <li><?=anchor($nav."/index", $this->lang->line($nav), $nav =="converter"? 'id="id_converter"': 'class="load_ajax"')?></li>

            <?php endforeach; ?>
        </ul>
 <div class="languages">      
 <?=anchor($this->lang->switch_uri('en'), "EN")?>
 <span>.</span> <?=anchor($this->lang->switch_uri('ru'), "RU")?>
 <span>.</span> <?=anchor($this->lang->switch_uri('lv'), "LV")?>
 </div>

</div>
<div class="wrapper">

<div class="content">