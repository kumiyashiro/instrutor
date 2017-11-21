<?php


//RETIRA A MENSAGEM DE ERRO DO MYSQL NO NAVEGADOR

error_reporting (E_ALL & ~ E_NOTICE & ~ E_DEPRECATED);
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_conn_instrutor = "localhost";
$database_conn_instrutor = "instrutor_db";
$username_conn_instrutor = "root";
$password_conn_instrutor = "vertrigo";
$conn_instrutor = mysql_pconnect($hostname_conn_instrutor, $username_conn_instrutor, $password_conn_instrutor) or trigger_error(mysql_error(),E_USER_ERROR); 
?>