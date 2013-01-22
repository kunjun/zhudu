<?php  if ( ! defined('ROOT_PATH')) exit('非法访问！！！');
/********************************************************
 Author: NIGG
Version: 1
 Effect: 
   Date: 
  Notes: ?space=10w&action=get_imdb_info&do=always&end=1
  php ../index.php 10w get_imdb_info 1 gbk
********************************************************/
class get_imdb_info_agent
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

        $aArgv = $GLOBALS['argv'];
        $this->aParameter['start0'] = isset($aArgv[5]) ? $aArgv[5] : '1';
        $this->aParameter['end'] = isset($aArgv[6]) ? $aArgv[6] : '214296';
        
        $this->aScriptNeed['charset_of_getcon'] = 'UTF-8';
        $url = remove_blank($this->aConfig['url_0_template']['con']);
        show_msg("开始获取imdb数据.......<br />\r\n");
        $aTmp = $this->gaTools['mysqldb']->find('SELECT * FROM movie WHERE id>='.$this->aParameter['start0'].' AND id<'.$this->aParameter['end'].' AND imdb_average="" ORDER BY id ASC');
        $id = isset($aTmp[0]['id']) ? $aTmp[0]['id'] : '';
        show_msg("从id=“{$id}”开始.......<br />\r\n");
        // echo count($aTmp);
        // die;

        // dump($aTmp);
        if (!empty($aTmp)) {
            $aCollectionResult = array();
            foreach ($aTmp as $key => $value) {
                unset($aTmp[$key]);
                show_msg("id={$value['id']}.......");
                $row2 = array();
                $row2['id'] = $value['id'];
                $this->aScriptNeed['url_url_0'] = str_replace($this->aConfig['url_0_template']['search_replace'], array($value['imdb_id']), $url);
                $sUrl = $this->aScriptNeed['url_url_0'];
                $sCon = meclient($sUrl);
                $content = json_decode($sCon);
                if ($content == NULL) {
                    show_msg("网络还没连上！！<br />\r\n");
                    die;
                }
                if (!$content || isset($content->code)) {
                    show_msg("title=".$value['title'].";aka_cn=".$value['aka_cn'].";mid=".$value['id'].";url=".$sUrl."<br />\r\n");
                    show_msg("尼玛！什么情况！！<br />\r\n");
                    $row2['imdb_num_raters'] = 0;
                    $row2['imdb_average'] = 0;
                    $this->gaTools['mysqldb']->update('movie',$row2);
                    continue;
                }
//                 echo $sUrl;
                // dump($content);
                // die;
                // if (isset($content->type) && $content->type != 'M') {
                //     continue;
                // }
                if (!isset($content->imdb_id)) {
                    show_msg("title=".$value['title'].";aka_cn=".$value['aka_cn'].";mid=".$value['id'].";url=".$sUrl);
                    show_msg("尼玛！imdb_id没有！！<br />\r\n");
                    var_dump($sCon);
                    die;
                }
                if (isset($content->rating_count)) {
                    $row2['imdb_num_raters'] = $content->rating_count;
                } else {
                    $row2['imdb_num_raters'] = 0;
                }
                if (isset($content->rating)) {
                    $row2['imdb_average'] = $content->rating;
                } else {
                    $row2['imdb_average'] = 0;
                }
                if (isset($value['douban_id']) && $value['douban_id']) {
                    $this->gaTools['mysqldb']->escape_row($row2);
                    $this->gaTools['mysqldb']->update('movie',$row2);
                    show_msg("电影信息已经有，只更新评分<br />\r\n");
                    continue;
                }
                $row2['updated_at'] = time();
                if (isset($content->title)) {
                    $row2['title'] = $content->title;
                }
                if (isset($content->release_date)) {
                    $row2['pubdate'] = $content->release_date;
                }
                if (isset($content->year)) {
                    $row2['year'] = $content->year;
                }
                if (isset($content->plot)) {
                    $row2['summary'] = $content->plot;
                }
                if (isset($content->country)) {
                    if (!empty($content->country)) {
                        $countrys = "";
                        foreach ($content->country as $country_k=>$country_v) {
                            $countrys .= $country_v." / ";
                        }
                        if ($countrys) {
                            $row2['country'] = substr($countrys,0,-3);
                        }
                    }
                }
                if (isset($content->also_known_as)) {
                    if (!empty($content->also_known_as)) {
                        $also_known_ass = "";
                        foreach ($content->also_known_as as $also_known_as_k=>$also_known_as_v) {
                            $also_known_ass .= $also_known_as_v." / ";
                        }
                        if ($also_known_ass) {
                            $row2['aka'] = substr($also_known_ass,0,-3);
                        }
                    }
                }
                if (isset($content->actors)) {
                    if (!empty($content->actors)) {
                        $actors = "";
                        foreach ($content->actors as $actors_k=>$actors_v) {
                            $actors .= $actors_v." / ";
                        }
                        if ($actors) {
                            $row2['cast'] = substr($actors,0,-3);
                        }
                    }
                } else {
                    $row2['cast'] = '';
                }
                if (isset($content->directors)) {
                    if (!empty($content->directors)) {
                        $directors = "";
                        foreach ($content->directors as $directors_k=>$directors_v) {
                            $directors .= $directors_v." / ";
                        }
                        if ($directors) {
                            $row2['director'] = substr($directors,0,-3);
                        }
                    }
                }
                if (isset($content->writers)) {
                    if (!empty($content->writers)) {
                        $writers = "";
                        foreach ($content->writers as $writers_k=>$writers_v) {
                            $writers .= $writers_v." / ";
                        }
                        if ($writers) {
                            $row2['writer'] = substr($writers,0,-3);
                        }
                    }
                }
                if (isset($content->runtime)) {
                    if (!empty($content->runtime)) {
                        $runtimes = "";
                        foreach ($content->runtime as $runtimes_k=>$runtimes_v) {
                            $runtimes .= $runtimes_v." / ";
                        }
                        if ($runtimes) {
                            $row2['movie_duration'] = substr($runtimes,0,-3);
                        }
                    }
                }
                if (isset($content->language)) {
                    if (!empty($content->language)) {
                        $languages = "";
                        foreach ($content->language as $languages_k=>$languages_v) {
                            $languages .= $languages_v." / ";
                        }
                        if ($languages) {
                            $row2['language'] = substr($languages,0,-3);
                        }
                    }
                }
                if (isset($content->genres)) {
                    if (!empty($content->genres)) {
                        $genres = "";
                        foreach ($content->genres as $genres_k=>$genres_v) {
                            $genres .= $genres_v." / ";
                        }
                        if ($genres) {
                            $row2['movie_type'] = substr($genres,0,-3);
                        }
                    }
                }
                if (!isset($row2['title'])) {
                    $row2['title'] = '';
                }
                if (!isset($row2['pubdate'])) {
                    $row2['pubdate'] = '';
                }
                if (!isset($row2['year'])) {
                    $row2['year'] = '';
                }
                if (!isset($row2['summary'])) {
                    $row2['summary'] = '';
                }
                if (!isset($row2['country'])) {
                    $row2['country'] = '';
                }
                if (!isset($row2['aka'])) {
                    $row2['aka'] = '';
                }
                if (!isset($row2['cast'])) {
                    $row2['cast'] = '';
                }
                if (!isset($row2['director'])) {
                    $row2['director'] = '';
                }
                if (!isset($row2['writer'])) {
                    $row2['writer'] = '';
                }
                if (!isset($row2['movie_duration'])) {
                    $row2['movie_duration'] = '';
                }
                if (!isset($row2['language'])) {
                    $row2['language'] = '';
                }
                if (!isset($row2['movie_type'])) {
                    $row2['movie_type'] = '';
                }
                if (!isset($row2['aka_cn'])) {
                    $row2['aka_cn'] = '';
                }
                // dump($row2);
                // die;
                
                $this->gaTools['mysqldb']->escape_row($row2);
                $this->gaTools['mysqldb']->update('movie',$row2);
                show_msg("过滤保存成功<br />\r\n");
                // die;
            }
        }
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
        $this->aConfig['url_0_template']['con'] = 'http://imdbapi.org/?id={@imdb_id}&type=json&plot=simple&episode=1&lang=zh-CN&aka=simple&release=simple&business=0&tech=0';
        $this->aConfig['url_0_template']['search_replace'] = array('{@imdb_id}');
        $this->aConfig['url_0']['p_total'] = 1;

        
        
        $this->aCommConfiger['url_url'] = 'url';
    }
    
    function __destruct()
    {
        
    }
}
