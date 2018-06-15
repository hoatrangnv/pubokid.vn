<?php
/**
*  Created by   :
*  @author		: Ong The Thanh
*  @date		: 2012/01/23
*  @version		: 0.0.1
*/ 
class Vote extends dbBasic {
	function Vote(){
		$this->pkey = "vote_id";
		$this->tbl = DB_PREFIX."vote";
	}
    function getTitle($id) {
        $res = $this->getOne($id);
        return $res['title'];
    }
    function getAnswer($id) {
        $clsAvote = new Avote;
        return $clsAvote->getAll('vote_id='.$id);
    }
    function getImage($news_id, $w, $h){
		$res = $this->getOne($news_id);
		$image = trim($res['image']);
        if(!$image) return 'http://media.webthethao.vn/resize_'.$w.'x'.$h.'/upload/nophoto.jpg';
        return 'http://media.webthethao.vn/resize_'.$w.'x'.$h.'/'.$image;
	}
    function getSelect($name, $value, $class='') {
        $all = $this->getAll("is_trash=0 and is_dudoan=1 order by vote_id desc limit 10");
        $html = '<select name="'.$name.'" class="'.$class.'">';
        $html .= '<option value=""> --- Select --- </option>';
        if($all) foreach($all as $one) { $one=$this->getOne($one);
            $selected = ''; if($one['vote_id']==$value) $selected = 'selected="selected"';
            $html .= '<option value="'.$one['vote_id'].'" '.$selected.'>'.$one['title'].'</option>';
        }
        return $html.'</select>';
    }
}
?>