<?php
function default_detail() {
    global $assign_list, $mod, $act, $head_page, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg, $cat_name;
	#
    /*=============Content Page==================*/

    $clsCategory = new Category(); $assign_list['clsCategory'] = $clsCategory;
    $clsNews = new News(); $assign_list['clsNews'] = $clsNews;
    $clsChannel = new Channel(); $assign_list['clsChannel'] = $clsChannel;
    $clsUser = new User(); $assign_list['clsUser'] = $clsUser;
    #
    if($_GET['id']) $news_id = $_GET['id'];
    else if($_GET['id']==0) {
        $news_id = $clsNews->slugToIDDetail($_GET['slug']);
    }
    else header('location: '.PCMS_URL.'/404.html');
    #
    $oneNews = $clsNews->getOne($news_id);$assign_list['oneNews'] = $oneNews;
    if($_GET['slug'] != $oneNews['slug']) {
        header("HTTP/1.1 301 Moved Permanently");
        header('location: '.$clsNews->getLink($news_id));
        exit();
    }
    #
    $oneNews = $clsNews->getOne($news_id);$assign_list['oneNews'] = $oneNews;
    $clsNews->plusView($news_id);
    $parentID = $clsCategory->getParentID($oneNews['category_id']);
    if($parentID) $assign_list['parentID'] = $parentID;
    
    if($oneNews['is_push'] != 1 || $oneNews['is_trash'] == 1 || $oneNews['is_draft'] == 1) {
        header('location: '.PCMS_URL.'/404.html');
        exit();
    } 
    #
    $cons = $clsNews->getCons($oneNews['category_id']);
    $list_other = $clsNews->getAll($cons.' and news_id <> '.$news_id.' order by push_date desc limit 12');
    $assign_list['list_other'] = $list_other;
    
    $listVideo = $clsNews->getAll($clsNews->getCons(22).' and news_id not in ('.$news_id.') order by news_id desc limit 6');
    $assign_list['listVideo'] = $listVideo;
    
	/*=============Title & Description Page==================*/
	$title_page = str_replace("\"", "", $oneNews['meta_title'])? str_replace("\"", "", $oneNews['meta_title']) : str_replace("\"", "", $oneNews['title']);
    $description_page = str_replace("\"", "", $oneNews['meta_description'])? str_replace("\"", "", $oneNews['meta_description']) :str_replace("\"", "", $oneNews['intro']);
	$keyword_page = $oneNews['meta_keyword']? $oneNews['meta_keyword'] : $oneNews['tags'];
    $link_post = $clsNews->getLink($oneNews['news_id']);
    $head_page = '<meta property="og:type" content="article" />
    <meta property="og:title" content="'.$title_page.'" />
    <meta property="og:description" content="'.$description_page.'" />
    <meta property="og:image" content="'.$clsNews->getImage($oneNews['news_id'],500,263).'"/>
    <meta property="og:type" content="article" />
    <meta property="article:author" content="https://www.facebook.com/thethaoso360/" />
    <meta property="article:section" content="News" />
    <meta property="article:tag" content="'.$keyword_page.'" />
    <meta property="og:url" content="'.$clsNews->getLink($oneNews['news_id']).'" />';
    $head_page .= '<link rel="alternate" href="'.$clsNews->getLink($oneNews['news_id']).'" hreflang="vi-vn" /><link rel="canonical" href="'.$clsNews->getLink($oneNews['news_id']).'"/>';
}
?>