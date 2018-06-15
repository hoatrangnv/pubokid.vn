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
    $clsCategory = new Category(); $assign_list['clsCategory'] = $clsCategory;
    $clsSignature=new Signature(); $assign_list['clsSignature'] = $clsSignature;
    $clsUser = new User(); $assign_list['clsUser'] = $clsUser; $me = $clsUser->getMe(); $assign_list['me'] = $me;
    $classTable = 'News';
    $clsClassTable = new $classTable; $assign_list["clsClassTable"] = $clsClassTable;
    $pkeyTable = $clsClassTable->pkey; $assign_list["pkeyTable"] = $pkeyTable;
    #
    $cons = "is_trash=0 and is_draft=0 and is_push=1"; 
    if($_GET['date_from']) $cons .= " and push_date>='".date('Y-m-d', strtotime($_GET['date_from']))."'";
    if($_GET['date_to']) $cons .= " and push_date<='".date('Y-m-d', strtotime($_GET['date_to'])+24*60*60)."'";
    if($_GET['keyword']) {
        $slug = $core->toSlug($_GET['keyword']);
        $cons .= " and slug like '%".$slug."%'";
        $assign_list["keyword"] = $_GET['keyword'];
    }
    //die($cons);
    #
    if($_GET['user_id']) $cons.=" and user_id='".$_GET['user_id']."'";
    //if($reg_date) $cons.=" and reg_date like '%".$reg_date."%'";
    if($_GET['nolimit']) {
        $listItem = $clsClassTable->getAll($cons." order by push_date");
    } else {
        $listItem = $clsClassTable->getListPage($cons." order by push_date");
        $paging = $clsClassTable->getNavPage($cons);
    }
    $assign_list["listItem"] = $listItem;
    $assign_list["paging"] = $paging;
    $assign_list["cursorPage"] = isset($_GET["page"])? $_GET["page"] : 1;
    #
    $total_news = $clsClassTable->getSum('royalty_news',$cons,'SUM'); $assign_list["total_news"] = $total_news;
    $total_photo = $clsClassTable->getSum('royalty_photo',$cons,'SUM'); $assign_list["total_photo"] = $total_photo;
    $total_video = $clsClassTable->getSum('royalty_video',$cons,'SUM'); $assign_list["total_video"] = $total_video;
    $total_other = $clsClassTable->getSum('royalty_other',$cons,'SUM'); $assign_list["total_other"] = $total_other;
    $total_audio = $clsClassTable->getSum('royalty_audio',$cons,'SUM'); $assign_list["total_audio"] = $total_audio;
    #
    if($_GET['output']=='excel') {
        require_once 'lib/PHPExcel/PHPExcel.php';
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("info@khoahocphattrien.vn")
			 ->setLastModifiedBy("otthanh@gmail.com")
			 ->setTitle("Nhuận bút Khoahocphattrien.vn")
			 ->setSubject("Nhuận bút Khoahocphattrien.vn")
			 ->setDescription("Bảng tính nhuận bút của Khoahocphattrien.vn")
			 ->setKeywords("Nhuận bút")
			 ->setCategory("Salary");
                                     
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'STT')
            ->setCellValue('B1', 'Tiêu đề')
            ->setCellValue('C1', 'Thành viên')
            ->setCellValue('D1', 'Bút danh')
            ->setCellValue('E1', 'Views')
            ->setCellValue('F1', 'Tin bài')
            ->setCellValue('G1', 'Hình ảnh')
            ->setCellValue('H1', 'Video')
            ->setCellValue('I1', 'Radio')
            ->setCellValue('J1', 'Khác')
            ->setCellValue('K1', 'Tổng')
            ->setCellValue('L1', 'Ngày xuất bản')
            ->setCellValue('M1', 'URL');
            
        if($listItem) foreach($listItem as $key=>$oneItem) {
            $oneItem=$clsClassTable->getOne($oneItem);
            $oneUser = $clsUser->getOne($oneItem['user_id']);
            $i = $key+2;
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$i, $i-1)
                ->setCellValue('B'.$i, $oneItem['title'])
                ->setCellValue('C'.$i, $oneUser['user_name'])
                ->setCellValue('D'.$i, $clsSignature->getTitle($oneItem['signature_id']))
                ->setCellValue('E'.$i, $oneItem['views'])
                ->setCellValue('F'.$i, $oneItem['royalty_news'])
                ->setCellValue('G'.$i, $oneItem['royalty_photo'])
                ->setCellValue('H'.$i, $oneItem['royalty_video'])
                ->setCellValue('I'.$i, $oneItem['royalty_audio'])
                ->setCellValue('J'.$i, $oneItem['royalty_other'])
                ->setCellValue('K'.$i, $oneItem['royalty_news']+$oneItem['royalty_photo']+$oneItem['royalty_video']+$oneItem['royalty_audio']+$oneItem['royalty_other'])
                ->setCellValue('L'.$i, date('d/m/Y', strtotime($oneItem['push_date'])))
                ->setCellValue('M'.$i, $clsClassTable->getLink($oneItem[$pkeyTable]));
        }
                    
        $objPHPExcel->getActiveSheet()->setTitle('KHOAHOCPHATTRIEN');
        header('Content-type: application/vnd.ms-excel');
        $file_name = 'Nhuan But';
        if($_GET['user_id'] || $_GET['date_from'] || $_GET['date_to']) {
            $file_name = '';
            if($_GET['user_id']) {
                $oneUser = $clsUser->getOne($_GET['user_id']);
                $file_name .= $oneUser['user_name'];
            }
            if(!$_GET['date_from']) $date_from = '01-01-2008'; else $date_from = date('d-m-Y', strtotime($_GET['date_from']));
            if(!$_GET['date_to']) $date_to = date('d-m-Y'); else $date_to = date('d-m-Y', strtotime($_GET['date_to']));
            $file_name .= ' - ['.$date_from.']';
            $file_name .= ' - ['.$date_to.']';
        }
        $file_name .= '- Trang '.$assign_list["cursorPage"];
        header('Content-Disposition: attachment; filename="'.$file_name.'.xls"');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        die();
    }
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
    $data['royalty_news'] = intval(str_replace('.', '', $_GET['royalty_news']));
    $data['royalty_photo'] = intval(str_replace('.', '', $_GET['royalty_photo']));
    $data['royalty_video'] = intval(str_replace('.', '', $_GET['royalty_video']));
    $data['royalty_other'] = intval(str_replace('.', '', $_GET['royalty_other']));
    $data['royalty_audio'] = intval(str_replace('.', '', $_GET['royalty_audio']));
    $total = $data['royalty_news']+$data['royalty_photo']+$data['royalty_video']+$data['royalty_other']+$data['royalty_audio'];
    if(!$id) die('0');
    $clsNews= new News();
    $clsNews->deleteArrKey('SUM');
    if($clsNews->updateOne($id, $data)) die($core->toString($total));
    else die('0');
}
function default_ajax_noti(){
    $id = intval($_POST['id']); if(!$id) die('0');
    $note = strval($_POST['note']);
    $clsNews= new News();
    if($clsNews->updateOne($id, array('note'=>$note))) die('1');
    else die('0');
}
?>