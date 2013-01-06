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
//         dump($this->aParameter);
        
        $this->aScriptNeed['charset_of_getcon'] = 'UTF-8';
        
        show_msg("开始获取豆瓣电影数据.......<br />\r\n");
        $where = ' WHERE 1 ';
        if ($this->aParameter['start0'] != '') {
            $where .= ' AND id>='.$this->aParameter['start0'];
        }
        if ($this->aParameter['end'] != '') {
            $where .= ' AND id<'.$this->aParameter['end'];
        }
        $aTmp = $this->gaTools['mysqldb']->find('SELECT * FROM douban_tmp '.$where.' AND is_ok=0 ORDER BY id ASC');
        $id = isset($aTmp[0]['id']) ? $aTmp[0]['id'] : '';
        show_msg("从id=“{$id}”开始.......<br />\r\n");
        // echo count($aTmp);
        // die;

        if (!empty($aTmp)) {
            $aCollectionResult = array();
            foreach ($aTmp as $key => $value) {
                show_msg("id={$value['id']}.......");
                $row2 = array();

                $url = remove_blank($this->aConfig['url_0_template']['con']);
                $this->aScriptNeed['url_url_0'] = str_replace($this->aConfig['url_0_template']['search_replace'], $value['douban_id'], $url);
                $sUrl = $this->aScriptNeed['url_url_0'];
                
                $doubanContent = meclient($sUrl);
                $domCon = str_get_html($doubanContent);
                $dom = $domCon->find("id",0);
                //判断是否被封
                if (!$dom) {
                    show_msg("内容：".$doubanContent."<br />\r\n");
                    if (preg_match("/bad imdb id/",$doubanContent) || preg_match("/wrong subject id/",$doubanContent)) {
                        $rowTmp = array();
                        $rowTmp['id'] = $value['id'];
                        $rowTmp['is_ok'] = 2;
                        $r = $this->gaTools['mysqldb']->update('movie',$rowTmp);
                        continue;
                    } else {
                        show_msg("尼玛！douban_id没有！！被封？？<br />\r\n");
                        die;
                    }
                }
                
                $dom = $domCon->find("db:attribute[name=episodes]",0);
                if ($dom) {
                    show_msg("电视剧《".$value['aka_cn']."》；集数：".$dom->plaintext."<br />\r\n");
                    continue;
                }
                $row2['douban_id'] = $value['douban_id'];
                $dom = $domCon->find("db:attribute[name=imdb]",0);
                if ($dom) {
                    if (preg_match("/(tt\d+)/",$dom->plaintext,$arr)) {
                        $row2['imdb_id'] = $arr[1];
                    }
                }
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
                $dom = $domCon->find("db:attribute[name=movie_duration");
                if ($dom) {
                    $items = "";
                    foreach ($dom as $k_k=>$v_v) {
                        $items .= $v_v->plaintext . ' / ';
                    }
                    if ($pubdates) {
                        $row2['movie_duration'] = substr($items,0,-3);
                    }
                }
                $dom = $domCon->find("db:attribute[name=country");
                if ($dom) {
                    $items = "";
                    foreach ($dom as $k_k=>$v_v) {
                        $items .= $v_v->plaintext . ' / ';
                    }
                    if ($pubdates) {
                        $row2['country'] = substr($items,0,-3);
                    }
                }
                $dom = $domCon->find("db:attribute[name=writer");
                if ($dom) {
                    $items = "";
                    foreach ($dom as $k_k=>$v_v) {
                        $items .= $v_v->plaintext . ' / ';
                    }
                    if ($pubdates) {
                        $row2['writer'] = substr($items,0,-3);
                    }
                }
                $dom = $domCon->find("db:attribute[name=director");
                if ($dom) {
                    $items = "";
                    foreach ($dom as $k_k=>$v_v) {
                        $items .= $v_v->plaintext . ' / ';
                    }
                    if ($pubdates) {
                        $row2['director'] = substr($items,0,-3);
                    }
                }
                $dom = $domCon->find("db:attribute[name=pubdate");
                if ($dom) {
                    $items = "";
                    foreach ($dom as $k_k=>$v_v) {
                        $items .= $v_v->plaintext . ' / ';
                    }
                    if ($pubdates) {
                        $row2['pubdate'] = substr($items,0,-3);
                    }
                }
                $dom = $domCon->find("db:attribute[name=aka");
                if ($dom) {
                    $items = "";
                    foreach ($dom as $k_k=>$v_v) {
                        $items .= $v_v->plaintext . ' / ';
                    }
                    if ($pubdates) {
                        $row2['aka'] = substr($items,0,-3);
                    }
                }
                $dom = $domCon->find("db:attribute[name=movie_type");
                if ($dom) {
                    $items = "";
                    foreach ($dom as $k_k=>$v_v) {
                        $items .= $v_v->plaintext . ' / ';
                    }
                    if ($pubdates) {
                        $row2['movie_type'] = substr($items,0,-3);
                    }
                }
                $dom = $domCon->find("db:attribute[name=language");
                if ($dom) {
                    $items = "";
                    foreach ($dom as $k_k=>$v_v) {
                        $items .= $v_v->plaintext . ' / ';
                    }
                    if ($pubdates) {
                        $row2['language'] = substr($items,0,-3);
                    }
                }
                $row2['aka_cn'] = $value['aka_cn'];
                $dom = $domCon->find("db:attribute[name=cast");
                if ($dom) {
                    $items = "";
                    foreach ($dom as $k_k=>$v_v) {
                        $items .= $v_v->plaintext . ' / ';
                    }
                    if ($pubdates) {
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
                    if ($pubdates) {
                        $row2['tag'] = substr($tags,0,-3);
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
                $rowTmp['is_ok'] = 1;
                // $r = $this->gaTools['mysqldb']->update('movie',$rowTmp);
                dump($rowTmp);
                echo '----------------------------';
                dump($row2);
                die;
                
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
        
        $this->aConfig['url_0_template']['con'] = 'http://api.douban.com/movie/subject/{@douban_id}?apikey=06953f4549257b8213bfbdcfdb707286';
        $this->aConfig['url_0_template']['search_replace'] = array('{@douban_id}');
        $this->aConfig['url_1_template']['con'] = 'http://api.douban.com/movie/subject/imdb/{@imdb_id}?apikey=06953f4549257b8213bfbdcfdb707286';
        $this->aConfig['url_1_template']['search_replace'] = array('{@imdb_id}');
        
        $this->aCommConfiger['url_url'] = 'url';
    }
    
    function __destruct()
    {
        
    }
}
