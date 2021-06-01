--TOTAL DE REGISTROS DE INGRESOS Y SALIDAS, INGRESADOS EN TMEntradas Y TMSalidas POR UNO DE LOS DIAS DE UN MES
select ano,mes,dia, SUM(entradas_salidas) cantidad_entradas_salidas from (
	select YEAR(Fecha_registro) ano,MONTH(Fecha_registro) mes,DAY(Fecha_registro) dia , COUNT(*) entradas_salidas from TMEntradas
	where YEAR(Fecha_registro)=2015  and MONTH(Fecha_registro)=11
	group by YEAR(Fecha_registro),MONTH(Fecha_registro),DAY(Fecha_registro)
	union
	select YEAR(Fecha_registro) ano,MONTH(Fecha_registro) mes,DAY(Fecha_registro) dia , COUNT(*) entradas_salidas  from TMSalidas
	where YEAR(Fecha_registro)=2015  and MONTH(Fecha_registro)=11
	group by YEAR(Fecha_registro),MONTH(Fecha_registro),DAY(Fecha_registro)
)A
group by ano,mes,dia
order by dia

-------------------------------------------
--CANTIDAD DE REGISTROS DE INGRESOS Y SALIDAS, INGRESADOS EN TMEntradas Y TMSalidas POR UNO DE LOS DIAS DE UN MES
	select YEAR(Fecha_registro) ano,MONTH(Fecha_registro) mes,DAY(Fecha_registro) dia , COUNT(*) entradas_salidas,'Entrada' tipo from TMEntradas
	where YEAR(Fecha_registro)=2015  and MONTH(Fecha_registro)=11
	group by YEAR(Fecha_registro),MONTH(Fecha_registro),DAY(Fecha_registro)
	union
	select YEAR(Fecha_registro) ano,MONTH(Fecha_registro) mes,DAY(Fecha_registro) dia , COUNT(*) entradas_salidas,'Salida' tipo  from TMSalidas
	where YEAR(Fecha_registro)=2015  and MONTH(Fecha_registro)=11
	group by YEAR(Fecha_registro),MONTH(Fecha_registro),DAY(Fecha_registro)
order by dia