<?php
/**
*  Created by   :
*  @author		: Ong The Thanh
*  @date		: 2012/01/23
*  @version		: 0.0.1
*/ 
class Channel extends dbBasic{
	function Channel(){
		$this->pkey = "channel_id";
		$this->tbl = DB_PREFIX."channel";
	}
    function getTitle($id) {
        $res = $this->getOne($id);
        return $res['title'];
    }
    function getLink($channel_id,$mobile=true){
                if($mobile == false) $pcms_url = 'http://tinnuocmy.com';
        else $pcms_url = PCMS_URL;
        $res = $this->getOne($channel_id);
        return $pcms_url.'/'.$res['slug'].'-chuyen-de-'.$channel_id.'/';
    }
    function getCons() {
        $cons = "is_trash=0 and is_push=1";
        
        return $cons;
    }
    function getImage($channel_id,$w,$h) {
        $clsNews = new News;
        $channel=$clsNews->getAll($clsNews->getCons()." and channel_path like '%|".$channel_id."|%' order by push_date desc, show_date desc limit 1");
        return $clsNews->getImage($channel[0],$w,$h);
    }

}
?>