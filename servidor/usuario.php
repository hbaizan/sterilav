<?php 

function listaUsuarios() {
	global $conn;
	$query = "SELECT * FROM persona, perfil WHERE perfil_idperfil=idperfil";
	$recordset = mysql_query($query, $conn) or die(mysql_error());
	
	$result = '{"status":"OK","data":[';
	while($row = mysql_fetch_assoc($recordset)) {
		$result .= '{"id":"'.$row['idpersona'].'",';
		$result .= '"nombre":"'.$row['nombre'].'",';
		$result .= '"apellido":"'.$row['apellido'].'",';
		$result .= '"idperfil":"'.$row['perfil_idperfil'].'",';
		$result .= '"perfil":"'.$row['perfil_nombre'].'",';
		$result .= '"usuario":"'.$row['usuario'].'",';
		$result .= '"password":"'.$row['clave'].'",';
		$result .= '"departamento":"'.$row['departamento_iddepartamento'].'",';
		$result .= '"iva":"'.$row['iva_idiva'].'"';
		$result .= '},';
	}
	if(mysql_num_rows($recordset)>0) {
		$result = substr($result, 0, strlen($result)-1);
	}
	$result .= ']}';
	
	return $result;
}

function getUsuario($id) {
	global $conn;
	$query = "SELECT * FROM persona WHERE idpersona = ".$id;
	$recordset = mysql_query($query, $conn) or die(mysql_error());
	$result = "";
	
	if(mysql_num_rows($recordset)==0) {
		$result = '{"status":"error","data":"No existe la Persona."}';
	} else {
		$result = '{"status":"OK","data":';
		if($row = mysql_fetch_assoc($recordset)) {
			$result .= '{"id":"'.$row['idpersona'].'",';
			$result .= '"nombre":"'.$row['nombre'].'",';
			$result .= '"apellido":"'.$row['apellido'].'",';
			$result .= '"departamento":"'.$row['departamento_iddepartamento'].'",';
			$result .= '"iva":"'.$row['iva_idiva'].'",';
			$result .= '"perfil":"'.$row['perfil_idperfil'].'",';
			$result .= '"usuario":"'.$row['usuario'].'",';
			$result .= '"password":"'.$row['clave'].'"';
			$result .= '},';
		}
		$result = substr($result, 0, strlen($result)-1);
		$result .= '}';
	}
	
	return $result;
}

function validarUsuario($usuario,$password) {
	global $conn;
	$query = "SELECT * FROM persona WHERE usuario LIKE '".$usuario."'";
	$recordset = mysql_query($query, $conn) or die(mysql_error());
	$result = "";
	
	if(mysql_num_rows($recordset)==0) {
		$result = '{"status":"error","data":"Credenciales invalidas."}';
	} else {
		if($row = mysql_fetch_assoc($recordset)) {
			if($password==$row['clave']) {
				$result = '{"status":"OK","data":{"usuario":"'.$usuario.'","perfil":"'.$row['perfil_idperfil'].'"}}';
			} else {
				$result = '{"status":"error","data":"Credenciales invalidas."}';
			}
		}
	}
	
	return $result;
}

function putUsuario() {
	global $conn;

	$nombre = chequearCampo($_POST['nombre']);
	$apellido = chequearCampo($_POST['apellido']);
	$usuario = chequearCampo($_POST['usuario']);
	$perfil = chequearCampo($_POST['perfil']);
	$password = chequearCampo($_POST['password']);
	$departamento = chequearCampo($_POST['departamento']);
	$iva = chequearCampo($_POST['iva']);
	
	$query = "INSERT INTO usuario (nombre, apellido, usuario, perfil_idperfil, clave, departamento_iddepartamento, iva_idiva) VALUES ('$nombre','$apellido','$usuario',$perfil,'$password', $departamento, $iva)";
	$result = mysql_query($query, $conn);
	if(!$result) {
		return '{"status":"error","data":"'.mysql_error().'"}';
	} else {
		return '{"status":"OK","data":"La Persona ha sido creada."}';
	}
}

function updateUsuario() {
	global $conn;

	$id = chequearCampo($_POST['id']);
	$nombre = chequearCampo($_POST['nombre']);
	$apellido = chequearCampo($_POST['apellido']);
	$usuario = chequearCampo($_POST['usuario']);
	$perfil = chequearCampo($_POST['perfil']);
	$password = chequearCampo($_POST['password']);
	$departamento = chequearCampo($_POST['departamento']);
	$iva = chequearCampo($_POST['iva']);
	$query = "UPDATE usuario SET nombre='$nombre', apellido='$apellido', usuario='$usuario', perfil_idperfil=$perfil, clave='$password', departamento_iddepartamento=$departamento, iva_idiva=$iva WHERE idpersona = ".$id;
	$result = mysql_query($query, $conn);
	if(!$result) {
		return '{"status":"error","data":"'.mysql_error().'"}';
	} else {
		return '{"status":"OK","data":"La Persona ha sido creada."}';
	}
}
?>