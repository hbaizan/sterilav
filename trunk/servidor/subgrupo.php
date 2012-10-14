<?php

function listaSubgrupos() {
	global $conn;
	$query = "SELECT * FROM subgrupo,grupo WHERE idgrupo=grupo_idgrupo";
	$recordset = mysql_query($query, $conn) or die(mysql_error());
	
	$result = '{"status":"OK","data":[';
	while($row = mysql_fetch_assoc($recordset)) {
		$result .= '{"id":"'.$row['idsubgrupo'].'",';
		$result .= '"idgrupo":"'.$row['grupo_idgrupo'].'",';
		$result .= '"grupo":"'.$row['grupo_nombre'].'",';
		$result .= '"descripcion":"'.$row['subgrupo_nombre'].'"';
		$result .= '},';
	}
	if(mysql_num_rows($recordset)>0) {
		$result = substr($result, 0, strlen($result)-1);
	}
	$result .= ']}';
	
	return $result;
}

function getSubgrupo($id) {
	global $conn, $tabla;
	$query = "SELECT * FROM subgrupo WHERE idsubgrupo = ".$id;
	$recordset = mysql_query($query, $conn) or die(mysql_error());
	$result = "";
	
	if(mysql_num_rows($recordset)==0) {
		$result = '{"status":"error","data":"No existe el Subgrupo."}';
	} else {
		$result = '{"status":"OK","data":';
		if($row = mysql_fetch_assoc($recordset)) {
			$result .= '{"id":"'.$row['idsubgrupo'].'",';
			$result .= '"grupo":"'.$row['grupo_idgrupo'].'",';
			$result .= '"descripcion":"'.$row['subgrupo_nombre'].'"';
			$result .= '},';
		}
		$result = substr($result, 0, strlen($result)-1);
		$result .= '}';
	}
	
	return $result;
}

function putSubgrupo() {
	global $conn;

	$response = "";
	$descripcion = chequearCampo($_POST['descripcion']);
	$grupo = chequearCampo($_POST['grupo']);
	
	$query = "INSERT INTO subgrupo (subgrupo_nombre, grupo_idgrupo) VALUES ('$descripcion',$grupo)";
	$result = mysql_query($query, $conn);
	if(!$result) {
		$response = '{"status":"error","data":"'.mysql_error().'"}';
	} else {
		$response = '{"status":"OK","data":"El Subgrupo ha sido creado."}';
	}

	return $response;
}

function updateSubgrupo() {
	global $conn;

	$response = "";
	$id = chequearCampo($_POST['id']);
	$descripcion = chequearCampo($_POST['descripcion']);
	$grupo = chequearCampo($_POST['grupo']);

	$query = "UPDATE subgrupo SET subgrupo_nombre='$descripcion', grupo_idgrupo=$grupo WHERE idsubgrupo = ".$id;
	$result = mysql_query($query, $conn);
	if(!$result) {
		$response = '{"status":"error","data":"'.mysql_error().'"}';
	} else {
		$response = '{"status":"OK","data":"El Subgrupo ha sido actualizado."}';
	}

	return $response;
}
?>