SET PHP_CLI="C:\Users\cgarcia\Desktop\xampp\php\php.exe"
SET PHP_INI="C:\Users\cgarcia\Desktop\xampp\php\php.ini"
SET DOCUMENTOR_PATH="C:\SVN\DESARROLLO_WEB\PERU\EOL\DESARROLLO\online\empresas\application\phpDocumentor2-develop\bin\phpdoc.bat"
SET TIPO="HTML"
SET TARGET_DIR="C:\SVN\DESARROLLO_WEB\PERU\EOL\DESARROLLO\online\empresas\application\docs"
SET projects="C:\SVN\DESARROLLO_WEB\PERU\EOL\DESARROLLO\online\empresas\application\controllers"

call phpdoc -d %DOCUMENTOR_PATH% -t %TARGET_DIR% -ue on -ti "holamundo" -pp off -po %projects% -o %TIPO% -s off