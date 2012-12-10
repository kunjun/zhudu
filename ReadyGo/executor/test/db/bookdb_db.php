<?php  if ( ! defined('ROOT_PATH')) exit('非法访问！！！');
/********************************************************
 Author: BurtonQ
Version: 1
 Effect: 
   Date: 
  Notes: 
********************************************************/
class bookdb_db
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
            foreach ($v as $v_v) {
            	if ($this->gaTools['mysqldb']->load('bookdb',$v_v['name'],'name')) {
            		continue;
            	}
                $row = array();
                $row['no'] = $v_v['tmp_no'];
                $row['book_home_link'] = $v_v['book_home_link'];
                $row['book_new_link'] = $v_v['book_new_link'];
                $row['category'] = $v_v['category'];
                $row['name'] = $v_v['name'];
                $row['words'] = $v_v['words'];
                $row['clicks'] = (int)$v_v['clicks'];
                $row['author'] = $v_v['author'];
                $row['uptime'] = $v_v['uptime'];
                $now = time();
                $row['created_at'] = $now;
                $row['updated_at'] = $now;
                $this->gaTools['mysqldb']->save('bookdb',$row);
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
