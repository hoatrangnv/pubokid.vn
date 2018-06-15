<?php  

    $clsCategory = new Category(); $assign_list['clsCategory'] = $clsCategory;
    $menu = $clsCategory->getAll('is_trash=0 and parent_id=0 and menu_display>0 order by menu_display'); 
    $assign_list['menu'] = $menu;
    //$clsStatistic = new Statistic;
    $clsChannel = new Channel;$assign_list['clsChannel'] = $clsChannel;
    $channel = $clsChannel->getAll("is_trash = 0 order by push_date desc limit 2");
    $assign_list['channel'] = $channel;
    if($mod=='news') {
        if($act=='default') {
            $id = $clsCategory->slugToID(trim($_GET['slug'],'/'));
            $active = $id;
        }
        elseif($act=='subcat') {
            $id = $clsCategory->slugToID(trim($_GET['slug'],'/'));
            $active = $clsCategory->getCategoryRoot($id);
            $sub_active = $id;
        } elseif($act=='detail') {
            $id = $_GET['id'];
            $clsNews = new News();
            $oneNews = $clsNews->getOne($id);
            $sub_active = $oneNews['category_id'];
            $active = $clsCategory->getCategoryRoot($sub_active);
        }
    }
    //$clsStatistic->setViews($id);
    $assign_list['active'] = $active;
    $assign_list['sub_active'] = $sub_active;

?>