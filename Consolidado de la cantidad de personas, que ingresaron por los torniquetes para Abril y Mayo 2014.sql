select COUNT(*),fecha from(
    select distinct(NoTarjeta),fecha from(
        --69967
        
        select NoTarjeta,  CONVERT(date,FechaCompleta,104)fecha from  HojaDeTiempo.dbo.TMEntradas
        where YEAR(FechaCompleta)=2014 --and DAY(FechaTerminal)=13
        and MONTH(FechaCompleta) in (5)
        --and DATEPART(hh,FechaTerminal) between 8 and 10        
    )T1
    group by NoTarjeta,fecha
)T2
group by fecha
order by fecha 


select COUNT(*),fecha from(
    select distinct(NoTarjeta),fecha from(
        --69967
        select NoTarjeta,  CONVERT(date,FechaTerminal,104)fecha from  SmartAccessControl.dbo.TB_LOG_TERMINAL
        where YEAR(FechaTerminal)=2014 --and DAY(FechaTerminal)=13
        and MONTH(FechaTerminal) in (4,5)
        --and DATEPART(hh,FechaTerminal) between 8 and 10
        and EntradaSalida=0
        and SmartAccessControl.dbo.TB_LOG_TERMINAL.IdTerminal<>15
        and SmartAccessControl.dbo.TB_LOG_TERMINAL.IdTerminal<>16
        and SmartAccessControl.dbo.TB_LOG_TERMINAL.IdTerminal<>17
        and SmartAccessControl.dbo.TB_LOG_TERMINAL.IdTerminal<>18
        and SmartAccessControl.dbo.TB_LOG_TERMINAL.IdTerminal<>19
    )T1
    group by NoTarjeta,fecha
)T2
group by fecha
order by fecha 

