<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_conn = "localhost";
//SERVIDOR
/*$database_conn = "kafekafe_sterilav";
$username_conn = "kafekafe_admin";
$password_conn = "sterilavAdmin2012";*/

//LOCAL
$database_conn = "sterilav";
$username_conn = "root";
$password_conn = "";
$conn = mysql_pconnect($hostname_conn, $username_conn, $password_conn) or trigger_error(mysql_error(),E_USER_ERROR); 
?>