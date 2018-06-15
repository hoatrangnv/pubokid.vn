	var offset = 0;
	var limit = 15;
	var artContent = $('#art-sugg-content');
	function search(){ 
		var keyword = $('input[name="search_keyword"]').val();
		$.ajax({                                      
			type : "POST",
			url: 'http://edaily.vn/lib/tinymce/plugins/Block/search_related_news.php?key='+keyword+'&limit='+limit+'&offset='+offset,
			//data : {act:"loadRelationNews",q : keyword,limit : limit,offset : offset},
			dataType: "html",
			success : function(html){
				offset = offset + limit;            
				$('#art-sugg-content').html(html);
			}
		});
	}