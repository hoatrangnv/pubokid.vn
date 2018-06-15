<?php
ini_set("memory_limit", "5120M");
set_time_limit(0);
require("lib/simple_html_dom.php");


function default_getLink() {
    global $core;
    $clsNews = new News;
    
    $domain = 'http://www.nuocmy.info/'.$_GET['domain'];
    $category_id = $_GET['category_id'];
    $html = @file_get_html($domain);
    
    if(!$_GET['page']) {
        $link_last_page = $html->find('.last',0)->href;
        $last_page = end(explode("/",$link_last_page));
    } else {
        $last_page = $_GET['page'];
    }

    
    for($i = $last_page;$i > 0;$i--) {
        if($i == 1) $domain = 'http://www.nuocmy.info/'.$_GET['domain'];
        else  $domain = 'http://www.nuocmy.info/'.$_GET['domain'].'/page/'.$i;
        
        
        $html_ = @file_get_html($domain);
        
        
        foreach($html_->find('#main .post') as $artice) {
            $link = $artice->find('a',0)->href;
            $array['link'] = $link;
            $array['title'] = trim($artice->find('.post-title',0)->plaintext);
            $array['slug'] = $core->toSlug($array['title']);
            $check_trung = $clsNews->getAll('slug = "'.$array['slug'].'" limit 1');
            if(!$check_trung[0]) {
                $array['category_id'] = $category_id;
            
                print_r($array['link']);
                $clsNews->insertOne($array);
            } else {
                echo 'Đã có';
            }
        }
        echo '<br>'.$i.'<br>';
        ob_flush();
            flush();   
    }
}


function default_getDetail() {
    global $core;
    header('Content-Type: text/html; charset=utf-8');
    #
    /*=============Content Page==================*/
    #
    echo("<meta http-equiv='refresh' content='1'>");
    $clsNews = new News;
    $listNews=$clsNews->getAll('is_done = 0 and link != "" limit 1',false);

    
    foreach($listNews as $one) { $one = $clsNews->getOne($one);
        $link = $one['link'];
        $clsNews->updateOne($one['news_id'],array("is_done"=>1));
        $html = @file_get_html($link);
        
        if($html) {
        $array['title'] = trim($one['title']); 
        $slug = $one['slug'];

        if($html->find('meta[property="article:published_time"]')) { $reg_date = strtotime($html->find('meta[property="article:published_time"]',0)->getAttribute('content'));}
        else $reg_date = time();
        $array['reg_date'] = $reg_date;
        $array['push_date'] = $reg_date;
        $array['show_date'] = date("Y-m-d H:i:00",$reg_date);
        
        $array['intro'] =  trim($html->find(".entry-content p",0)->plaintext);        
        
        $content = str_replace($array['intro'],"",$html->find(".entry-content",0)->innertext);
        
        $html_ = str_get_html($content);
        if($html_->find('span[style=font-size:x-small]')) $content = str_replace($html_->find('span[style=font-size:x-small]',0)->outertext,"",$content);
            
        foreach($html_->find('h1, h2, h3, h4') as $e) { $e->style = "font-weight:bold"; $e->tag="p";}
        foreach($html_->find('img') as $e) { $e->outertext = '<figure>'.$e->outertext.'</figure>';}
        $html_->find('span',0)->outertext='';
        foreach($html_->find("script") as $a) {
            $a->outertext = '';
        }
        
        if($html_->find(".adsbygoogle")) foreach($html_->find(".adsbygoogle") as $a) {
            $a->outertext = '';
        }
        if($html_->find(".tptn_counter")) foreach($html_->find(".tptn_counter") as $a) {
            $a->outertext = '';
        }
            
        if($html_->find("a")) foreach($html_->find('a') as $e) { $e->outertext = $e->innertext;}
            
        $array['content'] = str_replace("<div style='margin-top:3px;margin-bottom:3px;'><center>","",$html_->outertext);
        $array['content'] = str_replace(array("<div>","</div>"),array("<p>","</p>"),$array['content']);
        foreach(str_get_html($array['content'])->find("img") as $img) {
            $i++;
            $image = $img->src;
            //if(!strpos($image, 'nuocmy.info')){
                $ext = end(explode(".",strtolower(basename($image))));
                $directory = 'files/'.date('Y', $reg_date).'/'; if(!is_dir($directory)) {$old = umask(0); mkdir($directory, 0777); umask($old);}
                $directory .= date('m', $reg_date).'/'; if(!is_dir($directory)) {$old = umask(0); mkdir($directory, 0777); umask($old);}
                $directory .= date('d', $reg_date).'/'; if(!is_dir($directory)) {$old = umask(0); mkdir($directory, 0777); umask($old);}
                $directory .= $slug.'-'.$i.'.'.$ext;
            
            
                file_put_contents($directory, file_get_contents($image));
            
                $array['content'] = str_replace($image, $directory, $array['content']);
            //}
        }
        if($html->find('meta[property="og:image"]')) { $image = $html->find('meta[property="og:image"]',0)->getAttribute('content');
            $ext = end(explode(".",strtolower(basename($image))));
            $directory1 = 'upload/'.date('Y', $reg_date).'/'; if(!is_dir($directory1)) {$old = umask(0); mkdir($directory1, 0777); umask($old);}
            $directory1 .= date('m', $reg_date).'/'; if(!is_dir($directory1)) {$old = umask(0); mkdir($directory1, 0777); umask($old);}
            $directory1 .= date('d', $reg_date).'/'; if(!is_dir($directory1)) {$old = umask(0); mkdir($directory1, 0777); umask($old);}
            $directory1 .= $slug.'.'.$ext;
            
            
            file_put_contents($directory1, file_get_contents($image));
            $array['image'] = $directory1;
        }
        
        
        echo $one['news_id'];
        $array['status'] = 3;
        $array['is_draft'] = 0;
        $array['user_id'] = 2;
        $clsNews->updateOne($one['news_id'],$array);
        
        ob_flush();
        flush();      
        }
    }
    
    die('a');
}


function default_getBDplus() {
    
    global $core;
    $clsNews = new News;
    $domain = 'http://bongdaplus.vn/';

    $slug_ = $_GET['slug'];
    if($_GET['page']>1) $page = 'trang-'.$_GET['page'].'.html';else $page = '';
    $category_end = $_GET['category_end'];
    
    $link = $domain.$slug_.'/'.$page;
    
    $html_ = @file_get_html($link);
    
    foreach($html_->find(".grlf img") as $article) {
        $link_bv = 'http://bongdaplus.vn'.$article->parent('a')->getAttribute("href");

        $html = @file_get_html($link_bv);
        
        $array['title'] = trim($html->find("h1.tit",0)->plaintext); 
        $checkexist = $clsNews->getAll("title = '".$array['title']."' limit 1",false);
        
        
        if($html && !$checkexist[0]) {
            
            $array['link'] = $link_bv;
            $slug = $core->toSlug($array['title']);
            $array['slug'] = $slug;
            $reg_date = explode(" ",str_replace(" ngày "," ",$html->find(".period",0)->plaintext));
            $r1 = explode("-",$reg_date[1]);
            
            $array['reg_date'] = strtotime($r1[2].'/'.$r1[1].'/'.$r1[0].' '.$reg_date[0]);
            
            
            $reg_date = $array['reg_date'];
            $array['intro'] = $html->find(".summ",0)->outertext;
            

            if($html->find(".nref")) $nref = $html->find(".nref",0)->outertext;
            $array['intro'] = strip_tags(str_replace($nref,"",$array['intro']));
     
            foreach($html->find('a') as $e) { $e->outertext = $e->innertext;}
            $array['content'] = $html->find(".content",0)->innertext;
            if($html->find(".inpage")) $arr_new_relation_top = $html->find(".inpage",0)->outertext;
            //if($html->find("div[style=background: #F6F6F6; padding: 15px; margin-top: 15px;]")) $arr_gt = $html->find("div[style=background: #F6F6F6; padding: 15px; margin-top: 15px;]",0)->outertext;

            $array['content'] = str_replace($arr_new_relation_top,"",$array['content']);

            $array['content'] = preg_replace("/\<a([^>]*)\>([^<]*)\<\/a\>/i", "$2", $array['content']);
            
            if($html->find('meta[name="keywords"]')) $array['tags'] = ','.$html->find('meta[name="keywords"]',0)->getAttribute('content').',';
            
            
            if($html->find('meta[property="og:image"]')) { 
                $image = $html->find('meta[property="og:image"]',0)->getAttribute('content');

                $ext = end(explode(".",strtolower(basename($image))));
                $directory1 = 'upload/'.date('Y', $reg_date).'/'; if(!is_dir($directory1)) {$old = umask(0); mkdir($directory1, 0777); umask($old);}
                $directory1 .= date('m', $reg_date).'/'; if(!is_dir($directory1)) {$old = umask(0); mkdir($directory1, 0777); umask($old);}
                $directory1 .= date('d', $reg_date).'/'; if(!is_dir($directory1)) {$old = umask(0); mkdir($directory1, 0777); umask($old);}
                $directory1 .= $slug.'.'.$ext;
                
                
                file_put_contents($directory1, file_get_contents($image));
                $array['image'] = $directory1;
            }
            
           foreach(str_get_html($array['content'])->find("img") as $img) {
            $i++;
            $image = $img->src;

            $ext = end(explode(".",strtolower(basename($image))));
            $directory = 'files/'.date('Y', $reg_date).'/'; if(!is_dir($directory)) {$old = umask(0); mkdir($directory, 0777); umask($old);}
            $directory .= date('m', $reg_date).'/'; if(!is_dir($directory)) {$old = umask(0); mkdir($directory, 0777); umask($old);}
            $directory .= date('d', $reg_date).'/'; if(!is_dir($directory)) {$old = umask(0); mkdir($directory, 0777); umask($old);}
            $directory .= $slug.'-'.$i.'.'.$ext;
            
            
            file_put_contents($directory, file_get_contents($image));
            
            $array['content'] = str_replace($image, $directory, $array['content']);
            
        }
        
        $array['content'] = str_replace("<div","<p",$array['content']);
        $array['content'] = str_replace("</div>","</p>",$array['content']);
            $array['category_id'] = $category_end;
            $array['is_draft'] = 0;
                        $array['is_featured'] = 1;
            $array['featured_date'] = $array['reg_date'];
            $array['status'] = 3;
            $array['push_date'] = $array['reg_date'];
            $array['show_date'] = date("Y-m-d H:i",$array['reg_date']);
            $array['is_push'] = 0;
            $array['user_id'] = $_GET['user_id'];
            echo $array['title'];
            
            //print_r($array);
            $clsNews->insertOne($array);

            ob_flush();
            flush();      
        } else {
            echo 'Đã có';
        }
    }
    exit();
}
function default_getTT247() {
    
    global $core;
    $clsNews = new News;
    $domain = 'http://thethao247.vn/';

    $slug_ = $_GET['slug'];
    if($_GET['page']>1) $page = 'p'.$_GET['page'];else $page = '';
    $category_end = $_GET['category_end'];
    
    $link = $domain.$slug_.'/'.$page;
    
    $html_ = @file_get_html($link);
    
    foreach($html_->find(".grid940 img") as $article) {
        $link_bv = $article->parent('a')->getAttribute("href");
        
            
        
        $html = @file_get_html($link_bv);
        
        
        $checkexist = $clsNews->getAll("link = '".$link_bv."' limit 1",false);
        
        
        if($html && !$checkexist[0]) {
            $array['title'] = trim($html->find("h1.title_detail",0)->plaintext); 
            $array['link'] = $link_bv;
            $slug = $core->toSlug($array['title']);
            $array['slug'] = $slug;
            $reg_date = explode(" ",$html->find(".date_news",0)->plaintext);

            $r1 = explode("/",$reg_date[3]);
            
            $array['reg_date'] = strtotime($r1[2].'/'.$r1[1].'/'.$r1[0].' '.str_replace("h",":",$reg_date[0]));
            

            $reg_date = $array['reg_date'];
            $array['intro'] =  trim($html->find(".sapo_detail",0)->plaintext);
            
            
            $content = $html->find("#main-detail",0)->innertext;
            //if($html->find(".inpage")) $arr_new_relation_top = $html->find(".inpage",0)->outertext;
            //if($html->find("div[style=background: #F6F6F6; padding: 15px; margin-top: 15px;]")) $arr_gt = $html->find("div[style=background: #F6F6F6; padding: 15px; margin-top: 15px;]",0)->outertext;

            //$array['content'] = str_replace($arr_new_relation_top,"",$array['content']);
            $html_ = str_get_html($content);
            //if($html_->find('span[style=font-size:x-small]')) $content = str_replace($html_->find('span[style=font-size:x-small]',0)->outertext,"",$content);
            
            foreach($html_->find('h1, h2, h3, h4') as $e) { $e->style = "font-weight:bold"; $e->tag="p";}
            foreach($html_->find('img') as $e) { $e->outertext = '<figure>'.$e->outertext.'</figure>';}
            foreach($html_->find("[mcle]*") as $a) {
                $e_id = $a->id;
                $html_->find("#".$e_id,0)->outertext='';
            }
            $html_->find('span',0)->outertext='';
            foreach($html_->find("script") as $a) {
                $a->outertext = '';
            }
            foreach($html_->find("#div-gpt-ad-1474539902938-1") as $a) {
                $a->outertext = '';
            }
            foreach($html_->find("#add") as $a) {
                $a->outertext = '';
            }
            
            foreach($html_->find('a') as $e) { $e->outertext = $e->innertext;}
            
            $array['content'] = $html_->outertext;
            
            if($html->find('meta[name="keywords"]')) $array['tags'] = ','.$html->find('meta[name="keywords"]',0)->getAttribute('content').',';
            
            
            if($html->find('meta[property="og:image"]')) { 
                $image = $html->find('meta[property="og:image"]',0)->getAttribute('content');

                $ext = end(explode(".",strtolower(basename($image))));
                $directory1 = 'upload/'.date('Y', $reg_date).'/'; if(!is_dir($directory1)) {$old = umask(0); mkdir($directory1, 0777); umask($old);}
                $directory1 .= date('m', $reg_date).'/'; if(!is_dir($directory1)) {$old = umask(0); mkdir($directory1, 0777); umask($old);}
                $directory1 .= date('d', $reg_date).'/'; if(!is_dir($directory1)) {$old = umask(0); mkdir($directory1, 0777); umask($old);}
                $directory1 .= $slug.'.'.$ext;
                
                
                file_put_contents($directory1, file_get_contents($image));
                $array['image'] = $directory1;
            }
            
           foreach(str_get_html($array['content'])->find("img") as $img) {
            $i++;
            $image = $img->src;

            $ext = end(explode(".",strtolower(basename($image))));
            $directory = 'files/'.date('Y', $reg_date).'/'; if(!is_dir($directory)) {$old = umask(0); mkdir($directory, 0777); umask($old);}
            $directory .= date('m', $reg_date).'/'; if(!is_dir($directory)) {$old = umask(0); mkdir($directory, 0777); umask($old);}
            $directory .= date('d', $reg_date).'/'; if(!is_dir($directory)) {$old = umask(0); mkdir($directory, 0777); umask($old);}
            $directory .= $slug.'-'.$i.'.'.$ext;
            
            
            file_put_contents($directory, file_get_contents($image));
            
            $array['content'] = str_replace($image, $directory, $array['content']);
            
        }
            $array['category_id'] = $category_end;
            $array['is_draft'] = 0;
            $array['is_featured'] = 1;
            $array['featured_date'] = $array['reg_date'];
            $array['status'] = 3;
            $array['push_date'] = $array['reg_date'];
            $array['show_date'] = date("Y-m-d H:i",$array['reg_date']);
            $array['is_push'] = 1;
            $array['user_id'] = $_GET['user_id'];
            echo $array['title'];

            $clsNews->insertOne($array);
            ob_flush();
            flush();      
        } else {
            echo 'Đã có';
        }
    }
    exit();
}

?>