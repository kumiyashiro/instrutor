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
  $updateSQL = sprintf("UPDATE tbl_militar SET mil_nomecompleto=%s, mil_datanasc=%s, mil_pai=%s, mil_mae=%s, mil_ctt=%s, mil_naturalidade=%s, mil_end=%s, mil_pgrad=%s, mil_nomeguerra=%s, mil_idtmil=%s, mil_cpf=%s, mil_dataincorp=%s, mil_pel=%s, mil_funcao=%s, mil_antiguidade=%s, mil_situacao=%s WHERE mil_id=%s",
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
                       GetSQLValueString($_POST['mil_antiguidade'], "text"),
                       GetSQLValueString($_POST['mil_situacao'], "text"),
                       GetSQLValueString($_POST['mil_id'], "int"));

  mysql_select_db($database_conn_instrutor, $conn_instrutor);
  $Result1 = mysql_query($updateSQL, $conn_instrutor) or die(mysql_error());

  $updateGoTo = "militar_lista.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_rsMil = "-1";
if (isset($_GET['mil_id'])) {
  $colname_rsMil = $_GET['mil_id'];
}
mysql_select_db($database_conn_instrutor, $conn_instrutor);
$query_rsMil = sprintf("SELECT * FROM tbl_militar WHERE mil_id = %s", GetSQLValueString($colname_rsMil, "int"));
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
}
a:visited {
	color: #000;
}
a:hover {
	color: #333;
}
a:active {
	color: #000;
}
-->
</style></head>

<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center" class="fonte_geral">.:: EDITAR MILITAR CADASTRADO::.</td>
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
    <td align="center"><form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
      <table align="center" class="fonte_geral">
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Nome Completo:</td>
          <td><input type="text" name="mil_nomecompleto" value="<?php echo htmlentities($row_rsMil['mil_nomecompleto'], ENT_COMPAT, 'utf-8'); ?>" size="50" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Data de Nascimento:</td>
          <td><input type="text" name="mil_datanasc" value="<?php echo htmlentities($row_rsMil['mil_datanasc'], ENT_COMPAT, 'utf-8'); ?>" size="50" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Nome do Pai:</td>
          <td><input type="text" name="mil_pai" value="<?php echo htmlentities($row_rsMil['mil_pai'], ENT_COMPAT, 'utf-8'); ?>" size="50" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Nome da mãe:</td>
          <td><input type="text" name="mil_mae" value="<?php echo htmlentities($row_rsMil['mil_mae'], ENT_COMPAT, 'utf-8'); ?>" size="50" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Telefone para Contato:</td>
          <td><input type="text" name="mil_ctt" value="<?php echo htmlentities($row_rsMil['mil_ctt'], ENT_COMPAT, 'utf-8'); ?>" size="50" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Naturalidade:</td>
          <td><input type="text" name="mil_naturalidade" value="<?php echo htmlentities($row_rsMil['mil_naturalidade'], ENT_COMPAT, 'utf-8'); ?>" size="50" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Endereço:</td>
          <td><textarea name="mil_end" cols="50" rows="2"><?php echo htmlentities($row_rsMil['mil_end'], ENT_COMPAT, 'utf-8'); ?></textarea></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Posto/Graduação:</td>
          <td><input type="text" name="mil_pgrad" value="<?php echo htmlentities($row_rsMil['mil_pgrad'], ENT_COMPAT, 'utf-8'); ?>" size="50" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Nome de Guerra:</td>
          <td><input type="text" name="mil_nomeguerra" value="<?php echo htmlentities($row_rsMil['mil_nomeguerra'], ENT_COMPAT, 'utf-8'); ?>" size="50" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Identidade Militar:</td>
          <td><input type="text" name="mil_idtmil" value="<?php echo htmlentities($row_rsMil['mil_idtmil'], ENT_COMPAT, 'utf-8'); ?>" size="50" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">CPF:</td>
          <td><input type="text" name="mil_cpf" value="<?php echo htmlentities($row_rsMil['mil_cpf'], ENT_COMPAT, 'utf-8'); ?>" size="50" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Data de Incorporação:</td>
          <td><input type="text" name="mil_dataincorp" value="<?php echo htmlentities($row_rsMil['mil_dataincorp'], ENT_COMPAT, 'utf-8'); ?>" size="50" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Pelotão:</td>
          <td><input type="text" name="mil_pel" value="<?php echo htmlentities($row_rsMil['mil_pel'], ENT_COMPAT, 'utf-8'); ?>" size="50" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Mil_funcao:</td>
          <td><input type="text" name="mil_funcao" value="<?php echo htmlentities($row_rsMil['mil_funcao'], ENT_COMPAT, 'utf-8'); ?>" size="50" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Antiguidade:</td>
          <td><input type="text" name="mil_antiguidade" value="<?php echo htmlentities($row_rsMil['mil_antiguidade'], ENT_COMPAT, 'utf-8'); ?>" size="50" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Situação:</td>
          <td><select name="mil_situacao">
            <option value="Ativado" <?php if (!(strcmp("Ativado", htmlentities($row_rsMil['mil_situacao'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Ativado</option>
            <option value="Desativado" <?php if (!(strcmp("Desativado", htmlentities($row_rsMil['mil_situacao'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Desativado</option>
          </select></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">&nbsp;</td>
          <td><input type="submit" value="Atualizar" /></td>
        </tr>
      </table>
      <input type="hidden" name="MM_update" value="form1" />
      <input type="hidden" name="mil_id" value="<?php echo $row_rsMil['mil_id']; ?>" />
    </form></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="center" class="fonte_geral"><?php echo $row_rsLogin['usu_nome']; ?> | <a href="<?php echo $logoutAction ?>">Sair</a></td>
  </tr>
  <tr>
    <td align="center" class="fonte_geral">&nbsp;</td>
  </tr>
  <tr>
    <td align="center" class="fonte_geral">DESENVOLVIDO PELO 2º SGT KLEBER UEHARA MIYASHIRO - 2017</td>
  </tr>
</table>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($rsMil);

mysql_free_result($rsLogin);
?>
