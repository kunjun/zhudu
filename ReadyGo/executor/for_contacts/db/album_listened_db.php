<?php  if ( ! defined('ROOT_PATH')) exit('非法访问！！！');
/********************************************************
 Author: BurtonQ
Version: 1
 Effect: 
   Date: 
  Notes: 
********************************************************/
class album_listened_db
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
        die('ddddddddddd');
    }
    
    function save_data($waData=null)
    {
        if (empty($waData)) {
            return false;
        }
        foreach ($waData as $k=>$v) {
        	$aTmp = $this->gaTools['mysqldb']->load('album_listened',$v['title'],'title');
        	if ($aTmp && $aTmp['cover']) {
        		continue;
        	}
            $row = array();
			$this->gaTools['mysqldb']->ping();//经过前面的网页抓取后，或者会导致数据库连接关闭,检查并重新连接
			if ($aTmp) {
				$row['id'] = $aTmp['id'];
				$row['cover'] = $v['cover'];
				$this->gaTools['mysqldb']->update('album_listened',$row);
			} else {
				$row['title'] = $v['title'];
				$row['author'] = $v['author'];
				$row['year'] = $v['year'];
				$row['style'] = $v['style'];
				$row['company'] = $v['company'];
				$row['description'] = $v['description'];
				$row['cover'] = $v['cover'];
				$row['cover_uri'] = $v['cover_uri'];
				$row['to_internet'] = $v['to_internet'];
				$row['created_at'] = $v['created_at'];
				$this->gaTools['mysqldb']->save('album_listened',$row);
			}
        }
        return true;
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
        
    }
    
    function __destruct()
    {
        
    }
}
