<?php
/**
*  Defautl action
*  @author		: Ong Thế Thành	
*  @date		: 2012/01/23	
*  @version		: 0.0.1
*/
function default_default(){
	global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg;
	#
    $classTable = ucfirst(strtolower($mod));
    $clsClassTable = new $classTable; $assign_list["clsClassTable"] = $clsClassTable;
    $pkeyTable = $clsClassTable->pkey; $assign_list["pkeyTable"] = $pkeyTable;
    #
    if($_GET['is_trash']) $cons.=" is_trash=".$_GET['is_trash'];
    else $cons.=" is_trash=0";
    
    if($_POST['keyword']) {
        $slug = $core->toSlug($_POST['keyword']);
        $cons .= " and slug like '%".$slug."%'";
        $assign_list["keyword"] = $_POST['keyword'];
    }
    #
    $listItem = $clsClassTable->getListPage($cons."  order by ".$pkeyTable." desc",RECORD_PER_PAGE,"",false);
    $paging = $clsClassTable->getNavPage($cons);
    $assign_list["listItem"] = $listItem;
    $assign_list["paging"] = $paging;
    $assign_list["cursorPage"] = isset($_GET["page"])? $_GET["page"] : 1;
    #
	/*=============Title & Description Page==================*/
	$title_page = "List - ".$classTable;
	$description_page = '';
	$keyword_page = '';
	/*=============Content Page==================*/
}
function default_new(){
	global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg;
	#
    $classTable = ucfirst(strtolower($mod));
    $clsClassTable = new $classTable; $assign_list["clsClassTable"] = $clsClassTable;
    $clsNews = new News; $assign_list["clsNews"] = $clsNews;
    #
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey ;
    #
    if($_POST) {

        
        if($_POST['send_date']) {
            $arr = explode(' ', $_POST['send_date']);
            $date = explode('/', $arr[0]);
            $_POST['send_date'] = strtotime($date[2].'-'.$date[1].'-'.$date[0].' '.$arr[1].':00');

        }
        
        $_POST['slug'] = $core->toSlug($_POST['slug']);
        $_POST['reg_date'] = time();
        if($clsClassTable->insertOne($_POST)) {
            header('location: ?mod='.$mod.'&act=edit&id='.mysql_insert_id().'&mes=insertSuccess');
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
    $classTable = ucfirst(strtolower($mod));
    $clsClassTable = new $classTable; $assign_list["clsClassTable"] = $clsClassTable;
    $oneItem = $clsClassTable->getOne($_GET['id']);
    $clsNews = new News; $assign_list["clsNews"] = $clsNews;
    if($oneItem) foreach($oneItem as $key => $val) {
        $assign_list[$key] = $val;
    }
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey ;
    #
    if($_POST) {
        
        if($_POST['send_date']) {
            $arr = explode(' ', $_POST['send_date']);
            $date = explode('/', $arr[0]);
            $_POST['send_date'] = strtotime($date[2].'-'.$date[1].'-'.$date[0].' '.$arr[1].':00');

        }
        
        if($clsClassTable->updateOne($_GET['id'],$_POST))
            header('location: ?mod='.$mod.'&act='.$act.'&id='.$_GET['id'].'&mes=updateSuccess');
        else {
            foreach ($_POST as $key => $val) {
                $assign_list[$key] = $val;
            }
            $msg = "Chỉnh sửa thất bại!";
        }
        unset($_POST);
    }
	#
	/*=============Title & Description Page==================*/
	$title_page = "Edit - ".$classTable;
	$description_page = '';
	$keyword_page = '';
	/*=============Content Page==================*/
}
function default_in_trash(){
	global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg;
    #
    $classTable = ucfirst(strtolower($mod));
    $clsClassTable = new $classTable; $assign_list["clsClassTable"] = $clsClassTable;
    $pkeyTable = $clsClassTable->pkey; $assign_list["pkeyTable"] = $pkeyTable;
    #
    $listItem = $clsClassTable->getListPage("is_trash=1 order by ".$pkeyTable." desc");
    $paging = $clsClassTable->getNavPage("is_trash=1");
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
function default_movedown(){
	global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg;
    #
    
    #
    $pvalTable = isset($_GET['id'])? $_GET['id'] : "";
    unset($_GET['act']); unset($_GET['id']);    
	$first=true; $url='?'; if($_GET) foreach($_GET as $key => $val) { if($first) $first=false; else $url.='&'; $url.=$key.'='.$val; }
    #    
	$classTable = ucfirst(strtolower($mod));
	$clsClassTable = new $classTable;
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey ;
	
	if($pvalTable == "") 
		header('location: '.$url.'&mes=moveFalse');
	$one = $clsClassTable->getOne($pvalTable);
	
	$current_pos = $one['order_no'];
	$all = $clsClassTable->getAll("parent_id='".$_GET['parent_id']."' and order_no<'$current_pos' order by order_no desc");
    if(is_array($all)) {
        $prev_pos = $all[0]['order_no'];
        $prev_id = $all[0][$pkeyTable];
        if((int)$prev_id>0) {
            if(($clsClassTable->updateOne($pvalTable, array('order_no'=>$prev_pos)) and ($clsClassTable->updateOne($prev_id,array('order_no'=>$current_pos)))))
                header('location: '.$url.'&mes=moveSuccess');
        }
    }
    else header('location: '.$url.'&mes=moveFalse');
}

function default_moveup(){
	global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg;
    #
    
    #
    $pvalTable = isset($_GET['id'])? $_GET['id'] : "";
    unset($_GET['act']); unset($_GET['id']);    
	$first=true; $url='?'; if($_GET) foreach($_GET as $key => $val) { if($first) $first=false; else $url.='&'; $url.=$key.'='.$val; }
    #    
	$classTable = ucfirst(strtolower($mod));
	$clsClassTable = new $classTable;
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey ;
	
	if($pvalTable == "") 
		header('location: '.$url.'&mes=moveFalse');
	$one = $clsClassTable->getOne($pvalTable);
	
	$current_pos = $one['order_no'];
	$all = $clsClassTable->getAll("parent_id='".$_GET['parent_id']."' and order_no>'$current_pos' order by order_no asc");
    if(is_array($all)) {
    	$next_pos = $all[0]['order_no'];
    	$next_id = $all[0][$pkeyTable];
        if((int)$next_id>0) {
            if($clsClassTable->updateOne($pvalTable,array('order_no'=>$next_pos)) and $clsClassTable->updateOne($next_id,array('order_no'=>$current_pos)))
                header('location: '.$url.'&mes=moveSuccess');
        }
    }        
    else header('location: '.$url.'&mes=moveFalse');
}
function default_trash(){
    global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg;
    #
	$id = $_GET['id'];
    if(!$id) die('Not ID!');
    $classTable = ucfirst(strtolower($mod));
    $clsClassTable = new $classTable;
    if($clsClassTable->updateOne($id,array('is_trash'=>1))) die('1');
    else die('Update!');
}
function default_restore(){
    global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg;
    #
	$id = $_GET['id'];
    if(!$id) die('0');
    $classTable = ucfirst(strtolower($mod));
    $clsClassTable = new $classTable;
    if($clsClassTable->updateOne($id,array('is_trash'=>'0'))) die('1');
    else die('0');
}
function default_delete(){
    global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg;
    #
	$id = $_GET['id'];
    if(!$id) die('0');
    $classTable = ucfirst(strtolower($mod));
    $clsClassTable = new $classTable;
    if($clsClassTable->deleteOne($id)) die('1');
    else die('0');
}
function default_sendartice() {
    global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg;
    #
	$id = $_GET['id'];
    $classTable = ucfirst(strtolower($mod));
    $clsClassTable = new $classTable;$clsNews = new News;
    
    $subscribe = $clsClassTable->getOne($id);
    $lnews = $subscribe['news_id'];
    $html = '<div style="background-color:#fff;font-family:Helvetica Neue,Helvetica,Arial,sans-serif;font-size:14px;color:#666;margin:0 auto;padding:0">
        <table style="max-width:600px;width:600px" border="0" cellspacing="0" cellpadding="0" align="center">
<tbody><tr style="height:80px;width:600px" align="center">
<td style="color:#fff;font-size:30px;font-family:Arial" align="center" valign="middle" width="600" height="80"><img src="http://thethao24.tv/upload/banner_em.jpg" width="600"></td>
</tr><tr style="height:20px;width:600px">
<td style="font-family:Arial;font-size:12px;font-style:italic" align="center" valign="top" bgcolor="#ffffff" width="600" height="20">Ngày '.date("d-m-Y").'</td>
</tr><tr><td><table style="max-width:600px;width:600px;font-family:helvetica,arial,sans-serif;color:#555;font-size:13px;line-height:15px" border="0" cellspacing="0" cellpadding="10" align="center">
<tbody><tr>
<td><table style="width:100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
<tbody>';

    if($subscribe['type'] == 3) {
        $arr = explode("|", $lnews);     
    } else if($subscribe['type'] == 1) {
            $arr = $clsNews->getAll($clsNews->getCons($subscribe['category_id']).' order by push_date desc limit 4',"",true);
        } else if($subscribe['type'] == 2){
            $arr = $clsNews->getAll($clsNews->getCons().' and is_pick=1 order by pick_date desc limit 4',"",true);
        } else if($subscribe['type'] == 4){
            $arr = $clsNews->getAll($clsNews->getCons().' and is_hot = 1 order by order_hot, hot_date desc limit 4',"",true);
        }
    else {
        die();
    }
    
    
    if($arr) foreach($arr as $one) if($one) {
        $news = $clsNews->getOne($one);
        $html .= '<tr>
                <td style="font-family:Arial;font-size:12px;padding-bottom:8px;padding-top:8px;border-bottom:1px dotted #e2e2e3">
                <img style="padding-right:15px" alt="picture" width="133" height="100" align="left" src="'.$clsNews->getImage($news['news_id'],148,100).'"> 
                <a rel="nofollow" style="color:#000;text-decoration:none" href="'.$clsNews->getLink($news['news_id']).'" target="_blank"><strong>'.$news['title'].'</strong></a><br>
                <div>'.$news['intro'].'</div></td></tr>';
        }
        $html .= '
        <tr><td style="padding-top:10px">
            <a rel="nofollow" style="float:left;margin-left:15px;padding:5px 10px;text-decoration:none;color:#fff;background-color:#ddd" href="{link_unsubscribe}" target="_blank">Ngừng nhận bản tin</a> 
            <a rel="nofollow" style="float:right;margin-right:15px;padding:5px 10px;text-decoration:none;color:#fff;background-color:#ef4034" href="http://thethao24.tv" target="_blank">Xem Thêm</a>
            </td>
        </tr>
        </tbody>
    </table></td>
    </tr></tbody></table></td></tr><tr><td style="font-family:Arial;font-size:12px"></td></tr><tr><td style="font-family:Arial;font-size:12px" bgcolor="#ffffff">&nbsp;</td></tr></tbody></table></div>'; 

    
    #
        require_once ('lib/phpmailer/class.phpmailer.php');
        $title = "Bản tin thể thao 24h";
        $subject = $title;
        $subject = '=?UTF-8?B?' . base64_encode($subject) . '?=';
        $mail = new PHPMailer();
        $mail->CharSet = 'UTF-8';
        $mail->SMTPDebug = 0; 
        $mail->IsSMTP();
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = "tls";
        $mail->Host = "mail.sport24h.com.vn";
        $mail->Port = 25;
        $mail->Username = "noreply@sport24h.com.vn";
        $mail->Password = "Thethaoketnoibanbe";
        $mail->SetFrom('noreply@sport24h.com.vn', 'Thethao24tv');
        $mail->Subject = $subject;
    
    #
    
    
    $clsProfiles = new Profile;
    $listProfiles = $clsProfiles->getAll('is_trash = 0 and is_confirm = 1 and is_unsubscribe = 0');
    $count = 0;
    foreach($listProfiles as $profiles) { $profiles = $clsProfiles->getOne($profiles);
        $body = str_replace("{link_unsubscribe}","http://thethao24.tv/unsubscribe".$profiles['profile_id'].".html",$html);
        $mail->MsgHTML($body);
        $mail->AddAddress($profiles['email'], "");
        if (!$mail->Send()) {
            
        } else {
            $count++;
        }
    }
    echo($count);
    if($count > 0) $clsClassTable->updateOne($id,array("is_send"=>1));
    die();
}
?>