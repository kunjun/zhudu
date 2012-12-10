<?php  if ( ! defined('ROOT_PATH')) exit('非法访问！！！');
/********************************************************
 Author: BurtonQ
Version: 1
 Effect: 
   Date: 
  Notes: ?space=u17&action=u17_list&do=always&end=1
********************************************************/
class u17_list_agent
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
//		if ($this->aParameter['do'] != 'always') {
//			$sql = "SELECT * FROM `album_listened`";
//			$data = $this->gaTools['mysqldb']->find( $sql );
//			if (!empty($data)) {
//				foreach ($data as $v) {
//					echo '<img src="'.$v['cover'].'" style="width:90px;height:90px;" title="'.$v['title'] . ' -- ' . $v['author'] . "\n" . $v['description'].'" />';
//				}
//				return;
//			}
//		}

		
		$this->aScriptNeed['charset_of_getcon'] = 'UTF-8';
		//连载中
		for ($i = $this->aParameter['start0']; $i <= $this->aConfig['url_0']['p_total']; $i++) {
			$this->aScriptNeed['url_url_0'] = str_replace($this->aConfig['url_0_template']['search_replace'], $i, $this->aConfig['url_0_template']['con']);
			$aCollectionResult = $this->filter();
			$u17_db = new u17_db();
			if ($u17_db->save_data($aCollectionResult)) {
	            echo '“'.$this->aScriptNeed['url_url_0'].'”保存成功！<br />'."\n";
	        }
//	        dump($aCollectionResult);
			if ($this->aParameter['end'] != '' && $i == $this->aParameter['end']) {
	        	break;
	        }
		}
		
		//已完结
		for ($i = $this->aParameter['start1']; $i <= $this->aConfig['url_1']['p_total']; $i++) {
			$this->aScriptNeed['url_url_0'] = str_replace($this->aConfig['url_1_template']['search_replace'], $i, $this->aConfig['url_1_template']['con']);
			$aCollectionResult = $this->filter();
			$u17_db = new u17_db();
			if ($u17_db->save_data($aCollectionResult)) {
	            echo '“'.$this->aScriptNeed['url_url_0'].'”保存成功！<br />'."\n";
	        }
//	        dump($aCollectionResult);
			if ($this->aParameter['end'] != '' && $i == $this->aParameter['end']) {
	        	break;
	        }
		}
		
		die;
		#响应
//		$responser = &load_class('responser');
//		$responser->execute($aCollectionResult);
	}
	
	private function filter()
	{
		$aCollectionResult = null;
		$sUrl = $this->aScriptNeed['url_url_0'];
		$sCon = meclient($sUrl);
		$domCon = str_get_html($sCon);
		$domTmp0 = $domCon->find(".comiclist", 0);
		if (empty($domTmp0)) {
			return false;
		}
		$domTmp1 = $domTmp0->find("li");
		if (empty($domTmp1)) {
			return false;
		}
		foreach ($domTmp1 as $domTmp1_v) {
			//封面
			$cover = '';
			$domTmp1_v_0 = $domTmp1_v->find("div",0);
			if (!empty($domTmp1_v_0)) {
				$domTmp1_v_0_img = $domTmp1_v->find("img",0);
				if (!empty($domTmp1_v_0_img)) {
					$cover = $domTmp1_v_0_img->src;
					if ($cover == '' || $cover == false) {
						$cover = $domTmp1_v_0_img->xsrc;
					}
//					echo '<img class="cover_img" src="'.$cover.'" />';
				}
			}
			
			//标题 地址 id
			$title = $url = $u17_id = '';
			$domTmp1_v_1_a = $domTmp1_v->find(".info .u", 0);
			if (empty($domTmp1_v_1_a)) {
				continue;
			}
			$title = $domTmp1_v_1_a->title;
			$url = $domTmp1_v_1_a->href;
			preg_match('/\/(\d*)\.htm/', $url, $aTmp);
			$u17_id = isset($aTmp[1]) ? $aTmp[1] : '';
			//作者及其地址
			$u17_author_id = $author = $author_url = '';
			$domTmp1_v_1_em_a_2 = $domTmp1_v->find("h3 em a");
			$c = count($domTmp1_v_1_em_a_2);
			$domTmp1_v_1_a_2 = null;
			if ($c == 0) {
				$domTmp1_v_1_a_2 = $domTmp1_v->find("h3 a", 1);
			} else if ($c == 1) {
				$domTmp1_v_1_a_2 = $domTmp1_v->find("h3 a", 2);
			} else if ($c == 2) {
				$domTmp1_v_1_a_2 = $domTmp1_v->find("h3 a", 3);
			}
			if (!empty($domTmp1_v_1_a_2)) {
				$author = $domTmp1_v_1_a_2->title;
				$author_url = $domTmp1_v_1_a_2->href;
				preg_match('/\/(\d*)\/$/', $author_url, $aTmp);
				$u17_author_id = isset($aTmp[1]) ? $aTmp[1] : '';
			}
			//tag 人气 月票 收藏
			$tag = $renqi = $yuepiao = $shoucang =  '';
			$domTmp1_v_1_p = $domTmp1_v->find(".info p", 0);
			if (!empty($domTmp1_v_1_p)) {
				$tmp = $domTmp1_v_1_p->plaintext;
				if ($tmp) {
					$aTmp = split('人气', $tmp);
					$tag = isset($aTmp[0]) ? $aTmp[0] : '';
					preg_match_all('/：(\d*)/', $tmp, $aTmp);
					$renqi = isset($aTmp[1][0]) ? $aTmp[1][0] : '';
					$yuepiao = isset($aTmp[1][1]) ? $aTmp[1][1] : '';
					$shoucang = isset($aTmp[1][2]) ? $aTmp[1][2] : '';
				}
			}
			//简介
			$summary = '';
			$domTmp1_v_1_p_textmore = $domTmp1_v->find(".info p.textmore", 0);
			if (!empty($domTmp1_v_1_p_textmore)) {
				$tmp = $domTmp1_v_1_p_textmore->innertext;
				if ($tmp) {
					$aTmp = split('<a', $tmp);
					$summary = isset($aTmp[0]) ? $aTmp[0] : '';
				}
			} else {
				$domTmp1_v_1_p_text = $domTmp1_v->find(".info p.text", 0);
				if (!empty($domTmp1_v_1_p_text)) {
					$tmp = $domTmp1_v_1_p_text->innertext;
					if ($tmp) {
						$aTmp = split('<a', $tmp);
						$summary = isset($aTmp[0]) ? $aTmp[0] : '';
					}
				}
			}
			//更新时间
			$update_time = '';
			$domTmp1_v_1_span_fr = $domTmp1_v->find(".info span.fr", 0);
			if (!empty($domTmp1_v_1_span_fr)) {
				$update_time = str_replace('最后更新时间：', '', $domTmp1_v_1_span_fr->plaintext);
			}
			
			$aTmpCollectionResult['u17_id'] = $u17_id;
			$aTmpCollectionResult['u17_author_id'] = $u17_author_id;
			$aTmpCollectionResult['cover'] = $cover;
			$aTmpCollectionResult['title'] = $title;
			$aTmpCollectionResult['url'] = $url;
			$aTmpCollectionResult['author'] = $author;
			$aTmpCollectionResult['author_url'] = $author_url;
			$aTmpCollectionResult['tag'] = $tag;
			$aTmpCollectionResult['renqi'] = $renqi;
			$aTmpCollectionResult['yuepiao'] = $yuepiao;
			$aTmpCollectionResult['shoucang'] = $shoucang;
			$aTmpCollectionResult['summary'] = $summary;
			$aTmpCollectionResult['update_time'] = $update_time;
			$aTmpCollectionResult['updated_at'] = $aTmpCollectionResult['created_at'] = time();
			$aCollectionResult[] = $aTmpCollectionResult;
			
		}
		
		$domCon->__destruct();
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
		
		//连载中
		$this->aConfig['url_0_template']['con'] = 'http://www.u17.com/comic_list/th99_gr99_ca99_ss0_ob0_ac0_as0_wm0_p{@p}.html';
		$this->aConfig['url_0_template']['search_replace'] = array('{@p}');
		$this->aConfig['url_0']['p_total'] = 363;
		
		//已完结
		$this->aConfig['url_1_template']['con'] = 'http://www.u17.com/comic_list/th99_gr99_ca99_ss1_ob0_ac0_as0_wm0_p{@p}.html';
		$this->aConfig['url_1_template']['search_replace'] = array('{@p}');
		$this->aConfig['url_1']['p_total'] = 194;
		
		$this->aCommConfiger['url_url'] = 'url';
	}
	
	function __destruct()
	{
		
	}
}
