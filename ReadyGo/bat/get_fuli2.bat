@echo off
set /p choise= Press Enter
:loop
	::���ӿ��
	rasdial "�������" 100003029634 s5a4g2x6
	php ../index.php 10w get_fuli 1 gbk 1487 1567
	::�Ͽ����
	rasdial "�������" /d
	ping -n 3 127.1>nul
goto loop

