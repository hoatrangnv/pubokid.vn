<?php
/**
*  Created by   :
*  @author		: Ong The Thanh
*  @date		: 2012/01/23
*  @version		: 0.0.1
*/ 
class Theloai extends dbBasic{
	function Theloai(){
		$this->pkey = "theloai_id";
		$this->tbl = DB_PREFIX."theloai";
	}
        function getTitle($_id){
        $res = $this->getOne($_id);
        return $res['title'];
    }
    function getSelectCat($name, $value, $class) {
        $all = $this->getAll();
        $html = '<select name="'.$name.'" class="'.$class.'">';
        $html .= '<option value="0"> --- Select --- </option>';
        if($all) foreach($all as $one) { $one=$this->getOne($one);
            $selected = ''; if($one[$this->pkey]==$value) $selected = 'selected="selected"';
            $html .= '<option value="'.$one[$this->pkey].'" '.$selected.'>'.$one['title'].'</option>';
        }
        return $html.'</select>';
    }
    function getSotien($_id){
        $res = $this->getOne($_id);
        return $res['sotien'];
    }
 }
 ?>