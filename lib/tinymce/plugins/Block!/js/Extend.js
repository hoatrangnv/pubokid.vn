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
        alert('Báº¡n chÆ°a nháº­p tÃªn tiÃªu Ä‘á»? hoáº·c ID bÃ i viáº¿t');
        document.getElementById('search_keyword').focus();
        return false;
    }                       
    $(document).ready(function(){
        var imageContainer = $('#add');
        $.ajax({
            type : "GET",
            url : "/",
            data : {act:"pluginSearch", id : key , action : action },
            success : function(html){
                if(html!="")
                {
                    imageContainer.prepend(html);
                      i=i+1;                        
                }
                else 
                {
                    alert("KhÃ´ng tá»“n táº¡i bÃ i viáº¿t theo ID "+key);  
                }
            }                
        });
    });       
    }
    else
    {
        alert('Block chá»‰ hiá»ƒn thá»‹ Ä‘Æ°á»£c 4 bÃ i viáº¿t');
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
        alert('Báº¡n chÆ°a nháº­p tÃªn tiÃªu Ä‘á»? hoáº·c ID bÃ i viáº¿t');
        document.getElementById('search_keyword').focus();
        return false;
    }                       
    $(document).ready(function(){
        var imageContainer = $('#add');
        $.ajax({
            type : "GET",
            url : "/",
            data : {act:"pluginSearch", id : key , action : action },
            success : function(html){
                if(html!="")
                {
                    imageContainer.prepend(html);
                      i=i+1;                        
                }
                else 
                {
                    alert("KhÃ´ng tá»“n táº¡i bÃ i viáº¿t theo ID "+key);  
                }
            }                
        });
    });       
    }
    else
    {
        alert('Block chá»‰ hiá»ƒn thá»‹ Ä‘Æ°á»£c 4 bÃ i viáº¿t');
        return false;
    }
}
 
function timkiemanh(key) {
    var action = document.getElementById('style1').value;                          
    if(key=="")
    {
        alert('Bạn cần nhập tiêu đề bài viết');
        document.getElementById('search_keyword').focus();
        return false;
    }                       
    $(document).ready(function(){
        var imageContainer = $('#add ul');
        $.ajax({
            type : "GET",
            url : "/",
            data : {act:"pluginSearchPhoto", id : key , action : action },
            success : function(html){
                if(html!="")
                {
                    imageContainer.prepend(html);
                      i=i+1;                        
                }
                else 
                {
                    alert("KhÃ´ng tá»“n táº¡i bÃ i viáº¿t theo ID "+key);  
                }
            }                
        });
    });       
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
    if(confirm('Báº¡n cÃ³ cháº¯c cháº¯n xÃ³a bÃ i viáº¿t nÃ y khá»?i ná»™i dung !'))
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
    height : 460,
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
function blockstyle6()
{    
    tinyMCE.activeEditor.windowManager.open({
    url : 'style6.htm',
    width : 700,
    height : 460,
    scrollbars : 1
    }, {
    custom_param : 1
    });         
    tinyMCEPopup.close();
} 