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
    $clsUser = new User;$assign_list['clsUser'] = $clsUser;
    $clsNews = new News(); $assign_list['clsNews'] = $clsNews;
    
    $listUser = $clsUser->getAll('is_trash=0');$assign_list['listUser'] = $listUser;
    
    $keycache = 'TKBV';
            $slday = 7;
    if($_POST['thongke']) {        
        unset($listUser);unset($checked);
        $i=0;
        if($_POST['user_id']) foreach($_POST['user_id'] as $one) {
            $listUser[$i] = $one;
            $keycache .= $one;
            $checked[$one] = 'checked';
            $i++;
        }
        if(!$_POST['start_date']) $date_from = date('Y-m-d',time()-86400*$slday); else $date_from = date('Y-m-d', strtotime($_POST['start_date']));
        if(!$_POST['end_date']) $date_to = date('Y-m-d'); else $date_to = date('Y-m-d', strtotime($_POST['end_date']));
        $assign_list['start_date'] = $_POST['start_date'];
        $assign_list['end_date'] = $_POST['end_date'];
        $keycache .= md5($date_from.$date_to);
        $slday = (round(strtotime($date_to) - strtotime($date_from))/86400)+1;
        
    } else {
        $date_from = date('Y-m-d',time()-86400*$slday);
        $date_to = date('Y-m-d');
    }
    $assign_list['checked1'] = $checked;
    
    $array = $clsNews->getCache($keycache);
    if(!$array) {
        $array[0][] = 'Day';
        if(!$_POST['user_id'] || $_POST['user_id'][0]==0) {
            $array[0][] = 'Tất cả';
        } else {
            foreach($listUser as $oneUser) { 
                $one = $clsUser->getOne($oneUser);
                $array[0][] = $one['full_name'];
            }
        }
        
        
        
        for($i = 1; $i<=intval($slday); $i++) {
            $array[$i][] = date('d/m/Y',strtotime($date_from)+86400*($i-1));
            $time = strtotime($date_from)+86400*$i;
            $time1 = strtotime($date_from)+86400*($i-1);
            
            foreach($listUser as $oneUser) {
                $one = $clsUser->getOne($oneUser);
                $cons = "is_trash=0 and is_draft=0 and is_push=1 and is_unpush=0 and status=3 and push_date>=".$time1." and push_date<=".$time." and user_id = ".$one['user_id'];
                $slbv = $clsNews->getCount($cons);
                $array[$i][] = intval($slbv);
            }
               
        }
        
        $array = json_encode($array);
        $clsNews->setCache($keycache,$array,43200);
    }
    
    $assign_list['array'] = $array;
        
    
	/*=============Title & Description Page==================*/
    $title_page = 'Thống kê bài viết';
	$description_page = '';
	$keyword_page = '';
	/*=============Content Page==================*/
}
function default_views(){
	global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg;
	#
    $clsCategory = new Category;$assign_list['clsCategory'] = $clsCategory;
    $clsStatistic = new Statistic;$assign_list['clsStatistic'] = $clsStatistic;
    
    $keycache = 'TKV';
            $slday = 5;
    if($_POST['thongke']) {        
        if(!$_POST['start_date']) $date_from = date('Y-m-d',time()-86400*$slday); else $date_from = date('Y-m-d', strtotime($_POST['start_date']));
        if(!$_POST['end_date']) $date_to = date('Y-m-d'); else $date_to = date('Y-m-d', strtotime($_POST['end_date']));
        $assign_list['start_date'] = $_POST['start_date'];
        $assign_list['end_date'] = $_POST['end_date'];
        $keycache .= md5($date_from.$date_to);
        $slday = (round(strtotime($date_to) - strtotime($date_from))/86400)+1;
        
    } else {
        $date_from = date('Y-m-d',time()-86400*$slday);
        $date_to = date('Y-m-d');
    }
    
    $array = $clsStatistic->getCache($keycache);

    if(!$array) {
        $array[0][] = 'Day';
        $array[0][] = 'Trang chủ';
        $array[0][] = 'Chuyên mục';
        $array[0][] = 'Bài viết';
        $array[0][] = 'Khác';
        
        
        for($i = 1; $i<=intval($slday)+1; $i++) {
            $array[$i][] = date('d/m/Y',strtotime($date_from)+86400*($i-1));
            $time = strtotime($date_from)+86400*($i-1);
            
            $cons = "day='".date("Y-m-d",$time)."'";
            
            $lslv = $clsStatistic->getAll($cons.' limit 1',false);
            
            if($lslv[0]) {
                $slv = $clsStatistic->getOne($lslv[0],false);
                $array[$i][1] =  intval($slv['views_home']);
                $array[$i][2] =  intval($slv['views_cat']);
                $array[$i][3] =  intval($slv['views_detail']);
                $array[$i][4] =  intval($slv['views_other']);    
            } else {
                $array[$i][1] =  0;
                $array[$i][2] =  0;
                $array[$i][3] =  0;
                $array[$i][4] =  0;    
            }
            
            
        }

        $array = json_encode($array);
        $clsStatistic->setCache($keycache,$array,43200);
    }
    
    
    $assign_list['array'] = $array;
        
    
	/*=============Title & Description Page==================*/
    $title_page = 'Thống kê lượt xem';
	$description_page = '';
	$keyword_page = '';
	/*=============Content Page==================*/
}
function default_viewschuyenmuc(){
	global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg;
	#
    $clsCategory = new Category;$assign_list['clsCategory'] = $clsCategory;
    $clsStatisticDetail = new StatisticDetail;$assign_list['clsStatisticDetail'] = $clsStatisticDetail;
    
    $keycache = 'TKCMV';
    $slday = 5;
    
    $listCategory = $clsCategory->getAll("is_trash = 0 and parent_id = 0");

    
    if($_POST['thongke']) {        
        if(!$_POST['start_date']) $date_from = date('Y-m-d',time()-86400*1); else $date_from = date('Y-m-d', strtotime($_POST['start_date']));
        if(!$_POST['end_date']) $date_to = date('Y-m-d'); else $date_to = date('Y-m-d', strtotime($_POST['end_date']));
        $assign_list['start_date'] = $_POST['start_date'];
        $assign_list['end_date'] = $_POST['end_date'];
        $keycache .= md5($date_from.$date_to);
        $slday = (round(strtotime($date_to) - strtotime($date_from))/86400)+1;
        
    } else {
        $date_from = date('Y-m-d',time()-86400*1);
        $date_to = date('Y-m-d');
        
    }
    $assign_list['date_from'] = $date_from;
    $assign_list['date_to'] = $date_to;
    //$array = $clsStatisticDetail->getCache($keycache);

    if(!$array) {
        $array[0][0] = 'Chuyên mục';
        $array[0][1] = 'Views';
        
        $cons = "day >= '".$date_from."' and day <= '".$date_to."'";
        
        
        foreach($listCategory as $k=>$cat) { $cat = $clsCategory->getOne($cat);
            $listChild = $clsCategory->getChild($cat['category_id']);
            if($listChild) $cons_add = ','.implode(',',$listChild);
            $lslv = $clsStatisticDetail->getSum('views',$cons.' and type = 1 and category_id in ('.$cat['category_id'].$cons_add.') limit 1');
            $array[$k+1][0] = $cat['title'];
            if($lslv) $array[$k+1][1] =  intval($lslv); else $array[$k+1][1] = 0;
        } 
        
        $array = json_encode($array);
        $clsStatisticDetail->setCache($keycache,$array,43200);
    }
    $assign_list['array'] = $array;

    
	/*=============Title & Description Page==================*/
    $title_page = 'Thống kê chuyên mục';
	$description_page = '';
	$keyword_page = '';
	/*=============Content Page==================*/
}
function default_viewsbvchuyenmuc(){
    global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg;
	#
    $clsCategory = new Category;$assign_list['clsCategory'] = $clsCategory;
    $clsNews = new News(); $assign_list['clsNews'] = $clsNews;
    
    $listCategory = $clsCategory->getAll('is_trash=0 and parent_id = 0');$assign_list['listCategory'] = $listCategory;
    
    $keycache = 'TKBVCM';
            $slday = 7;
    if($_POST['thongke'] || $_POST['output']) {        
        unset($listCategory);unset($checked);
        $i=0;
        if($_POST['category_id']) foreach($_POST['category_id'] as $one) {
            $listCategory[$i] = $one;
            $keycache .= $one;
            $checked[$one] = 'checked';
            $i++;
        } else {
            $listCategory = '';
        }
        if(!$_POST['start_date']) $date_from = date('Y-m-d',time()-86400*$slday); else $date_from = date('Y-m-d', strtotime($_POST['start_date']));
        if(!$_POST['end_date']) $date_to = date('Y-m-d'); else $date_to = date('Y-m-d', strtotime($_POST['end_date']));
        $assign_list['start_date'] = $_POST['start_date'];
        $assign_list['end_date'] = $_POST['end_date'];
        $keycache .= md5($date_from.$date_to);
        $slday = (round(strtotime($date_to) - strtotime($date_from))/86400)+1;
        
    } else {
        $date_from = date('Y-m-d',time()-86400*$slday);
        $date_to = date('Y-m-d');
    }
    $assign_list['checked1'] = $checked;

    $array = $clsNews->getCache($keycache);
    if(!$array) {
        $array[0][] = 'Day';
        if(!$_POST['category_id'] || $_POST['category_id'][0]==0) {
            $array[0][] = 'Tất cả';
        } else {
            foreach($listCategory as $oneCategory) { 
                $one = $clsCategory->getOne($oneCategory);
                $array[0][] = $one['title'];
            }
        }
        
        
        
        for($i = 1; $i<=intval($slday); $i++) {
            $array[$i][] = date('d/m/Y',strtotime($date_from)+86400*($i-1));
            $time = strtotime($date_from)+86400*$i;
            $time1 = strtotime($date_from)+86400*($i-1);
            
            
            if(!$listCategory) {
                $cons = $clsNews->getCons();
                $cons .= " and push_date>=".$time1." and push_date<=".$time;
                $slbv = $clsNews->getSum("views",$cons);
                $array[$i][] = intval($slbv);
            } else 
            
            foreach($listCategory as $oneCategory) {
                $cons = $clsNews->getCons($oneCategory);
                $cons .= " and push_date>=".$time1." and push_date<=".$time;
                
                $slbv = $clsNews->getSum("views",$cons);
                $array[$i][] = intval($slbv);
            }
               
        }
        
        
        
        $array = json_encode($array);
        $clsNews->setCache($keycache,$array,43200);
    }
    ob_clean();
    if($_POST['output']) {
        
            require_once 'lib/PHPExcel/PHPExcel.php';
            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setCreator("info@thethao24.vn")
			 ->setLastModifiedBy("nguyenvanduc.ptit@gmail.com");
            
            $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'STT')
            ->setCellValue('B1', 'Tiêu đề')
            ->setCellValue('C1', 'Views');
            
            
            foreach($listCategory as $k=>$one) { $one=$clsCategory->getOne($one);
                $title = $one['title'];
                $cons = $clsNews->getCons($one['category_id']);
                if($date_from && $date_to) $cons .= " and push_date>=".strtotime($date_from)." and push_date<=".strtotime($date_to);
                $views = $clsNews->getSum("views",$cons);

                $i = $k+2;
                
     
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A'.$i, $i-1)
                    ->setCellValue('B'.$i, $title)
                    ->setCellValue('C'.$i, $views);
            }
 
            $objPHPExcel->getActiveSheet()->setTitle('WEBTHETHAO');
            header('Content-type: application/vnd.ms-excel');
            $file_name = 'Thống kê chuyên mục';
            $file_name .= '- Trang '.$assign_list["cursorPage"];
            header('Content-Disposition: attachment; filename="'.$file_name.'.xls"');
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            $objWriter->save('php://output');
        }
    $assign_list['array'] = $array;
        
    
	/*=============Title & Description Page==================*/
    $title_page = 'Thống kê bài viết';
	$description_page = '';
	$keyword_page = '';
	/*=============Content Page==================*/
    
}
function default_new() {
    header('location: ?mod=thongke&mes=lock');
}
function default_edit() {
    header('location: ?mod=thongke&mes=lock');
}
function default_trash() {
    header('location: ?mod=thongke&mes=lock');
}
?>