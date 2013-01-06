<?php  if ( ! defined('ROOT_PATH')) exit('非法访问！！！');
/********************************************************
 Author: NIGG
Version: 1
 Effect: 
   Date: 
  Notes: ?space=10w&action=scan_douban_movie&do=always&end=1
  php ../index.php 10w scan_douban_movie 1 gbk 23 1 23 1
********************************************************/
class scan_douban_movie_agent
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
        $this->aParameter['start'] = isset($aArgv[5]) ? $aArgv[5] : '0';
        $this->aParameter['start1'] = isset($aArgv[6]) ? $aArgv[6] : '0';
        $this->aParameter['end'] = isset($aArgv[7]) ? $aArgv[7] : count($this->aConfig['years'])-1;
        $this->aParameter['end1'] = isset($aArgv[8]) ? $aArgv[8] : count($this->aConfig['countrys'])-1;
        $this->aParameter['is_read_log'] = isset($aArgv[9]) ? $aArgv[9] : '1';
        
        $log_file = DATAS_PATH.'scan_douban_movie_agent.log';
        $this->aScriptNeed['log_file_tmp'] = $log_file_tmp = DATAS_PATH.'tmp.log';
        create_dir(DATAS_PATH);
        $this->aScriptNeed['charset_of_getcon'] = 'UTF-8';
        $url = remove_blank($this->aConfig['url_0_template']['con']);
        show_msg("开始扫描豆瓣电影数据.......<br />\r\n");
        $p_total = 0;
        $log_k = 0;
        $log_k1 = 0;
        $log_i = 0;
        if ($this->aParameter['is_read_log'] == '1' && file_exists($log_file)) {
            $data = file_get_contents($log_file);
            show_msg("读取断点记录：".$data."<br />\r\n");
            // return;
            $arr = split(" ", $data);
            $log_k = isset($arr[0]) ? $arr[0] : 0;
            $log_k1 = isset($arr[1]) ? $arr[1] : 0;
            $log_i = isset($arr[2]) ? $arr[2] : 0;
        }
        // dump($this->aParameter);
        // die;
        foreach ($this->aConfig['years'] as $k=>$v) {
            if ($k < $this->aParameter['start'] || $k < $log_k) {
                continue;
            }
            if ($k > $this->aParameter['end']) {
                show_msg("程序退出;year=".$v."@".$k."<br />\r\n");
                break;
            }
            foreach ($this->aConfig['countrys'] as $k1=>$v1) {
                if ($k1 < $this->aParameter['start1'] || $k1 < $log_k1) {
                    continue;
                }
                if ($k1 > $this->aParameter['end1']) {
                    show_msg("程序退出;year=".$v."@".$k."::country=".$v1."@".$k1."<br />\r\n");
                    break;
                }
                for ($i=1;$i<=$this->aConfig['url_0']['p_total'];$i++) {
                    // $i = 16;
                    if ($i < $log_i) {
                        continue;
                    }
                    $satrt = ($i-1)*20;
                    $this->aScriptNeed['url'] = str_replace($this->aConfig['url_0_template']['search_replace'], array($v,$v1,$satrt), $url);        
                    $r = $this->filter();
                    if ($r === 'endpage') {
                        show_msg("endpage<br />\r\n");
                        break;
                    }
                    if ($r === false) {
                        $data = $k." ".$k1." ".$i;
                        show_msg("断点记录：".$data."<br />\r\n");
                        file_put_contents($log_file, $data);
                        return;
                    }
                    // dump($r);
                    show_msg("本页数：".count($r)."<br />\r\n");
                    $r = $this->save($r);
                    if ($r === false) {
                        $data = $k." ".$k1." ".$i;
                        show_msg("断点记录：".$data."<br />\r\n");
                        file_put_contents($log_file, $data);
                        return;
                    } else {
                        show_msg("url=".$this->aScriptNeed['url']."保存成功！！<br />\r\n");
                    }
                }
                show_msg("--------------------------------------------------------------<br />\r\n");
                show_msg("".$v1.$v."全部"."保存成功！！<br />\r\n");
                $p_total = 0;
            }
        }
        
    }
    
    private function save($data)
    {
        try{
            if ($data) {
                foreach ($data as $key => $value) {
                    $this->gaTools['mysqldb']->escape($value);
                    $tmp = $this->gaTools['mysqldb']->load('douban_tmp',  $value['douban_id'], 'douban_id');
                    // $tmp = false;
                    if ($tmp) {
                        continue;
                    }
                    $this->gaTools['mysqldb']->save('douban_tmp', $value);
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
        $domCon = str_get_html($sCon);
        if (!$domCon->find("#content")) {
            return false;
        }
        $domTmp = $domCon->find("#subject_list p",0);
        if ($domTmp) {
            if (strpos($domTmp->plaintext, "没有找到符合条件的电影") !== false) {
                show_msg($domTmp->plaintext);
                return 'endpage';
            }
        }
        
        $domItems = $domCon->find('tr.item');
        if ($domItems) {
            show_msg(count($domItems)."<br />\r\n");
            // die;
            foreach ($domItems as $key => $value) {
                $row = array();
                $domTmp = $value->find('.nbg',0);
                if ($domTmp) {
                    if (isset($domTmp->href)) {
                        if (preg_match('/(\d+?)\/?$/', $domTmp->href,$aTmp)) {
                            $row['douban_id'] = $aTmp[1];
                        }
                    }
                    $domTmp = $domTmp->find('img',0);
                    if ($domTmp) {
                        if (isset($domTmp->src)) {
                            $row['douban_image'] = $domTmp->src;
                        }
                        if (isset($domTmp->alt)) {
                            $row['aka_cn'] = $domTmp->alt;
                        }
                    }
                    if ($row) {
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
        
        $this->aConfig['html_charset'] = 'UTF-8';
        
        $this->aConfig['url_0_template']['con'] = 'http://movie.douban.com/tag/{@year}%20{@country}?start={@start}';
        $this->aConfig['url_0_template']['search_replace'] = array('{@year}','{@country}','{@start}');
        $this->aConfig['url_0']['p_total'] = 50;

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
