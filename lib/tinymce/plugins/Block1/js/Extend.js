var i=0;
/*var title = document.getElementById('search_keyword').value;                */
    $("li").click(function () {
        $(this).remove();
        i=i-1;
        return false;
    });
function timkiem1(key)
{
        var action = document.getElementById('style1').value;                          
    if(key=="")
    {
        alert('Nhập ID bài viết');
        document.getElementById('search_keyword').focus();
        return false;
    }                       
    $(document).ready(function(){
        var imageContainer = $('#add');
        $.ajax({
            type : "POST",
            url : 'http://edaily.vn/lib/tinymce/plugins/Block/pluginSearch.php?id='+key,
            //data : {act:"pluginSearch", key : key , action : action },
            success : function(html){
                if(html!="")
                {
                    imageContainer.prepend(html);
                      i=i+1;                        
                }
                else 
                {
                    alert("Không tồn tại bài viết với ID "+key);  
                }
            }                
        });
    });       
}
	function timkiem()
	{      
			var action = document.getElementById('style1').value;                          
			var key = document.getElementById('IDkeyword').value;
		if(key=="")
		{
			alert('Nhập ID bài viết');
			document.getElementById('IDkeyword').focus();
			return false;
		}                       
		$(document).ready(function(){
			var imageContainer = $('#add');
			$.ajax({
				type : "POST",
				url : 'http://edaily.vn/lib/tinymce/plugins/Block/pluginSearch.php?id='+key,
				//data : {act:"pluginSearch", key : key , action : action },
				success : function(html){
					if(html!="")
					{
						imageContainer.prepend(html);
						  i=i+1;                        
					}
					else 
					{
						alert(html);
						alert("Không tồn tại bài viết với ID "+key);  
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
    if(confirm('Xóa bài này?'))
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
