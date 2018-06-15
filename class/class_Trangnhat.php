<?php
/**
*  Created by   :
*  @author		: Ong The Thanh
*  @date		: 2012/01/23
*  @version		: 0.0.1
*/ 
class Trangnhat extends dbBasic{
	function Trangnhat(){
		$this->pkey = "trangnhat_id";
		$this->tbl = DB_PREFIX."trangnhat";
	}
    function getLink($id){
        $res = $this->getOne($id);
        return PCMS_URL.'/'.$res['slug'].'-tn'.$id.'.html';
    }
    function getImage($id, $w, $h){
		$res = $this->getOne($id);
		$image = trim($res['image']);
        if(!$image) return 'http://media.webthethao.vn/resize_'.$w.'x'.$h.'/upload/nophoto.jpg';
        return 'http://media.webthethao.vn/resize_'.$w.'x'.$h.'/'.$image;
	}
}
?>