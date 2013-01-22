@echo off
:loop
	php ../index.php 10w get_imdb_info 1 gbk 216000 217000
	ping -n 20 127.1>nul
goto loop
