var offset = 0;
var limit = 10;
var artContent = $('#art-sugg-content');
function search(){    
	alert('stone corleone');
    var keyword = $('input[name="search_keyword"]').val();
    $.ajax({                                      
		url: 'http://beta.edaily.vn/search_related_news.php?key='+keyword+'&limit='+limit+'&offset='+offset,
        type : "GET",
        //data : {act:"loadRelationNews",q : keyword,limit : limit,offset : offset},
        success : function(html){
            offset = offset + limit;            
            $('#art-sugg-content').html(html);
        }
    });
}