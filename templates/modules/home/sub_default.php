<?php
/**
*  Defautl action
*  @author		: Ong Thế Thành	
*  @date		: 2012/01/23	
*  @version		: 0.0.1
*/
function default_default(){
	global $assign_list, $head_page, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core,$ads, $msg;
	#
    /*=============Content Page==================*/
    #

    $clsNews = new News(); $assign_list['clsNews'] = $clsNews;
    $clsCategory = new Category(); $assign_list['clsCategory'] = $clsCategory;
    $clsVideo = new Video(); $assign_list['clsVideo'] = $clsVideo;
    #
    $cons = $clsNews->getCons();
    $list_pick = $clsNews->getAll($cons.' and is_pick=1 order by pick_date desc limit 3',true,'IS_PICK'); $assign_list['list_pick']= $list_pick;
    $list_hot = $clsNews->getAll($cons.' and is_hot=1 order by hot_date desc limit 3',true,'IS_HOT'); $assign_list['list_hot']= $list_hot;
    $clsBanner = new Banner; $listBanner = $clsBanner->getAll("is_trash = 0 and is_push = 1 order by push_date desc limit 3");
    $assign_list['listBanner'] = $listBanner;$assign_list['clsBanner'] = $clsBanner;
    #
	/*=============Title & Description Page==================*/
	$title_page = PAGE_TITLE;
	$description_page = META_DES;
	$keyword_page = META_KEY;
}
function default_vote(){
	global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg, $ads;
	#
    /*=============Content Page==================*/
    #
    $clsVotes = new Votes;
    $clsAVote = new AVote;
    $vote_id = $_GET['vote_id'];
    $ans_id = $_GET['ans_id'];

    $number_vote = $clsAns->getNumber($ans_id) + 1;
    $total_vote = $clsAns->getTotal($vote_id);

    $clsAns->updateOne($ans_id,'number='.$number_vote);
    
    $html = '<div style="padding-top:10px">';
    $listAns = $clsAns->getAll('vote_id='.$vote_id);
    if($listAns) foreach($listAns as $one) {
        $vote = $clsAns->getNumber($one)+1;
        $total = $total_vote+1;
       $html .= '<div style="float:left; width:80px; margin-top: 4px;" >'.$clsAns->getTitle($one).' : </div><img src="poll.gif" width="'.(round(100*($vote/$total))*1.5).'" height="18"> ' ;
       $html .= $vote.' lựa chọn<br>';  
    }
    $html .= '</div>';
    echo $html;
    die();
}
function default_addvote(){
    global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg, $ads;
	#
    /*=============Content Page==================*/
    #
    $clsAVote = new AVote;$clsIPVote = new IPVote;
    if($_GET['avote_id']) {
        $avote_id = $_GET['avote_id'];
        //$ip=$core->getIP();
        $check=$_COOKIE['VOTETHETHAO_'.$_GET['vote_id']];
        if(!$check) {
            $clsAVote->getQuery('UPDATE default_avote SET amount = amount + 1 WHERE avote_id = '.$avote_id);
            //$clsIPVote->insertOne(array("vote_id"=>$_GET['vote_id'],"ip"=>$ip));
            $clsAVote->deleteArrKey();
            setcookie('VOTETHETHAO_'.$_GET['vote_id'],1,time()+31536000);
            die('Bình chọn thành công');
        } else {
            die('Bạn đã bình chọn 1 lần');
        }
    } else {
        die('Có lỗi trong quá trình bình chọn');
    }
}
function default_showvote(){
    global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg, $ads;
	#
    /*=============Content Page==================*/
    #
    $clsAVote = new AVote;$clsIPVote = new IPVote;$clsVote = new Vote;
    if($_GET['vote_id']) {
        $listAns = $clsAVote->getAll('vote_id='.$_GET['vote_id']);
        $html = '';
        $total_vote = $clsAVote->getTotal($_GET['vote_id']);
        
        if($listAns) foreach($listAns as $one) { $one = $clsAVote->getOne($one);
            $percent = round($one['amount']*100/$total_vote);
            $html .= '<li id="rs_39452">
        			<div class="info_result">
        			  <div class="rsV_left">'.$one['title'].'</div>
        			  <div class="rsV_right">
        				<label>'.$one['amount'].'</label>
        				phiếu</div>
        			  <div class="scroll_color"> <span percent="'.$percent.'" class="bg_center_scroll" style="width:'.$percent.'%;"> &nbsp;
        				<label class="txt_number_ketqua">'.$percent.'%</label>
        				</span> </div>
        			</div>
        		  </li>';
        }
        echo $html;die();
    } else {
        die('Có lỗi trong quá trình xem kết quả');
    }
}
function default_js_plus_views() {
    header('Content-type: text/javascript');
    $post_id = (int)$_GET['p'];
    if($post_id) {
        $clsPost = new News();
        //$clsPost->plusView($post_id);
    }
    die();
}
function default_js_plus_views_video() {
    header('Content-type: text/javascript');
    $post_id = (int)$_GET['p'];
    if($post_id) {
        $clsPost = new Video();
        $clsPost->plusView($post_id);
    }
    die();
}
function default_feed(){
	global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg, $ads, $head_page;
	#
    /*=============Content Page==================*/
    #
    $clsCategory = new Category(); $assign_list['clsCategory'] = $clsCategory;
    $list_cat_home = $clsCategory->getAll('is_trash=0 and parent_id=0 order by home_display');
    $assign_list['list_cat_home'] = $list_cat_home;
    #
	/*=============Title & Description Page==================*/
	$title_page = 'Feed - RSS';
	$description_page = META_DES;
	$keyword_page = META_KEY;
}
function default_unknow(){
	global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg, $ads, $head_page;
	#
    /*=============Content Page==================*/
    #

    $clsNews = new News(); $assign_list['clsNews'] = $clsNews;
    $cons = $clsNews->getCons();
    $list_new = $clsNews->getAll($cons.' order by order_new, push_date desc limit 9'); $assign_list['list_new'] = $list_new;
    #
	/*=============Title & Description Page==================*/
	$title_page = '404';
	$description_page = META_DES;
	$keyword_page = META_KEY;
    $head_page = '<link rel="canonical" href="http://tinnuocmy.com" />';
}
function default_rss() {
   global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg;
#
   /*=============Content Page==================*/
   #
   header('Content-Type: text/xml; charset=UTF-8');
   $clsCategory = new Category();
   $clsNews = new News();
   $cat_id=0;
   $oneCat['title'] = 'Vietq';
   $oneCat['description'] = 'Vietq';
   if(isset($_GET['slug']) && $_GET['slug']!='') {
        $slug = $_GET['slug'];
        $cat_id = $clsCategory->slugToID($slug);
        $oneCat = $clsCategory->getOne($cat_id);
   }
   if(isset($_GET['is_hot']) && $_GET['is_hot']='1') {
        $all = $clsNews->getAll($clsNews->getCons()." and is_hot=1 order by order_hot, push_date desc limit 1");
        $all_pick = $clsNews->getAll($clsNews->getCons()." and is_pick=1 order by order_pick, push_date desc limit 3");
   }else{
          $all = $clsNews->getAll($clsNews->getCons($cat_id)." order by news_id desc limit 20");
   }
   #
   $now = date("D, d M Y H:i:s T");
   
   $output = '<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0"
    xmlns:content="http://purl.org/rss/1.0/modules/content/"
    xmlns:wfw="http://wellformedweb.org/CommentAPI/"
    xmlns:dc="http://purl.org/dc/elements/1.1/"
    xmlns:atom="http://www.w3.org/2005/Atom"
    xmlns:sy="http://purl.org/rss/1.0/modules/syndication/">';
    
   $output .= '<channel>
                <title>'.$oneCat['title'].'</title>
                <atom:link href="'.$clsCategory->getLinkRSS($cat_id).'" rel="self" type="application/rss+xml"/>
                <link>http://tinnuocmy.com/</link>
                <description>'.$oneCat['description'].'</description>
                <pubDate>'.$now.'</pubDate>
                <generator>http://tinnuocmy.com/</generator>
                <language>vi</language>
                <sy:updatePeriod>hourly</sy:updatePeriod>
                <sy:updateFrequency>1</sy:updateFrequency>';
               
   if($all) foreach ($all as $line)
   {
       $line=$clsNews->getOne($line);
       $output .= '<item>
                    <title>
                    <![CDATA[ '.$line['title'].' ]]>
                    </title>
                    <link>'.$clsNews->getLink($line['news_id']).'</link>
                    <image>'.PCMS_URL.'/'.$line['image'].'</image>
                    <comments></comments>
                    <pubDate>'.date('D, d M Y H:i:s', strtotime($line['reg_date'])).'</pubDate>
                    <dc:creator>tin</dc:creator>
                    <guid isPermaLink="false">'.$clsNews->getLink($line['news_id']).'</guid>
                    <description>
                    <![CDATA[
                    <img src="'.$clsNews->getImage($line['news_id'],404,260).'" width="100px" height="64px"/><br /> '.$line['intro'].'
                    ]]>
                    </description>                    
                    <wfw:commentRss></wfw:commentRss>
                    </item>';
   }
   if($all_pick) foreach ($all_pick as $line)
   {
       $line=$clsNews->getOne($line);
       $output .= '<item>
                    <title>
                    <![CDATA[ '.$line['title'].' ]]>
                    </title>
                    <link>'.$clsNews->getLink($line['news_id']).'</link>
                    <image>'.$clsNews->getImage($line['news_id'],404,260).'</image>
                    <comments></comments>
                    <pubDate>'.date('D, d M Y H:i:s', strtotime($line['reg_date'])).'</pubDate>
                    <dc:creator>tin</dc:creator>
                    <guid isPermaLink="false">'.$clsNews->getLink($line['news_id']).'</guid>
                    <description>
                    <![CDATA[
                    <img src="'.$clsNews->getImage($line['news_id'],404,260).'" width="100px" height="64px"/><br /> '.$line['intro'].'
                    ]]>
                    </description>                    
                    <wfw:commentRss></wfw:commentRss>
                    </item>';
   }
   $output .= "</channel></rss>";
   echo $output;
   die();
}
function default_loadRelationNews() {
    global $core;
    #
    $q = $core->toSlug($_GET['q']);
    $limit = $_GET['limit'];
    $offset = $_GET['offset'];
    $clsNews = new News();
    $all = $clsNews->getAll("is_trash=0 and is_draft=0 and is_push=1 and image != '' and slug like '%".$q."%' order by reg_date desc limit ".$offset.",".$limit);
    if($all) foreach($all as $one) {
        $one = $clsNews->getOne($one);
        echo '<a href="#"  onclick="javascript:timkiem1('.$one['news_id'].');">'.$one['title'].'</a><br/>';
    }
    echo '<input type="button" value="Xem tiếp" onclick="javascript:search();" />';
    die();
}

function default_pluginSearch() {
    $clsNews = new News();
    $oneNews = $clsNews->getOne(intval($_GET['id']));
    echo '<script type="text/javascript">$("li").click(function () {if(confirm("Bạn có muốn xóa bài viết ?")){$(this).remove();i=i-1;return false;}else return false;});$(".suggest-link").click(function () {if(confirm("B?n có mu?n xóa bài vi?t?")){$(this).remove();i=i-1;return false;}else return false;});$(".hor-content").click(function () {if(confirm("B?n có mu?n xóa bài vi?t ?")){$(this).remove();i=i-1;return false;}else return false;});$(".box-news-suggest-content").click(function () {if(confirm("B?n có mu?n xóa bài vi?t ?")){$(this).remove();i=i-1;return false;}else return false;});$(".box-news-suggest-content suggest-hor").click(function () {if(confirm("B?n có mu?n xóa bài vi?t ?")){$(this).remove();i=i-1;return false;}else return false;});</script>
    <div class="box-news-suggest-content suggest-hor" id="'.$oneNews['news_id'].'" >                
    <img src="'.$clsNews->getImage($oneNews['news_id'], 140, 88).'" class="img-130x85" /><a href="'.$clsNews->getLink($oneNews['news_id']).'" class="box-news-suggest-a" title="'.$oneNews['title'].'">'.$oneNews['title'].'</a>
    </div>';
  die();
}
function default_loadRelationVote() {
    global $core;
    #
    $q = $core->toSlug($_GET['q']);
    $limit = $_GET['limit'];
    $offset = $_GET['offset'];
    $clsVote = new Vote();
    $all = $clsVote->getAll("is_trash=0 and is_push=1 and slug like '%".$q."%' order by push_date desc limit ".$offset.",".$limit);
    if($all) foreach($all as $one) {
        $one = $clsVote->getOne($one);
        echo '<a href="#"  onclick="javascript:timkiem('.$one['vote_id'].');">'.$one['title'].'</a><br/>';
    }
    echo '<input type="button" value="Xem tiếp" onclick="javascript:search();" />';
    die();
}
function default_insertBlockVote() {
    $clsVote = new Vote();$clsAvote = new AVote;
    $vote = $clsVote->getOne(intval($_GET['id']));
    $listAvote = $clsVote->getAnswer($_GET['id']);
    
    if($_GET['float_'] == 'center') {
        $style = 'margin: auto';
    } else {
        $style = $_GET['float_'];
    }
    
    $html = '<div id="box_vote_'.$_GET['id'].'" class="box_vote" data="'.$_GET['id'].'" style="width: 50%;border: 1px solid #D44044;'.$style.'">
            <div class="head_right pkg"><a class="title_head_right">Bình chọn</a></div>
            <div class="pad10">
            <div class="f16 font_segob">'.$vote['title'].'</div>
            <ul class="list_vote">';

    if($listAvote) foreach($listAvote as $key=>$one) { $one = $clsAvote->getOne($one);
        $html .= '<li><input type="radio" name="vradios"  value="'.$one['avote_id'].'" vote_id="'.$one['avote_id'].'"';
        $html .= '<input type="radio"  id="radio'.$key.'" value="'.$one['avote_id'].'" name="radios"';
        if($key==0) $html .= 'checked="checked"';
        $html .= ' class="slt_vote"><label>'.$clsAvote->getTitle($one['avote_id']).'</label></li>';
    }

    $html .= '</ul><a href="javascript:void(0)" class="btn_vote">Biểu quyết</a><a href="javascript:void(0)" class="btn_result">Xem kết quả</a>
        </div>
            <div class="BoxOverlay" style=""></div>
            <div class="SexyAlertBox-Box" style="display: none; position: fixed; top: 65px; left: 30%; z-index: 65557; width: 40%;">
                <div class="login-form" id="login-vne7">
                  <div class="ttOline">Kết quả bình chọn</div>
                  <div class="rs_vote">
                    <p class="question_vote">'.$vote['title'].'</p>
                  </div> 
                  <div class="complete-form"  style="overflow: scroll;">
                		<div class="list_rs width_common">
                			<ul class="scroll_thongke">
                           
                			</ul>
                		</div>
                  </div>
                  <div class="close-lb">x</div>
                  <div class="clear"></div>
                </div>
            </div>
        </div>';        
        
    echo $html;
    
    die();
}

function default_loadRelationPhoto() {
    global $core;
    #
    $q = $core->toSlug($_GET['q']);
    $limit = $_GET['limit'];
    $offset = $_GET['offset'];
    $clsNews = new News();
    $all = $clsNews->getAll("is_trash=0 and is_push=1 and category_id = 45 and slug like '%".$q."%' order by push_date desc limit ".$offset.",".$limit);
    if($all) foreach($all as $one) {
        $one = $clsNews->getOne($one);
        echo '<a href="#"  onclick="javascript:timkiemanh('.$one['news_id'].');">'.$one['title'].'</a><br/>';
    }
    echo '<input type="button" value="Xem tiếp" onclick="javascript:search();" />';
    die();
}
function default_pluginSearchPhoto() {
    $clsNews = new News();
    $oneNews = $clsNews->getOne(intval($_GET['id']));
    echo '<li><a class="thumbblock thumb215x130 img-130x85" href="'.$clsNews->getLink($oneNews['news_id']).'"><img src="'.$clsNews->getImage($oneNews['news_id'],140,88).'" /></a>
         <a href="'.$clsNews->getLink($oneNews['news_id']).'" class="title_slide_video">'.$oneNews['title'].'</a></li>';
    
    die();
}
function default_checkSlug() {
    global $core;
    $clsNews = new News();
    $title = trim(strval($_POST['title']));
    $slug = $core->toSlug($title);
    $all = $clsNews->getAll("is_trash=0 and (slug='".addslashes($slug)."' or title='".addslashes($title)."') limit 1");
    if($all) die('0');
    else die('1');
}
function default_checkShowDate(){
    $clsNews = new News();
    $clsNews->checkShowDate();
    die('Done');
}
function default_sitemap() {
    global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg, $ads;
	#
    /*=============Content Page==================*/
    #
    $end_time = strtotime("2017-01-01");  
            
    $day   = date("d");
    $month = date("m");
    $year  = date("Y");
    
    $current_month = strtotime("$year-$month-01");
    
    $current_date = date("Y-m-d");
    
    ob_clean();
    header("Content-type: text/xml; charset=utf-8");
    echo '<sitemapindex xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/siteindex.xsd">
    <sitemap>
<loc>http://tinnuocmy.com/sitemaps/categories.xml</loc>
<lastmod>'.$current_date.'</lastmod>
</sitemap>';
    $run=0;
    while($current_month>=$end_time){
        $m = (int)date("m",$current_month);
        $y = (int)date("Y",$current_month);
        if($run==0)
            $t = $day ;
        else
            $t = date("t",$current_month);
        echo '<sitemap>
                <loc>http://tinnuocmy.com/sitemaps/news-'.$y.'-'.$m.'.xml</loc>
                <lastmod>'.$y.'-'.$m.'-'.$t.'</lastmod>
                </sitemap>';    
        
        $current_month = strtotime("-1 month",$current_month);    
        $run++;
    }
    echo '</sitemapindex>';
    die();  

}
function default_sitemap_categories() {
    $clsCategory = new Category;
    $current_day = date("c");
    $menu = $clsCategory->getAll('is_trash=0 and parent_id=0'); 
    
    ob_clean();
    header("Content-type: text/xml; charset=utf-8"); 
    echo '<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
        <url>
        <loc>http://tinnuocmy.com</loc>
        <lastmod>'.$current_day.'</lastmod>
        <changefreq>always</changefreq>
        <priority>1.0</priority>
        </url>
        ';
    foreach($menu as $category){
        $link = $clsCategory->getLink($category);
        echo '<url>
            <loc>
            '.$link.'
            </loc>
            <lastmod>'.$current_day.'</lastmod>
            <changefreq>always</changefreq>
            <priority>0.9</priority>
        </url>';
    }
    echo '</urlset>';
    die();
}
function default_sitemap_month() {
    set_time_limit(0); 
    $end_time = strtotime("2013-01-01"); 
    
    $current_y = (int)date("Y");
    $current_m = (int)date("m");
    $end_y     = (int)date("Y",$end_time);
    
    $m = isset($_GET["y"]) ? $_GET["y"] : 0; if($m < 10) $m = '0'.$m;
    $y = isset($_GET["m"]) ? $_GET["m"]  : 0;
    
    if($y > $current_y || $y < $end_y)
        die("not permission");
    
    if($m < 1 || $m > 12)
        die("not permssion");
    
    $current_day = date("c");
    
    $start = strtotime($y."-".$m."-"."01 00:00:00");
    $start_time = strtotime("$y-$m-01 00:00:00");
    $end_time   = date("Y-m-d 00:00:00",strtotime("next month",$start_time));
    

    $clsNews = new News;
    $cons = $clsNews->getCons();
    $listNews = $clsNews->getAll($cons." AND push_date < '".strtotime($end_time)."' AND push_date > '".$start."' order by push_date desc limit 1000");     
    
    ob_clean();
    header("Content-type: text/xml; charset=utf-8"); 
    $result = '<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
        <url>
        <loc>http://tinnuocmy.com</loc>
        <lastmod>'.$current_day.'</lastmod>
        <changefreq>always</changefreq>
        <priority>1.0</priority>
        </url>
        ';
    foreach($listNews as $one){ $one = $clsNews->getOne($one);
        $result .= '<url>
            <loc>
            '.$clsNews->getLink($one['news_id']).'
            </loc>
            <lastmod>'.date("Y-m-d",$one['reg_date']).'</lastmod>
            <changefreq>daily</changefreq>
            <priority>0.7</priority>
        </url>';
    }
    $result .= '</urlset>';
    
    echo $result;
    die();
}
function default_sitemap_news() {
    global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg, $ads;
	#
    /*=============Content Page==================*/
    #
    
    $clsNews = new News;
    $cons = $clsNews->getCons();
    $listNews = $clsNews->getAll($cons." AND TIMESTAMPDIFF(SECOND,push_date,now()) <= 132800 order by push_date desc"); 
    header('Content-Type: text/xml');
    $sitemap = '
    <urlset
      xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
      xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
      xmlns:news="http://www.google.com/schemas/sitemap-news/0.9"
      xsi:schemaLocation="
            http://www.sitemaps.org/schemas/sitemap/0.9
            http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">';
    
     foreach($listNews as $one){ $one = $clsNews->getOne($one);
        $sitemap .= "
            <url>
               <loc>".$clsNews->getLink($one['news_id'])."</loc>
               <lastmod>".date("Y-m-d",strtotime($one['push_date'])).'T'.date("H:i:s+07:00",strtotime($one['push_date']))."</lastmod>
               <news:news>
                 <news:publication>               
                   <news:name>Thể thao TV</news:name>
                   <news:language>vi</news:language>
                 </news:publication>
                 <news:publication_date>".date("Y-m-d",strtotime($one['push_date'])).'T'.date("H:i:s+07:00",strtotime($one['push_date']))."</news:publication_date>
                 <news:title>".str_replace("&","",$one['title'])."</news:title>
                 <news:keywords></news:keywords>
               </news:news>
          </url>";
      }
      $sitemap .= "</urlset>";
      echo $sitemap;
      die();
}
function default_updateTags() {
    global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg, $ads;
	#
    /*=============Content Page==================*/
    #
    $clsNews = new News;
    $clsTags = new Tags;
    
    $listNews = $clsNews->getAll('is_trash = 0 and news_id > 3999 and news_id < 9038 order by news_id asc');
    $i=0;
    foreach($listNews as $n) { $n = $clsNews->getOne($n);
        $tags = $n['tags'];
        if($tags) {
            $arrTags = explode(",",$tags);   
            $clsTags = new Tags;
            foreach($arrTags as $oneTag) {
                $tags_id = $clsTags->getAll('slug = "'.$core->toSlug($oneTag).'" limit 1');
                if(!$tags_id[0]) {
                    $clsTags->insertOne(array("title"=>$oneTag,"slug"=>$core->toSlug($oneTag)));
                }
            }
        }
        $i++;
    }
    
    echo $i;
    
    die();
    
}
function default_updateTags2() {
    global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg, $ads;
	#
    /*=============Content Page==================*/
    #
    $clsNews = new News;
    $clsTags = new Tags;
    
    $listNews = $clsNews->getAll('is_trash = 0 order by news_id asc');
    $i=0;
    foreach($listNews as $n) { $n = $clsNews->getOne($n);
        $tags = $n['tags'];
        $tags_new = ','.$tags.',';
        $clsNews->updateOne($n['news_id'],array("tags"=>$tags_new));
    }
    
    echo $i;
    
    die();
    
}
function default_googleBox(){
    global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg, $ads;
    # 
    $clsNews = new News(); $assign_list['clsNews'] = $clsNews;
    $clsSignature = new Signature;
    $cons = $clsNews->getCons();
    $listNews = $clsNews->getAll($cons.' and is_pick=1 order by pick_date desc limit 5',true,'IS_PICK',500);

    header("Content-type: text/xml");
    echo'<?xml version="1.0" encoding="UTF-8" ?>
        <rss version="2.0" xmlns:dc="http://purl.org/dc/elements/1.1/">
        <channel>
         <link>'.PCMS_URL.'</link>
         <description>Tin tuc trong ngay - Đọc báo tin tức mới nhanh 24h tại báo Chất lượng Việt Nam Online Thethaoso360. Cập nhật tin mới thời sư, tin cảnh báo, tin pháp luật trong ngày.</description>
         <title>Tin tuc trong ngay, đọc báo tin mới Chất lượng Việt Nam Online</title>
         <image>
            <url>http://media.tinnuocmy.com/thumb_x126x40/upload/logo.png</url>
            <title>Tin tuc trong ngay, đọc báo tin mới Chất lượng Việt Nam Online</title>
            <link>'.PCMS_URL.'</link>
         </image>';
 
    if(!empty($listNews)) foreach($listNews as $oneNews) { $one = $clsNews->getOne($oneNews);
    echo '
        <item>
           <title>'.str_replace("&","",$one['title']).'</title>
           <link>'.$clsNews->getLink($one['news_id']).'</link>
           <description>'.str_replace("&","",$one['intro']).'</description>
            <dc:creator>'.ucwords($clsSignature->getTitle($one['signature_id'])).'</dc:creator>
            <pubDate>'.date('Y-m-d\TH:i:s\z',strtotime($one['push_date'])).'</pubDate>
         </item>';
    }
    echo '</channel>
</rss>';
    die();
}

function default_sitemap_tags() {
    global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg, $ads;
    # 
    $clsNews = new News(); $assign_list['clsNews'] = $clsNews;
    $cons = $clsNews->getCons();
    $listNews = $clsNews->getAll($cons.' order by push_date desc limit 5',true,'',500);
    
    $arr_tags = array();
    $one0 = $clsNews->getOne($listNews[0]);$arr_tags1 = explode(",",trim($one0['tags'],","));
    $one1 = $clsNews->getOne($listNews[1]);$arr_tags2 = explode(",",trim($one1['tags'],","));
    $one2 = $clsNews->getOne($listNews[2]);$arr_tags3 = explode(",",trim($one2['tags'],","));
    $one3 = $clsNews->getOne($listNews[3]);$arr_tags4 = explode(",",trim($one3['tags'],","));
    $one4 = $clsNews->getOne($listNews[4]);$arr_tags5 = explode(",",trim($one4['tags'],","));
    

    $rss = '<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
    foreach($arr_tags0 as $one) { $link_tag = $clsNews->getLinkTag($one);
        $rss .= '<url>
                         <loc>'.$link_tag.'</loc>
                         <lastmod>'.date("Y-m-d\TH:i:s.000\Z", $one0['push_date']).'</lastmod>
                         <changefreq>daily</changefreq>
                         <priority>0.5</priority>
                    </url>';
    }
    foreach($arr_tags1 as $one) { $link_tag = $clsNews->getLinkTag($one);
        $rss .= '<url>
                         <loc>'.$link_tag.'</loc>
                         <lastmod>'.date("Y-m-d\TH:i:s.000\Z", $one1['push_date']).'</lastmod>
                         <changefreq>daily</changefreq>
                         <priority>0.5</priority>
                    </url>';
    }
    foreach($arr_tags2 as $one) { $link_tag = $clsNews->getLinkTag($one);
        $rss .= '<url>
                         <loc>'.$link_tag.'</loc>
                         <lastmod>'.date("Y-m-d\TH:i:s.000\Z", $one2['push_date']).'</lastmod>
                         <changefreq>daily</changefreq>
                         <priority>0.5</priority>
                    </url>';
    }
    foreach($arr_tags3 as $one) { $link_tag = $clsNews->getLinkTag($one);
        $rss .= '<url>
                         <loc>'.$link_tag.'</loc>
                         <lastmod>'.date("Y-m-d\TH:i:s.000\Z", $one3['push_date']).'</lastmod>
                         <changefreq>daily</changefreq>
                         <priority>0.5</priority>
                    </url>';
    }
    foreach($arr_tags4 as $one) { $link_tag = $clsNews->getLinkTag($one);
        $rss .= '<url>
                         <loc>'.$link_tag.'</loc>
                         <lastmod>'.date("Y-m-d\TH:i:s.000\Z", $one4['push_date']).'</lastmod>
                         <changefreq>daily</changefreq>
                         <priority>0.5</priority>
                    </url>';
    }
    $rss .= '</urlset>'; 
    header("Content-type: text/xml");
    echo($rss);exit();
    
}
require("lib/simple_html_dom.php");
function default_rssia(){
    global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg, $ads;
    # 
    $clsNews = new News(); $assign_list['clsNews'] = $clsNews;
    $clsCategory = new Category;
    $cons = $clsNews->getCons();
    $listNews = $clsNews->getAll('is_trash=0 and is_draft = 0 and status=3 and is_push=1 order by push_date desc limit 100',true,'',500);
    
    $one0 = $clsNews->getOne($listNews[0]);
    
    $rss = '<rss xmlns:content="http://purl.org/rss/1.0/modules/content/" version="2.0"><channel>';
    $rss .= '<title></title>
                <link>tinnuocmy.com</link>
                <description>
                Tin tức nước Mỹ - Đọc báo người Việt tại Mỹ 24h mới nhất
                </description>
                <language>vi-vn</language>
                <lastBuildDate>'.date("Y-m-d\TH:i:s.000\Z", $one0['push_date']).'</lastBuildDate>';
                
    $listHot2 = $clsNews->getAll($cons.' and is_hot = 1 order by push_date desc limit 3',true,'',500);
    $tintitle = '<figure class="op-interactive"><iframe>';
    $tintitle .= '<link href="http://tinnuocmy.com/templates/themes/css/fbcss.css?v=10" rel="stylesheet"/>
    <div class="morenews" id="relatednews"><ul>';
    foreach($listHot2 as $k=>$c) { $c = $clsNews->getOne($c);
        $tintitle .= '<li><a style="color:#005689" href="'.$clsNews->getLink($c['news_id']).'">'.$c['title'].'</a></li>';
    }
    $tintitle .= '</ul></div></iframe></figure>';




   
    foreach($listNews as $one) { $one = $clsNews->getOne($one);
        
        $content = preg_replace('/\s\s+/', '', trim($one['content']));
    $html_dom = str_get_html($content); 
    foreach($html_dom->find('input') as $e) { $e->outertext="";}
    foreach($html_dom->find('h1, h2, h3, h4') as $e) { $e->style = "font-weight:bold"; $e->tag="p";}
    foreach($html_dom->find('*[style]') as $e) {
        $style=strtolower(str_replace(" ", "", $e->style));
        if(stripos($style, "text-align:center")) {
            $e->style = "text-align:center";
            $e->align = "center";
        } elseif(stripos($style, "text-align:right")) {
            $e->style = "text-align:right";
            $e->align = "right";
        } else $e->style = '';
    }
    foreach($html_dom->find('img') as $e) { if(stripos($e->outertext, "<figure>")<0) { $e->outertext="<figure>".$e->outertext."</figure>";}}
    foreach($html_dom->find('*[align=center]') as $e) { $e->style='text-align:center';}
    foreach($html_dom->find('*[ALIGN=CENTER]') as $e) { $e->style='text-align:center';}
    foreach($html_dom->find('*[align=right]') as $e) { $e->style='text-align:right';}
    foreach($html_dom->find('*[ALIGN=RIGHT]') as $e) { $e->style='text-align:right';}
    foreach($html_dom->find('*[id]') as $e) {$e->id = "";}
    foreach($html_dom->find('*[class]') as $e) {$e->class = "";}
 
    $content_html = $html_dom->outertext;
    $content_html = str_replace("<p>&nbsp;</p>", "", $content_html);
    $content_html = str_replace(' style=""', "", $content_html);
    $content_html = str_replace(' id=""', "", $content_html);
    $content_html = str_replace(' class=""', "", $content_html);
    $content_html = str_replace("<p></p>", "", $content_html);
    $content_html = str_replace("<p></p>", "", $content_html);
    $content_html = str_replace("<p></p>", "", $content_html);
    $content_html = str_replace("<em><figure>", "<figure>", $content_html);
    $content_html = str_replace("</figure></em>", "</figure>", $content_html);
    $content_html = str_replace("<p><figure>", "<figure>", $content_html);
    $content_html = str_replace("<p><figure>", "<figure>", $content_html);
    $content_html = str_replace("</figure></p>", "</figure>", $content_html);
    $content_html = str_replace("</figure></p>", "</figure>", $content_html);
    $content_html = str_replace("<p> </p>", "", $content_html);
    $content_html = str_replace("<p><br></p>", "", $content_html);
    $content_html = str_replace('src="files/', 'src="http://media.tinnuocmy.com/files/', $content_html);
    
    
    $content = $content_html;
    $content = str_replace("<p><figure>", "<figure>", $content);
        $content = str_replace("</figure></p>", "</figure>", $content);
            $content = str_replace("<p><figure>", "<figure>", $content);
        $content = str_replace("</figure></p>", "</figure>", $content);
        $arr = explode("|",trim($one['news_related'], "|")); if($arr) { 
        $bvlienquan = '<ul class="op-related-articles" title="Bài viết liên quan">';
            foreach($arr as $k=>$c) { if($k<3){$c = $clsNews->getOne($c);
                $bvlienquan .= '<li><a href="'.$clsNews->getLink($c['news_id']).'">'.$c['title'].'</a></li>';
            }}
        $bvlienquan .= '</ul>';
        }
        
        //$listHot = $clsNews->getAll($cons.' and news_id not in ('.$one['news_id'].','.implode(",",$arr).') order by push_date desc limit 4',true,'',500);
        $listHot = '';
        $tinhot = '<figure class="op-interactive"><iframe>';
        $tinhot .= '<link href="http://tinnuocmy.com/templates/themes/css/fbcss.css?v35" rel="stylesheet"/><script src="http://tinnuocmy.com/templates/themes/js/jquery.min.js?v=1" type="text/javascript"></script><script src="http://tinnuocmy.com/templates/themes/js/jquery.bxslider.js?v=1" type="text/javascript"></script>
        <div class="box_left box_slidevideo">
                  <div class="head_left pkg">
                        <a class="bg_head_left fl" href="">Tin mới nhất</a>
                </div>
                  <div class="slidevideo">
                    <ul class="slide_video1">';
        foreach($listHot as $c) { $c = $clsNews->getOne($c);
            $tinhot .= '<li><a class="thumbblock thumb215x130" href="'.$clsNews->getLink($c['news_id']).'"><img src="'.$clsNews->getImage($c['news_id'],110,65).'" /></a><a href="'.$clsNews->getLink($c['news_id']).'" class="title_slide_video">'.$c['title'].'</a></li>';
        }
            $tinhot .= '</ul>
                  </div>
                  <script>
            		$(document).ready(function(){
            				$(".slide_video1").bxSlider({
            				  minSlides:2,
            				  maxSlides: 2,
            				  slideWidth: 400,
            				  slideMargin: 20,
            				  moveSlides:1,
            				  pager:true,
                              auto:true,
            				});
            		});
                </script> 
        </div>';
        $tinhot .= '</iframe></figure>';
        
        $html = '<![CDATA[
                <!doctype html>
                <html lang="vi">
                <head>
                    <meta charset="utf-8">
                    <link rel="stylesheet" title="default" href="#">
                    <link rel="canonical" href="'.$clsNews->getLink($one['news_id']).'" >
                    <meta property="fb:article_style" content="default">
                    <meta property="fb:pages" content="368520406593719" />
                    <meta property="fb:use_automatic_ad_placement" content="enable=true ad_density=default">
                    <meta property="fb:likes_and_comments" content="enable">
                </head>
                <body>
                    <article>
                    <header>
                    <figure class="op-ad">
                      <iframe width="300" height="250" style="border:0; margin:0;" src="https://www.facebook.com/adnw_request?placement=351935225207299_351935238540631&adtype=banner300x250"></iframe>
                    </figure>
                    <figure data-feedback="fb:likes,fb:comments"><img src="'.$clsNews->getImage($one['news_id'],450,350).'" /></figure> 
                    <h1>'.$one['title'].'</h1>
                    <address>
                        <a>tinnuocmy.com</a>
                    </address>
                    <time class="op-published" dateTime="'.date("Y-m-d\TH:i:s.000\Z", $one['push_date']).'">'.date("Y-m-d\TH:i:s.000\Z", $one['push_date']).'</time>
                    <time class="op-modified" dateTime="'.date("Y-m-d\TH:i:s.000\Z", $one['last_edit']).'">'.date("Y-m-d\TH:i:s.000\Z", $one['last_edit']).'</time> 
                    <h3 class="op-kicker">'.$clsCategory->getTitle($one['category_id']).'</h3>
                    </header> 
                    '.$tintitle.'<h2>'.$one['intro'].'</h2>'.$content.$bvlienquan.'
                    <figure class="op-tracker"><iframe>'."<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-42654181-20', 'auto');
  ga('send', 'pageview');

</script>".'</iframe></figure>
                    <footer>
                    </footer>
                    </article>
                </body>
                </html>
                ]]>';
        $rss .= '<item>
                    <title>'.$one['title'].'</title>
                    <link>'.$clsNews->getLink($one['news_id']).'</link>
                    <guid>'.md5($clsNews->getLink($one['news_id'])).'</guid>
                    <pubDate>'.date("Y-m-d\TH:i:s.000\Z", $one['push_date']).'</pubDate>
                    <author>tinnuocmy.com</author>
                    <description>'.$one['intro'].'</description>
                    <content:encoded>'.$html.'</content:encoded>
        </item>';
    }
    
    $rss .= '</channel></rss>';
    header("Content-type: text/xml");
    echo $rss;    

    die();
}
function default_privacypolicy() {
    
}
?>