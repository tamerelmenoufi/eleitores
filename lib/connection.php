<?php
if ($_SERVER['HTTP_HOST'] === 'localhost' || $_SERVER['HTTP_HOST'] === '10.0.0.115') {
    define('DB_HOST', 'localhost');
    define('DB_USERNAME', 'root');
    define('DB_PASSWORD', '');
    define('DB_DATABASE', 'politica');
} else {
    define('DB_HOST', '3.93.20.163');
    define('DB_USERNAME', 'eleitores');
    define('DB_PASSWORD', 'Mf6t1y76');
    define('DB_DATABASE', 'eleitores');
}

echo DB_HOST.' & '.DB_USERNAME.' & '.DB_PASSWORD;

$con = mysql_connect(DB_HOST, DB_USERNAME, DB_PASSWORD);
mysql_set_charset('utf8', $con);
mysql_select_db(DB_DATABASE, $con);

if (!$con) {
    die('Não foi possível conectar: ' . mysql_error());
}

exit();
#mysql_close($con);