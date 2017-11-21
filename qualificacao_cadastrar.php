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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO tbl_qualificar (qual_cpf, qual_qualific, qual_resp) VALUES (%s, %s, %s)",
                       GetSQLValueString($_POST['qual_cpf'], "text"),
                       GetSQLValueString($_POST['qual_qualific'], "text"),
                       GetSQLValueString($_POST['qual_resp'], "text"));

  mysql_select_db($database_conn_instrutor, $conn_instrutor);
  $Result1 = mysql_query($insertSQL, $conn_instrutor) or die(mysql_error());

  $insertGoTo = "qualificacao_cadastrar.php?mil_id=%s";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
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

$colname_rsMil = "-1";
if (isset($_GET['mil_id'])) {
  $colname_rsMil = $_GET['mil_id'];
}
mysql_select_db($database_conn_instrutor, $conn_instrutor);
$query_rsMil = sprintf("SELECT * FROM tbl_militar WHERE mil_id = %s", GetSQLValueString($colname_rsMil, "int"));
$rsMil = mysql_query($query_rsMil, $conn_instrutor) or die(mysql_error());
$row_rsMil = mysql_fetch_assoc($rsMil);
$totalRows_rsMil = mysql_num_rows($rsMil);

$colname_rsQual = "-1";
if (isset($_GET['mil_id'])) {
  $colname_rsQual = $_GET['mil_id'];
}
mysql_select_db($database_conn_instrutor, $conn_instrutor);
$query_rsQual = sprintf("SELECT * FROM tbl_militar, tbl_qualificar WHERE tbl_militar.mil_id = %s AND tbl_militar.mil_cpf = tbl_qualificar.qual_cpf", GetSQLValueString($colname_rsQual, "int"));
$rsQual = mysql_query($query_rsQual, $conn_instrutor) or die(mysql_error());
$row_rsQual = mysql_fetch_assoc($rsQual);
$totalRows_rsQual = mysql_num_rows($rsQual);
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
    <td align="center" class="fonte_geral">.:: CADASTRAR QUALIFICAÇÃO ::.</td>
  </tr>
  <tr>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td align="center"><span class="fonte_geral"><a href="index.php">VOLTAR</a></span></td>
  </tr>
  <tr>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
<td><form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table align="center" class="fonte_geral">
    <tr valign="baseline">
      <td colspan="2" align="center" nowrap="nowrap"><table width="200" border="0" cellspacing="2" cellpadding="0">
        <tr>
          <td align="center"><img src="fotos/<?php echo $row_rsMil['mil_foto']; ?>" alt="" width="120" height="160" /></td>
        </tr>
        <tr>
          <td align="center" nowrap="nowrap"><?php echo $row_rsMil['mil_pgrad']; ?> <?php echo $row_rsMil['mil_nomeguerra']; ?></td>
        </tr>
      </table></td>
      </tr>
    <tr valign="baseline">
      <td colspan="2" align="center" nowrap="nowrap">CPF: 
        <input name="qual_cpf" type="text" value="<?php echo $row_rsMil['mil_cpf']; ?>" size="32" readonly="readonly" /></td>
      </tr>
    <tr valign="baseline">
      <td colspan="2" align="center" nowrap="nowrap">&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <td colspan="2" align="center" nowrap="nowrap"><strong>Descrição da Qualificação</strong></td>
      </tr>
    <tr valign="baseline">
      <td colspan="2" align="center" nowrap="nowrap"><textarea name="qual_qualific" cols="50" rows="2"></textarea></td>
      </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right"><strong>Usuário:</strong></td>
      <td><input type="text" name="qual_resp" value="<?php echo $row_rsLogin['usu_nome']; ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td colspan="2" align="center" nowrap="nowrap">&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <td colspan="2" align="center" nowrap="nowrap"><input type="submit" value="Adicionar" /></td>
      </tr>
  </table>
  <input type="hidden" name="MM_insert" value="form1" />
</form></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr class="fonte_geral">
    <td align="center"><table width="790" border="0" cellspacing="2" cellpadding="0">
      <tr align="center" bgcolor="#999999">
        <td width="584">QUALIFICAÇÃO</td>
        <td width="200" align="center">&nbsp;</td>
      </tr>
      <?php do { ?>
        <tr bgcolor="#CCCCCC">
          <td>- <?php echo $row_rsQual['qual_qualific']; ?></td>
          <td align="center"> <a href="javascript:if(confirm('Deseja excluir esse registro?')) {location = 'qualificacao_excluir.php?qual_id=<?php echo $row_rsQual['qual_id']; ?>';}">EXCLUIR</a></td>
          
          
        </tr>
        <?php } while ($row_rsQual = mysql_fetch_assoc($rsQual)); ?>
    </table></td>
  </tr>
  <tr class="fonte_geral">
    <td align="center">&nbsp;</td>
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
mysql_free_result($rsLogin);

mysql_free_result($rsMil);

mysql_free_result($rsQual);
?>
