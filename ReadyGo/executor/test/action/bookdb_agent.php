<?php  if ( ! defined('ROOT_PATH')) exit('非法访问！！！');
/********************************************************
 Author: BurtonQ
Version: 1
 Effect: 
   Date: 
  Notes: 
********************************************************/
class bookdb_agent
{
    private $aConfig = array();
    private $aCommConfiger = array();
    private $gaTools = array();
    private $aaGatherHandleError = array();
    private $aParameter = array();
    private $aScriptNeed = array();
    function __construct()
    {
        $this->aCommConfiger = &get_config('configer');
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
//		$sql = "SELECT * FROM `user` LIMIT 10";
//		$data = $this->gaTools['mysqldb']->getData( $sql );
//		dump($data);
        
        $this->aScriptNeed['charset_of_getcon'] = $this->aConfig['html_charset'];
        
        //每20次插入数据库
        $page_index = 1;
        $js = 1;
        $js2 = 1;
        $tmp_flag = false;
        $aCollectionResult = array();
        $bookdb_db = new bookdb_db();
        do {
            $this->aScriptNeed['page_index'] = $page_index;
            $bereplace = $this->aConfig['baseUrl_template']['search_replace'];
            $replace = array($page_index);
            $base_con = $this->aConfig['baseUrl_template']['con'];
            $this->aScriptNeed['url_1'] = str_replace($bereplace,$replace,$base_con);
            $waTmp = $this->filter();
            if (empty($waTmp)) {
                if ($js2 < 4) {
                    $js2++;
                    continue;
                } else {
                    break;
                }
            }
            $js2 = 1;
            $aCollectionResult[] = $waTmp;
            $page_index++;
            if ($page_index > $js*2) {
                $js++;
                if ($bookdb_db->save_data($aCollectionResult)) {
                    echo ($page_index-1).'保存成功！<br />';
                }
                $aCollectionResult = array();
                if ($page_index > 4) {
                    die;
                }
            }
            
        } while (true);
        
        
        #响应
//         $responser = &load_class('responser');
//         $responser->execute($aCollectionResult);
    }
    
    private function filter()
    {
        $sUrl = $this->aScriptNeed['url_1'];
        $sCon = meclient($sUrl);
        $domCon = str_get_html($sCon);
        $aCollectionResult = array();
        $domTmp = $domCon->find(".sw1");
        $this->help_2($domTmp,$aCollectionResult);
        $domTmp = $domCon->find(".sw2");
        $this->help_2($domTmp,$aCollectionResult);
        $domCon->__destruct();
        if($aCollectionResult)
        {
            $aCollectionResult = sysSortArray($aCollectionResult,'tmp_no');
        }
//         $bookdb_db = new bookdb_db();
//         if ($bookdb_db->save_data($aCollectionResult)) {
//             echo '保存成功！<br />';
//         }
//         dump($aCollectionResult);
//         die;
        return $aCollectionResult;
    }
    
    function help_1($con='')
    {
        $r = '';
        if ($con) {
            $r = remove_invalid(iconv($this->aScriptNeed['charset_of_getcon'],DEFAULT_CHARSET_ICONV,$con));
        }
        return $r;
    }
    
    function help_2($domTmp=null,&$aCollectionResult)
    {
        if (empty($domTmp)) {
            return false;
        }
        $c = count($aCollectionResult);
        foreach ($domTmp as $v) {
            $domName = $v->find(".swb .swbt",0);
            //如果书名没获取到。就跳过
            if (empty($domName)) {
                continue;
            }
            $aTmp = array();
            $aTmp['name'] = $this->help_1($domName->plaintext);
            $domName_a = $domName->find('a',0);
            $aTmp['book_home_link'] = '';
            if ($domName_a) {
                $aTmp['book_home_link'] = $this->help_1($domName_a->href);
            }
            $domTmpNo = $v->find(".swz",0);
            $aTmp['tmp_no'] = !empty($domTmpNo) ? $this->help_1($domTmpNo->plaintext) : 999999;
            $domCategory = $v->find(".swa",0);
            $aTmp['category'] = !empty($domCategory) ? $this->help_1($domCategory->plaintext) : '';
            $domNameSub = $v->find(".swb .hui2",0);
            $aTmp['name_sub'] = !empty($domNameSub) ? $this->help_1($domNameSub->plaintext) : '';
            $aTmp['book_new_link'] = isset($domNameSub->href) && !empty($domNameSub->href) ? $this->help_1($domNameSub->href) : '';
            $domTmpTmp = $v->find(".column4 li");
            $aTmp['words'] = isset($domTmpTmp[0]) && !empty($domTmpTmp[0]) ? $this->help_1($domTmpTmp[0]->plaintext) : 0;
            $aTmp['clicks'] = isset($domTmpTmp[1]) && !empty($domTmpTmp[1]) ? $this->help_1($domTmpTmp[1]->plaintext) : 0;
            $domAuthor = $v->find(".swd",0);
            $aTmp['author'] = !empty($domAuthor) ? $this->help_1($domAuthor->plaintext) : '';
            $domUptime = $v->find(".swe",0);
            $aTmp['uptime'] = !empty($domUptime) ? $this->help_1($domUptime->plaintext) : '';
            $aCollectionResult[$c] = $aTmp;
            $c++;
        }
        unset($domTmp);
        return true;
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
        
        $this->aConfig['baseUrl_template']['con'] = 'http://all.qidian.com/book/bookstore.aspx?ChannelId=-1&SubCategoryId=-1&Tag=all&Size=-1&Action=-1&OrderId=9&P=all&PageIndex={@PageIndex}&update=4&Vip=-1&Boutique=-1&SignStatus=-1';
        $this->aConfig['baseUrl_template']['search_replace'] = array('{@PageIndex}');
    
        $this->aCommConfiger['url_url'] = 'url';
    }
    
    function __destruct()
    {
        
    }
}
