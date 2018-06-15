  function getUrlVars()
{
    var vars = [], hash;    
    var hashes = window.opener.location.href.slice(window.opener.location.href.indexOf('?') + 1).split('&');
    for(var i = 0; i < hashes.length; i++)
    {
        hash = hashes[i].split('=');
        vars.push(hash[0]);
        vars[hash[0]] = hash[1];
    }
    return vars;
}

  function listthumb()
    {        
    var first = getUrlVars()["nid"];     
    var imageContainer = $('#display-list-thumb');    
            $.ajax({        
                type : "POST",
                url : "/",
                data : {act:"acpListThumb",id :first},
                success : function(html){
                    imageContainer.html(html);
                }
            });     
    }
  function uploadImageUrl(){
        var ajaxUrl ='/';
        var imgurl = $("#link_url").val();        
        if(imgurl=="")
        {
            alert('Vui lòng nhập URL ảnh');
            return false;
        }        
        $.post(ajaxUrl, {
                act : "mediaAction", action : "uploadimgLink",imglink : imgurl
            },           
             function(str){                
                    $('#display-file-content').prepend(str);
                }
          
        );
    }; 
    
$(function(){
    var btnUpload=$('#upload');
    var status=$('#status');
    var uploadUrl = '/';
    var submitVar = 'fileUpload';
    new AjaxUpload(btnUpload, { 
        action: uploadUrl,
        name: submitVar,
        data: {
            act: 'acpNewsAction',
            action : 'imagecontentIdUser',
        },
        responseType: false,
        onSubmit: function(file, ext){
             if (! (ext && /^(jpg|png|jpeg)$/.test(ext))){ 
                // extension is not allowed 
                status.text('Only JPG, PNG or GIF files are allowed');
                return false;
            }
            status.text('Uploading...');
        },
        onComplete: function(file, response){
            response = JSON.parse(response);
            //On completion clear the status
            status.text('');
            //Add uploaded file to list
            if(response){                
                ihtml = '<div style="float:left;padding: 10px; margin: 70px 1px 30px;;border:#ccc 1px solid;width: 145px;background-color:#fff"><div style="background:url('+ response.file_thumb +') no-repeat center;height:80px;"></div><center><a style="color:#288FB8;text-decoration:none" title="Chèn ảnh này vào trong nội dung!" class="gallery" onclick="insertImage(\''+ response.file_link +'\');return false" href="javascript:void(0);"><strong>Chèn ảnh</strong></a> |<a style="color:#C00;text-decoration:none" title="Xóa ảnh này!" class="gallery" onclick="return deleteimage(2979686);" href="#"><strong>Xóa</strong></a></center></div><br/>';
                $('#display-file-content').prepend(ihtml);
            } else{
                alert('a');
                return false;
                //$('<li></li>').append('#files').text(file).addClass('error');
            }
           
        }
    });
    
});

$(document).ready(function(){    
    var imageContainer = $('#display-file-content');    
    $.ajax({        
        type : "POST",
        url : "/",
        data : {act:"ListImage",page :1},
        success : function(html){
            imageContainer.html(html);
        }
    }); 
});