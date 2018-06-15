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
    $classTable = 'News';
    $clsClassTable = new $classTable; $assign_list["clsClassTable"] = $clsClassTable;
    $pkeyTable = $clsClassTable->pkey; $assign_list["pkeyTable"] = $pkeyTable;
    #
    if(!$_GET['is_hot'] && !$_GET['is_pick'] && !$_GET['is_featured'] && !$_GET['is_top'] && !$_GET['is_new']) header('Location: /admin.php?mod=position&is_hot=1');
    #
    $cons = "is_trash=0 and is_draft=0 and is_push=1";
    if($_GET['is_hot']=='1') $cons .= " and is_hot=1";
    elseif($_GET['is_pick']=='1') $cons .= " and is_pick=1";
    elseif($_GET['is_featured']=='1') $cons .= " and is_featured=1";
    elseif($_GET['is_top']=='1') $cons .= " and is_top=1";
    elseif($_GET['is_new']=='1') $cons .= " and is_new=1";
    #
    if($_GET['category_id']) $cons.=" and (category_id=".$_GET['category_id']." or category_id in(SELECT category_id FROM default_category WHERE parent_id=".$_GET['category_id']."))";
    if($_GET['keyword']) {
        $slug = $core->toSlug($_GET['keyword']);
        $cons .= " and slug like '%".$slug."%'";
        $assign_list["keyword"] = $_GET['keyword'];
    }
    #
    $order = 'order_hot';
    if($_GET['is_pick']==1) $order = 'order_pick';
    elseif($_GET['is_featured']==1) $order = 'order_feat';
    elseif($_GET['is_top']==1) $order = 'order_top';
    elseif($_GET['is_new']==1) $order = 'order_new';
    $assign_list["order"] = $order;
    if($_COOKIE['key']=='admin') {
        //print_r($cons); //is_trash=0 and is_draft=0 and is_push=1 and is_new=1
        //die();
    }
    #
    if(isset($_GET['reset_all']) && $_GET['reset_all']==1) {
        $all = $clsClassTable->getAll($cons, true, 'CMS');
        if($all) foreach($all as $news_id) $clsClassTable->updateOne($news_id, array($order=>'9'));
        $clsClassTable->deleteArrKey('');
        $clsClassTable->deleteArrKey('CMS');
        $arr = array('reset_all'=>2); $res = $_GET; $str = '?';$i=0;if($arr) foreach($arr as $key => $val) {$res[$key] = $val;if($val=='') unset($res[$key]);}if($res) foreach($res as $key => $val) {if($i==0) $i=1;else $str .= '&';$str .= $key.'='.$val;}
        header('Location: '.$str);
        die();
    }
    #
    $listItem = $clsClassTable->getListPage($cons." order by ".$order.", push_date desc", RECORD_PER_PAGE, 'CMS');
    $paging = $clsClassTable->getNavPage($cons, RECORD_PER_PAGE, 'CMS');
    $assign_list["listItem"] = $listItem;
    $assign_list["paging"] = $paging;
    $assign_list["cursorPage"] = isset($_GET["page"])? $_GET["page"] : 1;
    #
    $listCategory = $clsCategory->getAll("is_trash=0 and parent_id=0 order by menu_display");
    $assign_list["listCategory"] = $listCategory;
    #
    /*=============Title & Description Page==================*/
    $title_page = "List - ".$classTable; if($_GET['mes']=='updateSuccess') $title_page = "Update Success!";
    $description_page = '';
    $keyword_page = '';
    /*=============Content Page==================*/
}
function default_ajax(){
    global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg;
    #
    $id = $_GET['id'];
    $field_name = $_GET['name'];
    $field_value = intval($_GET['value']); if(!$field_value || $field_value==0) $field_value=9;
    if(!$id) die('0');
    $clsNews= new News();
    $clsNews->deleteArrKey('CMS');
    $clsNews->deleteArrKey();
    if($clsNews->updateOne($id, array($field_name=>$field_value))) die('1');
    else die('0');
}
?>