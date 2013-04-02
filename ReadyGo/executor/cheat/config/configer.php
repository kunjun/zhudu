<?php  if ( ! defined('ROOT_PATH')) exit('非法访问！！！');

/*
应用配置信息
*/

//mysql配置
$configer['mysql_config']['host'] = '127.0.0.1';
$configer['mysql_config']['port'] = '3306';
$configer['mysql_config']['username'] = 'root';
$configer['mysql_config']['password'] = 'vertrigo';
$configer['mysql_config']['dbname'] = '10w';
$configer['mysql_config']['charset'] = 'utf8';

//自动加载的工具脚本
#$configer['tools'] = array(array('脚本名','脚本所在空间',是否是工具类)); 注脚本名一定要
$configer['tools'] = array(array('main','',false),array('simple_html_dom','',false),array('log_email','',true),array('mysqldb','',true,$configer['mysql_config']));
