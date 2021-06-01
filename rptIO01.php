<?php
session_start();
include("../verificaRegistro2.php");
include('../conectaBD.php');
mssql_select_db('SmartAccessControl');
?>

<?

//Establecer la conexión a la base de datos
$conexion = conectar();


?>
<html>
<head>
<script>
var newwindow;
function muestraventana(url)
{
	newwindow=window.open(url,"name","height=400,width=650, resizable=yes, scrollbars=yes");
	if (window.focus) {newwindow.focus()}
}
function muestraventana2(url)
{
	newwindow=window.open(url,"name2","height=400,width=650, resizable=0, scrollbars=0");
	if (window.focus) {newwindow.focus()}
}
</script>
<title>Torniquetes</title>
<LINK REL="stylesheet" HREF="../css/estilo.css" TYPE="text/css">
<script language="JavaScript">
window.name="winLibrosSGC";
</script>


<script language="JavaScript" type="text/JavaScript">
<!--
function MM_swapImgRestore() { //v3.0
	var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}

function MM_preloadImages() { //v3.0
	var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
	var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
	if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_findObj(n, d) { //v4.01
	var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
		d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
		if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
		for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
		if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_swapImage() { //v3.0
	var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
	if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}

function MM_callJS(jsStr) { //v2.0
  return eval(jsStr)
}

function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
//-->
</script>
</head>
<body leftmargin="0" topmargin="0" rightmargin="0" bottommargin="0"  bgcolor="E6E6E6">
	<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><? include("bannerArriba.php") ; ?></td>
  </tr>
</table>
    <table width="100%"  border="0" cellspacing="1" cellpadding="0">
      <tr>
        <td width="15%" height="35" class="FichaAct">Ficha Activa </td>
        <td width="15%" height="35" class="FichaInAct"><a href="solElementos2CMinf.php?laFirma=1" class="FichaInAct1" >Ficha inactiva 2 </a></td>
		<td width="15%" height="35" class="FichaInAct"><a href="gesTraspasos.php" class="FichaInAct1" >Ficha inactiva 3 </a></td>
        <td width="15%" class="FichaInAct"><a href="qryReportes.php" class="FichaInAct1" >Consolidados</a></td>
        <td>&nbsp;</td>
      </tr>
    </table>
    <table width="100%"  border="0" cellspacing="1" cellpadding="0">
      <tr>
        <td>&nbsp;</td>
      </tr>
    </table>
	<table width="60%"  border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td bgcolor="#FFFFFF">
        
        <table width="100%"  border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td class="TituloUsuario">Criterios de consulta </td>
          </tr>
        </table>
        
        <form name="form1">
          <table width="100%"  border="0" cellspacing="1" cellpadding="0">
            <tr>
              <td width="30%" class="TituloTabla">Division</td>
              <td width="2" class="TxtTabla">
              <select name="division" class="CajaTexto" id="division" onChange="document.form1.submit();">
                <option value="">:::Selecciones Division:::</option>
                <?
				  	$sqlDv = "select nombre, id_division from HojaDeTiempo.dbo.Divisiones where estadoDiv = 'A'";
					$qryDv = mssql_query( $sqlDv );	#	
                  	while( $rwDv = mssql_fetch_array( $qryDv ) ){
						$sel_div="";
						if($division==$rwDv[id_division])
							$sel_div="selected";
				?>
		                <option value="<?= $rwDv[id_division] ?>" <?php echo $sel_div; ?> ><?= $rwDv[nombre] ?></option>
                <?
					}
				?>
              </select></td>
            </tr>
            <tr>
              <td class="TituloTabla">Departamento</td>
              <td class="TxtTabla">
              <select name="departamento" class="CajaTexto" id="departamento">
                  <option value="">:::Seleccione Departamento:::</option>
                  <?
				  	$sqlDep = "select nombre, id_departamento from HojaDeTiempo.dbo.Departamentos where estadoDpto = 'A' ";
					if( $division != "" )	
						$sqlDep .= "AND id_division = ".$division;
					$qryDep = mssql_query( $sqlDep );
                  	while( $rwDp = mssql_fetch_array( $qryDep ) ){
						$sel_dep="";
						if($departamento==$rwDp[id_departamento])
							$sel_dep="selected";
				  ?>
                  <option value="<?= $rwDp[id_departamento] ?>"  <?php echo $sel_dep; ?> ><?= $rwDp[nombre] ?></option>
                  <?
					}
				  ?>
              </select></td>
            </tr>
            <tr>
              <td colspan="2" class="TituloTabla">Fecha</td>
            </tr>
            <tr>
              <td class="TituloTabla">Mes</td>
              <td class="TxtTabla">
              <select name="mess" class="CajaTexto" id="mess">
                <?
					$mes = array( ':::Seleccione Mes:::', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre' );
					$m = 0;
					while( $m < count( $mes ) ){
						$sel_mes="";
						if($m==$mess)
							$sel_mes="selected";
				  ?>
                <option value="<?=$m ?>" <? echo $sel_mes; ?>>
                  <?= $mes[$m] ?>
                </option>
                <?	$m++;
				  }	?>
              </select>
<? echo $mess; ?>
              </td>
            </tr>
            <tr>
              <td class="TituloTabla">A&ntilde;o</td>
              <td class="TxtTabla">
              <select name="anio" id="anio" class="CajaTexto">
                <option value="">:::Seleccion Año:::</option>
                <?
					$a = 2006;
					while( $a <= date( 'Y' ) ){
						$sel_ano="";
						if($a==$anio)
							$sel_ano="selected";
				?>
                <option value="<?=$a ?>" <? echo $sel_ano; ?>>
                  <?= $a ?>
                </option>
                <? 	
						$a++;
					}
					#option value="2">Vencida</option
				?>
              </select></td>
            </tr>
            <tr>
              <td colspan="2" align="right" class="TituloTabla"><input type="submit" value="Buscar" class="CajaTexto" /></td>
            </tr>
          </table>
          </form>
        </td>
      </tr>
    </table>
    
	<table width="100%"  border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td>&nbsp;</td>
      </tr>
    </table>
    <?
/*
		$sqlSmart = "SELECT 
						DEP.Descripcion Dependencia
						, u.unidad
						, de.nombre Departamento
						, ter.Descripcion Torniquete
						, tlob.NoTarjeta
						, usu.NoDocumento
						, usu.Nombres, usu.Apellidos
						, tlob.Fecha FechaCompleta
						, CONVERT(VARCHAR(10), tlob.Fecha, 111) Fecha
						, CONVERT(VARCHAR(10), tlob.Fecha, 114) Hora
						, tlob.EntradaSalida
					FROM
						 SmartAccessControl.dbo.TB_LOG_TERMINAL tlob
						 , SmartAccessControl.dbo.TB_USUARIO_TARJETAS_ASIGNADAS tUsu
						 , SmartAccessControl.dbo.TB_EMPLEADO usu
						 , SmartAccessControl.dbo.TB_TERMINAL ter
						 , SmartAccessControl.dbo.TB_DEPENDENCIA dep
						 , HojaDeTiempo.dbo.Usuarios u
						 , HojaDeTiempo.dbo.Departamentos de
					
					WHERE
						 (YEAR(tlob.Fecha) = ".$anio.") AND (MONTH(tlob.Fecha) = ".$lstEstado." ) 
						AND tUsu.NoTarjeta = tlob.NoTarjeta
						AND usu.IdEmpleado = tUsu.IdUsuarioSistema
						AND ter.IdTerminal = tlob.IdTerminal
						AND dep.IdDependencia = usu.IdDependencia
						AND u.numDocumento = CAST( usu.NoDocumento AS VARCHAR(20) )
						AND u.id_departamento = de.id_departamento
						AND de.id_departamento = ".$departamento."
					ORDER BY usu.Apellidos, usu.Nombres, tlob.Fecha";
		echo $sqlSmart;
		$qrySmart = mssql_query( $sqlSmart );
*/

# -- AND usu.NoTarjeta = 2365557997
# -- AND usu.IdEstadoUsuario = 2
	?>
	<table width="100%"  border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td class="TituloUsuario">Reporte</td>
      </tr>
    </table>
	<table width="100%"  border="0" cellspacing="1" cellpadding="0">
      <tr>
        <td bgcolor="#FFFFFF"><table width="100%"  border="0" cellspacing="1" cellpadding="0">
          <tr class="TituloTabla2">
            <td width="1%">&nbsp;</td>
            <td width="1%">&nbsp;</td>
            <td>Nombre Apellido</td>
            <td>Unidad</td>
            <td>Divisio</td>
            <td>Departamento</td>
            <td>Fecha</td>
            <td>Hora</td>
            <td>Entrada/Salida</td>
            <td>&nbsp;</td>
            <td width="5%">Titulo</td>
            <td width="5%">Titulo</td>
          </tr>
          <?	while( $rw = mssql_fetch_array( $qrySmart ) ){	?>
          <tr class="TxtTabla">
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td><?= $rw[Apellidos]." ".$rw[Nombres]	?></td>
            <td><?= $rw[unidad]	?></td>
            <td><?= $rw[Departamento]	?></td>
            <td><?= $rw[Departamento]	?></td>
            <td><?= $rw[Fecha]	?></td>
            <td><?= $rw[Hora]	?></td>
            <td>
			<?
				if( $rw[EntradaSalida]	== 0 ){			
			?>
            	<img src="FlechaVerdeGananciasMasivas.png" title="Entrada" height="25px" width="25px" />
            <?
				}
				else if( $rw[EntradaSalida]	== 1 ){			
			?>
            	<img src="flecha-roja.jpg" title="Salida" height="25px" width="25px" />
            <?	}	?>
            </td>
            <td>&nbsp;</td>
            <td align="center">&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <?	}	?>
          <tr class="TxtTabla">
            <td width="1%">&nbsp;</td>
            <td width="1%">&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td width="5%" align="center"><img src="../images/icoAdd.gif" width="16" height="15" style="cursor: hand" onClick="MM_openBrWindow('addAdiciones.php','addAdd','scrollbars=yes,resizable=yes,width=900,height=300')"></td>
            <td width="5%">&nbsp;</td>
          </tr>
          <tr class="TxtTabla">
            <td width="1%">&nbsp;</td>
            <td width="1%">&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td width="5%" align="center"><img src="../images/icoAdd.gif" style="cursor: hand" width="16" height="15"></td>
            <td width="5%">&nbsp;</td>
          </tr>
          <tr class="TxtTabla">
            <td width="1%">&nbsp;</td>
            <td width="1%">&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td width="5%" align="center"><img src="../images/icoAdd.gif" style="cursor: hand" width="16" height="15"></td>
            <td width="5%">&nbsp;</td>
          </tr>
          <tr class="TxtTabla">
            <td width="1%"><img src="file:///C|/portal/images/actualizar.jpg" width="19" height="17"></td>
            <td width="1%"><img src="file:///C|/portal/images/Del.gif" width="14" height="13"></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td width="5%" align="center">&nbsp;</td>
            <td width="5%">&nbsp;</td>
          </tr>
          <tr class="TxtTabla">
            <td width="1%"><img src="file:///C|/portal/images/actualizar.jpg" width="19" height="17"></td>
            <td width="1%"><img src="file:///C|/portal/images/Del.gif" width="14" height="13"></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td width="5%" align="center">&nbsp;</td>
            <td width="5%">&nbsp;</td>
          </tr>
        </table>
          <table width="100%"  border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td align="right" class="TxtTabla"><input name="Submit3" type="submit" class="Boton" onClick="MM_openBrWindow('addRegEquipos.php','winAdEquipos','scrollbars=yes,resizable=yes,width=1000,height=400')" value="Registrar equipo">
                </td>
            </tr>
          </table></td>
      </tr>
    </table>
	

	<div style="position:absolute; left:2px; top:60px; width: 647px;">
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="TxtNota1">Reportes de Ingresos y Salidas </td>
  </tr>
</table>
</div>
    <table width="100%" border="0" cellspacing="0" cellpadding="1">
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="right" valign="bottom"><input name="Submit2" type="submit" class="Boton" onClick="MM_callJS('window.close()')" value="Cerrar Inventario de equipos"></td>
  </tr>
</table>
    <table width="100%" border="0" cellspacing="0" cellpadding="1">
      <tr>
        <td>&nbsp;</td>
      </tr>
</table>

    <p>&nbsp;</p>
</body>
</html>

<? mssql_close ($conexion); ?>	
