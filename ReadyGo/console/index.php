<?php

/**
入口文件
*/
/**
 * 范例：php index.php "space=cheat&action=brush_down"
 * 
 * @author NIGG
 * @version $Id$
 */
if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {   
    //'This is a server using Windows!';   
    define('NL', "\r\n");
} else {   
    //'This is a server not using Windows!';
    define('NL', "\n");
}
if (isset($argv)) {
    define('TNL', NL);
} else {
    define('TNL', "<br />".NL);
}

if(isset($argv) AND count($argv) >= 2) {
    $argv[1] = str_replace("?", '', $argv[1]);
    if ($argv[1] === mb_convert_encoding(mb_convert_encoding($argv[1], "GB2312", "UTF-8"), "UTF-8", "GB2312")) {

    } else {
        //如果是gb2312 的就转换为utf8的
        $argv[1] = mb_convert_encoding($argv[1], 'UTF-8', 'GB2312');
    }

    if (strpos($argv[1], '&') !== false) {
        $params = explode('&', $argv[1]);
    } else {
        $params = array($argv[1]);
    }

    $_REQUEST = array();
    foreach($params as $p) {
        if (strpos($p, '=') !== false) {
            $arr = explode('=', $p);
            $_REQUEST[$arr[0]] = $arr[1];
        } else {
            $_REQUEST[$p] = '';
        }
    }

    $_GET = $_REQUEST;
}

###########系统级需要的参数
$ops['def_charset'] = isset($_GET['def_charset']) ? $_GET['def_charset'] : 'UTF-8';
$ops['out_charset'] = isset($_GET['out_charset']) ? $_GET['out_charset'] : 'GBK';
$ops['debug'] = isset($_GET['debug']) ? $_GET['debug'] : true;
$ops['time_limit'] = isset($_GET['time_limit']) ? $_GET['time_limit'] : 0;
$ops['memory_limit'] = isset($_GET['memory_limit']) ? $_GET['memory_limit'] : '-1';
###########系统级需要的参数

define('DEFAULT_CHARSET', $ops['def_charset']); #默认编码
define('DEFAULT_CHARSET_ICONV', $ops['def_charset'].'//IGNORE'); #默认编码

define('OUT_CHARSET', $ops['out_charset']);
header('Content-Type: text/html; charset='.OUT_CHARSET);
date_default_timezone_set("Asia/Shanghai");
if ($ops['debug']) {
    error_reporting(E_ALL);
} else {
    error_reporting(0);
}
set_time_limit($ops['time_limit']);
ini_set('memory_limit', $ops['memory_limit']);

define('LANGUAGE', 'cn'); #默认语言cn

define('SCRIPT_TYPE', 'php');
define('EXT', '.php');
define('PATH_CUT', '::');

define('SELF_NAME', pathinfo(__FILE__, PATHINFO_BASENAME));
define('CONSOLE_PATH', str_replace(SELF_NAME, '', __FILE__));
define('ROOT_PATH', dirname(dirname(__FILE__)) . "\\");

define('DATAS_FOLDER','datas'); #目录名
define('DATAS_PATH', ROOT_PATH.DATAS_FOLDER.'/');

define('COOKIES_FOLDER','cookies'); #目录名
define('COOKIES_PATH', DATAS_PATH.COOKIES_FOLDER.'/');

define('CACHES_FOLDER','caches'); #目录名
define('CACHES_PATH', DATAS_PATH.CACHES_FOLDER.'/');

define('APPLICATION_FOLDER','executor'); #目录名
define('APP_PATH', ROOT_PATH.APPLICATION_FOLDER.'/');

define('SYSTEM_FOLDER','system'); #目录名
define('LANGUAGE_FOLDER','languages'); #语言包目录名
define('CONFIG_FOLDER','config'); #配置目录名
define('CONFIGER','configer'); #配置文件名
define('LIBS_FOLDER','libs'); #类库目录名
define('TOOLS_FOLDER','tools'); #工具目录名
define('LOGS_FOLDER','logs'); #日志目录名
define('SYSTEM_PATH', CONSOLE_PATH.SYSTEM_FOLDER.'/');
define('LANGUAGE_PATH', CONSOLE_PATH.LANGUAGE_FOLDER.'/');
define('CONFIG_PATH', CONSOLE_PATH.CONFIG_FOLDER.'/');
define('LIBS_PATH', CONSOLE_PATH.LIBS_FOLDER.'/');
define('TOOLS_PATH', CONSOLE_PATH.TOOLS_FOLDER.'/');
define('LOGS_PATH', CONSOLE_PATH.LOGS_FOLDER.'/');

define('LANGUAGE_SUFFIX','_language'); #语言类后缀
define('TOOL_SUFFIX','_tool'); #工具类后缀

define('APP_LANGEAGE_FOLDE','app'); #应用程序语言包的文件目录名
define('SYSTEM_LANGEAGE_FOLDE','system'); #系统语言包的文件目录名
define('DEFAULT_LANGEAGE_FILENAME','main'); #默认读取的语言包的文件名

define('UNAUTHORIZED_ACCESS', '非法访问！！！');

ini_set('include_path',ini_get('include_path').';'.LANGUAGE_PATH.';'.CONFIG_PATH.';'.LIBS_PATH.';'.TOOLS_PATH);
// ini_set('include_path',ini_get('include_path').';'.CONFIG_PATH);
// ini_set('include_path',ini_get('include_path').';'.LIBS_PATH);
// ini_set('include_path',ini_get('include_path').';'.TOOLS_PATH);
// echo ini_get('include_path');die;

#脚本计时开始
$mtime = explode(' ', microtime());
$script_starttime = (float)$mtime[1] + (float)$mtime[0];

$gaTools = array();
//$gaTools['mysqldb'] = new SaeMysql();

class Loader
{
    /**
     * 自动加载类
     * @param $class 类名
     */
    public static function autoload($class)
    {
        $path = '';
        $path = str_replace('__', '/', $class) . '.php';
        include_once($path);
    }
}

/**
 * sql自动加载
 */
spl_autoload_register(array('Loader', 'autoload'));

//加载核心文件
require_once SYSTEM_PATH.'heart'.EXT;
/*
//计算占用内存、运行时间
$mtime = explode(' ', microtime());
$script_endtime = (float)$mtime[1] + (float)$mtime[0];
$run_time = $script_endtime-$script_starttime;
$day = floor($run_time/(24*60*60));//天
$hour = floor(($run_time-$day*24*60*60)/(60*60));//小时
$min = floor(($run_time-$day*24*60*60-$hour*60*60)/60);//分
$sec = $run_time-$day*24*60*60-$hour*60*60-$min*60;//秒
if ($day) {
    $run_time = $day.'d'.$hour.'h'.$min.'m'.$sec.'s';
} elseif ($hour) {
    $run_time = $hour.'h'.$min.'m'.$sec.'s';
} elseif ($min) {
    $run_time = $min.'m'.$sec.'s';
} else {
    $run_time = $sec.'s';
}
$run_memory = memory_get_usage()/1024/1024;
$run_date = date('Y-m-d H:i:s');
$run_info = sprintf("%s\n  runtime:%s \n  memory usage: %01.2f MB\n", $run_date, $run_time, $run_memory);
$run_info_filename = substr($router->get_space(),0,-1) . '@' . $router->get_action() . '.log';
$gaTools['log_email']->write_log($run_info,LOGS_PATH,'',$run_info_filename);
*/