@echo off
set /p choise= Press Enter
:loop
	::连接宽带
	rasdial "宽带连接" 100003029634 s5a4g2x6
	php ../index.php 10w get_fuli 1 gbk 1487 1567
	::断开宽带
	rasdial "宽带连接" /d
	ping -n 3 127.1>nul
goto loop

