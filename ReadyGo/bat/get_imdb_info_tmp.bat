@echo off
:loop
	::���ӿ��
	rasdial "�������" 100003029634 s5a4g2x6
	php ../index.php 10w get_imdb_info 1 gbk
	::�Ͽ����
	rasdial "�������" /d
	ping -n 3 127.1>nul
goto loop
