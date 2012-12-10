<?php  if ( ! defined('ROOT_PATH')) exit('非法访问！！！');
/********************************************************
 Author: BurtonQ
Version: 1
 Effect: 
   Date: 
  Notes: shell:php index.php test test 1
********************************************************/
class test_agent
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
		######################################
		
//		$oHttpClient = new Http_Client();
//		$oHttpClient->setHeader('Authorization: GoogleLogin auth', 'DQAAALAAAACSbO7F47Ke40E32r6nB6GGZnbMv-Y0PMdru8BBi1yMZhAeJcpBwsn3-_R8SZjAJYXIFUWV0dLfIr1ZTRuCpkdYUdghdSh0IX1YWvOwPkbezogsJpZ999v1AH8HljLulIu2cdnZPH9lXEIKDFRk88nziPb-E58HQRjJOqkpVj0MJKRyaOYqxLgxWREdJsK69exTLD_AVkx634OQRkv72vaNBa4zyOJb2FelTbyTIWHlPA');
//		$oHttpClient->addPostField('registration_id', 'APA91bEz3nr0iiAkCJhlN3aaJWwoqasMSqJrhLsoVUWf7yhsPkEyZ6kbsh8xFkgzMAMuKGotJ0rVn6OmJzv2rNvg7Z-_CpQOsEHRxobpnUEhPjNfZnRez2g9LNGB5wf5asS77ap6Mevdrp3R__DuonYQoVOhqMVQnQ');
//		$oHttpClient->addPostField('collapse_key', '1');
//		$oHttpClient->addPostField('data.msg', '哈喽');
//		$sUrl = 'https://android.apis.google.com/c2dm/send';
//		$r = $oHttpClient->Post($sUrl);
		//DQAAALAAAABAAT_FWjHSrRzWIRMolkGrH0Xr3TevIfTuAN0O8F5WLCDQ8wOAYSEEsyMcsMu9LBv3mkvQAxrR2E5W8TAHwnlXn9bF_YSb3IwzIEOGEZUWWJCn1Avy39RgEJSInr9o16gbEYZFzJh0iiWO01UDg7JMu4fiNViOe0RmjOt3FxTJWTlJysPz_msCKVaQ5rTz1L5e4Vp0CrRP5ktMFpFix3MsaQ4vttWgkS94zjpQhuVLtg
		$sUrl = 'https://android.apis.google.com/c2dm/send';
		$sData = 'registration_id=APA91bEz3nr0iiAkCJhlN3aaJWwoqasMSqJrhLsoVUWf7yhsPkEyZ6kbsh8xFkgzMAMuKGotJ0rVn6OmJzv2rNvg7Z-_CpQOsEHRxobpnUEhPjNfZnRez2g9LNGB5wf5asS77ap6Mevdrp3R__DuonYQoVOhqMVQnQ&collapse_key=1&data.msg=jhkjhkjhkjhkj';
		$sData = 'registration_id=APA91bG_oLJEAoXQQ7yOmfzf25doeLRJ_D15PrTgpg9HxqAOi_p7VMadqIkcJumG9WiEa4gxPkqU0cWzsAkuqTg1xLS5n-s_SYfnXegcs091eAqlwO50RE3ZhMp7tslLLSkr2OBbJ0Lj8RKObiit5LG-zL-1tBveaQ&collapse_key=1&data.msg=ljksjdl';
//		$sData = 'registration_id=APA91bEYScaoBqRj4lGZzBLhUuOxa6nolexHysyA9fXdCNOfn5nfPaeJ6mnkvJ0Q7EwVEWPD0pepOZ6Z0pWYz32by2c7paieq9clbtxQxQVYLOp6xIxvhp3WpGovwV8ZctfOCwDcwVfoG3RUPPlKfsqq5EjHenDhbw&collapse_key=1&data.msg=22222222222';
		$aHeader = array(
            'Authorization:GoogleLogin auth=' . 'DQAAALEAAADfdSfdRBtUMWeUns3tDvvHbe58-QtiR8gT63mYPt0vESpUkfkDl_7hp7gZeUFI3ppbIF0Ms5MNgWM9l2fTuXlWNBwfNL0vr_AR-NIpk182BIXlkXPFwRfJXLIkeMI5DP8z7YQjHrHZaIEjDMejbxYibqckVwXf7z9fXSUMtjyNQhJ1SSIiEC6HU1nPmKTvbTZIjaZhXKCpIHn74LUpJhxJRJASnF6JTVOgB41TPqZTsZkPZZBPbo0saleNRBt4n4I',
        );
		$aHeader = array(
            'Authorization:GoogleLogin auth=' . 'DQAAALAAAADPL7xzOc-FiStUOnJEyhrRXRTKwvlHqpXlYyiVu0_CArTuosOFRN27-mI06LXGvBJoCRO5SzjjXLG90t0Y-j_KwGdmLmgeekAFDEAxV2ihAfOn8Cgt-NDoGvH_TgBY-aEn4BT9zv5LwKqv1toKiv-jgSHTNyfEXQhyEVdyrLEjKjXRyyyUg6BuIuvbnyQ695XKyyOI50zBDPLz42I5hsPrT5SLapGRRhaA1I65FfcNdA',
        );
		$r = meclient($sUrl, $sData, $aHeader);
		echo $r;
		die;
		######################################
		
		//qzone头像地址2558604288
		$this->aConfig['baseQzoneAvatarUrl_template']['con'] = 'http://qlogo1.store.qq.com/qzone/{@QQ}/{@QQ}/100';
        $this->aConfig['baseQzoneAvatarUrl_template']['search_replace'] = array('{@QQ}','{@QQ}');
		$csvFileName = DATAS_PATH.'qqContacts/2012-06-15-16-29-10-contact-63.csv';
		$aData = $this->getCSVdata($csvFileName);
		
		$aResult = array();
		if (!empty($aData)) {
			foreach ($aData as $aData_v) {
				$aTmp = array();
				$aTmp['name'] = $aData_v['姓'] . $aData_v['名'];
				if (isset($aData_v['QQ']) && !empty($aData_v['QQ'])) {
					$mime = '';
					$file_type = 'jpg';
					$sUrl = str_replace($this->aConfig['baseQzoneAvatarUrl_template']['search_replace'],array($aData_v['QQ'],$aData_v['QQ']),$this->aConfig['baseQzoneAvatarUrl_template']['con']);
//					$r = meclient("http://qlogo1.store.qq.com/qzone/1410920557/1410920557/100");
//					$base64 = base64_encode($r);
//					if ($base64) {
//						$sCover = 'data:' . $mime . ';base64,' . $base64;
//						echo '<img src="'.$sCover.'" style="" title="'.$aTmp['name'].'" />';
//					}
//					die("http://qlogo1.store.qq.com/qzone/1410920557/1410920557/100");
					$img_data = meclient($sUrl);
					if ($img_data) {
						$p = DATAS_PATH.'qqContacts/';
						if (!is_readable($p)) {
							creat_dir($p);
						}
						$sImgName = $p.$aData_v['QQ'].'.'.$file_type;
						file_put_contents($sImgName,$img_data);
						$aTmp['avatar'] = $sImgName;
					} else {
						continue;
					}
				} else {
					continue;
				}
				$aResult[] = $aTmp;
			}
		}
		
		foreach ($aResult as $aResult_k=>$aResult_v) {
			echo ($aResult_k+1).'、'.$aResult_v['name']."".' - <img src="'.$aResult_v['avatar'].'" style="" title="'.$aResult_v['name'].'" />'.'<br />';
		}
		
		die;
		
//		$sql = "SELECT * FROM `user` LIMIT 10";
//		$data = $this->gaTools['mysqldb']->find( $sql );
//		dump($data);
//		die;
		
		$this->aScriptNeed['charset_of_getcon'] = 'UTF-8';
		if (!isset($this->aParameter['p'])) {
			echo "<a href='index.php?space=test&action=test&p=h'>back</a><br /><br />";
			echo "<a href='index.php?space=test&action=test&p=l'>gowork</a><br /><br />";
			die;
		}
		if ($this->aParameter['p'] == 'h') {
			$this->aScriptNeed['url_1'] = 'http://m.5320000.com/index.php/Home/Line/nextbus/lid/143/r/0/sid/2193/i/25/rand/ca77';
		} else if ($this->aParameter['p'] == 'l') {
			$this->aScriptNeed['url_1'] = 'http://m.5320000.com/index.php/Home/Line/nextbus/lid/143/r/0/sid/1424/i/7';
		}
		$aCollectionResult = $this->filter();
		//dump($aCollectionResult);
		//die;
		#响应
		$responser = &load_class('responser');
		$responser->execute($aCollectionResult);
	}
	
	private function filter()
	{
		$aCollectionResult = null;
		$sUrl = $this->aScriptNeed['url_1'];
		$sCon = file_get_contents($sUrl);
		$domCon = str_get_html($sCon);
		$domTmp = $domCon->find(".cmode font",0);
		if (empty($domTmp)) {
			return false;
		}
		$sBusLine = remove_invalid(iconv($this->aScriptNeed['charset_of_getcon'],DEFAULT_CHARSET_ICONV,$domTmp->plaintext));
		$domTmp = $domCon->find(".cmode .tl font",0);
		if (empty($domTmp)) {
			return false;
		}
		$sStation = remove_invalid(iconv($this->aScriptNeed['charset_of_getcon'],DEFAULT_CHARSET_ICONV,$domTmp->plaintext));
		$domTmp = $domCon->find(".cmode",0);
		if (empty($domTmp)) {
			return false;
		}
		$sBusInTime = '资讯又未知了-.-';
		$sTmp = remove_invalid(iconv($this->aScriptNeed['charset_of_getcon'],DEFAULT_CHARSET_ICONV,$domTmp->plaintext));
		if (preg_match('/路还差(.*?)公里到站/',$sTmp,$aTmp)) {
			$sBusInTime = '还差' . $aTmp[1] . '公里到站';
		}
		$domTmp = $domCon->find(".cmode img[alt=请稍候...]",0);
		if (empty($domTmp)) {
			return false;
		}
		$sBusInTimeImg = 'http://m.5320000.com'.$domTmp->src;
		#判断图片类型
		$file_type = pictype($sBusInTimeImg);
		if ($file_type === 1) {
			#不知道图片类型
			null;
		} else {
			$mime = 'image/'.$file_type;
			$base64 = base64_encode(file_get_contents($sBusInTimeImg));
			$sBusInTimeImg = 'data:' . $mime . ';base64,' . $base64;
		}
		$domCon->__destruct();
		$aCollectionResult['BusLine'] = $sBusLine;
		$aCollectionResult['Station'] = $sStation;
		$aCollectionResult['BusInTime'] = $sBusInTime;
		$aCollectionResult['BusInTimeImg'] = $sBusInTimeImg;
		return $aCollectionResult;
	}
	
	function getCSVdata($filename)
	{
		$row = 1;//第一行开始
		if(($handle = fopen($filename, "r")) !== false) 
		{
		    while(($dataSrc = fgetcsv($handle)) !== false) 
		    {
		        $num = count($dataSrc);
		        for ($c=0; $c < $num; $c++)//列 column 
		        {
		        	if($row === 1)//第一行作为字段 
		        	{
		        		$dataName[] = $dataSrc[$c];//字段名称
		        	}
		            else
		            {
		        		foreach ($dataName as $k=>$v)
		        		{
		        			if($k == $c)//对应的字段
		        			{
		            			$data[iconv('GBK',DEFAULT_CHARSET_ICONV,$v)] = iconv('GBK',DEFAULT_CHARSET_ICONV,$dataSrc[$c]);
		        			}
		        		}
		            }
		        }
		        if(!empty($data))
		        {
			         $dataRtn[] = $data;
			         unset($data);
		        }
		        $row++;
		    }
		    fclose($handle);
		    return $dataRtn;
		}
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
		
		$this->aCommConfiger['url_url'] = 'url';
	}
	
	function __destruct()
	{
		
	}
}
