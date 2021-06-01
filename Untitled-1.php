<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
</head>

<body>
<?php



	$cant_reg=0;

	$sqlRta="select *, tmItems.nomItem from CSCPFichaFamiliaMorbilidad
			inner join tmOpciones on tmOpciones.codOpcion=CSCPFichaFamiliaMorbilidad.codOpcion
			inner join tmItems on CSCPFichaFamiliaMorbilidad.codItemSexo=tmItems.codItem";
	$sqlRta= $sqlRta. " WHERE CSCPFichaInfoCant.codProyecto=".$_SESSION["ccfProyecto"] ;
	$sqlRta= $sqlRta. " AND CSCPFichaInfoCant.codModulo=".$_SESSION["ccfModulo"] ;
	$sqlRta= $sqlRta. " AND CSCPFichaInfoCant.consecutivo=".$_SESSION["ccfConsecutivo"] ;
	$sqlRta= $sqlRta. " AND CSCPFichaInfoCant.numFormulario='".$_SESSION["ccfFormulario"]."'" ;
	$sqlRta= $sqlRta. " AND CSCPFichaInfoCant.nroVivienda=".$_SESSION["ccfVivienda"] ;
	$sqlRta= $sqlRta. " AND CSCPFichaInfoCant.nroFamilia=".$_SESSION["ccfFamilia"] ;
	$sqlRta= $sqlRta. " AND  CSCPFichaFamiliaMorbilidad.codOpcion=".$Opc ;

	$cur_morb=mssql_query($sqlRta);
	$cant_reg=mssql_num_rows($cur_morb);
//	$sqlRta= $sqlRta. " AND  CSCPFichaFamiliaMorbilidad.consecMorbilidad=".$Opc ;




?>
	<table width="100%"  border="0" cellspacing="1" cellpadding="0">
      <tr>
        <td bgcolor="#FFFFFF">
		
		<table width="100%"  border="0" cellspacing="1" cellpadding="0">
		  <tr class="TituloTabla2">
			<td colspan="2" class="TituloTabla"><? echo $pTituloPpal ;?><a name="<? echo $T; ?>"></a></td>
		  </tr>

          <tr class="TxtTabla">
            <td width="">Sexo</td>
            <td width="" >Edad</td>
            <td width="" >Causa</td>
            <td width="" colspan="2" ></td>
          </tr>

<?php
			if($cant_reg==0)
			{
?>
              <tr>
                <td></td>
                <td></td>
                <td></td>
                <td><img src="http://www.ingetec.com.co/sistemas/sisCanafisto/images/del.gif" alt="Editar" /></td>
                <td><img src="http://www.ingetec.com.co/sistemas/sisCanafisto/images/actualizar2.gif" alt="Editar" /></td>
              </tr>
<?php	
			}
			else
			{
				while($datos_rta=mssql_fetch_array($cur_morb))
				{
?>			
                  <tr>

                    <td><?php echo $datos_rta["nomItem"]; ?></td>
                    <td><?php echo $datos_rta["edad"]; ?></td>
                    <td><?php echo $datos_rta["causa"]; ?></td>
                    <td><img src="http://www.ingetec.com.co/sistemas/sisCanafisto/images/del.gif" alt="Editar" /></td>
                    <td><img src="http://www.ingetec.com.co/sistemas/sisCanafisto/images/actualizar2.gif" alt="Editar" /></td>
                  </tr>
<?php
				}
			}
		
?>

        </table>
  	 </td>
	 </tr>
	</table>

	<!-- Botones -->    
	<table width="100%"  border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td align="right">
		<!-- Validación de Perfil de Usuario -->
	<? if (($_SESSION["ccfUsuPerfil"] == 1) OR ($_SESSION["ccfUsuPerfil"] == 2) OR ($_SESSION["ccfUsuPerfil"] == 3)) 	
		{
?>
			<input name="Submit" type="button" class="Boton" onClick="MM_openBrWindow('addCSCPMorbHogar.php?Opc=<? echo $T;?>&Sum=<? echo $Sum;?>&pag=<? echo $Pag;?>&tipo=<? echo $tipo;?>','vAF','scrollbars=yes,resizable=yes,width=780,height=300')" value="Nuevo">

<?php
		}	 ?>
		</td>
      </tr>
    </table>
    
    <!-- ESPACIO -->
    <table width="100%"  border="0">
        <tr>
            <td height="10"> </td>
        </tr>
    </table>

</body>
</html>