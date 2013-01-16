@echo off
:loop
	php ../index.php 10w get_movie_info_douban_imdb 1 gbk 30000 38000 2 0
	ping -n 20 127.1>nul
goto loop
