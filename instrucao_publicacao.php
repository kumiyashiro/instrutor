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

$colname_rsLogin = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_rsLogin = $_SESSION['MM_Username'];
}
mysql_select_db($database_conn_instrutor, $conn_instrutor);
$query_rsLogin = sprintf("SELECT * FROM tbl_usuarios WHERE usu_nome = %s", GetSQLValueString($colname_rsLogin, "text"));
$rsLogin = mysql_query($query_rsLogin, $conn_instrutor) or die(mysql_error());
$row_rsLogin = mysql_fetch_assoc($rsLogin);
$totalRows_rsLogin = mysql_num_rows($rsLogin);

$colname_rsInst = "-1";
if (isset($_GET['inst_id'])) {
  $colname_rsInst = $_GET['inst_id'];
}
mysql_select_db($database_conn_instrutor, $conn_instrutor);
$query_rsInst = sprintf("SELECT * FROM tbl_instrucao WHERE inst_id = %s", GetSQLValueString($colname_rsInst, "int"));
$rsInst = mysql_query($query_rsInst, $conn_instrutor) or die(mysql_error());
$row_rsInst = mysql_fetch_assoc($rsInst);
$totalRows_rsInst = mysql_num_rows($rsInst);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $row_rsInst['inst_titulo']; ?> - <?php echo $row_rsInst['inst_assunto']; ?></title>
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
	color: #000;
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
    <td align="center" class="fonte_geral">2ª COMPANHIA DE COMUNICAÇÕES LEVE</td>
  </tr>
  <tr>
    <td align="center" class="fonte_geral">_________________________________________________________________________________________________________________________________</td>
  </tr>
  <tr>
    <td align="center" class="fonte_geral">&nbsp;</td>
  </tr>
  <tr>
    <td align="center"><table width="800" border="0" cellpadding="0" cellspacing="2" class="fonte_geral">
      <tr>
        <td><strong>Categoria: </strong><?php echo $row_rsInst['inst_assunto']; ?></td>
        </tr>
      <tr>
        <td><strong>Título</strong>: <?php echo $row_rsInst['inst_titulo']; ?></td>
        </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td align="center" class="Barra2">&nbsp;</td>
      </tr>
      <tr>
        <td align="left"><?php echo $row_rsInst['inst_texto']; ?></td>
      </tr>
      <tr>
        <td align="center">&nbsp;</td>
        </tr>
      <tr>
        <td align="center" class="Barra2">&nbsp;</td>
      </tr>
      <tr>
        <td align="right"><strong>Publicado por:</strong> <?php echo $row_rsInst['inst_resp']; ?> <strong>em</strong> <?php echo $row_rsInst['inst_data']; ?></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td align="center" class="fonte_geral"><?php echo $row_rsLogin['usu_nome']; ?> | <a href="<?php echo $logoutAction ?>">Sair</a></td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($rsLogin);

mysql_free_result($rsInst);
?>
