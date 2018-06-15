<?php
/**
*  Defautl action
*  @author        : Ong Thế Thành    
*  @date        : 2012/01/23    
*  @version        : 0.0.1
*/
function default_default(){
    global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg;
    #
    $clsCategory = new Category(); $assign_list['clsCategory'] = $clsCategory;
    $clsUser = new User(); $assign_list['clsUser'] = $clsUser; $me = $clsUser->getMe(); $assign_list['me'] = $me;
    $classTable = ucfirst(strtolower($mod));
    $clsClassTable = new $classTable; $assign_list["clsClassTable"] = $clsClassTable;
    $pkeyTable = $clsClassTable->pkey; $assign_list["pkeyTable"] = $pkeyTable;
    #
    
    if($_POST['crawler_news']) {
        include_once("lib/simple_html_dom.php");
        if($_POST['source'] == 'dantri.com.vn') $mes = $clsClassTable->dantri("http://dantri.com.vn/the-thao.htm");
        if($_POST['source'] == 'thethao.vnexpress.net') $mes = $clsClassTable->vnexpress("http://thethao.vnexpress.net/");
        $assign_list["mes"] = $mes;
    }
           
    $cons = "is_trash=0 and is_xuly=0";// and category_id>0
    if(isset($_GET['keyword']) && $_GET['keyword']) {
        $slug = $core->toSlug($_GET['keyword']);
        $cons .= " and slug like '%".$slug."%'";
        $assign_list["keyword"] = $_GET['keyword'];
    }
    if($_GET['source']) {
        $cons .= " and source = '".$_GET['source']."'";
    }
    #
    $order = ' crawler_date desc';

    if(isset($_GET['date_from']) && $_GET['date_from']) $cons .= " and crawler_date>='".strtotime($_GET['date_from'])."'";
    if(isset($_GET['date_to']) && $_GET['date_to']) $cons .= " and crawler_date<='".(strtotime($_GET['date_to'])+24*60*60)."'";

    #
    if($_GET['is_trash']) header('Location: /admin.php?mod=news_crawler&act=in_trash');
    $listItem = $clsClassTable->getListPage($cons." order by".$order, 50);//echo $order;
    $paging = $clsClassTable->getNavPageAdmin($cons, 50);
    $assign_list["listItem"] = $listItem;
    $assign_list["paging"] = $paging;
    $assign_list["cursorPage"] = isset($_GET["page"])? $_GET["page"] : 1;
    #
    $listCategory = $clsCategory->getAll("is_trash=0 and parent_id=0 order by order_no");
    $assign_list["listCategory"] = $listCategory;
    #
    /*=============Title & Description Page==================*/
    $title_page = "List - ".$classTable; if(isset($_GET['mes']) && $_GET['mes']=='updateSuccess') $title_page = "Update Success!";
    $description_page = '';
    $keyword_page = '';
    /*=============Content Page==================*/
}
function default_edit(){
    global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg;
    #
    $clsChannel = new Channel(); $assign_list['clsChannel'] = $clsChannel;
    $classTable = ucfirst(strtolower($mod));
    $clsClassTable = new $classTable; $assign_list["clsClassTable"] = $clsClassTable;
    $oneItem = $clsClassTable->getOne($_GET['id']);
    $clsUser = new User;
    $me = $clsUser->getMe(); $assign_list["me"] = $me;
    
    if($oneItem) foreach($oneItem as $key => $val) $assign_list[$key] = $val;
    $tableName = $clsClassTable->tbl;
    $pkeyTable = $clsClassTable->pkey;
    #
    $clsCategory = new Category(); $assign_list["clsCategory"] = $clsCategory;
    #
    if($_POST) {
        $_POST['last_edit'] = time();
        if($oneItem['is_push']=='1' && $_POST['is_push']=='0') $_POST['is_unpush'] = '1';
        if($oneItem['is_push']=='0' && $_POST['is_push']=='1') $_POST['is_unpush'] = '0';
        
        $_POST['channel_path'] = '|'.trim($_POST['channel_path'], '|').'|';
        $_POST['news_related'] = '|'.trim($_POST['news_related'], '|').'|';
        #
        $note = $_POST['note']; unset($_POST['note']);
        #
        if($_POST['show_date']) {
            $arr = explode(' ', $_POST['show_date']);
            $date = explode('/', $arr[0]);
            $_POST['show_date'] = $date[2].'-'.$date[1].'-'.$date[0].' '.$arr[1].':00';
        } else {
            $_POST['push_date'] = time();
            $_POST['show_date'] = date("Y-m-d H:i:00");
        }
        
        if($_POST['pick_date']) {
            $arr = explode(' ', $_POST['pick_date']);
            $date = explode('/', $arr[0]);
            $_POST['pick_date'] = strtotime($date[2].'-'.$date[1].'-'.$date[0].' '.$arr[1].':00');
        }
        
        #        
        
        if($_POST['is_hot']==1 && !$oneItem['is_hot']) {
            $_POST['hot_date']=time();   
        }
        if($_POST['is_pick']==1 && !$oneItem['is_pick']) $_POST['pick_date']=time();
        if($_POST['is_featured']==1 && !$oneItem['is_featured']) $_POST['featured_date']=time();
        if($_POST['is_top']==1 && !$oneItem['is_top']) $_POST['top_date']=time();
        if($_POST['is_push']==1 && !$oneItem['is_push']) {
            $_POST['push_date'] = time();
            $_POST['user_push'] = $me['user_id'];
        } 
        
        if(strtotime($_POST['show_date']) > time()) {
            $_POST['push_date'] = strtotime($_POST['show_date']);
            $_POST['hot_date'] = strtotime($_POST['show_date']);
            $_POST['pick_date'] = strtotime($_POST['show_date']);
            $_POST['featured_date'] = strtotime($_POST['show_date']);
            $_POST['top_date'] = strtotime($_POST['show_date']);
        } 
        
        
        if($_POST['show_date']!= $oneItem['show_date']) {
            $_POST['push_date'] = strtotime($_POST['show_date']);
            $_POST['hot_date']=strtotime($_POST['show_date']);
            $_POST['pick_date']=strtotime($_POST['show_date']);
            $_POST['featured_date']=strtotime($_POST['show_date']);
            $_POST['top_date']=strtotime($_POST['show_date']);
        }
        if($_POST['image'] && $_POST['image']!='') {
            $image= $core->ftpUrlUpload($_POST['image'], 'upload', $oneItem['slug'].rand(1,9), $oneItem['reg_date']);
        } else if($_FILES['image']['name']) {
            $image = $core->ftpUpload('image', 'upload', $oneItem['slug'].rand(1,9), $oneItem['reg_date'],true);
        } unset($_POST['image']);

        if($_POST['image_bot'] && !$_FILES['image']['name']) {
            $folder_root = getcwd();
            $url_image = $_POST['image_bot'];
            if(substr($url_image,0,2)=='//') $url_image = 'http:'.$url_image;
            $title_image = $oneItem['slug'];
            $year = date('Y');
            $month = date('m');
            $day = date('d');
            $path = 'upload/'.$year.'/'.$month.'/'.$day;
            $pathYear = $folder_root.'/upload/'.$year.'/';
            if (!is_dir($pathYear)) {
                mkdir($pathYear,0777);         
            }
            $pathMonth = $folder_root.'/upload/'.$year.'/'.$month.'/';
            if (!is_dir($pathMonth)) {
                mkdir($pathMonth,0777);         
            }
            $pathDay = $folder_root.'/upload/'.$year.'/'.$month.'/'.$day.'/';
            if (!is_dir($pathDay)) {
                mkdir($pathDay,0777);         
            }
            $image = $path.'/'.$title_image.'.jpg';
            file_put_contents($folder_root.'/'.$image, file_get_contents($url_image));
        } 
        if($image) $_POST['image'] = $image;else $_POST['image'] = $oneItem['image'];
        unset($_POST['image_bot']);
        #
        if($_POST['tags']) {
            $arrTags = explode(",",$_POST['tags']);   
            $clsTags = new Tags;
            foreach($arrTags as $oneTag) {
                $tags_id = $clsTags->getAll('slug = "'.$core->toSlug(trim($oneTag)).'" limit 1');
                if(!$tags_id[0]) {
                    $clsTags->insertOne(array("title"=>trim($oneTag),"slug"=>$core->toSlug(trim($oneTag))));
                }
            }
        }
        #
        $_POST['content'] = str_replace("<p>&nbsp;</p>","",$_POST['content']);
        $_POST['content'] = str_replace("<br />","</p><p>",$_POST['content']);
        $guiduyet = $_POST['guiduyet'];unset($_POST['guiduyet']);


        if($clsClassTable->updateOne($_GET['id'],$_POST)) {
            $clsClassTable->deleteArrKey();
            if($_POST['is_push'] == 1) {
                $clsClassTable->updateOne($_GET['id'],array("is_xuly"=>1));
                $clsNews = new News;
                $clsNews->setShowDate($_POST['show_date']);
                $_POST['is_crawler'] = 1;
                $_POST['user_id'] = $me['user_id'];
                $_POST['reg_date'] = time();
                $_POST['status'] = 3;
                $_POST['is_draft'] = 0;
                $_POST['news_crawler_id'] = $_GET['id'];
                $clsNews->insertOne($_POST);
                $maxId = mysql_insert_id();
                $data = $clsNews->getOne($maxId);
                $clsHistory = new History();
                $clsHistory->add($data, "Viết bài mới từ crawler");
                if($me['user_level_id'] >= 3) $news = 'news';
                else if($me['user_level_id'] == 2) $news = 'news_btv';
                else if($me['user_level_id'] == 1) $news = 'news_ctv';
                header('location: ?mod='.$news.'&act=edit&id='.$maxId.'&mes=insertSuccess');
                exit();
            }
            
            if($guiduyet) {
               $clsClassTable->updateOne($_GET['id'],array("is_xuly"=>1));
               $_POST['status'] = 1;
               $_POST['user_id'] = $me['user_id'];
               $_POST['reg_date'] = time();
               $_POST['is_draft'] = 0;
               $_POST['is_crawler'] = 1;
               $_POST['news_crawler_id'] = $_GET['id'];
               $clsNews = new News;
               $clsNews->insertOne($_POST);
               $maxId = mysql_insert_id();
               $data = $clsNews->getOne($maxId);
               $clsHistory = new History();
               $clsHistory->add($data, "Gửi duyệt từ crawler");
               if($me['user_level_id'] >= 3) $news = 'news';
                else if($me['user_level_id'] == 2) $news = 'news_btv';
                else if($me['user_level_id'] == 1) $news = 'news_ctv';
                header('location: ?mod='.$news.'&act=edit&id='.$maxId.'&mes=insertSuccess');
                exit();
            }
            
            header('location: ?mod='.$mod.'&act='.$act.'&id='.$_GET['id'].'&mes=updateSuccess');
            exit();
        }
        else {
            print_r($_POST); die();
            foreach ($_POST as $key => $val) {
                $assign_list[$key] = $val;
            }
            $msg = "Chỉnh sửa thất bại!";
        }
        unset($_POST);
    }
    #
    /*=============Title & Description Page==================*/
    $title_page = "Edit - ".$classTable; if($_GET['mes']=='updateSuccess') $title_page = "Update Success!";
    $description_page = '';
    $keyword_page = '';
    /*=============Content Page==================*/
}
function default_in_trash(){
	global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg;
    #
    $clsCategory = new Category(); $assign_list['clsCategory'] = $clsCategory;
    $classTable = ucfirst(strtolower($mod));
    $clsClassTable = new $classTable; $assign_list["clsClassTable"] = $clsClassTable;
    $pkeyTable = $clsClassTable->pkey; $assign_list["pkeyTable"] = $pkeyTable;
    #
    $listItem = $clsClassTable->getListPage("is_trash=1 order by ".$pkeyTable." desc", RECORD_PER_PAGE, 'CMS');
    $paging = $clsClassTable->getNavPage("is_trash=1", RECORD_PER_PAGE, 'CMS');
    $assign_list["listItem"] = $listItem;
    $assign_list["paging"] = $paging;
    $assign_list["cursorPage"] = isset($_GET["page"])? $_GET["page"] : 1;
    #
	/*=============Title & Description Page==================*/
	$title_page = "In trash - ".$classTable;
	$description_page = '';
	$keyword_page = '';
	/*=============Content Page==================*/
}
function default_trash(){
    global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg;
    #
	$id = $_GET['id'];
    if(!$id) die('Not ID!');
    $classTable = ucfirst(strtolower($mod));
    $clsClassTable = new $classTable;
    #
    $oneNews = $clsClassTable->getOne($id);
    $clsUser = new User(); $me = $clsUser->getMe();
    if($me['user_id']!=$oneNews['user_id'] && $me['user_level_id']<3) die('Bạn không thể xóa bài của user khác!');
    #
    if($clsClassTable->updateOne($id,array('is_trash'=>1))) {
        $clsClassTable->deleteArrKey();$clsClassTable->deleteArrKey('CMS');
        die('1');
    }
    else die('Update!');
}
function default_restore(){
    global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg;
    #
	$id = $_GET['id'];
    if(!$id) die('0');
    $classTable = ucfirst(strtolower($mod));
    $clsClassTable = new $classTable;
    $clsClassTable->deleteArrKey();$clsClassTable->deleteArrKey('CMS');
    if($clsClassTable->updateOne($id,array('is_trash'=>'0'))) {
        die('1');
    }
    else die('0');
}
function default_delete(){
    global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg;
    #
	$id = $_GET['id'];
    if(!$id) die('0');
    $classTable = ucfirst(strtolower($mod));
    $clsClassTable = new $classTable;
    if($clsClassTable->deleteOne($id)) {
        $clsClassTable->deleteArrKey('CMS');
        die('1');
    }
    else die('0');
}
function default_load_ajax(){
    global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg;
    #
	$title = $_GET['title'];
    if(!$title) die('0');
    $classTable = ucfirst(strtolower($mod));
    if(!class_exists($classTable)) die('System not found mod \''.$classTable.'\'');
    $clsClassTable = new $classTable;
    $slug = $core->toSlug($title);
    $all_news = $clsClassTable->getAll("slug like '%".$slug."%' order by show_date desc limit 10");
    if($all_news) {
        $html = '<ul>';
        foreach($all_news as $oneNews) { $one=$clsClassTable->getOne($oneNews);
            $html .= "<li><a href='#' rel='".$one['channel_id']."'>".$one['title']."</a></li>";
        }
        die($html.'</ul>');
    } else die('0');
}
?>