<?php
session_start();
include("../../verificaRegistro2.php");
include('../../conectaBD.php');
mssql_select_db('HojaDeTiempo');
?>

<?

//Establecer la conexión a la base de datos
$conexion = conectar();

if($recarga==2)
{
echo "ingersoooo <br><br>";
	//consulta las personas que hacen parte de la division y el departamento
	$sql_consul_perso=" select distinct(unidad),Nombres,Apellidos,NoTarjeta,Departamento from HojaDeTiempo.dbo.TMEntradas";
	$sql_consul_perso=$sql_consul_perso."	 where id_division=".$division;

	if(trim($uni)!="")
		$sql_consul_perso=$sql_consul_perso." and unidad=".$uni;

	if(trim($departamento)!="")
		$sql_consul_perso=$sql_consul_perso." and id_departamento=".$departamento;

	$sql_consul_perso=$sql_consul_perso." and YEAR(FechaCompleta)=".$ano;
	$sql_consul_perso=$sql_consul_perso." and MONTH(FechaCompleta)=".$mess;



	$cur_perso=mssql_query($sql_consul_perso);

echo $sql_consul_perso." --- ".mssql_get_last_message()." *** ".mssql_num_rows($cur_perso)." <br><br>";
}

?>
<html>
<head>
<title>Entradas y Salidas</title>
<LINK REL="stylesheet" HREF="../../css/estilo.css" TYPE="text/css">
<script>
var nav4 = window.Event ? true : false;
function acceptNum(evt){   
var key = nav4 ? evt.which : evt.keyCode;   
return (key <= 13 || (key>= 48 && key <= 57));
}

function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}

function valida()
{

	var e="n",msg="";
	if(document.form1.division.value=="")
	{
		e="s";
		msg="Seleccione una division.\n ";
	}
//*
	if(document.form1.departamento.value=="")
	{
		e="s";
		msg=msg+"Seleccione un departamento.\n ";
	}
//*/
	if(document.form1.mess.value=="0")
	{
		e="s";

		msg=msg+"Seleccione un mes.\n ";
	}
	if(document.form1.ano.value=="")
	{
		e="s";
		msg=msg+"Seleccione un año.\n ";
	}
	if(e=="s")
	{
		alert(msg);
	}
	else
	{
		document.form1.recarga.value="2";
		document.form1.submit();
	}
}
//-->
function MM_callJS(jsStr) { //v2.0
  return eval(jsStr)
}
</script>
</head>
<body leftmargin="0" topmargin="0" rightmargin="0" bottommargin="0"  bgcolor="E6E6E6">
	<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><? include("bannerArriba.php") ; ?></td>
  </tr>
</table>
    <table width="100%"  border="0" cellspacing="1" cellpadding="0">
      <tr>
        <td width="15%" height="35" class="FichaAct">Detalle</td><!-- anio=2012&mes=12&departamento=3 -->
        <td width="15%" height="35" class="FichaInAct">
        <?	if( ( $ano != "" ) && ( $mes != "" ) && ( $departamento != "" ) && ( $division != "" ) ){		?>
		        <a href="rpt02.php?anio=<?= $ano ?>&mes=<?= $mes ?>&division=<?= $division ?>&departamento=<?= $departamento ?>" class="FichaInAct1" >Grafica</a>
        <?	}	else {	?>
		        <a href="#" class="FichaInAct1" >Grafica</a>
        <?	}		?>
        </td>
        <td>&nbsp;</td>
      </tr>
    </table>
    <table width="100%"  border="0" cellspacing="1" cellpadding="0">
      <tr>
        <td>&nbsp;</td>
      </tr>
    </table>
    <form name="form1">
        <table width="60%"  border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td bgcolor="#FFFFFF">
            
            <table width="100%"  border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td class="TituloUsuario">Criterios de consulta </td>
              </tr>
            </table>
            
              <table width="100%"  border="0" cellspacing="1" cellpadding="0">
                <tr>
                  <td width="30%" class="TituloTabla">Division</td>
                  <td class="TxtTabla">
                        <select name="division" class="CajaTexto" id="division" onChange="document.form1.submit();">
                            <option value="">:::Selecciones Division:::</option>
                        <?	$sqlDv = "select nombre, id_division from HojaDeTiempo.dbo.Divisiones where estadoDiv = 'A'";
                            $qryDv = mssql_query( $sqlDv );		
                            while( $rwDv = mssql_fetch_array( $qryDv ) ){
                                $sel="";
                                if($division==$rwDv[id_division])
                                    $sel="selected";
                        ?>
                                <option value="<?= $rwDv[id_division] ?>" <?php echo $sel ?> ><?= $rwDv[nombre] ?></option>
                        <?	}	?>
                        </select>
                  </td>
                </tr>
                <tr>
                  <td class="TituloTabla">Departamento</td>
                  <td class="TxtTabla">
                        <select name="departamento" class="CajaTexto" id="departamento">
                          <option value="">:::Seleccione Departamento:::</option>
                          <?
                            if(trim($division) != "" )	
                            {
                                $sqlDep = "select nombre, id_departamento from HojaDeTiempo.dbo.Departamentos where estadoDpto = 'A' ";
                                $sqlDep .= "AND id_division = ".$division;
                                $qryDep = mssql_query( $sqlDep );
                                while( $rwDp = mssql_fetch_array( $qryDep ) ){
                                    $sel="";
                                    if($departamento==$rwDp[id_departamento])
                                        $sel="selected";
                              ?>
                              <option value="<?= $rwDp[id_departamento] ?>" <? echo  $sel; ?> ><?= $rwDp[nombre] ?></option>
                              <?
                                }
                            }
                              ?>
                        </select>
                    </td>					
                </tr>
				 <tr>
                  <td class="TituloTabla">Unidad</td>
                  <td class="TxtTabla"><input type="text" name="uni" id="uni" value="<? echo $uni; ?>" class="CajaTexto" onKeyPress="return acceptNum(event)" ></td>
				</tr>
                <tr>
                    <td  class="TituloTabla">Fecha</td>
                    <td  class="TituloTabla">
                        <table width="100%" class="TxtTabla" >
                            <tr class="TxtTabla">
                              <td class="TxtTabla">Mes</td>
                              <td>
                              <select name="mess" class="CajaTexto" id="mess">
                                <?
                                    $mes = array( ':::Seleccione Mes:::', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre' );
                                    $m = 0;
                                    while( $m < count( $mes ) ){
                                        $sel="";
                                        if($mess==$m)
                                            $sel="selected";
                                ?>
                                    <option value="<?= $m ?>" <?php echo $sel; ?> <? echo  $sel; ?> ><?= $mes[$m] ?></option>
                                <?	$m++;
                                  }	
                                ?>
                            </select>
                            </td>
                            <td class="TxtTabla">A&ntilde;o</td>
                            <td>
                                <select name="ano" id="ano" class="CajaTexto">
                                    <option value="">:::Seleccion A&ntilde;o:::</option>
                                    <?
										$sql_fech="select distinct(year(FechaCompleta))as anos from HojaDeTiempo.dbo.TMEntradas";
										$cur_fech=mssql_query($sql_fech);
										while($datos_fec=mssql_fetch_array($cur_fech))
										{
											$sel="";
											if($ano==$datos_fec["anos"])
												$sel="selected";
?>
		                                    <option value="<?=$datos_fec["anos"] ?>"  <?php echo $sel; ?> > <?=$datos_fec["anos"];  ?> </option>
<?
										}
?>
                                </select>
                            </td>
        
                          </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                <input type="hidden" name="recarga" id="recarga" value="1" >
                  <td colspan="2" align="right" class="TituloTabla"><input type="button" value="Consultar" class="Boton" onClick="valida()" ></td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
    </form>

    <?

	?>
	<table width="100%"  border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td class="TituloUsuario">Reporte</td>
      </tr>
    </table>
	<table width="100%"  border="0" cellspacing="1" cellpadding="0">
      <tr>
        <td bgcolor="#FFFFFF"><table width="100%"  border="0" cellspacing="1" cellpadding="0">
          <tr class="TituloTabla2">
			<td></td>
            <td>Unidad</td>
            <td>Nombre Apellido</td>
            <td>No tarjeta </td>
            <td>Departamento</td>
            <td>Fecha</td>
            <td>Cant. Horas laboradas</td>
            <td>Detalle</td>
          </tr>
<?php
//si se ha enviado, la consulta
if($recarga==2)
{
			$reg=1;
			while($datos_perso=mssql_fetch_array($cur_perso))
			{
?>
              <tr class="TxtTabla">
				<td><? echo $reg; ?></td>
                <td><? echo $datos_perso["unidad"]; ?></td>
                <td><? echo $datos_perso["Nombres"]." ".$datos_perso["Apellidos"]; ?></td>
                <td><? echo $datos_perso["NoTarjeta"]; ?></td>
                <td><? echo $datos_perso["Departamento"]; ?></td>
                <td><? echo $datos_perso[""]; ?></td>
                <td><? echo $datos_perso[""]; ?></td>
                <td align="center"><input name="Submit" type="submit" class="Boton" onClick="MM_openBrWindow('detalle_usuario.php?division=<? echo $division;?>&departamento=<? echo $departamento;?>&uni=<? echo $datos_perso["unidad"]; ?>&mess=<? echo $mess;?>&ano=<? echo $ano;?>','vAF','scrollbars=yes,resizable=yes,width=780,height=300')" value="Detalle"></td>
              </tr>
<?php
				$reg++;
			}
}
?>
      
        </table>
          <table width="100%"  border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td align="right" class="TxtTabla">

                </td>
            </tr>
          </table></td>
      </tr>
    </table>
	

	<div style="position:absolute; left:2px; top:60px; width: 647px;">
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="TxtNota1">Reportes de Ingresos y Salidas </td>
  </tr>
</table>
</div>
    <table width="100%" border="0" cellspacing="0" cellpadding="1">
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="right" valign="bottom"><input name="Submit2" type="submit" class="Boton" onClick="MM_callJS('window.close()')" value="Cerrar Reporte de Ingresos y Salidas"></td>
  </tr>
</table>
    <table width="100%" border="0" cellspacing="0" cellpadding="1">
      <tr>
        <td>&nbsp;</td>
      </tr>
</table>

    <p>&nbsp;</p>
</body>
</html>

<? mssql_close ($conexion); ?>	
