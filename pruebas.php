<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>


		<script language="javascript">
			function Borrar(valor)
			{
				if(document.getElementById("texto").value==valor)
				{
					document.getElementById("texto").value="";
				}
			}
		
			function Escribir(valor)
			{
				if(document.getElementById("texto").value=="")
				{
					document.getElementById("texto").value=valor;
				}
			}

			function mostrar()
			{
				alert(document.f.texto.value);

			}
			
			
var nav4 = window.Event ? true : false;
function acceptNum(evt){   
var key = nav4 ? evt.which : evt.keyCode;   
return (key <= 13 || (key>= 48 && key <= 57));
}



		</script>
</head>

<body>
<script type="text/javascript">
function val()
{
	var can_item=document.form1.cant_tempo.value;
	var can_selec=0;
	for(var i=1;i<=can_item;i++)
	{
		window['aplicas']='aplica'+i; 
		//valida cuales estan marcados con la opcion NO

		if(document.getElementById(aplicas).checked)
		{
			can_selec++; //realiza el conteo por cada marcacion
		}
		
	}
	alert(can_selec+" *** "+can_item);
}
		  
		  <? // $Sum=3; ?>
function valida_campo_orden()
{

	return ret;
}


</script>

<script type="text/javascript"> alert("La sumatoria de la division 1 en los diferentes lotes de trabajo, no puede superar el valor asignado, asigne un valor igual o inferior a 0."); </script> 
    <table width="100%"  border="2" cellspacing="1" cellpadding="0" >
      <tr >
        <td>Esta es la tabla 1</td>
      </tr>
    </table>

  <table width="100%"  border="2" cellspacing="1" cellpadding="0" >
<?
	function genera_tabla($val)
	{ 
?>
  
      <tr >
        <td>Esta es la tabla generada por la funcion <? echo  $val; ?></td>
      </tr>
<?
	}

?>
    </table>

    <table width="100%"  border="2" cellspacing="1" cellpadding="0" >
      <tr >
        <td>Esta es la tabla 2</td>
      </tr>
    </table>

<form action=""  name="ff">
<input align="center"  type="text"   class="CajaTexto" id="cantidad1"  name="cantidad1" onKeyPress="return acceptNum(event)" >
<input align="center"  type="text"   class="CajaTexto" id="cantidad2"  name="cantidad2" onKeyPress="return acceptNum(event)" >
<input align="center"  type="text"   class="CajaTexto" id="cantidad3"  name="cantidad3" onKeyPress="return acceptNum(event)" >
<input type="submit" onClick="valida_campo_orden()">
</form >
<br><br>

<?php 

$mee=date("m");
echo "---------------------->".$mee."<br>";

echo "<br> -------------------------------------------------------*****";

$fecha=729;
$ano= (int) ($fecha/365);

$dias=$fecha - ($ano*365);
echo " Son $ano años y  ".$dias." Dias<br><br>";

echo "-------------------------------------------------------";


$can=substr_count('LT2.2.2.A.1','.');
echo "<br>------------------------".$can." ***<br>";

$mes=date("m");
$mes=$mes-1;
if ((1<=$mes) or ($mes<=9))
{
	$mes="0".$mes;
}
echo "MESSS ---".$mes."<br><br>";


$sql_consul_entradas=" and MONTH( tlob.Fecha) = month(getdate())-1 and year(tlob.Fecha)=year(getdate()) and day( tlob.Fecha)=day(dateadd(d,-1,dateadd(m,1,convert(datetime, '".date("Y")."' + '".(date("m")-1)."' + '01'))))";
$sql_consul_entradas=" and MONTH( tlob.Fecha) = month(getdate())-1 and year(tlob.Fecha)=year(getdate()) and day( tlob.Fecha)=day(dateadd(d,-1,dateadd(m,1,convert(datetime, '".date("Y")."' + '".(date("m")-1)."' + '01'))))";
echo "<br><br> ****".$sql_consul_entradas."<br><br>";


echo "dia ".date("d")." año ".date("Y")." mes ".date("m")."<br>";

if (int(date("m"))=='03')
	echo "siii";

echo "select day(dateadd(d,-1,dateadd(m,1,convert(datetime, '".date("Y")."' + '0".(date("m")-1)."' + '01'))))";

	genera_tabla('Valor enviado');
	echo number_format(333363.00)." decimal <br>";

?>

<form action=""  method="post"  name="form1">
Si<input type="radio" name="aplica1" id="aplica1" value="si"   >
No<input type="radio" name="aplica1" id="aplica1" value="no" checked>
<br>
Si<input type="radio" name="aplica2" id="aplica2" value="si"   >
No<input type="radio" name="aplica2" id="aplica2" value="no" checked>
<br>
Si<input type="radio" name="aplica3" id="aplica3" value="si"   >
No<input type="radio" name="aplica3" id="aplica3" value="no" checked>
<input type="hidden" name="cant_tempo" id="cant_tempo" value="3" >
<input type="submit" onClick="val()">
</form>						

<form action="" onsubmit="mostrar()" name="f">
<input type="text" id="texto" value="Hola" onfocus="Borrar('Hola')" />
<input type="submit"  />
</form >


<br>

<?

function resta($inicio, $fin)
  {
  $dif=date("H:i:s", strtotime("00:00:00") + strtotime($fin) - strtotime($inicio) );
  return $dif;
  }
  
  $hora_inicial="11:45";
$hora_inicial="13:00";
$diferencia=resta($hora_inicial,$hora_inicial);
echo "La diferencia es $diferencia";
  
  


$inicio='08:22:35:9';
$fin='08:32:35:9';
echo $inicio-$fin."  fffff<br>";
echo "horra:".(floatval (date("H",strtotime('08:32:35:9') - strtotime('08:22:35:9') )/60))."<br>";

$valor="LT12.12";
echo  substr($valor,strrpos($valor, ".")+1,strlen($valor))."<br>";

$valor="LT12.12555.155.1";
echo  substr($valor,strrpos($valor, ".")+1,strlen($valor))."<br>";

echo strrpos($valor, ".");


$cellValue=strtr('GeologÃ­a, sismologÃ­a', "Ã", "Í");
echo $cellValue."<br>";

$cant_reg=5;
for($i=1;$i<=$cant_reg;$i++)
{
	$a=$i+1;
	if($cant_reg<$a)
	{
		$a=1;
	}
	for($z=1;$z<=$cant_reg-1;$z++)
	{
//		if()
		echo "iteracion # $i ".$i."-".$a."   ";
		if($a==$cant_reg)
		{
			$a=0;
		}
		$a++;
	}
	echo "<br>";
}

echo substr('LT1.1',0,strrpos('LT1.1.1', "-"));

echo substr('LT1.1.1',0,strrpos('LT1.1.1', ".")+1)."--- <br>";



echo substr('LT12.12.3',strrpos('LT12.12.3', ".")+1,strlen('LT12.12.3'))."<br>";
echo substr('LT2.2.3.A.15',0,strrpos('LT2.2.3.A.15', ".")+1)."<br>";
echo substr('LT22.41.13',0,strrpos('LT21.44.43', ".")+1)."<br>";
echo substr('LT2.4.3',0,strrpos('LT2.4.3', ".")+(-1))."<br>--";


echo substr('LT12.12.13.A.15',strrpos('LT12.12.13.A.15', ".")+1,strlen('LT12.12.13.A.15'))."-- <br>"; //almacenamos el ultimo numero, despues del ultimo punto, que identifica la actividad LT2.2.2.A.(2) para uzarlo en la vista previa
echo substr('LT2.2.3.A.15',0,strrpos('LT2.2.3.A.15', ".")+1)."-- <br>";

$macro="LC12";
echo substr($macro,2,strlen($macro))."******<br>";
echo substr('LC213',2,strlen('LC213'))."  -  ".strlen('LT2.2.3.A.15')." - ".substr('LC2',0,2)."<br>";

echo substr('10-30-5',0,strrpos('10-30-5', "-"))."<br>";
echo 			$macro=substr('LT2.2.2',0,2);


echo "<br><br>".date("m");



?>

</body>



</html>