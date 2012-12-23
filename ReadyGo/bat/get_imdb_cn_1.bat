@echo off
set /p choise= Press Enter
:loop
	php ../index.php 10w get_imdb_cn 1 gbk 2012 2013
	ping -n 20 127.1>nul
goto loop