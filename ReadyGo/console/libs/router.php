<?php  if ( ! defined('ROOT_PATH')) exit('非法访问！！！');

class router
{
    public $aConfiger = '';
    public $aRouter = '';
    private $sSpace = '';
    private $sAction = '';
    private $sSuffix = '';
    private $sCharset = '';
    private $sScriptSuffix = '';
    function __construct()
    {
        $this->aConfiger = &get_config('configer');
        $this->aRouter = &get_config('router');
    }
    
    function execute()
    {
        $this->set_router();
        $this->select_action();
    }
    
    function set_router()
    {
        $this->sSpace = get_or_post($this->aConfiger['url_space']);
        $this->sAction = get_or_post($this->aConfiger['url_action']);
        $this->sSuffix = get_or_post($this->aConfiger['url_suffix']);
        
        $this->sSpace = $this->sSpace=='' ? $this->aRouter['default_space'] : $this->sSpace;
        $this->sAction = $this->sAction=='' ? $this->aRouter['default_action'] : $this->sAction;
        $this->sSuffix = $this->sSuffix=='' ? $this->aRouter['default_suffix'] : $this->sSuffix;

        if ($this->sSuffix) {
            $this->sScriptSuffix = '_'.$this->sSuffix;
        }
        if ($this->sAction) {
            $this->sAction = $this->sAction . $this->sScriptSuffix;
        }
        if ($this->sSpace == '') {
            show_error('要执行的脚本所在的空间必需有');
        } else {
            $include = array(APP_PATH.$this->sSpace.'/db', APP_PATH.$this->sSpace.'/show');
            set_include_path(get_include_path() . PATH_SEPARATOR .implode(PATH_SEPARATOR, $include));
        }
    }
    
    function select_action()
    {
        if ($this->sAction == '') { #没有action，则执行space/action/目录下的所有脚本
            if ($this->sSpace) {
                $this->sSpace = str_replace(PATH_CUT,'/',$this->sSpace).'/action/';
            }
            $sTmpDir = APP_PATH.$this->sSpace;
            //echo $sTmpDir;
            if (is_dir($sTmpDir)) {
                $aTmpFile = my_scandir($sTmpDir);
                if (!empty($aTmpFile)) {
                    foreach ($aTmpFile as $v) {
                        //得到文件名
                        $sFileName = substr(basename($v),0,(strlen(basename($v))-strlen(SCRIPT_TYPE)-1));
                        $this->call_execute_class($v,$sFileName);
                    }
                } else {
                    show_error('脚本空间没有可执行的脚本！');
                }
            } else {
                show_error('没您指定的脚本空间！');
            }
        } else if ($this->sAction != '') { #action有，则执行$this->sSpace/action/下的$this->sAction脚本
            if ($this->sSpace) {
                $this->sSpace = str_replace(PATH_CUT,'/',$this->sSpace).'/action/';
            }
            $sTmpDir = APP_PATH.$this->sSpace;
            $sTmpScript = $sTmpDir.$this->sAction.EXT;
//			print($sTmpScript);
//			echo '<br />---------------------------<br />';
            if (!file_exists($sTmpScript)) {
                show_error('没您指定的脚本！');
            }
            $this->call_execute_class($sTmpScript,$this->sAction);
        }
    }
    
    function call_execute_class($sPath,$sClassName)
    {
        require_once($sPath);
        $oTmp = new $sClassName();
        $oTmp->execute();
        $oTmp->__destruct();
    }
    
    function get_action()
    {
        return $this->sAction;
    }
    function get_space()
    {
        return $this->sSpace;
    }
}