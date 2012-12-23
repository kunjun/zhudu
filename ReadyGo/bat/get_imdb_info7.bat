@echo off
set /p choise= Press Enter
:loop
	php ../index.php 10w get_imdb_info 1 gbk 95000 95500
	ping -n 20 127.1>nul
goto loop
