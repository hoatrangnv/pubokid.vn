<?php
/**
*  Created by   :
*  @author		: Ong The Thanh
*  @date		: 2012/01/23
*  @version		: 0.0.1
*/ 
class History extends dbBasic{
	function History(){
		$this->pkey = "history_id";
		$this->tbl = DB_PREFIX."history";
	}
    function add($data, $note) {
        $clsUser = new User();
        $oneUser = $clsUser->getMe();
        $user_id = $oneUser['user_id'];
        $news_id = $data['news_id'];
        return $this->insertOne(array('news_id'=>$news_id, 'data'=>json_encode($data), 'reg_date'=>date('Y-m-d H:i:s'), 'user_id'=>$user_id, 'note'=>$note));
    }
}
?>