@echo off
:loop
	::���ӿ��
	rasdial "�������" 100003029634 s5a4g2x6
	php ../index.php 10w get_movie_info_douban_imdb 1 gbk 1 70000 0 1
	::�Ͽ����
	rasdial "�������" /d
	ping -n 3 127.1>nul
goto loop
