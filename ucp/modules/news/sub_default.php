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
    if(!$_GET['is_draft'] && !$_GET['status'] && !$_GET['user_id']) header('Location: '.PCMS_URL.'/admin.php?mod=news&act=default&status=3&is_draft=0&is_push=1&is_unpush=0');
    #
    $cons = "is_trash=0 and is_live = 0";// and category_id>0
    if($_GET['is_hot']=='1') $cons .= " and is_hot=1";
    if($_GET['is_pick']=='1') $cons .= " and is_pick=1";
    if($_GET['is_featured']=='1') $cons .= " and is_featured=1";
    if($_GET['is_picture']=='1') $cons .= " and is_picture=1";
    if($_GET['status']) $cons .= " and status=".$_GET['status'];
    if($_GET['user_id']=='me') $cons .= " and user_id='".$me['user_id']."'";
    if($_GET['user_id']) $cons .= " and user_id=".$_GET['user_id'];
    #
    if(isset($_GET['is_draft'])) $cons .= " and is_draft=".$_GET['is_draft'];
    if(isset($_GET['is_push'])) $cons .= " and is_push=".$_GET['is_push'];
    if(isset($_GET['is_unpush'])) $cons .= " and is_unpush=".$_GET['is_unpush'];
    #
    if($_GET['category_id']) $cons.=" and (category_id=".$_GET['category_id']." or category_id in(SELECT category_id FROM default_category WHERE parent_id=".$_GET['category_id']."))";
    if($_GET['keyword']) {
        $slug = $core->toSlug($_GET['keyword']);
        $cons .= " and (slug like '%".$slug."%' or title like '%".$_GET['keyword']."%')";
        $assign_list["keyword"] = $_GET['keyword'];
    }
    if($_GET['tags']) {
        $cons .= " and (tags like '%,".$_GET['tags'].",%' or tags like '%, ".$_GET['tags'].",%')";
    }
    #
    $order = 'last_edit desc, reg_date desc';
    if($_GET['status']=='1' && $_GET['is_draft']=='0') $order = 'reg_date desc, last_edit desc';
    elseif($_GET['status']=='3' && $_GET['is_draft']=='0' && $_GET['is_push']=='0' && $_GET['is_unpush']=='0') $order = 'reg_date desc, last_edit desc';
    elseif($_GET['status']=='3' && $_GET['is_draft']=='0' && $_GET['is_push']=='1' && $_GET['is_unpush']=='0') {$order = 'push_date desc, last_edit desc'; $assign_list["news_pushed"] = '1';}
    elseif($_GET['status']=='1' && $_GET['is_draft']=='0') $order = 'last_edit desc';
    elseif($_GET['status']=='2' && $_GET['is_draft']=='0') $order = 'last_edit desc';
    #
    if($assign_list["news_pushed"]==1) {
        if($_GET['date_from']) $cons .= " and push_date>='".strtotime($_GET['date_from'])."'";
        if($_GET['date_to']) $cons .= " and push_date<='".(strtotime($_GET['date_to'])+24*60*60)."'";
    } else {
        if($_GET['date_from']) $cons .= " and reg_date>='".strtotime($_GET['date_from'])."'";
        if($_GET['date_to']) $cons .= " and reg_date<='".(strtotime($_GET['date_to'])+24*60*60)."'";
    }
    #
    if($_GET['is_picker']==1) $order = 'picker_date desc, news_id desc';
    
    $listItem = $clsClassTable->getListPage($cons." order by ".$order, 50, 'CMS',false);

    $paging = $clsClassTable->getNavPage($cons, 50, 'CMS');
    $assign_list["listItem"] = $listItem;
    $assign_list["paging"] = $paging;
    $assign_list["cursorPage"] = isset($_GET["page"])? $_GET["page"] : 1;
    #
    $listCategory = $clsCategory->getAll("is_trash=0 and parent_id=0 order by order_no");
    $assign_list["listCategory"] = $listCategory;
    
    #
    /*=============Title & Description Page==================*/
    $title_page = "List - ".$classTable; if($_GET['mes']=='updateSuccess') $title_page = "Update Success!";
    $description_page = '';
    $keyword_page = '';
    /*=============Content Page==================*/
}
function default_new(){
    global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg;
    #
    $classTable = ucfirst(strtolower($mod));
    $clsClassTable = new $classTable;
    #
    $clsCategory = new Category(); $assign_list["clsCategory"] = $clsCategory;
    $clsRoyalty = new Royalty(); $assign_list["clsRoyalty"] = $clsRoyalty;
    $clsUser = new User();
    $me = $clsUser->getMe(); $assign_list["me"] = $me; if(!$me) {header('Location: '.SITE_PROTOCOL.SITE_DOMAIN.'/admin.php?mod=user&act=login&u='.rawurlencode($core->getAddress())); die();}
    #
    $tableName = $clsClassTable->tbl;
    $pkeyTable = $clsClassTable->pkey;

    #
    if($_POST) {
        $_POST['last_edit'] = time();
        if($_POST['show_date']) {
            $arr = explode(' ', $_POST['show_date']);
            $date = explode('/', $arr[0]);
            $_POST['show_date'] = $date[2].'-'.$date[1].'-'.$date[0].' '.$arr[1].':00';
            
        } else {
            $_POST['push_date'] = time();
            $_POST['show_date'] = date("Y-m-d H:i:00");
        }
        #
        $_POST['slug'] = $core->toSlug($_POST['title']);
        
        if($_POST['image'] && $_POST['image']!='') {
            $image = str_replace("http://pukokid.clickbuy.shop/","",$_POST['image']);
        } else if($_FILES['image']['name']) {
            $image = $core->ftpUpload('image', 'files', $_POST['slug'].rand(1,9), time(),true);
        } unset($_POST['image']);
        if($image) $_POST['image'] = $image;
        
        if($_POST['image_chuyengia'] && $_POST['image_chuyengia']!='') {
            $image_chuyengia = str_replace("http://pukokid.clickbuy.shop/","",$_POST['image']);
        } else if($_FILES['image_chuyengia']['name']) {
            $image_chuyengia = $core->ftpUpload('image_chuyengia', 'files', $_POST['slug'].rand(1,9), time(),true);
        } unset($_POST['image_chuyengia']);
        if($image_chuyengia) $_POST['image_chuyengia'] = $image_chuyengia;
        
        $_POST['user_id'] = $me['user_id'];
        $_POST['reg_date'] = time();
        
        if($_POST['is_hot']==1) $_POST['hot_date']=time();
        if($_POST['is_pick']==1) $_POST['pick_date']=time();
        if($_POST['is_featured']==1) $_POST['featured_date']=time();
        if($_POST['is_top']==1) $_POST['top_date']=time();
        if($_POST['is_benphai']==1) $_POST['benphai_date']= time();
        if($_POST['is_benphaicm']==1) $_POST['benphaicm_date']= time();
        $_POST['is_unpush'] = 0;
        if($_POST['is_push']==1) {
            $_POST['push_date']=strtotime($_POST['show_date']);
            $_POST['user_push'] = $me['user_id'];
        }  
        #
        if(strtotime($_POST['show_date']) > time()) {
            $_POST['push_date'] = strtotime($_POST['show_date']);
            $_POST['hot_date']=strtotime($_POST['show_date']);
            $_POST['pick_date']=strtotime($_POST['show_date']);
            $_POST['featured_date']=strtotime($_POST['show_date']);
            $_POST['top_date']=strtotime($_POST['show_date']);
            $_POST['benphai_date']=strtotime($_POST['show_date']);
            $_POST['benphaicm_date'] = strtotime($_POST['show_date']);
        } 
        
        
        if($_POST['tags']) {
            $arrTags = explode(",",$_POST['tags']);   
            $clsTags = new Tags;
            foreach($arrTags as $oneTag) {
                $tags_id = $clsTags->getAll('slug = "'.$core->toSlug(trim($oneTag)).'" limit 1');
                if(!$tags_id[0]) {
                    $clsTags->insertOne(array("title"=>trim($oneTag),"slug"=>$core->toSlug(trim($oneTag)),"category_id"=>"|".$_POST['category_id']));
                }
            }
        }
        
        $_POST['content'] = str_replace("<p></p>","",$_POST['content']);
        $_POST['content'] = str_replace("<p>&nbsp;</p>","",$_POST['content']);
        $_POST['content'] = str_replace("<br />","</p><p>",$_POST['content']);
        $_POST['content'] = str_replace('<p style="font-weight: bold;">&nbsp;</p>',"",$_POST['content']);
$_POST['content'] = str_replace('<p style="text-align: center;">&nbsp;</p>',"",$_POST['content']);
            $_POST['seo_note'] = $clsClassTable->checkSeo($_POST);
        
        
        $array['status_royalty'] = $_POST['status_royalty'];
        $array['user_royalty'] = $_POST['user_royalty'];
        unset($_POST['status_royalty']);unset($_POST['user_royalty']);
        
        if($clsClassTable->insertOne($_POST)) {
            if($_POST['is_push'] == 1) {
                
                $arr1 = explode("|",trim($_POST['channel_path'], "|")); if($arr1) {
                    $clsChannel = new Channel;
                    foreach($arr1 as $one) if($one) { 
                        
                        $clsChannel->updateOne($one,array("push_date"=>time()));    
                    }
                    $clsChannel->deleteArrKey();
                }
                $clsClassTable->deleteArrKey('IS_BENPHAI');
                if($_POST['is_top']!='') {
                    $clsClassTable->deleteArrKey('IS_TOP');
                }
                if($_POST['is_hot']!='') {
                    $clsClassTable->deleteArrKey('IS_HOT');
                }
                if($_POST['is_new']!='') {
                    $clsClassTable->deleteArrKey('IS_NEW');
                }
                if($_POST['is_pick']!='') {
                    $clsClassTable->deleteArrKey('IS_PICK');
                }
                if($_POST['is_featured']!='') {
                    $clsClassTable->deleteArrKey('IS_FEATURED');
                }
                
                $clsClassTable->deleteArrKey('KEYARR_VIDEO');
                 
            }
            $clsClassTable->deleteArrKey('KEYARR_'.$_POST['category_id']);
            $clsClassTable->deleteArrKey('KEYARR_'.$clsCategory->getParentID($_POST['category_id']));
            $arr = explode("|",trim($_POST['category_related'], "|")); if($arr) {
                foreach($arr as $one) if($one) { 
                    $clsClassTable->deleteArrKey('KEYARR_'.$one);
                    $clsClassTable->deleteArrKey('KEYARR_'.$clsCategory->getParentID($one));
                }
            }
            

            $clsClassTable->deleteArrKey('CMS');
            $clsClassTable->deleteArrKey();
            $maxId = $clsClassTable->getMaxID();
            
            foreach($array['status_royalty'] as $key=>$status_royalty) {
                $user_royalty = $array['user_royalty'][$key];
    
                $data = $clsRoyalty->getAll("user_id = ".$user_royalty.' and status = '.$status_royalty.' and news_id = '.$maxId.' limit 1',false);
    
                if(!$data[0]) {
                    $clsRoyalty->insertOne(array("user_id"=>$user_royalty,"status"=>$status_royalty,"news_id"=>$maxId,"title"=>$_POST['title'],"push_date"=>$_POST['push_date'],"reg_date"=>time()));
                }
            }
            
            
            $clsClassTable->setShowDate($_POST['show_date'],$maxId);
            $data = $clsClassTable->getOne($maxId);
            $clsHistory = new History();
            //$clsHistory->add($data, "Viết bài mới");            
            header('location: ?mod='.$mod.'&act=edit&id='.$maxId.'&mes=insertSuccess');
        }
        else {
            foreach ($_POST as $key => $val) {
                $assign_list[$key] = $val;
            }
            $msg = "Thêm mới thất bại!";
        }
        unset($_POST);
    }
    #
    /*=============Title & Description Page==================*/
    $title_page = "New - ".$classTable;
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
            $clsRoyalty = new Royalty;$assign_list['clsRoyalty'] = $clsRoyalty;
    $oneItem = $clsClassTable->getOne($_GET['id']);
    $clsUser = new User;$assign_list["clsUser"] = $clsUser;
    $me = $clsUser->getMe(); $assign_list["me"] = $me;
    
    if($oneItem) foreach($oneItem as $key => $val) $assign_list[$key] = $val;
    $tableName = $clsClassTable->tbl;
    $pkeyTable = $clsClassTable->pkey;
    #
    $clsCategory = new Category(); $assign_list["clsCategory"] = $clsCategory;
    #
//    $Cache_edit = new Memcache();
//    $Cache_edit->connect('localhost', 11211);
//    if($Cache_edit->get($_GET['id'])!='' && strtolower($Cache_edit->get($_GET['id']))!=strtolower($me['user_name'])) {
//     echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />Bài viết đang được sửa bởi '.$clsUser->UserNameToFullName($Cache_edit->get($_GET['id']));
//     die();     
//    } 
//    else {
//     $key = $_GET['id'];
//     $val = $_SESSION['USER'];
//     $Cache_edit->set($key, $val, false, 15);
//    }
    #
    if($_POST) {
        
        $_POST['last_edit'] = time();
        if($oneItem['is_push']=='1' && $_POST['is_push']=='0') $_POST['is_unpush'] = '1';
        if($oneItem['is_push']=='0' && $_POST['is_push']=='1') $_POST['is_unpush'] = '0';
        if(!$_POST['slug']) $_POST['slug'] = $core->toSlug($_POST['title']);
        $_POST['channel_path'] = '|'.trim($_POST['channel_path'], '|').'|';
        $_POST['news_related'] = '|'.trim($_POST['news_related'], '|').'|';
        #
        $note = $_POST['note']; unset($_POST['note']);
        #
        if($_POST['show_date']) {
            $arr = explode(' ', $_POST['show_date']);
            $date = explode('/', $arr[0]);
            $_POST['show_date'] = $date[2].'-'.$date[1].'-'.$date[0].' '.$arr[1].':00';
            $clsClassTable->setShowDate($_POST['show_date'],$_GET['id']);
        } else {
            $_POST['push_date'] = time();
            $_POST['show_date'] = date("Y-m-d H:i:00");
        }
        
        if($_POST['pick_date']) {
            $arr = explode(' ', $_POST['pick_date']);
            $date = explode('/', $arr[0]);
            $_POST['pick_date'] = strtotime($date[2].'-'.$date[1].'-'.$date[0].' '.$arr[1].':00');
        }
        if($_POST['push_date']) {
            $arr = explode(' ', $_POST['push_date']);
            $date = explode('/', $arr[0]);
            $_POST['push_date'] = strtotime($date[2].'-'.$date[1].'-'.$date[0].' '.$arr[1].':00');
        }
        #        
        
        if($_POST['is_hot']==1 && !$oneItem['is_hot']) {
            $_POST['hot_date']=time();
            
            
        }
        if($_POST['is_pick']==1 && !$oneItem['is_pick']) $_POST['pick_date']=time();
        if($_POST['is_featured']==1 && !$oneItem['is_featured']) $_POST['featured_date']=time();
        if($_POST['is_top']==1 && !$oneItem['is_top']) $_POST['top_date']=time();
        if($_POST['is_benphai']==1 && !$oneItem['is_benphai']) $_POST['benphai_date']= time();
        if($_POST['is_benphaicm']==1 && !$oneItem['is_benphaicm']) $_POST['benphaicm_date']= time();
        if($_POST['is_push']==1 && !$oneItem['is_push']) {
            $_POST['push_date'] = time();
            $_POST['user_push'] = $me['user_id'];
            $_POST['status'] = 3;
        } 
        
        if(strtotime($_POST['show_date']) > time()) {
            $_POST['push_date'] = strtotime($_POST['show_date']);
            $_POST['hot_date'] = strtotime($_POST['show_date']);
            $_POST['pick_date'] = strtotime($_POST['show_date']);
            $_POST['featured_date'] = strtotime($_POST['show_date']);
            $_POST['top_date'] = strtotime($_POST['show_date']);
            $_POST['benphai_date'] = strtotime($_POST['show_date']);
            $_POST['benphaicm_date'] = strtotime($_POST['show_date']);
            //echo date("H:i:s d/m/Y",$_POST['hot_date']);
//            echo strtotime($_POST['show_date']);
//            echo $_POST['hot_date'];
//            die();
        } 
        
        
        if($_POST['show_date']!= $oneItem['show_date']) {
            $_POST['push_date'] = strtotime($_POST['show_date']);
            $_POST['hot_date']=strtotime($_POST['show_date']);
            $_POST['pick_date']=strtotime($_POST['show_date']);
            $_POST['featured_date']=strtotime($_POST['show_date']);
            $_POST['top_date']=strtotime($_POST['show_date']);
            $_POST['benphai_date'] = strtotime($_POST['show_date']);
            $_POST['benphaicm_date'] = strtotime($_POST['show_date']);
        }
                        
        if($_POST['image'] && $_POST['image']!='') {
            $image = str_replace("http://pukokid.clickbuy.shop/","",$_POST['image']);
        } else if($_FILES['image']['name']) {
            $image = $core->ftpUpload('image', 'files', $oneItem['slug'].rand(1,9), $oneItem['reg_date'],true);
        } unset($_POST['image']);
        if($image) $_POST['image'] = $image;
        #
        if($_POST['image_chuyengia'] && $_POST['image_chuyengia']!='') {
            $image_chuyengia = str_replace("http://pukokid.clickbuy.shop/","",$_POST['image']);
        } else if($_FILES['image_chuyengia']['name']) {
            $image_chuyengia = $core->ftpUpload('image_chuyengia', 'files', $_POST['slug'].rand(1,9), time(),true);
        } unset($_POST['image_chuyengia']);
        if($image_chuyengia) $_POST['image_chuyengia'] = $image_chuyengia;
        if($_POST['tags']) {
            $arrTags = explode(",",$_POST['tags']);   
            $clsTags = new Tags;
            foreach($arrTags as $oneTag) {
                $tags_id = $clsTags->getAll('slug = "'.$core->toSlug(trim($oneTag)).'" limit 1');
                if(!$tags_id[0]) { 
                    $clsTags->insertOne(array("title"=>trim($oneTag),"slug"=>$core->toSlug(trim($oneTag)),"category_id"=>"|".$_POST['category_id']."|"));
                }
            }
        }
        #
        $_POST['content'] = str_replace("<p>&nbsp;</p>","",$_POST['content']);
        $_POST['content'] = str_replace("<br />","</p><p>",$_POST['content']);
        $_POST['content'] = str_replace('<p style="font-weight: bold;">&nbsp;</p>',"",$_POST['content']);
        $_POST['content'] = str_replace('<p style="text-align: center;">&nbsp;</p>',"",$_POST['content']);
        
        $_POST['seo_note'] = $clsClassTable->checkSeo($_POST);
        

        foreach($_POST['status_royalty'] as $key=>$status_royalty) {
            $user_royalty = $_POST['user_royalty'][$key];

            $data = $clsRoyalty->getAll("user_id = ".$user_royalty.' and status = '.$status_royalty.' and news_id = '.$_GET['id'].' limit 1',false);

            if(!$data[0]) {
                if($user_royalty) $clsRoyalty->insertOne(array("user_id"=>$user_royalty,"status"=>$status_royalty,"news_id"=>$_GET['id'],"title"=>$_POST['title'],"push_date"=>$_POST['push_date'],"reg_date"=>time()));
            }
        }
        unset($_POST['status_royalty']);unset($_POST['user_royalty']);
        if($clsClassTable->updateOne($_GET['id'],$_POST)) {
            if($oneItem['is_push'] != $_POST['is_push']) {
                if($_POST['is_push'] == 1) {
                    $arr1 = explode("|",trim($_POST['channel_path'], "|")); if($arr1) {
                        $clsChannel = new Channel;
                        foreach($arr1 as $one) if($one) { 
                            
                            $clsChannel->updateOne($one,array("push_date"=>time()));    
                        }
                        $clsChannel->deleteArrKey();
                    }
                }
            }
            $clsClassTable->deleteArrKey('IS_TOP');
            $clsClassTable->deleteArrKey('IS_HOT');
            $clsClassTable->deleteArrKey('IS_NEW');
            $clsClassTable->deleteArrKey('IS_PICK');
            $clsClassTable->deleteArrKey('IS_FEATURED');
            $clsClassTable->deleteArrKey('IS_BENPHAI');
            
            $clsClassTable->deleteArrKey('KEYARR_'.$oneItem['category_id']);
            $clsClassTable->deleteArrKey('KEYARR_'.$clsCategory->getParentID($oneItem['category_id']));

            $clsClassTable->deleteArrKey('KEYARR_'.$_POST['category_id']);
            $clsClassTable->deleteArrKey('KEYARR_'.$clsCategory->getParentID($_POST['category_id']));
            $arr = explode("|",trim($_POST['category_related'], "|")); if($arr) {
                foreach($arr as $one) if($one) { 
                    $clsClassTable->deleteArrKey('KEYARR_'.$one);
                    $clsClassTable->deleteArrKey('KEYARR_'.$clsCategory->getParentID($one));
                }
            }
            $arr = explode("|",trim($oneItem['category_related'], "|")); if($arr) {
                foreach($arr as $one) if($one) { 
                    $clsClassTable->deleteArrKey('KEYARR_'.$one);
                    $clsClassTable->deleteArrKey('KEYARR_'.$clsCategory->getParentID($one));
                }
            }
            $clsClassTable->deleteArrKey('KEYARR_VIDEO');
            $clsClassTable->deleteArrKey();
            $clsClassTable->deleteArrKey('CMS');
            $data = $clsClassTable->getOne(intval($_GET['id']));
            $clsHistory = new History();
            $note = 'Sửa bài';
            //$clsHistory->add($data, $note);
            header('location: ?mod='.$mod.'&act='.$act.'&id='.$_GET['id'].'&mes=updateSuccess');
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

function default_ajax(){
    global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg;
    #
    $id = $_GET['id'];
    $field = $_GET['field'];
    $value = $_GET['value'];
    if(!$id) die('0');
    $classTable = ucfirst(strtolower($mod));
    $clsClassTable = new $classTable;
    
    $data_update = array($field=>$value); $data_update['last_edit'] = time();
    if($field=='is_push' && $value=='0') $data_update['is_unpush'] = 1;
    if($field=='is_push' && $value=='1') $data_update['is_unpush'] = '0';
    
    if($field=='is_hot' && $value==1) $data_update['hot_date']=time();
    if($field=='is_pick' && $value==1) $data_update['pick_date']=time();
    if($field=='is_featured' && $value==1) $data_update['featured_date']=time();
    if($field=='is_top' && $value==1) $data_update['top_date']=time();
    if($field=='is_push' && $value==1) $data_update['push_date'] = time();
    
    if($clsClassTable->updateOne($id, $data_update)) {
        sleep(2);
        $clsClassTable->deleteArrKey();
        $clsClassTable->deleteArrKey('CMS');
        $data = $clsClassTable->getOne($id);
        $clsHistory = new History();
        if($field=='is_hot') $note = 'Tin nóng';
        elseif($field=='is_pick') $note = 'NB Home';
        elseif($field=='is_featured') $note = 'NB Cat';
        elseif($field=='is_push') $note = 'Xuất bản';
        elseif($field=='is_top') $note = 'Headline';
        if($value=='1') $note = 'Chọn '.$note;
        else $note = 'Bỏ chọn '.$note;
        $clsHistory->add($data, $note);
        die('1');
    }
    else die('0');
}
function default_in_trash(){
	global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg;
    #
    $clsCategory = new Category(); $assign_list['clsCategory'] = $clsCategory;
    $classTable = ucfirst(strtolower($mod));
    $clsClassTable = new $classTable; $assign_list["clsClassTable"] = $clsClassTable;
    $pkeyTable = $clsClassTable->pkey; $assign_list["pkeyTable"] = $pkeyTable;
    #
    $listItem = $clsClassTable->getListPage("is_trash=1 order by ".$pkeyTable." desc");
    $paging = $clsClassTable->getNavPageAdmin("is_trash=1");
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
        sleep(2);
        $clsClassTable->delAllCache($id); 
        $clsClassTable->deleteArrKey();
        $clsClassTable->deleteArrKey('CMS');
        $data = $clsClassTable->getOne($id);
        $clsHistory = new History();
        $clsHistory->add($data, "Xóa tạm");
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
    $clsClassTable->deleteArrKey();
    if($clsClassTable->updateOne($id,array('is_trash'=>'0'))) {
        $clsClassTable->delAllCache($id); 
        $data = $clsClassTable->getOne($id);
        $clsHistory = new History();
        $clsHistory->add($data, "Phục hồi");
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
        $clsClassTable->delAllCache($id); 
        $data = $clsClassTable->getOne($id);
        $clsHistory = new History();
        $clsHistory->add($data, "Xóa vĩnh viễn");
        die('1');
    }
    else die('0');
}
function default_xac_nhan() {
    $clsNews = new News();
    $id = (int)$_GET['id'];
    $clsNews->updateOne($id, array('status'=>3, 'last_edit'=>time(), 'push_date'=>time(), 'show_date'=>date("Y-m-d H:i:00")));
    $clsNews->deleteArrKey();
    $clsNews->delAllCache($id);  
    $data = $clsNews->getOne($id);
    $clsHistory = new History();
    $clsHistory->add($data, "Xác nhận");
    header('Location: /admin.php?mod=news&act=edit&id='.$id);
    die();
}
function default_tra_lai() {
    $clsNews = new News();
    $id = (int)$_POST['id'];
    $reason = $_POST['note'];
    $clsNews->updateOne($id, array('status'=>2, 'last_edit'=>time(),'reason'=>$reason));
    $clsNews->deleteArrKey();
    $clsNews->delAllCache($id);  
    $data = $clsNews->getOne($id);
    $clsHistory = new History();
    $clsHistory->add($data, "Trả lại");
    echo 1;
    die();
}
function default_ajax_user_edit(){
     $Cache_edit = new Memcache();
     $Cache_edit->connect('localhost', 11211);
    
     $clsUser = new User();
    
     $news_id = $_GET['id'];
     $user_id = $_SESSION['USER'];
     $key = $news_id;
     $val = $user_id;
     $Cache_edit->delete($key, 0);
     $Cache_edit->set($key, $val, false, 15);
     die();
}
function default_updatedacheck(){
    $clsNews = new News();
    $id = (int)$_POST['news_id'];

    $clsNews->updateOne($id, array('is_seo_note_check'=>1,"seo_note"=>""));
    
    die();
}
function default_xoa_royalty(){
    $clsRoyalty = new Royalty();
    $news_id = (int)$_POST['news_id'];
    $user_id = (int)$_POST['user_id'];
    $status = (int)$_POST['status'];
    
    $clsRoyalty->deleteAll("user_id = ".$user_id.' and news_id = '.$news_id.' and status = '.$status);
    
    die();
}
?>