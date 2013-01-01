<?php  if ( ! defined('ROOT_PATH')) exit('非法访问！！！');
/********************************************************
 Author: NIGG
Version: 1
 Effect: 
   Date: 
  Notes: ?space=10w&action=scan_douban_movie&do=always&end=1
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
        $this->aParameter['start0'] = isset($aArgv[5]) ? $aArgv[5] : '1';
        $this->aParameter['end'] = isset($aArgv[6]) ? $aArgv[6] : '97191';
        
        $this->aScriptNeed['charset_of_getcon'] = 'UTF-8';
        $url = remove_blank($this->aConfig['url_0_template']['con']);
        show_msg("开始扫描豆瓣电影数据.......<br />\r\n");
        $aTmp = $this->gaTools['mysqldb']->find('SELECT id,title,aka_cn,year FROM '.$this->aConfig['tb_name_0'].' WHERE id>='.$this->aParameter['start0'].' AND id<'.$this->aParameter['end'].' AND imdb_id="" AND noin_imdb=0 ORDER BY id ASC');
        $id = isset($aTmp[0]['id']) ? $aTmp[0]['id'] : '1';
        show_msg("从id=“{$id}”开始.......<br />\r\n");
        
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
        
        //mtime 彩色=433
        $this->aConfig['url_0_template']['con'] = 'http://imdbapi.org/?title={@title}&type=json&plot=full&episode=1&limit=1&year={@year}&yg={@yg}&mt=none&lang=zh-CN&offset=&aka=simple&release=simple';
        $this->aConfig['url_0_template']['search_replace'] = array('{@title}','{@year}','{@yg}');
        $this->aConfig['url_0']['p_total'] = 1;

        $countrys = array(
        '美国','香港','日本','中国','英国','法国','韩国','台湾',
        '意大利','德国','内地','泰国','西班牙','印度','欧洲','加拿大',
        '澳大利亚','俄罗斯','伊朗','中国大陆','瑞典','爱尔兰','巴西','波兰',
        '捷克','丹麦','阿根廷','比利时','墨西哥','奥地利','荷兰','匈牙利',
        '土耳其','新加坡','以色列','新西兰'
        );
        $years = array(
        '1988','1988','1988','1988','1988','1988','1988',
        '1988','1988','1988','1988','1988','1988','1988',
        '1988','1988','1988','1988','1988','1988','1988',
        );
        
        $this->aCommConfiger['url_url'] = 'url';
    }
    
    function __destruct()
    {
        
    }
}
