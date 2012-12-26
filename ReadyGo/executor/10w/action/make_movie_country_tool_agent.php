<?php  if ( ! defined('ROOT_PATH')) exit('非法访问！！！');
/********************************************************
 Author: NIGG
Version: 1
 Effect: 
   Date: 
  Notes: ?space=10w&action=get_fuli&do=always&end=1
********************************************************/
class make_movie_country_tool_agent
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
        $arr = $this->gaTools['mysqldb']->find("select * from movie order by id asc");
//         dump($bads[0]);
//         die;
        if (!empty($arr)) {
            $js = 0;
            foreach ($arr as $v) {
                $items = split(" / ", $v['country']);
                if ($items) {
                    foreach ($items as $vv) {
                        $row = array();
                        $row['name'] = trim($vv);
                        if (!$row['name']) {
                            continue;
                        }
                        $this->save($row,$v['id']);
                        $js++;
                        show_msg(".");
                        if (!($js%100)) {
                            show_msg("<br />\r\n");
                        }
                    }
                }
            }
            show_msg("<br />\r\n");
        }
        show_msg("-----------------------ok!!!<br />\r\n");
        die;
        
    }
    
    private function save(&$row,$obj_id)
    {
        $this->gaTools['mysqldb']->escape_row($row);
        $tmp = $this->gaTools['mysqldb']->load("movie_country", $row['name'] , "name");
        if (!$tmp) {
            $this->gaTools['mysqldb']->save('movie_country',$row);
        } else {
            $row['id'] = $tmp['id'];
        }
        $aTmp = $this->gaTools['mysqldb']->get('SELECT * FROM movie_country_relations WHERE movie_country_id=' . $row['id'] . ' AND obj_id='.$obj_id);
        if (!$aTmp) {
            $row2 = array();
            $row2['movie_country_id'] = $row['id'];
            $row2['obj_id'] = $obj_id;
            $this->gaTools['mysqldb']->save('movie_country_relations',$row2);
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
