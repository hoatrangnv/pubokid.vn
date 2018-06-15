<?php
/**
*  Created by   :
*  @author		: Ong The Thanh
*  @date		: 2012/01/23
*  @version		: 0.0.1
*/ 
class Source extends dbBasic{
	function Source(){
		$this->pkey = "source_id";
		$this->tbl = DB_PREFIX."source";
	}
    function getTitle($source_id) {
        if(!$source_id) return 'Vietq';
        $res = $this->getOne($source_id);
        return $res['title'];
    }
    function getLink($source_id, $type=0) {
        if(!$source_id) return '#';
        $res = $this->getOne($source_id);
        return PCMS_URL.'/'.$res['slug']."-source".$source_id."/";
    }
}
?>