<?php
//include('../conectaBD.php');

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






//$error="no";

mssql_query("BEGIN TRANSACTION");

echo "bbbbbbbbbbbbbbbbbbbbbbiiiiiiiiiiiiiiiiiiiennnnnnnnnnnnnnnnnnnnnnnnnnnnn  <br><br><br>";

$hora = getdate(time());
            echo ( "HORA DE INICIO ".$hora["hours"] . ":" . $hora["minutes"] . ":" . $hora["seconds"] ); 
echo $error."<br>";
function almacena_entradas_salidas($tipo,$tabla,$error)
{
echo "<br>Inicia Funcion    tipo: ".$tipo." Tabla: ".$tabla." error ".$error." <br><br>";
	if($error=="no")
	{
	
		$conexion = conectar("HojaDeTiempo");
		$sql_div="select * from HojaDeTiempo.dbo.Divisiones where estadoDiv='A'";
		$cur_div=mssql_query($sql_div);
echo "<BR><BR> SQL DIVISION ".$sql_div." ---***** <br><br>";
		while($datos_div=mssql_fetch_array($cur_div))
		{
		
			$sql_dep="select *  from HojaDeTiempo.dbo.Departamentos WHERE id_division=".$datos_div["id_division"]." and estadoDpto='A'";
			$cur_dep=mssql_query($sql_dep);
		echo "<BR><BR>SQL DEPARTAMENTO ".$sql_dep." ---***** <br><br>";
			while(($datos_dep=mssql_fetch_array($cur_dep))and($error=="no"))
			{
		
				echo " ** DIVISION *-********--****** ".$datos_div["id_division"]."<br><br>";
			
					//Establecer la conexión a la base de datos		
					$conexion = conectar("SmartAccessControl");
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
								AND de.id_division = dv.id_division 
			
								AND tlob.Autorizada=1
								AND tlob.TranBatch=0";
					
								//TEMPORAL PARA QUITAR
								//$sql_consul_entradas=$sql_consul_entradas." and dv.id_division=14	and de.id_departamento=263";
								$sql_consul_entradas=$sql_consul_entradas." and dv.id_division=".$datos_div["id_division"]." and de.id_departamento=".$datos_dep["id_departamento"];
								
								$sql_consul_entradas=$sql_consul_entradas." and tlob.EntradaSalida=".$tipo."
								and tlob.IdTerminal<>15
								and tlob.IdTerminal<>16
								and tlob.IdTerminal<>17
								and tlob.IdTerminal<>18
								and tlob.IdTerminal<>19";
								$sql_consul_entradas=$sql_consul_entradas." and MONTH(tlob.Fecha)=3 and year(tlob.Fecha)=2013 ";
/*					
								//SI EL MES ACTUAL ES ENERO, SE CONSULTA LA INFORMACION DE DICIEMBRE DEL AÑO ANTERIOR
								if(date("m")==1) 
									$sql_consul_entradas=$sql_consul_entradas." and MONTH( tlob.Fecha)=12 and year(tlob.Fecha)=year(getdate())-1";
								else			
									$sql_consul_entradas=$sql_consul_entradas." and MONTH( tlob.Fecha) = month(getdate())-1 and year(tlob.Fecha)=year(getdate()) and day( tlob.Fecha)=DAY(GETDATE())-1 ";
*/					
								$sql_consul_entradas=$sql_consul_entradas." ORDER BY tlob.Fecha,Horas";
					
								$cur_consul_entradas=mssql_query($sql_consul_entradas);
echo $sql_consul_entradas." ".mssql_get_last_message()."<br><br>";					
								if(trim($cur_consul_entradas)=="")
									$error="si";
					
					echo 	$sql_consul_entradas." ---- error ".$error." --- ".mssql_get_last_message()." registros ".mssql_num_rows($cur_consul_entradas)." <br><br>";
					
								$conexion = conectar("HojaDeTiempo");
							
					$ff=0;
								while(($datos_entradas=mssql_fetch_array($cur_consul_entradas))and($error=="no"))
								{
					$ff++;
		
									$sql_insert="insert into HojaDeTiempo.dbo.".$tabla."(IdRegistro,unidad,id_division,id_departamento,IdTerminal,Torniquete,NoTarjeta,NoDocumento,FechaCompleta,Fecha,Hora,EntradaSalida,Fecha_registro)values(";
									$sql_insert=$sql_insert." ".$datos_entradas["IdRegistro"].",".$datos_entradas["unidad"].",".$datos_entradas["id_division"].",".$datos_entradas["id_departamento"].",".$datos_entradas["IdTerminal"].",'".$datos_entradas["Torniquete"]."',".$datos_entradas["NoTarjeta"].",".$datos_entradas["NoDocumento"].",'".$datos_entradas["FechaCompleta"]."','".$datos_entradas["Fecha"]."','".$datos_entradas["Horas"]."','".$datos_entradas["EntradaSalida"]."','".gmdate("n/d/y") ."')";
					 
									$cur_insert=mssql_query($sql_insert);
echo 	"$ff *** ".$sql_insert." ----  ".mssql_get_last_message()." --- ".$error." <br><br>";
									if(trim($cur_insert)=="")
										$error="si";
								}
				echo "<br><br><br><br>**********************************************************************************---------************************************************<br>";
				//si no se presentaron errores se ejecuta el sigunete select
			}
		}
	}
  echo ( "<br>HORA DE finalizacion $tabla ".$hora["hours"] . ":" . $hora["minutes"] . ":" . $hora["seconds"]." error: $error" ); 
	return $error;
}

//ejecuta la funcion, para las entradas
$error2=almacena_entradas_salidas(0,"TMEntradas","no");

echo "<br><br> error2: $error2 <br>";
if($error2=="no") //si no se presentaron errores al insertar las salidas
	$error2=almacena_entradas_salidas(1,"TMSalidas","no");

$hora = getdate(time());

  echo ( "HORA DE finalizacion total ".$hora["hours"] . ":" . $hora["minutes"] . ":" . $hora["seconds"]."  error: ".$error2 ); 

if(($error2=="no"))
{
	echo " operacion commit bien";
	mssql_query("COMMIT TRANSACTION");
}
else
{
	echo " operacion rollb error";
	mssql_query("ROLLBACK TRANSACTION");
}

			
?>