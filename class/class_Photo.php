<?php
/**
*  Created by   :
*  @author		: Ong The Thanh
*  @date		: 2012/01/23
*  @version		: 0.0.1
*/ 
class Photo extends dbBasic{
	function Photo(){
		$this->pkey = "photo_id";
		$this->tbl = DB_PREFIX."photo";
	}
    function getLink($id){
        $res = $this->getOne($id);
        return PCMS_URL.'/'.$res['slug']."-photo".$id.".html";
    }
    function getImage($id, $w, $h){
		$res = $this->getOne($id);
		$image = trim($res['image']);
        if(!$image) $image=PCMS_URL.'/upload/nophoto.jpg';
        else return PCMS_URL.'/'.$image;
	}
        
}

?>