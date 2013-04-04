<?php  if ( ! defined('ROOT_PATH')) exit('非法访问！！！');
/********************************************************
 Author: NIGG
Version: 1
 Effect: 
   Date: 
  Notes: ?space=cheat&action=brush_down&do=always&end=1
********************************************************/
class brush_down_agent
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
//         $aArgv = $GLOBALS['argv'];
//         $this->aParameter['start0'] = isset($aArgv[5]) ? $aArgv[5] : '1';
//         $this->aParameter['end'] = isset($aArgv[6]) ? $aArgv[6] : '97191';
        ##################
        
        ##################
        
        dump($_REQUEST);
        dump($_GET);
        die;
        
        $i = 0;
        do {
            sleep(3);
            $this->run();
            $i++;
        } while($i < 10);
        
        show_msg("报告刷了{$i}次<br />\r\n");
        die;
    }
    
    function run()
    {
        $CURLOPT_TIMEOUT = rand(4,12);
        $ip_0 = make_internal_ip();
        $ip_1 = make_internal_ip();
        $CURLOPT_HTTPHEADER = array('CLIENT-IP:'.$ip_0, 'X-FORWARDED-FOR:'.$ip_1);
        $url = "http://iframe.ip138.com/ic.asp";
        $url = "http://ip123.com";
        $user_agent = "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1;  .NET CLR 2.0.50727; .NET CLR 3.0.04506.648; .NET CLR 3.5.21022; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729; InfoPath.2;";
        $proxy = "http://202.99.27.3:8080";    //此处为代理服务器IP和PORT
        
        $url = 'http://www.nduoa.com/apk/detail/515534';
        $string = curl_string($url,$user_agent,$proxy,$CURLOPT_HTTPHEADER,$CURLOPT_TIMEOUT);
        //$string = iconv("utf-8","utf-8",$string);
        $r_0 = "";
        if (preg_match("/<title>(合金弹头叉版.*?)|.*?<\/title>/", $string, $arr)) {
            $r_0 = $arr[1];
        }
        if ($r_0 != "") {
            show_msg($r_0."<br />\r\n");
            //开刷
            $url = 'http://www.nduoa.com/apk/download/515534?from=ndoo';
            $string = curl_string($url,$user_agent,$proxy,$CURLOPT_HTTPHEADER,$CURLOPT_TIMEOUT);
        } else {
            show_msg("已被封"."<br />\r\n");
        }
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
        
        $this->aConfig['have'] = &get_config('citys_info');
        $this->aConfig['tb_name_0'] = 'fuli_tmp';
        $this->aConfig['tb_name_1'] = 'fuli';
        
        $this->aConfig['url_0_template']['con'] = 'http://www.umei.cc/p/gaoqing/index-{@p}.htm';
        $this->aConfig['url_0_template']['search_replace'] = array('{@p}');
        $this->aConfig['url_0']['p_total'] = 46;

        
        
        $this->aCommConfiger['url_url'] = 'url';
    }
    
    function __destruct()
    {
        
    }
}
