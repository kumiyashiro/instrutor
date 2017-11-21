<?php require_once('Connections/conn_instrutor.php'); ?>
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
  $updateSQL = sprintf("UPDATE tbl_instrucao SET inst_data=%s, inst_assunto=%s, inst_titulo=%s, inst_texto=%s, inst_situacao=%s, inst_resp=%s WHERE inst_id=%s",
                       GetSQLValueString($_POST['inst_data'], "text"),
                       GetSQLValueString($_POST['inst_assunto'], "text"),
                       GetSQLValueString($_POST['inst_titulo'], "text"),
                       GetSQLValueString($_POST['inst_texto'], "text"),
                       GetSQLValueString($_POST['inst_situacao'], "text"),
                       GetSQLValueString($_POST['inst_resp'], "text"),
                       GetSQLValueString($_POST['inst_id'], "int"));

  mysql_select_db($database_conn_instrutor, $conn_instrutor);
  $Result1 = mysql_query($updateSQL, $conn_instrutor) or die(mysql_error());

  $updateGoTo = "instrucao_editar.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

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
</style>

<script src="ckeditor/ckeditor.js"></script>
</head>

<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center" class="fonte_geral">.:: EDITAR PUBLICAÇÃO ::.</td>
  </tr>
  <tr>
    <td align="center" class="fonte_geral">&nbsp;</td>
  </tr>
  <tr>
    <td align="center" class="fonte_geral"><a href="instrucao_editar.php">VOLTAR</a></td>
  </tr>
  <tr>
    <td align="center" class="fonte_geral">&nbsp;</td>
  </tr>
  <tr>
    <td align="center" class="fonte_geral">&nbsp;
      <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
        <table align="center">
          <tr valign="baseline">
            <td colspan="2" align="left" nowrap="nowrap">Data da Publicação:
              <input type="text" name="inst_data" value="<?php echo htmlentities($row_rsInst['inst_data'], ENT_COMPAT, 'utf-8'); ?>" size="50" /></td>
          </tr>
          <tr valign="baseline">
            <td colspan="2" align="left" nowrap="nowrap">Categoria:
              <input type="text" name="inst_assunto" value="<?php echo htmlentities($row_rsInst['inst_assunto'], ENT_COMPAT, 'utf-8'); ?>" size="50" /></td>
          </tr>
          <tr valign="baseline">
            <td colspan="2" align="left" nowrap="nowrap">Título:
              <input type="text" name="inst_titulo" value="<?php echo htmlentities($row_rsInst['inst_titulo'], ENT_COMPAT, 'utf-8'); ?>" size="100" /></td>
          </tr>
          <tr valign="baseline">
            <td colspan="2" align="left" nowrap="nowrap"><textarea name="inst_texto" cols="32"><?php echo htmlentities($row_rsInst['inst_texto'], ENT_COMPAT, 'utf-8'); ?></textarea></td>
          </tr>
          <tr valign="baseline">
            <td align="left" nowrap="nowrap">Situação:
              <select name="inst_situacao">
                <option value="Aprovado" <?php if (!(strcmp("Aprovado", htmlentities($row_rsInst['inst_situacao'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Aprovado</option>
                <option value="Desaprovado" <?php if (!(strcmp("Desaprovado", htmlentities($row_rsInst['inst_situacao'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Desaprovado</option>
            </select></td>
            <td align="left" nowrap="nowrap">Responsável:
            <input type="text" name="inst_resp" value="<?php echo htmlentities($row_rsInst['inst_resp'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
          </tr>
          <tr valign="baseline">
            <td colspan="2" align="left" nowrap="nowrap">&nbsp;</td>
          </tr>
          <tr valign="baseline">
            <td nowrap="nowrap" align="left">&nbsp;</td>
            <td align="left"><input type="submit" value="Atualizar" /></td>
          </tr>
        </table>
        <input type="hidden" name="MM_update" value="form1" />
        <input type="hidden" name="inst_id" value="<?php echo $row_rsInst['inst_id']; ?>" />
      </form>
<script>
      CKEDITOR.replace('inst_texto');
//]]></script>
    <p>&nbsp;</p></td>
  </tr>
  <tr>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
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
mysql_free_result($rsInst);
?>
