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
    if($_GET['date_from']) $cons .= " and push_date>='".strtotime($_GET['date_from'])."'";
    if($_GET['date_to']) $cons .= " and push_date<='".(strtotime($_GET['date_to'])+24*60*60)."'";
    //die($cons);
    #
    if($_GET['keyword']) {
        $slug = $core->toSlug($_GET['keyword']);
        $cons .= " and slug like '%".$slug."%'";
        $assign_list["keyword"] = $_GET['keyword'];
    }
    $cons.=" and user_id='".$me['user_id']."'";
    if($reg_date) $cons.=" and reg_date like '%".$reg_date."%'";
    $listItem = $clsClassTable->getListPage($cons." order by news_id desc");
    $paging = $clsClassTable->getNavPage($cons);
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
    ob_clean();
    if($_GET['output']=='excel') {
        require_once 'lib/PHPExcel/PHPExcel.php';
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("info@thethao24.tv")
			 ->setLastModifiedBy("nguyenvanduc.ptit@gmail.com")
			 ->setTitle("Bài của tôi Thethao24")
			 ->setSubject("Bài của tôi Thethao24")
			 ->setDescription("Bảng Bài của tôi Thethao24");
                                     
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'STT')
            ->setCellValue('B1', 'Tiêu đề')
            ->setCellValue('D1', 'Chuyên mục')
            ->setCellValue('E1', 'Views')
            ->setCellValue('G1', 'Link')
            ->setCellValue('J1', 'Ngày xuất bản');
        
        if($_GET['nolimit']==1) {
            $listItem = $clsClassTable->getAll($cons." order by news_id desc",false);
        }
        
        if($listItem) foreach($listItem as $key=>$oneItem) {
            $oneItem=$clsClassTable->getOne($oneItem);
            $oneUser = $clsUser->getOne($oneItem['user_id']);
            $channel_title = '';
            $clsChannel = new Channel; 
            if(trim($oneItem['channel_path'], '|') != '') { 
                $arr = array();
                $arr = explode('|', trim($oneItem['channel_path'],'|')); 
                $channel_id=$arr[0]; 
                $channel_title = $clsChannel->getTitle($channel_id); 
            }
            $i = $key+2;
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$i, $i-1)
                ->setCellValue('B'.$i, $oneItem['title'])
                ->setCellValue('D'.$i, $clsCategory->getTitle($oneItem['category_id']))
                ->setCellValue('E'.$i, $oneItem['views'])
                ->setCellValue('G'.$i, $clsClassTable->getLink($oneItem['news_id']))
                ->setCellValue('J'.$i, date('d/m/Y', $oneItem['push_date']));

        }
                    
        $objPHPExcel->getActiveSheet()->setTitle('THETHAOT24');
        header('Content-type: application/vnd.ms-excel');
        $file_name = $me['user_name'];
        if($_GET['date_from'] || $_GET['date_to']) {
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
?>