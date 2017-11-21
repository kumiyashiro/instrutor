<?php header("Content-Type: text/html; charset=utf-8", true); ?>
<?php
error_reporting (E_ALL & ~ E_NOTICE & ~ E_DEPRECATED);
$host="localhost"; // Host name
$username="root"; // Mysql username
$password="vertrigo"; // Mysql password
$db_name="instrutor_db"; // Database name


	$con = mysql_connect($host,$username,$password)   or die(mysql_error());
	mysql_select_db($db_name, $con)  or die(mysql_error());

$q = strtolower($_GET["q"]);
if (!$q) return;

$sql = "select DISTINCT inst_titulo from tbl_instrucao where inst_titulo LIKE '%$q%'";
$rsd = mysql_query($sql);
while($rs = mysql_fetch_array($rsd)) {
	$cname = $rs['inst_titulo'];
	echo "$cname\n";
}
?>
