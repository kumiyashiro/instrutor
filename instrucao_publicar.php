<?php require_once('Connections/conn_instrutor.php'); ?>
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO tbl_instrucao (inst_data, inst_assunto, inst_titulo, inst_texto, inst_situacao, inst_resp) VALUES (%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['inst_data'], "text"),
                       GetSQLValueString($_POST['inst_assunto'], "text"),
                       GetSQLValueString($_POST['inst_titulo'], "text"),
                       GetSQLValueString($_POST['inst_texto'], "text"),
                       GetSQLValueString($_POST['inst_situacao'], "text"),
                       GetSQLValueString($_POST['inst_resp'], "text"));

  mysql_select_db($database_conn_instrutor, $conn_instrutor);
  $Result1 = mysql_query($insertSQL, $conn_instrutor) or die(mysql_error());

  $insertGoTo = "instrucao_index.php";
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
</style>


<script src="ckeditor/ckeditor.js"></script>
</head>

<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center" class="fonte_geral">.:: ADICIONAR NOVA PUBLICAÇÃO ::.</td>
  </tr>
  <tr>
    <td align="center" class="fonte_geral">&nbsp;</td>
  </tr>
  <tr>
    <td align="center" class="fonte_geral"><a href="instrucao_index.php">VOLTAR</a></td>
  </tr>
  <tr>
    <td align="center" class="fonte_geral">&nbsp;</td>
  </tr>
  <tr>
    <td align="center"><form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
        <table align="center">
          <tr valign="baseline" class="fonte_geral">
            <td colspan="2" align="left" nowrap="nowrap">Data da Publicação:
              <input type="text" name="inst_data" value="<?php
$dataAtual = date("d/m/Y");
echo $dataAtual;
?>" size="32" /></td>
          </tr>
          <tr valign="baseline" class="fonte_geral">
            <td colspan="2" align="left" nowrap="nowrap">Categoria:
              <input type="text" name="inst_assunto" value="" size="50" /></td>
          </tr>
          <tr valign="baseline" class="fonte_geral">
            <td colspan="2" align="left" nowrap="nowrap">Título:
              <input type="text" name="inst_titulo" value="" size="100" /></td>
          </tr>
          <tr align="center" valign="baseline" class="fonte_geral">
            <td colspan="2" nowrap="nowrap"><textarea name="inst_texto" cols="120"></textarea></td>
          </tr>
          <tr valign="baseline">
            <td align="left" nowrap="nowrap" class="fonte_geral">Situação:
              <input name="inst_situacao" type="text" value="Aprovado" size="32" readonly="readonly" /></td>
            <td align="left"><span class="fonte_geral">Responsável</span>:              <input type="text" name="inst_resp" value="<?php echo $row_rsLogin['usu_nome']; ?>" size="32" /></td>
          </tr>
          <tr valign="baseline" class="fonte_geral">
            <td colspan="2" align="left" nowrap="nowrap">&nbsp;</td>
          </tr>
          <tr valign="baseline" class="fonte_geral">
            <td colspan="2" align="center" nowrap="nowrap"><input type="submit" value="Publicar" /></td>
          </tr>
        </table>
        <input type="hidden" name="MM_insert" value="form1" />
    </form>
<script>
      CKEDITOR.replace('inst_texto');
//]]></script>
    </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr class="fonte_geral">
    <td align="center"> <?php echo $row_rsLogin['usu_nome']; ?>&nbsp;| <a href="<?php echo $logoutAction ?>">Sair</a></td>
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

?>
