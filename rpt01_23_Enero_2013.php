<?php
session_start();
include("../verificaRegistro2.php");
include('../conectaBD.php');

?>

<?

//Establecer la conexión a la base de datos
$conexion = conectar();
//mssql_select_db('HojaDeTiempo');
if($recarga==2)
{
	//consulta las personas que hacen parte de la division y el departamento
//,NoTarjeta

	$sql_consul_perso="  select distinct(t.unidad),u.retirado, u.nombre as Nombres,u.apellidos as Apellidos, d.nombre as Departamento from HojaDeTiempo.dbo.TMEntradas as t
 inner join HojaDeTiempo.dbo.Usuarios as u on u.unidad=t.unidad
 inner join HojaDeTiempo.dbo.Departamentos as d on d.id_departamento=t.id_departamento";
	$sql_consul_perso=$sql_consul_perso."	 where t.id_division=".$division;

	if(trim($uni)!="")
		$sql_consul_perso=$sql_consul_perso." and t.unidad=".$uni;

	if(trim($departamento)!="")
		$sql_consul_perso=$sql_consul_perso." and t.id_departamento=".$departamento;

	$sql_consul_perso=$sql_consul_perso." and YEAR(t.FechaCompleta)=".$ano;
	$sql_consul_perso=$sql_consul_perso." and MONTH(t.FechaCompleta)=".$mess;
	$sql_consul_perso=$sql_consul_perso." order by(u.nombre)";

	$cur_perso=mssql_query($sql_consul_perso);
	$cant_reg=mssql_num_rows($cur_perso);

//echo $sql_consul_perso." --- ".mssql_get_last_message()." *** ".mssql_num_rows($cur_perso)." <br><br>";
}

?>



<html>
<head>
<title>Entradas y Salidas</title>
<LINK REL="stylesheet" HREF="../../css/estilo.css" TYPE="text/css">
<script>

window.name="win_accesos";

var nav4 = window.Event ? true : false;
function acceptNum(evt){   
var key = nav4 ? evt.which : evt.keyCode;   
return (key <= 13 || (key>= 48 && key <= 57));
}

function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
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
    <table width="100%"  border="0" cellspacing="1" cellpadding="0">
      <tr>
        <td width="15%" height="35" class="FichaAct">Detalle</td><!-- anio=2012&mes=12&departamento=3 -->
        <td width="15%" height="35" class="FichaInAct">
        <?	if( ( $ano != "" ) && ( $mess != "" ) && ( $departamento != "" ) && ( $division != "" ) ){		?>
		        <a href="rpt02.php?anio=<?= $ano ?>&mes=<?= $mess ?>&division=<?= $division ?>&departamento=<?= $departamento ?>&uni=<?=$uni  ?>" class="FichaInAct1" >Grafica</a>
        <?	}		?>
        </td>
        <td>&nbsp;</td>
      </tr>
    </table>
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
                <td class="TituloUsuario">Criterios de consulta </td>
              </tr>
            </table>
            
              <table width="100%"  border="0" cellspacing="1" cellpadding="0">
                <tr>
                  <td width="30%" class="TituloTabla">Division</td>
                  <td class="TxtTabla">
                        <select name="division" class="CajaTexto" id="division" onChange="document.form1.submit();">
                            <option value="">:::Selecciones Division:::</option>
                        <?	$sqlDv = "select nombre, id_division from HojaDeTiempo.dbo.Divisiones where estadoDiv = 'A'";
                            $qryDv = mssql_query( $sqlDv );		
                            while( $rwDv = mssql_fetch_array( $qryDv ) ){
                                $sel="";
                                if($division==$rwDv[id_division])
                                    $sel="selected";
                        ?>
                                <option value="<?= $rwDv[id_division] ?>" <?php echo $sel ?> ><?= $rwDv[nombre] ?></option>
                        <?	}	?>
                        </select>
                  </td>
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
                              <option value="<?= $rwDp[id_departamento] ?>" <? echo  $sel; ?> ><?= $rwDp[nombre] ?></option>
                              <?
                                }
                            }
                              ?>
                        </select>
                    </td>					
                </tr>
				 <tr>
                  <td class="TituloTabla">Unidad</td>
                  <td class="TxtTabla"><input type="text" name="uni" id="uni" value="<? echo $uni; ?>" class="CajaTexto" onKeyPress="return acceptNum(event)" ></td>
				</tr>
                <tr>
                    <td  class="TituloTabla">Fecha</td>
                    <td  class="TituloTabla">
                        <table width="100%" class="TxtTabla" >
                            <tr class="TxtTabla">
                              <td class="TxtTabla">Mes</td>
                              <td>
                              <select name="mess" class="CajaTexto" id="mess">
                                <?
                                    $mes = array( ':::Seleccione Mes:::', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre' );
                                    $m = 0;
                                    while( $m < count( $mes ) ){
                                        $sel="";
                                        if($mess==$m)
                                            $sel="selected";
                                ?>
                                    <option value="<?= $m ?>" <?php echo $sel; ?> <? echo  $sel; ?> ><?= $mes[$m] ?></option>
                                <?	$m++;
                                  }	
                                ?>
                            </select>
                            </td>
                            <td class="TxtTabla">A&ntilde;o</td>
                            <td>
                                <select name="ano" id="ano" class="CajaTexto">
                                    <option value="">:::Seleccion A&ntilde;o:::</option>
                                    <?
										$sql_fech="select distinct(year(FechaCompleta))as anos from HojaDeTiempo.dbo.TMEntradas";
										$cur_fech=mssql_query($sql_fech);
										while($datos_fec=mssql_fetch_array($cur_fech))
										{
											$sel="";
											if($ano==$datos_fec["anos"])
												$sel="selected";
?>
		                                    <option value="<?=$datos_fec["anos"] ?>"  <?php echo $sel; ?> > <?=$datos_fec["anos"];  ?> </option>
<?
										}
?>
                                </select>
                            </td>
        
                          </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                <input type="hidden" name="recarga" id="recarga" value="1" >
                  <td colspan="2" align="right" class="TituloTabla"><input type="button" value="Consultar" class="Boton" onClick="valida()" ></td>
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
        <td class="TituloUsuario" colspan="3">Reporte</td>
      </tr>
      <tr>
        <td class="TxtTabla" colspan="3">&nbsp;</td>
      </tr>
      <tr>
        <td class="TituloUsuario" width="30%">Cantidad de registros</td>
        <td class="TxtTabla" width="10%"><? echo $cant_reg ?></td>
        <td class="TxtTabla" width="70%"></td>
      </tr>

    </table>
	<table width="100%"  border="0" cellspacing="1" cellpadding="0">
      <tr>
        <td bgcolor="#FFFFFF"><table width="100%"  border="0" cellspacing="1" cellpadding="0">
          <tr class="TituloTabla2">
			<td></td>
            <td>Unidad</td>
            <td>Nombre Apellido</td>
<!--            <td>No tarjeta </td>
-->
            <td>Departamento</td>
            <td>Fecha</td>

            <td>Cant. Horas laboradas</td>
            <td>Estado</td>

            <td>Detalle</td>
          </tr>
<?php
//si se ha enviado, la consulta
if($recarga==2)
{
		//consulta los dias del mes en la tabla de entradas
		$sql_dias="select distinct(day(FechaCompleta)) as dia from HojaDeTiempo.dbo.TMSalidas where  MONTH(FechaCompleta)=".$mess." and YEAR(FechaCompleta)=".$ano." order by(dia)  ";
		$cur_dias=mssql_query($sql_dias);
//echo $sql_dias." --- <br> ".mssql_get_last_message()."<br>";
		$i=1;
//		$dias= array();
		while($datos_dia=mssql_fetch_array($cur_dias))
		{
			$dias[$i]=$datos_dia["dia"];
			$i++;
		}
//echo "Diasssss  ".$dias[3]."<br>";

			$reg=1;
			while($datos_perso=mssql_fetch_array($cur_perso))
			{
?>
              <tr class="TxtTabla">
				<td><? echo $reg; ?></td>
                <td><? echo $datos_perso["unidad"]; ?></td>
                <td><? echo $datos_perso["Nombres"]." ".$datos_perso["Apellidos"]; ?></td>
<!--
                <td><? //echo $datos_perso["NoTarjeta"]; ?></td>
-->
                <td><? echo $datos_perso["Departamento"]; ?></td>
                <td><? echo $mess."/".$ano; ?></td>
<?
				$z=1;
				$total_horas=0;
				//permite recorrer el vector que contiene los dias del mes, y asi calcular la cantidad de horas laboradas, durante el mes
				while($z<$i)
				{
//echo $dias[$z]."    indice ".$z."<br>";
					//calcula la cantidad de horas laboradas, por cada persona
					$sql_entradas="select * from HojaDeTiempo.dbo.TMEntradas where id_departamento=".$departamento." and unidad=".$datos_perso["unidad"]." and MONTH(FechaCompleta)=".$mess." and YEAR(FechaCompleta)=".$ano." and DAY(FechaCompleta)=".$dias[$z];
					$cur_entradas=mssql_query($sql_entradas);
			
					$cant_entradas=mssql_num_rows($cur_entradas);

			
					//consulta las salidas
					$sql_salidas="select * from HojaDeTiempo.dbo.TMSalidas where id_departamento=".$departamento." and unidad=".$datos_perso["unidad"]." and MONTH(FechaCompleta)=".$mess." and YEAR(FechaCompleta)=".$ano." and DAY(FechaCompleta)=".$dias[$z];
					$cur_salidas=mssql_query($sql_salidas);
			
					$cant_salidas=mssql_num_rows($cur_salidas);
			//*********
						$cont_entra=1; $cont_salidas=1; //contadores,  utilizados para llevar el indice, de los registros de entradas y salidas

						//valida si cada cursor ha finalizado, el ciclo de la cantidad toal de registros a mostrar
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
								while( ( (int) $datos_salidas["IdRegistro"]) <  ( (int) $datos_entrada["IdRegistro"]))
								{
									$cont_salidas++;
			//echo "aumenta salidas<br>";
									$datos_salidas=mssql_fetch_array($cur_salidas);
								}	
					?>
			
			<?
								$hora_entrada=$datos_entrada["Hora"];
								$hora_salida=$datos_salidas["Hora"];
			
								//calcula la diferencia, entre la hora de ingreso y la hora de salida,calculando el timpo transcurrido
								$cur_horas=mssql_query("select cast (DATEDIFF (MI,'".$hora_entrada."','".$hora_salida."') as float)/60 as hora ");
			
								if($dato_hora=mssql_fetch_array($cur_horas))
								{
									$total_horas+=$dato_hora["hora"];
								}
							}
							$cont_entra++;
							$cont_salidas++;


			
//echo $total_horas."  ---  ".$hora_entrada." ---- ".$hora_salida."<br>";
						}
					$z++;
				}
//		echo $sql_entradas." --- $cant_entradas -- $cont_entra  -- $total_horas --- $z <br> ".mssql_get_last_message()."<br><br>";
//		echo $sql_salidas." ---  $cant_salidas  -- $cont_salidas <br> ".mssql_get_last_message()."<br><br>";
?>


                <td><? echo round($total_horas * 100)/100; //redondeamos la cantidad de horas(multiplicandolo) y dividendolo, para tomar solo los dos primeros numeros despues del punto ?></td>

				<td align="center">

<?

				if( $datos_perso["retirado"]=="") 
				{
?>
                      <img src="icono_usuario.gif" alt="Entrada" border="0" >					
<?
				}
				if( $datos_perso["retirado"]==1) 
				{
?>
                      <img src="icono_usuario2.gif" alt="Entrada" border="0" >					
<?
				}
?>

					
				</td>

                <td align="center"><input name="Submit" type="submit" class="Boton" onClick="MM_openBrWindow('detalle_usuario.php?division=<? echo $division;?>&departamento=<? echo $departamento;?>&uni=<? echo $datos_perso["unidad"]; ?>&mess=<? echo $mess;?>&ano=<? echo $ano;?>','vAF','scrollbars=yes,resizable=yes,width=780,height=300')" value="Detalle"></td>
              </tr>
<?php
				$reg++;
			}
?>
	
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
