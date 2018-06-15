<?php
/**
*  Created by   :
*  @author		: Ong The Thanh
*  @date		: 2012/01/23
*  @version		: 0.0.1
*/ 
class Words extends dbBasic{
	function Words(){
		$this->pkey = "word_id";
		$this->tbl = DB_PREFIX."words";
	}
    function getAllWords(){
        $cons = "is_trash = 0 and is_push=1 order by uutien desc, word_id";
        $allwords=$this->getAll($cons);
        return $allwords;
    }
}
?>