<?php
    
    $clsHistory = new History;$assign_list['clsHistory']            = $clsHistory;
    $clsUser = new User;$assign_list['clsUser']            = $clsUser;
    $clsNews = new News;$assign_list['clsNews']            = $clsNews;
    
    $listHistory = $clsHistory->getAll('note != "" order by history_id desc limit 50');
    $assign_list['listHistory']            = $listHistory;
    
    $arr['welcome']         = "Chào mừng bạn đã đăng nhập vào trang quản trị. Để được hỗ trợ, vui lòng liên hệ duc.nguyenvan@netlink.vn. Website: itmedia.vn";
    $arr['permission']      = "Bạn chưa quyền hạn để truy cập URL trên";
    $arr['updateSuccess']   = "Cập nhật thành công!";
    $arr['updateFalse']     = "Cập nhật thất bại!";
    $arr['insertSuccess']   = "Thêm mới thành công!";
    $arr['insertFalse']     = "Thêm mới thất bại!";
    $arr['moveSuccess']     = "Di chuyển thành công!";
    $arr['moveFalse']       = "Di chuyển thất bại!";
    $arr['lock']            = "Chức năng bị khóa";
    
    
    
    if($_GET['mes']) {
        $msg = $arr[$_GET['mes']];
        $assign_list["msg"] = $msg;
        unset($_GET['mes']);
    }
?>