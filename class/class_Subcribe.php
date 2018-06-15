<?php
/**
*  Created by   :
*  @author		: Ong The Thanh
*  @date		: 2012/01/23
*  @version		: 0.0.1
*  1.cat
*  2.detail
*  3.other
* 
*/ 
class Subscribe extends dbBasic{
	function Subscribe(){
		$this->pkey = "subscribe_id";
		$this->tbl = DB_PREFIX."subscribe";
	}
}
?>