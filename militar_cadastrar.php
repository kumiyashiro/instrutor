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

$colname_rsLogin = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_rsLogin = $_SESSION['MM_Username'];
}
mysql_select_db($database_conn_instrutor, $conn_instrutor);
$query_rsLogin = sprintf("SELECT * FROM tbl_usuarios WHERE usu_nome = %s", GetSQLValueString($colname_rsLogin, "text"));
$rsLogin = mysql_query($query_rsLogin, $conn_instrutor) or die(mysql_error());
$row_rsLogin = mysql_fetch_assoc($rsLogin);
$totalRows_rsLogin = mysql_num_rows($rsLogin);

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
	
	$arquivo = $_FILES["mil_foto"];
$arquivo_nome = $arquivo["name"];	
	
  $insertSQL = sprintf("INSERT INTO tbl_militar (mil_nomecompleto, mil_datanasc, mil_pai, mil_mae, mil_ctt, mil_naturalidade, mil_end, mil_pgrad, mil_nomeguerra, mil_idtmil, mil_cpf, mil_dataincorp, mil_pel, mil_funcao, mil_foto, mil_antiguidade, mil_situacao) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s,'$arquivo_nome', %s, %s)",
                       GetSQLValueString($_POST['mil_nomecompleto'], "text"),
                       GetSQLValueString($_POST['mil_datanasc'], "text"),
                       GetSQLValueString($_POST['mil_pai'], "text"),
                       GetSQLValueString($_POST['mil_mae'], "text"),
                       GetSQLValueString($_POST['mil_ctt'], "text"),
                       GetSQLValueString($_POST['mil_naturalidade'], "text"),
                       GetSQLValueString($_POST['mil_end'], "text"),
                       GetSQLValueString($_POST['mil_pgrad'], "text"),
                       GetSQLValueString($_POST['mil_nomeguerra'], "text"),
                       GetSQLValueString($_POST['mil_idtmil'], "text"),
                       GetSQLValueString($_POST['mil_cpf'], "text"),
                       GetSQLValueString($_POST['mil_dataincorp'], "text"),
                       GetSQLValueString($_POST['mil_pel'], "text"),
                       GetSQLValueString($_POST['mil_funcao'], "text"),
                       //GetSQLValueString($_POST['mil_foto'], "text"));
					   GetSQLValueString($_POST['mil_antiguidade'], "text"),
					   GetSQLValueString($_POST['mil_situacao'], "text"));
					  
  
  set_time_limit(0);
$diretorio = "fotos/";
$id_arquivo = "mil_foto";
$nome_arquivo = $_FILES[$id_arquivo]["name"];
$arquivo_temporario = $_FILES[$id_arquivo]["tmp_name"];
move_uploaded_file($arquivo_temporario, "$diretorio/$nome_arquivo");
  

  mysql_select_db($database_conn_instrutor, $conn_instrutor);
  $Result1 = mysql_query($insertSQL, $conn_instrutor) or die(mysql_error());

  $insertGoTo = "militar_lista.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Cadastrar Militar</title>
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
    <td align="center" class="fonte_geral">.:: CADASTRAR MILITARES ::.</td>
  </tr>
  <tr>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td align="center"><span class="fonte_geral"><a href="administracao.php">VOLTAR</a></span></td>
  </tr>
  <tr>
    <td align="center">&nbsp;
      <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1" enctype="multipart/form-data">
        <table align="center" class="fonte_geral">
          <tr valign="baseline">
            <td nowrap="nowrap" align="right">Nome Completo:</td>
            <td><input type="text" name="mil_nomecompleto" value="" size="50" /></td>
          </tr>
          <tr valign="baseline">
            <td nowrap="nowrap" align="right">Data de Nascimento:</td>
            <td><input type="text" name="mil_datanasc" value="" size="50" /></td>
          </tr>
          <tr valign="baseline">
            <td nowrap="nowrap" align="right">Nome de Pai:</td>
            <td><input type="text" name="mil_pai" value="" size="50" /></td>
          </tr>
          <tr valign="baseline">
            <td nowrap="nowrap" align="right">Nome de Mãe:</td>
            <td><input type="text" name="mil_mae" value="" size="50" /></td>
          </tr>
          <tr valign="baseline">
            <td nowrap="nowrap" align="right">Telefone Contato:</td>
            <td><input type="text" name="mil_ctt" value="" size="50" /></td>
          </tr>
          <tr valign="baseline">
            <td nowrap="nowrap" align="right">Naturalidade:</td>
            <td><input type="text" name="mil_naturalidade" value="" size="50" /></td>
          </tr>
          <tr valign="baseline">
            <td nowrap="nowrap" align="right">Endereço:</td>
            <td><textarea name="mil_end" cols="50" rows="2"></textarea></td>
          </tr>
          <tr valign="baseline">
            <td nowrap="nowrap" align="right">Posto/Graduação:</td>
            <td><input type="text" name="mil_pgrad" value="" size="50" /></td>
          </tr>
          <tr valign="baseline">
            <td nowrap="nowrap" align="right">Nome de Guerra:</td>
            <td><input type="text" name="mil_nomeguerra" value="" size="50" /></td>
          </tr>
          <tr valign="baseline">
            <td nowrap="nowrap" align="right">Identidade Militar:</td>
            <td><input type="text" name="mil_idtmil" value="" size="50" /></td>
          </tr>
          <tr valign="baseline">
            <td nowrap="nowrap" align="right">CPF:</td>
            <td><input type="text" name="mil_cpf" value="" size="50" /></td>
          </tr>
          <tr valign="baseline">
            <td nowrap="nowrap" align="right">Data de Incorporação:</td>
            <td><input type="text" name="mil_dataincorp" value="" size="50" /></td>
          </tr>
          <tr valign="baseline">
            <td nowrap="nowrap" align="right">Pelotão:</td>
            <td><input type="text" name="mil_pel" value="" size="50" /></td>
          </tr>
          <tr valign="baseline">
            <td nowrap="nowrap" align="right">Função:</td>
            <td><input type="text" name="mil_funcao" value="" size="50" /></td>
          </tr>
          <tr valign="baseline">
            <td nowrap="nowrap" align="right">Foto:</td>
            <td><input type="file" name="mil_foto" value="" size="50" /></td>
          </tr>
          <tr valign="baseline">
            <td nowrap="nowrap" align="right">Antiguidade:</td>
            <td><input type="text" name="mil_antiguidade" value="" size="50" /></td>
          </tr>
          <tr valign="baseline">
            <td nowrap="nowrap" align="right">Situação:</td>
            <td><input name="mil_situacao" type="text" value="Ativado" size="50" readonly="readonly" /></td>
          </tr>
          <tr valign="baseline">
            <td nowrap="nowrap" align="right">&nbsp;</td>
            <td><input type="submit" value="Cadastrar Militar" /></td>
          </tr>
        </table>
        <input type="hidden" name="MM_insert" value="form1" />
      </form>
    <p>&nbsp;</p></td>
  </tr>
  <tr class="fonte_geral">
    <td align="center"><?php echo $row_rsLogin['usu_nome']; ?> | <a href="<?php echo $logoutAction ?>">Sair</a></td>
  </tr>
  <tr>
    <td align="center">&nbsp;</td>
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
