<?php  if ( ! defined('ROOT_PATH')) exit('非法访问！！！');
/********************************************************
 Author: NIGG
Version: 1
 Effect: 
   Date: 
  Notes: ?space=cheat&action=brush_down&ini=ini&num=10
********************************************************/
class brush_down_agent
{
    private $aConfig = array();
    private $aCommConfiger = array();
    private $gaTools = array();
    private $aaGatherHandleError = array();
    private $aParameter = array();
    private $aScriptNeed = array();
    private $user_agents = array();
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
        $this->aParameter['num'] = isset($this->aParameter['num']) ? $this->aParameter['num'] : 10;
        $this->aParameter['ini'] = isset($this->aParameter['ini']) ? $this->aParameter['ini'] : '';
        //app详细页
        $this->aParameter['iurl'] = isset($this->aParameter['iurl']) ? urldecode($this->aParameter['iurl']) : '';
        //下载链接
        $this->aParameter['durl'] = isset($this->aParameter['durl']) ? urldecode($this->aParameter['durl']) : '';
        //页面下载链接的元素选择
        $this->aParameter['durl_wz'] = isset($this->aParameter['durl_wz']) ? $this->aParameter['durl_wz'] : '';
        //title包含的某一文字，用于判断是否打开页面成功
        $this->aParameter['title'] = isset($this->aParameter['title']) ? $this->aParameter['title'] : '';
        //下载限定时长
        $this->aParameter['dtime'] = isset($this->aParameter['dtime']) ? $this->aParameter['dtime'] : '';
        ####################################################
        
        if ($this->aParameter['ini']) {
            $this->ini = &get_config($this->aParameter['ini']);
            $this->aParameter['iurl'] = isset($this->ini['iurl']) ? urldecode($this->ini['iurl']) : '';
            $this->aParameter['durl'] = isset($this->ini['durl']) ? urldecode($this->ini['durl']) : '';
            $this->aParameter['durl_wz'] = isset($this->ini['durl_wz']) ? $this->ini['durl_wz'] : '';
            $this->aParameter['title'] = isset($this->ini['title']) ? $this->ini['title'] : '';
            $this->aParameter['num'] = isset($this->ini['num']) ? $this->ini['num'] : $this->aParameter['num'];
            $this->aParameter['dtime'] = isset($this->ini['dtime']) ? $this->ini['dtime'] : $this->aParameter['dtime'];
        }
        
//         dump($this->aParameter);
//         die;
        
        $is_no_nulls = array('iurl');
        if ($is_no_nulls) foreach ($is_no_nulls as $k=>$v) {
            if (!isset($this->aParameter[$v]) || $this->aParameter[$v] == '') {
                show_error($v."不能为空");
            }
        }
        if ($this->aParameter['durl'] == '' && $this->aParameter['durl_wz'] == '') {
            show_error("durl & durl_wz 至少一个不为空");
        }
        
        if (!is_numeric($this->aParameter['num'])) {
            show_error("num需要是一个数字");
        }
        
        $this->user_agents = &get_config("user_agents");
        
        $t_time = 18*3600;
        $run_num = $this->aParameter['num'];
        $i = 0;
        do {
            rand_sleep($run_num);
            show_msg($i.".....".TNL);
            if ($this->is_true_time() && $run_num != $this->aParameter['num']) {
                $run_num = $this->aParameter['num']/3;
                show_msg("run_num=".$run_num.".....".TNL);
            }
            $this->run();
//             die;
            $i++;
        } while($i < $run_num);
        
        show_msg("报告刷了{$i}次<br />\r\n");
        die;
    }
    
    function rand_sleep($run_num)
    {
        $t_time = 3600-2*$run_num-2;
        $max = 3600/$run_num;
        $min = (3600/$run_num)/2;
        sleep(rand($min,$max));
    }
    
    function is_true_time() {
        $r = false;
        $y=date("Y",time()); 
        $m=date("m",time()); 
        $d=date("d",time()); 
        $start_time = mktime(6, 0, 0, $m, $d ,$y); 
        $end_time = mktime(23, 59, 59, $m, $d ,$y);
        $time = time(); 
        if($time >= $start_time && $time <= $end_time) 
        { 
            $r = true;
        }
        return $r;
    }
    
    function run()
    {
        //'http://www.nduoa.com/apk/detail/515534'
        //'http://www.nduoa.com/apk/download/515534?from=ndoo'
        
        $curl_p = array();
        $ip_0 = make_internal_ip();
        $ip_1 = make_internal_ip();
        
//         $url = "http://iframe.ip138.com/ic.asp";
//         $url = "http://ip123.com";
        
        $curl_p['CURLOPT_URL'] = $this->aParameter['iurl'];
        $curl_p['CURLOPT_USERAGENT'] = $this->get_rand_user_agent($this->user_agents);
        $curl_p['CURLOPT_HTTPHEADER'] = array('CLIENT-IP:'.$ip_0, 'X-FORWARDED-FOR:'.$ip_1);
        
        $result = $this->net_engine($curl_p);
        if (preg_match("/^\\\$_\\\$Errno\:(.*)/", $result['msg'], $arr)) {
            $error_msg = $arr[1];
            show_msg($error_msg.TNL);
        }
        $r_0 = "";
        if ($this->aParameter['title'] != '') {
            if (preg_match("/<title>.*?(".$this->aParameter['title'].").*?<\/title>/", $result['con'], $arr)) {
                $r_0 = $arr[1];
            }
        } else {
            $r_0 = 'yes';
        }
        if ($r_0 != "") {
            show_msg($r_0.TNL);
            //开刷 
            if ($this->aParameter['durl'] != '') {
                $curl_p['CURLOPT_URL'] = $this->aParameter['durl'];
            } else {
                //根据durl_wz选择
                $curl_p['CURLOPT_URL'] = '';
            }
            if ($curl_p['CURLOPT_URL'] != '') {
                $curl_p['CURLOPT_TIMEOUT'] = $this->aParameter['dtime'] ? $this->aParameter['dtime'] : rand(3,12);
                $result = $this->net_engine($curl_p);
                if (preg_match("/^\\\$_\\\$Errno\:(.*)/", $result['msg'], $arr)) {
                    $error_msg = $arr[1];
                    show_msg($error_msg.TNL);
                    return false;
                }
            } else {
                return false;
            }
        } else {
            show_msg("已被封".TNL);
            return false;
        }
        return true;
    }
    
    function get_rand_user_agent($user_agents)
    {
        return $user_agents[rand(0,(count($user_agents)-1))];
    }
    
        


    function net_engine ($p = array()){
        if (!isset($p['CURLOPT_URL']) || $p['CURLOPT_URL'] == "") {
            return false;
        }
        $curl = curl_init();
        if (isset($p['cksfile']) && $p['cksfile'] != "") {
            $cksfile = $p['cksfile'];
        } else {
            $cksfile = COOKIES_PATH.'cookie_file.txt';
        }
        
        if (isset($p['CURLOPT_PROXY']) && $p['CURLOPT_PROXY'] != "") {
            curl_setopt ($curl, CURLOPT_PROXY, $p['CURLOPT_PROXY']);
        }
        if (isset($p['CURLOPT_URL']) && $p['CURLOPT_URL'] != "") {
            curl_setopt ($curl, CURLOPT_URL, $p['CURLOPT_URL']);
        }
        if (isset($p['CURLOPT_USERAGENT']) && $p['CURLOPT_USERAGENT'] != "") {
            curl_setopt ($curl, CURLOPT_USERAGENT, $p['CURLOPT_USERAGENT']);
        }
        if (isset($p['CURLOPT_HTTPHEADER']) && $p['CURLOPT_HTTPHEADER'] != "") {
            //此处可以改为任意假IP
            curl_setopt ($curl, CURLOPT_HTTPHEADER, $p['CURLOPT_HTTPHEADER']);
        }
        $p['CURLOPT_FOLLOWLOCATION'] = isset($p['CURLOPT_FOLLOWLOCATION']) && $p['CURLOPT_HTTPHEADER'] != "" ? $p['CURLOPT_FOLLOWLOCATION'] : 1;
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, $p['CURLOPT_FOLLOWLOCATION']);

        $p['CURLOPT_COOKIESESSION'] = isset($p['CURLOPT_COOKIESESSION']) && $p['CURLOPT_COOKIESESSION'] != "" ? $p['CURLOPT_COOKIESESSION'] : 1;
        curl_setopt($curl, CURLOPT_COOKIESESSION, $p['CURLOPT_COOKIESESSION']);

        curl_setopt($curl, CURLOPT_COOKIEJAR, $cksfile); //读上次cookie
        curl_setopt($curl, CURLOPT_COOKIEFILE, $cksfile); //写本次cookie
        
        $p['CURLOPT_TIMEOUT'] = isset($p['CURLOPT_TIMEOUT']) && $p['CURLOPT_TIMEOUT'] != "" ? $p['CURLOPT_TIMEOUT'] : 90;
        curl_setopt($curl, CURLOPT_TIMEOUT, $p['CURLOPT_TIMEOUT']); // 设置超时限制防止死循环
        
        $p['CURLOPT_HEADER'] = isset($p['CURLOPT_HEADER']) && $p['CURLOPT_HEADER'] != "" ? $p['CURLOPT_HEADER'] : 0;
        curl_setopt($curl, CURLOPT_HEADER, $p['CURLOPT_HEADER']); // 是否显示返回的Header区域内容
        
        $p['CURLOPT_RETURNTRANSFER'] = isset($p['CURLOPT_RETURNTRANSFER']) && $p['CURLOPT_RETURNTRANSFER'] != "" ? $p['CURLOPT_RETURNTRANSFER'] : 1;
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, $p['CURLOPT_RETURNTRANSFER']); // 获取的信息以文件流的形式返回
        $result = curl_exec($curl); // 执行操作
        $aResult = array();
        $aResult['msg'] = 'Success';
        $aResult['con'] = $result;
        if (curl_errno($curl)) {
            $aResult['msg'] = '$_$Errno:'.curl_error($curl);//捕抓异常
        }
        curl_close($curl);
        return $aResult;
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
        #信息模板
        $this->aConfig['error_template']['con'] = '采集“@sName”“@sGoUrl”出错，在脚本：“@sCfile”，的第“@sCline”行附近';
        $this->aConfig['error_template']['search_replace'] = array('@sName','@sGoUrl','@sCfile','@sCline');
        
        $this->aConfig['html_charset'] = 'gbk';
        
        $this->aConfig['tb_name_0'] = 'fuli_tmp';
        
    }
    
    function __destruct()
    {
        
    }
}
