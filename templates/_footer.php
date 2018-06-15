<?php  
    //$clsAds = new Ads;
    //$ads = $clsAds->getAllContent();$assign_list['ads'] = $ads;  
    $clsCategory = new Category(); $assign_list['clsCategory'] = $clsCategory;
    $menu_footer = $clsCategory->getAll('is_trash=0 and footer_display>0 order by footer_display'); 
    $assign_list['menu_footer'] = $menu_footer;  
?>
