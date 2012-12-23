<?php  if ( ! defined('ROOT_PATH')) exit('非法访问！！！');
/********************************************************
 Author: NIGG
Version: 1
 Effect: 
   Date: 
  Notes: ?space=10w&action=get_fuli&do=always&end=1
********************************************************/
class u17_tool_agent
{
    private $aConfig = array();
    private $aCommConfiger = array();
    private $gaTools = array();
    private $aaGatherHandleError = array();
    private $aParameter = array();
    private $aScriptNeed = array();
    function __construct()
    {
        $this->aCommConfiger = &get_config(CONFIGER);
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
        ####################################################
        $this->aParameter['do'] = isset($this->aParameter['do']) ? $this->aParameter['do'] : '';
        $this->aParameter['start0'] = isset($this->aParameter['start0']) ? $this->aParameter['start0'] : 1;
        $this->aParameter['start1'] = isset($this->aParameter['start1']) ? $this->aParameter['start1'] : 1;
        $this->aParameter['end'] = isset($this->aParameter['end']) ? $this->aParameter['end'] : '';
        ####################################################
        $aArgv = $GLOBALS['argv'];
        $tags = $this->gaTools['mysqldb']->find("SELECT * FROM tag");
        if (!empty($tags)) {
            foreach ($tags as $v) {
                $tmp = $this->gaTools['mysqldb']->find("SELECT * FROM u17 WHERE tag LIKE '%".$v['name']."%'");
                $v['obj_total'] = count($tmp);
//                 dump($v);
                $this->gaTools['mysqldb']->update('tag',$v);
                if (!empty($tmp)) {
                    foreach ($tmp as $v_v) {
                        $row = array();
                        $row['tag_id'] = $v['id'];
                        $row['obj_id'] = $v_v['id'];
                        $this->save($row);
//                         die;
                    }
                }
            }
        }
        show_msg("-----------------------ok!!!<br />\r\n");
        die;
        
    }
    
    private function save(&$row)
    {
//         dump($row);
        $this->gaTools['mysqldb']->escape_row($row);
        $aTmp = $this->gaTools['mysqldb']->get('SELECT * FROM dongman_tag_relations WHERE tag_id=' . $row['tag_id'] . ' AND obj_id='.$row['obj_id']);
//         dump($aTmp);
        if (!$aTmp) {
//             $sql = "insert into `dongman_tag_relations` set tag_id=" . $row['tag_id'] . ", obj_id=".$row['obj_id'];
//             echo $sql;
//             $this->gaTools['mysqldb']->query($sql);
            $this->gaTools['mysqldb']->save('dongman_tag_relations',$row);
//             dump($row);
        }
    }
    
    private function filter()
    {
        $aCollectionResult = array();
        $sUrl = $this->aScriptNeed['url_url_0'];
        $sCon = meclient($sUrl);
        // $domCon = str_get_html($sCon);
//		dump($aCollectionResult);
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
        $this->aConfig['tb_name_0'] = '';
        
        $this->aConfig['url_0_template']['con'] = 'http://www.imdb.cn/Sections/Years/{@year}/{@p}';
        $this->aConfig['url_0_template']['search_replace'] = array('{@year}','{@p}');
        $this->aConfig['url_0']['p_total'] = 46;

        
        
        $this->aCommConfiger['url_url'] = 'url';
    }
    
    function __destruct()
    {
        
    }
}
