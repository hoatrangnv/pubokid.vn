<?php
/**
*  Created by   :
*  @author		: Ong The Thanh
*  @date		: 2012/01/23
*  @version		: 0.0.1
*/ 
class Signature extends dbBasic{
	function Signature(){
		$this->pkey = "signature_id";
		$this->tbl = DB_PREFIX."signature";
	}
    function getTitle($id){
        $res = $this->getOne($id);
        return $res['title'];
    }
    function getSelect($name, $value, $class, $user_id=0) {
        $all = $this->getAll("is_trash=0 and user_id='".$user_id."' order by signature_id desc");
        $html = '<select name="'.$name.'" class="'.$class.'">';
        $html .= '<option value="0"> --- Select --- </option>';
        if($all) foreach($all as $one) { $one=$this->getOne($one);
            $selected = ''; if($one['signature_id']==$value) $selected = 'selected="selected"';
            $html .= '<option value="'.$one['signature_id'].'" '.$selected.'>'.$one['title'].'</option>';
        }
        return $html.'</select>';
    }
}

?>