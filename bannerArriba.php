
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="left" valign="top" background="../imagenes/pixBan2.gif">&nbsp;&nbsp;<img src="../imagenes/imgLogo.gif" width="137" height="50"></td>
    <td align="right" background="../imagenes/pixBan2.gif"><img src="../imagenes/BannerImg02.gif" width="355" height="72"></td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="right" class="Fecha" >
	<? 
	//$miNombreCompleto = $nombreempleado." ".$apellidoempleado; 
	$miNombreCompleto = $_SESSION["sesNomApeUsuario"] ;
	echo strtoupper($miNombreCompleto); 
	?></td>
  </tr>
</table>



