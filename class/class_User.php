<?php
/**
*  Created by   :
*  @author		: Ong The Thanh
*  @date		: 2012/01/23
*  @version		: 0.0.1
*/ 
class User extends dbBasic{
	function User(){
		$this->pkey = "user_id";
		$this->tbl = DB_PREFIX."user";
	}
    function getMe(){
        $user = $_SESSION['USER']; if(!$user) {$user = $_COOKIE['USER']; $_SESSION['USER'] = $user;}
        $all = $this->getAll("is_trash=0 and is_lock = 0 and user_name='".addslashes($user)."'");
        return $this->getOne($all[0]);
	}
	function checkUser($user_name, $user_pass){
        $all = $this->getAll("is_trash=0 and is_lock = 0 and user_name='".addslashes($user_name)."'");
        $one = $all[0]; $one=$this->getOne($one);
        if($one['user_pass']==$user_pass && $one['user_pass']!='') return true;
        else return false;
	}
    function checkToLogin() {
        setcookie("USER", $_SESSION['USER'], time()+31536000);
        setcookie("PASS", $_SESSION['PASS'], time()+31536000);
        if(!$this->checkUser($_SESSION['USER'], $_SESSION['PASS']))
        header('location: admin.php?mod=user&act=login&u='.rawurlencode($core->selfURL()));
    }
    function login($user_name, $user_pass){
        $all = $this->getAll("is_trash=0 and is_lock = 0 and user_name='".addslashes($user_name)."'");
        $one = $all[0]; $one=$this->getOne($one);
        if($one['user_pass']==md5($user_pass) && $one['user_pass']!='') {
            $_SESSION['USER'] = ($user_name);
            $_SESSION['PASS'] = md5($user_pass);
            $_SESSION['LOGIN_JQUERYIMAGE'] = 'DUC';
            setcookie("USER", $_SESSION['USER'], time()+31536000);
            setcookie("PASS", $_SESSION['PASS'], time()+31536000);
            return true;
        }
        else return false;
	}
    function getUserLevel() {
        $one = $this->getMe();
        return $one['user_level_id'];
    }
    function getSelectLevel($name, $value) {
        $res = '<select name="'.$name.'"><option>--- Select ---</option>';
        for($i=2; $i<3; $i++) {
            if (($i+1)==3) $title = 'Quản trị';
            elseif (($i+1)==2) $title = 'Biên tập viên';
            else $title = 'Cộng tác viên';
            $selected = '';
            if(($i+1)==$value) $selected='selected="selected"';
            $res.='<option value="'.($i+1).'" '.$selected.'>'.$title.'</option>';
        }
        return $res.'</select>';
    }
    function getChucVu($user_level_id) {
        if ($user_level_id==3) return 'Quản trị';
        elseif($user_level_id==2) return 'Biên tập viên';
        elseif($user_level_id==1) return 'Cộng tác viên';
        else return false;
    }
    function is_exits_user($user_name) {
        $all = $this->getAll("is_trash=0 and user_name='".addslashes($user_name)."'");
        if($all[0]) return true;
        else return false;
    }
    function permissionCat($category_id) {
        $this->checkToLogin();
        $one = $this->getMe();
        if($one['user_level_id']==3) return true;
        elseif($one['user_level_id']<3) {
            $category_id2 = $one['category_id'];
            $clsCategory = new Category();
            if($clsCategory->isCatInCat($category_id, $category_id2)) return true;
        } return false;
    }
    function permissionNews($news_id) {
        $this->checkToLogin();
        $one = $this->getMe();
        if($one['user_level_id']==3) return true;
        elseif($one['user_level_id']==2) {
            $category_id = $one['category_id'];
            $clsNews = new News();
            if($clsNews->isNewsInCat($news_id, $category_id)) return true;
        } elseif($one['user_level_id']==1) {
            $category_id = $one['category_id'];
            $clsNews = new News();
            $oneNews = $clsNews->getOne($news_id);
            if($oneNews['user_id']==$one['user_id'] && $oneNews['is_draft']==1 && $clsNews->isNewsInCat($news_id, $category_id)) return true;
        } return false;
    }
    function getUserName($user_id) {
        $one = $this->getOne($user_id);
        return $one['user_name'];
    }
    function getFullName($user_id) {
        $one = $this->getOne($user_id);
        return $one['full_name'];
    }
    function UserNameToFullName($user_name) {
        $one = $this->getAll('is_trash = 0 and user_name ="'.$user_name.'" limit 1');
        return $this->getFullName($one[0]);
    }
    function getSelect($name, $value, $class) {
        $all = $this->getAll("is_trash=0 order by user_name");
        $html = '<select name="'.$name.'" class="'.$class.'">';
        $html .= '<option value="0"> --- Select --- </option>';
        if($all) foreach($all as $one) { $one=$this->getOne($one);
            $selected = ''; if($one['user_id']==$value) $selected = 'selected="selected"';
            $html .= '<option value="'.$one['user_id'].'" '.$selected.'>'.$one['user_name'].'    ('.$one['full_name'].')</option>';
        }
        return $html.'</select>';
    }
    function getSelectMuti($name, $value, $class) {
        $all = $this->getAll("is_trash=0 order by user_name");
        $html = '<select multiple="multiple" name="'.$name.'" class="'.$class.'">';
        
        if(!$value) $selected = 'selected=""';
        $html .= '<option value="0" '.$selected.' style="padding: 4px 4px;    border-bottom: 1px solid #F3F3F3;"> --- Select --- </option>';
        
        $arrvalue = explode(",",$value);
        
        if($all) foreach($all as $one) { $one=$this->getOne($one);
            $selected = ''; if(in_array($one['user_id'],$arrvalue)) $selected = 'selected="selected"';
            $html .= '<option value="'.$one['user_id'].'" '.$selected.' style="padding: 4px 4px;    border-bottom: 1px solid #F3F3F3;">'.$one['user_name'].'    ('.$one['full_name'].')</option>';
        }
        return $html.'</select>';
    }
    function getImage($_id, $w, $h){
		$res = $this->getOne($_id);
		$image = trim($res['image']);
        if(!$image) return false;
        return 'http://media.webthethao.vn/resize_'.$w.'x'.$h.'/'.$image;
	}
    function getModule() {
        $arr = array();
        $arr['news'] = 'Bài viết';
        $arr['category'] = 'Danh mục';
        $arr['banner'] = 'Banner';
        $arr['donhang'] = 'Đơn hàng';
        $arr['user'] = 'Thành viên';
        $arr['setting'] = 'Cài đặt';
        return $arr;
    }
    function getAction() {
        $arr = array();
        $arr['default'] = 'Xem danh sách';
        $arr['edit'] = 'Sửa đổi';
        $arr['new'] = 'Tạo mới';
        $arr['trash'] = 'Xóa tạm';
        $arr['restore'] = 'Phục hồi';
        $arr['delete'] = 'Xóa vĩnh viễn';
        $arr['in_trash'] = 'Xem danh sách xóa tạm';
        return $arr;
    }
}
?>