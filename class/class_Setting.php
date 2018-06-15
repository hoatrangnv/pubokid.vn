<?php
/**
*  Created by   :
*  @author		: Ong The Thanh
*  @date		: 2012/01/23
*  @version		: 0.0.1
*/ 
class Setting extends dbBasic{
	function Setting(){
		$this->pkey = "setting_id";
		$this->tbl = DB_PREFIX."setting";
	}
    function getTitle(){
        $one = $this->getOne(1);
        return $one['title'];
	}
    function getMetaDes(){
        $one = $this->getOne(1);
        return $one['meta_des'];
	}
    function getMetaKey(){
        $one = $this->getOne(1);
        return $one['meta_key'];
	}
    function getNamePage(){
        $one = $this->getOne(1);
        return $one['name_page'];
	}
    function getAddress(){
        $one = $this->getOne(1);
        return $one['address'];
	}
    function getEmail(){
        $one = $this->getOne(1);
        return $one['email'];
	}
    function getSdt(){
        $one = $this->getOne(1);
        return $one['sdt'];
	}
    function getFacebook(){
        $one = $this->getOne(1);
        return $one['facebook'];
	}
    function getLogo(){
        $one = $this->getOne(1);
        return $one['logo'];
	}
    function getFrame(){
        $one = $this->getOne(1);
        return $one['is_iframe'];
	}
    function getOptions(){
        $one = $this->getOne(1);
        return get_object_vars(json_decode($one['options']));
	}
}
?>