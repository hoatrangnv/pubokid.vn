<?php
/**
*  Created by   :
*  @author		: Ong The Thanh
*  @date		: 2012/01/23
*  @version		: 0.0.1
*/ 
class Avote extends dbBasic {
	function Avote(){
		$this->pkey = "avote_id";
		$this->tbl = DB_PREFIX."avote";
	}
    function getTitle($id) {
        $res = $this->getOne($id);
        return $res['title'];
    }
    function getNumber($id) {
        $res = $this->getOne($id);
        return $res['amount'];
    }
    function getTotal($vote_id) {
        $res = $this->getSum('amount','vote_id='.$vote_id);
        return $res;
    }
    function getImage($news_id, $w, $h){
		$res = $this->getOne($news_id);
		$image = trim($res['image']);
        if(!$image) return 'http://media.webthethao.vn/resize_'.$w.'x'.$h.'/upload/nophoto.jpg';
        return 'http://media.webthethao.vn/resize_'.$w.'x'.$h.'/'.$image;
	}
}
?>