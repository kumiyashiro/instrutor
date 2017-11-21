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
$MM_authorizedUsers = "1";
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE tbl_usuarios SET usu_nome=%s, usu_senha=%s, usu_tipo=%s WHERE usu_id=%s",
                       GetSQLValueString($_POST['usu_nome'], "text"),
                       GetSQLValueString($_POST['usu_senha'], "text"),
                       GetSQLValueString($_POST['usu_tipo'], "text"),
                       GetSQLValueString($_POST['usu_id'], "int"));

  mysql_select_db($database_conn_instrutor, $conn_instrutor);
  $Result1 = mysql_query($updateSQL, $conn_instrutor) or die(mysql_error());

  $updateGoTo = "usuario_cadastrar.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_rsUsuarios = "-1";
if (isset($_GET['usu_id'])) {
  $colname_rsUsuarios = $_GET['usu_id'];
}
mysql_select_db($database_conn_instrutor, $conn_instrutor);
$query_rsUsuarios = sprintf("SELECT * FROM tbl_usuarios WHERE usu_id = %s", GetSQLValueString($colname_rsUsuarios, "int"));
$rsUsuarios = mysql_query($query_rsUsuarios, $conn_instrutor) or die(mysql_error());
$row_rsUsuarios = mysql_fetch_assoc($rsUsuarios);
$totalRows_rsUsuarios = mysql_num_rows($rsUsuarios);

$colname_rsLogin = "-1";
if (isset($_SERVER['MM_Username'])) {
  $colname_rsLogin = $_SERVER['MM_Username'];
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
}
a:visited {
	color: #000;
}
a:hover {
	color: #000;
}
a:active {
	color: #000;
}
-->
</style></head>

<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td align="center"><span class="fonte_geral">.:: EDITAR USUÁRIO ::.</span></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="center" class="fonte_geral"><a href="index.php">VOLTAR</a></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="center"><form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
      <table align="center" class="fonte_geral">
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Usuário:</td>
          <td><input name="usu_nome" type="text" class="TFbordaEsqLogin" value="<?php echo htmlentities($row_rsUsuarios['usu_nome'], ENT_COMPAT, 'utf-8'); ?>" size="32" readonly="readonly" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Alterar Senha:</td>
          <td><input name="usu_senha" type="password" class="TFbordaEsqLogin" value="<?php echo htmlentities($row_rsUsuarios['usu_senha'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Alterar Tipo:</td>
          <td><select name="usu_tipo">
            <option value="1" <?php if (!(strcmp(1, htmlentities($row_rsUsuarios['usu_tipo'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Administrador</option>
            <option value="2" <?php if (!(strcmp(2, htmlentities($row_rsUsuarios['usu_tipo'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Usuário</option>
            <option value="3" <?php if (!(strcmp(3, htmlentities($row_rsUsuarios['usu_tipo'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Convidado</option>
          </select></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">&nbsp;</td>
          <td><input type="submit" value="Atualizar Cadastro" /></td>
        </tr>
      </table>
      <input type="hidden" name="MM_update" value="form1" />
      <input type="hidden" name="usu_id" value="<?php echo $row_rsUsuarios['usu_id']; ?>" />
    </form></td>
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
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($rsUsuarios);

mysql_free_result($rsLogin);
?>
