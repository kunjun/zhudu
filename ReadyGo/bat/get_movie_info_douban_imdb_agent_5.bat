@echo off
:loop
	php ../index.php 10w get_movie_info_douban_imdb 1 gbk 25000 30000 1 0
	ping -n 20 127.1>nul
goto loop
