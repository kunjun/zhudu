<?php  if ( ! defined('ROOT_PATH')) exit('非法访问！！！');
/********************************************************
 Author: BurtonQ
Version: 1
 Effect: 
   Date: 
  Notes: ?space=10w&action=movie_tmp_tool&do=always&end=1
  php ../index.php 10w pipei_movie_name_tool 1 gbk
********************************************************/
class pipei_movie_name_tool_agent
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

        require 'sphinxapi.php';

        $aArgv = $GLOBALS['argv'];
        $this->aParameter['start0'] = isset($aArgv[5]) ? $aArgv[5] : '1';
        $this->aParameter['end'] = isset($aArgv[6]) ? $aArgv[6] : '214296';

        //test
        if ($this->aParameter['start0'] == 'test') {
            $s = new SphinxClient();
            $s->SetServer('127.0.0.1', 3312);
            echo $this->aParameter['end'];
            $result = $s->Query($this->aParameter['end'],'movie_name');
            dump($result);
            die;
        }

        $aTmp = $this->gaTools['mysqldb']->find('SELECT id,title FROM online_video_tmp WHERE id>='.$this->aParameter['start0'].' AND id<'.$this->aParameter['end'].' ORDER BY id ASC');
        $id = isset($aTmp[0]['id']) ? $aTmp[0]['id'] : '';
        show_msg("从id=“{$id}”开始.......<br />\r\n");

        if (!empty($aTmp)) {
            $s = new SphinxClient();
            $s->SetServer('127.0.0.1', 3312);
            $s->SetLimits(0, 5);
            $aCollectionResult = array();
            foreach ($aTmp as $k => $v) {
                // if ($k > 10) {
                //     die;
                // }
                // if ($v['id'] != 22053) {
                //     continue;
                // }
                unset($aTmp[$k]);
                show_msg("id={$v['id']}..");
                show_msg("《{$v['title']}》..");
                // $v['title'] = '大灌篮';
                $title = $v['title'];
                if (preg_match("/(.*)\d$/", $v['title'], $tmp)) {
                    $title = $tmp[1];
                }
                $tmp = split(' ', $title);
                $guojia = '';
                if (count($tmp)> 1) {
                    if ($tmp[1] == '日版' || $tmp[1] == '日本版') {
                        $guojia = '日';
                    } else if ($tmp[1] == '韩版' || $tmp[1] == '韩国版') {
                        $guojia = '韩';
                    } else if ($tmp[1] == '美版' || $tmp[1] == '美国版') {
                        $guojia = '美';
                    } else if ($tmp[1] == '港版' || $tmp[1] == '香港版') {
                        $guojia = '港';
                    } else if ($tmp[1] == '内地版' || $tmp[1] == '大陆版' || $tmp[1] == '中国版') {
                        $guojia = '中国';
                    }
                }
                $title = $tmp[0];
                // dump($tmp);
                // die;
                // show_msg($title);
                $title = '死神来了';
                $result = $s->Query($title,'movie_name');
                dump($result['total']);
//                 die;
                if (isset($result['matches']) && !empty($result['matches'])) {
                    $i = 0;
                    $f_this_id = '';
                    $f_this_title = '';
                    $f_this_aka_cn = '';
                    $this_id = '';
                    $this_title = '';
                    $this_aka_cn = '';
                    foreach ($result['matches'] as $k_k=>$v_v) {
                        if ($i > 10) {
                            break;
                        }
                        $row = $this->gaTools['mysqldb']->load('movie', $k_k);
//                         if ($i == 0) {
//                             $f_this_id = $row['id'];
//                             $f_this_title = $row['title'];
//                             $f_this_aka_cn = $row['aka_cn'];
//                         }
//                         // show_msg($row['aka_cn'].'::'.$row['country'].'--'.$guojia);
//                         // var_dump(strpos($row['country'],$guojia));
//                         // continue;
//                         if ($guojia != '') {
//                             if (strpos($row['country'],$guojia) === false) {
//                                 continue;
//                             }
//                         }
//                         if ($row['title'] == $v['title'] || $row['aka_cn'] == $v['title']) {
//                             // show_msg($row['title'].'--'.$row['aka_cn']."<br />\r\n");
//                             $this_id = $row['id'];
//                             $this_title = $row['title'];
//                             $this_aka_cn = $row['aka_cn'];
//                             break;
//                         }
                        show_msg($row['title'].'--'.$row['aka_cn']."<br />\r\n");
                        $i++;
                    }
                    die;
                    if ($guojia != '') {
                        continue;
                    }
                    if ($this_id == '') {
                        $this_id = $f_this_id;
                        $this_title = $f_this_title;
                        $this_aka_cn = $f_this_aka_cn;
                    }
                    show_msg("《{$this_title}》..《{$this_aka_cn}》<br />\r\n");
                    $row = array();
                    $row['id'] = $this_id;
                    $row['is_ying'] = 1;
                    $row['weights'] = 2;
                    // dump($row);
                    $this->gaTools['mysqldb']->update('movie', $row);
                    $row = array();
                    $row['id'] = $v['id'];
                    $row['mid'] = $this_id;
                    $this->gaTools['mysqldb']->update('online_video', $row);
                    // dump($row);
                }
                show_msg("ok!!<br />\r\n");
            }
        }
        die;

        #响应
//		$responser = &load_class('responser');
//		$responser->execute($aCollectionResult);
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
        
        
    }
    
    function __destruct()
    {
        
    }
}
