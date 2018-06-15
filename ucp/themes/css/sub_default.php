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
    /*=============Content Page==================*/
    #
    $clsNews = new News(); $assign_list['clsNews'] = $clsNews;
    $clsCategory = new Category(); $assign_list['clsCategory'] = $clsCategory;
    $clsVideo = new Video(); $assign_list['clsVideo'] = $clsVideo;
    #
    $cons = $clsNews->getCons();
    $list_pick = $clsNews->getAll($cons.' and is_pick=1 order by pick_date desc limit 3',false,'IS_PICK'); $assign_list['list_pick']= $list_pick;
    $list_top = $clsNews->getAll($cons.' and news_id != '.$list_pick[0].' and is_top=1 order by top_date desc limit 4',false,'IS_TOP'); $assign_list['list_top']= $list_top;
    $list_hot = $clsNews->getAll($cons.' and news_id != '.$list_pick[0].' and is_hot=1 order by hot_date desc limit 8',true,'IS_HOT'); $assign_list['list_hot']= $list_hot;
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
        $ip=$core->getIP();
        $check=$clsIPVote->getAll('ip = "'.$ip.'" and vote_id = '.$_GET['vote_id'].' limit 1');
        if(!$check) {
            $clsAVote->getQuery('UPDATE default_avote SET amount = amount + 1 WHERE avote_id = '.$avote_id);
            $clsIPVote->insertOne(array("vote_id"=>$_GET['vote_id'],"ip"=>$ip));
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
        $clsPost->plusView($post_id);
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
    $head_page = '<link rel="canonical" href="http://www.vietq.vn" />';
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
                <link>http://vietq.vn/</link>
                <description>'.$oneCat['description'].'</description>
                <pubDate>'.$now.'</pubDate>
                <generator>http://vietq.vn/</generator>
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
    $html = '<div id="box_vote_'.$one['avote_id'].'" class="box_vote" data="'.$one['avote_id'].'">
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

    $html .= '</ul><a href="javascript:void(0)" class="btn_vote" style="width: 50%;border: 1px solid #D44044;">Biểu quyết</a><a href="javascript:void(0)" class="btn_result">Xem kết quả</a>
        </div>
            <div class="BoxOverlay" style=""></div>
            <div class="SexyAlertBox-Box" style="display: none; position: fixed; top: 65px; left: 30%; z-index: 65557; width: 40%;">
                <div class="login-form" id="login-vne7">
                  <div class="ttOline">Kết quả bình chọn</div>
                  <div class="rs_vote">
                    <p class="question_vote">'.$vote['title'].'</p>
                  </div> 
                  <div class="complete-form">
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

function default_loadRelationVideo() {
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
function default_pluginSearchVideo() {
    $clsNews = new News();
    $oneNews = $clsNews->getOne(intval($_GET['id']));
    echo '<script type="text/javascript">$("li").click(function () {if(confirm("Bạn có muốn xóa bài viết ?")){$(this).remove();i=i-1;return false;}else return false;});$(".suggest-link").click(function () {if(confirm("B?n có mu?n xóa bài vi?t?")){$(this).remove();i=i-1;return false;}else return false;});$(".hor-content").click(function () {if(confirm("B?n có mu?n xóa bài vi?t ?")){$(this).remove();i=i-1;return false;}else return false;});$(".box-news-suggest-content").click(function () {if(confirm("B?n có mu?n xóa bài vi?t ?")){$(this).remove();i=i-1;return false;}else return false;});$(".box-news-suggest-content suggest-hor").click(function () {if(confirm("B?n có mu?n xóa bài vi?t ?")){$(this).remove();i=i-1;return false;}else return false;});</script>
    <div class="box-news-suggest-content suggest-hor" id="'.$oneNews['news_id'].'" >                
    <img src="'.$clsNews->getImage($oneNews['news_id'], 140, 88).'" class="img-130x85" /><a href="'.$clsNews->getLink($oneNews['news_id']).'" class="box-news-suggest-a" title="'.$oneNews['title'].'">'.$oneNews['title'].'</a>
    </div>';
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
    $end_time = strtotime("2013-01-01");  
            
    $day   = date("d");
    $month = date("m");
    $year  = date("Y");
    
    $current_month = strtotime("$year-$month-01");
    
    $current_date = date("Y-m-d");
    
    ob_clean();
    header("Content-type: text/xml; charset=utf-8");
    echo '<sitemapindex xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/siteindex.xsd">
    <sitemap>
<loc>http://thethao24.tv/sitemaps/categories.xml</loc>
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
                <loc>http://thethao24.tv/sitemaps/news-'.$y.'-'.$m.'.xml</loc>
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
    $menu = $clsCategory->getAll('is_trash=0 and parent_id=0 and home_display>0'); 
    ob_clean();
    header("Content-type: text/xml; charset=utf-8"); 
    echo '<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
        <url>
        <loc>http://thethao24.tv</loc>
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
    
    $m = isset($_GET["y"]) ? $_GET["y"] : 0;
    $y = isset($_GET["m"]) ? $_GET["m"]  : 0;

    if($y > $current_y || $y < $end_y)
        die("not permission");
    
    if($m < 1 || $m > 12)
        die("not permssion");
    
    $current_day = date("c");
    
    $start = $y."-".$m."-"."01 00:00:00";
    $start_time = strtotime("$y-$m-01 00:00:00");
    $end_time   = date("Y-m-d 00:00:00",strtotime("next month",$start_time));
    

    $clsNews = new News;
    $cons = $clsNews->getCons();
    $listNews = $clsNews->getAll($cons." AND push_date < '".$end_time."' AND push_date > '".$start."' order by push_date desc limit 1000");     
    
    ob_clean();
    header("Content-type: text/xml; charset=utf-8"); 
    $result = '<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
        <url>
        <loc>http://thethao24.tv</loc>
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
            <lastmod>'.date("Y-m-d",strtotime($one['reg_date'])).'</lastmod>
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
?>