<?php  if ( ! defined('ROOT_PATH')) exit('非法访问！！！');
/********************************************************
 Author: BurtonQ
Version: 1
 Effect: 
   Date: 
  Notes: ?space=tool&action=image&do=always&end=1
********************************************************/
class image_agent
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
        $this->aParameter['src'] = isset($this->aParameter['src']) ? $this->aParameter['src'] : '';
        $this->aParameter['to'] = isset($this->aParameter['to']) ? $this->aParameter['to'] : '';
        $this->aParameter['cc'] = isset($this->aParameter['cc']) ? $this->aParameter['cc'] : '';
        ####################################################
        
        $w = '640';
        $h = '960';
        
        if ($this->aParameter['cc'] != '') {
            $arr = split('x',$this->aParameter['cc']);
            if (count($arr) == 2) {
                $w = $arr[0];
                $h = $arr[1];
            }
        }
        
        echo "<html><head><meta charset='utf-8' /><title>图片</title></head><body bgcolor=000000><center><font size=2 color=red>";//输出html相关代码  
  
        $page = isset($_GET['page']) ? $_GET['page'] : '1';//获取当前页数  
          
        $max=3;//设置每页显示图片最大张数  
          
        $src_path = DATAS_PATH . $this->aParameter['src'];  
        $to_path = DATAS_PATH . $this->aParameter['to'];
        create_dir($to_path);
          
        $handle = opendir($src_path); //当前目录  
        
//         dump($path);
//         dump($handle);
//         die;
        $i = 0;
        while (false !== ($file = readdir($handle))) { //遍历该php文件所在目录  
      
          list($filesname,$kzm)=explode(".",$file);//获取扩展名  
      
            if($kzm=="gif" or $kzm=="png" or $kzm=="jpg" or $kzm=="JPG") { //文件过滤  
      
              if (!is_dir('./'.$file)) { //文件夹过滤  
      
                $array[]=$file;//把符合条件的文件名存入数组  
      
                $i++;//记录图片总张数  
      
               }  
      
              }  
      
        }
        $t = new ThumbHandler_tool();
        if (!empty($array)) foreach ($array as $k=>$v) {
            $t->setSrcImg($src_path.'/'.$v);
            $t->setCutType(1);//这一句就OK了
            $t->setDstImg($to_path.'/'.$v);
            $t->createImg($w,$h);
//             echo "<img width=".($w/5)." height=".($h/5)." src=".DATAS_FOLDER."/".$this->aParameter['src']."/".$v."></a>";  
            echo "<img width=".($w/5)." height=".($h/5)." src=".DATAS_FOLDER."/".$this->aParameter['to']."/".$v."></a>";  
        }
          
//         for ($j=$max*$page;$j<($max*$page+$max)&&$j<$i;++$j){//循环条件控制显示图片张数  
//           
//             //echo "<img widht=800 height=600 src=\".$path"\".$array[$j].">";//输出图片数组  
//           
//         echo "<a href=".$path."/".$array[$j]."><img width=150 height=110 src=".$path."/".$array[$j]."></a><br>";  
//           
//         }
        
        die;
        #响应
//		$responser = &load_class('responser');
//		$responser->execute($aCollectionResult);
    }
    
    private function filter()
    {
    }
    
    function get_config()
    {
        return $this->aConfig;
    }
    private function set_config()
    {
    }
    
    function __destruct()
    {
        
    }
}
