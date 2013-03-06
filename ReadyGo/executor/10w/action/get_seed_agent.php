<?php  if ( ! defined('ROOT_PATH')) exit('非法访问！！！');
/********************************************************
 Author: NIGG
Version: 1
 Effect: 
   Date: 
  Notes: ?space=10w&action=get_seed&do=always&end=1
  php ../index.php 10w get_seed 1 gbk 0 10
********************************************************/
class get_seed_agent
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
        $this->aParameter['start0'] = isset($aArgv[5]) ? $aArgv[5] : $this->aParameter['start0'];
        $this->aParameter['end'] = isset($aArgv[6]) ? $aArgv[6] : $this->aParameter['end'];
        $this->aParameter['apikey_index'] = isset($aArgv[7]) ? $aArgv[7] : '0';
        $this->aParameter['url_template_index'] = isset($aArgv[8]) ? $aArgv[8] : '0';
//         dump($this->aParameter);
        
        $apikey = isset($this->aConfig['apikey'][$this->aParameter['apikey_index']]) ? $this->aConfig['apikey'][$this->aParameter['apikey_index']] : '';
        $url_template_index = $this->aParameter['url_template_index'];
        $tb_name = $this->aConfig['tb_name'][$url_template_index];

        $this->aScriptNeed['charset_of_getcon'] = 'UTF-8';
        
        show_msg("开始获取豆瓣电影数据.......<br />\r\n");
        // $where = ' WHERE updated_at>1357315200 AND updated_at<1357729200 AND imdb_id="" ';
        $where = ' WHERE 1 ';
        if ($this->aParameter['start0'] != '') {
            $where .= ' AND id>='.$this->aParameter['start0'];
        }
        if ($this->aParameter['end'] != '') {
            $where .= ' AND id<'.$this->aParameter['end'];
        }
        $plan = 2;
        //SELECT * FROM `movie` WHERE imdb_id="" AND updated_at>1357315200
        $aRowDb = $this->gaTools['mysqldb']->find('SELECT * FROM '.$tb_name.' '.$where.' AND plan<'.$plan.' ORDER BY id ASC');
        //$aTmp = $this->gaTools['mysqldb']->find('SELECT * FROM movie '.$where.' ORDER BY id ASC');
        $id = isset($aRowDb[0]['id']) ? $aRowDb[0]['id'] : '';
        show_msg("从id=“{$id}”开始.......<br />\r\n");
        // dump($aTmp);
        // die;
        // echo count($aTmp);
        // die;

        if (!empty($aRowDb)) {
            $aCollectionResult = array();
            foreach ($aRowDb as $k => $v) {
                show_msg("id={$v['id']}.......");
                $row2db = array();
                $row2db['mid'] = $v['mid'];
                $row2db['created_at'] = time();

                $url = $v['url'];
                $sUrl = $url;
                // die($sUrl);
                $content = meclient($sUrl);
                $domCon = str_get_html($content);
//                 var_dump(stripos($url, 'http://www.icili.com/download/xiazai/'));
                if (stripos($url, 'http://www.icili.com/download/xiazai/') !== false) {
                    //磁力
                    $row2db['category'] = 1;
//                     echo $domCon;
//                     die;
                    $dom = $domCon->find("#magnet_url",0);
                    if ($dom) {
                        $row2db['url'] = html_entity_decode($dom->plaintext);
                        $arr = parse_url($row2db['url']);
                        if (isset($arr['query']) && $arr['query']) {
                            parse_str($arr['query'], $aTmp);
                            $row2db['name'] = isset($aTmp['dn']) ? $aTmp['dn'] : "";
                            if (isset($aTmp['xt']) && $aTmp['xt']) {
                                $row2db['md5'] = str_replace('urn:btih:', '', $aTmp['xt']);
                            }
//                             echo date("Y-m-d H:i:s", $aTmp['xl']);
                        }
                    }
//                     echo urldecode('Kung.Fu.Hustle.2004.RETAiL.DVDRip.XviD-TLF.%5BUsaBit.com%5D');
                    $dom = $domCon->find(".fileInfo");
                    if ($dom) foreach ($dom as $k1 => $v1) {
                        $con = remove_invalid($v1->plaintext);
                        if (stripos($con, '文件更新时间：') !== false) {
                            $str = str_replace('文件更新时间：', '', $con);
                            $row2db['updated_at'] = strtotime(trim($str));
//                             echo date("Y-m-d H:i:s", $row2db['updated_at']);
                        } else if (stripos($con, '下载文件大小：') !== false) {
                            $str = str_replace('下载文件大小：', '', $con);
                            $row2db['size'] = trim($str);
                        }
                    }
                } else if (stripos($url, 'http://www.icili.com/emule/download/') !== false) {
                    //电驴
                    $row2db['category'] = 2;
                    $row2db['ed2k'] = array();
                    $dom = $domCon->find("#main .info p");
                    if ($dom) foreach ($dom as $k1 => $v1) {
                        $con = remove_invalid($v1->plaintext);
                        if (stripos($con, '发布时间：') !== false) {
                            $str = str_replace('发布时间：', '', $con);
                            $row2db['updated_at'] = strtotime(trim($str));
//                             echo date("Y-m-d H:i:s", $row2db['updated_at']);
//                             die;
                        }
                    }
                    $dom = $domCon->find("#main h1[title=精华资源]", 0);
                    if ($dom) {
                        $row2db['name'] = remove_invalid($v1->plaintext);
                    }
                    $dom = $domCon->find("#emuleFile tr");
                    if ($dom) {
                        $c = count($dom);
                        $aOut = array(0,$c-1);
                        foreach ($dom as $k1 => $v1) {
                            if (!in_array($k1, $aOut)) {
                                
                                $domTmp = $v1->find("td");
                                if ($domTmp) {
                                    $aTmp = array();
                                    if (isset($domTmp[1])) {
                                        $domTmp1 = $domTmp[1]->find("a", 0);
                                        if ($domTmp1) {
                                            $aTmp['url'] = $domTmp1->href;
                                            $aTmp['name'] = $domTmp1->title;
                                        }
                                    }
                                    if (isset($domTmp[2])) {
                                        $aTmp['size'] = remove_invalid($domTmp[2]->plaintext);
                                    }
                                    $row2db['ed2k'][] = $aTmp;
                                }
                            }
                        }
                    }
                    $row2db['size'] = count($row2db['ed2k']);
                    
                } else if (stripos($url, 'http://www.torrentkitty.com/information/') !== false) {
                    //磁力
                    $row2db['category'] = 1;
                }
                
                dump($v);
                dump($row2db);
                die;
            }
        }
        
    }
    
    private function filter()
    {
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
        $this->aConfig['tb_name'][0] = 'from_google';
        $this->aConfig['tb_name'][1] = 'seed';

        $this->aConfig['apikey'] = array('06953f4549257b8213bfbdcfdb707286','005d6d7a8e4d9fdd0118486faae8df97', '014a0ae8b9bca19803de20ba2d35e4c8');
        
        // $this->aConfig['url_0_template']['con'] = 'http://api.douban.com/movie/subject/{@douban_id}?apikey={06953f4549257b8213bfbdcfdb707286}';
        $this->aConfig['url_template'][0]['con'] = 'http://api.douban.com/movie/subject/{@douban_id}?apikey={@apikey}';
        $this->aConfig['url_template'][0]['search_replace'] = array('{@douban_id}','{@apikey}');
        $this->aConfig['url_template'][1]['con'] = 'http://api.douban.com/movie/subject/imdb/{@imdb_id}?apikey={@apikey}';
        $this->aConfig['url_template'][1]['search_replace'] = array('{@imdb_id}','{@apikey}');
        
        $this->aCommConfiger['url_url'] = 'url';
    }
    
    function __destruct()
    {
        
    }
}
