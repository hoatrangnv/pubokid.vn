<?php
class News extends dbBasic{
	function News(){
		$this->pkey = "news_id";
		$this->tbl = DB_PREFIX."news";
	}
    function getTitle($news_id) {
        $res = $this->getOne($news_id);
        return $res['title'];
    }
       	function getLinkTitle($news_id,$class=''){
   	    $res = $this->getOne($news_id);
        $link = PCMS_URL.'/'.$res['slug']."-d".$res['news_id'].".html";
        if($res['title_seo']) {
            $t = $res['title_seo'];
        } else {
            $t = $res['title'];
        }
        return '<a href="'.$link.'"  style="text-transform: uppercase;" class="'.$class.'" title="'.$t.'">'.$res['title'].'</a>';
	}
    function news_in_home($category_id,$limit,$notcat,$not_news='') {
        $cons = $this->getCons($category_id);
        $listNews = $this->getAll($cons." order by push_date desc limit ".$limit,true,"KEYARR_".$category_id);
        //echo '<!--DEBUG'.$cons." and news_id != ".$listfeature[0]." order by push_date desc limit ".($limit-1).'-->';
        //array_unshift($listNews,$listfeature[0]);
        return $listNews;
    }
    function slugToIDDetail($slug) {
        $all = $this->getAll("is_trash = 0 and is_push = 1 and slug='".$slug."' order by ".$this->pkey." desc limit 0,1");
        if($all[0]) return $all[0];
    }
    function getSourceID($news_id) {
        $res = $this->getOne($news_id);
        $source_id = $res['source_id'];
        if(!$source_id) {
            $arr = parse_url($res['link']);
            $domain = $arr['host'];
            $clsSource = new Source();
            $all = $clsSource->getAll("is_trash=0 and domain='".$domain."'");
            $source_id = $all[0];
            if($source_id>0) {
                $this->updateOne($news_id, "source_id='".$source_id."'");
                return $source_id;
            }
        }
        return $source_id;
    }
	function getLink($news_id,$mobile = true){
        if($mobile == false) $pcms_url = 'https://pubokid.vn/';
        else $pcms_url = PCMS_URL;
		$res = $this->getOne($news_id);
        $slug = $res['slug'];
        if(!$slug) {
            $core = new core();
            $slug = $core->toSlug($res['title']);
            $value = "slug='".$slug."'";
            $this->updateOne($news_id, $value);
        }
        $link = $pcms_url.'/'.$slug."-d".$res['news_id'].".html";
        return $link;
	}
    
   	function getLinkVideo($news_id){
		$res = $this->getOne($news_id);
        $slug = $res['slug'];
        if(!$slug) {
            $core = new core();
            $slug = $core->toSlug($res['title']);
            $value = "slug='".$slug."'";
            $this->updateOne($news_id, $value);
        }
		$link = PCMS_URL.'/'.$slug."-v".$res['news_id'].".html";
        //$link = PCMS_URL.'/?mod=news&act=detailvideo&id='.$res['news_id'];
        return $link;
	}
    
    function getLinkRelated($news_id, $type=0){
		$res = $this->getOne($news_id);
        $slug = $res['slug'];
        if($type==1) return PCMS_URL.'/'.$slug."-d".$news_id."/tin-moi-related/";
        if($type==2) return PCMS_URL.'/'.$slug."-d".$news_id."/tin-hay-related/";
		return PCMS_URL.'/'.$slug."-d".$news_id."/related/";
	}
    function getLinkTag($tag, $page=1,$mobile=true){
        $core = new core();
        if($mobile == false) $pcms_url = 'https://pubokid.vn/';
        else $pcms_url = PCMS_URL;
        $slug = $core->toSlug($tag);
        if($page==1) return $pcms_url.'/'.$slug.".html";
		else return $pcms_url.'/'.$slug."/p".$page.".html";
	}
    function getCategory($news_id) {
        $res = $this->getOne($news_id);
        return $res['category_id'];
    }
    function getImage($news_id, $w, $h){
		$res = $this->getOne($news_id);
		$image = trim($res['image']);
        if(!$image) return 'https://pubokid.vn/thumb_x'.$w.'x'.$h.'/upload/nophoto.jpg';
        return 'https://pubokid.vn/'.$image.'" width="'.$w."\" height = \"".$h."\" style=\"height:".$h."px !important\"";
	}
    function getHtmlImage($news_id, $w,$h,$class='',$resize = false) {
        $res = $this->getOne($news_id);
		$image = trim($res['image']);
        //if($image) $link = MEDIA.'/thumb_x'.$w.'x'.$h.'/'.$image;
        if($image) {
            if(end(explode(".",$image)) == 'gif' || !$resize) $link = 'https://pubokid.vn/'.$image.'" width="'.$w."\" height = \"".$h."\"";
            else $link = 'https://pubokid.vn/'.$image;
        }
        else $link = 'https://pubokid.vn/upload/nophoto.jpg" width="'.$w."\" height = \"".$h."\"";        
        return '<a href="'.$this->getLink($news_id).'"><img alt="'.str_replace("\"","'",$res['title']).'" title="'.str_replace("\"","'",$res['title']).'" src="'.$link.'" class="'.$class.'"></a>';
    }
    function isNewsInCat($news_id, $category_id) {
        $one = $this->getOne($news_id);
        if($one['category_id']==$category_id) return true;
        $clsCategory = new Category();
        return $clsCategory->isCatInCat($one['category_id'], $category_id);
    }
    function getRegUser($news_id) {
        $one = $this->getOne($news_id);
        $clsUser = new User();
        return $clsUser->getUserName($one['user_id']);
    }
    function plusView($news_id) {
        $oneNews = $this->getOne($news_id);
        $views = (int)$oneNews['views']; $views++; $oneNews['views']=$views;
        $views_week = (int)$oneNews['views_week']; $views_week++; $oneNews['views_week']=$views_week;
        $views_month = (int)$oneNews['views_month']; $views_month++; $oneNews['views_month']=$views_month;
        $views_day = (int)$oneNews['views_day']; $views_day++; $oneNews['views_day']=$views_day;
        if($views_day%5==0) $this->updateOne($news_id, array('views'=>$views,'views_week'=>$views_week,'views_month'=>$views_month,'views_day'=>$views_day));
        else $this->setCache($this->getKey($news_id), $oneNews);
        return true;
    }
    function getLinkKeywords($news_id) {
        //include("lib/class_autokeyword.php");
        //$pn = new proper_nouns();

        $oneNews = $this->getOne($news_id);
        //$arr = $pn->get($oneNews['title']." ".$oneNews['intro']);
        $keyword = trim(html_entity_decode($oneNews['tags']));
        $all=explode(',', $keyword);
        $res = "";
        $core = new Core;        
        if(is_array($all)) foreach($all as $key=>$one) if($one!='') {
       	    $res.='<a href="'.PCMS_URL.'/'.$core->toSlug($one).'.html">'.trim($one).'</a>';
        }
        //if(is_array($arr)) foreach($arr as $k=>$o) {
//            $res.='<a href="'.PCMS_URL.'/'.$core->toSlug($o).'.html">'.trim($o).'</a>';
//        }
        return $res;
    }
    function getRelatedNews($news_id) {
        $res = $this->getOne($news_id);
        if(!$res['news_related'] || trim($res['news_related'])=='') return false;
        return explode('|', $res['news_related']);
    }
    function getCons($category_id=0, $notcat='') {
        $cons = "is_trash=0 and is_draft=0 and is_push=1 and status = 3";
        if($notcat) $cons .= ' and category_id not in ('.$notcat.')';
        if($category_id>0) {
            $clsCategory = new Category();
            $allCat = $clsCategory->getChild($category_id); $allCat[] = $category_id;
            if($allCat) foreach($allCat as $oneCat) {
                if(!$cons_related) $cons_related = "category_related like '%|".$oneCat."|%'";
                else  $cons_related .= " or category_related like '%|".$oneCat."|%'";
            }
            $cons.=" and (category_id in (".implode(',', $allCat).") OR ".$cons_related.')';

        }
        return $cons;
    }
    function getAllKeyCache() {
        $arr[] = 'IS_PICK';
        $arr[] = 'IS_HOT';
        $arr[] = 'IS_TOP';
        $arr[] = 'IS_FEATURED';
        $arr[] = 'IS_BENPHAI';
        $arr[] = 'KEYARR_VIDEO';

        return $arr;
    }
    function delAllCache($news_id){
        $one = $this->getOne($news_id);
        $clsCategory = new Category;
        $arr = $this->getAllKeyCache();

        foreach($arr as $key) {
            $this->deleteArrKey($key);
        }
        
        $this->deleteArrKey('KEYARR_'.$one['category_id']);
        if($clsCategory->getParentID($one['category_id'])) $this->deleteArrKey('KEYARR_'.$clsCategory->getParentID($one['category_id']));
    }
    function getAllShowDate() {
        $key = 'default_news_show_date';
        return $this->getCache($key);
    }
    function setAllShowDate($array) {
        $key = 'default_news_show_date';
        $this->setCache($key, $array,720000);
    }
    function setShowDate($show_date,$news_id) {
        $all = $this->getAllShowDate();
//        if($_SESSION['USER'] == 'vanduc0409') {
//            print_r($show_date);
//            die();
//        }
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
            echo 'HENGIO_'.$key.' '.date('d/m/Y H:i:s', $val).'<br />';
            if($val<$now) {
                $active = true;
                $news_id = $key;
                unset($all[$key]);
                $this->delAllCache($news_id);
                print_r($news_id);
                echo date('d/m/Y H:i:s', $val).'<br />';
            }
        }
        if($active) {
            echo 'da acti';
            print_r($all);
            $this->setAllShowDate($all);
            $this->deleteArrKey();
        }
    }
    function getAllImage($news_id) {
        $key = $this->getKey($one);
        $one = $this->getOne($news_id);
        if($one['getAllImage']) {
            if($one['getAllImage']=='false') return false;
            else return $one['getAllImage'];
        } else {
            $result = array();
            $doc = new DOMDocument();
            $doc->loadHTML('<meta http-equiv="content-type" content="text/html; charset=utf-8">'.$one['content']);
            $imageTags = $doc->getElementsByTagName('img');
            foreach($imageTags as $key=>$tag) {
                $result[$key]['src'] = $tag->getAttribute('src');
                $result[$key]['title'] = $tag->getAttribute('title');
                $result[$key]['alt'] = $tag->getAttribute('alt');
            }
            if(!$result) $result = 'false';
            $one['getAllImage'] = $result;
            $this->setCache($key, $one);
            return $result;
        }
	}
    
    function delAllKeyCache(){
        $arr = $this->getAllKeyCache();
        foreach($arr as $key) {
            //print_r($key);
            $this->deleteArrKey($key);
        }
    }
    
    function checkSeo($array) {
        $error = '';
        if($array['meta_title'] && mb_strlen($array['meta_title'],'utf8') > 65 && mb_strlen($array['title'],'utf8') > 65) $error .= 'Title dá© hon 65 kí¥ƒí´¿';
        if($array['meta_description'] && mb_strlen($array['meta_description'],'utf8') > 160 && mb_strlen($array['intro'],'utf8') > 160) $error .= ', Mí¡ƒí´¿ dá© hon 160 kí¥ƒí´¿';
        if($array['meta_title'] == '' && mb_strlen($array['title'],'utf8') > 65) $error .= 'Title dá© hon 65 kí¥ƒí´¿';
        if($array['meta_description'] == '' && mb_strlen($array['intro'],'utf8') > 160) $error .= ', Mí¡ƒí´¿ dá© hon 160 kí¥ƒí´¿';
        
        $dom = new DOMDocument();
        @$dom->loadHTML($array['content']);
        
        $imgs= $dom->getElementsByTagName('img');
        
        $count = 0;
        $countimg = 0;      
        foreach ($imgs as $img) {
            $countimg++;
            if ($img->hasAttribute('alt')) if($img->getAttribute('alt')) $count++;
        }
        
        if($count!=$countimg) $error .= ', '.($countimg-$count).' ?nh chua cí­‚í±¬t';
        
        return $error;
    }
    function updateSrcImage($content,$file_name) {
        $doc = new DOMDocument();
        $doc->loadHTML('<meta http-equiv="content-type" content="text/html; charset=utf-8">'.$content);
        $imageTags = $doc->getElementsByTagName('img');  
        $time = time(); 
        foreach($imageTags as $key=>$tag) {
            $src = $tag->getAttribute('src');

            if(!strpos($src, 'pubokid.vn')){
                $directory = "files/";
                $directory .= $_SESSION['USER'].'/'; if(!is_dir($directory)) {$old = umask(0); mkdir($directory, "0777");chmod($directory,0777); umask($old);}
                $directory .= date('Y', $time).'/'; if(!is_dir($directory)) {$old = umask(0); mkdir($directory, "0777");chmod($directory,0777); umask($old);}
                $directory .= date('m', $time).'/'; if(!is_dir($directory)) {$old = umask(0); mkdir($directory, "0777");chmod($directory,0777); umask($old);}
                $directory .= date('d', $time).'/'; if(!is_dir($directory)) {$old = umask(0); mkdir($directory, "0777");chmod($directory,0777); umask($old);}
                $directory .= $file_name.$key.'.jpg';
            
     			$myfile=fopen($directory,'r+');
    			flock($myfile,LOCK_SH);
    			file_put_contents($directory,file_get_contents($src));
    			fclose($myfile);
                
                $content = str_replace($src,'https://pubokid.vn/'.$directory,$content);
                
            }
        }
        return $content;
        
    }
    function fixed_content($content) {
    }
}
?>