@echo off
:loop
	php ../index.php 10w get_movie_info_douban_imdb 1 gbk 206000 210000 1 1
	ping -n 20 127.1>nul
goto loop
