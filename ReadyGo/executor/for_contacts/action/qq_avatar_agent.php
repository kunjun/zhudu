<?php  if ( ! defined('ROOT_PATH')) exit('非法访问！！！');
/********************************************************
 Author: BurtonQ
Version: 1
 Effect: 
   Date: 
  Notes: 
********************************************************/
class qq_avatar_agent
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
//		$url = 'http://taotao.qq.com/vc1/qz_first_utf8/firstjson?uin=137799538';
//		$url = 'http://taotao.qq.com/vc1/qz_first_utf8/def?uin=408907507&num=2';
//                $str = file_get_contents($url);
////                preg_match('/"title" : "(.*)"/', $str, $arr);
//                print_r($str);
		$r = file_get_contents("http://id.qq.com/cgi-bin/face?u=568058181-568058181&t=1&ldw=0d0e664bd9ab8083ca318766b1a6a356b20069f74546ddc9");
		$r = '{"ec":0,"ls":[{"f":"http:\/\/q2.qlogo.cn\/pif_get_cusface?version=1&src_usr_type=1&src_uin=2558604288&dst_usr_type=1&dst_uin=568058181&img_type=1&time=1332085625&hash=d979a5c83f441e441e06fb5d80e21f4b&sign_key=ab3c8d0e18713826124935cb0417055a5b63182d940edec2","u":568058181},{"f":"http:\/\/q2.qlogo.cn\/pif_get_cusface?version=1&src_usr_type=1&src_uin=2558604288&dst_usr_type=1&dst_uin=568058181&img_type=1&time=1332085625&hash=d979a5c83f441e441e06fb5d80e21f4b&sign_key=3fe87b2c5bbe5987c882fd8a80d612dc4edbcb69cff34c72","u":568058181}]}';
		print_r(json_decode($r));
		die;
		####################################################
		$this->aParameter['do'] = isset($this->aParameter['do']) ? $this->aParameter['do'] : '';
		####################################################
		if ($this->aParameter['do'] != 'always') {
			$sql = "SELECT * FROM `album_listened`";
			$data = $this->gaTools['mysqldb']->find( $sql );
			if (!empty($data)) {
				foreach ($data as $v) {
					echo '<img src="'.$v['cover'].'" style="width:90px;height:90px;" title="'.$v['title'] . ' -- ' . $v['author'] . "\n" . $v['description'].'" />';
				}
				return;
			}
		}
		
		$this->aScriptNeed['charset_of_getcon'] = 'UTF-8';
		$this->aScriptNeed['url_1'] = 'http://ting.baidu.com/people/1210754?r='.time();
		$aCollectionResult = $this->filter();
		$album_listened_db = new album_listened_db();
		if ($album_listened_db->save_data($aCollectionResult)) {
            echo '“'.$this->aScriptNeed['url_1'].'”保存成功！';
        }
		#响应
//		$responser = &load_class('responser');
//		$responser->execute($aCollectionResult);
	}
	
	private function filter()
	{
		$aCollectionResult = null;
		$sUrl = $this->aScriptNeed['url_1'];
		$sCon = meclient($sUrl);
		$domCon = str_get_html($sCon);
		$domTmp = $domCon->find(".cover");
		if (empty($domTmp)) {
			return false;
		}
		$img = new SaeImage();
		foreach ($domTmp as $domTmp_v) {
			$sTo_internet = $sTmpUrl = 'http://ting.baidu.com'.$domTmp_v->find("a",0)->href;
			$sTmpCon = meclient($sTmpUrl);
			$domTmpCon = str_get_html($sTmpCon);
			$domTmpRoot = $domTmpCon->find(".album-info",0);
			if (empty($domTmpRoot)) {
				continue;
			}
			$domTmp = $domTmpRoot->find(".album-title",0);
			$sAlbumTitle = "";
			if ($domTmp) {
				$sAlbumTitle = remove_invalid(iconv($this->aScriptNeed['charset_of_getcon'],DEFAULT_CHARSET_ICONV,$domTmp->plaintext));
				$aTmp = $this->gaTools['mysqldb']->load('album_listened',$sAlbumTitle,'title');
	        	if ($aTmp && $aTmp['cover']) {
	        		continue;
	        	}
			}
			$domTmp = $domTmpRoot->find(".author_list",0);
			$sAuthorList = "";
			if ($domTmp) {
				$sAuthorList = remove_invalid(iconv($this->aScriptNeed['charset_of_getcon'],DEFAULT_CHARSET_ICONV,$domTmp->title));
			}
			$domTmp = $domTmpRoot->find("dd",2);
			$sYear = "";
			if ($domTmp) {
				$sTmp = remove_invalid(iconv($this->aScriptNeed['charset_of_getcon'],DEFAULT_CHARSET_ICONV,$domTmp->plaintext));
				if (preg_match('/\d{4}-\d{1,2}-\d{1,2}/',$sTmp,$aTmp)) {
					$sYear = $aTmp[0];
				}
			}
			$domTmp = $domTmpRoot->find("dd",3);
			$sStyle = "";
			if ($domTmp) {
				$sTmp = remove_invalid(iconv($this->aScriptNeed['charset_of_getcon'],DEFAULT_CHARSET_ICONV,$domTmp->plaintext));
				if (strpos($sTmp,'：') !== false) {
					$aTmp = preg_split('/：/', $sTmp);
				} else {
					$aTmp = preg_split('/:/', $sTmp);
				}
				if (isset($aTmp[1])) {
					$sStyle = $aTmp[1];
				}
			}
			$domTmp = $domTmpRoot->find("dd",4);
			$sCompany = "";
			if ($domTmp) {
				$sTmp = remove_invalid(iconv($this->aScriptNeed['charset_of_getcon'],DEFAULT_CHARSET_ICONV,$domTmp->plaintext));
				if (strpos($sTmp,'：') !== false) {
					$aTmp = preg_split('/：/', $sTmp);
				} else {
					$aTmp = preg_split('/:/', $sTmp);
				}
				if (isset($aTmp[1])) {
					$sCompany = $aTmp[1];
				}
			}
			$domTmp = $domTmpRoot->find(".description",0);
			$sDescription = "";
			if ($domTmp) {
				$sDescription = remove_invalid(iconv($this->aScriptNeed['charset_of_getcon'],DEFAULT_CHARSET_ICONV,$domTmp->plaintext));
			}
			$sTmpUrl = "http://203.208.46.178/search?tbm=isch&hl=zh-CN&&q=".urlencode($sAlbumTitle.' '.$sAuthorList);
			$sTmpCon = meclient($sTmpUrl);
			$domTmpCon = str_get_html($sTmpCon);
			$domTmp = $domTmpCon->find(".images_table td img",0);
			$sCover = "";
			if ($domTmp) {
				$sCoverUri = $domTmp->src;
				#判断图片类型
				if ($sCoverUri) {
					$file_type = pictype($sCoverUri);
					if ($file_type === 1) {
						#不知道图片类型
						null;
					} else {
						$mime = 'image/'.$file_type;
						$img_data = file_get_contents($sCoverUri);
						if ($img_data) {
							$img->setData( $img_data );
						    $img->resize(90); // 等比缩放到200宽
						    $new_data = $img->exec(); // 执行处理并返回处理后的二进制数据
							$base64 = base64_encode($new_data);
							if ($base64) {
								$sCover = 'data:' . $mime . ';base64,' . $base64;
							}
						}
					}
				}
			}
			//宽度: 90px	高: 90px
//			echo '<img src="'.$sCoverPic.'" style="width:90px;height:90px;" title="'.$sCoverTitle . ' -- ' . $sAuthorList . "\n" . $sDescription.'" />';
			
			$domTmpCon->__destruct();
			$aTmpCollectionResult['title'] = $sAlbumTitle;
			$aTmpCollectionResult['author'] = $sAuthorList;
			$aTmpCollectionResult['year'] = $sYear;
			$aTmpCollectionResult['style'] = $sStyle;
			$aTmpCollectionResult['company'] = $sCompany;
			$aTmpCollectionResult['description'] = $sDescription;
			$aTmpCollectionResult['cover'] = $sCover;
			$aTmpCollectionResult['cover_uri'] = $sCoverUri;
			$aTmpCollectionResult['to_internet'] = $sTo_internet;
			$aTmpCollectionResult['created_at'] = time();
			$aCollectionResult[] = $aTmpCollectionResult;
		}
		
		$domCon->__destruct();
		dump($aCollectionResult);
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
		
		$this->aCommConfiger['url_url'] = 'url';
	}
	
	function __destruct()
	{
		
	}
}
