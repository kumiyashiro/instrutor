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

$colname_rsQual = "-1";
if (isset($_GET['mil_id'])) {
  $colname_rsQual = $_GET['mil_id'];
}
mysql_select_db($database_conn_instrutor, $conn_instrutor);
$query_rsQual = sprintf("SELECT * FROM tbl_militar, tbl_qualificar WHERE tbl_militar.mil_id = %s AND tbl_militar.mil_cpf = tbl_qualificar.qual_cpf", GetSQLValueString($colname_rsQual, "int"));
$rsQual = mysql_query($query_rsQual, $conn_instrutor) or die(mysql_error());
$row_rsQual = mysql_fetch_assoc($rsQual);
$totalRows_rsQual = mysql_num_rows($rsQual);

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
<title><?php echo $row_rsMil['mil_pgrad']; ?> <?php echo $row_rsMil['mil_nomeguerra']; ?> </title>
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
  <tr class="fonte_geral">
    <td align="center"><a href="index.php">2ª COMPANHIA DE COMUNICAÇÕES LEVE</a></td>
  </tr>
  <tr class="fonte_geral">
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td align="center"><table width="800" border="2" cellspacing="0" cellpadding="0">
      <tr align="center">
        <td><table width="790" border="0" cellspacing="2" cellpadding="0">
          <tr bgcolor="#00CC00" class="fonte_geral">
            <td colspan="2" align="center" valign="top">.:: Dados do Militar ::.</td>
          </tr>
          <tr>
            <td align="center" valign="top">&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td width="146" align="center" valign="top"><table width="100%" border="1" cellpadding="0" cellspacing="1" class="fonte_geral">
              <tr align="center">
                <td><img src="fotos/<?php echo $row_rsMil['mil_foto']; ?>" alt="" width="120" height="160" /></td>
              </tr>
            </table></td>
            <td width="542"><table width="600" border="0" cellpadding="0" cellspacing="1" class="fonte_geral">
              <tr>
                <td><strong> &nbsp;Posto/Grad:</strong> <?php echo $row_rsMil['mil_pgrad']; ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>Nome de Guerra: </strong><?php echo $row_rsMil['mil_nomeguerra']; ?></td>
              </tr>
              <tr>
                <td><strong>&nbsp;Data de Incorporação:</strong> <?php echo $row_rsMil['mil_dataincorp']; ?></td>
              </tr>
              <tr>
                <td width="93"><strong>&nbsp;Nome Completo:</strong> <?php echo $row_rsMil['mil_nomecompleto']; ?></td>
              </tr>
              <tr>
                <td><strong>&nbsp;DLN</strong>: <?php echo $row_rsMil['mil_datanasc']; ?> - <?php echo $row_rsMil['mil_naturalidade']; ?></td>
              </tr>
              <tr>
                <td><strong>&nbsp;CPF: </strong><?php echo $row_rsMil['mil_cpf']; ?> <strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Identidade Militar:</strong> <?php echo $row_rsMil['mil_idtmil']; ?></td>
              </tr>
              <tr>
                <td><strong>&nbsp;Filiação:</strong> <?php echo $row_rsMil['mil_pai']; ?> <strong>e</strong> <?php echo $row_rsMil['mil_mae']; ?></td>
              </tr>
              <tr>
                <td><strong>&nbsp;Endereço</strong>: <?php echo $row_rsMil['mil_end']; ?></td>
              </tr>
              <tr>
                <td><strong>&nbsp;Telefone:</strong> <?php echo $row_rsMil['mil_ctt']; ?></td>
              </tr>
              <tr>
                <td><strong>&nbsp;Pelotão:</strong> <?php echo $row_rsMil['mil_pel']; ?>&nbsp;&nbsp;&nbsp;&nbsp;<strong>Função: </strong><?php echo $row_rsMil['mil_funcao']; ?></td>
              </tr>
              </table></td>
          </tr>
          <tr align="center" class="fonte_geral">
            <td colspan="2">&nbsp;</td>
          </tr>
          <tr align="center" bgcolor="#3366FF" class="fonte_geral">
            <td colspan="2"><strong>.:: Qualificações ::.</strong></td>
          </tr>
          <tr>
            <td colspan="2"><table width="790" border="1" cellspacing="1" cellpadding="0">
              <?php do { ?>
                <tr bgcolor="#CCCCCC" class="fonte_geral">
                  <td>- <?php echo $row_rsQual['qual_qualific']; ?></td>
                  </tr>
                <?php } while ($row_rsQual = mysql_fetch_assoc($rsQual)); ?>
              </table></td>
          </tr>
          <tr>
            <td colspan="2" align="center">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="2" align="right"><a href="qualificacao_cadastrar.php?mil_id=<?php echo $row_rsMil['mil_id']; ?>" target="_blank" class="fonte_geral"><strong>Adicionar</strong></a></td>
          </tr>
          
         
          
          
          <tr align="center" bgcolor="#FFFF00" class="fonte_geral">
            <td colspan="2"><strong>.:: Histórico ::.</strong></td>
          </tr>
          <tr>
            <td colspan="2"><table width="790" border="1" cellpadding="0" cellspacing="2" class="fonte_geral">
              <tr align="center" bgcolor="#999999">
                <td width="45" align="center">DATA</td>
                <td width="53" align="center">TIPO</td>
                <td width="345" align="center">FATO</td>
                <td width="251" align="center">OBSERVAÇÕES</td>
                </tr>
              <?php do { ?>
                <tr valign="middle" bgcolor="#CCCCCC">
                  <td align="center" nowrap="nowrap"><?php echo $row_rsFo['fo_data']; ?>&nbsp;</td>
                  <td align="center" nowrap="nowrap">&nbsp;<?php $fo = $row_rsFo['fo_tipo']; 
				  if($fo == POSITIVO){
					  echo "<font color='#0000CC'><B>POSITIVO<B></font>";}
			else if ($fo == NEUTRO){
					  echo "<font color='#000000'><B>NEUTRO<B></font>";}
					  elseif ($fo == NEGATIVO){
						  echo "<font color='#FF0000'><B>NEGATIVO<B></font>";}
				  
				  ?>&nbsp;</td>
                  <td align="center"><?php echo $row_rsFo['fo_fato']; ?></td>
                  <td align="center"><?php echo $row_rsFo['fo_obs']; ?><strong>  Registrado por:</strong> <?php echo $row_rsFo['fo_resp']; ?></td>
                  </tr>
                <?php } while ($row_rsFo = mysql_fetch_assoc($rsFo)); ?>
              </table></td>
          </tr>
          <tr align="center">
            <td colspan="2"  class="fonte_geral">&nbsp;</td>
            </tr>
          <tr>
            <td colspan="2" align="right" class="fonte_geral"><a href="fo_cadastrar.php?mil_id=<?php echo $row_rsMil['mil_id']; ?>" target="_blank"><strong>Adicionar</strong></a></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="center" class="fonte_geral"><?php echo $row_rsLogin['usu_nome']; ?> | <a href="<?php echo $logoutAction ?>">Sair</a></td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($rsMil);

mysql_free_result($rsLogin);

mysql_free_result($rsQual);

mysql_free_result($rsFo);
?>
