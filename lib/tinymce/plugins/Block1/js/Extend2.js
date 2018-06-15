var i=0;
/*var title = document.getElementById('search_keyword').value;                */
    $("li").click(function () {
        $(this).remove();
        i=i-1;
        return false;
    });
function timkiem1(key)
{
    if(i<=3){
        var action = document.getElementById('style1').value;                          
    if(key=="")
    {
        alert('B?n chua nh?p tên tiêu d? ho?c ID bài vi?t');
        document.getElementById('search_keyword').focus();
        return false;
    }                       
    $(document).ready(function(){
        var imageContainer = $('#add');
        $.ajax({
            type : "POST",
            url : 'http://beta.edaily.vn/lib/tinymce/plugins/Block/pluginSearch.php?id='+key,
            //data : {act:"pluginSearch", key : key , action : action },
            success : function(html){
                if(html!="")
                {
                    imageContainer.prepend(html);
                      i=i+1;                        
                }
                else 
                {
                    alert("Không t?n t?i bài vi?t theo ID "+key);  
                }
            }                
        });
    });       
    }
    else
    {
        alert('Block ch? hi?n th? du?c 4 bài vi?t');
        return false;
    } 
}
function timkiem()
{
    if(i<=3){        
        var action = document.getElementById('style1').value;                          
        var key = document.getElementById('IDkeyword').value;
    if(key=="")
    {
        alert('B?n chua nh?p tên tiêu d? ho?c ID bài vi?t');
        document.getElementById('IDkeyword').focus();
        return false;
    }                       
    $(document).ready(function(){
        var imageContainer = $('#add');
        $.ajax({
            type : "POST",
            url : "/",
            data : {act:"pluginSearch", key : key , action : action },
            success : function(html){
                if(html!="")
                {
                    imageContainer.prepend(html);
                      i=i+1;                        
                }
                else 
                {
                    alert(html);
                    alert("Không t?n t?i bài vi?t theo ID "+key);  
                }
            }                
        });
    });       
    }
    else
    {
        alert('Block ch? hi?n th? du?c 4 bài vi?t');
        return false;
    }
}
 
function changestyle()
    {
             var obj = document.getElementById ("add");        
            if(document.getElementById("trai").checked==true)
            {
              obj.style.cssFloat = "left";  
            }
            else obj.style.cssFloat = "right";  
    }
function destroyDiv(key) 
{
    if(confirm('B?n có ch?c ch?n xóa bài vi?t này kh?i n?i dung !'))
    {
      var div = document.getElementById(key);
      div.parentNode.removeChild(div);
      i=i-1;              
    }    
}
function insertblock()
{
    tinyMCEPopup.requireLangPack();        
    var content = $('#preview').html();                                     
    tinyMCEPopup.editor.execCommand('mceInsertContent', false,content);
    tinyMCEPopup.close();
} 
function blockstyle1()
{    
    tinyMCE.activeEditor.windowManager.open({
    url : 'style1.htm',
    width : 700,
    height : 560,
    scrollbars : 1
    }, {
    custom_param : 1
    });         
    tinyMCEPopup.close();
} 
function blockstyle2()
{    
    tinyMCE.activeEditor.windowManager.open({
    url : 'style2.htm',
    width : 500,
    height : 700,
    scrollbars : 1
    }, {
    custom_param : 1
    });         
    tinyMCEPopup.close();
}
function blockstyle3()
{    
    tinyMCE.activeEditor.windowManager.open({
    url : 'style3.htm',
    width : 400,
    height : 600,
    scrollbars : 1
    }, {
    custom_param : 1
    });         
    tinyMCEPopup.close();
}  
function blockstyle4()
{    
    tinyMCE.activeEditor.windowManager.open({
    url : 'style4.htm',
    width : 400,
    height : 600,
    scrollbars : 1
    }, {

    custom_param : 1
    });         
    tinyMCEPopup.close();
}
function blockstyle5()
{    
    tinyMCE.activeEditor.windowManager.open({
    url : 'style5.htm',
    width : 700,
    height : 460,
    scrollbars : 1
    }, {
    custom_param : 1
    });         
    tinyMCEPopup.close();
} 
