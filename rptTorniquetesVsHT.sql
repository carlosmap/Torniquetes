--lista todos os registros de ingresos y salidas en un periodo determinaso
--0=Ingreso
--1=Salida

--	******************************
--	Con Usuarios
SELECT 
    DEP.Descripcion Dependencia
    , u.unidad
    , ter.Descripcion Torniquete
    , tlob.NoTarjeta
    , usu.NoDocumento
    , usu.Nombres, usu.Apellidos
    , tlob.Fecha FechaCompleta
    , CONVERT(VARCHAR(10), tlob.Fecha, 111) Fecha
    , CONVERT(VARCHAR(10), tlob.Fecha, 114) Hora
    , tlob.EntradaSalida
FROM
     TB_LOG_TERMINAL tlob
     , TB_USUARIO_TARJETAS_ASIGNADAS tUsu
     , TB_EMPLEADO usu
     , TB_TERMINAL ter
     , TB_DEPENDENCIA dep
     , HojaDeTiempo.dbo.Usuarios u
WHERE
    ( YEAR( tlob.Fecha ) = 2012 ) AND ( MONTH( tlob.Fecha ) in (9) )
    AND tUsu.NoTarjeta = tlob.NoTarjeta
    AND usu.IdEmpleado = tUsu.IdUsuarioSistema
    AND ter.IdTerminal = tlob.IdTerminal
    AND dep.IdDependencia = usu.IdDependencia
	AND u.numDocumento = CAST( usu.NoDocumento AS VARCHAR(20) )
ORDER BY tlob.Fecha

--	******************************
--	Con Departamento
SELECT 
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
     TB_LOG_TERMINAL tlob
     , TB_USUARIO_TARJETAS_ASIGNADAS tUsu
     , TB_EMPLEADO usu
     , TB_TERMINAL ter
     , TB_DEPENDENCIA dep
     , HojaDeTiempo.dbo.Usuarios u
     , HojaDeTiempo.dbo.Departamentos de

WHERE
    ( YEAR( tlob.Fecha ) = 2012 ) AND ( MONTH( tlob.Fecha ) in (9, 10, 11) )
    AND tUsu.NoTarjeta = tlob.NoTarjeta
    AND usu.IdEmpleado = tUsu.IdUsuarioSistema
    AND ter.IdTerminal = tlob.IdTerminal
    AND dep.IdDependencia = usu.IdDependencia
	AND u.numDocumento = CAST( usu.NoDocumento AS VARCHAR(20) )
    AND u.id_departamento = de.id_departamento
ORDER BY tlob.Fecha

--	******************************
--	Con Division
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
    , CONVERT(VARCHAR(10), tlob.Fecha, 114) Hora
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
    ( YEAR( tlob.Fecha ) = 2012 ) AND ( MONTH( tlob.Fecha ) in (9) )
    AND tUsu.NoTarjeta = tlob.NoTarjeta
    AND usu.IdEmpleado = tUsu.IdUsuarioSistema
    AND ter.IdTerminal = tlob.IdTerminal
    AND dep.IdDependencia = usu.IdDependencia
	AND u.numDocumento = CAST( usu.NoDocumento AS VARCHAR(20) )
    AND u.id_departamento = de.id_departamento
    AND de.id_division = dv.id_division
ORDER BY tlob.Fecha

--	******************************
--	Con Dependencia
SELECT 
    DEP.Descripcion Dependencia
    , u.unidad
	, dp.nombre Dependencia
	, dv.nombre Division
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
     TB_LOG_TERMINAL tlob
     , TB_USUARIO_TARJETAS_ASIGNADAS tUsu
     , TB_EMPLEADO usu
     , TB_TERMINAL ter
     , TB_DEPENDENCIA dep
     , HojaDeTiempo.dbo.Usuarios u
     , HojaDeTiempo.dbo.Departamentos de
     , HojaDeTiempo.dbo.Divisiones dv
     , HojaDeTiempo.dbo.Dependencias dp
WHERE
    ( YEAR( tlob.Fecha ) = 2012 ) AND ( MONTH( tlob.Fecha ) in (9, 10, 11) )
    AND tUsu.NoTarjeta = tlob.NoTarjeta
    AND usu.IdEmpleado = tUsu.IdUsuarioSistema
    AND ter.IdTerminal = tlob.IdTerminal
    AND dep.IdDependencia = usu.IdDependencia
	AND u.numDocumento = CAST( usu.NoDocumento AS VARCHAR(20) )
    AND u.id_departamento = de.id_departamento
    AND de.id_division = dv.id_division
    AND dv.id_dependencia = dp.id_dependencia
ORDER BY tlob.Fecha


--	******************************
--	Con Categoria
SELECT 
    DEP.Descripcion Dependencia
    , u.unidad
	, dp.nombre Dependencia
	, dv.nombre Division
	, de.nombre Departamento
	, ct.nombre Categoria
    , ter.Descripcion Torniquete
    , tlob.NoTarjeta
    , usu.NoDocumento
    , usu.Nombres, usu.Apellidos
    , tlob.Fecha FechaCompleta
    , CONVERT(VARCHAR(10), tlob.Fecha, 111) Fecha
    , CONVERT(VARCHAR(10), tlob.Fecha, 114) Hora
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
     , HojaDeTiempo.dbo.Dependencias dp
     , HojaDeTiempo.dbo.Categorias ct
WHERE
    ( YEAR( tlob.Fecha ) = 2012 ) AND ( MONTH( tlob.Fecha ) in (9, 10, 11) )
    AND tUsu.NoTarjeta = tlob.NoTarjeta
    AND usu.IdEmpleado = tUsu.IdUsuarioSistema
    AND ter.IdTerminal = tlob.IdTerminal
    AND dep.IdDependencia = usu.IdDependencia
	AND u.numDocumento = CAST( usu.NoDocumento AS VARCHAR(20) )
    AND u.id_departamento = de.id_departamento
    AND de.id_division = dv.id_division
    AND dv.id_dependencia = dp.id_dependencia
    AND ct.id_categoria = u.id_categoria
ORDER BY tlob.Fecha


--	***************************************
--	Lista un registro por persona y por día
SELECT DISTINCT
	DEP.Descripcion Dependencia
	, u.unidad, dp.nombre Dependencia, dv.nombre Division, de.nombre Departamento, ct.nombre Categoria
	, EMP.Nombres, EMP.Apellidos
    , A.fecha
	, MIN(A.horaIngreso) Ingreso, MAX(B.horaSalida) Salida
    , A.NoTarjeta, A.IdUsuarioSistema, B.NoTarjeta

FROM
     (
         SELECT CONVERT(VARCHAR(10), fecha, 111) fecha, CONVERT(VARCHAR(10), Fecha, 114) horaIngreso, NoTarjeta, IdUsuarioSistema, EntradaSalida
         FROM TB_LOG_TERMINAL
         WHERE (YEAR(Fecha) = 2012) AND (MONTH(Fecha) in (9, 10 ) )--, 11) ) 
         AND EntradaSalida = 0 
         --AND (NoTarjeta = '2365557997')
     ) A
     ,(
         SELECT CONVERT(VARCHAR(10), fecha, 111) fechaSalida, CONVERT(VARCHAR(10), Fecha, 114) horaSalida, NoTarjeta, IdUsuarioSistema, EntradaSalida
         FROM TB_LOG_TERMINAL
         WHERE (YEAR(Fecha) = 2012) 
         AND (MONTH(Fecha) in (9, 10 ) )--, 11) ) 
         AND EntradaSalida = 1 
         --AND (NoTarjeta = '2365557997')
     ) B
     , TB_EMPLEADO EMP
     , TB_DEPENDENCIA DEP
--/*
     , HojaDeTiempo.dbo.Usuarios u
     , HojaDeTiempo.dbo.Departamentos de
     , HojaDeTiempo.dbo.Dependencias dp
     , HojaDeTiempo.dbo.Divisiones dv
     , HojaDeTiempo.dbo.Categorias ct
--*/
WHERE
     A.fecha = B.fechaSalida 
     AND EMP.NoTarjeta = A.NoTarjeta 
     AND EMP.NoTarjeta = B.NoTarjeta 
     AND DEP.IdDependencia = EMP.IdDependencia
--/*
     AND u.numDocumento = CAST( EMP.NoDocumento AS VARCHAR(20) )
     AND u.id_departamento = de.id_departamento
     AND de.id_division = dv.id_division
     AND dv.id_dependencia = dp.id_dependencia
     AND ct.id_categoria = u.id_categoria
     AND u.unidad in( select unidad from HojaDeTiempo.dbo.Usuarios where unidad = 18120 )--AND u.fechaRetiro IS NULL
     
--*/ 
rocio gomez 
select * from HojaDeTiempo.dbo.Usuarios where email like '%sandy%'
	--AND EMP.NoTarjeta = '2365557997'
	
GROUP BY  
A.fecha, A.NoTarjeta, A.IdUsuarioSistema, DEP.Descripcion, EMP.Nombres, EMP.Apellidos, B.NoTarjeta, B.fechaSalida
, de.nombre, dv.nombre, dp.nombre, ct.nombre, u.unidad


select COUNT(*) from HojaDeTiempo.dbo.TMEntradas where YEAR(FechaCompleta)=2012 and MONTH(FechaCompleta)=12
select COUNT(*) from TB_LOG_TERMINAL where Descripcion='  TRANSACCION EXITOSA.   ' and YEAR(Fecha)=2012 and MONTH(Fecha)=12 and EntradaSalida=0

select * from HojaDeTiempo.dbo.TMEntradas where YEAR(FechaCompleta)=2012 and MONTH(FechaCompleta)=12
