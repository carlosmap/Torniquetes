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



//Establecer la conexión a la base de datos
$conexion = conectar("SmartAccessControl");

$error="no";

mssql_query("BEGIN TRANSACTION");

//CONSULTA LOSS INGRESOS
$sql_consul_entradas="
		SELECT 
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
			AND de.id_division = dv.id_division ";

			//TEMPORAL PARA QUITAR
	//		$sql_consul_entradas=$sql_consul_entradas." and dv.id_division=14	and de.id_departamento=263";
			$sql_consul_entradas=$sql_consul_entradas." and dv.id_division=14";
			
			$sql_consul_entradas=$sql_consul_entradas." and tlob.EntradaSalida=0
			and tlob.IdTerminal<>15
			and tlob.IdTerminal<>16
			and tlob.IdTerminal<>17
			and tlob.IdTerminal<>18
			and tlob.IdTerminal<>19";

			//SI EL MES ACTUAL ES ENERO, SE CONSULTA LA INFORMACION DE DICIEMBRE DEL AÑO ANTERIOR
			if(date("m")==1)
				$sql_consul_entradas=$sql_consul_entradas."and MONTH( tlob.Fecha) = 12 and year(tlob.Fecha)=year(getdate())-1";
			else			
				$sql_consul_entradas=$sql_consul_entradas." and MONTH( tlob.Fecha) = month(getdate())-1 and year(tlob.Fecha)=year(getdate()) ";

			$sql_consul_entradas=$sql_consul_entradas."ORDER BY tlob.Fecha,Horas";

			$cur_consul_entradas=mssql_query($sql_consul_entradas);

			if(trim($cur_consul_entradas)=="")
				$error="si";

echo 	$sql_consul_entradas." ---- error ".$error." --- ".mssql_get_last_message()." registros ".mssql_num_rows($cur_consul_entradas)." <br><br>";

$conexion = conectar("HojaDeTiempo");
		
			while(($datos_entradas=mssql_fetch_array($cur_consul_entradas))and($error=="no"))
			{

				$sql_insert="insert into HojaDeTiempo.dbo.TMEntradas (IdDependencia,Dependencia,unidad,id_division,Division,id_departamento,Departamento,IdTerminal,Torniquete,NoTarjeta,NoDocumento,Nombres,Apellidos,FechaCompleta,Fecha,Hora,EntradaSalida)values(";
				$sql_insert=$sql_insert."".$datos_entradas["IdDependencia"].",'".$datos_entradas["Dependencia"]."',".$datos_entradas["unidad"].",".$datos_entradas["id_division"].",'".$datos_entradas["Division"]."',".$datos_entradas["id_departamento"].",'".$datos_entradas["Departamento"]."',".$datos_entradas["IdTerminal"].",'".$datos_entradas["Torniquete"]."',".$datos_entradas["NoTarjeta"].",".$datos_entradas["NoDocumento"].",'".$datos_entradas["Nombres"]."','".$datos_entradas["Apellidos"]."','".$datos_entradas["FechaCompleta"]."','".$datos_entradas["Fecha"]."','".$datos_entradas["Horas"]."','".$datos_entradas["EntradaSalida"]."')";

				$cur_insert=mssql_query($sql_insert);
echo 	$sql_insert." ----  ".mssql_get_last_message()." --- ".$error." <br><br>";
				if(trim($cur_insert)=="")
					$error="si";
			}
echo "<br><br><br><br>**********************************************************************************---------************************************************<br>";
//si no se presentaron errores se ejecuta el sigunete select


if($error=="no")
{
$conexion = conectar("SmartAccessControl");

	//CONSULTA LAS SALIDAS
	$sql_consul_salidas="
		SELECT 
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
			AND de.id_division = dv.id_division ";

			//TEMPORAL PARA QUITAR
//			$sql_consul_salidas=$sql_consul_salidas." and dv.id_division=14	and de.id_departamento=263";
		$sql_consul_entradas=$sql_consul_entradas." and dv.id_division=14";
			
			$sql_consul_salidas=$sql_consul_salidas." and tlob.EntradaSalida=1
			and tlob.IdTerminal<>15
			and tlob.IdTerminal<>16
			and tlob.IdTerminal<>17
			and tlob.IdTerminal<>18
			and tlob.IdTerminal<>19";

			//SI EL MES ACTUAL ES ENERO, SE CONSULTA LA INFORMACION DE DICIEMBRE DEL AÑO ANTERIOR
			if(date("m")==1)
				$sql_consul_salidas=$sql_consul_salidas." and MONTH( tlob.Fecha) = 12 and year(tlob.Fecha)=year(getdate())-1";
			else
				$sql_consul_salidas=$sql_consul_salidas." and MONTH( tlob.Fecha) = month(getdate())-1 and year(tlob.Fecha)=year(getdate())";

			$sql_consul_salidas=$sql_consul_salidas." ORDER BY tlob.Fecha,Horas";

			$cur_consul_salidas=mssql_query($sql_consul_salidas);

			if(trim($cur_consul_salidas)=="")
				$error="si";

echo 	"<br><br><br><br> /////////////***************************************************************************************************************************************";
echo $sql_consul_salidas." ----  error ".$error." ".mssql_get_last_message()." --  registros ".mssql_num_rows($cur_consul_salidas)."<br><br><br>";


$conexion = conectar("HojaDeTiempo");

			while(($datos_salidas=mssql_fetch_array($cur_consul_salidas))and($error=="no"))
			{
				$sql_insert="insert into HojaDeTiempo.dbo.TMSalidas  (IdDependencia,Dependencia,unidad,id_division,Division,id_departamento,Departamento,IdTerminal,Torniquete,NoTarjeta,NoDocumento,Nombres,Apellidos,FechaCompleta,Fecha,Hora,EntradaSalida)values(";
				$sql_insert=$sql_insert."".$datos_salidas["IdDependencia"].",'".$datos_salidas["Dependencia"]."',".$datos_salidas["unidad"].",".$datos_salidas["id_division"].",'".$datos_salidas["Division"]."',".$datos_salidas["id_departamento"].",'".$datos_salidas["Departamento"]."',".$datos_salidas["IdTerminal"].",'".$datos_salidas["Torniquete"]."',".$datos_salidas["NoTarjeta"].",".$datos_salidas["NoDocumento"].",'".$datos_salidas["Nombres"]."','".$datos_salidas["Apellidos"]."','".$datos_salidas["FechaCompleta"]."','".$datos_salidas["Fecha"]."','".$datos_salidas["Horas"]."','".$datos_salidas["EntradaSalida"]."')";

				$cur_insert=mssql_query($sql_insert);

echo 	$sql_insert." ----  ".mssql_get_last_message()." --- ".$error." <br><br>";

				if(trim($cur_insert)=="")
					$error="si";				
			}
}

//SI NO SE PRESENTARON ERRORES
if(($error=="no")and(trim($cur_consul_entradas)!="")and(trim($cur_consul_salidas)!=""))
{
	echo "bien";
	mssql_query("COMMIT TRANSACTION");
}
else
{
	echo "error";
	mssql_query("ROLLBACK TRANSACTION");
}

			
?>