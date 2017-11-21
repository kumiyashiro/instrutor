<?php require_once('Connections/conn_instrutor.php'); ?>
<?php require_once('Connections/conn_instrutor.php'); ?>
<?php
//initialize the session
if (!isset($_SESSION)) {
  session_start();
}

// ** Logout the current user. **
$logoutAction = $_SERVER['PHP_SELF']."?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")){
  $logoutAction .="&". htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")){
  //to fully log out a visitor we need to clear the session varialbles
  $_SESSION['MM_Username'] = NULL;
  $_SESSION['MM_UserGroup'] = NULL;
  $_SESSION['PrevUrl'] = NULL;
  unset($_SESSION['MM_Username']);
  unset($_SESSION['MM_UserGroup']);
  unset($_SESSION['PrevUrl']);
	
  $logoutGoTo = "login.php";
  if ($logoutGoTo) {
    header("Location: $logoutGoTo");
    exit;
  }
}
?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "1,2";
$MM_donotCheckaccess = "false";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && false) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "login.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($QUERY_STRING) && strlen($QUERY_STRING) > 0) 
  $MM_referrer .= "?" . $QUERY_STRING;
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

$campo1_rsMil = "-1";
if (isset($_POST['txt'])) {
  $campo1_rsMil = $_POST['txt'];
}
mysql_select_db($database_conn_instrutor, $conn_instrutor);
$query_rsMil = sprintf("SELECT * FROM tbl_militar WHERE mil_situacao = 'Ativado' AND mil_nomeguerra LIKE %s ", GetSQLValueString("%" . $campo1_rsMil . "%", "text"));
$rsMil = mysql_query($query_rsMil, $conn_instrutor) or die(mysql_error());
$row_rsMil = mysql_fetch_assoc($rsMil);
$campo1_rsMil = "-1";
if (isset($_POST['txt'])) {
  $campo1_rsMil = $_POST['txt'];
}
mysql_select_db($database_conn_instrutor, $conn_instrutor);
$query_rsMil = sprintf("SELECT * FROM tbl_militar WHERE mil_nomeguerra LIKE %s  AND mil_situacao = 'Ativado'  ", GetSQLValueString("%" . $campo1_rsMil . "%", "text"));
$rsMil = mysql_query($query_rsMil, $conn_instrutor) or die(mysql_error());
$row_rsMil = mysql_fetch_assoc($rsMil);
$totalRows_rsMil = mysql_num_rows($rsMil);

$colname_rsLogin = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_rsLogin = $_SESSION['MM_Username'];
}
mysql_select_db($database_conn_instrutor, $conn_instrutor);
$query_rsLogin = sprintf("SELECT * FROM tbl_usuarios WHERE usu_nome = %s", GetSQLValueString($colname_rsLogin, "text"));
$rsLogin = mysql_query($query_rsLogin, $conn_instrutor) or die(mysql_error());
$row_rsLogin = mysql_fetch_assoc($rsLogin);
$totalRows_rsLogin = mysql_num_rows($rsLogin);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Instrutor</title>
<link href="estilo.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
a:link {
	color: #000;
	text-decoration: none;
}
a:visited {
	text-decoration: none;
	color: #000;
}
a:hover {
	text-decoration: underline;
	color: #333;
}
a:active {
	text-decoration: none;
	color: #000;
}
-->
</style></head>

<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center" class="fonte_geral">.:: MILITARES CADASTRADOS ::.</td>
  </tr>
  <tr>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td align="center" class="fonte_geral"><a href="index.php">VOLTAR</a></td>
  </tr>
  <tr>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td align="center"><form id="form1" name="form1" method="post" action="">
      <label>
        <input name="txt" type="text" class="TFbordaEsqPesquisa" id="txt" />
      </label>
      <label>
        <input type="submit" name="button" id="button" value="Pesquisar" />
      </label>
    </form></td>
  </tr>
  <tr>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td align="center"><table width="800" border="0" cellpadding="0" cellspacing="1" class="fonte_geral">
      <tr align="center" bgcolor="#999999">
        <td>Posto/Grad</td>
        <td>Nome de Guerra</td>
        <td>Nome Completo</td>
        <td>Pelotão</td>
        <td>Função</td>
        <td>&nbsp;</td>
      </tr>
      <?php do { ?>
        <tr align="center" bgcolor="#CCCCCC">
          <td nowrap="nowrap"><?php echo $row_rsMil['mil_pgrad']; ?></td>
          <td nowrap="nowrap"><a href="ficha_militar.php?mil_id=<?php echo $row_rsMil['mil_id']; ?>" target="_blank"><?php echo $row_rsMil['mil_nomeguerra']; ?></a></td>
          <td nowrap="nowrap"><a href="ficha_militar.php?mil_id=<?php echo $row_rsMil['mil_id']; ?>" target="_blank"><?php echo $row_rsMil['mil_nomecompleto']; ?></a></td>
          <td nowrap="nowrap"><?php echo $row_rsMil['mil_pel']; ?></td>
          <td nowrap="nowrap"><?php echo $row_rsMil['mil_funcao']; ?></td>
          <td nowrap="nowrap"><a href="ficha_militar.php?mil_id=<?php echo $row_rsMil['mil_id']; ?>" target="_blank">&nbsp;Visualizar Ficha</a></td>
          
        </tr>
        <?php } while ($row_rsMil = mysql_fetch_assoc($rsMil)); ?>
    </table></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr class="fonte_geral">
    <td align="center"><?php echo $row_rsLogin['usu_nome']; ?> | <a href="<?php echo $logoutAction ?>">Sair</a></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="center" class="fonte_geral">DESENVOLVIDO PELO 2º SGT KLEBER UEHARA MIYASHIRO - 2017</td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($rsMil);

mysql_free_result($rsLogin);
?>
