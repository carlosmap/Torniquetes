
-----REGISTROS GENERADOS POR LAS TARJETAS DE LOS USUARIOS QUE ESTAN EN EL SMART (TB_EMPLEADO) Y NO SE ENCUENTRAN EN LA H.T. (usuarios)
		SELECT 
			tlob.*	
		FROM
			 SmartAccessControl.dbo.TB_LOG_TERMINAL tlob
			 , SmartAccessControl.dbo.TB_EMPLEADO usu
			 , SmartAccessControl.dbo.TB_TERMINAL ter
			 , SmartAccessControl.dbo.TB_DEPENDENCIA dep
		--	 , HojaDeTiempo.dbo.Usuarios u
		/*	 , HojaDeTiempo.dbo.Departamentos de
			 , HojaDeTiempo.dbo.Divisiones dv
		*/	 
		WHERE
			usu.NoTarjeta=tlob.NoTarjeta
			AND ter.IdTerminal = tlob.IdTerminal
			AND dep.IdDependencia = usu.IdDependencia
		--AND LTRIM(RTRIM(u.numDocumento )) = LTRIM(RTRIM( CAST( usu.NoDocumento AS VARCHAR) ))
		--AND CAST( u.numDocumento AS NUMERIC ) = usu.NoDocumento 
		/*
			AND u.id_departamento = de.id_departamento
			AND de.id_division = dv.id_division 			
		*/	
			AND tlob.Autorizada=1
			AND tlob.TranBatch=0
		--	and u.fechaRetiro is null
			
			and tlob.IdTerminal<>15
			and tlob.IdTerminal<>16
			and tlob.IdTerminal<>17
			and tlob.IdTerminal<>18
			and tlob.IdTerminal<>19								
			
			and MONTH( tlob.Fecha)=5 and year(tlob.Fecha)=2015 and day(tlob.Fecha)=8
			and tlob.NoTarjeta in (
				2369106896	,
				1037257734	,
				2365430589	,
				2325577728	,
				2364772461	,
				1037321318	,
				2369189344	,
				2324875680	,
				1036886870	,
				2369119824	,
				1027023389	,
				1036900774	,
				1036933126	,
				2369289376	,
				1036851862	,
				1036817958	,
				1036995702	,
				2216214477	,
				1036945846	,
				2216055421	
			)
