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






$error="no";

mssql_query("BEGIN TRANSACTION");

echo "bbbbbbbbbbbbbbbbbbbbbbiiiiiiiiiiiiiiiiiiiennnnnnnnnnnnnnnnnnnnnnnnnnnnn  <br><br><br>";

$hora = getdate(time());
            echo ( "HORA DE INICIO ".$hora["hours"] . ":" . $hora["minutes"] . ":" . $hora["seconds"] ); 

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
					//	$sql_consul_entradas=$sql_consul_entradas." and dv.id_division=14	and de.id_departamento=263";
						$sql_consul_entradas=$sql_consul_entradas." and dv.id_division=".$datos_div["id_division"]." and de.id_departamento=".$datos_dep["id_departamento"];
						
						$sql_consul_entradas=$sql_consul_entradas." and tlob.EntradaSalida=0
						and tlob.IdTerminal<>15
						and tlob.IdTerminal<>16
						and tlob.IdTerminal<>17
						and tlob.IdTerminal<>18
						and tlob.IdTerminal<>19";
			
						//SI EL MES ACTUAL ES ENERO, SE CONSULTA LA INFORMACION DE DICIEMBRE DEL AÑO ANTERIOR
						if(date("m")==1) //para colocar  
							$sql_consul_entradas=$sql_consul_entradas." and MONTH( tlob.Fecha)=12 and year(tlob.Fecha)=year(getdate())-1";
						else			
							$sql_consul_entradas=$sql_consul_entradas." and MONTH( tlob.Fecha) = month(getdate())-1 and year(tlob.Fecha)=year(getdate()) ";
			
						$sql_consul_entradas=$sql_consul_entradas."ORDER BY tlob.Fecha,Horas";
			
						$cur_consul_entradas=mssql_query($sql_consul_entradas);
			
						if(trim($cur_consul_entradas)=="")
							$error="si";
			
			echo 	$sql_consul_entradas." ---- error ".$error." --- ".mssql_get_last_message()." registros ".mssql_num_rows($cur_consul_entradas)." <br><br>";
			
						$conexion = conectar("HojaDeTiempo");
					
			$ff=0;
						while(($datos_entradas=mssql_fetch_array($cur_consul_entradas))and($error=="no"))
						{
			$ff++;

							$sql_insert="insert into HojaDeTiempo.dbo.TMEntradas (IdRegistro,unidad,id_division,id_departamento,IdTerminal,Torniquete,NoTarjeta,NoDocumento,FechaCompleta,Fecha,Hora,EntradaSalida)values(";
							$sql_insert=$sql_insert." ".$datos_entradas["IdRegistro"].",".$datos_entradas["unidad"].",".$datos_entradas["id_division"].",".$datos_entradas["id_departamento"].",".$datos_entradas["IdTerminal"].",'".$datos_entradas["Torniquete"]."',".$datos_entradas["NoTarjeta"].",".$datos_entradas["NoDocumento"].",'".$datos_entradas["FechaCompleta"]."','".$datos_entradas["Fecha"]."','".$datos_entradas["Horas"]."','".$datos_entradas["EntradaSalida"]."')";
			
							$cur_insert=mssql_query($sql_insert);
			echo 	"$ff *** ".$sql_insert." ----  ".mssql_get_last_message()." --- ".$error." <br><br>";
							if(trim($cur_insert)=="")
								$error="si";
						}
		echo "<br><br><br><br>**********************************************************************************---------************************************************<br>";
		//si no se presentaron errores se ejecuta el sigunete select
	}
}
$hora = getdate(time());
            echo ( "HORA DE finalizacion entradas ".$hora["hours"] . ":" . $hora["minutes"] . ":" . $hora["seconds"] ); 

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
echo "<BR><BR> SQL DEPARTAMENTO ".$sql_dep." ---***** <br><br>";
		while(($datos_dep=mssql_fetch_array($cur_dep))and($error=="no"))
		{
			echo " ** DIVISION *-********--****** ".$datos_div["id_division"]."<br> error $error <br>";			
			$conexion = conectar("SmartAccessControl");
			
				//CONSULTA LAS SALIDAS
				$sql_consul_salidas="
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
						AND tlob.TranBatch=0 ";
			
						//TEMPORAL PARA QUITAR
						//$sql_consul_salidas=$sql_consul_salidas." and dv.id_division=14	and de.id_departamento=263";
					$sql_consul_salidas=$sql_consul_salidas." and dv.id_division=".$datos_div["id_division"]." and de.id_departamento=".$datos_dep["id_departamento"];
						
						$sql_consul_salidas=$sql_consul_salidas." and tlob.EntradaSalida=1
						and tlob.IdTerminal<>15
						and tlob.IdTerminal<>16
						and tlob.IdTerminal<>17
						and tlob.IdTerminal<>18
						and tlob.IdTerminal<>19";
			
						//SI EL MES ACTUAL ES ENERO, SE CONSULTA LA INFORMACION DE DICIEMBRE DEL AÑO ANTERIOR
						if(date("m")==1) //para colocar 
							$sql_consul_salidas=$sql_consul_salidas." and MONTH( tlob.Fecha)=12 and year(tlob.Fecha)=year(getdate())-1";
						else
							$sql_consul_salidas=$sql_consul_salidas." and MONTH( tlob.Fecha) = month(getdate())-1 and year(tlob.Fecha)=year(getdate())";
			
						$sql_consul_salidas=$sql_consul_salidas." ORDER BY tlob.Fecha,Horas";
			
						$cur_consul_salidas=mssql_query($sql_consul_salidas);
			
						if(trim($cur_consul_salidas)=="")
							$error="si";
			
			echo 	"<br><br><br><br> /////////////***************************************************************************************************************************************";
			echo $sql_consul_salidas." ----  error ".$error." ".mssql_get_last_message()." --  registros ".mssql_num_rows($cur_consul_salidas)."<br><br><br>";
			
			
			$conexion = conectar("HojaDeTiempo");
			$ff=0;
						while(($datos_salidas=mssql_fetch_array($cur_consul_salidas))and($error=="no"))
						{
			$ff++;
							$sql_insert="insert into HojaDeTiempo.dbo.TMSalidas (IdRegistro,unidad,id_division,id_departamento,IdTerminal,Torniquete,NoTarjeta,NoDocumento,FechaCompleta,Fecha,Hora,EntradaSalida)values(";
							$sql_insert=$sql_insert." ".$datos_salidas["IdRegistro"].",".$datos_salidas["unidad"].",".$datos_salidas["id_division"].",".$datos_salidas["id_departamento"].",".$datos_salidas["IdTerminal"].",'".$datos_salidas["Torniquete"]."',".$datos_salidas["NoTarjeta"].",".$datos_salidas["NoDocumento"].",'".$datos_salidas["FechaCompleta"]."','".$datos_salidas["Fecha"]."','".$datos_salidas["Horas"]."','".$datos_salidas["EntradaSalida"]."')";
							$cur_insert=mssql_query($sql_insert);
			
			echo 	"$ff *** ".$sql_insert." ----  ".mssql_get_last_message()." --- ".$error." <br><br>";
			
							if(trim($cur_insert)=="")
								$error="si";				
						}
		}
	}
}
$hora = getdate(time());
            echo ( "HORA DE FINALIZACION TOTAL ".$hora["hours"] . ":" . $hora["minutes"] . ":" . $hora["seconds"] ); 
//SI NO SE PRESENTARON ERRORES
if(($error=="no")and(trim($cur_consul_entradas)!="")and(trim($cur_consul_salidas)!=""))
{
	echo " bien";
	mssql_query("COMMIT TRANSACTION");
}
else
{
	echo " error";
	mssql_query("ROLLBACK TRANSACTION");
}

			
?>