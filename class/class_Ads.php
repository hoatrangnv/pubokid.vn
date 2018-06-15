<?php

class Ads extends dbBasic{
	function Ads(){
		$this->pkey = "ads_id";
		$this->tbl = DB_PREFIX."ads";
	}
    function plusView($_id) {
        $one = $this->getOne($_id);
        $views = (int)$one['views']; $views++;
        $this->updateOne($_id, array('views'=>$views));
        return true;
    }
    function getAllShowDate() {
        $key = 'default_ads_show_date';
        return $this->getCache($key);
    }
    function setAllShowDate($array) {
        $key = 'default_ads_show_date';
        $this->setCache($key, $array,720000);
    }
    function setShowDate($show_date,$news_id) {
        $all = $this->getAllShowDate();
        $show_date = strtotime($show_date);
        $now = time(); if($show_date<$now) return false; 
        $all[$news_id] = $show_date;
        //print_r($all);
        $this->setAllShowDate($all);
    }
    function checkShowDate() {
        $all = $this->getAllShowDate();
        $now = time();
        $active = false;
        print_r($all);
        echo date('d/m/Y H:i:s');
        if($all) foreach($all as $key=>$val) {
            echo 'HENGIOADS_'.$key.' '.date('d/m/Y H:i:s', $val).'<br />';
            if($val<$now) {
                $active = true;
                $news_id = $key;
                unset($all[$key]);
                print_r($news_id);
                echo date('d/m/Y H:i:s', $val).'<br />';
            }
        }
        if($active) {
            echo 'da acti';
            print_r($all);
            $this->setAllShowDate($all);
            $this->deleteArrKey();
            $this->deleteArrKey("KEYARR_ADS");
            $clsAdsContent = new AdsContent();
            $clsAdsContent->deleteArrKey("KEYARR_ADS");
        }
    }
    function delAllKeyCache(){
        $arr = $this->getAllKeyCache();
        foreach($arr as $key) {
            $this->deleteArrKey($key);
        }
    }
    function getContent($name, $category='',$act='') {
        $key = MEMCACHE_NAME.'_Get_Content_5'.$act.DEVICE.$name.'_'.$category;
        $res = $this->getCache($key); if($res) return $res;
        if($category=='') {
            if($act == 'detail') {
                $one=$this->getOneAds($name, '0','detail');
            }
            else $one=$this->getOneAds($name, '0');
            if($one) {
                if(DEVICE == 'mobile') {
                    if($one['is_mobile']) $res=$one['content']; else $res="";
                } else {
                    if($one['is_pc']) $res=$one['content']; else $res="";
                }
            } else $res='';
            
        } else if($category>0) {            
            $clsCategory = new Category();
            $clsAdsContent = new AdsContent();
            $parent_id = $clsCategory->getParentID($category);
            #
            if($parent_id>0) $one=$this->getOneAds($name, '2');
            else $one=$this->getOneAds($name, '1');
            #
            if($one) {
                if(DEVICE == 'mobile') {
                    if($one['is_mobile']) {
                        $content = $clsAdsContent->getContent($one['ads_id'], $category);
                        if($content!='') $res=$content;
                        else {
                            if($parent_id>0) $res=$this->getContent($name, $parent_id);
                            //elseif($parent_id==0) $res=$this->getContent($name, '');
                            else $res='';
                        }
                    } else $res="";
                }
                else {
                    if($one['is_pc']) {
                        $content = $clsAdsContent->getContent($one['ads_id'], $category);
                        if($content!='') $res=$content;
                        else {
                            if($parent_id>0) $res=$this->getContent($name, $parent_id);
                            //elseif($parent_id==0) $res=$this->getContent($name, '');
                            else $res='';
                        }
                    } else $res="";
                }
                
            } else $res='';
        }
        $this->setCache($key, '<span class="ads_views" data="'.$one['ads_id'].'">'.$res.'</span>');
        $this->setArrKey($key,"KEYARR_ADS");
        if($res) return '<span class="ads_views" data="'.$one['ads_id'].'">'.$res.'</span>';
        else return false;
    }
    function getOneAds($name, $type,$option = '') {
        if($option == 'detail') {
            $cons_meta = ' and is_detail = 1';
            $all = $this->getAll("is_trash=0 and start_ < now() and finish_ > now() and is_push = 1 and slug='".$name."' and type='".$type."' ".$cons_meta." order by ads_id limit 0,1");
        } 
        if($option == 'mobile') {
            $cons_meta = ' and is_mobile = 1';
            $all = $this->getAll("is_trash=0 and start_ < now() and finish_ > now() and is_push = 1 and slug='".$name."' and type='".$type."' ".$cons_meta." order by ads_id limit 0,1");
        }
        if($option == '') {
            $all = $this->getAll("is_trash=0 and start_ < now() and finish_ > now() and is_push = 1 and slug='".$name."' and type='".$type."' order by ads_id limit 0,1");
        }
        if($all[0]) return $this->getOne($all[0]);
        else return false;
    }
    function getAllContent($category='') {
        $key = MEMCACHE_NAME.'get_All_Content_13'.$_GET['act'].DEVICE.$category;
        $res = $this->getCache($key); if($res) return $res;
        $arr[] = 'header';
        $arr[] = 'right-1';
        $arr[] = 'right-2';
        $arr[] = 'right-3';
        $arr[] = 'right-4';
        $arr[] = 'right-5';
        $arr[] = 'right-6';
        $arr[] = 'right-7';
        $arr[] = 'right-8';
        $arr[] = 'right-9';
        $arr[] = 'right-10';
        $arr[] = 'middle-1';
        $arr[] = 'middle-2';
        $arr[] = 'middle-3';
        $arr[] = 'middle-4';
        
        $arr[] = 'detail-1';
        $arr[] = 'detail-2';
        $arr[] = 'detail-3';
        $arr[] = 'detail-4';
        $arr[] = 'detail-5';
        $arr[] = 'detail-6';
        $arr[] = 'detail-7';
        $arr[] = 'detail-8';
        $arr[] = 'detail-9';
        
        if(DEVICE != 'mobile' && $_GET['act'] != 'detailvideo') $arr[] = 'ballon'; 
        $arr[] = 'left';
        $arr[] = 'right';  
        if(!$category) {
            $arr[] = 'middle-5';
            $arr[] = 'middle-6';
            $arr[] = 'middle-7';
            $arr[] = 'middle-8'; 
            $arr[] = 'middle-9';
            $arr[] = 'middle-10';
            $arr[] = 'middle-11';
            $arr[] = 'middle-12';
            $arr[] = 'middle-13';
        }
        $res = array();
        foreach($arr as $one) {
            if($_GET['act'] == 'detail') $res[$one] = $this->getContent($one, $category,'detail');
            else $res[$one] = $this->getContent($one, $category);
        }
        $this->setCache($key, $res,7200);
        $this->setArrKey($key,"KEYARR_ADS");
        return $res;
    }
}
?>