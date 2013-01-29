<?php  if ( ! defined('ROOT_PATH')) exit('非法访问！！！');
/********************************************************
 Author: BurtonQ
Version: 1
 Effect: 
   Date: 
  Notes: ?space=10w&action=scan_seed_in_google&do=always&end=1
  php ../index.php 10w scan_seed_in_google 1 gbk
********************************************************/
class scan_seed_in_google_agent
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
        $this->aParameter['start0'] = isset($aArgv[5]) ? $aArgv[5] : '1';
        // $this->aParameter['end'] = isset($aArgv[6]) ? $aArgv[6] : '214296';
        $this->aParameter['end'] = isset($aArgv[6]) ? $aArgv[6] : '3';
        // $this->aParameter['start0'] = isset($aArgv[5]) ? $aArgv[5] : '109362';
        // $this->aParameter['end'] = isset($aArgv[6]) ? $aArgv[6] : '109363';
        // and id>='.$this->aParameter['start0'].' AND id<'.$this->aParameter['end'].'
        $aTmp = $this->gaTools['mysqldb']->find('SELECT id,title,aka_cn,director,cast,country,year,imdb_id,weights,updated_at,plan FROM movie WHERE id=1 ORDER BY id ASC');
        $id = isset($aTmp[0]['id']) ? $aTmp[0]['id'] : '';
        show_msg("从id=“{$id}”开始.......<br />\r\n");
        // echo base64_decode('AFQjCNGg8PrGh-CR94WvZFZUadQimpSzqA');
        // die;

        if (!empty($aTmp)) {
            $url = remove_blank($this->aConfig['url_0_template']['con']);
            $sites_0 = $this->aConfig['sites_may-filter_in-title_ok'];
            $sites_1 = $this->aConfig['sites_notmay-filter_notin-title_ok'];
            $or = $this->aConfig['or'];
            $and = $this->aConfig['and'];
            if ($sites_0 == '' || $sites_1 == '') {
                show_msg("啥米弄无？\r\n");
                die;
            }
            $aCollectionResult = array();
            foreach ($aTmp as $key => $value) {
                unset($aTmp[$key]);
                show_msg("id={$value['id']}...");
                $title = trim($value['title']);
                $aka_cn = trim($value['aka_cn']);
                $year = trim($value['year']);
                $imdb_id = trim($value['imdb_id']);
                $country = "";
                if ($value['country']) {
                    $tmp = split('/', $value['country']);
                    $country = trim($tmp[0]);
                }
                $director = "";
                if ($value['director']) {
                    $tmp = split('/', $value['director']);
                    $director = trim($tmp[0]);
                }
                $cast = "";
                if ($value['cast']) {
                    $tmp = split('/', $value['cast']);
                    $cast = trim($tmp[0]);
                }
                $q = '';
                $title_in_factor = '';
                $title_notin_factor = '';
                if ($aka_cn) {
                    if ($title_in_factor) {
                        $title_in_factor .= $or;
                        $title_notin_factor .= $or;
                    }
                    $title_in_factor .= 'intitle:"'.$aka_cn.'"';
                    $title_notin_factor .= '"'.$aka_cn.'"';
                }
                if ($title && $title != $aka_cn) {
                    if ($title_in_factor) {
                        $title_in_factor .= $or;
                        $title_notin_factor .= $or;
                    }
                    $title_in_factor .= 'intitle:"'.$title.'"';
                    $title_notin_factor .= '"'.$title .'"';
                }
                
                $fringe_filter_factor = '';
                $main_filter_factor = '';
                if ($director) {
                    if ($main_filter_factor) {
                        $main_filter_factor .= $or;
                    }
                    $main_filter_factor .= '"'.$director.'"';
                }
                if ($cast && $cast != $director) {
                    if ($main_filter_factor) {
                        $main_filter_factor .= $or;
                    }
                    $main_filter_factor .= '"'.$cast.'"';
                }
                if ($main_filter_factor) {
                        $main_filter_factor = '('.$main_filter_factor.')';
                    }
                if ($year && $year>0) {
                    if ($fringe_filter_factor) {
                        $fringe_filter_factor .= $or;
                    }
                    $fringe_filter_factor .= '"'.$year.'"';
                }
                if ($imdb_id) {
                    if ($fringe_filter_factor) {
                        $fringe_filter_factor .= $or;
                    }
                    $fringe_filter_factor .= '"'.$imdb_id.'"';
                }
                // if ($country) {
                //     if ($fringe_filter_factor) {
                //         $fringe_filter_factor .= $or;
                //     }
                //     $fringe_filter_factor .= '"'.$country.'"';
                // }
                if ($title_in_factor == '') {
                    countine;
                }
                $title_in_factor = '('.$title_in_factor.')';
                $title_notin_factor = '('.$title_notin_factor.')';
                if ($fringe_filter_factor) {
                    $fringe_filter_factor = '('.$fringe_filter_factor.')';
                }
                $keyword_0 = '';
                $keyword_1 = '';
                if ($fringe_filter_factor) {
                    $fringe_filter_factor = $and.$fringe_filter_factor;
                }
                if ($main_filter_factor) {
                    $main_filter_factor = $and.$main_filter_factor;
                }
                $keyword_0 = $sites_0.$and.$title_in_factor.$main_filter_factor.$fringe_filter_factor;
                $keyword_1 = $sites_1.$and.$title_notin_factor.$main_filter_factor.$fringe_filter_factor;
                // show_msg($keyword_0."\r\n");
                // show_msg($keyword_1."\r\n");
                $sUrl = str_replace($this->aConfig['url_0_template']['search_replace'], array(urlencode($keyword_0), '0'), $url);
                $sCon = meclient($sUrl);
                // $sCon = "";
                $domCon = str_get_dom($sCon);
                // if (empty($domCon)) {

                // }
                $dom = $domCon->find("#resultStats", 0);
                if (empty($dom)) {
                    show_msg("id=".$value['id']."::'".$sUrl."'"."\r\n");
                    die;
                }
                /*
                <div id="resultStats"></div>
                <div id="resultStats">2 results</div>
                <div id="resultStats">About 74 results</div>
                <div id="resultStats">Page 2 of about 74 results</div>
                */
                $result_stats = remove_invalid($dom->plaintext);
                // var_dump($result_stats);
                // var_dump($domCon);
                // resultStats
                // echo $sCon;
                
                if ($result_stats == '') {
                    //没找到种子
                    show_msg("id=".$value['id']."::'".$sUrl."'"."\r\n");
                    $row = array();
                    $row['id'] = $value['id'];
                    $row['updated_at'] = time();
                    if ($value['weights'] > 0) {
                        $row['weights'] = $value['weights']-1;
                    }
                    $row['plan'] = $value['plan']+1;
                    dump($row);
                    // $this->gaTools['mysqldb']->update('movie', $row);
                    die('why');
                    continue;
                }
                $dom = $domCon->find("#search ol", 0);
                if (empty($dom)) {
                    die('why');
                    continue;
                }
                $dom = $dom->find("li");
                if (empty($dom)) {
                    die('why');
                    continue;
                }
                $urls = array();
                foreach ($dom as $k1 => $v1) {
                     $domTmp = $v1->find(".r a", 0);
                     if (empty($domTmp) || !isset($domTmp->href) || $domTmp->href=="") {
                        continue;
                     }
                     $tmp_url = htmlspecialchars_decode(remove_invalid($domTmp->href));
                     $pos = strpos($tmp_url,"?");
                     $query = '';
                     if ($pos === false) {
                        $query = $tmp_url;
                     } else {
                        $query = substr($tmp_url, $pos+1);
                     }
                     $arr_query = convertUrlQuery($query);
                     if (!isset($arr_query['q']) || empty($arr_query['q'])) {
                        continue;
                     }
                     $urls[] = $arr_query['q'];
                }
                dump($urls);

                die;
                $row2 = array();
                $row2['id'] = $value['id'];
                $row2['average'] = number_format($average, 1);
                $row2['num_raters'] = $value['douban_num_raters']+$value['mtime_num_raters']+$value['imdb_num_raters'];
                // $row2['weights'] = 1;
                $this->gaTools['mysqldb']->update('movie', $row2);
                show_msg("ok!!<br />\r\n");
            }
        }
        die;


        show_msg("搞定收工！！！<br />\r\n");
        die;
        $this->aScriptNeed['charset_of_getcon'] = 'UTF-8';
        // $url = remove_blank($this->aConfig['url_0_template']['con']);
        // $this->aScriptNeed['url_url_0'] = str_replace($this->aConfig['url_0_template']['search_replace'], 1, $url);
        // $aCollectionResult = $this->filter();
        // echo $aCollectionResult;
        // die;

        $limit = 1;
        $movie_tmp_tool_db = new movie_tmp_tool_db();

        
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
        if (preg_match("/= { (.*?)};/", $sCon, $arr)) {
            // dump($arr);
            $aCollectionResult['content'] = "{".$arr[1]."}";
            // echo $aCollectionResult['content']."<br />\r\n";
            if (strripos($aCollectionResult['content'], 'ValidateCode.ashx') !== false) {
                $aCollectionResult['content'] = 'false';
            }
        } else {
            $aCollectionResult['content'] = 'false';
        }
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

        // $this->aConfig['url_0_template']['con'] = 'http://www.google.com.hk/search?oe=utf8&hl=zh-CN&ie=utf8&source=uds&start=0&safe=strict&filter=0&q={@q}&start={@start}';
        $this->aConfig['url_0_template']['con'] = 'http://www.google.com.hk/search?oe=utf8&hl=en&ie=utf8&source=uds&start=0&safe=strict&filter=0&q={@q}&start={@start}';
        $this->aConfig['url_0_template']['search_replace'] = array('{@q}', '{@start}');
        $this->aConfig['url_0']['p_total'] = 10;

        // echo urldecode('%68%74%74%70%3a%2f%2f%77%77%77%2e%69%63%69%6c%69%2e%63%6f%6d%2f%65%6d%75%6c%65%2f%64%6f%77%6e%6c%6f%61%64%2f%31%32%35%37%35%32');
        // die;

        $this->aConfig['sites_may-filter_in-title'] = array(
            'www.icili.com/download/xiazai/', 
            'www.icili.com/emule/download/', 
            'www.torrentkitty.com/information/', 
            'btmee.com/show/', 
            'www.2hd.cc/read.php', 
            );
        $this->aConfig['or'] = ' | ';
        $this->aConfig['and'] = ' ';
        $this->aConfig['sites_may-filter_in-title_ok'] = '';
        if (isset($this->aConfig['sites_may-filter_in-title']) && !empty($this->aConfig['sites_may-filter_in-title'])) {
            foreach ($this->aConfig['sites_may-filter_in-title'] as $k => $v) {
                $this->aConfig['sites_may-filter_in-title_ok'] .= 'site:'.$v.$this->aConfig['or'];
            }
            if ($this->aConfig['sites_may-filter_in-title_ok'] != '') {
                 $this->aConfig['sites_may-filter_in-title_ok'] = '('.substr($this->aConfig['sites_may-filter_in-title_ok'], 0, -3).')';
            }
            // $this->aConfig['sites_may-filter_in-title_ok'] = substr($this->aConfig['sites_may-filter_in-title_ok'], 0, -3);
        }
        $this->aConfig['sites_notmay-filter_notin-title'] = array(
            'grbt.asia/show.php', 
            );
        $this->aConfig['sites_notmay-filter_notin-title_ok'] = '';
        if (isset($this->aConfig['sites_notmay-filter_notin-title']) && !empty($this->aConfig['sites_notmay-filter_notin-title'])) {
            foreach ($this->aConfig['sites_notmay-filter_notin-title'] as $k => $v) {
                $this->aConfig['sites_notmay-filter_notin-title_ok'] .= 'site:'.$v.$this->aConfig['or'];
            }
            if ($this->aConfig['sites_notmay-filter_notin-title_ok'] != '') {
                $this->aConfig['sites_notmay-filter_notin-title_ok'] = '('.substr($this->aConfig['sites_notmay-filter_notin-title_ok'], 0, -3).')';
            }
            // $this->aConfig['sites_notmay-filter_notin-title_ok'] = substr($this->aConfig['sites_notmay-filter_notin-title_ok'], 0, -3);
        }
    }
    
    function __destruct()
    {
        
    }
}
