@echo off
set /p choise= Press Enter
:loop
	php ../index.php 10w get_fuli 1 gbk 1780 1825
	ping -n 20 127.1>nul
goto loop
