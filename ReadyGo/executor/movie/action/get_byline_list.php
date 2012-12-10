<?php  if ( ! defined('ROOT_PATH')) exit('非法访问！！！');
/********************************************************
 Author: BurtonQ
Version: 1
 Effect: 
   Date: 
  Notes: 
********************************************************/
class get_byline_list
{
	private $aConfig = array();
	private $aCommConfiger = array();
	private $gaTools = array();
	private $aaGatherHandleError = array();
	private $aParameter = array();
	private $aScriptNeed = array();
	function __construct()
	{
		$this->aCommConfiger = &get_config('configer');
		$this->aParameter = get_parameter();
		global $gaTools;
		$this->gaTools = $gaTools;
		$this->set_config();
		//出错处理需要的参数
		$this->aaGatherHandleError['error_template']['con'] = $this->aConfig['error_template']['con'];
		$this->aaGatherHandleError['error_template']['search_replace'] = $this->aConfig['error_template']['search_replace'];
		$this->aaGatherHandleError['log_email'] = $this->gaTools['log_email'];
		$this->aaGatherHandleError['logs_path'] = LOGS_PATH;
	}
	
	function execute()
	{
		$this->aScriptNeed['charset_of_getcon'] = 'UTF-8';
		if (!isset($this->aParameter['p'])) {
			echo "<a href='index.php?space=bus_in_time&p=h'>back</a><br /><br />";
			echo "<a href='index.php?space=bus_in_time&p=l'>gowork</a><br /><br />";
			die;
		}
		if ($this->aParameter['p'] == 'h') {
			$this->aScriptNeed['url_1'] = 'http://m.5320000.com/index.php/Home/Line/nextbus/lid/143/r/0/sid/2193/i/25/rand/ca77';
		} else if ($this->aParameter['p'] == 'l') {
			$this->aScriptNeed['url_1'] = 'http://m.5320000.com/index.php/Home/Line/nextbus/lid/143/r/0/sid/1424/i/7';
		}
		$aCollectionResult = $this->filter();
		//dump($aCollectionResult);
		//die;
		#响应
		$responser = &load_class('responser');
		$responser->execute($aCollectionResult);
	}
	
	private function filter()
	{
		$aCollectionResult = null;
		$sUrl = $this->aScriptNeed['url_1'];
		$sCon = meclient($sUrl);
		$domCon = str_get_html($sCon);
		$domTmp = $domCon->find(".cmode font",0);
		if (empty($domTmp)) {
			return false;
		}
		$sBusLine = remove_invalid(iconv($this->aScriptNeed['charset_of_getcon'],DEFAULT_CHARSET_ICONV,$domTmp->plaintext));
		$domTmp = $domCon->find(".cmode .tl font",0);
		if (empty($domTmp)) {
			return false;
		}
		$sStation = remove_invalid(iconv($this->aScriptNeed['charset_of_getcon'],DEFAULT_CHARSET_ICONV,$domTmp->plaintext));
		$domTmp = $domCon->find(".cmode",0);
		if (empty($domTmp)) {
			return false;
		}
		$sBusInTime = '资讯又未知了-.-';
		$sTmp = remove_invalid(iconv($this->aScriptNeed['charset_of_getcon'],DEFAULT_CHARSET_ICONV,$domTmp->plaintext));
		if (preg_match('/路还差(.*?)公里到站/',$sTmp,$aTmp)) {
			$sBusInTime = '还差' . $aTmp[1] . '公里到站';
		}
		$domTmp = $domCon->find(".cmode img[alt=请稍候...]",0);
		if (empty($domTmp)) {
			return false;
		}
		$sBusInTimeImg = 'http://m.5320000.com'.$domTmp->src;
		$domCon->__destruct();
		$aCollectionResult['BusLine'] = $sBusLine;
		$aCollectionResult['Station'] = $sStation;
		$aCollectionResult['BusInTime'] = $sBusInTime;
		$aCollectionResult['BusInTimeImg'] = $sBusInTimeImg;
		return $aCollectionResult;
	}
	
	function get_config()
	{
		return $this->aConfig;
	}
	private function set_config()
	{
		#采集出错信息模板
		$this->aConfig['error_template']['con'] = '采集“@sName”“@sGoUrl”出错，在脚本：“@sCfile”，的第“@sCline”行附近';
		$this->aConfig['error_template']['search_replace'] = array('@sName','@sGoUrl','@sCfile','@sCline');
		
		$this->aConfig['html_charset'] = 'gbk';
		
		$this->aConfig['have'] = &get_config('citys_info');
	}
	
	function __destruct()
	{
		
	}
}
