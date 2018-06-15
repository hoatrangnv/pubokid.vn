<?php
/**
*  Created by   :
*  @author		: Ong The Thanh
*  @date		: 2012/01/23
*  @version		: 0.0.1
*/ 
class Tags extends dbBasic{
	function Tags(){
		$this->pkey = "tags_id";
		$this->tbl = DB_PREFIX."tags";
	}
}
?>