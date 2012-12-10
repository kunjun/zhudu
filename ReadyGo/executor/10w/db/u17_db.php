<?php  if ( ! defined('ROOT_PATH')) exit('非法访问！！！');
/********************************************************
 Author: BurtonQ
Version: 1
 Effect: 
   Date: 
  Notes: 
********************************************************/
class u17_db
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
        die('execute');
    }
    
    function save_data($waData=null)
    {
        if (empty($waData)) {
            return false;
        }
        foreach ($waData as $k=>$v) {
        	$this->gaTools['mysqldb']->escape_row($v);
        	$aTmp = $this->gaTools['mysqldb']->load($this->aConfig['tb_name'],$v['u17_id'],'u17_id');
            $row = array();
//			$this->gaTools['mysqldb']->ping();//经过前面的网页抓取后，或者会导致数据库连接关闭,检查并重新连接
			if ($aTmp) {
				$row['id'] = $aTmp['id'];
				$row['u17_id'] = $v['u17_id'];
				$row['cover'] = $v['cover'];
				$row['u17_author_id'] = $v['u17_author_id'];
				$row['cover'] = $v['cover'];
				$row['title'] = $v['title'];
				$row['url'] = $v['url'];
				$row['author'] = $v['author'];
				$row['author_url'] = $v['author_url'];
				$row['tag'] = $v['tag'];
				$row['renqi'] = $v['renqi'];
				$row['yuepiao'] = $v['yuepiao'];
				$row['shoucang'] = $v['shoucang'];
				$row['summary'] = $v['summary'];
				$row['update_time'] = $v['update_time'];
				$row['updated_at'] = $v['updated_at'];
				$this->gaTools['mysqldb']->update($this->aConfig['tb_name'],$row);
			} else {
				$row['u17_id'] = $v['u17_id'];
				$row['u17_author_id'] = $v['u17_author_id'];
				$row['cover'] = $v['cover'];
				$row['title'] = $v['title'];
				$row['url'] = $v['url'];
				$row['author'] = $v['author'];
				$row['author_url'] = $v['author_url'];
				$row['tag'] = $v['tag'];
				$row['renqi'] = $v['renqi'];
				$row['yuepiao'] = $v['yuepiao'];
				$row['shoucang'] = $v['shoucang'];
				$row['summary'] = $v['summary'];
				$row['update_time'] = $v['update_time'];
				$row['updated_at'] = $row['created_at'] = $v['updated_at'] = $v['created_at'];
				$this->gaTools['mysqldb']->save($this->aConfig['tb_name'],$row);
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
        
        $this->aConfig['tb_name'] = 'u17';
    }
    
    function __destruct()
    {
        
    }
}
