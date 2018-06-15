<?php
/**
*  Created by   :
*  @author		: Ong The Thanh
*  @date		: 2012/01/23
*  @version		: 0.0.1
*/ 
class Partner extends dbBasic{
	function Partner(){
		$this->pkey = "partner_id";
		$this->tbl = DB_PREFIX."partner";
	}
    function getImage($partner_id,$w,$h){
		$res = $this->getOne($partner_id);
		$image = $res['image'];
        if(!$image) $image=PCMS_URL.'/upload/nophoto.jpg';
		else return $image;
	}
    function get5Parter() {
        $key = MEMCACHE_NAME.$this->tbl.'get5Parter';
        $res = $this->getCache($key); if($res) return $res;
        $clsNews = new News();
        $res = $this->getAll("is_trash=0 order by order_no desc limit 0,5");
        $this->setCache($key, $res);
        $this->setArrKey($key);
        return $res;
    }
}
?>