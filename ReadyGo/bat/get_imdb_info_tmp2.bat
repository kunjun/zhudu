@echo off
:loop
	php ../index.php 10w get_imdb_info 1 gbk 217000 218000
	ping -n 20 127.1>nul
goto loop
