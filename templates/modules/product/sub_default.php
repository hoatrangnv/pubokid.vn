<?php
/**
*  Defautl action
*  @author		: Ong Thế Thành	
*  @date		: 2012/01/23	
*  @version		: 0.0.1
*/
function default_detail(){
    /*=============Title & Description Page==================*/
    $title_page = str_replace("\"", "", $oneProduct['meta_title'])? str_replace("\"", "", $oneProduct['meta_title']) : str_replace("\"", "", $oneProduct['title']);
    $description_page = str_replace("\"", "", $oneProduct['meta_description'])? str_replace("\"", "", $oneProduct['meta_description']) :str_replace("\"", "", $oneProduct['intro']);
    $keyword_page = $oneProduct['meta_keyword']? $oneProduct['meta_keyword'] : $oneProduct['tags'];
    $image = PCMS_URL.'/'.$oneProduct['image'];

}
function default_dangkytuvan(){
    /*=============Title & Description Page==================*/
    $title_page = str_replace("\"", "", $oneProduct['meta_title'])? str_replace("\"", "", $oneProduct['meta_title']) : str_replace("\"", "", $oneProduct['title']);
    $description_page = str_replace("\"", "", $oneProduct['meta_description'])? str_replace("\"", "", $oneProduct['meta_description']) :str_replace("\"", "", $oneProduct['intro']);
    $keyword_page = $oneProduct['meta_keyword']? $oneProduct['meta_keyword'] : $oneProduct['tags'];
    $image = PCMS_URL.'/'.$oneProduct['image'];

}
function default_addProduct() {
    global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg, $ads, $canonical, $google_track;
    #
    $product_id = $_POST['product_id'];
    if(!isset($_SESSION['listProduct'])) $arr = array();
    else $arr = $_SESSION['listProduct'];
    if(!array_key_exists($product_id,$arr)) {
        $arr[$product_id] = 1;
    } else {
        $arr[$product_id]++;
    }
    $_SESSION['listProduct'] = $arr;
    die('');
}


function default_view_more_product() {
    global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg, $ads, $canonical, $google_track;
    $limit = $_POST['limit'];

    $clsProduct = new Product(); $assign_list['clsProduct'] = $clsProduct;
    $cons = $clsProduct->getCons();

    $listNew = $clsProduct->getAll($cons.' and is_new = 1 order by new_date desc limit ' . $limit); $assign_list['listNew'] = $listNew; $assign_list['limit_listNew'] = $limit;

    foreach($listNew as $oneNew) {
        $one = $clsProduct->getOne($oneNew); ?>
        <li>
            <a title="<?php echo $one['title'] ?>" href="<?php echo $clsProduct->getLink($one['product_id']) ?>"
               class="thumb250x250 thumb_over shadow_hover">
                <img title="<?php echo $one['title'] ?>" alt="<?php echo $one['title'] ?>"
                     src="<?php echo $clsProduct->getImage($one['product_id'], 250, 250) ?>"/>
                <span class="icon_news">New&nbsp;</span>
            </a>

            <h3><a class="title_list_products"><?php echo $one['title'] ?></a></h3>

            <div class="price_list_products">Giá: <?php echo $clsProduct->getPriceOld1($one['product_id']) ?> vnđ
                - <strong> <?php echo $clsProduct->getDiscount($one['product_id']) ?>%</strong></div>
        </li>
        <?php
    }
    die;
}
function default_adddonhang() {
    global $core;
    $clsDonHang = new DonHang;
    
    if($_POST['full_name']) {
        $_POST['reg_date'] = time();
        $_POST['slug'] = $core->toSlug($_POST['full_name']);
        $clsDonHang->insertOne($_POST);
        session_start();
        $_SESSION['hienthitb'] = 1;
        die('1');
    }else {
        die('0');
    }
}
function default_tb() {
    global $core;
    session_start();
    if(!$_SESSION['hienthitb']) exit();
}
?>
