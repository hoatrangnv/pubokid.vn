<?php
/**
 *  Created by   :
 *  @author		: Ong The Thanh
 *  @date		: 2012/01/23
 *  @version		: 0.0.1
 */
error_reporting(0);
ini_set('display_errors', 'Off');
global $cnn, $mod, $act, $_LANG_ID, $core, $head_page, $title_page, $description_page,
    $keyword_page, $msg, $ads, $global_options, $device;
#
require ('config.php');
global $Cache, $connect_cache, $connect_db;
//$Cache = new Memcache();
//$connect_cache = true;
$connect_db = false;
//$Cache->connect('localhost', 11211) or $connect_cache = false;
//if (isset($_GET['flush']) && $_GET['flush'] == '1') {
//    if ($Cache->flush())
//        die('OK');
//    else
//        die('NOT OK');
//}
class dbBasic
{
    function connect()
    {
        global $connect_db;
        if (!$connect_db) {
            $connect_db = true;
            global $cnn;
            if (!$cnn = mysql_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD))
                die("Error: Not connect to database!");
            mysql_query("SET NAMES utf8");
            if (!$sldb = mysql_select_db(DB_DATABASE, $cnn))
                die("Error: Not open table " . DB_DATABASE . "!");
        }
    }
    function disconnect()
    {
        global $cnn;
        if (!mysql_close($cnn))
            die("Error close SQL");
    }
    function getKey($id)
    {
        return MEMCACHE_NAME . '_' . $this->tbl . '_' . $id;
    }
    function getArrKey($key_open = '')
    {
        return $this->getCache(MEMCACHE_NAME . '_' . $this->tbl . '_' . $key_open);
    }
    function setArrKey($key, $key_open = '')
    {
        $arr = $this->getArrKey($key_open);
        $arr[$key] = $key;
        return $this->updateCache(MEMCACHE_NAME . '_' . $this->tbl . '_' . $key_open, $arr);
    }
    function deleteArrKey($key_open = '')
    {
        $arr = $this->getArrKey($key_open);
        if ($arr)
            foreach ($arr as $key)
                $this->deleteCache($key);
        $this->deleteCache(MEMCACHE_NAME . '_' . $this->tbl . '_' . $key_open);
    }
    function getCache($key)
    {
        global $Cache, $connect_cache;
        if (!$connect_cache)
            return false;
        $res = $Cache->get($key);
        return $res;
    }
    function setCache($key, $value = 'null', $time = 10800)
    {
        global $Cache, $connect_cache;
        if (!$connect_cache)
            return false;
        $compress = is_bool($value) || is_int($value) || is_float($value) ? false :
            MEMCACHE_COMPRESSED;
        $time = $time + (rand(0, 1000) * 10);
        $res = $Cache->set($key, $value, $compress, $time);
        return $res;
    }
    function updateCache($key, $value = 'null', $time = 10800)
    {
        global $Cache, $connect_cache;
        if (!$connect_cache)
            return false;
        $compress = is_bool($value) || is_int($value) || is_float($value) ? false :
            MEMCACHE_COMPRESSED;
        $time = $time + (rand(0, 1000) * 10);
        $res = $Cache->set($key, $value, $compress, $time);
        return $res;
    }
    function deleteCache($key)
    {
        global $Cache, $connect_cache;
        if (!$connect_cache)
            return;
        $Cache->delete($key, 0);
    }

    function setCacheBox($key, $html)
    {
        global $connect_cache;
        if (!$connect_cache)
            return;
        $key = MEMCACHE_NAME . '_BOX_' . $key;
        $this->setCache($key, $html);
        $this->setArrKey($key);
        echo $html;
    }
    function getCacheBox($key)
    {
        global $connect_cache;
        if (!$connect_cache)
            return;
        $key = MEMCACHE_NAME . '_BOX_' . $key;
        return $this->getCache($key);
    }
    function getField($id, $field)
    {
        $this->connect();
        if ($sql = mysql_query("SELECT " . $field . " FROM " . $this->tbl . " where " .
            $this->pkey . "='" . $id . "'")) {
            $_cn = mysql_fetch_assoc($sql);
            mysql_free_result($sql);
            return $_cn[$field];
        }
    }
    function getOne($id, $cache = true)
    {
        $arr = array();
        $key = $this->getKey($id);
        if ($cache)
            $arr = $this->getCache($key);
        if ($arr) {
            if ($arr == 'null')
                return false;
            else
                return $arr;
        } else {
            $this->connect();
            if ($sql = mysql_query("SELECT * FROM " . $this->tbl . " where " . $this->pkey .
                "='" . $id . "'")) {
                $arr = mysql_fetch_assoc($sql);
                mysql_free_result($sql);
            }
            $this->setCache($key, $arr, 172800);
        }
        return $arr;
    }
    function getCount($cons, $cache = true, $key_open = '')
    {
        $key = MEMCACHE_NAME . '_COUNT_' . md5($this->tbl . $cons);
        if ($cache)
            $res = $this->getCache($key);
        if ($res == 'null')
            return 0;
        if ($res)
            return $res;
        if ($cons != '')
            $cons = " WHERE " . $cons;
        $this->connect();
        if ($sql = mysql_query("SELECT count(" . $this->pkey . ") as intCount FROM " . $this->
            tbl . $cons)) {
            $_cn = mysql_fetch_assoc($sql);
            mysql_free_result($sql);
            $res = $_cn['intCount'];
            if ($res)
                $this->setCache($key, $res, 7200);
            else
                $this->setCache($key, 'null', 7200);
            $this->setArrKey($key, $key_open);
            return $res;
        }
    }
    function getMax($field, $cons, $key_open = '')
    {
        $key = MEMCACHE_NAME . '_MAX_' . md5($this->tbl . $field . $cons);
        $res = $this->getCache($key);
        if ($res == 'null')
            return 0;
        if ($res)
            return $res;
        if ($cons != '')
            $cons = " WHERE " . $cons;
        $this->connect();
        if ($sql = mysql_query("SELECT max(" . $field . ") as intMax FROM " . $this->
            tbl . $cons)) {
            $_cn = mysql_fetch_assoc($sql);
            mysql_free_result($sql);
            $res = $_cn['intMax'];
            if ($res)
                $this->setCache($key, $res, 7200);
            else
                $this->setCache($key, 'null', 7200);
            $this->setArrKey($key, $key_open);
            return $res;
        }
    }
    function getSum($field, $cons, $key_open = '')
    {
        $key = MEMCACHE_NAME . '_SUM_' . md5($this->tbl . $field . $cons);
        $res = $this->getCache($key);
        if ($res == 'null')
            return 0;
        if ($res)
            return $res;
        if ($cons != '')
            $cons = " WHERE " . $cons;
        $this->connect();
        if ($sql = mysql_query("SELECT sum(" . $field . ") as intSum FROM " . $this->
            tbl . $cons)) {
            $_cn = mysql_fetch_assoc($sql);
            mysql_free_result($sql);
            $res = $_cn['intSum'];
            if ($res)
                $this->setCache($key, $res, 7200);
            else
                $this->setCache($key, 'null', 7200);
            $this->setArrKey($key, $key_open);
            return $res;
        }
    }
    function getAll($cons, $cache = true, $key_open = '')
    {
        $key = MEMCACHE_NAME . '_ALL_' . md5($this->tbl . $cons);
        $arr = array();
        if ($cache)
            $arr = $this->getCache($key);
        if ($arr == 'null')
            return false;
        if (!$arr) {
            $this->connect();
            if ($cons != '')
                $cons = " WHERE " . $cons;
            if ($sql = mysql_query("SELECT " . $this->pkey . " FROM " . $this->tbl . $cons)) {
                while ($_cn = mysql_fetch_assoc($sql))
                    $arr[] = $_cn[$this->pkey];
            }
            mysql_free_result($sql);
            if ($arr)
                $this->setCache($key, $arr, 7200);
            else
                $this->setCache($key, 'null', 7200);
            $this->setArrKey($key, $key_open);
        }
        return $arr;
    }
    function getQuery($sql, $key_open = '', $cache = true)
    {
        $key = MEMCACHE_NAME . '_ALL_' . md5($sql);
        $arr = array();
        if ($cache)
            $arr = $this->getCache($key);
        if ($arr == 'null')
            return false;
        if (!$arr) {
            $this->connect();
            if ($cons != '')
                $cons = " WHERE " . $cons;
            if ($sql = mysql_query($sql)) {
                while ($_cn = mysql_fetch_assoc($sql))
                    $arr[] = $_cn;
            }
            mysql_free_result($sql);
            if ($arr)
                $this->setCache($key, $arr, $time);
            else
                $this->setCache($key, 'null', $time);
            $this->setArrKey($key, $key_open);
        }
        return $arr;
    }

    function updateOne($id, $array)
    {
        if (!is_array($array))
            return false;
        $key_cache = $this->getKey($id);
        $oneItem = $this->getCache($key_cache);
        if ($oneItem)
            $exists_cache = true;
        else
            $exists_cache = false;
        if ($array)
            foreach ($array as $key => $val) {
                if (!$value)
                    $value = $key . '="' . addslashes($val) . '"';
                else
                    $value .= ',' . $key . '="' . addslashes($val) . '"';
                $oneItem[$key] = $val;
            }
        $this->connect();
        $res = mysql_query("UPDATE " . $this->tbl . " SET " . $value . " WHERE " . $this->
            pkey . " = '" . $id . "'");
        if ($res)
            if ($exists_cache)
                $this->setCache($key_cache, $oneItem);
        return $res;
    }
    function updateAll($cons, $array, $key_open = '')
    {
        if (!is_array($array))
            return false;
        else
            foreach ($array as $key => $val) {
                if (!$value)
                    $value = $key . '="' . addslashes($val) . '"';
                else
                    $value .= ',' . $key . '="' . addslashes($val) . '"';
            }
        $this->connect();
        $res = mysql_query("UPDATE " . $this->tbl . " SET " . $value . " WHERE " . $cons);
        if ($res && $key_open)
            $this->deleteArrKey($key_open);
        return $res;
    }
    function insertOne($array, $clean_cache = true, $key_open = '')
    {
        if (!is_array($array))
            return false;
        if ($array)
            foreach ($array as $key => $val) {
                if (!$field)
                    $field = $key;
                else
                    $field .= ',' . $key;
                if (!$value)
                    $value = '"' . addslashes($val) . '"';
                else
                    $value .= ',"' . addslashes($val) . '"';
            }
        if ($clean_cache)
            $this->deleteArrKey($key_open);
        $this->connect();
        $res = mysql_query("INSERT INTO " . $this->tbl . " (" . $field . ") VALUES(" . $value .
            ")");
        return $res;
    }
    function deleteOne($id, $clean_cache = true, $key_open = '')
    {
        if ($clean_cache)
            $this->deleteArrKey($key_open);
        $this->connect();
        $sql = "DELETE FROM " . $this->tbl . " WHERE (" . $this->pkey . "='" . $id .
            "')";
        $res = mysql_query($sql);
        return $res;
    }
    function deleteAll($cons, $key_open = '')
    {
        $all = $this->getAll($cons, true, $key_open);
        if ($all)
            foreach ($all as $one)
                $this->deleteOne($one, true, $key_open);
        return true;
    }
    function getListPage($cons, $rpp = RECORD_PER_PAGE, $key_open = '',$cache = true)
    {
        if ($cons == '')
            $cons = "1=1";
        $page = isset($_GET["page"]) ? $_GET["page"] : 1;
        return $this->getAll($cons . ' LIMIT ' . ($page - 1) * $rpp . ',' . $rpp, $cache,
            $key_open);
    }
    function getNavPage($cons, $rpp = RECORD_PER_PAGE, $key_open = '')
    {
        if ($cons == '')
            $cons = "1=1";
        $page = isset($_GET["page"]) ? $_GET["page"] : 1;
        $totalRecord = $this->getCount($cons, true, $key_open);
        $totalPage = ceil($totalRecord / $rpp);
        $paging = '';
        if (($page - 100) > 0)
            $paging[] = array(0 => $page - 100, 1 => $page - 100);
        for ($i = $page - 5; $i < $page + 5; $i++)
            if ($i > 0 && $i <= $totalPage)
                $paging[] = array(0 => $i, 1 => $i);
        if (($page + 100) <= $totalPage)
            $paging[] = array(0 => $page + 100, 1 => $page + 100);
        return $paging;
    }
    function getNavPageAdmin($cons, $rpp = RECORD_PER_PAGE, $key_open = '')
    {
        if ($cons == '')
            $cons = "1=1";
        $page = isset($_GET["page"]) ? $_GET["page"] : 1;
        $totalRecord = $this->getCount($cons, true, $key_open);
        $totalPage = ceil($totalRecord / $rpp);
        $paging = '';
        if ($page > 1)
            $paging[] = array(0 => 1, 1 => 'Đầu');
        if (($page - 100) > 0)
            $paging[] = array(0 => $page - 100, 1 => $page - 100);
        for ($i = $page - 5; $i < $page + 5; $i++)
            if ($i > 0 && $i <= $totalPage)
                $paging[] = array(0 => $i, 1 => $i);
        if (($page + 100) <= $totalPage)
            $paging[] = array(0 => $page + 100, 1 => $page + 100);
        if ($page < $totalPage)
            $paging[] = array(0 => $totalPage, 1 => 'Cuối');
        return $paging;
    }

    function slugToID($slug)
    {
        $all = $this->getAll("is_trash = 0 and slug='" . $slug . "' order by " . $this->
            pkey . " desc limit 0,1");
        if ($all[0])
            return $all[0];
    }
    function getMaxID()
    {
        return $this->getMax($this->pkey, "1=1");
    }
    function getRegDate($_id, $field = 'reg_date')
    {
        $res = $this->getOne($_id);
        $reg_date = $res[$field];
        return "<time class='ago' datetime='" . date('Y-m-d', $reg_date) . "T" . date('H:i:s',
            $reg_date) . "Z+07:00'>" . date('H:i - d/m/Y', $reg_date) . "</time>";
    }
}
#
define("DB_PREFIX", "default_");
$classFile = scandir(DIR_CLASS . "/");
foreach ($classFile as $value)
    if (substr($value, 0, 5) == 'class')
        require (DIR_CLASS . "/" . $value);
#
$assign_list["DIR_TEMPLATES"] = DIR_TEMPLATES;
define("DIR_MODULES", DIR_TEMPLATES . "/modules");
define("DIR_THEMES", DIR_TEMPLATES . "/themes");
define("DIR_BLOCKS", DIR_TEMPLATES . "/blocks");
#
define("DIR_IMAGES", DIR_THEMES . "/images");
define("DIR_CSS", DIR_THEMES . "/css");
define("DIR_JS", DIR_THEMES . "/js");
#
define("URL_VIKICMS", "https://" . $_SERVER['HTTP_HOST'] . DIR_FOLDER);
define("URL_CLASS", URL_VIKICMS . '/' . DIR_CLASS);
define("URL_TEMPLATES", URL_VIKICMS . '/' . DIR_TEMPLATES);
define("URL_MODULES", URL_VIKICMS . '/' . DIR_MODULES);
define("URL_THEMES", URL_VIKICMS . '/' . DIR_THEMES);
define("URL_BLOCKS", URL_VIKICMS . '/' . DIR_BLOCKS);
#
define("URL_IMAGES", URL_VIKICMS . '/' . DIR_IMAGES);
define("URL_CSS", URL_VIKICMS . '/' . DIR_CSS);
define("URL_JS", URL_VIKICMS . '/' . DIR_JS);
#
$PCMS_URL = SITE_PROTOCOL . 'www.' . SITE_DOMAIN;
define("PCMS_URL", URL_VIKICMS);
#
$clsSetting = new Setting();
define("PAGE_TITLE", $clsSetting->getTitle());
define("PAGE_EMAIL", $clsSetting->getEmail());
define("META_DES", $clsSetting->getMetaDes());
define("META_KEY", $clsSetting->getMetaKey());
define("META_FRAME", $clsSetting->getFrame());
define("META_SDT", $clsSetting->getSdt());
define("META_FB", $clsSetting->getFacebook());
#
if(!function_exists('parse_user_agent')){
    include 'lib/parse-user-agent.php';
}
/**
 * Get Request User Agent
 * @return array
 */
function ppo_get_UserAgent() {
    $agent = parse_user_agent();

    if ($agent['browser'] == null) {
        $agent['browser'] = "Unknown";
    }
    if ($agent['platform'] == null) {
        $agent['platform'] = "Unknown";
    }
    if ($agent['version'] == null) {
        $agent['version'] = "Unknown";
    }

    return $agent;
}
/**
 * Test if the current browser runs on a mobile device (smart phone, tablet, etc.)
 *
 * @return bool
 */
function ppo_is_mobile() {
    if ( empty($_SERVER['HTTP_USER_AGENT']) ) {
        $is_mobile = false;
    } elseif ( strpos($_SERVER['HTTP_USER_AGENT'], 'Mobile') !== false // many mobile devices (all iPhone, iPad, etc.)
        || strpos($_SERVER['HTTP_USER_AGENT'], 'Android') !== false
        || strpos($_SERVER['HTTP_USER_AGENT'], 'Silk/') !== false
        || strpos($_SERVER['HTTP_USER_AGENT'], 'Kindle') !== false
        || strpos($_SERVER['HTTP_USER_AGENT'], 'BlackBerry') !== false
        || strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mini') !== false
        || strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mobi') !== false ) {
            $is_mobile = true;
    } else {
        $agent = ppo_get_UserAgent();
        if (in_array($agent['platform'], array('Android', 'iPhone', 'BlackBerry', 'Windows Phone OS', 'Kindle', 'Kindle Fire', 'Playbook', 'Macintosh')) or 
            in_array($agent['browser'], array('IEMobile'))) {
                $is_mobile = true;
        } else {
            $is_mobile = false;
        }
    }

    return $is_mobile;
}
#
$mod = ($_GET['mod']) ? $_GET['mod'] : 'home';
$assign_list["mod"] = $mod;
$_GET['mod'] = $mod;
$act = ($_GET['act']) ? $_GET['act'] : 'default';
$assign_list["act"] = $act;
$_GET['act'] = $act;
#
class core
{
    function inc_dir($str)
    {
        global $mod, $act, $_LANG_ID, $core, $msg, $ads;
        require ($str);
        return $assign_list;
    }
    function getBlock($name)
    {
        global $mod, $act, $_LANG_ID, $core, $ads;
        $phpfile = DIR_TEMPLATES . '/blocks/' . $name . '/index.php';
        $htmlfile = DIR_TEMPLATES . '/blocks/' . $name . '/index.html';
        if (file_exists($phpfile) && file_exists($htmlfile)) {
            $assign_list = $this->inc_dir($phpfile);
            if ($assign_list)
                foreach ($assign_list as $key => $val)
                    ${$key} = $val;
            require ($htmlfile);
            unset($phpfile);
            unset($htmlfile);
            unset($assign_list);
        } else
            echo 'Not found block \'' . $name . '\'';
    }
    function goHome()
    {
        header('location: ' . $PCMS_URL);
    }
    function getIP()
    {
        return $_SERVER['REMOTE_ADDR'];
    }
    function toString($int)
    {
        return number_format($int, 0, ",", ".");
    }
    function toSlug($doc)
    {
        $str = addslashes(html_entity_decode($doc));
        $str = $this->toNormal($str);
        $str = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $str);
        $str = preg_replace("/( )/", '-', $str);
        $str = str_replace('/', '', $str);
        $str = str_replace("\/", '', $str);
        $str = str_replace("+", "", $str);
        $str = strtolower($str);
        $str = stripslashes($str);
        return trim($str, '-');
    }
    function toNormal($str)
    {
        $str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $str);
        $str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $str);
        $str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $str);
        $str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $str);
        $str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $str);
        $str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $str);
        $str = preg_replace("/(đ)/", 'd', $str);
        $str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $str);
        $str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $str);
        $str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $str);
        $str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $str);
        $str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $str);
        $str = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $str);
        $str = preg_replace("/(Đ)/", 'D', $str);
        return $str;
    }
    function get_limit_content($string, $length = 255)
    {
        $string = strip_tags($string);
        if (strlen($string) > 0) {
            $arr = explode(' ', $string);
            $return = '';
            if (count($arr) > 0) {
                $count = 0;
                if ($arr)
                    foreach ($arr as $str) {
                        $count += strlen($str);
                        if ($count > $length) {
                            $return .= "...";
                            break;
                        }
                        $return .= " " . $str;
                    }
            }
            return $return;
        }
    }
    function formatUrlsInText($text)
    {
        $reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
        preg_match_all($reg_exUrl, $text, $matches);
        $usedPatterns = array();
        foreach ($matches[0] as $pattern) {
            if (!array_key_exists($pattern, $usedPatterns)) {
                $usedPatterns[$pattern] = true;
                $text = str_replace($pattern, '<a href="' . $pattern .
                    '" rel="nofollow" target="_blank">' . $pattern . '</a> ', $text);
            }
        }
        return $text;
    }
    function time_ago($tm, $rcs = 0)
    {
        $cur_tm = time();
        $dif = $cur_tm - $tm;
        $pds = array(
            'giây',
            'phút',
            'giờ',
            'ngày',
            'tuần',
            'tháng',
            'năm',
            'thập kỉ');
        $lngh = array(
            1,
            60,
            3600,
            86400,
            604800,
            2630880,
            31570560,
            315705600);
        for ($v = sizeof($lngh) - 1; ($v >= 0) && (($no = $dif / $lngh[$v]) <= 1); $v--)
            ;
        if ($v < 0)
            $v = 0;
        $_tm = $cur_tm - ($dif % $lngh[$v]);
        $no = floor($no);
        if ($no <> 1)
            $pds[$v] .= '';
        $x = sprintf("%d %s ", $no, $pds[$v]);
        if (($rcs == 1) && ($v >= 1) && (($cur_tm - $_tm) > 0))
            $x .= time_ago($_tm);
        return $x . ' trước';
    }
    function time_str($time)
    {
        return date("H:i d/m/Y", $time);
    }
    function getAddress()
    {
        
        $protocol = $_SERVER['HTTPS'] == 'on' ? 'https' : 'http';
        return $protocol . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    }
    function setLinkInAdmin($arr)
    {
        $res = $_GET;
        $str = '?';
        $i = 0;
        if ($arr)
            foreach ($arr as $key => $val) {
                $res[$key] = $val;
                if ($val == '')
                    unset($res[$key]);
            }
        if ($res)
            foreach ($res as $key => $val) {
                if ($i == 0)
                    $i = 1;
                else
                    $str .= '&';
                $str .= $key . '=' . $val;
            }
        echo $str;
    }
    function getWidthImage($url)
    {
        $image = ImageCreateFromString(file_get_contents($url));
        return imagesx($image);
    }
    function getHeightImage($url)
    {
        $image = ImageCreateFromString(file_get_contents($url));
        return imagesy($image);
    }
    function ftpUpload($file, $paths = 'upload', $slug_name = null, $time = 0)
    {
        #
        $directory = $paths . '/';
        if (!is_dir($directory)) {
            $old = umask(0);
            mkdir($directory, 0777);
            umask($old);
        }
        $directory .= date('Y', $time) . "/";
        if (!is_dir($directory)) {
            $old = umask(0);
            mkdir($directory, 0777);
            umask($old);
        }
        $directory .= date('m', $time) . "/";
        if (!is_dir($directory)) {
            $old = umask(0);
            mkdir($directory, 0777);
            umask($old);
        }
        $directory .= date('d', $time) . "/";
        if (!is_dir($directory)) {
            $old = umask(0);
            mkdir($directory, 0777);
            umask($old);
        }
        #
        $file_name = $_FILES[$file]['name'];
        if ($slug_name)
            $slug_name .= '.' . end(explode('.', $file_name));
        else
            $slug_name = $file_name;
        #
        $link = $directory . $slug_name;
        $res = move_uploaded_file($_FILES[$file]["tmp_name"], $link);
        return $link;


        # ======
        $domain_ftp = FTP_DOMAIN;
        $filep = $_FILES[$file]['tmp_name'];
        $file_type = $_FILES[$file]['type'];
        $file_size = $_FILES[$file]['size'];
        $file_name = $_FILES[$file]['name'];
        $ftp_server = FTP_SERVER;
        $ftp_user_name = FTP_USERNAME;
        $ftp_user_pass = FTP_PASSWORD;
        if ((($file_type == "image/gif") || ($file_type == "image/bmp") || ($file_type ==
            "image/jpeg") || ($file_type == "image/png") || ($file_type == "image/pjpeg")) &&
            ($file_size < 10000000)) {
            if ($slug_name)
                $slug_name .= '.' . end(explode('.', $file_name));
            else
                $slug_name = $file_name;
            //die('zzz');
            $conn_id = ftp_connect($ftp_server);
            //die('zzz');
            $login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
            if ((!$conn_id) || (!$login_result))
                die("FTP connection has encountered an error!");
            if (!$time)
                $time = time();
            if (!ftp_chdir($paths)) {
                $oldumask = umask(0);
                ftp_mkdir($conn_id, $paths);
                ftp_chmod($conn_id, '777', $paths);
                umask($oldumask);
            }
            $paths .= date('/Y', $time);
            if (!ftp_chdir($paths)) {
                $oldumask = umask(0);
                ftp_mkdir($conn_id, $paths);
                ftp_chmod($conn_id, '777', $paths);
                umask($oldumask);
            }
            $paths .= date('/m', $time);
            if (!ftp_chdir($paths)) {
                $oldumask = umask(0);
                ftp_mkdir($conn_id, $paths);
                ftp_chmod($conn_id, '777', $paths);
                umask($oldumask);
            }
            $paths .= date('/d', $time);
            if (!ftp_chdir($paths)) {
                $oldumask = umask(0);
                ftp_mkdir($conn_id, $paths);
                ftp_chmod($conn_id, '777', $paths);
                umask($oldumask);
            }
            $upload = ftp_put($conn_id, $paths . '/' . $slug_name, $filep, FTP_BINARY);
            ftp_close($conn_id);
            if (!$upload)
                print_r(error_get_last());
            return $domain_ftp . '/' . $paths . '/' . $slug_name;
        } else
            return false;
    }
    function ftpUrlUpload($url, $paths = 'upload', $slug_name = null, $time = 0)
    {
        $domain_ftp = FTP_DOMAIN;
        $ftp_server = FTP_SERVER;
        $ftp_user_name = FTP_USERNAME;
        $ftp_user_pass = FTP_PASSWORD;
        $file_type = end(explode('.', $url));
        if (!$time)
            $time = time();
        if (($file_type == "gif") || ($file_type == "bmp") || ($file_type == "jpeg") ||
            ($file_type == "png") || ($file_type == "jpg")) {
            if ($slug_name)
                $slug_name .= '.' . $file_type;
            else
                $slug_name = date('His', $time) . '.' . $file_type;
            $conn_id = ftp_connect($ftp_server);
            $login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
            if ((!$conn_id) || (!$login_result))
                die("FTP connection has encountered an error!");
            if (!ftp_chdir($paths)) {
                $oldumask = umask(0);
                ftp_mkdir($conn_id, $paths);
                ftp_chmod($conn_id, '777', $paths);
                umask($oldumask);
            }
            $paths .= date('/Y', $time);
            if (!ftp_chdir($paths)) {
                $oldumask = umask(0);
                ftp_mkdir($conn_id, $paths);
                ftp_chmod($conn_id, '777', $paths);
                umask($oldumask);
            }
            $paths .= date('/m', $time);
            if (!ftp_chdir($paths)) {
                $oldumask = umask(0);
                ftp_mkdir($conn_id, $paths);
                ftp_chmod($conn_id, '777', $paths);
                umask($oldumask);
            }
            $paths .= date('/d', $time);
            if (!ftp_chdir($paths)) {
                $oldumask = umask(0);
                ftp_mkdir($conn_id, $paths);
                ftp_chmod($conn_id, '777', $paths);
                umask($oldumask);
            }
            $newfile = fopen('upload/server.jpg', "wb");
            $file = fopen($url, "r");
            if ($newfile) {
                while (!feof($file)) {
                    fwrite($newfile, fread($file, 1024 * 8), 1024 * 8);
                }
            } else
                die('Could not open file upload/server.jpg!');
            fclose($newfile);
            fclose($file);
            $upload = ftp_put($conn_id, $paths . '/' . $slug_name, 'upload/server.jpg',
                FTP_BINARY);
            ftp_close($conn_id);
            if (!$upload)
                print_r(error_get_last());
            return $domain_ftp . '/' . $paths . '/' . $slug_name;
        } else
            return false;
    }
    function ftpUrlUpload2($url, $paths = 'upload', $slug_name = null, $time = 0)
    {

        $domain_ftp = FTP_DOMAIN;
        $ftp_server = FTP_SERVER;
        $ftp_user_name = FTP_USERNAME;
        $ftp_user_pass = FTP_PASSWORD;
        $file_type = end(explode('.', $url));
        if (!$time)
            $time = time();
        if (($file_type == "gif") || ($file_type == "bmp") || ($file_type == "jpeg") ||
            ($file_type == "png") || ($file_type == "jpg")) {
            if ($slug_name)
                $slug_name .= rand(1, 10) . '.' . $file_type;
            else
                $slug_name = date('His', $time) . '.' . $file_type;
            $conn_id = ftp_connect($ftp_server);
            $login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
            if ((!$conn_id) || (!$login_result))
                die("FTP connection has encountered an error!");


            // create foder local
            $directory = $paths . '/';
            if (!is_dir($directory)) {
                $old = umask(0);
                mkdir($directory, 0777);
                umask($old);
            }
            $directory .= date('Y', $time) . "/";
            if (!is_dir($directory)) {
                $old = umask(0);
                mkdir($directory, 0777);
                umask($old);
            }
            $directory .= date('m', $time) . "/";
            if (!is_dir($directory)) {
                $old = umask(0);
                mkdir($directory, 0777);
                umask($old);
            }
            $directory .= date('d', $time) . "/";
            if (!is_dir($directory)) {
                $old = umask(0);
                mkdir($directory, 0777);
                umask($old);
            }
            $newfile = fopen($directory . $slug_name, "wb");
            $file = fopen($url, "r");
            if ($newfile) {
                while (!feof($file)) {
                    fwrite($newfile, fread($file, 1024 * 8), 1024 * 8);
                }
            } else
                die('Could not open file ' . $directory . $slug_name);


            $paths = 'public_html/' . $paths;
            if (!ftp_chdir($paths)) {
                $oldumask = umask(0);
                ftp_mkdir($conn_id, $paths);
                ftp_chmod($conn_id, 0777, $paths);
                umask($oldumask);
            }
            $paths .= date('/Y', $time);
            if (!ftp_chdir($paths)) {
                $oldumask = umask(0);
                ftp_mkdir($conn_id, $paths);
                ftp_chmod($conn_id, 0777, $paths);
                umask($oldumask);
            }
            $paths .= date('/m', $time);
            if (!ftp_chdir($paths)) {
                $oldumask = umask(0);
                ftp_mkdir($conn_id, $paths);
                ftp_chmod($conn_id, 0777, $paths);
                umask($oldumask);
            }
            $paths .= date('/d', $time);
            if (!ftp_chdir($paths)) {
                $oldumask = umask(0);
                ftp_mkdir($conn_id, $paths);
                ftp_chmod($conn_id, 0777, $paths);
                umask($oldumask);
            }
            $newfile = fopen('upload/server.jpg', "wb");
            $file = fopen($url, "r");
            if ($newfile) {
                while (!feof($file)) {
                    fwrite($newfile, fread($file, 1024 * 8), 1024 * 8);
                }
            } else
                die('Could not open file upload/server.jpg!');
            fclose($newfile);
            fclose($file);
            $upload = ftp_put($conn_id, $paths . '/' . $slug_name, 'upload/server.jpg',
                FTP_BINARY);
            ftp_close($conn_id);
            if (!$upload)
                print_r(error_get_last());
            return str_replace("public_html/", "", $domain_ftp . '/' . $paths . '/' . $slug_name);
        } else
            return false;
    }

    function Unicode_Decode($entity)
    {
        $convmap = array(
            0x0,
            0x10000,
            0,
            0xfffff);
        $entity = mb_decode_numericentity($entity, $convmap, 'UTF-8');
        return $entity;
    }
    function uploadImageURL($url, $directory)
    {
        $url = trim($url);
        if ($url) {
            $url = str_replace(' ', "%20", $url);
            $file = fopen($url, "rb");
            if ($file) {
                //$directory = "lib/tinymce/plugins/imagemanager/files/v1/";
                $valid_exts = array(
                    "jpg",
                    "jpeg",
                    "gif",
                    "png",
                    "bmp");
                $ext = end(explode(".", strtolower(basename($url))));
                if (1) { //in_array($ext,$valid_exts)
                    $newfile = fopen($directory, "wb"); // creating new file on local server
                    if ($newfile) {
                        while (!feof($file)) {
                            // Write the url file to the directory.
                            fwrite($newfile, fread($file, 1024 * 8), 1024 * 8); // write the file to the new directory at a rate of 8kb/sec. until we reach the end.
                        }
                        return $directory;
                    } else {
                        return 'Error Upload Image: Could not establish new file (' . $directory .
                            ') on local server. Be sure to CHMOD your directory to 777.';
                    }
                } else {
                    return 'Error Upload Image: Invalid file type. Please try another file.';
                }
            } else {
                return 'Error Upload Image: Could not locate the file: ' . $url . '';
            }
        } else {
            return 'Error Upload Image: Invalid URL entered. Please try again.';
        }
    }
    function isMobile()
    {
        $tablet_browser = 0;
        $mobile_browser = 0;

        if (preg_match('/(tablet|ipad|playbook)|(android(?!.*(mobi|opera mini)))/i',
            strtolower($_SERVER['HTTP_USER_AGENT']))) {
            $tablet_browser++;
        }

        if (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android|iemobile)/i',
            strtolower($_SERVER['HTTP_USER_AGENT']))) {
            $mobile_browser++;
        }

        if ((strpos(strtolower($_SERVER['HTTP_ACCEPT']), 'application/vnd.wap.xhtml+xml') >
            0) or ((isset($_SERVER['HTTP_X_WAP_PROFILE']) or isset($_SERVER['HTTP_PROFILE'])))) {
            $mobile_browser++;
        }

        $mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'], 0, 4));
        $mobile_agents = array(
            'w3c ',
            'acs-',
            'alav',
            'alca',
            'amoi',
            'audi',
            'avan',
            'benq',
            'bird',
            'blac',
            'blaz',
            'brew',
            'cell',
            'cldc',
            'cmd-',
            'dang',
            'doco',
            'eric',
            'hipt',
            'inno',
            'ipaq',
            'java',
            'jigs',
            'kddi',
            'keji',
            'leno',
            'lg-c',
            'lg-d',
            'lg-g',
            'lge-',
            'maui',
            'maxo',
            'midp',
            'mits',
            'mmef',
            'mobi',
            'mot-',
            'moto',
            'mwbp',
            'nec-',
            'newt',
            'noki',
            'palm',
            'pana',
            'pant',
            'phil',
            'play',
            'port',
            'prox',
            'qwap',
            'sage',
            'sams',
            'sany',
            'sch-',
            'sec-',
            'send',
            'seri',
            'sgh-',
            'shar',
            'sie-',
            'siem',
            'smal',
            'smar',
            'sony',
            'sph-',
            'symb',
            't-mo',
            'teli',
            'tim-',
            'tosh',
            'tsm-',
            'upg1',
            'upsi',
            'vk-v',
            'voda',
            'wap-',
            'wapa',
            'wapi',
            'wapp',
            'wapr',
            'webc',
            'winw',
            'winw',
            'xda ',
            'xda-');

        if (in_array($mobile_ua, $mobile_agents)) {
            $mobile_browser++;
        }

        if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'opera mini') > 0) {
            $mobile_browser++;
            //Check for tablets on opera mini alternative headers
            $stock_ua = strtolower(isset($_SERVER['HTTP_X_OPERAMINI_PHONE_UA']) ? $_SERVER['HTTP_X_OPERAMINI_PHONE_UA'] :
                (isset($_SERVER['HTTP_DEVICE_STOCK_UA']) ? $_SERVER['HTTP_DEVICE_STOCK_UA'] : ''));
            if (preg_match('/(tablet|ipad|playbook)|(android(?!.*mobile))/i', $stock_ua)) {
                $tablet_browser++;
            }
        }

        if ($tablet_browser > 0) {
            // do something for tablet devices
            return 'tablet';
        } else
            if ($mobile_browser > 0) {
                // do something for mobile devices
                return 'mobile';
            } else {
                // do something for everything else
                return 'desktop';
            }


    }

    function mailtoHtml($to, $title, $mess, $from)
    {

        $subject = $title;
        $subject = '=?UTF-8?B?' . base64_encode($title) . '?=';
        require_once ('lib/phpmailer/class.phpmailer.php');
        $mail = new PHPMailer();
        $mail->SMTPDebug = 1;
        $mail->IsSMTP();
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = "tls";
        $mail->Host = "smtp.gmail.com";
        $mail->Port = 587;
        $mail->Username = "healthplus@netlink.vn";
        $mail->Password = "netlink@1234";
        $mail->SetFrom('healthplus@netlink.vn', 'Thethao24tv');
        $mail->Subject = $subject;
        $body = $mess;
        $mail->MsgHTML($body);
        $mail->AddAddress($to, "");
        if (!$mail->Send()) {
            echo "Error!";
        } else {
            echo "Message sent!";
        }
        die();
    }

}
$core = new core;
#
$device = $core->isMobile();
define("DEVICE", $device);
#
class cls_header
{
    function cls_header()
    {   
        global $mod, $act, $_LANG_ID, $core, $msg, $ads, $title_page, $keyword_page;
        
        require (DIR_TEMPLATES . '/_header.php');
        $this->assign_list = $assign_list;
        unset($assign_list);
    }
    function display()
    {
        global $mod, $act, $_LANG_ID, $core, $msg, $ads, $title_page, $keyword_page;
        $assign_list = $this->assign_list;
        if (sizeof($assign_list) > 0)
            foreach ($assign_list as $key => $val)
                ${$key} = $val;
        require (DIR_TEMPLATES . '/_header.html');
        unset($assign_list);
    }
}
class cls_footer
{
    function cls_footer()
    {
        global $mod, $act, $_LANG_ID, $core, $msg, $ads;
        require (DIR_TEMPLATES . '/_footer.php');
        $this->assign_list = $assign_list;
        unset($assign_list);
    }
    function display()
    {
        global $mod, $act, $_LANG_ID, $core, $msg, $ads;
        $assign_list = $this->assign_list;
        if (sizeof($assign_list) > 0)
            foreach ($assign_list as $key => $val)
                ${$key} = $val;
        require (DIR_TEMPLATES . '/_footer.html');
        unset($assign_list);
    }
}
class cls_module
{
    function cls_module()
    {
        global $mod, $act, $_LANG_ID, $core, $head_page, $title_page, $description_page,
            $keyword_page, $assign_list, $msg, $ads;
        if (file_exists(DIR_MODULES . "/" . $mod . '/sub_default.php')) {
            require (DIR_MODULES . "/" . $mod . '/sub_default.php');
        } elseif (file_exists(DIR_MODULES . '/default/sub_default.php')) {
            require (DIR_MODULES . '/default/sub_default.php');
        } else
            die("Not found modules " . $mod);
        if (function_exists('default_' . $act))
            call_user_func('default_' . $act);
        else
            die("Not found act function " . $act);
        if (file_exists(DIR_MODULES . "/" . $mod . '/act_' . $act . '.html')) {
            $this->act_temp = DIR_MODULES . "/" . $mod . '/act_' . $act . '.html';
        } elseif (file_exists(DIR_MODULES . '/default/act_' . $act . '.html')) {
            $this->act_temp = DIR_MODULES . '/default/act_' . $act . '.html';
        } else
            die("Not found act template " . $act);
        $this->assign_list = $assign_list;
        unset($assign_list);
    }
    function display()
    {
        global $mod, $act, $_LANG_ID, $core, $title_page, $description_page, $keyword_page,
            $msg, $ads;
        $assign_list = $this->assign_list;
        if (sizeof($assign_list) > 0)
            foreach ($assign_list as $key => $val)
                ${$key} = $val;
        require ($this->act_temp);
        unset($assign_list);
    }
}
$cls_header = new cls_header();
$cls_module = new cls_module();
$cls_footer = new cls_footer();

require 'lib/bbit-compress.php';
require (DIR_TEMPLATES . '/index.php');
#
if ($connect_db and $dbBasic)
    $dbBasic->disconnect(); //else echo '<div style="width:100%;height:1px;background:#d91f1f"></div>';
if ($connect_cache and $Cache)
    $Cache->close();
else
    echo '<div style="width:100%;height:1px;background:blue"></div>';
unset($cls_header);
unset($cls_module);
unset($cls_footer);
unset($core);
unset($classFile);
unset($mod);
unset($act);
unset($_LANG_ID);
unset($title_page);
unset($description_page);
unset($keyword_page);
unset($assign_list);
?>