<?php
$link = mysql_connect('localhost', 'sdht_usuario', 'Ochochar*1') or die('No se pudo conectar: ' . mysql_error());
mysql_select_db('sdht_subsidios') or die('No se pudo seleccionar la base de datos');

header("Content-Type: application/vnd.ms-excel");
header("content-disposition: attachment;filename=analisisUnidadesAsignadas.xls");

// Dibuja los titulos de la tabla
echo "<table><tr>
		<th>Formulario</th>
		<th>Modalidad</th>
		<th>Esquema</th>
		<th>Solucion</th>
		<th>valAspiraSubsidio</th>
		<th>Estado</th>
	</tr>";

// Recorrer las unidades residenciales de esquema individual
$query_unidades = "select seqFormulario, seqModalidad, seqTipoEsquema, seqSolucion, valAspiraSubsidio, SeqEstadoProceso
					from t_frm_formulario
$execute_unidades = mysql_query($query_unidades);
while ($row_unidades = mysql_fetch_array($execute_unidades)){
	$tbl_formulario		= $row_unidades['seqFormulario'];
	$tbl_modalidad		= $row_unidades['seqModalidad'];
	$tbl_esquema 		= $row_unidades['seqTipoEsquema'];
	$tbl_solucion 		= $row_unidades['seqSolucion'];
	$tbl_aspira 		= $row_unidades['valAspiraSubsidio'];
	$tbl_estado 		= $row_unidades['SeqEstadoProceso'];
	echo "<tr>";
	echo "<td>$tbl_formulario</td>";
	echo "<td>$tbl_modalidad</td>";
	echo "<td>$tbl_esquema</td>";
	echo "<td>$tbl_solucion</td>";
	echo "<td>$tbl_aspira</td>";
	echo "<td>$tbl_estado</td>";
	echo "</tr>";
}

echo "</table>";

// Liberar resultados
//mysql_free_result($result);

// Cerrar la conexión
mysql_close($link);
?>