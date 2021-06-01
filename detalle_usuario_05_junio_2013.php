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

<?php
	if((trim($departamento)=='')&&(trim($division=='')))
	{
		$sql_dep_div="
		select UPPER(HojaDeTiempo.dbo.Departamentos.nombre) as departamento, UPPER( HojaDeTiempo.dbo.Divisiones.nombre) as division from HojaDeTiempo.dbo.Usuarios 
		inner join HojaDeTiempo.dbo.Departamentos on HojaDeTiempo.dbo.Usuarios.id_departamento=HojaDeTiempo.dbo.Departamentos.id_departamento 
		inner join HojaDeTiempo.dbo.Divisiones on HojaDeTiempo.dbo.Departamentos.id_division=HojaDeTiempo.dbo.Divisiones.id_division
		where unidad=".$uni;
		$cur_div_dep=mssql_query($sql_dep_div);
		if($datos_div_dep=mssql_fetch_array($cur_div_dep))
		{

			$nom_division=$datos_div_dep["division"];
			$nom_departamento=$datos_div_dep["departamento"];
		}
//echo $nom_division." **// ".$nom_departamento."<br>".mssql_get_last_message()." <br>".$sql_dep_div;
	}
?>
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
//							$nom_division="";	
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
//								$nom_departamento="";
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
<?
  $mes = array( '', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre' );
?>
                              <td><input type="text" name="input2" id="input2" value="<? echo $mes[$mess];?>" class="CajaTexto" disabled></td>
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
            <td width="10%">Diferencia</td>
            <td width="10%">Tipo</td>
          </tr>
<?php
		//consulta los dias del mes en la tabla de entradas
		$sql_dias="select distinct(day(FechaCompleta)) as dia from HojaDeTiempo.dbo.TMSalidas where   unidad=".$uni." and MONTH(FechaCompleta)=".$mess." and YEAR(FechaCompleta)=".$ano." ";
		if(trim($id_departamento)!="")
			$sql_dias=$sql_dias." and id_departamento=".$departamento." ";

		$cur_dias=mssql_query($sql_dias);
//echo $sql_dias." --- <br> ".mssql_get_last_message()."<br>";
		while($datos_dia=mssql_fetch_array($cur_dias))
		{
?>						

<?php
			//consulta las entradas
			$sql_entradas="select * from HojaDeTiempo.dbo.TMEntradas where unidad=".$uni." and MONTH(FechaCompleta)=".$mess." and YEAR(FechaCompleta)=".$ano." and DAY(FechaCompleta)=".$datos_dia["dia"]." ";

		if(trim($id_departamento)!="")
				$sql_entradas=$sql_entradas." and id_departamento=".$departamento." ";
		$sql_entradas=$sql_entradas." order by(IdRegistro) ";

			$cur_entradas=mssql_query($sql_entradas);
	
			$cant_entradas=mssql_num_rows($cur_entradas);
//	echo $sql_entradas." --- <br> ".mssql_get_last_message()."<br><br>";
	
			//consulta las salidas
			$sql_salidas="select * from HojaDeTiempo.dbo.TMSalidas where unidad=".$uni." and MONTH(FechaCompleta)=".$mess." and YEAR(FechaCompleta)=".$ano." and DAY(FechaCompleta)=".$datos_dia["dia"]." ";

		if(trim($id_departamento)!="")
				$sql_salidas=$sql_salidas." and id_departamento=".$departamento." ";
		$sql_salidas=$sql_salidas." order by(IdRegistro) ";

			$cur_salidas=mssql_query($sql_salidas);
	
			$cant_salidas=mssql_num_rows($cur_salidas);
//	echo $sql_salidas." --- <br> ".mssql_get_last_message()."<br><br>";

			//si exite almenos una entrada y una salida para esa fecha se ejecutan las operaciones
			if(($cant_salidas!=0)&&($cant_entradas!=0))
			{

?>
			  <tr class="TxtTabla">
				<td  class="TituloTabla2" > <? echo $datos_dia["dia"]; ?></td>
				<td colspan="5"  bgcolor="#FFFFFF">
					<table width="100%"  border="0" cellspacing="1" cellpadding="0">
<?		
//			$cont_entra=1; $cont_salidas=1; //contadores,  utilizados para llevar el indice, de los registros de entradas y salidas
			$total_horas=0;
			$cont_entra=1; $cont_salidas=1; //contadores,  utilizados para llevar el indice, de los registros de entradas y salidas

			$cant_entradas_real=0; 	$cant_salidas_real=0; //permite contar las entradas y salidas validas, ya que se calculan las entradas que tienen una correspondiente salida
			//recorre la informacion de las entradas y salidas
//
//			while($datos_entrada=mssql_fetch_array($cur_entradas)or($datos_salidas=mssql_fetch_array($cur_salidas)))


//echo " total entradas ".$cant_entradas." --salidas- ".$cant_salidas."<br>";

			while(($cant_entradas>=$cont_entra)or($cant_salidas>=$cont_salidas))
			{
				$datos_entrada=mssql_fetch_array($cur_entradas);
				$datos_salidas=mssql_fetch_array($cur_salidas);
//echo "  indice entradas ".$cont_entra." --salidas- ".$cont_entra."<br>";
//echo "inresa".$datos_entrada["IdRegistro"] ." --salida-- ".$datos_salidas["IdRegistro"]."<br>";
				//valida que el id del registro, tanto como de las entradas y las salidas no este vacio
				if((trim($datos_entrada["IdRegistro"])!="")and(trim($datos_salidas["IdRegistro"])!="" ))
				{

					//valida si el IdRegistro de salidas es inferior al de entrada, ya que el registro de entrada, tiene que se inferior
					//se puede dar el caso que sea inferior, cuando el dia anterior no se registrado la salida, y se gira el torniquete al momento de ingresar
					//de ser asi, no se tiene en cuenta el registro, y se avanza el siguiente de las salidas, hasta que el id_registro sea mayor al de entrada

					$ban=0;//permite identificar si, se ha presentado la situacion cuando solo exite una entrada y una salida, y la entrada es mayor a la salida (IdRegistro)
					while( ( (int) $datos_salidas["IdRegistro"]) <  ( (int) $datos_entrada["IdRegistro"]))
					{
						$cont_salidas++;
//---------
						//SE CALCULA LA HORA DE SALIDA CON LA HORA APARTIR DE LAS 00:00, PARA ESTABLECER, LA CANTIDAD DE HORAS
					 	$hora_salida=$datos_salidas["Hora"];
						$hora_entrada="00:00:00:0";	
						$cur_horas=mssql_query("select cast (DATEDIFF (MI,'".$hora_entrada."','".$hora_salida."') as float)/60 as hora ");
						if($dato_hora=mssql_fetch_array($cur_horas))
						{
							$diferencia_horas=$dato_hora["hora"];
							$total_horas+=$dato_hora["hora"];

						}
?>
                        <tr class="TxtTabla">
                            <td  width="30%"></td>
                            <td  width="20%"><?php echo $datos_salidas["Fecha"]; ?></td>
                            <td  width="20%"><?PHP echo $hora_entrada; ?></td>
                            <td  width="10%"  rowspan="2"><?php echo round($diferencia_horas * 100)/100; ?></td>
                            <td width="10%"><img src="Entrada.gif" alt="Ingreso" border="0"  title="Ingreso"  ></td>
                        </tr>
                        <tr class="TxtTabla">
                            <td  width="30%"> <?php echo $datos_salidas["Torniquete"]; ?></td>
                            <td  width="20%"><?php echo $datos_salidas["Fecha"]; ?></td>
                            <td  width="20%"><?php echo $datos_salidas["Hora"]; ?></td>
                            <td  width="10%"><img src="Salida.gif" alt="Salida" border="0"  title="Ingreso"  ></td>

                        </tr>
<?php
// ------
						$datos_salidas=mssql_fetch_array($cur_salidas);

						//verifica que el registro de la salida no se a nulo, con el fin de romper el ciclo, si no se hace esto se convierte en ciclo infinito
						//la situacion se da cuando la entrada es mayor a la salida, y solo existe una entrada y una salida	
						if(trim($datos_salidas["IdRegistro"])=="" ) 
						{
							$ban=1;
							break;
						}
					}	

					//si se presento la situacion	
					if($ban==1)
					{
						$hora_entrada=$datos_entrada["Hora"];
						$hora_salida="23:59";
						$datos_salidas["Fecha"]=$datos_entrada["Fecha"];
						$datos_salidas["Hora"]="23:59";;
						
					}
					else
					{
						$hora_entrada=$datos_entrada["Hora"];
						$hora_salida=$datos_salidas["Hora"];
					}

					//calcula la diferencia, entre la hora de ingreso y la hora de salida,calculando el timpo transcurrido
					$cur_horas=mssql_query("select cast (DATEDIFF (MI,'".$hora_entrada."','".$hora_salida."') as float)/60 as hora ");



					if($dato_hora=mssql_fetch_array($cur_horas))
					{
						$diferencia_horas=$dato_hora["hora"];
						$total_horas+=$dato_hora["hora"];
					}

/*					$cur_horas2=mssql_query("select (DATEDIFF (MI,'".$hora_entrada."','".$hora_salida."'))/60 as hora ");
					if($dato_hora2=mssql_fetch_array($cur_horas2))
					{
//						$diferencia_horas2=$dato_hora2["hora"];
						$total_horas2+=$dato_hora2["hora"];
					}
*/
//echo "select cast (DATEDIFF (MI,'".$hora_entrada."','".$hora_salida."') as float)/60 as hora <br><br> diferencia sin float ".$diferencia_horas2."<br><br>";
?>
<!-- 
					Informacion de las entradas
-->
					<tr class="TxtTabla" width="100%">
					<td  width="30%"> <?php echo $datos_entrada["Torniquete"]; ?></td>
					<td  width="20%"><?php echo $datos_entrada["Fecha"]; ?></td>
					<td  width="20%"><?php echo $datos_entrada["Hora"]; ?></td>
					<td  width="10%" rowspan="2"><?php echo round($diferencia_horas * 100)/100; ?></td>
				
					<td  width="10%"><?php if(($datos_entrada["EntradaSalida"]==0)and($ban==0))
					{ ?>
						<img src="Entrada.gif" alt="Ingreso" border="0"  title="Ingreso"  >
<?					 //echo "Entrada"; 
					} 
					if(($datos_entrada["EntradaSalida"]==1)and($ban==0))
					{ 
?>
					<img src="Salida.gif" alt="Salida" border="0" >	
<?				//echo "Salida"; 
					}  ?></td>
					</tr>
<!-- 
					Informacion de las salidas
-->

					<tr class="TxtTabla">
					<td  width="30%"> <?php echo $datos_salidas["Torniquete"]; ?></td>
					<td  width="20%"><?php echo $datos_salidas["Fecha"]; ?></td>
					<td  width="20%"><?php echo $datos_salidas["Hora"]; ?></td>
					<td  width="10%"><?php if(($datos_salidas["EntradaSalida"]==0)and($ban==0))
					{ ?>
                      <img src="Entrada.gif" alt="Entrada" border="0" >
                      <?					 //echo "Entrada"; 
					} 
					if(($datos_salidas["EntradaSalida"]==1)and($ban==0))
					{ 
?>
                      <img src="Salida.gif" alt="Salida" border="0" title="Salida"  >
                      <?				//echo "Salida"; 
					}  ?></td>
					</tr>
		<?php



					
					$cant_entradas_real++;
					$cant_salidas_real++;

//echo "select cast (DATEDIFF (MI,'".$hora_entrada."','".$hora_salida."') as float)/60 as hora -----total <br> $total_horas <br>";
				}
				else //SI ALGUNO DE LOS DOS REGISTROS ESTA VACIO ES POR QUE EXISTE: 1. UNA ENTRADA SIN SALIDA 2. UNA SALIDA SIN ENTRADA
				{
					
					if((trim($datos_entrada["IdRegistro"])!=""))//ENTRADA SIN SALIDA 
					{
						$hora_entrada=$datos_entrada["Hora"];
						$hora_salida="23:59:00:0";	
						$cur_horas=mssql_query("select cast (DATEDIFF (MI,'".$hora_entrada."','".$hora_salida."') as float)/60 as hora ");
//echo "ENtrada<br> select cast (DATEDIFF (MI,'".$hora_entrada."','".$hora_salida."') as float)/60 as hora <br>".mssql_get_last_message()."<br><br>";
						if($dato_hora=mssql_fetch_array($cur_horas))
						{
							$diferencia_horas=$dato_hora["hora"];
							$total_horas+=$dato_hora["hora"];

						}
?>
                        <tr class="TxtTabla">
                            <td  width="30%"><?php echo $datos_entrada["Torniquete"]; ?></td>
                            <td  width="20%"><?php echo $datos_entrada["Fecha"]; ?></td>
                            <td  width="20%"><?php echo $datos_entrada["Hora"]; ?></td>
                            <td  width="10%" rowspan="2"><?php echo round($diferencia_horas * 100)/100; ?></td>
                            <td width="10%"><img src="Entrada.gif" alt="Ingreso" border="0"  title="Ingreso"  ></td>
                        </tr>
                        <tr class="TxtTabla">
                            <td  width="30%">&nbsp;  </td>
                            <td  width="20%"><?php echo $datos_entrada["Fecha"]; ?></td>
                            <td  width="20%"><?PHP echo $hora_salida; ?></td>
                            <td width="10%"><img src="Salida.gif" alt="Salida" border="0"  title="Ingreso"  ></td>
                        </tr>
<?php
					}			
					if(trim($datos_salidas["IdRegistro"])!="" ) //SALIDA SIN ENTRADA
					{
						$hora_entrada="00:00:00:0";
						$hora_salida=$datos_salidas["IdRegistro"];	

						$cur_horas=mssql_query("select cast (DATEDIFF (MI,'".$hora_entrada."','".$hora_salida."') as float)/60 as hora ");
//echo "<br>Salida  <br> select cast (DATEDIFF (MI,'".$hora_entrada."','".$hora_salida."') as float)/60 as hora  ".mssql_get_last_message();
						if($dato_hora=mssql_fetch_array($cur_horas))
						{
							$diferencia_horas=$dato_hora["hora"];
							$total_horas+=$dato_hora["hora"];

						}
?>
                        <tr class="TxtTabla">
                            <td  width="30%"></td>
                            <td  width="20%"><?php echo $datos_salidas["Fecha"]; ?></td>
                            <td  width="20%"><?PHP echo $hora_entrada; ?></td>
                            <td  width="10%"  rowspan="2"><?php echo round($diferencia_horas * 100)/100; ?></td>
                            <td width="10%"><img src="Entrada.gif" alt="Ingreso" border="0"  title="Ingreso"  ></td>
                        </tr>
                        <tr class="TxtTabla">
                            <td  width="30%"> <?php echo $datos_salidas["Torniquete"]; ?></td>
                            <td  width="20%"><?php echo $datos_salidas["Fecha"]; ?></td>
                            <td  width="20%"><?php echo $datos_salidas["Hora"]; ?></td>
                            <td  width="10%"><img src="Salida.gif" alt="Salida" border="0"  title="Ingreso"  ></td>

                        </tr>
<?php
					}

				}

					$cont_entra++;
					$cont_salidas++;

			}
?>
                    <TR>
						<td colspan="5">
							<table width="100%">
                                <td  class="TituloTabla2">Cantidad de entradas</td>
                                <td class="TxtTabla"><? echo $cant_entradas_real; ?></td>
                                <td  class="TituloTabla2">Cantidad de salidas</td>
                                <td class="TxtTabla"><? echo $cant_salidas_real; ?></td>
                                <td  class="TituloTabla2">Horas laboradas</td>
                                <td class="TxtTabla"><? echo round($total_horas * 100)/100; //$total_horas." *** ". ?></td>

							</table>
						</td>
                    <TR>
				</table>
			</td>
		  </tr>
<?
			}//del if que evalua entradas y salidas !=0
		} //del while

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
