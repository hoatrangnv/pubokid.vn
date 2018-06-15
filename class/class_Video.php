<?php
/**
*  Created by   :
*  @author		: Ong The Thanh
*  @date		: 2012/01/23
*  @version		: 0.0.1
*/ 
class Video extends dbBasic{
	function Video(){
		$this->pkey = "video_id";
		$this->tbl = DB_PREFIX."video";
	}
    function getLink($id){
        $res = $this->getOne($id);
        return '/'.$res['slug'].'-video'.$id.'.html';
    }
    function getImage($_id, $w, $h){
		$res = $this->getOne($_id);
		$image = trim($res['image']);
        if(!$image) $image=PCMS_URL.'/upload/nophoto.jpg';
        if(substr($image,0,4)=='http') {$image = $image;}
        elseif(substr($image,0,1)=='/') $image = PCMS_URL.$image;
        else $image = PCMS_URL.'/'.$image;
        return $image;
        //return PCMS_URL.'/photos/'.$w.'x'.$h.'/'.urlencode(base64_encode($image)).'.jpg';
	}
    function getCons($category_id=0) {
        $cons = "is_trash=0";
        if($category_id>0) {
            $clsCategory = new Category();
            $allCat = $clsCategory->getChild($category_id); $allCat[] = $category_id;
            $cons.=" and category_id in (".implode(',', $allCat).")";
        }
        return $cons;
    }
    function plusView($news_id) {
        $oneNews = $this->getOne($news_id);
        $views = (int)$oneNews['views']; $views++; $oneNews['views']=$views;
        $views_week = (int)$oneNews['views_week']; $views_week++; $oneNews['views_week']=$views_week;
        $views_month = (int)$oneNews['views_month']; $views_month++; $oneNews['views_month']=$views_month;
        $views_day = (int)$oneNews['views_day']; $views_day++; $oneNews['views_day']=$views_day;
        $this->updateOne($news_id, array('views'=>$views,'views_week'=>$views_week,'views_month'=>$views_month,'views_day'=>$views_day));
        return true;
    }
    function getLinkKeywords($_id) {
        $oneNews = $this->getOne($_id);
        $keyword = trim(html_entity_decode($oneNews['tags']));
        $all=explode(',', $keyword);
        $res = "";
        if(is_array($all)) foreach($all as $key=>$one) if($one!='') {
            if($key>0) $res.=', ';
       	    $res.='<a style="margin-bottom: 5px;" href="'.PCMS_URL.'/'.str_replace(' ', '-', trim($one)).'-tag/">'.trim($one).'</a>';
        }
        return $res;
    }
}
?>