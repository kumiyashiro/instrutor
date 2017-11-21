<?php require_once('Connections/conn_instrutor.php'); ?>
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
?>
<?php
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['textfield'])) {
  $loginUsername=$_POST['textfield'];
  $password=$_POST['textfield2'];
  $MM_fldUserAuthorization = "usu_tipo";
  $MM_redirectLoginSuccess = "index_principal.php";
  $MM_redirectLoginFailed = "login.php";
  $MM_redirecttoReferrer = false;
  mysql_select_db($database_conn_instrutor, $conn_instrutor);
  	
  $LoginRS__query=sprintf("SELECT usu_nome, usu_senha, usu_tipo FROM tbl_usuarios WHERE usu_nome=%s AND usu_senha=%s",
  GetSQLValueString($loginUsername, "text"), GetSQLValueString($password, "text")); 
   
  $LoginRS = mysql_query($LoginRS__query, $conn_instrutor) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);
  if ($loginFoundUser) {
    
    $loginStrGroup  = mysql_result($LoginRS,0,'usu_tipo');
    
    //declare two session variables and assign them
    $_SESSION['MM_Username'] = $loginUsername;
    $_SESSION['MM_UserGroup'] = $loginStrGroup;	      

    if (isset($_SESSION['PrevUrl']) && false) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
    }
    header("Location: " . $MM_redirectLoginSuccess );
  }
  else {
    header("Location: ". $MM_redirectLoginFailed );
  }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Instrutor</title>
<link href="estilo.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
body {
	background-image: url();
	background-repeat: no-repeat;
}
-->
</style></head>

<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center" class="fonte_TITULO">&nbsp;</td>
  </tr>
  <tr>
    <td align="center" class="fonte_TITULO">&nbsp;</td>
  </tr>
  <tr>
    <td align="center"><form id="form1" name="form1" method="POST" action="<?php echo $loginFormAction; ?>">
      <span class="fonte_TITULO"><img src="imagens/logo_instrutor.jpg" width="250" height="150" /></span>
      <table width="500" border="0" cellpadding="0" cellspacing="2" class="fonte_geral">
        <tr>
          <td width="162" rowspan="8" align="right">&nbsp;</td>
          <td align="right" nowrap="nowrap">&nbsp;</td>
          <td align="center">&nbsp;</td>
          <td width="219" rowspan="8" align="center">&nbsp;</td>
        </tr>
        <tr>
          <td align="right" nowrap="nowrap">&nbsp;</td>
          <td align="center">&nbsp;</td>
          </tr>
        <tr>
          <td width="111" align="right" nowrap="nowrap">&nbsp;</td>
          <td width="219" align="center">&nbsp;</td>
          </tr>
        <tr>
          <td align="right" nowrap="nowrap"><label>Nome de Usuário:</label></td>
          <td align="left"><input name="textfield" type="text" id="textfield" /></td>
          </tr>
        <tr>
          <td align="right" nowrap="nowrap">&nbsp;</td>
          <td align="left">&nbsp;</td>
          </tr>
        <tr>
          <td align="right" nowrap="nowrap"><label>Senha:</label></td>
          <td align="left"><input name="textfield2" type="password" id="textfield2" /></td>
          </tr>
        <tr>
          <td align="center" nowrap="nowrap">&nbsp;</td>
          <td align="center">&nbsp;</td>
          </tr>
        <tr>
          <td align="center" nowrap="nowrap">&nbsp;</td>
          <td align="center"><input type="submit" name="button" id="button" value="Entrar" /></td>
          </tr>
      </table>
    </form></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="center" class="fonte_geral">&nbsp;</td>
  </tr>
  <tr>
    <td align="center" class="fonte_geral">2ª COMPANHIA DE COMUNICAÇÕES LEVE</td>
  </tr>
  <tr>
    <td align="center" class="fonte_geral">&nbsp;</td>
  </tr>
  <tr>
    <td align="center" class="fonte_geral">&nbsp;</td>
  </tr>
  <tr>
    <td align="center" class="fonte_geral">&nbsp;</td>
  </tr>
  <tr>
    <td align="center" class="fonte_geral">&nbsp;</td>
  </tr>
  <tr>
    <td align="center" class="fonte_geral">&nbsp;</td>
  </tr>
  <tr>
    <td align="center" class="fonte_geral">DESENVOLVIDO PELO 2º SGT KLEBER UEHARA MIYASHIRO - 2017</td>
  </tr>
</table>
</body>
</html>