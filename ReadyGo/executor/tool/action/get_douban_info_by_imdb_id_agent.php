<?php  if ( ! defined('ROOT_PATH')) exit('非法访问！！！');
/********************************************************
 Author: NIGG
Version: 1
 Effect: 
   Date: 
  Notes: ?space=10w&action=get_douban_info_by_imdb_id&do=always&end=1
********************************************************/
class get_douban_info_by_imdb_id_agent
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
//		if ($this->aParameter['do'] != 'always') {
//			$sql = "SELECT * FROM `album_listened`";
//			$data = $this->gaTools['mysqldb']->find( $sql );
//			if (!empty($data)) {
//				foreach ($data as $v) {
//					echo '<img src="'.$v['cover'].'" style="width:90px;height:90px;" title="'.$v['title'] . ' -- ' . $v['author'] . "\n" . $v['description'].'" />';
//				}
//				return;
//			}
//		}

        if (!$this->aParameter['do']) {
            $aArgv = $GLOBALS['argv'];
            $this->aParameter['start0'] = isset($aArgv[5]) ? $aArgv[5] : '1';
            $this->aParameter['end'] = isset($aArgv[6]) ? $aArgv[6] : '97191';
        }
//         dump($this->aParameter);
        
        $this->aScriptNeed['charset_of_getcon'] = 'UTF-8';
        $url = remove_blank($this->aConfig['url_0_template']['con']);
        show_msg("开始获取imdb数据.......<br />\r\n");
        $aTmp = $this->gaTools['mysqldb']->find('SELECT id,imdb_id,title,aka_cn,year FROM '.$this->aConfig['tb_name_0'].' WHERE id>='.$this->aParameter['start0'].' AND id<'.$this->aParameter['end'].' AND imdb_id!="" AND douban_id="" AND noin_douban!=1 ORDER BY id ASC');
        $id = isset($aTmp[0]['id']) ? $aTmp[0]['id'] : '1';
        show_msg("从id=“{$id}”开始.......<br />\r\n");
//         echo count($aTmp);
//         die;

//         $row = array();
//         $this->aScriptNeed['url_url_0'] = str_replace($this->aConfig['url_0_template']['search_replace'], urlencode("盗梦空间"), $url);
//         $sUrl = $this->aScriptNeed['url_url_0'];
//         $row['mid'] = $value['id'];
//         $row['url'] = $sUrl;
//         $row['content'] = meclient($sUrl);
//         $row['content'] = json_decode($row['content']);
//         dump($row);
//         die;

        // dump($aTmp);
        if (!empty($aTmp)) {
            $aCollectionResult = array();
            foreach ($aTmp as $key => $value) {
                show_msg("id={$value['id']}.......");
                $row = array();
                $row2 = array();
                $row3 = array();
                
                $row2['id'] = $value['id'];
                $row2['updated_at'] = time();
                
                $row3['mid'] = $value['id'];
                
                $toDoubanUrl = "http://api.douban.com/movie/subject/imdb/".$value['imdb_id']."?apikey=06953f4549257b8213bfbdcfdb707286";
                $row3['url'] = $toDoubanUrl;
                $doubanContent = meclient($toDoubanUrl);
                $domCon = str_get_html($doubanContent);
                $row3['content'] = $doubanContent;
                $dom = $domCon->find("id",0);
                if ($dom) {
                    $str = $dom->plaintext;
                    if (preg_match("/\/(\d+)$/",$str,$arr)) {
                        $row3['douban_id'] = $row2['douban_id'] = $arr[1];
                    }
                } else {
                    show_msg("内容：".$doubanContent."<br />\r\n");
                    if (preg_match("/bad imdb id/",$doubanContent) || preg_match("/wrong subject id/",$doubanContent)) {
                        $rowTmp = array();
                        $rowTmp['id'] = $value['id'];
                        $rowTmp['noin_douban'] = 1;
                        $r = $this->gaTools['mysqldb']->update($this->aConfig['tb_name_0'],$rowTmp);
                        continue;
                    } else {
                        show_msg("尼玛！douban_id没有！！被封？？<br />\r\n");
                        die;
                    }
                }
                
                $dom = $domCon->find("db:attribute[lang=zh_CN]",0);
                if ($dom) {
                    if (isset($dom->name) && $dom->name == 'aka') {
                        $row2['aka_cn'] = $dom->plaintext;
                    }
                }
                $dom = $domCon->find("db:attribute[name=website]",0);
                if ($dom) {
                    $row2['website'] = $dom->plaintext;
                }
                $dom = $domCon->find("link[rel=image]",0);
                if ($dom) {
                    $row2['douban_image'] = $dom->href;
                }
                $dom = $domCon->find("db:attribute[name=pubdate]");
                if ($dom) {
                    $pubdates = "";
                    foreach ($dom as $k_k=>$v_v) {
                        $pubdates .= $v_v->plaintext . ' / ';
                    }
                    if ($pubdates) {
                        $row2['pubdate'] = substr($pubdates,0,-3);
                    }
                }
                if ((!isset($row2['summary']) || $row2['summary'] == '')) {
                    $dom = $domCon->find("summary",0);
                    if ($dom) {
                        $row2['summary'] = $dom->plaintext;
                    }
                }
                $dom = $domCon->find("db:tag");
                if ($dom) {
                    $tags = "";
                    foreach ($dom as $k_k=>$v_v) {
                        $tags .= $v_v->name . '('.$v_v->count.')' . ' / ';
                    }
                    if ($pubdates) {
                        $row2['tag'] = substr($tags,0,-3);
                    }
                }
                $dom = $domCon->find("gd:rating",0);
                if ($dom) {
                    $row2['douban_average'] = $dom->average;
                    $row2['douban_num_raters'] = $dom->numraters;
                }
                $dom = $domCon->find("author name",0);
                if ($dom) {
                    $row2['author'] = $dom->plaintext;
                }
                $domCon->__destruct();
                
//                 dump($row);
//                 echo '----------------------------';
//                 dump($row2);
//                 die;
                
                $this->gaTools['mysqldb']->escape_row($row2);
                $r = $this->gaTools['mysqldb']->update($this->aConfig['tb_name_0'],$row2);
                
                $this->gaTools['mysqldb']->escape_row($row3);
                $aTmp2 = $this->gaTools['mysqldb']->get('SELECT * FROM '.$this->aConfig['tb_name_1'] . ' WHERE mid=' . $value['id']);
                if ($aTmp2) {
                    $row3['id'] = $aTmp2['id'];
                    $r = $this->gaTools['mysqldb']->update($this->aConfig['tb_name_1'],$row3);
                } else {
                    $r = $this->gaTools['mysqldb']->save($this->aConfig['tb_name_1'],$row3);
                }
                show_msg("过滤保存成功<br />\r\n");
//                 die;
            }
        }
        die;
        
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
            // $arr2 = json_decode($json);
            // dump($arr2);
            // if (isset($arr2->value) && isset($arr2->value->listHTML)) {
            // 	$domCon = str_get_html($arr2->value->listHTML);
            // 	echo $domCon;
            // }
        } else {
            $aCollectionResult['content'] = 'false';
        }
//		dump($aCollectionResult);
        return $aCollectionResult;
    }

    private function filter2($aP)
    {
        if (!$aP) {
            return;
        }
        $aCollectionResult = array();
        
        $arr2 = json_decode($aP['content']);
        // dump($arr2);
        if (isset($arr2->value) && isset($arr2->value->listHTML)) {
            $domCon = str_get_html($arr2->value->listHTML);
            $lis = $domCon->find('li div.table');
            if (!empty($lis)) {
                foreach ($lis as $k => $v) {
                    $aka_cn = '';
                    $year = '';
                    $mtime_id = '';
                    $mtime_average = '';
                    $title = '';
                    $aka = '';
                    $mtime_num_raters = '';
                    $mtime_image = '';
                    $dom = $v->find('h3[class="normal mt6"]',0);
                    if (!empty($dom)) {
                        $a = $dom->find('a',0);
                        if (!empty($a)) {
                            $aka_cn = remove_invalid($a->plaintext);
                        }
                        $span = $dom->find('span',0);
                        if (!empty($span)) {
                            $year = remove_invalid(remove_noneed($span->plaintext));
                        }
                    } else {
                        echo '-----------------空';
                    }
                    // echo $aka_cn.'('.$year.')<br />';
                    $dom = $v->find('p[class="point ml6"]',0);
                    if (!empty($dom)) {
                        $mtime_average = remove_invalid($dom->plaintext);
                    } else {
                        echo '-----------------空';
                    }
                    // echo $mtime_average.'<br />';
                    $dom = $v->find('div div',1);
                    if (!empty($dom)) {
                        $dom = $dom->childNodes(2);
                        if (!empty($dom)) {
                            $title = remove_invalid($dom->plaintext);
                            $dom = $dom->find('a',0);
                            if ($dom) {
                                if (preg_match("/\/(\d+?)\//", $dom->href, $aTmp3)) {
                                    $mtime_id = $aTmp3[1];
                                }
                            }
                        }
                    } else {
                        echo '-----------------空';
                    }
                    $dom = $v->find('p[class="mt15 c_666"]');
                    if (!empty($dom)) {
                        foreach ($dom as $k_k => $v_v) {
                            $aka .= remove_invalid($v_v->plaintext).' / ';
                        }
                        $aka = preg_replace("/( \/ )$/", "", $aka);
                    }
                    // echo $aka.'<br />';
                    $dom = $v->find('p[class="c_666 mt6"]', 0);
                    if (!empty($dom)) {
                        if (preg_match("/(\d+?)人/", $dom->plaintext, $aTmp3)) {
                            $mtime_num_raters = $aTmp3[1];
                        }
                    }
                    // echo $mtime_num_raters.'人<br />';
                    $dom = $v->find('img[class="img_box"]', 0);
                    if (!empty($dom)) {
                        $mtime_image = $dom->src;
                    }
                    // echo '<img src="'.$mtime_image.'" /><br />';
                    $aResult['mtime_num_raters'] = $mtime_num_raters;
                    $aResult['aka'] = $aka;
                    $aResult['mtime_id'] = $mtime_id;
                    $aResult['mtime_average'] = $mtime_average;
                    $aResult['year'] = $year;
                    $aResult['aka_cn'] = $aka_cn;
                    $aResult['title'] = $title;
                    $aResult['mtime_image'] = $mtime_image;
                    $aResult['p'] = $aP['p'];
                    $aResult['color'] = 'colored';
                    $aResult['updated_at'] = $aResult['created_at'] = time();
                    $aCollectionResult[] = $aResult;
                    // die;
                }
            }
            $domCon->__destruct();
            // echo $domCon;
            
        }
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
        $this->aConfig['tb_name'] = 'imdb';
        $this->aConfig['tb_name_0'] = 'movie';
        $this->aConfig['tb_name_1'] = 'douban';
        
        //mtime 彩色=433
        $this->aConfig['url_0_template']['con'] = 'http://imdbapi.org/?title={@title}&type=json&plot=full&episode=1&limit=1&year={@year}&yg={@yg}&mt=none&lang=zh-CN&offset=&aka=simple&release=simple';
        $this->aConfig['url_0_template']['search_replace'] = array('{@title}','{@year}','{@yg}');
        $this->aConfig['url_0']['p_total'] = 1;

        
        
        $this->aCommConfiger['url_url'] = 'url';
    }
    
    function __destruct()
    {
        
    }
}
