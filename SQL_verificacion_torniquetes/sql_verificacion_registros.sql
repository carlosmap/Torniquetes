---CANTIDAD TOTAL DE REGISTROS DE INGRESOS Y SALIDAS
-- (4434)
		select COUNT(*),day(FechaTerminal) dia from TB_LOG_TERMINAL tlob where 
								tlob.Autorizada=1
								AND tlob.TranBatch=0
								and tlob.IdTerminal<>15
								and tlob.IdTerminal<>16
								and tlob.IdTerminal<>17
								and tlob.IdTerminal<>18
								and tlob.IdTerminal<>19								

								and year(FechaTerminal)=2015 and MONTH(FechaTerminal)=5
								and day(FechaTerminal)=8
		group by day(FechaTerminal)

--CANTIDAD DE REGISTROS GENERADOS CON LAS TARJETAS DE INVITADOS
--(116)--(112)
		select * --COUNT(*),day(FechaTerminal) dia 
		from TB_LOG_TERMINAL tlob where 
			tlob.Autorizada=1
			AND tlob.TranBatch=0
			and tlob.IdTerminal<>15
			and tlob.IdTerminal<>16
			and tlob.IdTerminal<>17
			and tlob.IdTerminal<>18
			and tlob.IdTerminal<>19								

			and year(FechaTerminal)=2015 and MONTH(FechaTerminal)=5
			and day(FechaTerminal)=8
			AND NoTarjeta NOT IN (
				select  NoTarjeta from SmartAccessControl.dbo.TB_EMPLEADO								
			)
--group by day(FechaTerminal)

---CANTIDAD DE REGISTROS EN LA TABLAS TEMPORALES
---(4184) -- (4210)
		select COUNT(*) ,day(FechaCompleta) dia  from HojaDeTiempo.dbo.TMEntradas where 
		--NoTarjeta ='2364676749' and 
		year(FechaCompleta)=2015 and 
		MONTH(FechaCompleta)=5 -- and unidad=900241
		and DAY(FechaCompleta)=8
		--order by day(FechaCompleta)
		group by day(FechaCompleta)

		--(4090) -- (4030)
		select COUNT(*), day(FechaCompleta) dia from HojaDeTiempo.dbo.TMSalidas 
		where 
		--NoTarjeta ='2364676749' and 
		year(FechaCompleta)=2015 and 
		MONTH(FechaCompleta)=5 -- and unidad=900241
		and DAY(FechaCompleta)=8
		--order by day(FechaCompleta)
		group by day(FechaCompleta)			

-----CONSULTA GENERADA PARA LA ALIMENTACION DE LAS TABLAS TEMPORALES
--(4159)-(4322)-- (4129)
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
								
							--	distinct(usu.NoDocumento)
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
							AND LTRIM(RTRIM(u.numDocumento )) = LTRIM(RTRIM( CAST( usu.NoDocumento AS VARCHAR) ))
							
							
								AND u.id_departamento = de.id_departamento
								AND de.id_division = dv.id_division 			
								
								AND tlob.Autorizada=1
								AND tlob.TranBatch=0
								and u.fechaRetiro is null
								
								and tlob.IdTerminal<>15
								and tlob.IdTerminal<>16
								and tlob.IdTerminal<>17
								and tlob.IdTerminal<>18
								and tlob.IdTerminal<>19								
								
and MONTH( tlob.Fecha)=5 and year(tlob.Fecha)=2015 and day(tlob.Fecha)=8

							ORDER BY tlob.Fecha,Horas
								
								