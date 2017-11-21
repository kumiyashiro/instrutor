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

$campo1_rsPesquisa = "3";
if (isset($_POST['txt'])) {
  $campo1_rsPesquisa = $_POST['txt'];
}
mysql_select_db($database_conn_instrutor, $conn_instrutor);
$query_rsPesquisa = sprintf("SELECT * FROM tbl_instrucao WHERE inst_titulo LIKE %s ORDER BY inst_id DESC", GetSQLValueString("%" . $campo1_rsPesquisa . "%", "text"));
$rsPesquisa = mysql_query($query_rsPesquisa, $conn_instrutor) or die(mysql_error());
$row_rsPesquisa = mysql_fetch_assoc($rsPesquisa);
$totalRows_rsPesquisa = mysql_num_rows($rsPesquisa);

mysql_select_db($database_conn_instrutor, $conn_instrutor);
$query_rsTotal = "SELECT * FROM tbl_instrucao WHERE inst_situacao = 'Aprovado'";
$rsTotal = mysql_query($query_rsTotal, $conn_instrutor) or die(mysql_error());
$row_rsTotal = mysql_fetch_assoc($rsTotal);
$totalRows_rsTotal = mysql_num_rows($rsTotal);
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
<script type="text/javascript" src="js/jquery-1.4.2.js"></script>
        <script type='text/javascript' src="js/jquery.autocomplete.js"></script>
        <link rel="stylesheet" type="text/css" href="js/jquery.autocomplete.css" />
        <script type="text/javascript">
            $().ready(function() {
                $("#inst_titulo").autocomplete("autoComplete.php", {
                    width: 500,
                    matchContains: true,
                    //mustMatch: true,
                    //minChars: 0,
                    //multiple: true,
                    //highlight: false,
                    //multipleSeparator: ",",
                    selectFirst: false
                });
            });
        </script>
</head>

<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center" class="fonte_geral">.:: 2ª COMPANHIA DE COMUNICAÇÕES LEVE ::.</td>
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
    <td align="center" bgcolor="#999999" class="fonte_geral">&nbsp;</td>
  </tr>
  <tr>
    <td align="center" bgcolor="#CCCCCC" class="fonte_geral">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" bgcolor="#CCCCCC" class="fonte_geral">&nbsp;&nbsp;::: PESQUISAR CONTEÚDO <em>(<?php echo $totalRows_rsTotal ?>&nbsp;publicações)</em></td>
  </tr>
  <tr>
    <td align="left" bgcolor="#CCCCCC"><form id="form1" name="form1" method="post" action="">
      <label>
        &nbsp;
        <input name="txt" type="text" class="TFbordaEsqPesquisa" id="inst_titulo" />
      </label>
      <label>
        <input type="submit" name="button" id="button" value="Pesquisar" />
      </label>
      <em class="fonte_geral">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $totalRows_rsPesquisa ?>&nbsp;&nbsp;resultado(s) encontrado(s)</em>
    </form></td>
  </tr>
  <tr>
    <td align="left" bgcolor="#CCCCCC">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" bgcolor="#CCCCCC"><span class="fonte_geral">&nbsp;&nbsp;&nbsp;<a href="instrucao_index.php">&nbsp;PÁGINA INICIAL</a> | <a href="instrucao_publicar.php">ADICIONAR NOVA PUBLICAÇÃO</a>| <a href="instrucao_editar.php">EDITAR PUBLICAÇÕES</a> |<a href="instrucao_editar_excluir.php">EXCLUIR PUBLICAÇÕES</a></span></td>
  </tr>
  <tr>
    <td align="left" bgcolor="#CCCCCC">&nbsp;</td>
  </tr>
  <tr>
    <td align="left">&nbsp;</td>
  </tr>
  <tr align="center" valign="middle">
    <td><table width="100%" border="0" cellspacing="2" cellpadding="0">
      <tr align="center" bgcolor="#999999">
        <td width="117">&nbsp;</td>
        <td width="117"><span class="fonte_geral">REGISTRO</span></td>
        <td width="132"><span class="fonte_geral">DATA</span></td>
        <td width="151"><span class="fonte_geral">CATEGORIA</span></td>
        <td width="1131"><span class="fonte_geral">TÍTULO</span></td>
        <td width="117" class="fonte_geral">SITUAÇÃO</td>
        </tr>
      <?php do { ?>
        <tr valign="middle" bgcolor="#CCCCCC" class="fonte_geral">
          <td align="center" nowrap="nowrap">&nbsp;<a href="instrucao_editarpublicacao.php?inst_id=<?php echo $row_rsPesquisa['inst_id']; ?>" target="_blank">EDITAR</a></td>
          
          <td align="center" nowrap="nowrap"><a href="instrucao_publicacao.php?inst_id=<?php echo $row_rsPesquisa['inst_id']; ?>" target="_blank"><?php echo $row_rsPesquisa['inst_id']; ?></a>&nbsp;</td>
          <td align="center" nowrap="nowrap"><a href="instrucao_publicacao.php?inst_id=<?php echo $row_rsPesquisa['inst_id']; ?>" target="_blank"><?php echo $row_rsPesquisa['inst_data']; ?></a></td>
          <td align="center"><a href="instrucao_publicacao.php?inst_id=<?php echo $row_rsPesquisa['inst_id']; ?>" target="_blank"><?php echo $row_rsPesquisa['inst_assunto']; ?></a></td>
          <td align="left"><a href="instrucao_publicacao.php?inst_id=<?php echo $row_rsPesquisa['inst_id']; ?>" target="_blank"><?php echo $row_rsPesquisa['inst_titulo']; ?></a></td>
          <td align="center" nowrap="nowrap"><a href="instrucao_publicacao.php?inst_id=<?php echo $row_rsPesquisa['inst_id']; ?>" target="_blank"><?php echo $row_rsPesquisa['inst_situacao']; ?></a></td>
          </tr>
        <?php } while ($row_rsPesquisa = mysql_fetch_assoc($rsPesquisa)); ?>
    </table></td>
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

mysql_free_result($rsPesquisa);

mysql_free_result($rsTotal);


?>
