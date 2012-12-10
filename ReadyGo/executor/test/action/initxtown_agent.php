<?php  if ( ! defined('ROOT_PATH')) exit('非法访问！！！');
/********************************************************
 Author: BurtonQ
Version: 1
 Effect: 
   Date: 
  Notes: ?space=test&action=initxtown&do=always
********************************************************/
class initxtown_agent
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
		####################################################
		$this->aParameter['do'] = isset($this->aParameter['do']) ? $this->aParameter['do'] : '';
		####################################################
		$this->aScriptNeed['charset_of_getcon'] = 'UTF-8';
		$this->aScriptNeed['url_1'] = 'http://idsoo.com/index/ajax';
		$i = 0;
		if ($this->aParameter['do']) {
//			$i = $this->aParameter['do'];
		}
		$i = 0;
		do {
			$this->aScriptNeed['data_1'] = 'start='.($i*100).'&end=100';
			$aCollectionResult = $this->filter();
			$initxtown_db = new initxtown_db();
			if ($initxtown_db->save_data($aCollectionResult)) {
	            echo '“'.$this->aScriptNeed['url_1'].'”保存成功！<br />';
	        }
//	        var_dump($aCollectionResult);
	        $i++;
//	        if ($i == 2) {
//	        	break;
//	        }
		} while ($aCollectionResult);
		#响应
//		$responser = &load_class('responser');
//		$responser->execute($aCollectionResult);
	}
	
	private function filter()
	{
		$aCollectionResult = null;
		$sUrl = $this->aScriptNeed['url_1'];

		$sCon = '';
		$index = md5($this->aScriptNeed['data_1']);
		if (file_exists(DATAS_PATH . $index . 'json')) {
			$sCon = file_get_contents(DATAS_PATH . $index . 'json');
			if ($sCon == "") {
				$sCon = meclient($sUrl,$this->aScriptNeed['data_1']);
				file_put_contents(DATAS_PATH . $index . 'json', $sCon);
			}
		} else {
			$sCon = meclient($sUrl,$this->aScriptNeed['data_1']);
			file_put_contents(DATAS_PATH . $index . 'json', $sCon);
		}
		

//		echo 'ok';
//		die;
//		echo $sCon;
		$aTmp = preg_split("/:'/", $sCon);
//		dump($aTmp);
//		die;
		$sCon = '';
		foreach ($aTmp as $k => $v) {
			if ($k > 0) {
				$aTmp[$k] = str_replace(':', '@@', $v);
			}
			$sCon .= $aTmp[$k] . ":'";
		}
		$sCon = substr($sCon, 0, -2);
		$sCon = str_replace(array(':\'','\',','\'}'), array(':"','",','"}'), $sCon);
//		echo $sCon;
//		die;
//		var_dump(json_decode('{"data":[{"id":"4173","listHeight":"120","Img":"http@@//pic.yupoo.com/26z2006_v/BUDr1w2A/metal/","favnum":"4","commentnum":"0","userid":"3914","useravatar":"http@@//pic.yupoo.com/26z2006_v/BzjvzrQc/water/","username":"ran2015","createtime":"2012-04-23 15@@40@@27","info":"\u65f6\u5149\u96c6\u5e02 \u65e5\u5355\u8d85\u7f8e\u6c11\u65cf\u98ce \u5c0f\u6e05\u65b0 \u7cd6\u679c\u8272\u68c9\u8d28\u5973\u889c","fave":"false","loginflag":"false","commentuserid":"","commentuseravatar":"","commentusername":"","comment":""}]}'));
		$data = ex_json_decode($sCon);
//		var_dump(json_decode('{"name":"JSON Editor","powered by":"jsLinb","version":"1.0","test data":{"int":23,"float":23.23,"string":"This\'s a string.","html":"<div>a<span>c</span>b</div>","hash":{"a":1,"b":"2"},"array":[1,"2",3],"regexp":"/^{[w]*}$/gim","NULL":null,"Date":"new Date(1998,10,20,4,23,14,120)","function":"function (a,b,c){return a+b+c;}"}}'));

		if (!empty($data)) {
			$aTmpCollectionResult = array();
			foreach ($data as $k => $v) {
				foreach ($v as $k_k => $v_v) {
				$aTmpCollectionResult['idsoo_id'] = $v_v->id;
				$aTmpCollectionResult['content'] = $v_v->info;
				$aTmpCollectionResult['photos'] = $v_v->Img;
				$aTmpCollectionResult['create_time'] = strtotime($v_v->createtime);
				$aTmpCollectionResult['list_height'] = $v_v->listHeight;
				$aCollectionResult[] = $aTmpCollectionResult;
				}
			}
		}
//		dump($aCollectionResult);
//		die;
		return $aCollectionResult;
		die;
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

function ex_json_decode($s, $mode=false) {
  if(preg_match('/\w:/', $s))
    $s = preg_replace('/(\w+):/is', '"$1":', $s);
   $s = str_replace('@@', ':', $s);
   $s = remove_invalid($s);
//   echo $s;
//   $s = '{"data":[{"id":"4008","listHeight":"200","Img":"http@@//pic.yupoo.com/26z2006_v/BU9nn2rH/metal/","favnum":"4","commentnum":"0","userid":"6018","useravatar":"http@@//pic.yupoo.com/26z2006_v/BU3yyX5G/water/","username":"梦幻瓷艺","createtime":"2012-04-20 11@@13@@47","info":"麦穗和情花都属于夏天，选择的话，选择的花。 摆上花瓶，插上花，缤纷的花朵绚烂这个夏天。 ","fave":"false","loginflag":"false","commentuserid":"","commentuseravatar":"","commentusername":"","comment":""}]}';
//   $s = "'".$s."'";
  return json_decode($s, $mode);
}
