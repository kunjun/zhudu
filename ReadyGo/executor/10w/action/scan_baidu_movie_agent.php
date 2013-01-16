<?php  if ( ! defined('ROOT_PATH')) exit('非法访问！！！');
/********************************************************
 Author: NIGG
Version: 1
 Effect: 
   Date: 
  Notes: ?space=10w&action=scan_baidu_movie&do=always&end=1
  php ../index.php 10w scan_baidu_movie 1 gbk 1
********************************************************/
class scan_baidu_movie_agent
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
        ####################################################

        $aArgv = $GLOBALS['argv'];
        $this->aParameter['is_read_log'] = isset($aArgv[5]) ? $aArgv[5] : '1';
        
        $log_file = DATAS_PATH.'scan_baidu_movie_agent.log';
        create_dir(DATAS_PATH);
        $this->aScriptNeed['log_file_tmp'] = DATAS_PATH.'tmp_html.html';
        $this->aScriptNeed['charset_of_getcon'] = 'UTF-8';
        $url = remove_blank($this->aConfig['url_0_template']['con']);
        show_msg("开始扫描百度电影数据.......<br />\r\n");
        $p_total = 0;
        $log_i = 0;
        if ($this->aParameter['is_read_log'] == '1' && file_exists($log_file)) {
            $data = file_get_contents($log_file);
            show_msg("读取断点记录：".$data."<br />\r\n");
            // return;
            $log_i = $data;
        }
        // dump($this->aParameter);
        // die;
        for ($i=1;$i<=$this->aConfig['url_0']['p_total'];$i++) {
            // $i = 16;
            if ($i < $log_i) {
                continue;
            }
            $this->aScriptNeed['url'] = str_replace($this->aConfig['url_0_template']['search_replace'], array($i), $url);        
            $r = $this->filter();
            if ($r === false) {
                $data = $i;
                show_msg("断点记录：".$data."<br />\r\n");
                file_put_contents($log_file, $data);
                return;
            }
            // dump($r);
            show_msg("本页数：".count($r)."<br />\r\n");
            $r = $this->save($r);
            if ($r === false) {
                $data = $i;
                show_msg("断点记录：".$data."<br />\r\n");
                file_put_contents($log_file, $data);
                return;
            } else {
                show_msg("url=".$this->aScriptNeed['url']."保存成功！！<br />\r\n");
            }
            // show_msg("第一页测试完成"."---保存成功！！<br />\r\n");
            // die;
        }
    }
    
    private function save($data)
    {
        try{
            if ($data) {
                foreach ($data as $key => $value) {
                    $this->gaTools['mysqldb']->escape($value);
                    $tmp = $this->gaTools['mysqldb']->load('online_video',  $value['url_md5'], 'url_md5');
                    // $tmp = false;
                    if ($tmp) {
                        continue;
                    }
                    $this->gaTools['mysqldb']->save('online_video', $value);
                }
            }
            return true;
        } catch(Exception $e) {
            show_msg($e->getMessage());
            return false;
        }
    }

    private function filter()
    {
        $aCollectionResult = array();
        $sUrl = $this->aScriptNeed['url'];
        show_msg($sUrl."...<br />\r\n");
        $sCon = meclient($sUrl);
        // file_put_contents($this->aScriptNeed['log_file_tmp'], $sCon);
        $sCon = iconv($this->aConfig['html_charset'],DEFAULT_CHARSET.'//IGNORE',$sCon);
        $domCon = str_get_html($sCon);
        if (!$domCon->find(".nav")) {
            return false;
        }
        $domTmp = $domCon->find("div.no-result",0);
        if ($domTmp) {
            return 'endpage';
        }
        
        $domItems = $domCon->find('ul.video-item-list li');
        if ($domItems) {
            // show_msg(count($domItems)."<br />\r\n");
            // die;
            foreach ($domItems as $key => $value) {
                $row = array();
                $domTmp = $value->find('dt.v-title a',0);
                if ($domTmp) {
                    if (isset($domTmp->href)) {
                        $row['title'] = $domTmp->title;
                        $row['url'] = $domTmp->href;
                    } else {
                        continue;
                    }
                    if ($row && isset($row['url']) && $row['url'] != '') {
                        $row['updated_at'] = time();
                        $row['url_md5'] = md5($row['url']);
                        $row['site_name'] = '百度';
                        $aCollectionResult[] = $row;
                    }
                }
            }
        }
        
        $domCon->__destruct();
        // dump($aCollectionResult);
        // die;
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
        
        $this->aConfig['html_charset'] = 'GBK';
        
        $this->aConfig['url_0_template']['con'] = 'http://video.baidu.com/movie/?order=hot&pn={@pn}';
        $this->aConfig['url_0_template']['search_replace'] = array('{@pn}');
        $this->aConfig['url_0']['p_total'] = 1475;

        // $this->aConfig['countrys'] = array(
        // '美国','香港','日本','中国','英国','法国','韩国','台湾',
        // '意大利','德国','内地','泰国','西班牙','印度','欧洲','加拿大',
        // '澳大利亚','俄罗斯','伊朗','中国大陆','瑞典','爱尔兰','巴西','波兰',
        // '捷克','丹麦','阿根廷','比利时','墨西哥','奥地利','荷兰','匈牙利',
        // '土耳其','新加坡','以色列','新西兰'
        // );
        $this->aConfig['countrys'] = array(
        '香港','日本','中国','内地','中国大陆','台湾'
        );
        // $this->aConfig['years'] = array(
        // '1988','1989','1990','1991','1992','1993','1994',
        // '1995','1996','1997','1998','2000','2001','2002',
        // '2003','2004','2005','2006','2007','2008','2009',
        // '2010','2011','2012',
        // );
        $this->aConfig['years'] = array(
        '1920','1921','1922','1923','1924','1925','1926',
        '1927','1928','1929','1930','1931','1932','1933',
        '1934','1935','1936','1937','1938','1939','1940',
        '1941','1942','1943','1944','1945','1946','1947',
        '1948','1949','1950','1951','1952','1953','1954',
        '1955','1956','1957','1958','1959','1960','1961',
        '1962','1963','1964','1965','1966','1967','1968',
        '1962','1963','1964','1965','1966','1967','1968',
        '1969','1970','1971','1972','1973','1974','1975',
        '1976','1977','1978','1979','1980','1981','1982',
        '1983','1984','1985','1986','1987',
        );
        
        $this->aCommConfiger['url_url'] = 'url';
    }
    
    function __destruct()
    {
        
    }
}
