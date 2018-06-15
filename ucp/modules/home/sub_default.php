<?php
/**
*  Defautl action
*  @author		: Ong Thế Thành	
*  @date		: 2012/01/23	
*  @version		: 0.0.1
*/
function default_default(){
    #
    $mes = $_GET['mes']?$_GET['mes']:'welcome';
    header('location: '.PCMS_URL.'/admin.php?mod=news&mes='.$mes);
}
function default_load_ajax() {
    global $core;
    #
    $classTable = ucfirst(strtolower($_GET['class']));
    $html = '<ul>';
    $clsClassTable = new $classTable;
    $pkeyTable = $clsClassTable->pkey;

    if($_GET['title']=='') die();
    if($classTable=='News') {
        $slug = $core->toSlug($_GET['title']);
        $all = $clsClassTable->getAll($clsClassTable->getCons()." and (slug like '%".$slug."%' or tags like '%".$slug."%') order by reg_date desc limit 10");
        if($all) foreach($all as $one) { $one=$clsClassTable->getOne($one);
            $res = $one[$pkeyTable];
            if($classTable=='Keywords') $res=$one['title'];
            $html .= '<li><a href="#" rel="'.$res.'">'.$one['title'].' ('.date("H:i d/m/Y",$one['push_date']).')'.'</a></li>';
        } else die();
        echo $html.'</ul>'; die();
    } else if($classTable=='Team') {
        $slug = $core->toSlug($_GET['title']);
        $all = $clsClassTable->getAll("is_trash = 0 and slug like '%".$slug."%' order by team_id desc limit 10");
        if($all) foreach($all as $one) { $one=$clsClassTable->getOne($one);
            $res = $one[$pkeyTable];
            $html .= '<li><a href="#" rel="'.$res.'" type="1">'.$one['title'].'</a></li>';
        } else die();
        echo $html.'</ul>'; die();
    } else if($classTable=='League') {
        $slug = $core->toSlug($_GET['title']);
        $all = $clsClassTable->getAll("is_trash = 0 and slug like '%".$slug."%' order by league_id desc limit 10");
        if($all) foreach($all as $one) { $one=$clsClassTable->getOne($one);
            $res = $one[$pkeyTable];
            $html .= '<li><a href="#" rel="'.$res.'" type="1">'.$one['title'].' '.$one['season'].'</a></li>';
        } else die();
        echo $html.'</ul>'; die();
    } else if($classTable=='Tags') {
        $slug = $core->toSlug($_GET['title']);
        $all = $clsClassTable->getAll("is_trash = 0 and slug like '%".$slug."%' order by tags_id desc limit 10");
        if($all) foreach($all as $one) { $one=$clsClassTable->getOne($one);
            $res = $one[$pkeyTable];
            $html .= '<li><a href="#" rel="'.str_replace("\"","'",$one['title']).'" type="1">'.$one['title'].'</a></li>';
        } else die();
        echo $html.'</ul>'; die();
    }
    else {
        $slug = $core->toSlug($_GET['title']);
        if($classTable=='Keywords') $all = $clsClassTable->getAll("is_trash = 0 and title like '".$_GET['title']."' order by keywords_id desc limit 10");
        else $all = $clsClassTable->getAll("is_trash = 0 and slug like '%".$slug."%' order by reg_date desc limit 10");

        if($all) foreach($all as $one) { $one=$clsClassTable->getOne($one);
            $res = $one[$pkeyTable];
            if($classTable=='Keywords') $res=$one['title'];
            if($classTable=='Category')
                if($clsClassTable->getParentID($one['category_id']) == 0) {
                    $one['title'] = $one['title'].'( Danh mục gốc )';
                } else {
                    $one['title'] = $one['title'].'( '.$clsClassTable->getTitle($clsClassTable->getParentID($one['category_id'])).' )';
                }
            
            $html .= '<li><a href="#" rel="'.$res.'">'.$one['title'].'</a></li>';
        } else die();
        echo $html.'</ul>'; die();
    }
}
function default_get_status() {
    global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg, $ads;
	#
    /*=============Content Page==================*/
    #
    $clsUser = new User; $me = $clsUser->getMe();
    $function = json_decode($me['function'],true);
    $clsNews = new News;
    $news_id = $_POST['news_id'];
    
    $one = $clsNews->getOne($news_id);
    $slide = ($one['position']==1)?0:1;
    $toptc = ($one['position']==2)?0:1;
    $pick = ($one['position']==3)?0:1;
    $hot = ($one['is_hot']==1)?0:1;
    $pick = ($one['is_pick']==1)?0:1;
    $top = ($one['is_top']==1)?0:1;
    $featured = ($one['is_featured']==1)?0:1;
    $push = ($one['is_push']==1)?0:1;
    #
    $s_slide = ($one['position']==1)?'on':'off';
    $s_toptc = ($one['position']==2)?'on':'off';
    $s_pick = ($one['position']==3)?'on':'off';
    $s_hot = ($one['is_hot']==1)?'on':'off';
    $s_pick = ($one['is_pick']==1)?'on':'off';
    $s_top = ($one['is_top']==1)?'on':'off';
    $s_featured = ($one['is_featured']==1)?'on':'off';
    $s_push = ($one['is_push']==1)?'on':'off';
    #
    
    if($me['user_level_id'] >= 3) {
        $res = '<table>
                <tbody>
                    <tr>
                        <td><a href="?mod=news&act=ajax&field=is_pick&value='.$pick.'&id='.$news_id.'" class="act_ajax js_check_'.$s_pick.'">NB trang chủ</a></td>
                        <td><a href="?mod=news&act=ajax&field=is_featured&value='.$featured.'&id='.$news_id.'" class="act_ajax js_check_'.$s_featured.'">NB chuyên mục</a></td>
                        <td><a href="?mod=news&act=ajax&field=is_push&value='.$push.'&id='.$news_id.'" class="act_ajax js_check_'.$s_push.'">Xuất bản</a></td>
                    </tr>
                    <tr>
                        <td><a href="?mod=news&act=ajax&field=is_hot&value='.$hot.'&id='.$news_id.'" class="act_ajax js_check_'.$s_hot.'">Tin mới</a></td>
                    </tr>
                </tbody>
            </table>';
        echo $res;
    } else {
        $res = '<table><tbody><tr>';
            if($function['is_hot']) $res .= '<td><a href="?mod=news&act=ajax&field=is_hot&value='.$hot.'&id='.$news_id.'" class="act_ajax js_check_'.$s_hot.'">Tin Mới</a></td>';
            if($function['is_pick']) $res .= '<td><a href="?mod=news&act=ajax&field=is_pick&value='.$pick.'&id='.$news_id.'" class="act_ajax js_check_'.$s_pick.'">NB trang chủ</a></td>';
            if($function['is_featured']) $res .= '<td><a href="?mod=news&act=ajax&field=is_featured&value='.$featured.'&id='.$news_id.'" class="act_ajax js_check_'.$s_featured.'">NB chuyên mục</a></td>';
        $res .= '</tr>';     
            if($me['is_push'] && $one['is_push'] != 1) $res .= '<td><a href="?mod=news&act=ajax&field=is_push&value='.$push.'&id='.$news_id.'" class="act_ajax js_check_'.$s_push.'">Xuất bản</a></td>';
        $res .= '</tr></tbody></table>';
        echo $res;
    }
    die();   
}
function default_save_pixlr() {
    global $core,$assign_list;
    $clsUser = new User;
    $me = $clsUser->getMe();

    $url = $_GET['image'];print_r($url);
    $directory = "files/".$me['user_name'];
    $ext = end(explode(".",strtolower(basename($url))));
    
    $reg_date = time();
    $directory1 = $directory.'/'.date('Y', $reg_date).'/'; if(!is_dir($directory1)) {$old = umask(0); mkdir($directory1, 0777); umask($old);}
    $directory1 .= date('m', $reg_date).'/'; if(!is_dir($directory1)) {$old = umask(0); mkdir($directory1, 0777); umask($old);}
    $directory1 .= date('d', $reg_date).'/'; if(!is_dir($directory1)) {$old = umask(0); mkdir($directory1, 0777); umask($old);}
    $directory1 .= $_GET['title'].'.'.$ext;
    
    file_put_contents($directory1, file_get_contents($url));
    $image_thumb = $directory1;
    
    print_r($directory1);
    
    $assign_list["url_server"] = str_replace("../","",$image_thumb);
}
function default_close_pixlr() {
    
}
?>