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
  $insertSQL = sprintf("INSERT INTO tbl_fo (fo_data, fo_tipo, fo_fato, fo_obs, fo_resp, fo_cpf) VALUES (%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['fo_data'], "text"),
                       GetSQLValueString($_POST['fo_tipo'], "text"),
                       GetSQLValueString($_POST['fo_fato'], "text"),
                       GetSQLValueString($_POST['fo_obs'], "text"),
                       GetSQLValueString($_POST['fo_resp'], "text"),
                       GetSQLValueString($_POST['fo_cpf'], "text"));

  mysql_select_db($database_conn_instrutor, $conn_instrutor);
  $Result1 = mysql_query($insertSQL, $conn_instrutor) or die(mysql_error());

  $insertGoTo = "fo_cadastrar.php";
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

$colname_rsFo = "-1";
if (isset($_GET['mil_id'])) {
  $colname_rsFo = $_GET['mil_id'];
}
mysql_select_db($database_conn_instrutor, $conn_instrutor);
$query_rsFo = sprintf("SELECT * FROM tbl_militar, tbl_fo WHERE tbl_militar.mil_id = %s AND tbl_militar.mil_cpf = tbl_fo.fo_cpf ORDER BY tbl_fo.fo_data DESC", GetSQLValueString($colname_rsFo, "int"));
$rsFo = mysql_query($query_rsFo, $conn_instrutor) or die(mysql_error());
$row_rsFo = mysql_fetch_assoc($rsFo);
$totalRows_rsFo = mysql_num_rows($rsFo);
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
    <td align="center" class="fonte_geral">.:: CADASTRAR FATO OBSERVADO ::.</td>
  </tr>
  <tr>
    <td align="center">&nbsp;
      <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
        <table align="center" class="fonte_geral">
          <tr valign="baseline">
            <td colspan="3" align="center" nowrap="nowrap"><img src="fotos/<?php echo $row_rsMil['mil_foto']; ?>" alt="" width="120" height="160" /></td>
          </tr>
          <tr valign="baseline">
            <td colspan="3" align="center" nowrap="nowrap"><?php echo $row_rsMil['mil_pgrad']; ?> <?php echo $row_rsMil['mil_nomeguerra']; ?></td>
          </tr>
          <tr valign="baseline">
            <td colspan="3" align="center" nowrap="nowrap">CPF:
<input type="text" name="fo_cpf" value="<?php echo $row_rsMil['mil_cpf']; ?>" size="20" /></td>
          </tr>
          <tr valign="baseline">
            <td align="left" nowrap="nowrap">&nbsp;</td>
            <td colspan="2" align="right" nowrap="nowrap">&nbsp;</td>
          </tr>
          <tr valign="baseline">
            <td align="left" nowrap="nowrap">Data:
            <input type="text" name="fo_data" value="<?php
$dataAtual = date("d/m/Y");
echo $dataAtual;
?>" size="15" /></td>
            <td colspan="2" align="right" nowrap="nowrap">Tipo do Fato:
              <select name="fo_tipo">
                <option value="POSITIVO" <?php if (!(strcmp("POSITIVO", ""))) {echo "SELECTED";} ?>>POSITIVO</option>
                <option value="NEGATIVO" <?php if (!(strcmp("NEGATIVO", ""))) {echo "SELECTED";} ?>>NEGATIVO</option>
                <option value="NEUTRO" <?php if (!(strcmp("NEUTRO", ""))) {echo "SELECTED";} ?>>NEUTRO</option>
            </select></td>
          </tr>
          <tr valign="baseline">
            <td colspan="3" align="center" nowrap="nowrap">&nbsp;</td>
          </tr>
          <tr valign="baseline">
            <td colspan="3" align="center" nowrap="nowrap">Descrição do Fato</td>
          </tr>
          <tr valign="baseline">
            <td colspan="3" align="center" nowrap="nowrap"><textarea name="fo_fato" cols="70" rows="2"></textarea></td>
          </tr>
          <tr valign="baseline">
            <td colspan="3" align="center" nowrap="nowrap">&nbsp;</td>
          </tr>
          <tr valign="baseline">
            <td colspan="3" align="center" nowrap="nowrap">Observações</td>
          </tr>
          <tr valign="baseline">
            <td colspan="3" align="center" nowrap="nowrap"><textarea name="fo_obs" cols="70" rows="2"></textarea></td>
          </tr>
          <tr valign="baseline">
            <td colspan="3" align="center" nowrap="nowrap">&nbsp;</td>
          </tr>
          <tr valign="baseline">
            <td colspan="3" align="center" nowrap="nowrap">Responsável:
            <input type="text" name="fo_resp" value="<?php echo $row_rsLogin['usu_nome']; ?>" size="30" /></td>
          </tr>
          <tr valign="baseline">
            <td width="51" align="right" nowrap="nowrap">&nbsp;</td>
            <td width="196">&nbsp;</td>
            <td width="104">&nbsp;</td>
          </tr>
          <tr valign="baseline">
            <td colspan="3" align="center" nowrap="nowrap"><input type="submit" value="Cadastrar Fato" /></td>
          </tr>
        </table>
        <input type="hidden" name="MM_insert" value="form1" />
    </form></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="center"><table width="734" border="0" cellpadding="0" cellspacing="2" class="fonte_geral">
      <tr align="center" bgcolor="#999999">
        <td width="89" align="center">DATA</td>
        <td width="90" align="center">TIPO</td>
        <td width="241" align="center">FATO</td>
        <td width="185" align="center">OBSERVAÇÕES</td>
        <td width="117" align="center" nowrap="nowrap">&nbsp;</td>
      </tr>
      <?php do { ?>
      <tr valign="middle" bgcolor="#CCCCCC">
        <td align="center" nowrap="nowrap"><?php echo $row_rsFo['fo_data']; ?>&nbsp;</td>
        <td align="center" nowrap="nowrap">&nbsp;<?php echo $row_rsFo['fo_tipo']; ?>&nbsp;</td>
        <td align="center"><?php echo $row_rsFo['fo_fato']; ?></td>
        <td align="center"><?php echo $row_rsFo['fo_obs']; ?> &nbsp;<strong>Registrado por:</strong><?php echo $row_rsFo['fo_resp']; ?></td>
        <td align="center" nowrap="nowrap">&nbsp;<a href="fo_editar.php?fo_id=<?php echo $row_rsFo['fo_id']; ?>">EDITAR</a> | <a href="javascript:if(confirm('Deseja excluir esse registro?')) {location = 'fo_excluir.php?fo_id=<?php echo $row_rsFo['fo_id']; ?>';}">EXCLUIR</a></td>
      </tr>
      
      
      <?php } while ($row_rsFo = mysql_fetch_assoc($rsFo)); ?>
    </table></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="center" class="fonte_geral"><?php echo $row_rsLogin['usu_nome']; ?> | <a href="<?php echo $logoutAction ?>">Sair</a></td>
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

mysql_free_result($rsFo);
?>
