<?php

function listaDepartamentos() {
	global $conn;
	$query = "SELECT * FROM departamento";
	$recordset = mysql_query($query, $conn) or die(mysql_error());
	
	if(mysql_num_rows($recordset)==0) {
		$result = '{"status":"error","data":"No hay Departamentos."}';
	} else {
		$result = '{"status":"OK","data":[';
		while($row = mysql_fetch_assoc($recordset)) {
			$result .= '{"id":"'.$row['idDepartamento'].'",';
			$result .= '"idProvincia":"'.$row['idProvincia'].'",';
			$result .= '"descripcion":"'.$row['descripcion'].'"';
			$result .= '},';
		}
		$result = substr($result, 0, strlen($result)-1);
		$result .= ']}';
	}
	
	return $result;
}

function getDepartamento($id) {
	global $conn, $tabla;
	$query = "SELECT * FROM departamento WHERE idDepartamento = ".$id;
	$recordset = mysql_query($query, $conn) or die(mysql_error());
	$result = "";
	
	if(mysql_num_rows($recordset)==0) {
		$result = '{"status":"error","data":"No existe el Departamento."}';
	} else {
		$result = '{"status":"OK","data":';
		if($row = mysql_fetch_assoc($recordset)) {
			$result .= '{"id":"'.$row['idDepartamento'].'",';
			$result .= '"descripcion":"'.$row['descripcion'].'"';
			$result .= '},';
		}
		$result = substr($result, 0, strlen($result)-1);
		$result .= '}';
	}
	
	return $result;
}
?>