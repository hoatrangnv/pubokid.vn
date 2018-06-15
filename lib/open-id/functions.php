<?php

require 'dbconfig.php';

class User {

    function checkUserGoogle($uid, $oauth_provider, $username, $email)
	{
        $userstable = USERS_TABLE_NAME;
        $query = mysql_query("SELECT * FROM `$userstable` WHERE email = '$email' and oauth_vendor = '$oauth_provider'") or die(mysql_error());
        $result = mysql_fetch_array($query);
        if (!empty($result)) {
            # User is already present
        } else {
            $reg_date = time();
            $query = mysql_query("INSERT INTO `$userstable` (oauth_vendor, oauth_id, fullname, email, reg_date) VALUES ('$oauth_provider', '$uid', '$username', '$email', '$reg_date')") or die(mysql_error());
            $query = mysql_query("SELECT * FROM `$userstable` WHERE email = '$email' and oauth_vendor = '$oauth_provider'");
            $result = mysql_fetch_array($query);
            return $result;
        }
        return $result;
    }

    

}

?>
