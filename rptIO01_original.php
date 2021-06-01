<?php
session_start();
include("../verificaRegistro2.php");
include('../conectaBD.php');
mssql_select_db('SmartAccessControl');
?>

<?

//Establecer la conexión a la base de datos
$conexion = conectar();

//Seleccionar los registros de las normas técnicas
$sql="select * from NormasLibros  ";
if (trim($ficha)=="") {
	$sql=$sql." WHERE codigoModulo = '3' " ;
}
else {
	$sql=$sql." WHERE codigoModulo = '".trim($ficha)."' " ;
}
$cursor = mssql_query($sql);

//Para activar la ficha que corresponda
for ($c=3; $c<27; $c++) {
	$miClase="laClase" . $c;
	if (trim($ficha) == $c) {
		${$miClase} = "FichaAct";
	}
	else {
		${$miClase} = "TituloTabla2";
	}
}
if (trim($ficha) == "") {
	$laClase3="FichaAct";
}


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
<title>Inventario de equipos</title>
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
        <td width="15%" height="35" class="FichaAct">detalle</td>
        <td width="15%" height="35" class="FichaInAct"><a href="" class="FichaInAct1" >Grafica</a></td>
<!--
		<td width="15%" height="35" class="FichaInAct"><a href="gesTraspasos.php" class="FichaInAct1" >Ficha inactiva 3 </a></td>
        <td width="15%" class="FichaInAct"><a href="qryReportes.php" class="FichaInAct1" >Consolidados</a></td>
-->
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
              <td class="TxtTabla">
              <select name="division" class="CajaTexto" id="division" onChange="document.form1.submit();">
                <option value="">:::Selecciones Division:::</option>
                <?
				  	$sqlDv = "select nombre, id_division from HojaDeTiempo.dbo.Divisiones where estadoDiv = 'A'";
					$qryDv = mssql_query( $sqlDv );	#	
                  	while( $rwDv = mssql_fetch_array( $qryDv ) ){
				?>
		                <option value="<?= $rwDv[id_division] ?>"><?= $rwDv[nombre] ?></option>
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
				  ?>
                  <option value="<?= $rwDp[id_departamento] ?>"><?= $rwDp[nombre] ?></option>
                  <?
					}
				  ?>
              </select></td>
            </tr>
            <tr>
              <td  class="TituloTabla">Fecha</td>
              <td  class="TituloTabla">
				<table width="100%" class="TxtTabla" >
        	        <tr class="TxtTabla">
    	              <td class="TxtTabla">Mes</td>
    	              <td><select name="lstEstado" class="CajaTexto" id="lstEstado">
   	                    <?
					$mes = array( ':::Seleccione Mes:::', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre' );
					$m = 0;
					while( $m < count( $mes ) ){
				  ?>
   	                    <option value="<?= $m ?>">
   	                      <?= $mes[$m] ?>
                        </option>
   	                    <?	$m++;
				  }	?>
  	                  </select></td>
					<td class="TxtTabla">  A&ntilde;o</td>
    	              <td><select name="anio" class="CajaTexto">
   	                    <option value="">:::Seleccion A&ntilde;o:::</option>
   	                    <?
					$a = 2006;
					while( $a <= date( 'Y' ) ){
				?>
   	                    <option value="<?= $a ?>">
   	                      <?= $a ?>
                        </option>
   	                    <? 	
						$a++;
					}
					#option value="2">Vencida</option
				?>
  	                  </select></td>

                  </tr>
              	</table>
			</td>
            </tr>
            <tr>
              <td colspan="2" align="right" class="TituloTabla"><input type="submit" value="Consultar" class="CajaTexto" /></td>
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
					ORDER BY tlob.Fecha";
		#echo $sqlSmart;
		$qrySmart = mssql_query( $sqlSmart );
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
            <td>Unidad</td>
            <td>Nombre Apellido</td>
            <td>No tarjeta </td>
            <td>Departamento</td>
            <td>Fecha</td>
            <td>Cant. Horas laboradas</td>
            <td>&nbsp;</td>
          </tr>
          <?	while( $rw = mssql_fetch_array( $qrySmart ) ){	?>
          <tr class="TxtTabla">
            <td><?= $rw[Apellidos]." ".$rw[Nombres]	?></td>
            <td><?= $rw[unidad]	?></td>
            <td><?= $rw[Departamento]	?></td>
            <td><?= $rw[Departamento]	?></td>
            <td><?= $rw[Fecha]	?></td>
            <td><?= $rw[Hora]	?></td>
            <td>&nbsp;</td>
          </tr>
          <?	}	?>
          <tr class="TxtTabla">
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr class="TxtTabla">
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr class="TxtTabla">
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr class="TxtTabla">
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr class="TxtTabla">
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
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
