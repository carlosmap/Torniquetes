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
            AND de.id_division = dv.id_division

and dv.id_division=14    and de.id_departamento=263
and tlob.EntradaSalida=1
            and tlob.IdTerminal<>15
            and tlob.IdTerminal<>16
            and tlob.IdTerminal<>17
            and tlob.IdTerminal<>18
            and tlob.IdTerminal<>19
and MONTH( tlob.Fecha) = month(getdate())-1 and year(tlob.Fecha)=year(getdate())
 ORDER BY tlob.Fecha,Horas


--lista todos os registros de ingresos y salidas en un periodo determinaso
--0=Ingreso
--1=Salida
SELECT
     ter.Descripcion Torniquete
     , dep.Descripcion Dependencia
     , tlob.NoTarjeta
     , usu.NoDocumento
     , usu.Nombres, usu.Apellidos
     , tlob.Fecha FechaCompleta
     , CONVERT(VARCHAR(10), tlob.Fecha, 111) Fecha --dateformat( 'yyyy/mm/dd', tlob.Fecha ) Fecha
     , CONVERT(VARCHAR(10), tlob.Fecha, 114) Hora--dateformat( 'yyyy/mm/dd', tlob.Fecha ) Fecha
     , tlob.EntradaSalida 
FROM
     TB_LOG_TERMINAL tlob
     , TB_USUARIO_TARJETAS_ASIGNADAS tUsu
     , TB_EMPLEADO usu
     , TB_TERMINAL ter
     , TB_DEPENDENCIA dep
WHERE
     (YEAR(tlob.Fecha) = 2012) AND (MONTH(tlob.Fecha) in (9) ) 
     -- AND(DAY(tlob.Fecha) = 14)
     AND tUsu.NoTarjeta = tlob.NoTarjeta 
     AND usu.IdEmpleado = tUsu.IdUsuarioSistema 
     AND ter.IdTerminal = tlob.IdTerminal
     AND dep.IdDependencia = usu.IdDependencia
     -- AND usu.NoTarjeta = 2365557997
     -- AND usu.IdEstadoUsuario = 2
ORDER BY tlob.Fecha



--Lista un registro por persona y por día
SELECT DISTINCT
     DEP.Descripcion Dependencia, EMP.Nombres, EMP.Apellidos
     , A.fecha
     , MIN(A.horaIngreso) Ingreso
     , MAX(B.horaSalida) Salida
     , A.NoTarjeta, A.IdUsuarioSistema
     , B.NoTarjeta
FROM
     (
         SELECT CONVERT(VARCHAR(10), fecha, 111) fecha, CONVERT(VARCHAR(10), Fecha, 114) horaIngreso, NoTarjeta, IdUsuarioSistema, EntradaSalida
         FROM TB_LOG_TERMINAL
         WHERE (YEAR(Fecha) = 2012) AND (MONTH(Fecha) in (9, 10, 11) ) 
         AND EntradaSalida = 0 
         --AND (NoTarjeta = 2201797773)
     ) A
     ,(
         SELECT CONVERT(VARCHAR(10), fecha, 111) fechaSalida, CONVERT(VARCHAR(10), Fecha, 114) horaSalida, NoTarjeta, IdUsuarioSistema, EntradaSalida
         FROM TB_LOG_TERMINAL
         WHERE (YEAR(Fecha) = 2012) 
         AND (MONTH(Fecha) in (9, 10, 11) ) 
         AND EntradaSalida = 1 
         --AND (NoTarjeta = 2201797773)
     ) B
     , TB_EMPLEADO EMP
     , TB_DEPENDENCIA DEP
WHERE
     A.fecha = B.fechaSalida 
     AND EMP.NoTarjeta = A.NoTarjeta 
     AND EMP.NoTarjeta = B.NoTarjeta 
     AND DEP.IdDependencia = EMP.IdDependencia 
     --AND EMP.NoTarjeta = '2201797773'
GROUP BY  A.fecha, A.NoTarjeta, A.IdUsuarioSistema, DEP.Descripcion, EMP.Nombres, EMP.Apellidos, B.NoTarjeta, B.fechaSalida



