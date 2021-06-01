<?php
session_start();
include("../verificaRegistro2.php");
include('../conectaBD.php');
mssql_select_db('SmartAccessControl');
?>

<?

//Establecer la conexión a la base de datos
$conexion = conectar();

if($recarga==2)
{

	$cant_entra=0;
	$cant_salidas=0;

	//consulta si existen registros en la tabla de entradas
	$sql_entradas="select top(1)  from HojaDeTiempo.dbo.TMEntradas where Division=".$division." and  Departamento=".$departamento." and  MONTH( Fecha)=".$mes1." and YEAR( Fecha)=".$ano;
	$cur_entra=mssql_query($sql_entradas);
	$cant_entra=mssql_num_rows($cur_entra);

	//consulta si existen registros en la tabla de salidas
	$sql_salidas="select top(1)  from HojaDeTiempo.dbo.TMSalidas where Division=".$division." and  Departamento=".$departamento." and  MONTH( Fecha)=".$mes1." and YEAR( Fecha)=".$ano;
	$cur_salidas=mssql_query($sql_salidas);
	$cant_salidas=mssql_num_rows($cur_salidas);

	//si no existen entradas en las tablas de entradas y salidas, se consulta y inserta la informacion
	if(($cant_entra==0) and($cant_salidas==0))
	{
$sql_consul_entradas="
		SELECT 
			DEP.Descripcion Dependencia
			, u.unidad
			, dv.nombre Division
			, de.nombre Departamento
			, ter.Descripcion Torniquete
			, tlob.NoTarjeta
			, usu.NoDocumento
			, usu.Nombres, usu.Apellidos
			, tlob.Fecha FechaCompleta
			, CONVERT(VARCHAR(10), tlob.Fecha, 111) Fecha
			
			,
			 CONVERT(VARCHAR(10), tlob.Fecha, 114) Horas
			, tlob.EntradaSalida
		FROM
			 TB_LOG_TERMINAL tlob
			 , TB_USUARIO_TARJETAS_ASIGNADAS tUsu
			 , TB_EMPLEADO usu
			 , TB_TERMINAL ter
			 , TB_DEPENDENCIA dep
			 , HojaDeTiempo.dbo.Usuarios u
			 , HojaDeTiempo.dbo.Departamentos de
			 , HojaDeTiempo.dbo.Divisiones dv
		WHERE
			tUsu.NoTarjeta = tlob.NoTarjeta
			AND usu.IdEmpleado = tUsu.IdUsuarioSistema
			AND ter.IdTerminal = tlob.IdTerminal
			AND dep.IdDependencia = usu.IdDependencia
			AND u.numDocumento = CAST( usu.NoDocumento AS VARCHAR(20) )
			AND u.id_departamento = de.id_departamento
			AND de.id_division = dv.id_division";
			
			if(trim($division)!="")
				$sql_consul_entradas=$sql_consul_entradas."and dv.id_division=".$division."";


			$sql_consul_entradas=$sql_consul_entradas.="and de.id_departamento=".$departamento;
			$sql_consul_entradas=$sql_consul_entradas."and tlob.EntradaSalida=0
			and tlob.IdTerminal<>16
			and tlob.IdTerminal<>17
			and tlob.IdTerminal<>19";
			$sql_consul_entradas=$sql_consul_entradas."and MONTH( tlob.Fecha) = 9 and year(tlob.Fecha)=2012 and DAY(tlob.Fecha)in(03,04)-- '2012/09/03'";
			$sql_consul_entradas=$sql_consul_entradas."ORDER BY tlob.Fecha,Horas";
	}
	else
	{
	}

}


?>
<html>
<head>
<title>Entradas y Salidas</title>
<LINK REL="stylesheet" HREF="../css/estilo.css" TYPE="text/css">
<script>
function valida()
{

	var e="n",msg="";
	if(document.form1.division.value=="")
	{
		e="s";
		msg="Seleccione una division.\n ";
	}
/*
	if(document.form1.departamento.value=="")
	{
		e="s";
		msg=msg+"Seleccione un departamento.\n ";
	}
*/
	if(document.form1.mes1.value=="0")
	{
		e="s";

		msg=msg+"Seleccione un mes.\n ";
	}
	if(document.form1.ano.value=="")
	{
		e="s";
		msg=msg+"Seleccione un año.\n ";
	}
	if(e=="s")
	{
		alert(msg);
	}
	else
	{
		document.form1.recarga.value="2";
		document.form1.submit();
	}
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
					$qryDv = mssql_query( $sqlDv );		
                  	while( $rwDv = mssql_fetch_array( $qryDv ) ){
						$sel="";
						if($division==$rwDv[id_division])
							$sel="selected";
				?>
		                <option value="<?= $rwDv[id_division] ?>" <?php echo $sel ?> ><?= $rwDv[nombre] ?></option>
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
					if(trim($division) != "" )	
					{
						$sqlDep = "select nombre, id_departamento from HojaDeTiempo.dbo.Departamentos where estadoDpto = 'A' ";
						$sqlDep .= "AND id_division = ".$division;
						$qryDep = mssql_query( $sqlDep );
						while( $rwDp = mssql_fetch_array( $qryDep ) ){
							$sel="";
							if($departamento==$rwDp[id_departamento])
								$sel="selected";
					  ?>
					  <option value="<?= $rwDp[id_departamento] ?>"><?= $rwDp[nombre] ?></option>
					  <?
						}
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
    	              <td><select name="mes1" class="CajaTexto" id="mes1">
   	                    <?
					$mes = array( ':::Seleccione Mes:::', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre' );
					$m = 0;
					while( $m < count( $mes ) ){

							$sel="";
							if($mes1==$m)
								$sel="selected";
				  ?>
   	                    <option value="<?= $m ?>" <?php echo $sel; ?>>
   	                      <?= $mes[$m] ?>
                        </option>
   	                    <?	$m++;
				  }	?>
  	                  </select></td>
					<td class="TxtTabla">  A&ntilde;o</td>
    	              <td><select name="ano" id="ano" class="CajaTexto">
   	                    <option value="">:::Seleccion A&ntilde;o:::</option>
   	                    <?
					$a = 2006;
					while( $a <= date( 'Y' ) ){

							$sel="";
							if($ano==$a)
								$sel="selected";
				?>
   	                    <option value="<?= $a ?>"  <?php echo $sel; ?>>
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
			<input type="hidden" name="recarga" id="recarga" value="1" >
              <td colspan="2" align="right" class="TituloTabla"><input type="button" value="Consultar" class="Boton" onClick="valida()" ></td>
            </tr>
          </table>
          </form>
        </td>
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
					ORDER BY tlob.Fecha";
		#echo $sqlSmart;
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
            <td>Unidad</td>
            <td>Nombre Apellido</td>
            <td>No tarjeta </td>
            <td>Departamento</td>
            <td>Fecha</td>
            <td>Cant. Horas laboradas</td>
            <td>&nbsp;</td>
          </tr>
          <?	//while( $rw = mssql_fetch_array( $qrySmart ) )
				{	?>
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
              <td align="right" class="TxtTabla">

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
