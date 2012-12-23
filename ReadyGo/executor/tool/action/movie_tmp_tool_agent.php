<?php  if ( ! defined('ROOT_PATH')) exit('非法访问！！！');
/********************************************************
 Author: BurtonQ
Version: 1
 Effect: 
   Date: 
  Notes: ?space=10w&action=movie_tmp_tool&do=always&end=1
********************************************************/
class movie_tmp_tool_agent
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

        
        $this->aScriptNeed['charset_of_getcon'] = 'UTF-8';
        // $url = remove_blank($this->aConfig['url_0_template']['con']);
        // $this->aScriptNeed['url_url_0'] = str_replace($this->aConfig['url_0_template']['search_replace'], 1, $url);
        // $aCollectionResult = $this->filter();
        // echo $aCollectionResult;
        // die;

        $limit = 1;
        $movie_tmp_tool_db = new movie_tmp_tool_db();

        ####################### 过滤抓到mtime的数据1##################
        //彩色
//         show_msg("更新时间.......<br />\r\n");
//         $t = time();
//         $sql  = "update `{$this->aConfig['tb_name_0']}` set `updated_at` = '{$t}',`created_at` = '{$t}' WHERE color='colored'";
//         $this->gaTools['mysqldb']->query($sql);
//         die;


//         show_msg("开始过滤彩色数据.......<br />\r\n");
//         $aTmp2 = $this->gaTools['mysqldb']->get('SELECT p FROM '.$this->aConfig['tb_name_0'].' WHERE color="colored" ORDER BY p DESC');
//         $where = isset($aTmp2) ? ' p>='.$aTmp2['p'] : '1';
//         show_msg("从条件“{$where}”开始.......<br />\r\n");
//         $aTmp = $this->gaTools['mysqldb']->find('SELECT * FROM '.$this->aConfig['tb_name']. '  WHERE color=433 AND '. $where.' LIMIT 0,5');
//         $aTmp = $this->gaTools['mysqldb']->find('SELECT * FROM '.$this->aConfig['tb_name']. ' '. '  WHERE color=433 AND '. $where);

//         // dump($aTmp);
//         if (!empty($aTmp)) {
//             $aCollectionResult = array();
//             foreach ($aTmp as $key => $value) {
//                 show_msg("p={$value['p']}.......");
//                 $aCollectionResult = $this->filter2($value);
//                 $r = $movie_tmp_tool_db->save_data2($aCollectionResult);
//                 if ($r === true) {
//                     show_msg("过滤保存成功<br />\r\n");
//                 } else {
//                     var_dump($r);
//                     show_msg("过滤保存有一些失败<br />\r\n");
//                 }
                dump($aCollectionResult);
//             }
//         }
//         die;
        show_msg("开始过滤黑白数据.......<br />\r\n");
        $aTmp2 = $this->gaTools['mysqldb']->get('SELECT p FROM '.$this->aConfig['tb_name_0'].' WHERE color="nocolored" ORDER BY p DESC');
        $where = isset($aTmp2) ? ' p>='.$aTmp2['p'] : '1';
        show_msg("从条件“{$where}”开始.......<br />\r\n");
//         $aTmp = $this->gaTools['mysqldb']->find('SELECT * FROM '.$this->aConfig['tb_name']. '  WHERE color=433 AND '. $where.' LIMIT 0,5');
        $aTmp = $this->gaTools['mysqldb']->find('SELECT * FROM '.$this->aConfig['tb_name']. ' '. '  WHERE color=434 AND '. $where);

        // dump($aTmp);
        if (!empty($aTmp)) {
            $aCollectionResult = array();
            foreach ($aTmp as $key => $value) {
                show_msg("p={$value['p']}.......");
                $aCollectionResult = $this->filter2($value);
                $r = $movie_tmp_tool_db->save_data2($aCollectionResult);
                if ($r === true) {
                    show_msg("过滤保存成功<br />\r\n");
                } else {
                    var_dump($r);
                    show_msg("过滤保存有一些失败<br />\r\n");
                }
//                 dump($aCollectionResult);
            }
        }
        die;
        ####################### 过滤抓到mtime的数据1##################


        //彩色
        // show_msg("开始抓彩色.......<br />\r\n");
        // $color = 433;
        // if ($this->aParameter['start0'] == 1) {
        // 	$aTmp = $this->gaTools['mysqldb']->get('SELECT p FROM '.$this->aConfig['tb_name'] . ' WHERE color='.$color.' ORDER BY p DESC');
        // 	$this->aParameter['start0'] = isset($aTmp['p']) ? ($aTmp['p']+1) : '1';
        // }
        // $aCollectionResult = array();
        // for ($i = $this->aParameter['start0']; $i <= $this->aConfig['url_0']['p_total']; $i++) {
        // 	show_msg("($i)<br />\r\n");
        // 	$this->aScriptNeed['url_url_0'] = str_replace($this->aConfig['url_0_template']['search_replace'], $i, remove_blank($this->aConfig['url_0_template']['con']));
        // 	$tmp = $this->filter();
        // 	$tmp['p'] = $i;
        // 	$tmp['color'] = $color;
        // 	if ($tmp['content'] == 'false') {
        // 		show_msg("尼玛！！被封<br />\r\n");
        // 		die;
        // 		sleep(60);
        // 		$i--;
        // 		continue;
        // 	}
        // 	$aCollectionResult[] = $tmp;
        // 	if ($i%$limit == 0) {
        // 		$movie_tmp_tool_db->save_data($aCollectionResult);
        // 		$aCollectionResult = array();
        // 	}
        // 	if ($this->aParameter['end'] != '' && $i == $this->aParameter['end']) {
     //        	break;
     //        }
        // }
        // if (!empty($aCollectionResult)) {
        // 	show_msg("最后存数据库：最后一页是($i)<br />\r\n");
        // 	$movie_tmp_tool_db->save_data($aCollectionResult);
        // 	$aCollectionResult = array();
        // }
        // show_msg("-------------------------------------------------------<br />\r\n");
        //黑白
        show_msg("开始抓黑白.......<br />\r\n");
        $color = 434;
        if ($this->aParameter['start1'] == 1) {
            $aTmp = $this->gaTools['mysqldb']->get('SELECT p FROM '.$this->aConfig['tb_name'] . ' WHERE color='.$color.' ORDER BY p DESC');
            $this->aParameter['start1'] = isset($aTmp['p']) ? ($aTmp['p']+1) : '1';
        }
        $aCollectionResult = array();
        for ($i = $this->aParameter['start1']; $i <= $this->aConfig['url_1']['p_total']; $i++) {
            show_msg("($i)<br />\r\n");
            $this->aScriptNeed['url_url_0'] = str_replace($this->aConfig['url_1_template']['search_replace'], $i, remove_blank($this->aConfig['url_1_template']['con']));
            $tmp = $this->filter();
            $tmp['p'] = $i;
            $tmp['color'] = $color;
            if ($tmp['content'] == 'false') {
                show_msg("尼玛！！被封<br />\r\n");
                die;
                sleep(60);
                $i--;
                continue;
            }
            $aCollectionResult[] = $tmp;
            if ($i%$limit == 0) {
                $movie_tmp_tool_db->save_data($aCollectionResult);
                $aCollectionResult = array();
            }
            if ($this->aParameter['end'] != '' && $i == $this->aParameter['end']) {
                break;
            }
        }
        if (!empty($aCollectionResult)) {
            show_msg("最后存数据库：最后一页是($i)<br />\r\n");
            $movie_tmp_tool_db->save_data($aCollectionResult);
            $aCollectionResult = array();
        }
        show_msg("-----------------------ok!!!<br />\r\n");
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
        $this->aConfig['tb_name'] = 'mtime_tmp';
        $this->aConfig['tb_name_0'] = 'movie_tmp';
        
        //mtime 彩色=433
        $this->aConfig['url_0_template']['con'] = 'http://service.channel.mtime.com/service/search.mcs
                                                    ?Ajax_CallBack=true
                                                    &Ajax_CallBackType=Mtime.Channel.Pages.SearchService
                                                    &Ajax_CallBackMethod=SearchMovieByCategory
                                                    &Ajax_CrossDomain=1
                                                    &Ajax_RequestUrl=http%3A%2F%2Fmovie.mtime.com%2Fmovie%2Fsearch%2Fsection%2F%23&t=20121282174592622
                                                    &Ajax_CallBackArgument0=
                                                    &Ajax_CallBackArgument1=0
                                                    &Ajax_CallBackArgument2=0
                                                    &Ajax_CallBackArgument3=0
                                                    &Ajax_CallBackArgument4=0
                                                    &Ajax_CallBackArgument5=0
                                                    &Ajax_CallBackArgument6=433
                                                    &Ajax_CallBackArgument7=0
                                                    &Ajax_CallBackArgument8=
                                                    &Ajax_CallBackArgument9=0
                                                    &Ajax_CallBackArgument10=0
                                                    &Ajax_CallBackArgument11=0
                                                    &Ajax_CallBackArgument12=0
                                                    &Ajax_CallBackArgument13=0
                                                    &Ajax_CallBackArgument14=1
                                                    &Ajax_CallBackArgument15=0
                                                    &Ajax_CallBackArgument16=1
                                                    &Ajax_CallBackArgument17=4
                                                    &Ajax_CallBackArgument18={@p}
                                                    &Ajax_CallBackArgument19=0';
        $this->aConfig['url_0_template']['search_replace'] = array('{@p}');
        $this->aConfig['url_0']['p_total'] = 4129;

        //mtime 黑白=434
        $this->aConfig['url_1_template']['con'] = 'http://service.channel.mtime.com/service/search.mcs
                                                    ?Ajax_CallBack=true
                                                    &Ajax_CallBackType=Mtime.Channel.Pages.SearchService
                                                    &Ajax_CallBackMethod=SearchMovieByCategory
                                                    &Ajax_CrossDomain=1
                                                    &Ajax_RequestUrl=http%3A%2F%2Fmovie.mtime.com%2Fmovie%2Fsearch%2Fsection%2F%23color%3D433&t=201212821111545813
                                                    &Ajax_CallBackArgument0=
                                                    &Ajax_CallBackArgument1=0
                                                    &Ajax_CallBackArgument2=0
                                                    &Ajax_CallBackArgument3=0
                                                    &Ajax_CallBackArgument4=0
                                                    &Ajax_CallBackArgument5=0
                                                    &Ajax_CallBackArgument6=434
                                                    &Ajax_CallBackArgument7=0
                                                    &Ajax_CallBackArgument8=
                                                    &Ajax_CallBackArgument9=0
                                                    &Ajax_CallBackArgument10=0
                                                    &Ajax_CallBackArgument11=0
                                                    &Ajax_CallBackArgument12=0
                                                    &Ajax_CallBackArgument13=0
                                                    &Ajax_CallBackArgument14=1
                                                    &Ajax_CallBackArgument15=0
                                                    &Ajax_CallBackArgument16=1
                                                    &Ajax_CallBackArgument17=4
                                                    &Ajax_CallBackArgument18={@p}
                                                    &Ajax_CallBackArgument19=0';
        $this->aConfig['url_1_template']['search_replace'] = array('{@p}');
        $this->aConfig['url_1']['p_total'] = 1208;
        
        
        $this->aCommConfiger['url_url'] = 'url';
    }
    
    function __destruct()
    {
        
    }
}
