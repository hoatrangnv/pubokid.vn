<?php
/**
*  Created by   :
*  @author		: Ong The Thanh
*  @date		: 2012/01/23
*  @version		: 0.0.1
*/ 
class Profile extends dbBasic{
	function Profile(){
		$this->pkey = "profile_id";
		$this->tbl = DB_PREFIX."profile";
	}
}
?>