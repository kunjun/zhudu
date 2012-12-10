<?php  if ( ! defined('ROOT_PATH')) exit('非法访问！！！');
define('VERSION',	'1.0.0');

/*
 系统用的公共函数
 */
require_once(SYSTEM_PATH.'common'.EXT);

if ( ! is_php('5.3'))
{
	@set_magic_quotes_runtime(0); // Kill magic quotes
}

//加载配置文件
$configer2 = CONFIGER;
$$configer2 = &get_config(CONFIGER);
$tmpConfiger = $$configer2;
$space = get_or_post($tmpConfiger['url_space']);
if (!$space) {
	$aArgv = isset($GLOBALS['argv'])?$GLOBALS['argv']:'';
	$space = isset($aArgv[$tmpConfiger['argv_space']]) ? $aArgv[$tmpConfiger['argv_space']] : '';
}
if ($space) {
	$app_configer_loction = APP_PATH . $space . '/' . CONFIG_FOLDER . '/' . CONFIGER . EXT;
	if (file_exists($app_configer_loction)) {
		require_once($app_configer_loction);
	}
}

if (!empty($configer['tools'])) {
	global $gaTools;
	foreach ($configer['tools'] as $v) {
		//如果没指定工具脚本名则跳下一个
		if (!isset($v[0]) && $v[0] == '') {
			continue;
		}
		if (isset($v[1]) && $v[1] != '') {
			$index_space = $v[1].PATH_CUT.$v[0];
		} else {
			$index_space = $v[0];
		}
		$space = isset($v[1]) ? $v[1] : '';
		$isclass = isset($v[2]) ? $v[2] : false;
		$aP = isset($v[3]) ? $v[3] : null;
		$gaTools[$index_space] = &load_tool($v[0],$space,$isclass,$aP);
	}
}

/*
 init
 */
require_once(SYSTEM_PATH.'init'.EXT);

#路由
$router = &load_class('router');
$router->execute();