<?php
/**
*  Created by   :
*  @author		: Ong The Thanh
*  @date		: 2012/01/23
*  @version		: 0.0.1
*/ 
class Team extends dbBasic {
	function Team(){
		$this->pkey = "team_id";
		$this->tbl = "data_team";
	}
    function getTitle($_id) {
        $res = $this->getOne($_id);
        return $res['title'];
    }
    function getImage($_id, $w, $h){
		$res = $this->getOne($_id);
		$image = trim($res['image']);
        if(!$image) return false;
        return 'http://media.thethaoso360.com/thumb_x'.$w.'x'.$h.'/'.$image;
	}
}

?>