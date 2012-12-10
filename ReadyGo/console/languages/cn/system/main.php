<?php  if ( ! defined('BASEPATH')) exit('你乱来哦。果断退出。');
/*
系统语言包
*/
class system_language
{
	public $language = array();
	function __construct()
	{
		$this->language['load_module_fail'] = '加载模块失败！';
		$this->language['config_format_false'] = '你的配置文件似乎格式错误！';
		$this->language['autoload_calss_fail'] = '自动加载类库失败！';
		$this->language['load_calss_fail'] = '加载类库失败！';
		$this->language['load_tool_fail'] = '加载tool失败！';
		$this->language['load_controller_fail'] = '加载控制器失败！';
		$this->language['load_file_fail'] = '加载文件失败！';
		$this->language['moban_notexists'] = '模板不存在！';
	}
}