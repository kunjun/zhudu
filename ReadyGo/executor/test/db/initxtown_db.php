<?php  if ( ! defined('ROOT_PATH')) exit('非法访问！！！');
/********************************************************
 Author: BurtonQ
Version: 1
 Effect: 
   Date: 
  Notes: 
********************************************************/
class initxtown_db
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
//        dump($waData);
        foreach ($waData as $k=>$v) {
        	$aTmp = $this->gaTools['mysqldb']->load('tbl_post',$v['idsoo_id'],'idsoo_id');
        	if ($aTmp && $aTmp['photos']) {
        		continue;
        	}
            $row = array();
			if ($aTmp) {
				$row['id'] = $aTmp['id'];
				$row['photos'] = $v['photos'];
				$row['content'] = $v['content'];
				$this->gaTools['mysqldb']->update('tbl_post',$row);
			} else {
				$row['idsoo_id'] = $v['idsoo_id'];
				$row['content'] = $v['content'];
				$row['photos'] = $v['photos'];
				$row['create_time'] = $v['create_time'];
				$row['update_time'] = $v['create_time'];
				$row['list_height'] = $v['list_height'];
				$row['status'] = 2;
				$row['tags'] = "初始化";
				$row['author_id'] = 1;
				$row['origin'] = 1;
				$this->gaTools['mysqldb']->save('tbl_post',$row);
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
