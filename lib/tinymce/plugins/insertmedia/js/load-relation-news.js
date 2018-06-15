var offset = 0;
var limit = 10;
var artContent = $('#art-sugg-content');
function searchphoto(){    
    var keyword = $('input[name="search_keyword"]').val();
    $.ajax({
        url : "/",
        type : "GET",
        data : {act:"loadRelationPhoto",q : keyword,limit : limit,offset : offset},
        success : function(html){
            offset = offset + limit;            
            $('#art-sugg-content').html(html);
        }
    });
}
function searchvideo(){    
    var keyword = $('input[name="search_keyword"]').val();
    $.ajax({
        url : "/",
        type : "GET",
        data : {act:"loadRelationVideo",q : keyword,limit : limit,offset : offset},
        success : function(html){
            offset = offset + limit;            
            $('#art-sugg-content').html(html);
        }
    });
}