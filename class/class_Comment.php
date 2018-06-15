<?php
/**
*  Created by   :
*  @author		: Ong The Thanh
*  @date		: 2012/01/23
*  @version		: 0.0.1
*/ 
class Comment extends dbBasic{
	function Comment(){
		$this->pkey = "comment_id";
		$this->tbl = DB_PREFIX."comment";
	}
}
?>