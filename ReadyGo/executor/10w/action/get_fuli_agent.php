<?php  if ( ! defined('ROOT_PATH')) exit('非法访问！！！');
/********************************************************
 Author: NIGG
Version: 1
 Effect: 
   Date: 
  Notes: ?space=10w&action=get_fuli&do=always&end=1
********************************************************/
class get_fuli_agent
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
            $this->aParameter['start0'] = isset($aArgv[5]) ? $aArgv[5] : '1';
            $this->aParameter['end'] = isset($aArgv[6]) ? $aArgv[6] : '1822';
        }
//         dump($this->aParameter);
        
        $this->aScriptNeed['charset_of_getcon'] = 'gbk';
//         $aTmp = $this->gaTools['mysqldb']->get('SELECT fid FROM fuli_items_tmp WHERE content!="" AND fid<=752 ORDER BY fid DESC');
//         $fid = isset($aTmp['fid']) ? $aTmp['fid'] : '1';
//         echo $fid;
//         die;
        $aTmp = $this->gaTools['mysqldb']->find('SELECT * FROM fuli WHERE is_ok!=1 AND id>='.$this->aParameter['start0'].' AND id<'.$this->aParameter['end'].' ORDER BY id ASC');
        show_msg("开始保存写真数据.......<br />\r\n");
        if ($aTmp) {
            foreach ($aTmp as $k=>$v) {
                show_msg("{$v['id']}.......");
                $this->gaTools['mysqldb']->remove('fuli_items_tmp',$v['id'],'fid');
//                 dump($v);
                if ($v['url'] == '') {
                    continue;
                }
                $rootUrl = "";
                if (strripos($v['url'],"/") !== false) {
                    $rootUrl = substr($v['url'],0,strripos($v['url'],"/"))."/";
                } else {
                    continue;
                }
                $sCon = meclient($v['url']);
//                 show_msg(iconv($this->aConfig['html_charset'],DEFAULT_CHARSET.'//IGNORE',$sCon));
//                 die;
                if (!$sCon) {
//                     sleep(60);
//                     $sCon = meclient($v['url']);
                    if (!$sCon) {
                        show_msg("空！退出<br />\r\n");
                        die;
                    }
                }
                $row = array();
                $row['fid'] = $v['id'];
                $row['content'] = iconv($this->aConfig['html_charset'],DEFAULT_CHARSET.'//IGNORE',$sCon);
                $this->gaTools['mysqldb']->escape_row($row);
                $this->gaTools['mysqldb']->save('fuli_items_tmp',$row);
                $urls = array();
                $domCon = str_get_html($sCon);
                $dom = $domCon->find("#pagination .pages",1);
                if ($dom) {
                    $dom = $dom->find("a");
                    if ($dom) {
                        foreach ($dom as $k_k=>$v_v) {
                            if ($v_v->href != "#" && preg_match("/\d+/",$v_v->plaintext)) {
                                $urls[] = $rootUrl.$v_v->href;
                            }
                        }
                    }
                }
                if ($urls) {
                    foreach ($urls as $k_k=>$v_v) {
                        $sCon = meclient($v_v);
                        if (!$sCon) {
//                             sleep(60);
//                             $sCon = meclient($v_v);
                            if (!$sCon) {
                                show_msg("空！退出<br />\r\n");
                                die;
                            }
                        }
                        $row = array();
                        $row['fid'] = $v['id'];
                        $row['content'] = iconv($this->aConfig['html_charset'],DEFAULT_CHARSET.'//IGNORE',$sCon);
                        $this->gaTools['mysqldb']->escape_row($row);
                        $this->gaTools['mysqldb']->save('fuli_items_tmp',$row);
                    }
                }
                $domCon->__destruct();
                $rowTmp = array();
                $rowTmp['id'] = $v['id'];
                $rowTmp['is_ok'] = 1;
                $r = $this->gaTools['mysqldb']->update($this->aConfig['tb_name_1'],$rowTmp);
                show_msg("保存成功<br />\r\n");
            }
        }
        die;
        
        
        $url = remove_blank($this->aConfig['url_0_template']['con']);
        show_msg("开始保存写真数据.......<br />\r\n");
        $aTmp = $this->gaTools['mysqldb']->find('SELECT * FROM '.$this->aConfig['tb_name_0'].' ORDER BY p ASC');
        if ($aTmp) {
            foreach ($aTmp as $k=>$v) {
                $domCon = str_get_html($v['content']);
                $dom = $domCon->find(".t");
                if (!$dom) {
                    continue;
                }
                unset($dom[0]);
                foreach ($dom as $k_k=>$v_v) {
                    $row = array();
                    $row['p'] = $v['p'];
                    $row['updated_at'] = $row['created_at'] = time();
                    $domTmp = $v_v->children(1);
                    if ($domTmp) {
                        $row['title'] = $domTmp->title;
                        $row['url'] = "http://www.umei.cc".$domTmp->href;
                        $domTmp = $domTmp->find("img",0);
                        if ($domTmp) {
                            $row['index_img'] = $domTmp->src;
                        }
                    }
                    $this->gaTools['mysqldb']->save($this->aConfig['tb_name_1'],$row);
                    show_msg("“".$row['title']."”保存成功<br />\r\n");
                }
            }
        }
        
        
        die;
//         $p = isset($aTmp['p']) ? $aTmp['p'] : '1';
//         show_msg("从p=“{$p}”开始.......<br />\r\n");
        $url = remove_blank($this->aConfig['url_0_template']['con']);
        $aCollectionResult = array();
        for ($i = $this->aParameter['start0']; $i <= $this->aConfig['url_0']['p_total']; $i++) {
            show_msg("($i)<br />\r\n");
            $this->aScriptNeed['url_url_0'] = str_replace($this->aConfig['url_0_template']['search_replace'], $i, remove_blank($this->aConfig['url_0_template']['con']));
//             $tmp = $this->filter();
            $sCon = iconv($this->aConfig['html_charset'],OUT_CHARSET.'//IGNORE',meclient($this->aScriptNeed['url_url_0']));
            $domCon = str_get_html($sCon);
//             show_msg($sCon);
//             die;
            $row = array();
            $row['p'] = $i;
            $row['content'] = $domCon->find("#msy",0)->innertext;
//             if ($row['content'] == 'false') {
//                 show_msg("尼玛！！被封<br />\r\n");
//                 die;
//             }
// dump($row);die;
            $this->gaTools['mysqldb']->escape_row($row);
            $r = $this->gaTools['mysqldb']->save($this->aConfig['tb_name_0'],$row);
            if ($this->aParameter['end'] != '' && $i == $this->aParameter['end']) {
                break;
            }
//             die;
        }
        show_msg("-----------------------ok!!!<br />\r\n");
        die;
        
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
        $this->aConfig['tb_name_0'] = 'fuli_tmp';
        $this->aConfig['tb_name_1'] = 'fuli';
        
        $this->aConfig['url_0_template']['con'] = 'http://www.umei.cc/p/gaoqing/index-{@p}.htm';
        $this->aConfig['url_0_template']['search_replace'] = array('{@p}');
        $this->aConfig['url_0']['p_total'] = 46;

        
        
        $this->aCommConfiger['url_url'] = 'url';
    }
    
    function __destruct()
    {
        
    }
}
