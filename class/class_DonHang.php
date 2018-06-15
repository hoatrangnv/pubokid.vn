<?php
/**
*  Created by   :
*  @author		: Ong The Thanh
*  @date		: 2012/01/23
*  @version		: 0.0.1
*/ 
class DonHang extends dbBasic{
	function DonHang(){
		$this->pkey = "donhang_id";
		$this->tbl = DB_PREFIX."donhang";
	}
}
?>