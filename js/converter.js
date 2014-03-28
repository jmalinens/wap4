/* 
 * 
 * @author Juris Malinens<juris.malinens@inbox.lv>
 * @version 0.1
 */

$(document).ready(function(){

$('.convert_options').hide();
$('#bar_convert').hide();
$('#converter2').hide();
function createUploader(){
    var uploader = new qq.FileUploader({
        element: document.getElementById('demo'),
        listElement: document.getElementById('separate-list'),
        action: '/ffmpeg/val/server/php.php',
        multiple: false,
        params: {
            caur_ajax: 'true',
            key: opt_uniqid,
            lang: $("html").attr("lang")
        },  
        allowedExtensions: opt_allowed,        
        sizeLimit: opt_max*1024, 
        minSizeLimit: 1,
        debug: true,
        onSubmit: function(id, fileName){
            $('.qq-upload-button').hide('fast');
            $('.content_right').css({'visibility': 'hidden', 'width': '0px'});
            $('.content_right').hide();
            $('.content_right').remove();
            $(".content_left").css({"width": "98%"});
            $("#content_left_apvalks").css({"padding": "0px 1px 0px 1px"});
            $("#content_right_apvalks").css({"padding": "0px 1px 0px 1px"});
            $('#separate-list').show();
            $('#bar_convert').appendTo($('#content_left_apvalks'));
            $('#converter2').appendTo($('#content_left_apvalks'));
        },
        onProgress: function(id, fileName, loaded, total){},
        onComplete: function(id, fileName, responseJSON){

        document.getElementById('demo').style.display = 'none';
        $('.left_top').hide();
        $('#converter_left').show();
        $('#converter2').show();
        $('#converter2').css({'visibility': 'visible', 'display': 'block'});
        $('.step').hide('slow');
        $('.media').hide('slow');
        faila_nosaukums = fileName;
console.log(faila_nosaukums);
        },
        onCancel: function(id, fileName){},

        messages: {
            typeError: "{file}- "+opt_fail_extension+" ({extensions}).",
            sizeError: "{file}- "+opt_fail_size+", max: {sizeLimit}.",
            minSizeError: "{file} is too small, minimum file size is {minSizeLimit}.",
            emptyError: "{file} is empty, please select files again without it.",
            onLeave: "The files are being uploaded, if you leave now the upload will be cancelled."            
        },
        showMessage: function(message){ alert(message); }

    });           
}
//end function createUploader()

function getSelectedValue(id) {
    return $("#" + id).find("dt a span.value").html();
}


$(function() {
  var timer = setInterval(showDiv, 100);
  function showDiv() {
	$(".upload_file_button").html(opt_file);
        clearInterval(timer);
  }
});


$("#youtube").live("submit", function(){
    
    $('.content_left').remove();
    $(".content_right").css({"width": "98%"});
    $("#content_left_apvalks").css({"padding": "0px 1px 0px 1px"});
    $("#content_right_apvalks").css({"padding": "0px 1px 0px 1px"});
    $('.right_top').hide();
    $('#bar_convert').appendTo($('#content_right_apvalks'));
    $('#converter2').appendTo($('#content_right_apvalks'));
    $('#bar_convert').hide();
    $('#converter2').hide();
    
    if($('#youtube_link').val().indexOf("youtube.com") == -1 && $('#youtube_link').val().indexOf("vimeo.com") == -1) {
        alert(opt_fail_link+": http://www.youtube.com, http://m.youtube.com, http://vimeo.com");
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
    $('#demo').hide("slow");

         $.ajax({
           type: "POST",
           url: "/en/converter/get_title/"+opt_uniqid+"/",
           data: ({youtube : $('#youtube_link').val(), website : website_type}),
           success: function(msg){  
           tube_title = msg;
           }
         });

         $.ajax({
           type: "POST",
           url: "/en/converter/ajax_upload/"+opt_uniqid+"/",
           data: ({youtube : $('#youtube_link').val(), website : website_type}),
           success: function(msg){
           
           $('#youtube_uploaded').html(msg);
           $('.media').hide("slow");
           $('#bar_youtube').hide();
           $('#youtube_uploaded').hide();
           $('#converter_right').show();
           $('#converter2').show();
           
           faila_nosaukums = tube_title + ".flv";
           $('#youtube_uploaded2').html("100%");
           $('#youtube_uploaded2').width(300);
           clearInterval(refreshIntervalId2);
           }
         });
         
         $('#youtube_uploaded').html(opt_upload_wait);
         $('#bar_youtube').show();
         
          refreshIntervalId2 = setInterval(function(){
          $("#youtube_uploaded2").load("/en/converter/upload_status/"+opt_uniqid+"/" + tube_title, function(response, status, xhr) {
          if (status == "error") {
            var msg = opt_upload_sorry+": ";
            $("#error").html(msg + xhr.status + " " + xhr.statusText);

            clearInterval(refreshIntervalId2);

          }
          else
          {

              $('#youtube_uploaded2').width(response*4);
              
              if(response == 100 || response == 99)
              {
                   $('#youtube_uploaded').html(opt_done);    
                   clearInterval(refreshIntervalId2);
                   $('.media').hide("slow");
                   $('.step').hide("slow");
                   document.getElementById('converter2').style.visibility = 'visible';
                   $('#converter2').show();
                   faila_nosaukums = tube_title + ".flv";
                   $('#youtube_uploaded2').html("100%");
                   $('#youtube_uploaded2').width(400);
                   clearInterval(refreshIntervalId2);
              }
              
          }
        });
        },2000);
   
       return false;  
     });
//end youtube submit

    $('.header_title').click(function() {
        window.location = "/"+$("html").attr("lang");
    });

createUploader();

       $('.cut_field').hide();
       $('#cut').attr("unchecked");
       
       $("input[name='cut']").change(function () {
           var isChecked = $("input[name='cut']").prop('checked');
            if (isChecked === true) {
                $('.cut_field').show();
            } else {
                $('.cut_field').hide();
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
     
     $('#converter2').hide("slow");
     $('.media').hide("slow");
     $('#youtube_uploaded').hide("slow");
     $('#bar_youtube').hide("slow");
     $('#bar_convert').show("slow");

     test = $.ajax({
            url: "/en/converter/change_body/" +faila_nosaukums,
            async: false
            }).responseText;


  refreshIntervalId = setInterval(function(){
      $("#percents").load("/en/converter/convert_status/"+opt_uniqid, function(response, status, xhr) {
      if (status == "error") {
        var msg = opt_upload_sorry+": ";
        $("#error").html(msg + xhr.status + " " + xhr.statusText);
        
        clearInterval(refreshIntervalId);
        
      } else if (response > 100) {
          $('#percents').html("100");
          $("#percents").append("%");
          $('#percents').width(400);
          $('#bar_convert').hide();
          var fails          = test+"-" + opt_uniqid + "." + getFormat($("input[name='format']:checked").val());
          var saite_uz_failu = opt_download+': <a href="/files/converted/' + fails + '">' + fails + '</a>';

          $('#download').html(saite_uz_failu);
          $('#download').show("slow");
          clearInterval(refreshIntervalId);
      } else {
           $('#percents').html(response);
           $('#percents').width(response*4);
           $("#percents").append("%");
		   
           if(response == 100 || response == 99) {
                 var fails          = test+"-" + opt_uniqid + "." + getFormat($("input[name='format']:checked").val());
                 var saite_uz_failu = opt_download+': <a href="/files/converted/' + fails + '">' + fails + '</a>';
                 $('#bar_convert').hide();
                 $('#percents').width(400);
                 $('#download').html(saite_uz_failu);
                 $('#download').show("slow");
                 clearInterval(refreshIntervalId); 
               }

      }
    });
},2000);
//end refreshinterval

var isCutChecked = $("input[name='cut']").prop('checked');

        $.get("/en/converter/convert/"
		+opt_uniqid+"/"
		+ $("input[name='format']:checked").val()
		+"/"+faila_nosaukums+"/"
		+$('input:checkbox:checked').val()
		+"/"+$('#s_hh').val()
		+"/"+$('#s_mm').val()
		+"/"+$('#s_ss').val()
		+"/"+$('#e_hh').val()
		+"/"+$('#e_mm').val()
		+"/"+$('#e_ss').val()
		+"/"+$('#apraksts').val()
		+"/"+$('input[name="quality"]:checked').val(), {
                input_file: faila_nosaukums,
                caur_ajax:   "true",
                key:    opt_uniqid,
                format: $("input[name='format']:checked").val(),    //format to which convert
                cut:    isCutChecked,       //cut?
                s_hh:   $('#s_hh').val(),      //start hours
                s_mm:   $('#s_mm').val(),      //start minutes
                s_ss:   $('#s_ss').val(),      //start seconds
                e_hh:   $('#e_hh').val(),      //end hours
                e_mm:   $('#e_mm').val(),      //end minutes
                e_ss:   $('#e_ss').val(),      //end seconds
                descr:  $('#apraksts').val(),  //description
                quality: $('input[name="quality"]:checked').val() //quality
               },

           function(data){
             clearInterval(refreshIntervalId);
             $('#percents').html("100%"); //uztaisaam 100% arii tad, ja video tiek apgriezts
             $('#percents').width(400);
             $('#bar_convert').hide();
             var fails          = data + "-" + opt_uniqid + "." + getFormat($("input[name='format']:checked").val());
             var saite_uz_failu = opt_mobile_download+': <a href="/files/converted/' + fails + '">' + fails + '</a>';

             $('#download').html(saite_uz_failu);
             $('#download').show("slow");
        });
	//end /en/converter/convert
      
       return false;
});
//end #conv submit


});
//end document.ready


  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-4906479-3']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

