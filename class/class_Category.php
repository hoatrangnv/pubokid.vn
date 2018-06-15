<?php
/**
*  Created by   :
*  @author		: Ong The Thanh
*  @date		: 2012/01/23
*  @version		: 0.0.1
*/ 
class Category extends dbBasic{
	function Category(){
		$this->pkey = "category_id";
		$this->tbl = DB_PREFIX."category";
	}
    function getViews($category_id, $field='views_day') {
        $clsNews = new News();
        return $clsNews->getSum($field, $clsNews->getCons($category_id));
    }
    function getTitle($id) {
        if($id==0) return 'Trang chủ';
        $res = $this->getOne($id);
        return $res['title'];
    }
    function getSlug($id) {
        $res = $this->getOne($id);
        return $res['slug'];
    }
    function getAllPostHome($category_id) {
        $clsPost = new Post();
        return $clsPost->getAll("category_id='".$category_id."' order by reg_date desc limit 20");
    }
    function getParentID($cat) {
        $res=$this->getOne($cat);
        return $res['parent_id'];
    }
    function slugToIdCat($slug,$sub = false) {
        if(!$sub) $parent_id = ' and parent_id = 0'; else $parent_id = ' and parent_id != 0';
        $all = $this->getAll("slug='".$slug."'".$parent_id."  order by ".$slug." desc limit 0,1");
        if($all[0]) return $all[0];
    }
	function getLink($id,$mobile = true){
        if($mobile == false) $pcms_url = 'http://tinnuocmy.com';
        else $pcms_url = PCMS_URL;
	    if($id == 0) return $pcms_url;
        $res = $this->getOne($id);
        if($res['link']) return $res['link'];
        return $pcms_url.'/'.$res['slug'].'/';
	}
    function getLinkRSS($Id) {
        $res = $this->getOne($Id);
        $slug = $res['slug'];        
        return PCMS_URL."/".$slug.".rss";        
            
    }  
    function countChild($id) {
        $res = $this->getCount('is_trash=0 and parent_id='.$id);
        return $res;
    }
    function countNews($id) {
        $clsNews = new News();
        $res = $clsNews->getCount('(category_id in(SELECT category_id FROM default_category WHERE parent_id='.$id.') or category_id='.$id.')');
        return $res;
    }
    function getSelectCat($name, $value, $class, $cat=0, $only_root=false) {
        $all = $this->getChild($cat);
        $html = '<select name="'.$name.'" class="'.$class.'">';
        $html .= '<option value=""> --- Select --- </option>';
        if($all) foreach($all as $one) { $one=$this->getOne($one);
            $selected = ''; if($one['category_id']==$value) $selected = 'selected="selected"';
            $html .= '<option value="'.$one['category_id'].'" '.$selected.'>'.$one['title'].'</option>';
            if(!$only_root) {
                $all = $this->getChild($one['category_id']);
                if($all) foreach($all as $one) { $one=$this->getOne($one);
                    $selected = ''; if($one['category_id']==$value) $selected = 'selected="selected"';
                    $html .= '<option value="'.$one['category_id'].'" '.$selected.'>| --- '.$one['title'].'</option>';
                    $all1 = $this->getChild($one['category_id']);
                    if($all1) foreach($all1 as $one) { $one=$this->getOne($one);
                        $selected = ''; if($one['category_id']==$value) $selected = 'selected="selected"';
                        $html .= '<option value="'.$one['category_id'].'" '.$selected.'>| ----- '.$one['title'].'</option>';
                    }
                }
            }
        }
        return $html.'</select>';
    }
    function getSelectCatLink($name, $value, $class, $cat=0, $only_root=false) {
        $all = $this->getChild($cat);
        $html = '<select name="'.$name.'" class="'.$class.'">';
        $html .= '<option value=""> --- Select --- </option>';
        if($all) foreach($all as $one) { $one=$this->getOne($one);
            $category = $this->getLink($one['category_id']);
            $selected = ''; if($category == $value) $selected = 'selected="selected"';
            $html .= '<option value="'.$this->getLink($one['category_id']).'" '.$selected.'>'.$one['title'].'</option>';
            if(!$only_root) {
                $all = $this->getChild($one['category_id']);
                if($all) foreach($all as $one) { $one=$this->getOne($one);
                    $category = $this->getLink($one['category_id']);
                    $selected = ''; if($category==$value) $selected = 'selected="selected"';
                    $html .= '<option value="'.$this->getLink($one['category_id']).'" '.$selected.'>| --- '.$one['title'].'</option>';
                    $all1 = $this->getChild($one['category_id']);
                    if($all1) foreach($all1 as $one) { $one=$this->getOne($one);
                        $category = $this->getLink($one['category_id']);
                        $selected = ''; if($category==$value) $selected = 'selected="selected"';
                        $html .= '<option value="'.$this->getLink($one['category_id']).'" '.$selected.'>| ----- '.$one['title'].'</option>';
                    }
                }
            }
        }
        return $html.'</select>';
    }
    function getSelectARR($name, $value, $class, $arr_cat) {
        $all = $arr_cat;
        $html = '<select name="'.$name.'" class="'.$class.'">';
        $html .= '<option value=""> --- Select --- </option>';
        if($all) foreach($all as $one) { $one=$this->getOne($one);
            $selected = ''; if($one['category_id']==$value) $selected = 'selected="selected"';
            $html .= '<option value="'.$one['category_id'].'" '.$selected.'>'.$one['title'].'</option>';
            if(!$only_root) {
                $all = $this->getChild($one['category_id']);
                if($all) foreach($all as $one) { $one=$this->getOne($one);
                    $selected = ''; if($one['category_id']==$value) $selected = 'selected="selected"';
                    $html .= '<option value="'.$one['category_id'].'" '.$selected.'>| --- '.$one['title'].'</option>';
                }
            }
        }
        return $html.'</select>';
    }
    function getChild($category_id) {
        $res = $this->getAll("is_trash=0 and parent_id='".$category_id."' order by menu_display, category_id");
        return $res;
    }
    function makeOptionCat($category_id, $value, $lv=0) {
        $key = MEMCACHE_NAME.$this->tbl.'getOptionCat'.$category_id.'_'.$value.'_'.$lv;
        $res = $this->getCache($key); if($res) return $res;
        if($category_id=='') $category_id = 0;
        $all = $this->getChild($category_id);
        $one = $this->getOne($category_id);
        if($lv>0) $a = '|'; else $a = '';
        $selected = ''; if($value==$one['category_id']) $selected='selected="selected"';
        $res='<option '.$selected.' value="'.$one['category_id'].'">'.$a.str_repeat("------", $lv).$one['title'].'</option>';
        if(is_array($all)) foreach($all as $one) { $one=$this->getOne($one);
            $res.=$this->makeOptionCat($one['category_id'], $value, $lv+1);
        }
        $this->setCache($key, $res);
        $this->setArrKey($key);
        return $res;
    }
    function isCatInCat($category_id, $category_id2) {
        if($category_id==$category_id2) return true;
        $one = $this->getOne($category_id);
        if($category_id2==$one['parent_id']) return true;
        return false;
    }
    function getBreadcrumb($category_id) {
        $key = MEMCACHE_NAME.$this->tbl.'getBreadcrumb'.$category_id;
        $res = $this->getCache($key); if($res) return $res;
        if($category_id=='') $category_id = 0;
        $arr = array();
        $arr[] = $category_id;
        while($category_id!=0) {
            $one = $this->getOne($category_id);
            $category_id = $one['parent_id'];
            $arr[] = $category_id;
        }
        $res = array();
        for($i=count($arr); $i>=0; $i--) if($arr[$i]!='') $res[] = $arr[$i];
        $this->setCache($key, $res);
        $this->setArrKey($key);
        return $res;
    }
    function getImage($_id,$w,$h){
        $one = $this->getOne($_id);
        if($one['image']) return PCMS_URL."/".$one['image'];
		else return PCMS_URL."/upload/folder.png";
	}
    function getCategoryRoot($category_id) {
        $parent_id = $category_id;
        while($parent_id) {
            $res = $parent_id;
            $parent_id = $this->getParentID($parent_id);
        } return $res;
    }
    function getTitleRoot($category_id) {
        $parent_id = $category_id;
        while($parent_id) {
            $res = $parent_id;
            $parent_id = $this->getParentID($parent_id);
        } return $this->getTitle($res);
    }
    function getLinkRoot($category_id) {
        $parent_id = $category_id;
        while($parent_id) {
            $res = $parent_id;
            $parent_id = $this->getParentID($parent_id);
        } return $this->getLink($res);
    }
}
?>