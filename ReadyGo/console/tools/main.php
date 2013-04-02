<?php
/**
 * php_curl模块模拟客户端
 *
 * @param string $url     -------- 要提交的地址，如果是GET方式参数就放这边，如：xxx.xxx.xxx/xxx.xxx?xxx1=xxx1_value&xxx2=xxx2_value&...
 * @param string $data    -------- 如果是POST提交，才要这传入这个参数，
 * @return string
 */
function meclient($url='',$data='',$header=array())
{
    if ($url == '') {
        return '';
    }
    $cksfile = COOKIES_PATH.'cookie_file.txt';
//    echo $cksfile;
    // 模拟提交数据函数
    $curl = curl_init(); // 启动一个CURL会话
    curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
    //判断是不是https
    if (strpos($url,'https://') === 0) {
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1); // 从证书中检查SSL加密算法是否存在
    }

    // if (!isset($_SERVER['HTTP_USER_AGENT'])) {
    // 	$http_user_agent = "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1;  .NET CLR 2.0.50727; .NET CLR 3.0.04506.648; .NET CLR 3.5.21022; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729; InfoPath.2; {@d})";
    // } else {
    // 	$http_user_agent = $_SERVER['HTTP_USER_AGENT'];
    // }
    $http_user_agent = "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1;  .NET CLR 2.0.50727; .NET CLR 3.0.04506.648; .NET CLR 3.5.21022; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729; InfoPath.2; {@d})";
    $http_user_agent = str_replace('{@d}', 'http://www.baidu.com', $http_user_agent);
    // echo $http_user_agent."\r\n<br />";
        curl_setopt($curl, CURLOPT_USERAGENT, $http_user_agent); // 模拟用户使用的浏览器
//     curl_setopt($curl, CURLOPT_USERAGENT, $user_agent);//HTTP请求User-Agent:头
    //curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
    curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
    if ($data != '') {
        curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data); // Post提交的数据包
    }
    //用CURLOPT_FOLLOWLOCATION会出错，所以改成下面
//    curl_redir_exec($curl);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_COOKIESESSION, 1);
    curl_setopt($curl, CURLOPT_COOKIEJAR, $cksfile); //读上次cookie
    curl_setopt($curl, CURLOPT_COOKIEFILE, $cksfile); //写本次cookie
    curl_setopt($curl, CURLOPT_TIMEOUT, 160); // 设置超时限制防止死循环
    curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
    if (!empty($header)) {
        /*
         * 例：冒号前是Key后是Value
         $aHeader = array(
            'Authorization:GoogleLogin auth=' . 'google认证的auth值',
        );
         */
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
    }
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
    $tmpInfo = curl_exec($curl); // 执行操作
    if (curl_errno($curl)) {
        echo 'Errno'.curl_error($curl);//捕抓异常
    }
//    preg_match_all('/Set-Cookie:(.*?)=(.*?);/is',$tmpInfo,$regs);
//print_r($regs);
    curl_close($curl); // 关闭CURL会话
    return $tmpInfo; // 返回数据
}

function curl_redir_exec($ch,$debug="") 
{ 
    static $curl_loops = 0; 
    static $curl_max_loops = 20; 

    if ($curl_loops++ >= $curl_max_loops) 
    { 
        $curl_loops = 0; 
        return FALSE; 
    } 
    curl_setopt($ch, CURLOPT_HEADER, true); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
    $data = curl_exec($ch); 
    $debbbb = $data; 
    $header = '';
    $data = '';
    $aTmp = explode("\n\n", $data, 2); 
    $header = isset($aTmp[0]) ? $aTmp[0] : '';
    $data = isset($aTmp[1]) ? $aTmp[1] : '';
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE); 

    if ($http_code == 301 || $http_code == 302) { 
        $matches = array(); 
        preg_match('/Location:(.*?)\n/', $header, $matches); 
        $url = @parse_url(trim(array_pop($matches))); 
        //print_r($url); 
        if (!$url) 
        { 
            //couldn't process the url to redirect to 
            $curl_loops = 0; 
            return $data; 
        } 
        $last_url = parse_url(curl_getinfo($ch, CURLINFO_EFFECTIVE_URL)); 
    /*    if (!$url['scheme']) 
            $url['scheme'] = $last_url['scheme']; 
        if (!$url['host']) 
            $url['host'] = $last_url['host']; 
        if (!$url['path']) 
            $url['path'] = $last_url['path'];*/ 
        $new_url = $url['scheme'] . '://' . $url['host'] . $url['path'] . ($url['query']?'?'.$url['query']:''); 
        curl_setopt($ch, CURLOPT_URL, $new_url); 
    //    debug('Redirecting to', $new_url); 

        return curl_redir_exec($ch); 
    } else { 
        $curl_loops=0; 
        return $debbbb; 
    } 
} 

function create_dir($pDir){
  return is_dir($pDir) or (create_dir(dirname($pDir)) and mkdir($pDir, 0777));
 }

function make_5_str()
{
    $str="qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLMNBVCXZ";
    $s="";
    $i=rand(1,52);
    for($j=1;$j<6;$j++){
        $s=$s.substr($str,$i-1,1); //在字符串$str中取从$i-1开始的1个字符
    }
    return $s;
}

/**
 * 去掉无用字符
 *
 * @param string $str
 * @return string
 */
function remove_invalid($str)
{
    if ($str == '') {
        return $str;
    }
    $str = trim($str);
    $aTmp = array("\n","\r","\t","&nbsp;");
    $aReplace = array("","","","");
    $str = str_replace($aTmp,$aReplace,$str);
    return $str;
}

/**
 * 去掉空白字符
 *
 * @param string $str
 * @return string
 */
function remove_blank($str)
{
    if ($str == '') {
        return $str;
    }
    $str = trim($str);
    $str = preg_replace('/\s(?=\s)/', '', $str);
    $str = preg_replace('/[\n\r\t]/', '', $str);
    return $str;
}

/**
 * 按时间大小排序
 *
 * @param array or string $e
 * @param string $str
 * @return array
 */
function array_intime_sort($e,$str='')
{
    $r = '';
    $lin_r = '';
    if (!is_array($e)) {
        $a_sorttmp = explode($e,$str);
    } else {
        $a_sorttmp = $e;
    }
    if (!empty($a_sorttmp)) {
        sort($a_sorttmp);
        $aTmp = array();
        foreach ($a_sorttmp as $a_sorttmp_v) {
            if (in_array($a_sorttmp_v,$aTmp)) {
                continue;
            }
            $aTmp[] = $a_sorttmp_v;
            if ($a_sorttmp_v <= '03:00') {
                $lin_r .= '&nbsp;'.$a_sorttmp_v;
                continue;
            }
            $r .= '&nbsp;'.$a_sorttmp_v;
        }
    }
    if ($r != '') {
        $r = substr($r,6).$lin_r;
    } elseif ($lin_r != '') {
        $r = substr($lin_r,6);
    }
    return $r;
}


/**
 * 合并电影名相同的数据
 *
 * @param array $aaP
 * @return array $aaResult
 */
function merge_result($aaP)
{
    $aaTmp = array();
    foreach ($aaP as $v) {
        if (array_key_exists($v['movie_name'],$aaTmp)) {
            $aaTmp[$v['movie_name']]['movie_time'] = array_merge($aaTmp[$v['movie_name']]['movie_time'], $v['movie_time']);
        } else {
            $aaTmp[$v['movie_name']] = $v;			
        }
    }
    $i = 0;
    $aaResult = array();
    if (!empty($aaTmp)) {
        foreach ($aaTmp as $v) {
            $aaResult[$i] = $v;
            $i++;
        }
    }
    return $aaResult;
}

/**
 * 返回指定的天数时间戳
 *
 * @param int $iNum
 * @return array
 */
function get_date($iNum=2)
{
    $aTmp = array();
    for ($i=0;$i<$iNum;$i++) {
        $iStamp = strtotime('+'.$i.' day');
        $aTmp[$i] = $iStamp;
    }
    return $aTmp;
}

/**
 * 采集出错处理
 *
 * @param unknown_type $sGoUrl
 * @param unknown_type $sCfile
 * @param unknown_type $sCline
 * @param unknown_type $aP
 * $sLogTheaterName === string 出错的采集的影院名
 * $sGoUrl === string 出错的采集数据的url
 * $sCfile === string 出错的脚本名
 * $sCline === string 出错的脚本中大概第几行
 * $aP['error_template']['con'] === string 错误信息模板
 * $aP['error_template']['search_replace'] === string 错误信息模板要替换的参数
 * $aP['log_email'] === string 调用邮件和日志的对象
 * $aP['logs_path'] === string 日志记录路径
 * $aP['isdie'] === bool 错误处理完之后是否结束掉程序
 * $sFringeMsg === string 附加的错误信息
 */
function gather_handle_error($sLogTheaterName,$sGoUrl,$sCfile,$sCline,$aP,$sFringeMsg='')
{
    $sMsg = str_replace($aP['error_template']['search_replace'],array($sLogTheaterName,$sGoUrl,$sCfile,$sCline),$aP['error_template']['con']);
    if ($sFringeMsg != '') {
        $sMsg .= '++++'.$sFringeMsg;
    }
    $aP['log_email']->sent_email($sMsg);
    $aP['log_email']->write_log($sMsg,$aP['logs_path']);
    if (isset($aP['isdie']) && $aP['isdie'] == true) {
        exit;
    }
}

/**
 * 得到用户传的参数
 *
 * @param string $aP 0=>'get',1=>'post',2=>'argv'---取参数顺序get-post-argv
 * @return unknown
 */
function get_parameter($aP=array('get','post','argv'))
{
    foreach ($aP as $v) {
        $sType = strtoupper($v);
        if ($sType == 'ARGV') {
            global $argv;
            $aTmp = $argv;
        } elseif ($sType == 'GET') {
            $aTmp = $_GET;
        } elseif ($sType == 'POST') {
            $aTmp = $_POST;
        }
        if (!empty($aTmp)) {
            foreach ($aTmp as $k=>$v) {
                $aR[$k] = $v;
            }
        }
    }
    return $aR;
}

function final_parameter($aIndex,$aParameter,$aCon)
{
    $aTmpHave = array();
    if (!isset($aParameter[$aIndex[0]]) || $aParameter[$aIndex[0]] == '') {
        if (isset($aParameter[$aIndex[1]]) && $aParameter[$aIndex[1]] != '') {
            $aParameter[$aIndex[0]] = $aParameter[$aIndex[1]];			
        }
    }
    if (isset($aParameter[$aIndex[0]]) && $aParameter[$aIndex[0]] != '') {
        $aTmp = explode(',',$aParameter[$aIndex[0]]);
        if ($aTmp) {
            foreach ($aTmp as $v) {
                if (!isset($aCon[$v])) {
                    continue;
                }
                $aTmpHave[] = $aCon[$v];
            }
        }
    } else {
        $aTmpHave = $aCon;
    }
    if (empty($aTmpHave)) {
        show_error('参数有误,传which参数：“1,3,5”');
    }
    return $aTmpHave;
}

// 说明：PHP 中将全角字符转换成半角的方法
function sbc_dbc($str)
{
    $queue = Array(
    '０' => '0', '１' => '1', '２' => '2', '３' => '3', '４' => '4',
    '５' => '5', '６' => '6', '７' => '7', '８' => '8', '９' => '9',
    'Ａ' => 'A', 'Ｂ' => 'B', 'Ｃ' => 'C', 'Ｄ' => 'D', 'Ｅ' => 'E',
    'Ｆ' => 'F', 'Ｇ' => 'G', 'Ｈ' => 'H', 'Ｉ' => 'I', 'Ｊ' => 'J',
    'Ｋ' => 'K', 'Ｌ' => 'L', 'Ｍ' => 'M', 'Ｎ' => 'N', 'Ｏ' => 'O',
    'Ｐ' => 'P', 'Ｑ' => 'Q', 'Ｒ' => 'R', 'Ｓ' => 'S', 'Ｔ' => 'T',
    'Ｕ' => 'U', 'Ｖ' => 'V', 'Ｗ' => 'W', 'Ｘ' => 'X', 'Ｙ' => 'Y',
    'Ｚ' => 'Z', 'ａ' => 'a', 'ｂ' => 'b', 'ｃ' => 'c', 'ｄ' => 'd',
    'ｅ' => 'e', 'ｆ' => 'f', 'ｇ' => 'g', 'ｈ' => 'h', 'ｉ' => 'i',
    'ｊ' => 'j', 'ｋ' => 'k', 'ｌ' => 'l', 'ｍ' => 'm', 'ｎ' => 'n',
    'ｏ' => 'o', 'ｐ' => 'p', 'ｑ' => 'q', 'ｒ' => 'r', 'ｓ' => 's',
    'ｔ' => 't', 'ｕ' => 'u', 'ｖ' => 'v', 'ｗ' => 'w', 'ｘ' => 'x',
    'ｙ' => 'y', 'ｚ' => 'z', '－' => '-'
    );
    foreach ($queue as $k => $v) {
        $str = str_replace($k,$v,$str);
    }
    
    return $str;
} 

/**
 * 去掉（）【】《》()[]<>
 *
 * @param string $str
 * @return string
 */
function remove_noneed($str)
{
    if ($str == '') {
        return $str;
    }
    $str = trim($str);
    $aTmp = array("（","）","【","】","《","》","(",")","[","]","<",">");
    $aReplace = array("","","","","","","","","","","","");
    $str = str_replace($aTmp,$aReplace,$str);
    return $str;
}


/**
 * 计算中文字符串长度
 *
 * @param string $string
 * @return string
 */
function utf8_strlen($string = null)
{
    // 将字符串分解为单元
    preg_match_all("/./us", $string, $match);
    // 返回单元个数
    if (!isset($match[0])) {
        $sR = false;
    } else {
        $sR = count($match[0]);
    }
    return $sR;
}

function pictype ( $url )
{
    global $aConfig;
    $tmp = strrpos($url,'.')+1;
    $file_type = substr($url,$tmp);
    if (!in_array($file_type,array('jpg','gif','bmp','png'))) {
        $fp = fopen($url, "rb");
        $bin = fread($fp, 2); //只读2字节
        fclose($fp);
        $str_info  = @unpack("C2chars", $bin);
        $type_code = intval($str_info['chars1'].$str_info['chars2']);
        $file_type = '';
        switch ($type_code) {
            case 7790:
                $file_type = 'exe';
                break;
            case 7784:
                $file_type = 'midi';
                break;
            case 8075:
                $file_type = 'zip';
                break;
            case 8297:
                $file_type = 'rar';
                break;
            case 255216:
                $file_type = 'jpg';
                break;
            case 7173:
                $file_type = 'gif';
                break;
            case 6677:
                $file_type = 'bmp';
                break;
            case 13780:
                $file_type = 'png';
                break;
            default:
                $file_type = 'unknown';
                break;
        }
    }
    if (!in_array($file_type,array('jpg','gif','bmp','png'))) {
        return 1;
    } else {
        return $file_type;
    }
} 

// 说明：PHP中二维数组的排序方法
    // 整理：http://www.CodeBit.cn
    /**
    * @package BugFree
    * @version $Id: FunctionsMain.inc.php,v 1.32 2005/09/24 11:38:37 wwccss Exp $
    *
    *
    * Sort an two-dimension array by some level two items use array_multisort() function.
    *
    * sysSortArray($Array,"Key1","SORT_ASC","SORT_RETULAR","Key2"……)
    * @author Chunsheng Wang <wwccss@263.net>
    * @param array $ArrayData the array to sort.
    * @param string $KeyName1 the first item to sort by.
    * @param string $SortOrder1 the order to sort by("SORT_ASC"|"SORT_DESC")
    * @param string $SortType1 the sort type("SORT_REGULAR"|"SORT_NUMERIC"|"SORT_STRING")
    * @return array sorted array.
    */
    function sysSortArray($ArrayData,$KeyName1,$SortOrder1 = "SORT_ASC",$SortType1 = "SORT_REGULAR")
    {
        if(empty($ArrayData) || !is_array($ArrayData))
        {
            return $ArrayData;
        }
        // Get args number.
        $ArgCount = func_num_args();
        // Get keys to sort by and put them to SortRule array.
        for($I = 1;$I < $ArgCount;$I ++)
        {
            $Arg = func_get_arg($I);
            if(!mb_eregi("SORT",$Arg))
            {
                $KeyNameList[] = $Arg;
                $SortRule[] = '$'.$Arg;
            }
            else
            {
                $SortRule[] = $Arg;
            }
        }
            // Get the values according to the keys and put them to array.
        foreach($ArrayData AS $Key => $Info)
        {
            foreach($KeyNameList AS $KeyName)
            {
                ${$KeyName}[$Key] = $Info[$KeyName];
            }
        }
        // Create the eval string and eval it.
        $EvalString = 'array_multisort('.join(",",$SortRule).',$ArrayData);';
        eval ($EvalString);
        return $ArrayData;
    } 

/**
 * Returns the url query as associative array
 *
 * @param    string    query
 * @return    array    params
 */
function convertUrlQuery($query)
{
    if (empty($query)) {
        return null;
    }
    $queryParts = explode('&', $query);
     
    $params = array();
    if (!empty($queryParts)) {
        foreach ($queryParts as $param)
        {
            $item = explode('=', $param);
            if (count($item) == 2) {
                $params[$item[0]] = $item[1];
            }
        }
    }
    return $params;
}

function getUrlQuery($array_query)
{
    $tmp = array();
    foreach($array_query as $k=>$param)
    {
        $tmp[] = $k.'='.$param;
    }
    $params = implode('&',$tmp);
    return $params;
}

/**
* 输出变量的内容，通常用于调试
*
* @package Core
*
* @param mixed $vars 要输出的变量
* @param string $label
* @param boolean $return
*/
function dump($vars, $label = '', $return = false)
{
    if (ini_get('html_errors')) {
        $content = "<pre>\n";
        if ($label != '') {
            $content .= "<strong>{$label} :</strong>\n";
        }
        $content .= htmlspecialchars(print_r($vars, true));
        $content .= "\n</pre>\n";
    } else {
        $content = $label . " :\n" . print_r($vars, true);
    }
    if ($return) { return $content; }
    echo show_msg($content);
    return null;
}


function make_internal_ip()
{
    /*使用了2个php函数
        ip2long($ip)把ip转为int
        long2ip($int_ip)把int转回ip
     */
    $ip_long = array(
        array('607649792', '608174079'), //36.56.0.0-36.63.255.255
        array('1038614528', '1039007743'), //61.232.0.0-61.237.255.255
        array('1783627776', '1784676351'), //106.80.0.0-106.95.255.255
        array('2035023872', '2035154943'), //121.76.0.0-121.77.255.255
        array('2078801920', '2079064063'), //123.232.0.0-123.235.255.255
        array('-1950089216', '-1948778497'), //139.196.0.0-139.215.255.255
        array('-1425539072', '-1425014785'), //171.8.0.0-171.15.255.255
        array('-1236271104', '-1235419137'), //182.80.0.0-182.92.255.255
        array('-770113536', '-768606209'), //210.25.0.0-210.47.255.255
        array('-569376768', '-564133889'), //222.16.0.0-222.95.255.255
    );
    $rand_key = mt_rand(0, 9);
    $ip = long2ip(mt_rand($ip_long[$rand_key][0], $ip_long[$rand_key][1]));
    return $ip;
}

function curl_string ($url,$user_agent,$proxy,$CURLOPT_HTTPHEADER,$CURLOPT_TIMEOUT){
    $curl = curl_init();
    $cksfile = "d:\cookies.txt";
    //curl_setopt ($ch, CURLOPT_PROXY, $proxy);
    curl_setopt ($curl, CURLOPT_URL, $url);
    curl_setopt ($curl, CURLOPT_USERAGENT, $user_agent);
    curl_setopt ($curl, CURLOPT_HTTPHEADER, $CURLOPT_HTTPHEADER);  //此处可以改为任意假IP

    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($curl, CURLOPT_COOKIESESSION, 1);
    curl_setopt($curl, CURLOPT_COOKIEJAR, $cksfile); //读上次cookie
    curl_setopt($curl, CURLOPT_COOKIEFILE, $cksfile); //写本次cookie
    curl_setopt($curl, CURLOPT_TIMEOUT, $CURLOPT_TIMEOUT); // 设置超时限制防止死循环
    curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
    $result = curl_exec($curl); // 执行操作
    if (curl_errno($curl)) {
        echo 'Errno'.curl_error($curl);//捕抓异常
    }

//     $result = curl_exec ($ch);
    curl_close($curl);
    return $result;
}