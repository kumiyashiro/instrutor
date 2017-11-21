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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE tbl_fo SET fo_data=%s, fo_tipo=%s, fo_fato=%s, fo_obs=%s, fo_resp=%s, fo_cpf=%s WHERE fo_id=%s",
                       GetSQLValueString($_POST['fo_data'], "text"),
                       GetSQLValueString($_POST['fo_tipo'], "text"),
                       GetSQLValueString($_POST['fo_fato'], "text"),
                       GetSQLValueString($_POST['fo_obs'], "text"),
                       GetSQLValueString($_POST['fo_resp'], "text"),
                       GetSQLValueString($_POST['fo_cpf'], "text"),
                       GetSQLValueString($_POST['fo_id'], "int"));

  mysql_select_db($database_conn_instrutor, $conn_instrutor);
  $Result1 = mysql_query($updateSQL, $conn_instrutor) or die(mysql_error());

  $updateGoTo = "militar_lista_usuario.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_Recordset1 = "-1";
if (isset($_GET['fo_id'])) {
  $colname_Recordset1 = $_GET['fo_id'];
}
mysql_select_db($database_conn_instrutor, $conn_instrutor);
$query_Recordset1 = sprintf("SELECT * FROM tbl_fo WHERE fo_id = %s", GetSQLValueString($colname_Recordset1, "int"));
$Recordset1 = mysql_query($query_Recordset1, $conn_instrutor) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);
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
    <td align="center" class="fonte_geral">.:: EDITAR FATO OBSERVADO ::.</td>
  </tr>
  <tr>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td align="center">&nbsp;
      <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
        <table align="center" class="fonte_geral">
          <tr valign="baseline">
            <td colspan="2" align="center" nowrap="nowrap">&nbsp;&nbsp;CPF do Militar:
<input name="fo_cpf" type="text" value="<?php echo htmlentities($row_Recordset1['fo_cpf'], ENT_COMPAT, 'utf-8'); ?>" size="20" readonly="readonly" /></td>
          </tr>
          <tr valign="baseline">
            <td colspan="2" align="left" nowrap="nowrap">&nbsp;</td>
          </tr>
          <tr valign="baseline">
            <td colspan="2" align="center" nowrap="nowrap">Data:
              <input type="text" name="fo_data" value="<?php echo htmlentities($row_Recordset1['fo_data'], ENT_COMPAT, 'utf-8'); ?>" size="32" />
            &nbsp;&nbsp;Tipo do Fato:
            <select name="fo_tipo">
              <option value="POSITIVO" <?php if (!(strcmp("POSITIVO", htmlentities($row_Recordset1['fo_tipo'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>POSITIVO</option>
              <option value="NEGATIVO" <?php if (!(strcmp("NEGATIVO", htmlentities($row_Recordset1['fo_tipo'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>NEGATIVO</option>
              <option value="NEUTRO" <?php if (!(strcmp("NEUTRO", htmlentities($row_Recordset1['fo_tipo'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>NEUTRO</option>
            </select></td>
          </tr>
          <tr valign="baseline">
            <td colspan="2" align="right" nowrap="nowrap">&nbsp;</td>
          </tr>
          <tr valign="baseline">
            <td colspan="2" align="center" nowrap="nowrap">Descrição do Fato</td>
          </tr>
          <tr valign="baseline">
            <td colspan="2" align="center" nowrap="nowrap"><textarea name="fo_fato" cols="60" rows="2"><?php echo htmlentities($row_Recordset1['fo_fato'], ENT_COMPAT, 'utf-8'); ?></textarea></td>
          </tr>
          <tr valign="baseline">
            <td colspan="2" align="center" nowrap="nowrap">&nbsp;</td>
          </tr>
          <tr valign="baseline">
            <td colspan="2" align="center" nowrap="nowrap">Observações</td>
          </tr>
          <tr valign="baseline">
            <td colspan="2" align="center" nowrap="nowrap"><textarea name="fo_obs" cols="60" rows="2"><?php echo htmlentities($row_Recordset1['fo_obs'], ENT_COMPAT, 'utf-8'); ?></textarea></td>
          </tr>
          <tr valign="baseline">
            <td nowrap="nowrap" align="right">&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr valign="baseline">
            <td nowrap="nowrap" align="right">Responsável pelo Registro:</td>
            <td><input type="text" name="fo_resp" value="<?php echo htmlentities($row_Recordset1['fo_resp'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
          </tr>
          <tr valign="baseline">
            <td nowrap="nowrap" align="right">&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr valign="baseline">
            <td colspan="2" align="center" nowrap="nowrap"><input type="submit" value="Atualizar" /></td>
          </tr>
        </table>
        <input type="hidden" name="MM_update" value="form1" />
        <input type="hidden" name="fo_id" value="<?php echo $row_Recordset1['fo_id']; ?>" />
      </form>
    <p>&nbsp;</p></td>
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
mysql_free_result($Recordset1);
?>
