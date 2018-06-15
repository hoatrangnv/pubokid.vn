<?php
/**
*  Created by   :
*  @author		: Ong The Thanh
*  @date		: 2012/01/23
*  @version		: 0.0.1
*/ 
class Royalty extends dbBasic{
	function Royalty(){
		$this->pkey = "royalty_id";
		$this->tbl = "default_royalty";
	}
}
?>