<?php
/**
*  Defautl action
*  @author        : Ong Thế Thành    
*  @date        : 2012/01/23    
*  @version        : 0.0.1
*/
function default_default(){
    global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg, $ads, $canonical;
    #
    /*=============Content Page==================*/
    #
    $clsAds = new Ads(); $ads = $clsAds->getAllContent();
    #
    $clsCategory = new Category(); $assign_list['clsCategory'] = $clsCategory;
    if($_GET['id']){$page_id = $_GET['id'];}else{header('Location: '.PCMS_URL.'/404.html');}
    $page = $clsCategory->getOne($page_id);$assign_list['page'] = $page;
    
    #
    /*=============Title & Description Page==================*/
    $title_page = $page['title_seo'];
    $description_page = $page['description'];
    $keyword_page = $page['keywords'];    
    $canonical = PCMS_URL;
}

?>