<?php

function listaPerfiles() {
	global $conn;
	$query = "SELECT * FROM perfil";
	$recordset = mysql_query($query, $conn) or die(mysql_error());
	
	if(mysql_num_rows($recordset)==0) {
		$result = '{"status":"error","data":"No hay Perfiles."}';
	} else {
		$result = '{"status":"OK","data":[';
		while($row = mysql_fetch_assoc($recordset)) {
			$result .= '{"id":"'.$row['idPerfil'].'",';
			$result .= '"descripcion":"'.$row['descripcion'].'"';
			$result .= '},';
		}
		$result = substr($result, 0, strlen($result)-1);
		$result .= ']}';
	}
	
	return $result;
}

function listaPermisos($id) {
	global $conn, $tabla;
	$query = "SELECT * FROM grupopantallas";
	$recordset = mysql_query($query, $conn) or die(mysql_error());
	
	if(mysql_num_rows($recordset)>0) {
		$result = '{"status":"OK","data":[';
		while($row = mysql_fetch_assoc($recordset)) {
			$result .= '{"grupo":"'.$row['nombre'].'","pantallas":[';
			$query2 = "SELECT * FROM perfilpermiso,pantallas WHERE perfilpermiso.idPantalla=pantallas.idPantalla AND idPerfil = ".$id. " AND idGrupo = ".$row['idPantalla'];
			$recordset2 = mysql_query($query2, $conn) or die(mysql_error());
			while($row2 = mysql_fetch_assoc($recordset2)) {
				$result .= '{"id":"'.$row2['idPantalla'].'",';
				$result .= '"pantalla":"'.$row2['nombre'].'",';
				$result .= '"aplicacion":"'.$row2['aplicacion'].'"';
				$result .= '},';
			}
			if(mysql_num_rows($recordset2)>0) {
				$result = substr($result, 0, strlen($result)-1);
			}
			$result .= ']},';
		}
	}
	if(mysql_num_rows($recordset)>0) {
		$result = substr($result, 0, strlen($result)-1);
	}
	$result .= ']}';
			
	return $result;
}

function getPerfil($id) {
	global $conn, $tabla;
	$query = "SELECT * FROM perfil WHERE idPerfil = ".$id;
	$recordset = mysql_query($query, $conn) or die(mysql_error());
	$result = "";
	
	if(mysql_num_rows($recordset)==0) {
		$result = '{"status":"error","data":"No existe el Perfil."}';
	} else {
		$result = '{"status":"OK","data":';
		if($row = mysql_fetch_assoc($recordset)) {
			$result .= '{"id":"'.$row['idPerfil'].'",';
			$result .= '"descripcion":"'.$row['descripcion'].'"';
			$result .= '},';
		}
		$result = substr($result, 0, strlen($result)-1);
		$result .= '}';
	}
	
	return $result;
}
?>