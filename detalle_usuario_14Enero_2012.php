<?php
session_start();
include("../verificaRegistro2.php");
include('../conectaBD.php');
//mssql_select_db('HojaDeTiempo');
?>

<?

//Establecer la conexión a la base de datos
$conexion = conectar();

?>
<html>
<head>
<title>Entradas y Salidas</title>
<LINK REL="stylesheet" HREF="../../css/estilo.css" TYPE="text/css">
<script>
var nav4 = window.Event ? true : false;
function acceptNum(evt){   
var key = nav4 ? evt.which : evt.keyCode;   
return (key <= 13 || (key>= 48 && key <= 57));
}

function valida()
{

	var e="n",msg="";
	if(document.form1.division.value=="")
	{
		e="s";
		msg="Seleccione una division.\n ";
	}
//*
	if(document.form1.departamento.value=="")
	{
		e="s";
		msg=msg+"Seleccione un departamento.\n ";
	}
//*/
	if(document.form1.mess.value=="0")
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
function MM_callJS(jsStr) { //v2.0
  return eval(jsStr)
}
</script>
</head>
<body leftmargin="0" topmargin="0" rightmargin="0" bottommargin="0"  bgcolor="E6E6E6">
	<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><? include("bannerArriba.php") ; ?></td>
  </tr>

</table>
<!--
    <table width="100%"  border="0" cellspacing="1" cellpadding="0">
      <tr>
        <td width="15%" height="35" class="FichaAct">Detalle</td>
        <td width="15%" height="35" class="FichaInAct">
        <?	/*if( ( $ano != "" ) && ( $mes != "" ) && ( $departamento != "" ) && ( $division != "" ) ){		?>
		        <a href="rpt02.php?anio=<?= $ano ?>&mes=<?= $mes ?>&division=<?= $division ?>&departamento=<?= $departamento ?>" class="FichaInAct1" >Grafica</a>
        <?	}	else {	?>
		        <a href="#" class="FichaInAct1" >Grafica</a>
        <?	}	*/	?>
        </td>
        <td>&nbsp;</td>
      </tr>
    </table>
-->
    <table width="100%"  border="0" cellspacing="1" cellpadding="0">
      <tr>
        <td>&nbsp;</td>
      </tr>
    </table>
    <form name="form1">
        <table width="60%"  border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td bgcolor="#FFFFFF">
            
            <table width="100%"  border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td class="TituloUsuario">Informaci&oacute;n de la persona</td>
              </tr>
            </table>
            
              <table width="100%"  border="0" cellspacing="1" cellpadding="0">
                <tr>
                  <td width="30%" class="TituloTabla">Division</td>
                        <?	$sqlDv = "select nombre, id_division from HojaDeTiempo.dbo.Divisiones where estadoDiv = 'A' and id_division=".$division;
                            $qryDv = mssql_query( $sqlDv );	
							$nom_division="";	
                            while( $rwDv = mssql_fetch_array( $qryDv ) ){
                                $nom_division=$rwDv[nombre];
								}
                        ?>
                  <td class="TxtTabla"><input type="text" name="input" id="input" value="<? echo $nom_division;?>" class="CajaTexto" disabled></td>
                </tr>
                <tr>
                  <td class="TituloTabla">Departamento</td>
<?  
								$sqlDep = "select nombre, id_departamento from HojaDeTiempo.dbo.Departamentos where estadoDpto = 'A' ";
                                $sqlDep .= "AND id_division = ".$division;
                                $sqlDep .= "AND id_departamento = ".$departamento;
                                $qryDep = mssql_query( $sqlDep );
								$nom_departamento="";
                                while( $rwDp = mssql_fetch_array( $qryDep ) ){
                                    $nom_departamento=$rwDp[nombre];
								}
?>
                  <td class="TxtTabla"><input type="text" name="" id="" value="<? echo $nom_departamento;?>" class="CajaTexto" disabled></td>					
                </tr>
				 <tr>
                  <td class="TituloTabla">Unidad</td>
                  <td class="TxtTabla"><input type="text" name="uni" id="uni" value="<? echo $uni; ?>" class="CajaTexto" onKeyPress="return acceptNum(event)" disabled ></td>
				</tr>

				 <tr>
                  <td class="TituloTabla">Nombres</td>
<?  
					$sql_usu = "select * from HojaDeTiempo.dbo.Usuarios								 
							 where unidad=".$uni." --and retirado is null ";
					$cur_usu=mssql_query($sql_usu);

					while($datos_usu=mssql_fetch_array($cur_usu))
					{
						$nom_usu=$datos_usu["nombre"];
						$ape_usu=$datos_usu["apellidos"];
						$Nodocumento=$datos_usu["numDocumento"];

						$sql_tar="select NoTarjeta from  SmartAccessControl.dbo.TB_EMPLEADO where NoDocumento=".$Nodocumento;
						$cur_tar=mssql_query($sql_tar);

						if($datos_tar=mssql_fetch_array($cur_tar))
								$NoTarjeta=$datos_tar["NoTarjeta"];

					}
?>
                  <td class="TxtTabla"><input type="text" name="nom" id="nom" value="<? echo $nom_usu; ?>" class="CajaTexto" onKeyPress="return acceptNum(event)" disabled ></td>
				</tr>

				 <tr>
                  <td class="TituloTabla">Apellidos</td>
                  <td class="TxtTabla"><input type="text" name="ape" id="ape" value="<? echo $ape_usu; ?>" class="CajaTexto" onKeyPress="return acceptNum(event)" disabled ></td>
				</tr>
				 <tr>
                  <td class="TituloTabla">No Tarjeta</td>
                  <td class="TxtTabla"><input type="text" name="ntar" id="ntar" value="<? echo $NoTarjeta; ?>" class="CajaTexto" onKeyPress="return acceptNum(event)" disabled ></td>
				</tr>
                <tr>
                    <td  class="TituloTabla">Fecha</td>
                    <td  class="TituloTabla">
                        <table width="100%" class="TxtTabla" >
                            <tr class="TxtTabla">
                              <td class="TxtTabla">Mes</td>
                              <td><input type="text" name="input2" id="input2" value="<? echo $mess;?>" class="CajaTexto" disabled></td>
                            <td class="TxtTabla">A&ntilde;o</td>
                            <td><input type="text" name="input3" id="input3" value="<? echo $ano;?>" class="CajaTexto" disabled ></td>
        
                          </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                <input type="hidden" name="recarga" id="recarga" value="1" >
                  <td colspan="2" align="right" class="TituloTabla">&nbsp;</td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
    </form>

    <?

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
			<td >

			Dia</td>
            <td width="30%">Torniquete</td>
            <td width="20%">Fecha</td>
            <td width="20%">Hora</td>
            <td width="20%">Tipo</td>
          </tr>
<?php
		//consulta los dias del mes en la tabla de entradas
		$sql_dias="select distinct(day(FechaCompleta)) as dia from HojaDeTiempo.dbo.TMSalidas where id_departamento=".$departamento." and unidad=".$uni." and MONTH(FechaCompleta)=".$mess." and YEAR(FechaCompleta)=".$ano." ";
		$cur_dias=mssql_query($sql_dias);
//echo $sql_dias." --- <br> ".mssql_get_last_message()."<br>";
		while($datos_dia=mssql_fetch_array($cur_dias))
		{
?>						
		  <tr class="TxtTabla">
<?php
		//consulta las entradas
		$sql_entradas="select * from HojaDeTiempo.dbo.TMEntradas where id_departamento=".$departamento." and unidad=".$uni." and MONTH(FechaCompleta)=".$mess." and YEAR(FechaCompleta)=".$ano." and DAY(FechaCompleta)=".$datos_dia["dia"]." order by(IdRegistro)";
		$cur_entradas=mssql_query($sql_entradas);

		$cant_entradas=mssql_num_rows($cur_entradas);
//echo $sql_entradas." --- <br> ".mssql_get_last_message()."<br><br>";

		//consulta las salidas
		$sql_salidas="select * from HojaDeTiempo.dbo.TMSalidas where id_departamento=".$departamento." and unidad=".$uni." and MONTH(FechaCompleta)=".$mess." and YEAR(FechaCompleta)=".$ano." and DAY(FechaCompleta)=".$datos_dia["dia"]." order by(IdRegistro)";
		$cur_salidas=mssql_query($sql_salidas);

		$cant_salidas=mssql_num_rows($cur_salidas);
//echo $sql_salidas." --- <br> ".mssql_get_last_message()."<br><br>";
?>
				<td  class="TituloTabla2" > <? echo $datos_dia["dia"]; ?></td>
				<td colspan="4">
					<table width="100%">
<?		
			$cont_entra=1; $cont_salidas=1; //contadores,  utilizados para llevar el indice, de los registros de entradas y salidas
			$total_horas=0;
			//valida si cada cursor ha finalizado, el ciclo de la cantidad toal de registros a mostrar
			while(($cant_entradas>=$cont_entra)or($cant_salidas>=$cont_salidas))
			{

	
				//se muestra, primero la informacion de las entradas y se va alternando con la informacion de las salidas
				if($datos_entrada=mssql_fetch_array($cur_entradas))
				{
		?>
					<tr class="TxtTabla">
					<td  width="30%"> <?php echo $datos_entrada["Torniquete"]; ?></td>
					<td  width="20%"><?php echo $datos_entrada["Fecha"]; ?></td>
					<td  width="20%"><?php echo $datos_entrada["Hora"]; ?></td>
					<td  width="20%"><?php if($datos_entrada["EntradaSalida"]==0)
					{ ?>
					<img src="Entrada.gif" alt="Entrada" border="0" >
<?					 //echo "Entrada"; 
					} 
					if($datos_entrada["EntradaSalida"]==1)
					{ 
?>
					<img src="Salida.gif" alt="Entrada" border="0" >	
<?				//echo "Salida"; 
					}  ?></td>
					</tr>
		<?php
					$cont_entra++;
					$hora_entrada=$datos_entrada["Hora"];
				}
				if($datos_salidas=mssql_fetch_array($cur_salidas))
				{
		?>
					<tr class="TxtTabla">
					<td  width="30%"> <?php echo $datos_salidas["Torniquete"]; ?></td>
					<td  width="20%"><?php echo $datos_salidas["Fecha"]; ?></td>
					<td  width="20%"><?php echo $datos_salidas["Hora"]; ?></td>
					<td  width="20%"><?php if($datos_salidas["EntradaSalida"]==0)
					{ ?>
                      <img src="Entrada.gif" alt="Entrada" border="0" >
                      <?					 //echo "Entrada"; 
					} 
					if($datos_salidas["EntradaSalida"]==1)
					{ 
?>
                      <img src="Salida.gif" alt="Entrada" border="0" >
                      <?				//echo "Salida"; 
					}  ?></td>
					</tr>
		<?php
					$cont_salidas++;
					$hora_salida=$datos_salidas["Hora"];
				}
				//calcula la diferencia, entre la hora de ingreso y la hora de salida,calculando el timpo transcurrido
				$cur_horas=mssql_query("select cast (DATEDIFF (MI,'".$hora_entrada."','".$hora_salida."') as float)/60 as hora ");
				if($dato_hora=mssql_fetch_array($cur_horas))
					$total_horas+=$dato_hora["hora"];
			}
?>
                    <TR>
						<td colspan="4">
							<table width="100%">
                                <td  class="TituloTabla2">Cantidad de entradas</td>
                                <td class="TxtTabla"><? echo $cant_entradas; ?></td>
                                <td  class="TituloTabla2">Cantidad de salidas</td>
                                <td class="TxtTabla"><? echo $cant_salidas; ?></td>
                                <td  class="TituloTabla2">Horas laboradas</td>
                                <td class="TxtTabla"><? echo $total_horas; ?></td>

							</table>
						</td>
                    <TR>
				</table>
			</td>
		  </tr>
<?
		}

?>      
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
    <td align="right" valign="bottom"><input name="Submit2" type="submit" class="Boton" onClick="MM_callJS('window.close()')" value="Cerrar Reporte de Ingresos y Salidas"></td>
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
