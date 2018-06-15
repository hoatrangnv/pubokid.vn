<?php
    $clsCategory = new Category;$assign_list['clsCategory'] = $clsCategory;
    $clsNews = new News;$assign_list['clsNews'] = $clsNews;
    $listYKien = $clsNews->getAll($clsNews->getCons(7).' order by push_date desc limit 4');
    $assign_list['listYKien'] = $listYKien;
?>