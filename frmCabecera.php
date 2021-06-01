<?php
	session_start();
	include("verificaRegistro.php");

	//Identifica la unidad del usuario conectandeo a la base da datos de hoja de tiempo
	$sql = "select unidad from usuarios where email='$ptUsr'";
	//if(!$ct = mssql_connect("sqlservidor","12974","1373")){
	if (!($ct=@mssql_connect("sqlservidor","12974","1373")))	{
	
		echo "<scrip>alert('Error en la conexión con el servidor');</script>";
		exit();
	}
	if(!mssql_select_db("hojadetiempo",$ct)){
		echo "<script>alert('Error en la selección de la base de datos');</script>";
		exit();
	}
	$ap = mssql_query($sql,$ct);
	$reg = mssql_fetch_array($ap);
	$laUnidaddelUsuario = $reg[unidad];

?>

<html>
<head>
<title>::: INGETEC S.A. :::</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="shortcut icon" href="imagenes/icoIngetec.ico">
<LINK REL="stylesheet" HREF="css/estilo.css" TYPE="text/css">
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
//-->
</script>
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_reloadPage(init) {  //reloads the window if Nav4 resized
  if (init==true) with (navigator) {if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {
    document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage; }}
  else if (innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();
}
MM_reloadPage(true);
//-->
</script>
<style type="text/css">
<!--
#Layer2 {
	position:absolute;
	width:370px;
	height:81px;
	z-index:2;
	left: 426px;
	top: 134px;
}
-->
</style>
</head>

<body  leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#F5F5F5">

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><? include("frmbannerArriba.php") ; ?></td>
  </tr>
</table>

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>&nbsp;</td>
  </tr>
   <tr>
    <td>&nbsp;</td>
  </tr>
   <tr>
    <td>&nbsp;</td>
  </tr>
</table>

</body>
</html>