<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="zh" xml:lang="zh" xmlns="http://www.w3.org/1999/xhtml">
    <head>
    	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
        <title>我的巴士在线</title>
        <meta name="viewport" content="wfidth=device-width,initial-scale=1.0,user-scalable=no" />
        <meta name="format-detection" content="telephone=no" />
        <meta id="cybgeo" name="geo" content="" />
        
        <style>
        	html{color:#111;background:#fff}
        	h3{margin:0;padding:0;float:left}
        	span{font: 14px/150% Arial,Helvetica,sans-serif;}
        	h3 {
				color: #666666;
				font: 14px/150% Arial,Helvetica,sans-serif;
			}
			h1, h2, h3, h4, h5, h6 {
				font-size: 100%;
				font-weight: normal;
			}
			
			a:link {
				color: #336699;
				text-decoration: none;
			}
			a:hover{
				color:#555555;
				text-decoration: underline;
			}
			a {
				cursor: pointer;
			}
			.clear{clear: both;}
			.m{margin:9px;}
			.m span{float:left}
        </style>
    </head>
    <body>
        <div>
			<div class="m">
			<span>路线：</span><h3><?php echo $data['BusLine'] ?></h3>
			</div>
			<div class="clear"></div>
			<div class="m">
			<span>当前站点：</span><h3><?php echo str_replace('当前站点: ','',$data['Station']) ?></h3>
			</div>
			<div class="clear"></div>
			<div class="m">
			<img src="<?php echo $data['BusInTimeImg'] ?>" alt="路线图" />
			</div>
			<div class="clear"></div>
			<div style="display:none"></div>
			<div class="clear"></div>
			<div class="m">
			<span>巴士Online：</span><h3><?php echo $data['BusInTime'] ?></h3>
			</div>
			<div class="clear"></div>
        </div>
    </body>
</html>