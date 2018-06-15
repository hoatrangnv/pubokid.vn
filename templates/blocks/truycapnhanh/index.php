<?php
$clsCategory = new Category;
$assign_list['clsCategory'] = $clsCategory;
$menu = $clsCategory->getAll("is_trash = 0 and home_display > 0 order by home_display");
$assign_list['menu'] = $menu;
?>