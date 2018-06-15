<?php

class Nhandinh extends dbBasic{
	function Nhandinh(){
		$this->pkey = "nhandinh_id";
		$this->tbl = DB_PREFIX."nhandinh";
	}
    
    function getImage($_id, $w, $h){
		$res = $this->getOne($_id);
		$image = trim($res['image']);
        if(!$image) return 'http://media.webthethao.vn/resize_'.$w.'x'.$h.'/upload/nophoto.jpg';
        return 'http://media.webthethao.vn/resize_'.$w.'x'.$h.'/'.$image;
	} 
}
?>