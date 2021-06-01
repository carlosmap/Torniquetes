<?php
//include('../conectaBD.php');

include("../fncEnviaMailPEAR.php");

function conectar($bd){
//		if (!($CONECTADO=@mssql_connect("sqlservidor","12974","1373")))	{
		if (!($CONECTADO=@mssql_connect("sqlservidor","12974","1373")))	{
			return 0;
			exit();
		}

		if (!@mssql_select_db($bd,$CONECTADO))
		{
			exit();
		}
		return $CONECTADO;
	}


//SE CREAN LOS ARRAYS QUE VAN A CONTENER LA INFORMACIÓN QUE SE ENVIARA POR CORREO
$divisiones= array();
$informacion_departamentos_entradas; //pos 1: (nom_depto) 2: (cante Entradas) 3: (cant Salidas) 4: (Horas) 5: (Estado)
$informacion_departamentos_salidas;
$in_div=0;
$f=0; 
$c=0;

$hora = getdate(time());
$hora_inicio=$hora["hours"].":".$hora["minutes"].":".$hora["seconds"];
  echo ( "<br>HORA DE inicio $tabla ".$hora["hours"] . ":" . $hora["minutes"] . ":" . $hora["seconds"]." error: $error" ); 

///CONSULTA EL MES ANTERIOR, Y SI ESTA ENTRE EL 1 Y EL 9 MES, SE RESTA EN UNO, ESTO PARA CALCULAR EL DIA 1 DEL MES ANTERIOR, Y  AÑADE UN "0" AL PRINCIPIO
$mes=date("m");
$mes=$mes-1;
if ((1<=$mes) or ($mes<=9))
{
	$mes="0".$mes;
}

function almacena_entradas_salidas($tipo,$tabla,$error,$mes)
{
	$conexion = conectar("HojaDeTiempo");
	$informacion_departamentos= array();
	$sql_error="";
	$sql_error2="";
	$f=0;
	if($error=="no")
	{
	 
		$sql_div="select * from HojaDeTiempo.dbo.Divisiones where estadoDiv='A' " ;
		$cur_div=mssql_query($sql_div);

echo "<br> 1. ".$sql_div." <br> ---".mssql_get_last_message()."<br>";

		if(trim($cur_div)=="")
			$error="si";
		while ( ($datos_div=mssql_fetch_array($cur_div))and($error=="no") )
		{
			$divisiones[$in_div][0]=$datos_div["nombre"]; //ALMACENA EL NOMBRE DE LAS DIVISIONES

			$sql_dep="select *  from HojaDeTiempo.dbo.Departamentos WHERE id_division=".$datos_div["id_division"]." and estadoDpto='A'   ";
			$cur_dep=mssql_query($sql_dep);

			$divisiones[$in_div][1]=mssql_num_rows($cur_dep); //ALMACENA LA CANTIDAD DEPARTAMENTO ASOCIADOS A LA DIVISION
echo "<br> 2.".$sql_dep." <br> ---".mssql_get_last_message()."<br>";

			if(trim($cur_dep)=="")
				$error="si";

			while(($datos_dep=mssql_fetch_array($cur_dep))and($error=="no"))
			{

				$c=0;
				$informacion_departamentos[$f][$c]=$datos_dep["nombre"];


					//CONSULTA LOSS INGRESOS
					$sql_consul_entradas="
							SELECT 
								tlob.IdRegistro,
								DEP.Descripcion Dependencia
								, DEP.IdDependencia
								, u.unidad
								, dv.nombre Division
								, dv.id_division
								, de.nombre Departamento
								, de.id_departamento
								, ter.Descripcion Torniquete
								, ter.IdTerminal
								, tlob.NoTarjeta
								, usu.NoDocumento
								, usu.Nombres, usu.Apellidos
								, tlob.Fecha FechaCompleta
								, CONVERT(VARCHAR(10), tlob.Fecha, 111) Fecha			
								,
								 CONVERT(VARCHAR(10), tlob.Fecha, 114) Horas
								, tlob.EntradaSalida
							FROM
								 SmartAccessControl.dbo.TB_LOG_TERMINAL tlob
								 , SmartAccessControl.dbo.TB_EMPLEADO usu
								 , SmartAccessControl.dbo.TB_TERMINAL ter
								 , SmartAccessControl.dbo.TB_DEPENDENCIA dep
								 , HojaDeTiempo.dbo.Usuarios u
								 , HojaDeTiempo.dbo.Departamentos de
								 , HojaDeTiempo.dbo.Divisiones dv
							WHERE
								usu.NoTarjeta=tlob.NoTarjeta
								AND ter.IdTerminal = tlob.IdTerminal
								AND dep.IdDependencia = usu.IdDependencia
								AND u.numDocumento = CAST( usu.NoDocumento AS VARCHAR(20) ) 
								AND u.id_departamento = de.id_departamento
								AND de.id_division = dv.id_division 			
								AND tlob.Autorizada=1
								AND tlob.TranBatch=0";
					
								//TEMPORAL PARA QUITAR
								$sql_consul_entradas=$sql_consul_entradas." and dv.id_division=".$datos_div["id_division"]." and de.id_departamento=".$datos_dep["id_departamento"];								
								$sql_consul_entradas=$sql_consul_entradas." and tlob.EntradaSalida=".$tipo."
								and tlob.IdTerminal<>15
								and tlob.IdTerminal<>16
								and tlob.IdTerminal<>17
								and tlob.IdTerminal<>18
								and tlob.IdTerminal<>19";

								$sql_consul_entradas=$sql_consul_entradas." and MONTH( tlob.Fecha)='6' and year(tlob.Fecha)='2013' and day(tlob.Fecha)='03' ";  //TEMPORAL

/*					
								//SI EL DIA ACTUAL ES 1 DEL MES, Y EL MES NO ES ENERO, SE CONSULTA LA INFORMACION DEL ULTIMO DIA DEL MES ANTERIOR
								if((date("d")==1) and (date("m")!=1) )
									$sql_consul_entradas=$sql_consul_entradas." and MONTH( tlob.Fecha) = month(getdate())-1 and year(tlob.Fecha)=year(getdate()) and day( tlob.Fecha)=day(dateadd(d,-1,dateadd(m,1,convert(datetime, '".date("Y")."' + '".$mes."' + '01'))))";

								//SI EL DIA ES 1 DE ENERO, SE CONSULTA LA INFORMACION DEL 31 DE DICIEMBRE DEL AÑO ANTERIOR
								else if((date("d")==1) and (date("m")==1) )
									$sql_consul_entradas=$sql_consul_entradas." and MONTH( tlob.Fecha)=12 and year(tlob.Fecha)=year(getdate())-1 and day(tlob.Fecha)=31";

								else			
									$sql_consul_entradas=$sql_consul_entradas." and MONTH( tlob.Fecha) = month(getdate()) and year(tlob.Fecha)=year(getdate()) and day( tlob.Fecha)=DAY(GETDATE())-1 ";
					
*/

								$sql_consul_entradas=$sql_consul_entradas." ORDER BY tlob.Fecha,Horas ";					
								$cur_consul_entradas=mssql_query($sql_consul_entradas);

echo "<br> consecutivo depto ".$f."  --- 4.  ".$sql_consul_entradas." <br> ---".mssql_get_last_message()."<br>".$error."<br>";

								if(trim($cur_consul_entradas)=="")
									$error="si";				

								if($error=="si")
								{
									$sql_error2="insert into HojaDeTiempo.dbo.TM_Log_Torniquetes  (Fecha,transaccion_sql,Descripcion)
									 values(getdate(),'".$sql_consul_entradas."', '".str_replace("'","", mssql_get_last_message() )."' )";

								}

								$c++;
								$informacion_departamentos[$f][$c]=mssql_num_rows($cur_consul_entradas);//CANTIDAD ENTRADAS /SALIDAS
								$c++;
								$informacion_departamentos[$f][$c]= date("d-m-Y H:i:s"); //ALMACENA LA FECHA Y HORA 
						


								while(($datos_entradas=mssql_fetch_array($cur_consul_entradas))and($error=="no"))
								{
//					$ff++;
		
									$sql_insert="insert into HojaDeTiempo.dbo.".$tabla."(IdRegistro,unidad,id_division,id_departamento,IdTerminal,Torniquete,NoTarjeta,NoDocumento,FechaCompleta,Fecha,Hora,EntradaSalida,Fecha_registro)values(";
									$sql_insert=$sql_insert." ".$datos_entradas["IdRegistro"].",".$datos_entradas["unidad"].",".$datos_entradas["id_division"].",".$datos_entradas["id_departamento"].",".$datos_entradas["IdTerminal"].",'".$datos_entradas["Torniquete"]."',".$datos_entradas["NoTarjeta"].",".$datos_entradas["NoDocumento"].",'".$datos_entradas["FechaCompleta"]."','".$datos_entradas["Fecha"]."','".$datos_entradas["Horas"]."','".$datos_entradas["EntradaSalida"]."','".gmdate("n/d/y") ."  ')";
	

									$cur_insert=mssql_query($sql_insert);
									if(trim($cur_insert)=="")
										$error="si";
echo $f." Insertar ".$sql_insert." <br>".mssql_get_last_message()."<br>";
									//SI OCURRE UN ERROR SE ALMACENA LA INFORMACION EN LA TABLA TM_Log_Torniquetes
									if($error=="si")
									{
										$sql_error="insert into HojaDeTiempo.dbo.TM_Log_Torniquetes  (IdRegistro,Fecha,NoTarjeta,unidad,transaccion_sql,Descripcion)
										 values( ".$datos_entradas["IdRegistro"].",getdate(),".$datos_entradas["NoTarjeta"].",".$datos_entradas["unidad"].",'". str_replace("'","",$sql_insert)."',  '". str_replace("'","", mssql_get_last_message() )."' )";

									}
								}

								$c++;
								$informacion_departamentos[$f][$c]=$error; //estado		
				//si no se presentaron errores se ejecuta el sigunete select

				
				$f++ ; //INDICE DE LOS DEPARTAMENTOS $informacion_departamentos[$f][$c]
			}


echo "Valor F:".$f."<br>";
			$in_div++; //INDICE DE LAS DIVISIONES $divisiones[$in_div]
		}
	}

echo ( "<br>HORA DE finalizacion $tabla ".$hora["hours"] . ":" . $hora["minutes"] . ":" . $hora["seconds"]." error: $error ".mssql_get_last_message() ); 
	return array($error, $informacion_departamentos ,$divisiones , $sql_error, $sql_error2);
}
$error2="";
$conexion = conectar("HojaDeTiempo");
$cur_t=mssql_query("BEGIN TRANSACTION");
echo "<br>BEGIN<br>".mssql_get_last_message();

//ejecuta la funcion, para las entradas
list($error2,$informacion_departamentos_entradas,$info_divisiones_entradas,$sql_error,$sql_error2)=almacena_entradas_salidas(0,"TMEntradas","no",$mes);

if($error2=="no") //si no se presentaron errores al insertar las salidas
	list ($error2,$informacion_departamentos_salidas,$info_divisiones_salidas,$sql_error,$sql_error2)=almacena_entradas_salidas(1,"TMSalidas","no",$mes);

$hora = getdate(time());
$hora_finaliza=$hora["hours"].":".$hora["minutes"].":".$hora["seconds"];
$err_el="";

$conexion = conectar("HojaDeTiempo");
if(($error2=="no"))
{
	echo " operacion commit bien";
	mssql_query("COMMIT TRANSACTION");
//	$cur_t=mssql_query("ROLLBACK TRANSACTION");
		echo "--------------- COMMIT <BR>".mssql_get_last_message();
}
else
{
	echo " operacion rollb error";
	$cur_t=mssql_query("ROLLBACK TRANSACTION");
echo "--------------- ROLL <BR>".mssql_get_last_message();
	if(trim($sql_error)!="") //si se presentaron errores, se almacena la informacion del error
	{
		$cur_error=mssql_query($sql_error);
		echo "--------------- SQL ERROR <BR>".$sql_error."<br><br>".mssql_get_last_message();
	}
	if(trim($sql_error2)!="") //si se presentaron errores, se almacena la informacion del error
	{
			$cur_error=mssql_query($sql_error2);
		echo "--------------- SQL ERROR <BR>".$sql_error2."<br><br>";
	}
	//SI SE PRENSENTA UN ERROR EN EL ROLLBACK, SE ELIMINA LA INFORMACION DE  FORMA MANUAL
	if(trim($cur_t)=="")
	{
		//SI EL ROLLBACK PRESENTA ERROR, POR ELLO SE INCLUYO LAS CONSULTAS, PARA QUE SE ELIMINEN LOS DATOS DE LAS ENTRADAS Y SALIDAS, CUANDO SE PRESENTA UN ERROR
		$sql_err=" delete from HojaDeTiempo.dbo.TMEntradas where YEAR(Fecha_registro)=".$hora["year"]." and MONTH(Fecha_registro)='".$hora["mon"]."' and DAY(Fecha_registro)='".$hora["mday"]."' ";
		$cur_err=mssql_query($sql_err);
		
		if(trim($cur_err)!="")
		{
			$sql_err=" delete from HojaDeTiempo.dbo.TMSalidas where YEAR(Fecha_registro)=".$hora["year"]." and MONTH(Fecha_registro)='".$hora["mon"]."' and DAY(Fecha_registro)='".$hora["mday"]."' ";
			$cur_err=mssql_query($sql_err);
		}

		if(trim($cur_err)!="")
			$err_el="  <center> Se ha presentado un error y la informaci&oacute;n se ha eliminado de forma manual de las tablas temporales exitosamente </center>";
		
		if(trim($cur_err)=="")
			$err_el=" <center> Error durante la eliminaci&oacute;n de las tablas temporales </center> <br> ".$sql_err." <br>".mssql_get_last_message();
	}
}


echo "<br>".(count($informacion_departamentos_entradas, COUNT_RECURSIVE)-count($informacion_departamentos_entradas))." --------------- <br>";
		//envia el correo con la  informacion del procedimiento
		$pTema='<table width="100%" border="0"> ';
		$pTema=$pTema='
		  <tr class="Estilo2">
			<td width="24%">Asunto:</td>
			<td width="76%">Informaci&oacute;n sobre el procedimiento de torniquetes </td>
		  </tr>
		  <tr class="Estilo2">
			<td width="24%">Fecha Procedimiento:</td>
			<td width="76%"> '.$hora["mday"].'/'.$hora["month"].'/'.$hora["year"].' </td>
		  </tr>

		  <tr class="Estilo2">
			<td width="24%">Hora de inicio:</td>
			<td width="76%"> '.$hora_inicio.' </td>
		  </tr>

		  <tr class="Estilo2">
			<td width="24%">Hora de finalizaci&oacute;n:</td>
			<td width="76%"> '.$hora_finaliza.' </td>
		  </tr>
		  <tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		  </tr>';
		$pTema=$pTema." </table><table  width='100%' border='0' >";
		$pTema=$pTema."<tr>
		    	<td colspan=4 class='Estilo2' align='center'>Informaci&oacute;n de las entradas </td>
		  </tr>
		    <tr>
		      <td class='Estilo2' align='center'>Departamento </td>

		      <td class='Estilo2' align='center'>Cantidad Registros</td>

		      <td class='Estilo2' align='center'>Fecha/Hora</td>
		      <td class='Estilo2' align='center'>Error</td></tr>";
		$total=0;
		for($i=0;$i<=(count($informacion_departamentos_entradas, COUNT_RECURSIVE)-count($informacion_departamentos_entradas)) ;$i++)
		{
//			for($c=0;$c<=4;$c++)
			{
				echo $informacion_departamentos_entradas[$i][0]."<br>";
				
				 $pTema=$pTema.'<tr class="Estilo2" align="center">
					<td >'.$informacion_departamentos_entradas[$i][0].'</td>
					<td>'.$informacion_departamentos_entradas[$i][1].'</td>
					<td>'.$informacion_departamentos_entradas[$i][2].'</td>
					<td>'.$informacion_departamentos_entradas[$i][3].'</td>
				  </tr>';
				$total+=$informacion_departamentos_entradas[$i][1];
			}
		}

		$pTema=$pTema."<tr  class='Estilo2' ><td>Total entradas</td><td>".$total."</td><td colspan='2' ></td></tr>";

echo "<br>".(count($informacion_departamentos_salidas, COUNT_RECURSIVE)-count($informacion_departamentos_salidas))." --------------- <br>";
		$pTema=$pTema."<tr><td>&nbsp;</td></tr><tr><td colspan=4  class='Estilo2' align='center'>Informacion de las salidas <td></tr>";
		//envia el correo con la  informacion del procedimiento
		$total=0;
		for($i=0;$i<=(count($informacion_departamentos_entradas, COUNT_RECURSIVE)-count($informacion_departamentos_entradas)) ;$i++)
		{
//			for($c=0;$c<=4;$c++)
			{
				echo $informacion_departamentos_salidas[$i][0]."<br>";
				
				 $pTema=$pTema.'<tr class="Estilo2" align="center">
					<td >'.$informacion_departamentos_salidas[$i][0].'</td>
					<td>'.$informacion_departamentos_salidas[$i][1].'</td>
					<td>'.$informacion_departamentos_salidas[$i][2].'</td>
					<td>'.$informacion_departamentos_salidas[$i][3].'</td>
				  </tr>';
				$total+=$informacion_departamentos_entradas[$i][1];
			}
		}

		$pTema=$pTema."<tr  class='Estilo2'><td>Total salidas</td><td>".$total."</td><td colspan='2' ></td></tr>";
		if(trim($err_el)!="") //mensaje de error al momento de eliminar la informacion de las tablas temporales
		{
			$pTema=$pTema.'<tr><td colspan="4" class="Estilo2" > &nbsp;</td></tr> <tr><td colspan="4" class="Estilo2" >'.$err_el.'</td></tr>';		
		}

		$pTema=$pTema.'		
		  <tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		  </tr>';
		$pTema=$pTema.'</table>';

echo "<br><br> tabla<br>".$pTema."<br>";

	   $pAsunto="Informacion procedimiento torniquetes";
		$pFirma="Portal";
	   $miMailUsuarioEM = "carlosmaguirre";
	   $pPara= trim($miMailUsuarioEM) . "@ingetec.com.co";
	   enviarCorreo($pPara, $pAsunto, $pTema, $pFirma);
	   //***FIN EnviarMailPEAR
	   $miMailUsuarioEM = "";
			
?>