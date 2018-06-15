<?php
    if(isset($_POST['btnSearch'])){
    	$title = $_POST['title'];
        $name = $_POST['txtname'];
        $from = $_POST['txtemail'];
        $content = $_POST['txtcontent'];
        $to = 'hanhchinhthienphu@gmail.com';
        $message = 'From: '.$name.": ".$content;
        
        $subject = 'Feedback Pubokid: '.$title;
        $headers = "From:" . $from;
        
        mail($to,$subject,$message,$headers); 
        header('Location: https://pubokid.vn/lienhe.html');  
    }
?>