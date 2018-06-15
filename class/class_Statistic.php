<?php
/**
*  Created by   :
*  @author		: Ong The Thanh
*  @date		: 2012/01/23
*  @version		: 0.0.1
*  1.cat
*  2.detail
*  3.other
* 
*/ 
class Statistic extends dbBasic{
	function Statistic(){
		$this->pkey = "statistic_id";
		$this->tbl = "statistic";
	}
    function setViews($id,$address) {
        global  $mod,$act,$core;
        
        $mod1 =  $_GET['mod1'];
        $act1 =  $_GET['act1'];

        $date=date('Y-m-d');
        $statistic = $this->getAll('day="'.$date.'" limit 1');
        if($statistic[0]) {
            if($mod1 == 'home' && $act1 == 'default') {
                $count = 0;
                $count = intval($this->getCache("STATISTICHOME"));
                if($count > 4) {
                    $cons = 'views_home = views_home+5';
                    $this->setCache("STATISTICHOME",1,186400);
                } else {
                    $count = $count+1;
                    $this->setCache("STATISTICHOME",$count,186400);
                }
            } else if($mod1 == 'news' && ($act1 == 'default' || $act1 == 'subcat')) {
                $cons = 'views_cat = views_cat+1';
                $clsStatisticDetail = new StatisticDetail();
                $clsStatisticDetail->setViews($address,$statistic[0],1,$date,$id);
            } else if($mod1 == 'news' && ($act1 == 'detail' || $act1 == 'detailvideo')) {
                $count = 0;
                $count = intval($this->getCache("STATISTICNEWSDETAIL_".$id));

                if($count > 4) {
                    $clsNews = new News;
                    $cons = 'views_detail = views_detail+5';
                    $this->setCache("STATISTICNEWSDETAIL_".$id,1,186400);
                    $clsNews->getQuery('UPDATE default_news SET views = views+5,views_day = views_day + 5, views_week = views_week + 5, views_month = views_month+5 WHERE news_id='.$id);
                    echo 'UPDATE default_news SET views = views+5,views_day = views_day + 5, views_week = views_week + 5, views_month = views_month+5 WHERE news_id='.$id;
                } else {
                    $count = $count+1;
                    $this->setCache("STATISTICNEWSDETAIL_".$id,$count,186400);
                    echo $count;
                }
                
                $clsStatisticDetail = new StatisticDetail();
                $clsStatisticDetail->setViews($address,$statistic[0],2,$date);
            } else if($act1!='unknow') {
                $count = 0;
                $count = intval($this->getCache("STATISTICUNKNOW"));
                if($count > 4) {
                    $cons = 'views_other = views_other+5';
                    $this->setCache("STATISTICUNKNOW",1,186400);
                } else {
                    $count = $count+1;
                    $this->setCache("STATISTICUNKNOW",$count,186400);
                }
                
                $clsStatisticDetail = new StatisticDetail();
                $clsStatisticDetail->setViews($address,$statistic[0],3,$date);
            }
            if($cons) {
                $this->getQuery('UPDATE statistic SET '.$cons.' WHERE statistic_id='.$statistic[0],'',false);
            }
        } else {
            $this->getQuery('INSERT INTO statistic (day) VALUES ("'.$date.'")','',false);
            $this->deleteArrKey();
        }
    }
}
?>