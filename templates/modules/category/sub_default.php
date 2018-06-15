<?php
function default_default(){
	global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg, $ads, $canonical;
	#
    /*=============Content Page==================*/
    #
    $clsCategory = new Category(); $assign_list['clsCategory'] = $clsCategory;
    $clsProduct = new Product(); $assign_list['clsProduct'] = $clsProduct;
    #
 
    if($_GET['id']) $category_id = $_GET['id'];    
    $assign_list['category_id'] = $category_id;
    $oneCat = $clsCategory->getOne($category_id); $assign_list['oneCat'] = $oneCat;
    $listCatChild = $clsCategory->getChild($category_id); $assign_list['listCatChild'] = $listCatChild;
    if($_GET["page"] == 1) header('Location: '.$clsCategory->getLink($category_id));
    $page = isset($_GET["page"])? $_GET["page"] : 1; $assign_list['page'] = $page;  
    $slug =$_GET['slug'];
    $slug_2 = $oneCat['slug'];
    $clsAds = new Ads(); $ads = $clsAds->getAllContent($category_id);
    $assign_list['ads'] = $ads;  
    #
    $cons = $clsProduct->getCons($category_id);
    #

    
    $listFeatured = $clsProduct->getAll($cons.' and is_featured=1 and push_date<'.time().' order by featured_date desc limit 3');
    $assign_list['listFeatured'] = $listFeatured;
    #  
    if($_POST) {
        if($_POST['order']) {$order = ' price_new '.$_POST['order'];$assign_list['order'] = $_POST['order']; } 
        if($_POST['min']) {$cons .= ' and price_new >= '.$_POST['min'];$assign_list['min'] = $_POST['min']; }   
        if($_POST['max']) {$cons .= ' and price_new <= '.$_POST['max'];$assign_list['max'] = $_POST['max'];  }  
    } else {
        $order = 'push_date desc';
    }
    
    $listNew = $clsProduct->getListPage($cons." and push_date<".time()." order by ".$order, 9); $assign_list['listNew'] = $listNew;
    $paging = $clsProduct->getNavPage($cons." and push_date<".time(), 9); $assign_list['paging'] = $paging;
    

	/*=============Title & Description Page==================*/
    $title_page = $oneCat['title_seo']?$oneCat['title_seo']:$oneCat['title'];
	$description_page = $oneCat['description'];
	$keyword_page = $oneCat['keywords'];
}
function default_detail(){
	global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg, $ads, $canonical;
	#
    /*=============Content Page==================*/
    #
    $clsCategory = new Category(); $assign_list['clsCategory'] = $clsCategory;
    $clsProduct = new Product(); $assign_list['clsProduct'] = $clsProduct;
    #

    if($_GET['id']) $category_id = $_GET['id'];    
    $assign_list['category_id'] = $category_id;
    $oneCat = $clsCategory->getOne($category_id); $assign_list['oneCat'] = $oneCat;
    $parent_id = $clsCategory->getParentID($category_id); $assign_list['parent_id'] = $parent_id;
    $listCatChild = $clsCategory->getChild($parent_id); $assign_list['listCatChild'] = $listCatChild;
    if($_GET["page"] == 1) header('Location: '.$clsCategory->getLink($category_id));
    $page = isset($_GET["page"])? $_GET["page"] : 1; $assign_list['page'] = $page;  
    $slug =$_GET['slug'];
    $slug_2 = $oneCat['slug'];
    if($slug!=$slug_2){
        if($page > 1){
            header('location: '.$clsCategory->getLink($category_id).'p'.$page);
        }else{
            header('location: '.$clsCategory->getLink($category_id));
        }
    }
    $clsAds = new Ads(); $ads = $clsAds->getAllContent($category_id);
    $assign_list['ads'] = $ads;  
    #
    $cons = $clsProduct->getCons($category_id);
    #
    $listFeatured = $clsProduct->getAll($cons.' and is_featured=1 and push_date<'.time().' order by featured_date desc limit 3');
    $assign_list['listFeatured'] = $listFeatured;
    #  
    $listNew = $clsProduct->getListPage($cons." and push_date<".time()." order by push_date desc ", 9); $assign_list['listNew'] = $listNew;
    $paging = $clsProduct->getNavPage($cons." and push_date<".time(), 9); $assign_list['paging'] = $paging;
	/*=============Title & Description Page==================*/
    $title_page = $oneCat['title_seo']?$oneCat['title_seo']:$oneCat['title'];
	$description_page = $oneCat['description'];
	$keyword_page = $oneCat['keywords']; 
}

?>