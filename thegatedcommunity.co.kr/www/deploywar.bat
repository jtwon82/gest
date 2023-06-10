ECHO Y | RMDIR /S C:\APM_Setup\htdocs
MKDIR C:\APM_Setup\htdocs

xcopy C:\workset\workspace-gest\gest\thegatedcommunity.co.kr\*.* C:\APM_Setup\htdocs\ /e

