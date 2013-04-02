<?php  if ( ! defined('ROOT_PATH')) exit('非法访问！！！');
/********************************************************
 Author: BurtonQ
Version: 1
 Effect: 
   Date: 
  Notes: 
********************************************************/
class movie_tmp_tool_db
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
            $aTmp = $this->gaTools['mysqldb']->get('SELECT * FROM '.$this->aConfig['tb_name'] . ' WHERE p=' . $v['p'] . ' AND color='.$v['color']);
            $row = array();
            if ($aTmp) {
                $row['id'] = $aTmp['id'];
                $row['content'] = $v['content'];
                $this->gaTools['mysqldb']->update($this->aConfig['tb_name'],$row);
            } else {
                $row['content'] = $v['content'];
                $row['color'] = $v['color'];
                $row['p'] = $v['p'];
                $this->gaTools['mysqldb']->save($this->aConfig['tb_name'],$row);
            }
        }
        return true;
    }
    
    function save_data2($waData=null)
    {
        if (empty($waData)) {
            return false;
        }
        $flag = true;
        $aR = array();
        foreach ($waData as $k=>$v) {
            $this->gaTools['mysqldb']->escape_row($v);
            if (!isset($v['title']) || $v['title'] == '') {
                $aR[] = $v;
                continue;
            }
            $aTmp = $this->gaTools['mysqldb']->get('SELECT * FROM '.$this->aConfig['tb_name_0'] . ' WHERE title="' . $v['title'] . '" AND color="'.$v['color'].'"');
            $row = array();
            if ($aTmp) {
                $v['id'] = $aTmp['id'];
                $row = $v;
                $this->gaTools['mysqldb']->update($this->aConfig['tb_name_0'],$row);
            } else {
                $row = $v;
                $this->gaTools['mysqldb']->save($this->aConfig['tb_name_0'],$row);
            }
        }
        if (!empty($aR)) {
            return $aR;
        } else {
            return true;
        }
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
        
        $this->aConfig['tb_name'] = 'mtime_tmp';
        $this->aConfig['tb_name_0'] = 'movie';
    }
    
    function __destruct()
    {
        
    }
}
