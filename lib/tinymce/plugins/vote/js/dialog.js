var offset = 0;
var limit = 10;
var artContent = $('#art-sugg-content');
function search(){    
    var keyword = $('input[name="search_keyword"]').val();
    $.ajax({
        url : "/",
        type : "GET",
        data : {act:"loadRelationVote",q : keyword,limit : limit,offset : offset},
        success : function(html){
            offset = offset + limit;          
            $('#art-sugg-content').html(html);
        }
    });
}



function timkiem(key){
    if(key=="")
    {
        alert('Ban chua nhap ten binh chon');
        document.getElementById('search_keyword').focus();
        return false;
    }                       
    $(document).ready(function(){
        var imageContainer = $('#url_link');
        var float_ = $('.float').val();
        $.ajax({
            type : "GET",
            url : "/",
            data : {act:"insertBlockVote", id : key, float_: float_},
            success : function(html){
                if(html!="")
                {
                    tinyMCEPopup.editor.execCommand('mceInsertContent', false, html);
                    tinyMCEPopup.close();                   
                }
                else 
                {
                    alert("Khong ton tai ID "+key);  
                }
            }                
        });
    });      
}