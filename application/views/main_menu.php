<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>media-converter.com</title>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/style.css"/>
<link href="<?php echo base_url();?>css/fileuploader.css" rel="stylesheet" type="text/css">
<script src="<?php echo base_url();?>js/fileuploader.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>js/jquery.min.js" type="text/javascript"></script>


<script type="text/javascript">
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
                    key: '<?=$uniqid?>'
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
                onSubmit: function(id, fileName){},
                onProgress: function(id, fileName, loaded, total){},
                onComplete: function(id, fileName, responseJSON){
                    
                document.getElementById('demo').style.visibility = 'hidden';
                document.getElementById('converter2').style.visibility = 'visible';
                faila_nosaukums = fileName;
                //alert(fileName);
                    
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

$.ajax({
  url: '/<?=$lang?>/auth',
  success: function(data) {
    $('.right').html(data);
  }
});

$.ajax({
  url: '/<?=$lang?>/converter',
  success: function(data) {
    $('.content').html(data);
    createUploader();
  }
});
    
    $('#id_converter').click(function() {
    //$.get("/en/converter/convert", { input_file: fileName });      
    //alert('Handler for .submit() called.');

    $.ajax({
      url: '/<?=$lang?>/converter',
      success: function(data) {
        $('.content').html(data);
        createUploader();
      }
    });
           return false;
    });
  
$("#id_news").live("click", function(){
    $.ajax({
      url: '/<?=$lang?>/news',
      success: function(data) {
        $('.content').html(data);
      }
    });
           return false;
});


$("#id_howto").live("click", function(){
    $.ajax({
      url: '/<?=$lang?>/howto',
      success: function(data) {
        $('.content').html(data);
      }
    });
           return false;
});

$("#id_about").live("click", function(){
    $.ajax({
      url: '/<?=$lang?>/about',
      success: function(data) {
        $('.content').html(data);
      }
    });
           return false;
});
  


$(".link a").live("click", function(){
    $.ajax({
      url: this.href,
      success: function(data) {
        $('.content').html(data);
      }
    });
           return false;
});

  
   
});

</script>
<!--
success: function(data) {     $('.right').html(data);  $('link').click(function(){alert('clicked');});  }
-->
</head>
<body> 

<div class="container">
<div class="header">
	<h1>media-converter.com</h1>
            <ul id="nav">

<?php foreach ($navigation as $nav):?>

<li><?=anchor($nav, $this->lang->line($nav), 'id="id_'.$nav.'"')?></li>

<?php endforeach; ?>
            </ul>
</div>
<div class="wrapper">

<div class="content">
</div>

<div class="right">
    <?=anchor("auth/login","Log In")?>
</div>
<div class="bottom">
<p><strong>&copy; Juris Malinens</strong></p>
</div>
<div class="footer">
		<a href="http://validator.w3.org/check?uri=referer">
			<img src="/img/valid-xhtml10-converter.png" alt="Valid XHTML 1.0 Strict" height="31" width="88" />
		</a>
		<a href="http://jigsaw.w3.org/css-validator/check/referer">
			<img src="/img/vcss-converter.png" alt="Valid CSS!" width="88" height="31" />
		</a>
		<a href="http://ffmpeg.org">
			<img src="/img/ffmpeg-logo-converter.png" alt="Powered by FFMPEG" width="123" height="31" />
		</a>
		<a href="http://www.apache.org">
			<img src="/img/powered-by-apache-converter.png" alt="Powered by Apache WebServer" width="88" height="31" />
		</a>
		<a href="http://www.mysql.com">
			<img src="/img/button_mysql-converter.png" alt="Powered by MySQL Database" width="88" height="31" />
		</a>
</div>
</div>
</body>
</html>
