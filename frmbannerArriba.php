<html>
<head>
<title>::: INGETEC S.A. :::</title>
<link rel="shortcut icon" href="imagenes/icoIngetec.ico">

<LINK REL="stylesheet" HREF="css/estilo.css" TYPE="text/css">
<script type="text/javascript" src="swfPortal.js"></script>

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

function MM_goToURL() { //v3.0
  var i, args=MM_goToURL.arguments; document.MM_returnValue = false;
  for (i=0; i<(args.length-1); i+=2) eval(args[i]+".location='"+args[i+1]+"'");
}
//-->
</script>
</head>
<style type="text/css">
<!--
#Layer1 {
	position:absolute;
	width:604px;
	height:42px;
	z-index:4;
	left: 4px;
	top: 60px;
	
}
-->
</style>

<body onLoad="MM_openBrWindow('vtnInforme.php','verMsgInf','width=500,height=520')" >

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="left" valign="top" background="imagenes/pixBan2.gif">&nbsp;<img src="imagenes/imgLogo.gif" width="137" height="50">&nbsp;
    <div id="flashcontent1" style="position:absolute; width:200px; height:50px; z-index:3; left: 370px; top: 1px;">
			<a href="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0">Descargar Adobe Flash Player</a>		
			<script type="text/javascript"> 
				var fo = new FlashObject("NoticiaIngetec.swf", "animationName", "270", "50", "8", "none"); 
				fo.addParam("quality", "high"); 
				fo.addParam("wmode", "transparent"); 
				fo.write("flashcontent1"); 
		  </script>
	</div>
	</td>
    <td align="right" background="imagenes/pixBan2.gif"><img src="imagenes/BannerImg01.gif" width="355" height="72" border="0" usemap="#Map"></td>
  </tr>
</table>


<div id="flashcontent2" style="position:absolute; width:852px; height:42px; z-index:4; left: 4px; top: 60px;">
<a href="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0">Descargar Adobe Flash Player</a>		
	<script type="text/javascript"> 
			var fo = new FlashObject("flash/MenuIngetecGerencia.swf", "animationName", "850", "40", "8", "none"); 
			fo.addParam("quality", "high"); 
			fo.addParam("wmode", "transparent"); 
			fo.write("flashcontent2"); 
	</script>
</div>

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td class="Fecha">
		<? 
		$zDia = date("d");
		$zMes = date("m");
		$zAno = date("Y");
		$lMes = "";
		switch ($zMes) {
			case 1:
				$lMes = "Enero";
				break;
			case 2:
				$lMes = "Febrero";
				break;
			case 3:
				$lMes = "Marzo";
				break;
			case 4:
				$lMes = "Abril";
				break;
			case 5:
				$lMes = "Mayo";
				break;
			case 6:
				$lMes = "Junio";
				break;
			case 7:
				$lMes = "Julio";
				break;
			case 8:
				$lMes = "Agosto";
				break;
			case 9:
				$lMes = "Septiembre";
				break;
			case 10:
				$lMes = "Octubre";
				break;
			case 11:
				$lMes = "Noviembre";
				break;
			case 12:
				$lMes = "Diciembre";
				break;
		}
		
		echo $zDia . " de " . $lMes . " de " . $zAno;
		
		?>
		</td>
      </tr>
      <tr>
        <td align="right">
				<table width="100%"  border="0" cellpadding="0" cellspacing="0">
                  <tr>
                    <td valign="middle"><a href="#"><img src="imagenes/icoCalidad.gif" alt="Formatos de calidad" width="105" height="25" border="0" onClick="MM_openBrWindow('mnuCalidad.php','winMenuCalidad','scrollbars=no,resizable=no,width=960,height=300')"></a> </td>
                    <td width="20%" align="center" valign="middle"><a href="#"><img src="imagenes/iconoExt.gif" width="24" height="29" border="0" onClick="MM_openBrWindow('extensiones/asociaExt.php','winAdmin2','scrollbars=yes,resizable=yes,width=960,height=500')"></a>
					</td>
                    <td width="10%" align="center" valign="middle">
					<? 
				  //PBM
				  //04Abr2011
				  //Aparece para perfil Adminisrador del sistema = 1, Sistemas = 14, Contratos = 16
				  //Aparece para Directores, Coordinadores y Ordenadores de gasto

					//Verifica que la unidad activa sea Director, coordinador y Ordenador de gasto.
					$hayVerPD = 0 ;
					$sqlVerPD="Select id_proyecto from HojaDeTiempo.dbo.Proyectos ";
					$sqlVerPD=$sqlVerPD." where id_estado = 2 ";
					$sqlVerPD=$sqlVerPD." and (id_director = " . $_SESSION["sesUnidadUsuario"] . " or id_coordinador = ".$_SESSION["sesUnidadUsuario"]." ) ";
					$sqlVerPD=$sqlVerPD." Union ";
					$sqlVerPD=$sqlVerPD." Select P.id_proyecto ";
					$sqlVerPD=$sqlVerPD." from GestiondeInformacionDigital.dbo.OrdenadorGasto O, HojaDeTiempo.dbo.Proyectos P ";
					$sqlVerPD=$sqlVerPD." where O.id_proyecto = P.id_proyecto and O.unidadOrdenador = " . $_SESSION["sesUnidadUsuario"];
					$cursorVerPD = mssql_query($sqlVerPD);
					$hayVerPD = mssql_num_rows($cursorVerPD);
					
				  //if (($_SESSION["sesPerfilUsuario"] == "1") OR ($_SESSION["sesPerfilUsuario"] == "14") OR ($_SESSION["sesUnidadUsuario"] == 17048) OR ($_SESSION["sesUnidadUsuario"] == 14888))  { 
				  if (($_SESSION["sesPerfilUsuario"] == "1") OR ($_SESSION["sesPerfilUsuario"] == "16") OR ($hayVerPD > 0) )  { 

				  ?>

					 &nbsp;<a href="#"><img src="images/imgPrint.gif" alt="Reportes de Impresi&oacute;n" width="20" height="19" border="0" onClick="MM_openBrWindow('ReportesPD/rptTotalxProyYcargo.php','wRepPD','scrollbars=yes,resizable=yes,width=600,height=500')"></a>
					 <? } ?>
					</td>
                    <td width="10%" align="center" valign="middle">
					<? 
				  //El acceso al módulo Sistema de Administración se restringe únicamente al perfil Adminisrador del sistema = 1
				  if ( ($_SESSION["sesPerfilUsuario"] == "1") OR ($_SESSION["sesPerfilUsuario"] == "14") OR ($_SESSION["sesUnidadUsuario"] == 15712) ) { ?>
					<a href="#"><img src="imagenes/icoPC.gif" alt="Inventario de equipos" width="30" height="23" border="0" onClick="MM_openBrWindow('modEquipos/invEquipos.php','winLibrosSGC','scrollbars=yes,resizable=yes,width=1100,height=500')" ></a>
					<? } ?>
					</td>
                    <td width="30%" align="center" valign="middle">
					<? 
					if (($_SESSION["sesPerfilUsuario"] == "1") OR ($_SESSION["sesPerfilUsuario"] == "14") OR ($_SESSION["sesUnidadUsuario"] == 17048))  { ?>
					<a href="#"><img src="imagenes/imgActas.jpg" alt="VerActas" width="25" height="31" border="0" onClick="MM_openBrWindow('actas/actasPortal.php','winActas','scrollbars=yes,resizable=yes,width=800,height=500')"></a>
					<? } ?>
					<? 
				  //Para que por ahora sólo aparezca para el perfil Adminisrador del sistema = 1 y para Sistemas = 14 Y lAURA gAMBOA y Almacén
				  if (($_SESSION["sesPerfilUsuario"] == "1") OR ($_SESSION["sesPerfilUsuario"] == "14") OR ($_SESSION["sesPerfilUsuario"] == "4") OR ($_SESSION["sesUnidadUsuario"] == 17048))  { ?>
					<a href="#"><img src="images/imgPrestamos.gif" alt="Pr&eacute;stamos de sistemas" width="30" height="30" border="0" onClick="MM_openBrWindow('modPrestamos/AdmPrestamoElem.php','adminElm','scrollbars=yes,resizable=yes,width=800,height=500')"></a>
					<? } ?>
					
<? 
				  //Para que por ahora sólo aparezca para el perfil Adminisrador del sistema = 1 y  lAURA gAMBOA
				  //Maria antonieta 15695, rebeca 800295 ,  Paola rubio 17075,  Andres Marulanda 14234, Camilo Marulanda 14384, Andrés amaya 16464
				  if (($_SESSION["sesPerfilUsuario"] == "1") OR ($_SESSION["sesUnidadUsuario"] == 17048) OR ($_SESSION["sesUnidadUsuario"] == 15695) OR ($_SESSION["sesUnidadUsuario"] == 800295) OR ($_SESSION["sesUnidadUsuario"] == 17075) OR ($_SESSION["sesUnidadUsuario"] == 14234) OR ($_SESSION["sesUnidadUsuario"] == 14384) OR ($_SESSION["sesUnidadUsuario"] == 16464) OR ($_SESSION["sesUnidadUsuario"] == 12909) OR ($_SESSION["sesUnidadUsuario"] == 10684) OR ($_SESSION["sesUnidadUsuario"] == 8028) OR ($_SESSION["sesUnidadUsuario"] == 5008) OR ($_SESSION["sesUnidadUsuario"] == 15591) )  { ?>
					<a href="#"><img src="imagenes/iconoEvalua.gif" alt="Evaluaci&oacute;n de Desempe&ntilde;o" width="32" height="40" border="0" onClick="MM_openBrWindow('modRHEvalDes/sisDesempeno2.php','sisDesem','scrollbars=yes,resizable=yes,width=700,height=500')"></a>
				<? } ?>  
				</td>
                    <td width="12%" valign="middle">
					<? 
				  //Para que por ahora sólo aparezca para el perfil Adminisrador del sistema = 1
				  //if ($_SESSION["sesPerfilUsuario"] == "1")   { ?>
					<a href="#"><img src="imagenes/icoNormasAdm.gif" alt="Normas Administrativas" width="95" height="28" border="0" onClick="MM_openBrWindow('modGerAdm/mnuGerAdm.php','winFaq','scrollbars=yes,resizable=yes,width=800,height=500')"></a>
					<? // } ?>
					</td>
                    <td width="12%" valign="middle"><? 
				  //El acceso al m&oacute;dulo Sistema de Administraci&oacute;n se restringe &uacute;nicamente al perfil Adminisrador del sistema = 1

				  if ( ($_SESSION["sesPerfilUsuario"] == "1") OR ($_SESSION["sesUnidadUsuario"] == 15712) OR ($_SESSION["sesUnidadUsuario"] == 14384) OR ($_SESSION["sesUnidadUsuario"] == 14234) OR ($_SESSION["sesUnidadUsuario"] == 15695)OR ($_SESSION["sesUnidadUsuario"] == 18140)OR ($_SESSION["sesUnidadUsuario"] == 16362)OR ($_SESSION["sesUnidadUsuario"] == 17640)OR ($_SESSION["sesUnidadUsuario"] == 13595)OR ($_SESSION["sesUnidadUsuario"] == 16066)  )  { ?>
                      <a href="#"><img src="imagenes/icoTorniquetes.gif" alt="Registro de Ingresos y salidas" width="25" height="27" border="0" onClick="MM_openBrWindow('modRegistroIO/rpt01.php','winAccesos','scrollbars=yes,resizable=yes,width=1100,height=500')" ></a>
                      <? } ?></td>
                    <td width="12%" valign="middle">&nbsp;<a href="#"><img src="imagenes/iconoFAQ.gif" alt="Preguntas frecuentes" width="99" height="24" border="0" onClick="MM_openBrWindow('faq/verFaq.php','winFaq','scrollbars=yes,resizable=yes,width=800,height=500')"></a>&nbsp;</td>
                    <td width="12%" valign="middle"><a href="#"><img src="imagenes/icoHelpDesk.gif" alt="Solicitudes de Help Desk" width="117" height="26" border="0" onClick="MM_openBrWindow('Solicitudes/solHelpDesk.php','winSolHelpD','scrollbars=yes,resizable=yes,width=700,height=400')"></a></td>
                    <td width="12%" valign="middle">
					<? 
				  //Para que por ahora sólo aparezca para el perfil Adminisrador del sistema = 1, Laboratorio y CME y DRM
				  //25Ene2012
				  //PBM
				  //Desactivar la entrada para que todo el mundo pueda acceder.
				  //if (($_SESSION["sesUnidadUsuario"] == 15712) OR ($_SESSION["sesPerfilUsuario"] == "24") OR ($_SESSION["sesPerfilUsuario"] == "26") OR ($_SESSION["sesPerfilUsuario"] == "27") OR ($_SESSION["sesUnidadUsuario"] == 16374) OR ($_SESSION["sesUnidadUsuario"] == 14384) )  { ?>
					<a href="#"><img src="imagenes/icoInv.gif" alt="Laboratorio" width="112" height="26" border="0" onClick="MM_openBrWindow('modLaboratorio/sisLabProyectos.php','winLaboratorio','scrollbars=yes,resizable=yes,width=850,height=400')"></a>
					<? //} ?>
					</td>
                    <td width="12%" align="center" valign="middle">
					<?php
					
					session_start();
					include("verificaRegistro2.php");
					include('conectaBD.php');

					//Establecer la conexión a la base de datos
					$conexion = conectar();

					//Verifica si el usuario activo tiene autorización sobre las ideas
					$sql="select count(*) siEstoy ";
					$sql=$sql." From UsuariosAutorizadosIdeas ";
					$sql=$sql." where unidad = ". $_SESSION["sesUnidadUsuario"] ;
					$cursor = mssql_query($sql);
					if ($reg=mssql_fetch_array($cursor)) {
						$cualBoton = $reg[siEstoy] ;
					}
					mssql_close ($conexion);

					?>
					<? if ($cualBoton == "0") { ?>
					<a href="#"><img src="imagenes/iconoIdeas.gif" alt="Aporte de ideas a Ingetec" width="99" height="23" border="0" onClick="MM_openBrWindow('ideas/addIdea.php','verIdeas','scrollbars=yes,resizable=yes,width=500,height=400')"></a>
					<? } ?>
					<? if ($cualBoton == "1") { ?>
					<a href="#"><img src="imagenes/iconoIdeas.gif" alt="Aporte de ideas a Ingetec" width="99" height="23" border="0" onClick="MM_openBrWindow('ideas/verIdeas.php','verIdeas','scrollbars=yes,resizable=yes,width=750,height=400')"></a>
					<? } ?>
					</td>
                    <td width="12%" align="center" valign="middle"><?php
					
					session_start();
					include("verificaRegistro2.php");
					//include('conectaBD.php');

					//Establecer la conexión a la base de datos
					$conexion = conectar();

					//Verifica si el usuario se encuentra incluido en el manejo de la información del portal de clientes
					$verBoton1 = 0 ;
					$sql="select count(*) hayReg1 ";
					$sql=$sql." from PortalGID.dbo.asignaProyectosExt ";
					$sql=$sql." where (unidadDirector = ".$_SESSION["sesUnidadUsuario"]." or unidadEncargado = ".$_SESSION["sesUnidadUsuario"].") "  ;
					$cursor = mssql_query($sql);
					if ($reg=mssql_fetch_array($cursor)) {
						$verBoton1 = $reg[hayReg1] ;
					}

					$verBoton2 = 0 ;
					$sql="select count(*) hayReg2 ";
					$sql=$sql." from PortalGID.dbo.clientesProyectos ";
					$sql=$sql." where unidad = ". $_SESSION["sesUnidadUsuario"] ;
					$cursor = mssql_query($sql);
					if ($reg=mssql_fetch_array($cursor)) {
						$verBoton2 = $reg[hayReg2] ;
					}

					mssql_close ($conexion);

					?>
                      <? if (($verBoton1 != "0") OR ($verBoton2 != "0")) { ?>
                      <a href="#"><img src="imagenes/icoGID.gif" width="104" height="23" border="0" onClick="MM_openBrWindow('http://www.ingetec.com.co/GIDPortal/filtroProyectos.php','winArchivosGID','scrollbars=yes,resizable=yes,width=960,height=550')"></a>
                      <? } ?></td>
                    <td width="12%" align="center" valign="middle"><a href="#"><img src="imagenes/iconoSissoma.gif" alt="Enlace a nueva ventana con informaci&oacute;n SISSOMA" width="138" height="37" border="0" onClick="MM_openBrWindow('sissoma/verBoletin.php','winSissoma','scrollbars=yes,resizable=yes,width=900,height=500')"></a> </td>
                    <td width="5%" align="center" valign="middle">
					<? 
					/*
					//Solo muestra el icono a PBM y GRM y María Antonnieta y Camilo Marulanda 
					if (($_SESSION["sesUnidadUsuario"] == 15712) OR ($_SESSION["sesUnidadUsuario"] == 12974) OR ($_SESSION["sesUnidadUsuario"] == 15695) OR ($_SESSION["sesUnidadUsuario"] == 14384) 
					OR ($_SESSION["sesUnidadUsuario"] == 14319) OR ($_SESSION["sesUnidadUsuario"] == 14418) OR ($_SESSION["sesUnidadUsuario"] == 13925) OR ($_SESSION["sesUnidadUsuario"] == 15837) OR ($_SESSION["sesUnidadUsuario"] == 16008)
					OR ($_SESSION["sesUnidadUsuario"] == 12199) OR ($_SESSION["sesUnidadUsuario"] == 15695) OR ($_SESSION["sesUnidadUsuario"] == 12113) OR ($_SESSION["sesUnidadUsuario"] == 14753) OR ($_SESSION["sesUnidadUsuario"] == 15803)					) { 
					*/
//					if (($_SESSION["sesUnidadUsuario"] == 15712) OR ($_SESSION["sesUnidadUsuario"] == 12974) OR ($_SESSION["sesUnidadUsuario"] == 15695) OR ($_SESSION["sesUnidadUsuario"] == 14384) 		) { 
//Para bloquear la publicación
					//if ($_SESSION["sesUnidadUsuario"] == 'XXX') {
					?>
					<a href="#"><img src="imagenes/iconoEvalua.gif" alt="Evaluaci&oacute;n " width="32" height="40" border="0" onClick="MM_openBrWindow('RHumanos/medEncuesta.php','winMedEncuesta','scrollbars=yes,resizable=yes,width=900,height=500')"></a>
					<? // } ?>
					</td>
                    <td width="5%" align="center" valign="middle">
					<? 
					//Solo muestra el icono a PBM
					//Rebeca verano = 15803 y María Antonieta = 15695
					//Alberto marulanda = 2964
					if (($_SESSION["sesUnidadUsuario"] == 15712) OR ($_SESSION["sesUnidadUsuario"] == 800295) OR ($_SESSION["sesUnidadUsuario"] == 15695) OR ($_SESSION["sesUnidadUsuario"] == 2964)) { ?>
					<a href="#"><img src="imagenes/icoEncuesta2.gif" alt="Encuestas" width="28" height="26" border="0" onClick="MM_openBrWindow('RHumanos/sisEncuesta.php','winSisEncuesta','scrollbars=yes,resizable=yes,width=800,height=300')"></a>
					<? } ?>					</td>
                    <td width="8%" align="center" valign="middle">
					<? 
				  //Para que por ahora sólo aparezca para el perfil Adminisrador del sistema = 1
				  //if ($_SESSION["sesPerfilUsuario"] == "1")  { ?>
					<a href="#"><img src="imagenes/icoAyuda.gif" alt="Ayuda Portal Ingetec S.A." width="40" height="37" border="0" onClick="MM_openBrWindow('Ayuda/ayudaPortal.php','winAyuda','scrollbars=yes,resizable=yes,width=700,height=400')"></a>
					<? //} ?>					</td>
                    <td align="center" valign="middle">
					<? 
				  //El acceso a la generación de archivos se restringe únicamente al perfil Adminisrador del sistema = 1
				  // y perfil dibujantes = 21
				  if (($_SESSION["sesPerfilUsuario"] == "1") OR ($_SESSION["sesPerfilUsuario"] == "21") OR ($_SESSION["sesPerfilUsuario"] == "23")) { ?>
				  
					<a href="#"><img src="imagenes/icoGenFiles.gif" alt="gENERAR NOMBRES DE ARCHIVOS" width="59" height="31" border="0" onClick="MM_openBrWindow('generaArchivos/genArchivos.php','winGenArchivos','scrollbars=yes,resizable=yes,width=600,height=400')"></a>
					<? } ?>					</td>
                    <td align="right"><? 
				  //El acceso al módulo Sistema de Administración se restringe únicamente al perfil Adminisrador del sistema = 1
				  if ($_SESSION["sesPerfilUsuario"] == "1") { ?>
		<a href="#"><img src="imagenes/icoAdmin.gif" width="154" height="38" border="0" onClick="MM_openBrWindow('sisAdmin/menuAdministracion.php','winAdmin','toolbar=yes,scrollbars=yes,resizable=yes,width=960,height=700')"></a>
		<? } 
		else { ?>
        <img src="imagenes/icoAdmin.gif" width="154" height="38">        <? }
		?></td>
                  </tr>
          </table>
		</td>

		</td>
      </tr>
</table> 
<map name="Map">
  <area shape="poly" coords="295,38,276,50,285,66,353,61,350,29,298,27" href="#" onClick="MM_goToURL('parent','frmSalida.php');return document.MM_returnValue">
</map>
</body>
</html>