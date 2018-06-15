<?php
/**
*  Created by   :
*  @author		: Ong The Thanh
*  @date		: 2012/01/23
*  @version		: 0.0.1
*/ 
class Banner extends dbBasic{
	function Banner(){
		$this->pkey = "banner_id";
		$this->tbl = DB_PREFIX."banner";
	}
 }
 ?>