<?php
/**
*  Created by   :
*  @author		: Ong The Thanh
*  @date		: 2012/01/23
*  @version		: 0.0.1
*/ 
class AdsContent extends dbBasic{
	function AdsContent(){
		$this->pkey = "ads_content_id";
		$this->tbl = DB_PREFIX."ads_content";
	}
    function getContent($ads_id, $category_id) {
        global $mod;
        if($mod == 'ads') $all = $this->getAll("ads_id='".$ads_id."' and category_id='".$category_id."'",false,"KEYARR_ADS");
        else $all = $this->getAll("ads_id='".$ads_id."' and category_id='".$category_id."'",true,"KEYARR_ADS"); 
        $one = $all[0];
        if($one) {
            $one = $this->getOne($one);
            return $one['content'];
        }
        else return '';
    }
}
?>