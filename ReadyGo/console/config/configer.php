<?php  if ( ! defined('ROOT_PATH')) exit('非法访问！！！');

/*
应用配置信息
*/
################### 不嫩在executor下被重写 S
$configer['base_url']	 = "http://.../";

#名字空间
$configer['url_space'] = "space"; # 文件1::文件2... => 文件1/文件2...
$configer['argv_space'] = 1; # 文件1::文件2... => 文件1/文件2...
#要调用的空间下的动作
$configer['url_action'] = "action";
$configer['argv_action'] = 2;
$configer['url_suffix'] = "suffix";
$configer['argv_suffix'] = 3;
$configer['url_charset'] = 'charset';
$configer['argv_charset'] = 4;
$configer['url_which'] = 'which';
$configer['argv_which'] = 5;
$configer['url_p'] = 'p';
$configer['argv_p'] = 6;
################### 不嫩在executor下被重写  E

//mysql配置
$configer['mysql_config']['host'] = '192.168.1.100';
//$configer['mysql_config']['host'] = '110.86.13.78';
$configer['mysql_config']['port'] = '3306';
$configer['mysql_config']['username'] = 'VS_Admin';
$configer['mysql_config']['password'] = '(#$JGKhw-902j';
$configer['mysql_config']['dbname'] = '8684bus_tmp';
$configer['mysql_config']['charset'] = 'utf8';

//$configer['mysql_config']['host'] = SAE_MYSQL_HOST_M;
//$configer['mysql_config']['port'] = SAE_MYSQL_PORT;
//$configer['mysql_config']['username'] = SAE_MYSQL_USER;
//$configer['mysql_config']['password'] = SAE_MYSQL_PASS;
//$configer['mysql_config']['dbname'] = SAE_MYSQL_DB;
//$configer['mysql_config']['charset'] = 'utf8';

$configer['mysql_config']['host'] = '127.0.0.1';
$configer['mysql_config']['port'] = '3306';
$configer['mysql_config']['username'] = 'root';
$configer['mysql_config']['password'] = 'vertrigo';
$configer['mysql_config']['dbname'] = 'xtown';
$configer['mysql_config']['charset'] = 'utf8';

//分页
$configer['pager'] = array('total'=>1000,'perpage'=>20);

//自动加载的工具脚本
#$configer['tools'] = array(array('脚本名','脚本所在空间',是否是工具类)); 注脚本名一定要
//$configer['tools'] = array(array('main','',false),array('simple_html_dom','',false),array('log_email','',true),array('mysqldb','',true,$configer['mysql_config']));
$configer['tools'] = array(array('http_client.class','',false),array('main','',false),array('simple_html_dom','',false),array('log_email','',true),);
