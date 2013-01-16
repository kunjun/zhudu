<?php  if ( ! defined('ROOT_PATH')) exit('非法访问！！！');
/********************************************************
 Author: NIGG
Version: 1
 Effect: 
   Date: 
  Notes: ?space=10w&action=get_movie_info_douban_imdb&do=always&end=1
  php ../index.php 10w get_movie_info_douban_imdb 1 gbk
********************************************************/
class get_movie_info_douban_imdb_agent
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
        $this->aParameter['start0'] = isset($aArgv[5]) ? $aArgv[5] : '';
        $this->aParameter['end'] = isset($aArgv[6]) ? $aArgv[6] : '';
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
        //SELECT * FROM `movie` WHERE imdb_id="" AND updated_at>1357315200
        $aTmp = $this->gaTools['mysqldb']->find('SELECT * FROM '.$tb_name.' '.$where.' AND is_ok=0 ORDER BY id ASC');
        //$aTmp = $this->gaTools['mysqldb']->find('SELECT * FROM movie '.$where.' ORDER BY id ASC');
        $id = isset($aTmp[0]['id']) ? $aTmp[0]['id'] : '';
        show_msg("从id=“{$id}”开始.......<br />\r\n");
        // dump($aTmp);
        // die;
        // echo count($aTmp);
        // die;

        if (!empty($aTmp)) {
            $aCollectionResult = array();
            foreach ($aTmp as $key => $value) {
                show_msg("id={$value['id']}.......");
                $row2 = array();

                $url = remove_blank($this->aConfig['url_template'][$url_template_index]['con']);
                $this->aScriptNeed['url_url_0'] = str_replace($this->aConfig['url_template'][$url_template_index]['search_replace'], array($value['imdb_id'],$apikey), $url);
                $sUrl = $this->aScriptNeed['url_url_0'];
                // die($sUrl);
                $doubanContent = meclient($sUrl);
                $domCon = str_get_html($doubanContent);
                $dom = $domCon->find("id",0);
                //判断是否被封
                if (!$dom) {
                    show_msg("内容：".$doubanContent."<br />\r\n");
                    if (preg_match("/bad imdb id/",$doubanContent) || preg_match("/wrong subject id/",$doubanContent)) {
                        $rowTmp = array();
                        $rowTmp['id'] = $value['id'];
                        //豆瓣中没有
                        $rowTmp['is_ok'] = 2;
                        $r = $this->gaTools['mysqldb']->update($tb_name,$rowTmp);
                        if (isset($aTmp[$key])) {
                            unset($aTmp[$key]);
                        }
                        continue;
                    } else {
                        show_msg("尼玛！douban_id没有！！被封？？<br />\r\n");
                        die;
                    }
                }
                
                $dom = $domCon->find("db:attribute[name=episodes]",0);
                if ($dom) {
                    if ($dom->plaintext > 1) {
                        show_msg("电视剧《".$value['imdb_title']."》；集数：".$dom->plaintext."<br />\r\n");
                        $rowTmp = array();
                        $rowTmp['id'] = $value['id'];
                        //已在豆瓣中找过,且不是电影
                        $rowTmp['is_ok'] = 3;
                        $r = $this->gaTools['mysqldb']->update($tb_name,$rowTmp);
                        if (isset($aTmp[$key])) {
                            unset($aTmp[$key]);
                        }
                        continue;
                    }
                }
                if (!isset($value['douban_id']) || empty($value['douban_id'])) {
                    $value['douban_id'] = '';
                    $dom = $domCon->find("id",0);
                    if ($dom) {
                        $str = $dom->plaintext;
                        if (preg_match("/\/(\d+)$/",$str,$arr)) {
                            $value['douban_id'] = $arr[1];
                        }
                    } 
                }
                if (!isset($value['imdb_id']) || empty($value['imdb_id'])) {
                    $value['imdb_id'] = '';
                    $dom = $domCon->find("db:attribute[name=imdb]",0);
                    if ($dom) {
                        if (preg_match("/(tt\d+)/",$dom->plaintext,$arr)) {
                            $value['imdb_id'] = $arr[1];
                        }
                    }
                }
                if (!isset($value['aka_cn']) || empty($value['aka_cn'])) {
                    $value['aka_cn'] = '';
                    $dom = $domCon->find("db:attribute[lang=zh_CN]",0);
                    if ($dom) {
                        if (isset($dom->name) && $dom->name == 'aka') {
                            $value['aka_cn'] = $dom->plaintext;
                        }
                    }
                }
                $row2['douban_id'] = $value['douban_id'];
                $row2['imdb_id'] = $value['imdb_id'];
                $row2['title'] = '';
                $dom = $domCon->find("title",0);
                if ($dom) {
                    $row2['title'] = $dom->plaintext;
                }
                $dom = $domCon->find("author name",0);
                if ($dom) {
                    $row2['author'] = $dom->plaintext;
                }
                $dom = $domCon->find("link[rel=image]",0);
                if ($dom) {
                    $row2['douban_image'] = $dom->href;
                }
                $dom = $domCon->find("summary",0);
                if ($dom) {
                    $row2['summary'] = $dom->plaintext;
                }
                $dom = $domCon->find("db:attribute[name=year]",0);
                if ($dom) {
                    $row2['year'] = $dom->plaintext;
                }
                $dom = $domCon->find("db:attribute[name=movie_duration]");
                if ($dom) {
                    $items = "";
                    foreach ($dom as $k_k=>$v_v) {
                        $items .= $v_v->plaintext . ' / ';
                    }
                    if ($items) {
                        $row2['movie_duration'] = substr($items,0,-3);
                    }
                }
                $dom = $domCon->find("db:attribute[name=country]");
                if ($dom) {
                    $items = "";
                    foreach ($dom as $k_k=>$v_v) {
                        $items .= $v_v->plaintext . ' / ';
                    }
                    if ($items) {
                        $row2['country'] = substr($items,0,-3);
                    }
                }
                $dom = $domCon->find("db:attribute[name=writer]");
                if ($dom) {
                    $items = "";
                    foreach ($dom as $k_k=>$v_v) {
                        $items .= $v_v->plaintext . ' / ';
                    }
                    if ($items) {
                        $row2['writer'] = substr($items,0,-3);
                    }
                }
                $dom = $domCon->find("db:attribute[name=director]");
                if ($dom) {
                    $items = "";
                    foreach ($dom as $k_k=>$v_v) {
                        $items .= $v_v->plaintext . ' / ';
                    }
                    if ($items) {
                        $row2['director'] = substr($items,0,-3);
                    }
                }
                $dom = $domCon->find("db:attribute[name=pubdate]");
                if ($dom) {
                    $items = "";
                    foreach ($dom as $k_k=>$v_v) {
                        $items .= $v_v->plaintext . ' / ';
                    }
                    if ($items) {
                        $row2['pubdate'] = substr($items,0,-3);
                    }
                }
                $dom = $domCon->find("db:attribute[name=aka]");
                if ($dom) {
                    $items = "";
                    foreach ($dom as $k_k=>$v_v) {
                        $items .= $v_v->plaintext . ' / ';
                    }
                    if ($items) {
                        $row2['aka'] = substr($items,0,-3);
                    }
                }
                $dom = $domCon->find("db:attribute[name=movie_type]");
                if ($dom) {
                    $items = "";
                    foreach ($dom as $k_k=>$v_v) {
                        $items .= $v_v->plaintext . ' / ';
                    }
                    if ($items) {
                        $row2['movie_type'] = substr($items,0,-3);
                    }
                }
                $dom = $domCon->find("db:attribute[name=language]");
                if ($dom) {
                    $items = "";
                    foreach ($dom as $k_k=>$v_v) {
                        $items .= $v_v->plaintext . ' / ';
                    }
                    if ($items) {
                        $row2['language'] = substr($items,0,-3);
                    }
                }
                $row2['aka_cn'] = $value['aka_cn'];
                $dom = $domCon->find("db:attribute[name=cast]");
                if ($dom) {
                    $items = "";
                    foreach ($dom as $k_k=>$v_v) {
                        $items .= $v_v->plaintext . ' / ';
                    }
                    if ($items) {
                        $row2['cast'] = substr($items,0,-3);
                    }
                }
                $dom = $domCon->find("gd:rating",0);
                if ($dom) {
                    $row2['douban_average'] = $dom->average;
                    $row2['douban_num_raters'] = $dom->numraters;
                }
                $dom = $domCon->find("db:tag");
                if ($dom) {
                    $items = "";
                    foreach ($dom as $k_k=>$v_v) {
                        $items .= $v_v->name . '('.$v_v->count.')' . ' / ';
                    }
                    if ($items) {
                        $row2['tag'] = substr($items,0,-3);
                    }
                }
                $dom = $domCon->find("db:attribute[name=website]",0);
                if ($dom) {
                    $row2['website'] = $dom->plaintext;
                }
                $row2['updated_at'] = $row2['created_at'] = time();
                
                $domCon->__destruct();

                $rowTmp = array();
                $rowTmp['id'] = $value['id'];
                //已在豆瓣中找过
                $rowTmp['is_ok'] = 1;
                $r = $this->gaTools['mysqldb']->update($tb_name,$rowTmp);
                // dump($rowTmp);
                // echo '----------------------------';
                // dump($row2);
                // die;
                
                $this->gaTools['mysqldb']->escape_row($row2);
                $where2 = ' WHERE douban_id="' . $value['douban_id'].'" ';
                if ($value['imdb_id']) {
                    $where2 .= ' OR imdb_id="' . $value['imdb_id'].'"';
                }
                $aTmp2 = $this->gaTools['mysqldb']->get('SELECT * FROM movie '.$where2);
                if ($aTmp2) {
                    show_msg('<'.$row2['title'].">已存在<br />\r\n");
                    // continue;
                    $row2['id'] = $aTmp2['id'];
                    $r = $this->gaTools['mysqldb']->update('movie',$row2);
                } else {
                    $r = $this->gaTools['mysqldb']->save('movie',$row2);
                }
                show_msg('<'.$row2['title'].">过滤保存成功<br />\r\n");
                if (isset($aTmp[$key])) {
                    unset($aTmp[$key]);
                }
                // die;
            }
        }
        // die;
        
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
        $this->aConfig['tb_name'][0] = 'douban_tmp';
        $this->aConfig['tb_name'][1] = 'imdb_cn';

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
