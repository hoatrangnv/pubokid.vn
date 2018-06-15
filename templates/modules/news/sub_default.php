<?php
function default_default() {
    global $assign_list, $mod, $act, $_LANG_ID, $head_page, $title_page, $description_page, $keyword_page, $core, $msg, $ads, $cat_name,$ads;
	#
    /*=============Content Page==================*/
    #
    $clsCategory = new Category(); $assign_list['clsCategory'] = $clsCategory;
    $clsNews = new News(); $assign_list['clsNews'] = $clsNews;
   
    if(isset($_GET['slug'])) {
        $category_id = $clsCategory->slugToID(trim($_GET['slug'],'/'));

    } else header('Location: '.PCMS_URL.'/404.html');
        
    //$clsAds = new Ads(); $ads = $clsAds->getAllContent($category_id);
    //print_r($ads);
    
    
    $assign_list['category_id'] = $category_id;
    $oneCat = $clsCategory->getOne($category_id); $assign_list['oneCat'] = $oneCat;

    $page = isset($_GET["page"])? $_GET["page"] : 1; $assign_list['page'] = $page;
    if(isset($_GET["page"]) && $_GET["page"]==1) header('Location: '.$clsCategory->getLink($category_id));
        $cons = $clsNews->getCons($oneCat['category_id']);
        $list_featured = $clsNews->getAll($cons.' and is_featured=1 order by featured_date desc limit 1',true,'IS_FEATURED');
        $assign_list['list_featured'] = $list_featured;
        if($list_featured[0]) $cons1 = $cons." and news_id != ".$list_featured[0]; else $cons1 = $cons;
        $listNews = $clsNews->getListPage($cons1." order by push_date desc, news_id desc ", 15,"KEYARR_".$category_id); $assign_list['listNews'] = $listNews;
        $paging = $clsNews->getNavPage($cons1." order by push_date desc, news_id desc ", 15,"KEYARR_".$category_id); $assign_list['paging'] = $paging;
    #
    
	/*=============Title & Description Page==================*/
	$title_page = $oneCat['title_seo']?$oneCat['title_seo']:$oneCat['title'];
    if($page>1) $title_page.=' - Trang '.$page;
	$description_page = $oneCat['description'];
	$keyword_page = $oneCat['keywords'];
    $page_link = $clsCategory->getLink($category_id);
    if($page>1) $page_link.='p'.$page;
    $head_page = '
    <meta property="og:site_name" content="'.$title_page.'" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content= "'.$page_link.'" />
    <meta property="og:title" content="'.$title_page.'" itemprop="name"/>
    <meta property="og:description" content="'.$description_page.'" itemprop="description"/>
    <link rel="canonical" href="'.$page_link.'"/>';

}
function default_diemban() {
    global $assign_list, $mod, $act, $_LANG_ID, $head_page, $title_page, $description_page, $keyword_page, $core, $msg, $ads, $cat_name,$ads;
    #
    /*=============Content Page==================*/
    #
    $clsCategory = new Category(); $assign_list['clsCategory'] = $clsCategory;
    $clsNews = new News(); $assign_list['clsNews'] = $clsNews;
   
    if(isset($_GET['slug'])) {
        $category_id = $clsCategory->slugToID(trim($_GET['slug'],'/'));

    } else header('Location: '.PCMS_URL.'/404.html');
        
    //$clsAds = new Ads(); $ads = $clsAds->getAllContent($category_id);
    //print_r($ads);
    
    
    $assign_list['category_id'] = $category_id;
    $oneCat = $clsCategory->getOne($category_id); $assign_list['oneCat'] = $oneCat;

    $page = isset($_GET["page"])? $_GET["page"] : 1; $assign_list['page'] = $page;
    if(isset($_GET["page"]) && $_GET["page"]==1) header('Location: '.$clsCategory->getLink($category_id));
        $cons = $clsNews->getCons($oneCat['category_id']);
        $list_featured = $clsNews->getAll($cons.' and is_featured=1 order by featured_date desc limit 1',true,'IS_FEATURED');
        $assign_list['list_featured'] = $list_featured;
        if($list_featured[0]) $cons1 = $cons." and news_id != ".$list_featured[0]; else $cons1 = $cons;
        $listNews = $clsNews->getListPage($cons1." order by push_date desc, news_id desc ", 15,"KEYARR_".$category_id); $assign_list['listNews'] = $listNews;
        $paging = $clsNews->getNavPage($cons1." order by push_date desc, news_id desc ", 15,"KEYARR_".$category_id); $assign_list['paging'] = $paging;
    #
    
    /*=============Title & Description Page==================*/
    $title_page = $oneCat['title_seo']?$oneCat['title_seo']:$oneCat['title'];
    if($page>1) $title_page.=' - Trang '.$page;
    $description_page = $oneCat['description'];
    $keyword_page = $oneCat['keywords'];
    $page_link = $clsCategory->getLink($category_id);
    if($page>1) $page_link.='p'.$page;
    $head_page = '
    <meta property="og:site_name" content="'.$title_page.'" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content= "'.$page_link.'" />
    <meta property="og:title" content="'.$title_page.'" itemprop="name"/>
    <meta property="og:description" content="'.$description_page.'" itemprop="description"/>
    <link rel="canonical" href="'.$page_link.'"/>';

}
function default_subcat() {
    global $assign_list, $mod, $act, $_LANG_ID, $head_page, $title_page, $description_page, $keyword_page, $core, $msg, $ads, $cat_name;
	#
    /*=============Content Page==================*/
    #
    $clsCategory = new Category(); $assign_list['clsCategory'] = $clsCategory;
    $clsNews = new News(); $assign_list['clsNews'] = $clsNews;
    #
    if(isset($_GET['slug'])) {
        $category_id = $clsCategory->slugToID(trim($_GET['slug'],'/'));
    }
    else header('Location: '.PCMS_URL.'/404.html');
    $clsAds = new Ads(); $ads = $clsAds->getAllContent($category_id);


    $address = $core->getAddress();
    
    $assign_list['category_id'] = $category_id;
    $oneCat = $clsCategory->getOne($category_id); $assign_list['oneCat'] = $oneCat;
    $parentID = $clsCategory->getParentID($category_id);
    $assign_list['parentID'] = $parentID;
    
    $page = isset($_GET["page"])? $_GET["page"] : 1; $assign_list['page'] = $page;
    if(isset($_GET["page"]) && $_GET["page"]==1) header('Location: '.$clsCategory->getLink($category_id));
    #
        $cons = $clsNews->getCons($category_id);
        $list_featured = $clsNews->getAll($cons.' and is_featured=1 order by featured_date desc limit 4',true,'IS_FEATURED');
        $assign_list['list_featured'] = $list_featured;
        if($list_featured) $cons1 = $cons." and news_id not in(".implode(",",$list_featured).")"; else $cons1 = $cons;
        $listNews = $clsNews->getListPage($cons1." order by news_id desc ", 15,"KEYARR_".$category_id); $assign_list['listNews'] = $listNews;
        $paging = $clsNews->getNavPage($cons1." order by news_id desc ", 15,"KEYARR_".$category_id); $assign_list['paging'] = $paging;
    
    #
    
	/*=============Title & Description Page==================*/
	$title_page = $oneCat['title_seo']?$oneCat['title_seo']:$oneCat['title'];
    if($page>1) $title_page.=' - Trang '.$page;
	$description_page = $oneCat['description'].' '.$title_page;
	$keyword_page = $oneCat['keywords'];
    $page_link = $clsCategory->getLink($category_id);
    if($page>1) $page_link.='p'.$page;
    $head_page = '
    <meta property="og:site_name" content="'.$title_page.'" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content= "'.$page_link.'" />
    <meta property="og:title" content="'.$title_page.'" itemprop="name"/>
    <meta property="og:description" content="'.$description_page.'" itemprop="description"/>
    <link rel="canonical" href="'.$page_link.'"/>';
}
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

//    if($_GET['slug'] != $oneNews['slug']) {
//        header("HTTP/1.1 301 Moved Permanently");
//        header('location: '.$clsNews->getLink($news_id));
//        exit();
//    }
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
    $_news_related = explode("|", $oneNews['news_related']);
    $news_related = array_filter($_news_related);
    $news_related_str = '1=1';
    if(!empty($news_related)){
        $news_related_str = 'news_id IN (' . implode(",", $news_related) . ')';
    }
//    $cons = $clsNews->getCons($oneNews['category_id']);
//    $list_other = $clsNews->getAll($clsNews->getCons(7).' and news_id <> '.$news_id.' order by push_date desc limit 3');
    $list_other = $clsNews->getAll($news_related_str.' and news_id <> '.$news_id.' order by push_date desc limit 3');
    $assign_list['list_other'] = $list_other;
    #
    $_category_related = explode("|", $oneNews['category_related']);
    $category_related = array_filter($_category_related);
    $assign_list['menu'] = $category_related;
         
    
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
    <meta property="article:section" content="News" />
    <meta property="article:tag" content="'.$keyword_page.'" />
    <meta property="og:url" content="'.$clsNews->getLink($oneNews['news_id']).'" />';
    $head_page .= '<link rel="canonical" href="'.$clsNews->getLink($oneNews['news_id']).'"/>';
}
function default_tag(){
	global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg, $ads, $head_page;
	#
    /*=============Content Page==================*/
    #
    $clsCategory = new Category(); $assign_list['clsCategory'] = $clsCategory;
    $clsNews = new News(); $assign_list['clsNews'] = $clsNews;
    
    #
    $slug = $_GET['slug'];
    $slug = str_replace('-', ' ', $slug);
    $assign_list['slug'] = $slug;
    #
    $cons = $clsNews->getCons();
    $listNews = $clsNews->getListPage($cons." and (tags like '%".$slug."%' or slug like '%".$_GET['slug']."%') order by push_date desc", 10,"KEYARR_TAGS"); $assign_list['listNews'] = $listNews;
    $paging = $clsNews->getNavPage($cons." and (tags like '%".$slug."%' or slug like '%".$_GET['slug']."%') order by push_date desc", 10,"KEYARR_TAGS"); $assign_list['paging'] = $paging;
    $page = isset($_GET["page"])? $_GET["page"] : 1; $assign_list['page'] = $page;
    if(isset($_GET["page"]) && $_GET["page"]==1) header('Location: '.$clsNews->getLinkTag($slug));
    #
    $one0 = $clsNews->getOne($listNews[0]);$assign_list['one0'] = $one0;

	/*=============Title & Description Page==================*/
	$title_page = $slug." - ".$one0['title'];
    if($page>1) $title_page.=' - Trang '.$page;
    $description_page = "Tin tức ".$slug.", thông tin mới nhất ".$slug.": ".$one0['intro'];
    $keyword_page =  $slug.', '.$slug.', tin tuc '.$slug.', hinh anh '.$slug;
    $assign_list['link_page'] = $clsNews->getLinkTag($slug);
    if(!$page || $page==1) $head_page = '<link rel="canonical" href="'.$clsNews->getLinkTag($slug).'"/>';
    else $head_page = '<link rel="canonical" href="'.$clsNews->getLinkTag($_GET['slug'],$page).'"/>';

}
function default_preview(){
	global $assign_list, $head_page,$mod, $act, $head_page, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg, $ads, $cat_name;
	#
    /*=============Content Page==================*/
    #

    $clsCategory = new Category(); $assign_list['clsCategory'] = $clsCategory;
    $clsNews = new News(); $assign_list['clsNews'] = $clsNews;
    $clsSource = new Source(); $assign_list['clsSource'] = $clsSource;
    $clsChannel = new Channel(); $assign_list['clsChannel'] = $clsChannel;
    $clsHoidap = new Hoidap();$assign_list['clsHoidap'] = $clsHoidap;
    $clsUser = new User(); $assign_list['clsUser'] = $clsUser;
    #
    if($_GET['news_id']) $news_id = $_GET['news_id'];

    $oneNews = $clsNews->getOne($news_id);$assign_list['oneNews'] = $oneNews;
    $parentID = $clsCategory->getParentID($oneNews['category_id']);
    if($parentID) $assign_list['parentID'] = $parentID;

    $list_other = $clsNews->getAll($clsNews->getCons($oneNews['category_id']).' and news_id <> '.$news_id.' order by news_id desc limit 14');
    $assign_list['list_other'] = $list_other;
    
    
        $list_ques_ans = $clsHoidap->getAll('is_trash = 0 and is_show = 1 and gltt_id = '.$oneNews['gltt_id'].' order by show_date desc');
    $assign_list['list_ques_ans'] = $list_ques_ans;
    if($_POST['boxcauhoi']) { 
        unset($_POST['boxcauhoi']);
        $_POST['gltt_id'] = $oneNews['gltt_id'];
        $_POST['is_show'] = 0;
        $clsHoidap->insertOne($_POST);
    }
	/*=============Title & Description Page==================*/
	$title_page = str_replace("\"", "", $oneNews['meta_title'])? str_replace("\"", "", $oneNews['meta_title']) : str_replace("\"", "", $oneNews['title']);
	$description_page = str_replace("\"", "", $oneNews['meta_description'])? str_replace("\"", "", $oneNews['meta_description']) :str_replace("\"", "", $oneNews['intro']);
	$keyword_page = $oneNews['meta_keyword']? $oneNews['meta_keyword'] : $oneNews['tags'];
    $link_post = $clsNews->getLink($oneNews['news_id']);
    $head_page = '<meta name="robots" content="noindex,nofollow" />';
}
function default_channel(){
	global $assign_list, $head_page, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg, $ads, $head_page;
	#
    /*=============Content Page==================*/
    #
    $clsChannel = new Channel(); $assign_list['clsChannel'] = $clsChannel;
    $clsNews = new News(); $assign_list['clsNews'] = $clsNews;
    $clsCategory = new Category; $assign_list['clsCategory'] = $clsCategory;
    #
    if($_GET['id']) $channel_id = $_GET['id'];
    else header('Location: '.PCMS_URL.'/404.html');
    #
    $one_channel = $clsChannel->getOne($channel_id); $assign_list['one_channel'] = $one_channel;
    $cons = $clsNews->getCons();
    $listNews = $clsNews->getListPage($cons." and channel_path like '%|".$channel_id."|%' order by push_date desc, show_date desc", 10,"KEYARR_CHANNEL"); $assign_list['listNews'] = $listNews;
    $paging = $clsNews->getNavPage($cons." and channel_path like '%|".$channel_id."|%' order by push_date desc,show_date desc", 10); $assign_list['paging'] = $paging;
    $page = isset($_GET["page"])? $_GET["page"] : 1; $assign_list['page'] = $page;
    #
	/*=============Title & Description Page==================*/
	$title_page = $one_channel['title'].' - Tin tức hình ảnh video clip '.$one_channel['title'];
    if($page>1) $title_page.=' - Trang '.$page;
    $description_page = $one_channel['description'];
	$keyword_page = $one_channel['keywords'];
    if(!$page || $page==1) $head_page = '<link rel="canonical" href="'.$clsChannel->getLink($slug,false).'"/>';
    else $head_page = '<link rel="canonical" href="'.$clsChannel->getLink($_GET['slug'],false).'"/>';
    
}
function default_search(){
    global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg, $ads, $head_page;
    #
    /*=============Content Page==================*/
    #
    $clsCategory = new Category(); $assign_list['clsCategory'] = $clsCategory;
    $clsNews = new News(); $assign_list['clsNews'] = $clsNews;
    #
    $_GET['slug'] = addslashes($_GET['slug']);

    $slug = $core->toSlug($_GET['slug']);
    $title = str_replace("-", " ", $_GET['slug']);
    $assign_list['title'] = $title;
    #
    $list_sub_cat = $clsCategory->getAll("is_trash=0 and is_tat=0 and parent_id=0 order by menu_display"); 
    $assign_list['list_sub_cat'] = $list_sub_cat;

    #
    $cons = $clsNews->getCons();
    $listNews = $clsNews->getListPage($cons." and (slug like '%".$slug."%') order by push_date desc",14);$assign_list['listNews'] = $listNews;
    $paging = $clsNews->getNavPage($cons." and (slug like '%".$slug."%') order by push_date desc", 14); $assign_list['paging'] = $paging;
    #
    //$clsAds = new Ads(); $ads = $clsAds->getAllContent(0);
    
    /*=============Title & Description Page==================*/
    $title_page = $title.' và các bài viết liên quan';
    if($page>1) $title_page.=" | Trang ".$page;
}
function default_lienhe(){
    global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg, $ads, $head_page;
    #

    /*=============Title & Description Page==================*/
    $title_page = 'Liên hệ';
    $description_page = '';
    $keyword_page = '';
}
function default_gopy(){
    global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg, $ads, $head_page;
    #


    /*=============Title & Description Page==================*/
    $title_page = 'Góp ý';
    $description_page = '';
    $keyword_page = '';
}
function default_tintrongngay() {
    global $assign_list, $mod, $act, $_LANG_ID, $head_page, $title_page, $description_page, $keyword_page, $core, $msg, $ads, $cat_name,$ads;
	#
    /*=============Content Page==================*/
    #
    $clsCategory = new Category(); $assign_list['clsCategory'] = $clsCategory;
    $clsNews = new News(); $assign_list['clsNews'] = $clsNews;
   
    $cons = $clsNews->getCons();
    if(isset($_GET['d'])) {
        if($_GET['d'] < 10) $d = '0'.$_GET['d']; else $d = $_GET['d'];
        if($_GET['m'] < 10) $m = '0'.$_GET['m']; else $m = $_GET['m'];
        $y = $_GET['y']; 
        
        if(($d.'-'.$m.'-'.$y) == date("d-m-Y")) {
            header("HTTP/1.1 301 Moved Permanently");
            header('location: '.PCMS_URL.'/tin-bong-da-hom-nay/');
            
            $date = date("d/m/Y");
            
            
        } else {
            $cons .= ' and push_date >= "'.strtotime(date($y."-".$m."-".$d." 00:00:00")).'" and push_date <= "'.strtotime(date($y."-".$m."-".$d." 23:59:59")).'"';
            $date = $d."/".$m."/".$y;
            $check = 2;
        }
    } else {
        $cons .= ' and push_date >= "'.strtotime(date("Y-m-d 00:00:00")).'" and push_date <= "'.strtotime(date("Y-m-d 23:59:59")).'"';
        $date = date("d/m/Y");
        
        $check = 1;
    }
    $assign_list['date'] = $date;
    
    $page = isset($_GET["page"])? $_GET["page"] : 1; $assign_list['page'] = $page;
        
    $listNews = $clsNews->getListPage($cons." order by push_date desc, news_id desc ", 20,""); $assign_list['listNews'] = $listNews;
    
    $one0 = $clsNews->getOne($listNews[0]);$assign_list['one0'] = $one0;
    
    $paging = $clsNews->getNavPage($cons." order by push_date desc, news_id desc ", 20,""); $assign_list['paging'] = $paging;
    #
    
	/*=============Title & Description Page==================*/
    if($check == 1) {
    	$title_page = 'Tin bóng đá hôm nay '.$date.': '.$one0['title'];
        if($page>1) $title_page.=' - Trang '.$page;
    	$description_page = 'Tin bóng đá hôm nay ngày '.$date.': '.$one0['intro'];
    	$keyword_page = 'tin bóng đá, tin bóng đá hôm nay, tin bóng đá mới nhất, tin bóng đá trong ngày';
    } else {
        $title_page = 'Tin bóng đá ngày '.$date.': '.$one0['title'];
        if($page>1) $title_page.=' - Trang '.$page;
    	$description_page = 'Tin bóng đá ngày '.$date.': '.$one0['intro'];
    	$keyword_page = 'tin bóng đá, tin bóng đá hôm nay, tin bóng đá mới nhất, tin bóng đá trong ngày';
    }
}
function default_24h(){
    global $assign_list, $mod, $act, $_LANG_ID, $head_page, $title_page, $description_page, $keyword_page, $core, $msg, $ads;
    #
    /*=============Content Page==================*/
    #
    $clsCategory = new Category(); $assign_list['clsCategory'] = $clsCategory;
    $clsNews = new News(); $assign_list['clsNews'] = $clsNews;
    #
    $cons = $clsNews->getCons();
    $list_cat = $clsCategory->getAll('is_trash=0 and parent_id=0 and home_display>0 order by home_display'); $assign_list['list_cat'] = $list_cat;
    #
    $clsAds = new Ads(); $ads = $clsAds->getAllContent($category_id);
    
    /*=============Title & Description Page==================*/
    $title_page = "Tin tức 24h - Tin 24h";
    $description_page = "Tin cập nhật trong ngày. Tin hay nhất trong ngày. Tin mới nhất trong ngày";
}
function default_addComment() {
    session_start();
    if (md5($_POST['captcha']) != $_SESSION['randomnr2']) {
        $data['captcha'] = $_SESSION['randomnr2'];
        $data['status'] = 2;
        echo json_encode($data);
        die();
    }
    
    $data1['name'] = $_POST['name'];
    $data1['reg_date'] = date('Y-m-d H:i:s');
    $data1['news_id'] = $_POST['newsId'];
    $data1['content'] = $_POST['content'];
    $data1['email'] = $_POST['email'];
    $data1['parent_id'] = $_GET['parent_id'];
    
    $clsComment = new Comment();
    if(!$clsComment->insertOne($data1)) {
        $data['status'] = 0;
    } else {
        $data['status'] = 1;
    }
    
    echo json_encode($data);
    die();
}
function default_more_comment() {
    global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg, $ads;
	#
    /*=============Content Page==================*/
    #
    $clsComment = new Comment(); $assign_list['clsComment'] = $clsComment;
    $news_id = $_POST['news_id'];
    $cons = 'is_trash=0 and is_push=1 and news_id='.$news_id.' and parent_id=0 order by push_date desc, comment_id desc';
    $list_comment = $clsComment->getListPage($cons, 4);

    $html = '';
    foreach($list_comment as $one) { $one = $clsComment->getOne($one);
        $html .= '<div class="comment-ctn item-comment">
                    <div class="rbox">
                    <div class="comment-box">
        	            <span class="comment-author">'.$one['name'].'</span> - 
                        <span class="comment-date">'.date('H:i | d-m-Y',strtotime($one['reg_date'])).'</span>'.
                        '<div class="comment-body">'.$one['content'].'</div>'.
                        '<div class="commentActions"><a href="javascript:void(0)" style="color: red;" class="reply_comment" data="'.$one['comment_id'].'">Reply</a>
                        <a href="#" class="fb-reply-close" style="display: none;">Đóng</a></div>'.
                        '<div class="comment-box-reply" style="margin-left: 40px;">';
        
        $cons1 = 'is_trash=0 and is_push=1 and parent_id='.$one['comment_id'].' order by push_date desc, comment_id desc';
        $all = $clsComment->getAll($cons1);
        if($all) foreach($all as $one1) { $one1 = $clsComment->getOne($one1);
            $html.= '<div class="reply">
                    <span class="comment-author">'.$one1['name'].'</span> - <span class="comment-date">'.date('H:i | d-m-Y',strtotime($one1['reg_date'])).'</span>
                    <div class="comment-body">'.$one1['content'].'</div>
                    </div>';
        }               
                
         $html .= '</div></div></div></div>';
    }
    echo $html;
    die();
    
}
function default_sort_live() {
    global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg, $ads;
	#
    if($_POST['news_id'] && $_POST['sort']) {
        $news_id = $_POST['news_id'];
        $sort = $_POST['sort'];
        
        $clsNews=new News;$oneNews=$clsNews->getOne($news_id);
        
        $live = json_decode($oneNews['live'],true);
        
        if($sort == 'desc') {
            $arrlive = array_reverse($live);
        } else {
            $arrlive = $live;
        }
        
        foreach($arrlive as $l) {
            echo '<div class="live_ pkg" style="margin-bottom: 5px;"><span style="float:left; padding-top: 3px; padding-bottom: 3px;padding-right: 3px;width: 8%;" class="live_time">'.$l['time'].'\'</span  style="float:left"><span  style="float:left;border-left: 2px solid #BFBDBD;padding-left: 10px;vertical-align: middle;width: 87%;" class="live_content">'.$l['content'].'</span></div>';
        }
        
    }
    die();
    
}
function default_subscribe() {
    global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg, $ads;
	#
    if($_POST) {
        echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
        $clsSubscribe = new Subscribe;
        $arr['name'] = $_POST['txtname'];
        $arr['gender'] = $_POST['txtgender'];
        $arr['email'] = $_POST['txtemail'];
        $arr['reg_date'] = time();
       
        //$checkExist = $clsSubscribe->getAll('is_trash = 0 and is_unsubscribe = 0 and email = "'.$_POST['txtemail'].'" limit 1');
        if(!$checkExist[0]) {
            $clsSubscribe->insertOne($arr);
            
            ob_start();
            include_once('email-templates/confirm_subscribe.html');
            $content = ob_get_contents();ob_end_clean();
            #
            $link='abcxyz';
            $content = str_replace(array("{name}","{link}"),array($arr['name'],$link),$content);
            
            $core->mailtoHtml($arr['email'],'Xác nhận đăng ký bản tin',$content,'Thethaoso360.com <bantin@thethaoso360.com>');
            #
            $mess = 'Cảm ơn bạn đã lựa chọn dịch vụ của chúng tôi. <br> Vui lòng kiểm tra email của bạn và làm theo hướng dẫn để hoàn tất quá trình đăng ký.';
            $assign_list['mess'] = $mess;
        } else {
            $oneSubscribe = $clsSubscribe->getOne($checkExist[0]);
            if($oneSubscribe['is_confirm'] == 0) {
                $mess = 'Email này đã được đăng ký nhận tin từ Thethaoso360 nhưng chưa xác nhận email. <br>Bạn vui lòng kiểm tra lại Email và làm theo hướng dẫn';
            } else {
                $mess = 'Email này đã được đăng ký nhận tin từ Thethaoso360';
            }
            
            $assign_list['mess'] = $mess;
        }
        
    }
}
function default_subscribe_confirm() {
    global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg, $ads;
	#
    if($_GET['token']) {
        $token = $_GET['token'];
        $id = $_GET['id'];
        $subscribe = $clsSubscribe->getOne($id);
        if($token == md5($subscribe['id'].$subscribe['email'])) {
            
        }
    }
    
}
function default_guithongtin() {
    global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg, $ads;
    #

    if($_POST['full_name'] && $_POST['mobile']) {
        $clsDonHang = new DonHang;
        $_POST['reg_date'] = time();
        $_POST['slug'] = $core->toSlug($_POST['full_name']);
        $clsDonHang->insertOne($_POST);
        session_start();
        $_SESSION['hienthitb'] = 1;
        echo '1';
    } else {
        echo '0';
    }
    exit();
    
}
?>