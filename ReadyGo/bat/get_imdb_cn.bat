@echo off
set /p choise= Press Enter
:loop
	::���ӿ��
	rasdial "�������" 100003029634 s5a4g2x6
	php ../index.php 10w get_imdb_cn 1 gbk 2008 2011
	::�Ͽ����
	rasdial "�������" /d
	ping -n 3 127.1>nul
goto loop