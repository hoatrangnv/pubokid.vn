<?php
/**
*  Defautl action
*  @author		: Ong Th? Thành	
*  @date		: 2012/01/23	
*  @version		: 0.0.1
*/
function default_gio_hang(){
	global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg, $ads, $canonical, $google_track, $image;
	#
    /*=============Content Page==================*/
    #
    $clsCategory = new Category(); $assign_list['clsCategory'] = $clsCategory;
    $clsProduct = new Product(); $assign_list['clsProduct'] = $clsProduct;
    $listProduct = $_SESSION['listProduct']; $assign_list['listProduct'] = $listProduct;
}
function default_dat_hang(){
	global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg, $ads, $canonical, $google_track, $image;
	#
    /*=============Content Page==================*/
    #
    $clsClient = new Client; $assign_list['clsClient'] = $clsClient;
    $clsProduct = new Product(); $assign_list['clsProduct'] = $clsProduct;
    $clsInvoice = new Invoice(); $assign_list['clsInvoice'] = $clsInvoice;
    $clsInvoice_Detail = new Invoice_Detail(); $assign_list['clsInvoice_Detail'] = $clsInvoice_Detail;
    #
    $listProduct = $_SESSION['listProduct']; $assign_list['listProduct'] = $listProduct;
    $oneClient = $clsClient->getMe();
    if($oneClient) {
        $_SESSION['ttdh']['client_name'] = $oneClient['full_name'];
        $_SESSION['ttdh']['client_address'] = $oneClient['address'];
        $_SESSION['ttdh']['client_city'] = $oneClient['city'];
        $_SESSION['ttdh']['client_township'] = $oneClient['township'];
        $_SESSION['ttdh']['client_mobile'] = $oneClient['mobile'];
    }
    if($_POST) {
        #
        $_POST['reg_date'] = time();
        if($oneClient['client_id']) $_POST['client_id'] =  $oneClient['client_id'];
        
        $_SESSION['ttdh'] = $_POST;
        
        if($listProduct) { 
            if(!$clsInvoice->insertOne($_POST)) {
               $assign_list['mess'] = 'Chức năng đang được nâng cấp.';
            } else {
                $invoice_id = mysql_insert_id();
                $detail['invoice_id'] = $invoice_id;
            
                foreach($listProduct as $key=>$val) {
                    $one = $clsProduct->getOne($key);
                    $detail['product_id'] = $key;
                    $detail['amount'] = $val;
                    $detail['price_current_saled'] = $one['price_new'];
                    $clsInvoice_Detail->insertOne($detail);
                } 
                unset($_SESSION['listProduct']);unset($_SESSION['ttdh']);
                header('Location:'.PCMS_URL.'/thanh-cong.html'); 
            }
        } else {
            $assign_list['mess'] = 'Chưa có hàng trong giỏ';
        }
    }
}
function default_success() {
    global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg, $ads, $canonical, $google_track;
    #
    
}
function default_deleteProduct() {
    global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg, $ads, $canonical, $google_track;
    #
    $product_id = $_POST['product_id'];
    $arr = $_SESSION['listProduct'];
    if(array_key_exists($product_id,$arr)) {
        unset($arr[$product_id]);
    } 
    $_SESSION['listProduct'] = $arr;
    die('');
}
function default_updateProduct() {
    global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg, $ads, $canonical, $google_track;
    #
    $product_id = $_POST['product_id'];
    $num = $_POST['num'];
    $arr = $_SESSION['listProduct'];
    if(array_key_exists($product_id,$arr)) {
        $arr[$product_id] = $num;
    } 
    $_SESSION['listProduct'] = $arr;
    die('');
}
?>