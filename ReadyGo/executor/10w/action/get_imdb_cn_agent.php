<?php  if ( ! defined('ROOT_PATH')) exit('非法访问！！！');
/********************************************************
 Author: NIGG
Version: 1
 Effect: 
   Date: 
  Notes: ?space=10w&action=get_fuli&do=always&end=1
********************************************************/
class get_imdb_cn_agent
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
//         $aArgv = $GLOBALS['argv'];
//         $this->aParameter['start0'] = isset($aArgv[5]) ? $aArgv[5] : '1';
//         $this->aParameter['end'] = isset($aArgv[6]) ? $aArgv[6] : '97191';
        
        if (!$this->aParameter['do']) {
            $aArgv = $GLOBALS['argv'];
            $this->aParameter['start0'] = isset($aArgv[5]) ? $aArgv[5] : '2005';
            $this->aParameter['end'] = isset($aArgv[6]) ? $aArgv[6] : '2013';
            $this->aParameter['p_start'] = isset($aArgv[7]) ? $aArgv[7] : '1';
            $this->aParameter['cache_file'] = isset($aArgv[8]) ? $aArgv[8] : 'cache_file';
            $this->aParameter['p_end'] = isset($aArgv[9]) ? $aArgv[9] : '';
        }
        if ($this->aParameter['start0'] < 1892) {
            $this->aParameter['start0'] = 1892;
        }
        if ($this->aParameter['end'] > 2015) {
            $this->aParameter['end'] = 2015;
        }
//         dump($this->aParameter);
//         die;
        create_dir(CACHES_PATH);
        $cache_file = CACHES_PATH.$this->aParameter['cache_file'].'.txt';
        
        $this->aScriptNeed['charset_of_getcon'] = 'gbk';
        
        $aTmp = $this->gaTools['mysqldb']->get('SELECT * FROM `imdb_cn` WHERE year='.$this->aParameter['start0'].' ORDER BY `imdb_cn`.`p`  DESC');
//         dump($aTmp);
//         die;
        $year_start = $this->aParameter['start0'];
        $p_start = isset($aTmp['p']) && trim($aTmp['p']) ? ($aTmp['p']+1) : $this->aParameter['p_start'];
        $p_end = '';
//         $cache_str = file_get_contents($cache_file);
//         $arr = split(" ",$cache_str);
//         $year_start = isset($arr[0]) && trim($arr[0]) ? $arr[0] : $this->aParameter['start0'];
//         $p_start = isset($arr[1]) && trim($arr[1]) ? $arr[1] : $this->aParameter['p_start'];
//         $p_end = isset($arr[2]) && trim($arr[2]) ? $arr[2] : $this->aParameter['p_end'];
//         $p_start = 7;
        $url = remove_blank($this->aConfig['url_0_template']['con']);
        $go = true;
        $year = $year_start;
        $p = $p_start;
        for ($i = $year_start; $i <= $this->aParameter['end']; $i++) 
        {
            $year = $i;
            show_msg("===============================<br />\r\n");
            show_msg("year=".$i."::p=".$p_start."<br />\r\n");
            if (!$go) {
                show_msg("被封！小退！<br />\r\n");
                break;
            }
            $this->aScriptNeed['url_url_0'] = str_replace($this->aConfig['url_0_template']['search_replace'], array($i,$p_start), $url);
            $sUrl = $this->aScriptNeed['url_url_0'];
            $sCon = meclient($sUrl);
            $sCon = iconv($this->aConfig['html_charset'],DEFAULT_CHARSET.'//IGNORE',$sCon);
            $domCon = str_get_html($sCon);
            $dom = $domCon->find('.nextprev',0);
            if ($dom) {
                if (preg_match("/\d+/",$dom->plaintext,$arr)) {
                    $total = $arr[0];
                    if ($p_end == '') {
                        $p_end = ceil($total/30);
                    }
                    if (!$p_end) {
                        $p_end = 1;
                    }
                    show_msg("--------总条数：".$total."/总页数：".$p_end."<br />\r\n");
                    if ($p_end < $p_start) {
                        $p_start = 1;
                        if ($go) {
                            $p_end = '';
                        }
                        continue;
                    }
                    $dom = $domCon->find('#sections');
                    if ($dom) {
                        foreach($dom as $v) {
                            $domTmp = $v->find('td',0);
                            if ($domTmp) {
                                $domTmp = $domTmp->find('a',0);
                                if (preg_match("/(tt\d+)/",$domTmp->href,$arr)) {
                                    $imdb_id = $arr[1];
                                    $imdb_title = '';
                                    $domTmp = $domTmp->find('img',0);
                                    if ($domTmp) {
                                        $imdb_title = $domTmp->alt;
                                    }
                                    $row = array();
                                    $row['year'] = $i;
                                    $row['p'] = $p_start;
                                    $row['imdb_id'] = $imdb_id;
                                    $row['imdb_title'] = $imdb_title;
                                    $this->save($row);
                                }
                            }
                        }
                    }
                    $domCon->__destruct();
                    for ($j = ($p_start+1); $j <= $p_end; $j++) 
                    {
                        $p = $j;
                        show_msg("year=".$i."::p=".$j."<br />\r\n");
                        $this->aScriptNeed['url_url_0'] = str_replace($this->aConfig['url_0_template']['search_replace'], array($i,$j), $url);
                        $sUrl = $this->aScriptNeed['url_url_0'];
                        $sCon = meclient($sUrl);
                        $sCon = iconv($this->aConfig['html_charset'],DEFAULT_CHARSET.'//IGNORE',$sCon);
                        $domCon = str_get_html($sCon);
                        $dom = $domCon->find('#sections');
                        if ($dom) {
                            foreach($dom as $v) {
                                $domTmp = $v->find('td',0);
                                if ($domTmp) {
                                    $domTmp = $domTmp->find('a',0);
                                    if (preg_match("/(tt\d+)/",$domTmp->href,$arr)) {
                                        $imdb_id = $arr[1];
                                        $imdb_title = '';
                                        $domTmp = $domTmp->find('img',0);
                                        if ($domTmp) {
                                            $imdb_title = $domTmp->alt;
                                        }
                                        $row = array();
                                        $row['year'] = $i;
                                        $row['p'] = $j;
                                        $row['imdb_id'] = $imdb_id;
                                        $row['imdb_title'] = $imdb_title;
                                        $this->save($row);
                                    }
                                }
                            }
                            $domCon->__destruct();
                        } else {
                            $domCon->__destruct();
                            $go = false;
                            break;
                        }
                    }
                }
            }
            $p_start = 1;
            if ($go) {
                $p_end = '';
            }
        }
        if (!$go) {
            show_msg("保存状态！<br />\r\n");
            $cache_str = $year." ".$p." ".$p_end;
            file_put_contents($cache_file,$cache_str);
            die;
        }
        
        show_msg("-----------------------ok!!!<br />\r\n");
        die;
        
    }
    
    private function save(&$row)
    {
//         dump($row);
        $this->gaTools['mysqldb']->escape_row($row);
        $aTmp = $this->gaTools['mysqldb']->get('SELECT * FROM '.$this->aConfig['tb_name_0'] . ' WHERE imdb_id="' . $row['imdb_id'] . '"');
        if ($aTmp) {
            $row['id'] = $aTmp['id'];
            $this->gaTools['mysqldb']->update($this->aConfig['tb_name_0'],$row);
        } else {
            $this->gaTools['mysqldb']->save($this->aConfig['tb_name_0'],$row);
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
        $this->aConfig['tb_name_0'] = 'imdb_cn';
        
        $this->aConfig['url_0_template']['con'] = 'http://www.imdb.cn/Sections/Years/{@year}/{@p}';
        $this->aConfig['url_0_template']['search_replace'] = array('{@year}','{@p}');
        $this->aConfig['url_0']['p_total'] = 46;

        
        
        $this->aCommConfiger['url_url'] = 'url';
    }
    
    function __destruct()
    {
        
    }
}
