<?php  if ( ! defined('ROOT_PATH')) exit('非法访问！！！');
function is_php($version = '5.0.0')
{
    static $_is_php;
    $version = (string)$version;
    
    if ( ! isset($_is_php[$version]))
    {
        $_is_php[$version] = (version_compare(PHP_VERSION, $version) < 0) ? FALSE : TRUE;
    }

    return $_is_php[$version];
}

//function __autoload($class)
//{
//	$classpath = APPPATH.CONTROLLER_NAME.'/'.str_replace('_','/',$class).EXT;
//	if (!file_exists($classpath)) {
//		$system_language = &load_language(SYSTEM_LANGEAGE_FILE);
//		show_error($system_language->language['autoload_calss_fail'].'  '.$classpath);
//	}
//	include_once($classpath); //加栽类
//}

function &get_config($name='configer')
{
    static $main_conf;

    if ( ! isset($main_conf[$name]))
    {
        if ( ! file_exists(CONFIG_PATH.$name.EXT))
        {
            $system_language = &load_language(SYSTEM_DEFAULT_LANGEAGE_FILENAME);
            show_error($system_language->language['config_format_false']);
        }

        require_once(CONFIG_PATH.$name.EXT);

        if ( ! isset($$name) OR ! is_array($$name))
        {
            $system_language = &load_language(SYSTEM_DEFAULT_LANGEAGE_FILENAME);
            show_error($system_language->language['config_format_false']);
        }

        $main_conf[$name] =& $$name;
    }
    return $main_conf[$name];
}

function Add_S(&$array){
    if (is_array($array)) {
        foreach ($array as $key => $value) {
            if (!is_array($value)) {
                $array[$key] = addslashes($value);
            } else {
                Add_S($array[$key]);
            }
        }
    }
}

function get_or_post($index, $def_value='', $mode='', $priority='GET')
{
    if (!$index) {
        return false;
    }
    $result = '';
    if ($mode) {
        $mode = strtoupper($mode);	
    }
    if ($mode == 'GET' || $mode == 'POST') {
        $mode = '_'.$mode;
        if (isset($$mode[$index])) {
            $result = $$mode[$index];
        }
    } else {
        $priority = strtoupper($priority);
        if ($priority == '' || $priority == 'GET') {
            if (isset($_GET[$index])) {
                $result = $_GET[$index];
            } elseif (isset($_POST[$index])) {
                $result = $_POST[$index];
            }
        } elseif ($priority == 'POST') {
            if (isset($_POST[$index])) {
                $result = $_POST[$index];
            } elseif (isset($_GET[$index])) {
                $result = $_GET[$index];
            }
        }
    }
    //用户妹用url形式传递参数给脚本
    if ($result == '') {
        $result = $def_value;
    }
    return $result;
}

function &load_class($class, $space='', $instantiate = TRUE)
{
    static $objects = array();

    if ($space) {
        $index_space = $space.PATH_CUT.$class;
        $space = str_replace(PATH_CUT,'/',$space).'/';
    } else {
        $index_space = $class;
    }
    if (isset($objects[$index_space]))
    {
        return $objects[$index_space];
    }
    
    $load_success = false;
    if (file_exists(LIBS_PATH.$space.$class.EXT))
    {
        require_once(LIBS_PATH.$space.$class.EXT);
        $load_success = TRUE;
    }

    if ($instantiate == FALSE)
    {
        $objects[$index_space] = TRUE;
        return $objects[$index_space];
    }

    if ($load_success == TRUE)
    {
        $name = $class;
        $objects[$index_space] =& instantiate_class(new $name());
        return $objects[$index_space];
    } else {
        $system_language = &load_language(SYSTEM_LANGEAGE_FOLDE);
        show_error($system_language->language['load_calss_fail'].'  '.$class.'  '.$space);
    }
}

function &load_language($space='',$language='')
{
    static $languages = array();

    if ($language == '') {
        $language = DEFAULT_LANGEAGE_FILENAME;
    }
    if ($space) {
        $index_space = $space.PATH_CUT.$language;
        $space = str_replace(PATH_CUT,'/',$space).'/';
    } else {
        $index_space = $language;
    }
    if (isset($languages[$index_space]))
    {
        return $languages[$index_space];
    }
    
    $load_success = false;
    if (file_exists(LANGUAGE_PATH.LANGUAGE.'/'.$space.$language.EXT))
    {
        require_once(LANGUAGE_PATH.LANGUAGE.'/'.$space.$language.EXT);
        $load_success = true;
    }
    if ($load_success == true)
    {
        $classname = $language.LANGUAGE_SUFFIX;
        $languages[$index_space] = new $classname();
        return $languages[$index_space];
    } else {
        #语言包加载都失败了，你说还要怎么办？
        show_error('语言包加载失败 -- Language Load Fail -- name:'.$language.' -- space:'.$space);
    }
}

/**
 * 加载模块
 *
 * @param unknown_type $module
 * @param unknown_type $space  'folder_folder' ==> 'folder/folder'
 * @return bool
 */
function &load_module($module, $space='')
{
    static $modules = array();

    if (isset($modules[$module]) && !empty($modules[$module]))
    {
        return $modules[$module];
    }

    if ($space) {
        $space = str_replace('::','/',$space).'/';
    }
    
    $load_success = false;
    if (file_exists(APPPATH.MODULE_NAME.'/'.$space.'/'.$module.EXT))
    {
        require_once(APPPATH.MODULE_NAME.'/'.$space.'/'.$module.EXT);
        $load_success = true;
    }
    else
    {
        if (file_exists(BASEPATH.MODULE_NAME.'/'.$space.'/'.$module.EXT))
        {
            require_once(BASEPATH.MODULE_NAME.'/'.$space.'/'.$module.EXT);
            $load_success = true;
        }
    }

    if ($load_success == true)
    {
        $classname = $module.MODULE_SUFFIX;
        $modules[$module] = new $classname();
        return $modules[$module];
    } else {
        $system_language = &load_language(SYSTEM_LANGEAGE_FILE);
        show_error($system_language->language['load_module_fail']);
    }
}

function &load_tool($tool, $space='', $isclass=false, $aP=array())
{
    static $tools = array();
    if ($space) {
        $index_space = $space.PATH_CUT.$tool;
        $space = str_replace(PATH_CUT,'/',$space).'/';
    } else {
        $index_space = $tool;
    }
    if (isset($tools[$index_space]) && !empty($tools[$index_space]))
    {
        return $tools[$index_space];
    }
    
    $load_success = false;
    if (file_exists(TOOLS_PATH.$space.$tool.EXT))
    {
        require_once(TOOLS_PATH.$space.$tool.EXT);
        $load_success = true;
    }
    if ($load_success == true)
    {
        if ($isclass) {
            $classname = $tool.TOOL_SUFFIX;
            if ($aP) {
                $tools[$index_space] = new $classname($aP);
            } else {
                $tools[$index_space] = new $classname();
            }
        } else {
            $tools[$index_space] = true;
        }
        return $tools[$index_space];
    } else {
        $system_language = &load_language(SYSTEM_LANGEAGE_FILE);
        show_error($system_language->language['load_tool_fail']);
    }
}

function &instantiate_class(&$class_object)
{
    return $class_object;
}

/**
 * 扫描得到路径下的所有文件和目录
 *
 * @param string $path   要扫描的目录
 * @param string $sSkipDir  1--跳过要扫描目录下的目录，只扫面文件
 * @return array
 */
function my_scandir($path,$sSkipDir=1)
{
    $filelist=array();
    $path = sdirname($path);
    if($handle=opendir($path)){
        while (($file=readdir($handle))!==false){
            if($file!="." && $file !=".."){
                if(is_dir($path."/".$file)){
                    if ($sSkipDir != 1) {						
                        $filelist=array_merge($filelist,my_scandir($path."/".$file,$sSkipDir));
                    }
                }else{
                    $filelist[]=$path."/".$file;
                }
            }
        }
    }
    closedir($handle);
    return $filelist;
}

/*
得到期望的正确的路径格式
sdirname('/one'); // '/one'
sdirname('/one/');    // '/one'

sdirname('/one//two'); // '/one/two'
//（假如直接去调用dirname，这项的输出将是： // '/one'，不是预期想得到的）

sdirname('/one////two////');   // '/one/two'
*/
function sdirname($path)
{
    return dirname($path . '/.');
}

function show_error($message, $status_code = 500, $charset='utf-8')
{
    if (!defined('OUT_CHARSET')) {
        $sOutCharset = 'utf-8';
    } else {
        $sOutCharset = OUT_CHARSET;
    }
    if (!defined('DEFAULT_CHARSET')) {
        $sDefaultCharset = 'utf-8';
    } else {
        $sDefaultCharset = DEFAULT_CHARSET;
    }
    if ($status_code == 500) {
        die(iconv($sDefaultCharset,$sOutCharset,$message));
    }
}

function show_msg($message, $charset='utf-8')
{
    if (!defined('OUT_CHARSET')) {
        $sOutCharset = 'utf-8//IGNORE';
    } else {
        $sOutCharset = OUT_CHARSET.'//IGNORE';
    }
    if (!defined('DEFAULT_CHARSET')) {
        $sDefaultCharset = 'utf-8';
    } else {
        $sDefaultCharset = DEFAULT_CHARSET;
    }
    echo iconv($sDefaultCharset,$sOutCharset,$message);
}