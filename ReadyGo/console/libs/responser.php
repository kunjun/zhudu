<?php  if ( ! defined('ROOT_PATH')) exit('非法访问！！！');

class responser
{
	public $aConfiger = array();
	public $aRouter = array();
	private $aParameter = array();
	private $aScriptNeed = array();
	function __construct()
	{
		$this->aConfiger = &get_config('configer');
		$this->aRouter = &get_config('router');
		$this->aParameter = get_parameter();
	}
	
	function execute($data,$type=1,$t_name='')
	{
		$oRouterTmp = new router();
		$oRouterTmp->set_router();
		switch ($type) {
			case 1:
				if (file_exists(APP_PATH.$oRouterTmp->get_space().'/show/'.$t_name.EXT)) {
					require_once(APP_PATH.$oRouterTmp->get_space().'/show/'.$t_name.EXT);
				} else {
					$system_language = &load_language(SYSTEM_LANGEAGE_FILE);
					show_error($system_language->language['moban_notexists']);
				}
				break;
			case 2:
				echo json_encode($data);
				break;
		
			default:
				break;
		}
		
	}
	
	function do_assignment($data)
	{
		if (empty($data)) {
			return false;
		}
	}
}