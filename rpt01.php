<?php
session_start();
include("../verificaRegistro2.php");
include('../conectaBD.php');

?>

<?

//Establecer la conexión a la base de datos
$conexion = conectar();
//mssql_select_db('HojaDeTiempo');
if(($recarga==2)or($pagina!="")) //si se ha presionado el boton consultar, o se ha cambiado de pagina
{
	//consulta las personas que hacen parte de la division y el departamento
//,NoTarjeta

	//Consulta de registros a mostrar
	$sql_consul_perso="  select distinct(t.unidad),u.retirado, u.nombre as Nombres,u.apellidos as Apellidos, d.nombre as Departamento from HojaDeTiempo.dbo.TMEntradas as t
 inner join HojaDeTiempo.dbo.Usuarios as u on u.unidad=t.unidad
 inner join HojaDeTiempo.dbo.Departamentos as d on d.id_departamento=t.id_departamento 	 where ";



	$var_inicial=1; 
/////////////////PAGINACION
	$cantidad_registros=15; //establecemos la cantidad de registros que se mostraran en cada pagina
	//si $pagina="" es verdadero, quiere decir que se cargaran los valores iniciales de la paginacion
	if(trim($pagina) == "")
	{
		$inicio=0;  //variable utilizada para el betwen que se realiza en el query de la base de datos
		$pagina=1;  
		$fin=$cantidad_registros*$pagina; //$fin se usa para, definir el limite maximo de el rango de datos q se traheran de la base de datos

		$nueva=0;  //variable q permite identificar si se realizo una nueva conulta, para posicionar la paginacion en la primera hoja		
	//	echo "iniciacion ".$pagina." $nueva";
	}
	
	//si no es la primera pagina, se realiza las operaciones matematicas, para establecer los valores de las demas paginas
	else
	{
		$inicio=($pagina-1)*$cantidad_registros;
		$fin=$cantidad_registros*$pagina;
		
		$nueva=1;
//		echo "continuidad ".$pagina." $nueva";
	}




	//Consulta para la paginacion
$sqlprinci_top="WITH personas AS ( 
select *, ROW_NUMBER() OVER (ORDER BY  t.Nombres ) AS RowNumber  from (
	select distinct(t.unidad),u.retirado, u.nombre as Nombres,u.apellidos as Apellidos, d.nombre as Departamento
	from HojaDeTiempo.dbo.TMEntradas as t
	 inner join HojaDeTiempo.dbo.Usuarios as u on u.unidad=t.unidad
	 inner join HojaDeTiempo.dbo.Departamentos as d on d.id_departamento=t.id_departamento";
	$sqlprinci_top=$sqlprinci_top."	 where ";

	if(trim($division)!="")
	{
		$sql_cant_reg=$sql_cant_reg." t.id_division=".$division;
		$sql_consul_perso=$sql_consul_perso." t.id_division=".$division;
		$sqlprinci_top=$sqlprinci_top." t.id_division=".$division;
	}
	//SI SE HA SELECCIONADO UNA DIVISION Y SE HA INGRESADO UNA UNIDAD, SE FORMA LA CONSULTA CON LA UNIDDAD CON AND
	if((trim($uni)!="")and((trim($division)!="")))
	{

		$sql_cant_reg=$sql_cant_reg." and t.unidad=".$uni;
		$sql_consul_perso=$sql_consul_perso." and t.unidad=".$uni;
		$sqlprinci_top=$sqlprinci_top." and t.unidad=".$uni;
	}
	//SI SE HA INGRESADO SOLO LA UNIDAD LA CONSULTA SE FORMA SIN AND
	if((trim($uni)!="")and((trim($division)=="")))
	{

		$sql_cant_reg=$sql_cant_reg."  t.unidad=".$uni;
		$sql_consul_perso=$sql_consul_perso."  t.unidad=".$uni;
		$sqlprinci_top=$sqlprinci_top."  t.unidad=".$uni;
	}

	if(trim($departamento)!="")
	{
		$sql_cant_reg=$sql_cant_reg." and t.id_departamento=".$departamento;
		$sql_consul_perso=$sql_consul_perso." and t.id_departamento=".$departamento;
		$sqlprinci_top=$sqlprinci_top." and t.id_departamento=".$departamento;
	}


	$sql_cant_reg=$sql_cant_reg." and YEAR(t.FechaCompleta)=".$ano;
	$sql_consul_perso=$sql_consul_perso." and YEAR(t.FechaCompleta)=".$ano;
	$sqlprinci_top=$sqlprinci_top." and YEAR(t.FechaCompleta)=".$ano;


	$sql_cant_reg=$sql_cant_reg." and MONTH(t.FechaCompleta)=".$mess;
	$sql_consul_perso=$sql_consul_perso." and MONTH(t.FechaCompleta)=".$mess;
	$sqlprinci_top=$sqlprinci_top." and MONTH(t.FechaCompleta)=".$mess;

	$sql_consul_perso=$sql_consul_perso." order by(u.nombre)";

	$sqlprinci_top=$sqlprinci_top." ) as t ) SELECT RowNumber,* FROM personas WHERE RowNumber BETWEEN ".$inicio." AND ".$fin;

	$cur_perso=mssql_query($sql_consul_perso);
	$cant_reg=mssql_num_rows($cur_perso);

	$cantidad_pagina=ceil($cant_reg/15);

	$valores_sql_princi_top=mssql_query($sqlprinci_top); //consulta de la paginacion
#echo $sqlprinci_top."<br>".mssql_get_last_message()."<br><BR>";
#echo $sql_consul_perso." --- ".mssql_get_last_message()." *** ".mssql_num_rows($cur_perso)." <br><br>";


	//FORMA LA CONSULTA SQL DE LA TABLA A CONSULTAR, PARA LA CANTIDAD DE ENTRADAS Y SALIDAS
	function forma_sql_can_reg($tabla)
	{
		$sql_reto="	select COUNT(*) as valor from HojaDeTiempo.dbo.".$tabla." t
		 inner join HojaDeTiempo.dbo.Usuarios as u on u.unidad=t.unidad
		inner join HojaDeTiempo.dbo.Departamentos as d on d.id_departamento=t.id_departamento where  ";
		return  $sql_reto;
	}
	//CONSULTA LA CANTIDAD DE ENTRADAS
	$sql_can_reg2=forma_sql_can_reg("TMEntradas");
	$cant_entrada=mssql_query($sql_can_reg2." ".$sql_cant_reg);
	if($datos_can_entra=mssql_fetch_array($cant_entrada))
		$cant_entradas=$datos_can_entra["valor"];



	//CONSULTA LA CANTIDAD DE SALIDAS
	$sql_can_reg2=forma_sql_can_reg("TMSalidas");
	$cant_salida=mssql_query($sql_can_reg2. " " .$sql_cant_reg);
	if($datos_can_salida=mssql_fetch_array($cant_salida))
		$cant_salidas=$datos_can_salida["valor"];
	
	if(trim($cant_entradas)=="")
		$cant_entradas=0;

	if(trim($cant_salidas)=="")
		$cant_salidas=0;

}

?>



<html>
<head>
<title>Entradas y Salidas</title>
<LINK REL="stylesheet" HREF="../css/estilo.css" TYPE="text/css">
<script>

window.name="win_accesos";

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

	if((document.form1.division.value=="")&&(document.form1.uni.value==""))
	{
		e="s";
		msg="Seleccione una divisi\xf3n o ingrese una unidad.\n ";
	}
/*

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
		msg=msg+"Seleccione un a\u00F1o.\n ";
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
        <?	if( ( $ano != "" ) && ( $mess != "" ) && ( ( $division != "" )||($uni!="")) ){		?>
		        <a href="rpt02.php?anio=<?= $ano ?>&mes=<?= $mess ?>&division=<?= $division ?>&departamento=<?= $departamento ?>&uni=<?=$uni  ?>" class="FichaInAct1" >Grafica</a>
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
                        <select name="division" class="CajaTexto" id="division" onChange="document.form1.uni.value=''; document.form1.submit();">
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

    <!-- Paginación -->
	<table width="100%"  border="0" cellpadding="0" cellspacing="1">
	  <tr>
		<td align="center" class="TxtTabla">
		<?
//			echo $cantidad_pagina." $pagina <br>";		
		for ($p=1; $p<= $cantidad_pagina; $p++) 
		{
			/*
			if($nueva==0) //identifica si se ha realizado una nueva busqueda
			{
				$pag=1;
			}
			else  //if($nueva==1)and($pagina==$p)
			{
				$pag=$p;
			} */

			if($p == $pagina)
			{
				$clase = "menu3";
			}
			else{
				$clase = "menu";
			}
			//envia en la url, la informacion de la pagina, y de los filtros de consulta
			?>
			<a href='rpt01.php?pagina=<?php echo $p; ?>&division=<?php echo $division; ?>&departamento=<?php echo $departamento; ?>&uni=<?php echo $uni; ?>&mess=<?php echo $mess; ?>&ano=<?php echo $ano; ?>' class='<?php echo $clase; ?>'><?php echo $p; ?></a> | 
           <?php
		}
//		echo $p;
		
		?>
		</td>
	  </tr>
  </table>
	<table width="100%"  border="0">
      <tr>
        <td><input name="Submit3" type="submit" class="Boton" onClick="MM_openBrWindow('rptDiv01.php','win_accesosDiv','scrollbars=yes,resizable=yes,width=1000,height=550')" value="Reportes por Divisi&oacute;n"></td>
      </tr>
    </table>
	<table width="100%"  border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td class="TituloUsuario" colspan="6">Reporte</td>
      </tr>
      <tr>
        <td class="TxtTabla" colspan="6">&nbsp;</td>
      </tr>
      <tr>
        <td class="TituloUsuario" width="15%">Cantidad de personas</td>
        <td class="TxtTabla" width="15%"> <? echo "&nbsp;".$cant_reg ?></td>
        <td class="TituloUsuario" width="15%">Total de entradas</td>
        <td class="TxtTabla" width="15%"> <? echo "&nbsp;".$cant_entradas ?></td>
        <td class="TituloUsuario" width="15%">Total de salidas</td>
        <td class="TxtTabla" width="15%"> <? echo "&nbsp;".$cant_salidas ?></td>
      </tr>

    </table>
	<table width="100%"  border="0" cellspacing="1" cellpadding="0">
      <tr>
        <td bgcolor="#FFFFFF"><table width="100%"  border="0" cellspacing="1" cellpadding="0">
          <tr class="TituloTabla2">
			<td></td>
            <td>Unidad</td>
            <td>Nombre Apellido</td>
<!--            <td>No tarjeta </td>
-->
            <td>Departamento</td>
            <td>Fecha</td>

            <td>Cant. Horas laboradas</td>
            <td>Estado</td>

            <td>Detalle</td>
          </tr>
<?php
//si se ha enviado, la consulta
if (($recarga==2)or($pagina!="")) //si se ha presionado el boton consultar, o se ha cambiado de pagina
{
		//consulta los dias del mes en la tabla de entradas
		$sql_dias="select distinct(day(FechaCompleta)) as dia from HojaDeTiempo.dbo.TMSalidas where  MONTH(FechaCompleta)=".$mess." and YEAR(FechaCompleta)=".$ano." order by(dia)  ";
		$cur_dias=mssql_query($sql_dias);
//echo $sql_dias." --- <br> ".mssql_get_last_message()."<br>";
		$i=1;
//		$dias= array();
		while($datos_dia=mssql_fetch_array($cur_dias))
		{
			$dias[$i]=$datos_dia["dia"];
			$i++;
		}
//echo "Diasssss  ".$dias[3]."<br>";

			//$reg=1;
			while($datos_perso=mssql_fetch_array($valores_sql_princi_top))
			{
?>
              <tr class="TxtTabla">
				<td><? echo $datos_perso["RowNumber"]; ?></td>
                <td><? echo $datos_perso["unidad"]; ?></td>

                <td><? echo $datos_perso["Nombres"]." ".$datos_perso["Apellidos"]; ?></td>
<!--
                <td><? //echo $datos_perso["NoTarjeta"]; ?></td>
-->
                <td><? echo $datos_perso["Departamento"]; ?></td>
                <td><? echo $mess."/".$ano; ?></td>
<?
				$z=1;
				$total_horas=0;
				//permite recorrer el vector que contiene los dias del mes, y asi calcular la cantidad de horas laboradas, durante el mes
				while($z<$i)
				{
//echo $dias[$z]."    indice ".$z."<br>";
					//calcula la cantidad de horas laboradas, por cada persona
					$sql_entradas="select * from HojaDeTiempo.dbo.TMEntradas where ";
					if(trim($departamento)!="")
						$sql_entradas=$sql_entradas." id_departamento=".$departamento." and ";

					$sql_entradas=$sql_entradas." unidad=".$datos_perso["unidad"]." and MONTH(FechaCompleta)=".$mess." and YEAR(FechaCompleta)=".$ano." and DAY(FechaCompleta)=".$dias[$z]." ";
					$cur_entradas=mssql_query($sql_entradas);
#echo $sql_entradas." ---".mssql_get_last_message()."<br><br>";			
					$cant_entradas=mssql_num_rows($cur_entradas);

			
					//consulta las salidas
					$sql_salidas="select * from HojaDeTiempo.dbo.TMSalidas where ";

					if(trim($departamento)!="")
						$sql_salidas=$sql_salidas." id_departamento=".$departamento." and ";

						$sql_salidas=$sql_salidas." unidad=".$datos_perso["unidad"]." and MONTH(FechaCompleta)=".$mess." and YEAR(FechaCompleta)=".$ano." and DAY(FechaCompleta)=".$dias[$z];
					$cur_salidas=mssql_query($sql_salidas);

#echo $sql_salidas." ---".mssql_get_last_message()."<br><br>";						
					$cant_salidas=mssql_num_rows($cur_salidas);
			//*********
						$cont_entra=1; $cont_salidas=1; //contadores,  utilizados para llevar el indice, de los registros de entradas y salidas

						//valida si cada cursor ha finalizado, el ciclo de la cantidad toal de registros a mostrar
						while(($cant_entradas>=$cont_entra)or($cant_salidas>=$cont_salidas))
						{


							$datos_entrada=mssql_fetch_array($cur_entradas);
							$datos_salidas=mssql_fetch_array($cur_salidas);
//echo "  indice entradas ".$cont_entra." --salidas- ".$cont_entra."<br>";
//echo "inresa".$datos_entrada["IdRegistro"] ." --salida-- ".$datos_salidas["IdRegistro"]."<br>";
							//valida que el id del registro, tanto como de las entradas y las salidas no este vacio
							if((trim($datos_entrada["IdRegistro"])!="")and(trim($datos_salidas["IdRegistro"])!="" ))
							{
			
								//valida si el IdRegistro de salidas es inferior al de entrada, ya que el registro de entrada, tiene que se inferior
								//se puede dar el caso que sea inferior, cuando el dia anterior no se registrado la salida, y se gira el torniquete al momento de ingresar
								//de ser asi, no se tiene en cuenta el registro, y se avanza el siguiente de las salidas, hasta que el id_registro sea mayor al de entrada

								$ban=0;//permite identificar si, se ha presentado la situacion cuando solo exite una entrada y una salida, y la entrada es mayor a la salida
								while( ( (int) $datos_salidas["IdRegistro"]) <  ( (int) $datos_entrada["IdRegistro"]))
								{

									if($cont_salidas==1)//CUANDO SE TRATE DE UNA SALIDA SIN ENTRADA, Y SI ES EL PRIMER REGISTRO QUE SE TRAHE
									{
										//SE CALCULA LA HORA DE SALIDA CON LA HORA APARTIR DE LAS 00:00, PARA ESTABLECER, LA CANTIDAD DE HORAS
										$hora_salida=$datos_salidas["Hora"];
										$hora_entrada="00:00:00:0";	
										$cur_horas=mssql_query("select cast (DATEDIFF (MI,'".$hora_entrada."','".$hora_salida."') as float)/60 as hora ");
										if($dato_hora=mssql_fetch_array($cur_horas))
										{
											$diferencia_horas=$dato_hora["hora"];
											$total_horas+=$dato_hora["hora"];
				
										}
									}
									$cont_salidas++;
?>	
                                  
<?php

									$datos_salidas=mssql_fetch_array($cur_salidas);
									//verifica que el registro de la salida no se a nulo, con el fin de romper el ciclo, si no se hace esto se convierte en ciclo infinito
									//la situacion se da cuando la entrada es mayor a la salida, y solo existe una entrada y una salida
									if(trim($datos_salidas["IdRegistro"])=="" ) 
									{
										$ban=1;
										break;
									}
								}	
								//si se presento la situacion	
								if($ban==1)
								{
/*									$hora_entrada=$datos_entrada["Hora"];
									$hora_salida=$datos_entrada["Hora"];
*/
									$hora_entrada=$datos_entrada["Hora"];
									$hora_salida="23:59";
									$datos_salidas["Fecha"]=$datos_entrada["Fecha"];
									$datos_salidas["Hora"]="23:59";
								}
								else
								{
									$hora_entrada=$datos_entrada["Hora"];
									$hora_salida=$datos_salidas["Hora"];
								}
//echo "hora entrada $hora_entrada --- hora salida   $hora_salida -- <br><br>";
			
								//calcula la diferencia, entre la hora de ingreso y la hora de salida,calculando el timpo transcurrido
								$cur_horas=mssql_query("select cast (DATEDIFF (MI,'".$hora_entrada."','".$hora_salida."') as float)/60 as hora ");

								if($dato_hora=mssql_fetch_array($cur_horas))
								{
									$total_horas+=$dato_hora["hora"];
								}
//echo "<br><br> calculo de horas "."select cast (DATEDIFF (MI,'".$hora_entrada."','".$hora_salida."') as float)/60 as hora ----<br> total horas hata ahora $total_horas ".mssql_get_last_message()."<br><br>";	
							}
							else //SI ALGUNO DE LOS DOS REGISTROS ESTA VACIO ES POR QUE EXISTE: 1. UNA ENTRADA SIN SALIDA 2. UNA SALIDA SIN ENTRADA
							{
								
								if((trim($datos_entrada["IdRegistro"])!=""))//ENTRADA SIN SALIDA 
								{
									$hora_entrada=$datos_entrada["Hora"];
									$hora_salida="23:59:00:0";	
									$cur_horas=mssql_query("select cast (DATEDIFF (MI,'".$hora_entrada."','".$hora_salida."') as float)/60 as hora ");
			//echo "ENtrada<br> select cast (DATEDIFF (MI,'".$hora_entrada."','".$hora_salida."') as float)/60 as hora <br>".mssql_get_last_message()."<br><br>";
									if($dato_hora=mssql_fetch_array($cur_horas))
									{
										$diferencia_horas=$dato_hora["hora"];
										$total_horas+=$dato_hora["hora"];
			
									}
			?>
	
			<?php
								}			
								if(trim($datos_salidas["IdRegistro"])!="" ) //SALIDA SIN ENTRADA
								{
									if($cont_salidas==1)//CUANDO SE TRATE DE UNA SALIDA SIN ENTRADA, Y SI ES EL PRIMER REGISTRO QUE SE TRAHE
									{
										$hora_entrada="00:00:00:0";
										$hora_salida=$datos_salidas["IdRegistro"];	
				
										$cur_horas=mssql_query("select cast (DATEDIFF (MI,'".$hora_entrada."','".$hora_salida."') as float)/60 as hora ");
				//echo "<br>Salida  <br> select cast (DATEDIFF (MI,'".$hora_entrada."','".$hora_salida."') as float)/60 as hora  ".mssql_get_last_message();
										if($dato_hora=mssql_fetch_array($cur_horas))
										{
											$diferencia_horas=$dato_hora["hora"];
											$total_horas+=$dato_hora["hora"];
				
										}
									}
								}
			
							}
							$cont_entra++;
							$cont_salidas++;


			
//echo $total_horas."  ---  ".$hora_entrada." ---- ".$hora_salida."<br>";
						}
					$z++;
				}
//		echo $sql_entradas." --- $cant_entradas -- $cont_entra  -- $total_horas --- $z <br> ".mssql_get_last_message()."<br><br>";
//		echo $sql_salidas." ---  $cant_salidas  -- $cont_salidas <br> ".mssql_get_last_message()."<br><br>";

//$total_horas." ----***** ".
?>


                <td><? echo  round($total_horas * 100)/100; //redondeamos la cantidad de horas(multiplicandolo) y dividendolo, para tomar solo los dos primeros numeros despues del punto ?></td>

				<td align="center">

<?

				if( $datos_perso["retirado"]=="") 
				{
?>
                      <img src="icono_usuario.gif" alt="Activo" border="0" >					
<?
				}
				if( $datos_perso["retirado"]==1) 
				{
?>
                      <img src="icono_usuario2.gif" alt="Inactivo" border="0" >					
<?
				}
?>

					
				</td>

                <td align="center"><input name="Submit" type="submit" class="Boton" onClick="MM_openBrWindow('detalle_usuario.php?division=<? echo $division;?>&departamento=<? echo $departamento;?>&uni=<? echo $datos_perso["unidad"]; ?>&mess=<? echo $mess;?>&ano=<? echo $ano;?>','vAF','scrollbars=yes,resizable=yes,width=1180,height=500')" value="Detalle"></td>
              </tr>
<?php
				//$reg++;
			}
?>
	
<?		
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
