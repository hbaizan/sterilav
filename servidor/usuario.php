<?php 



function listaUsuarios() {

	global $conn;

	$query = "SELECT * FROM persona, perfil WHERE perfil_idperfil=idperfil AND NOT persona_inactivo=2 ORDER BY idpersona";

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

		$result .= '"activo":"'.$row['persona_inactivo'].'",';

		$remito = json_decode(usuarioTieneRemito($row['idpersona']));

		if($remito->data) {

			$result .= '"remito":true';

		} else {

			$result .= '"remito":false';

		}

		$result .= '},';

	}

	if(mysql_num_rows($recordset)>0) {

		$result = substr($result, 0, strlen($result)-1);

	}

	$result .= ']}';

	

	return $result;

}



function listaUsuariosParaRemito() {

	global $conn;

	$query = "SELECT * FROM persona, perfil WHERE perfil_idperfil=idperfil AND persona_inactivo=0";

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

		$result .= '"activo":"'.$row['persona_inactivo'].'",';

		$remito = json_decode(usuarioTieneRemito($row['idpersona']));

		if($remito->data) {

			$result .= '"remito":true';

		} else {

			$result .= '"remito":false';

		}

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

	$query = "SELECT * FROM persona WHERE NOT persona_inactivo=2 AND idpersona = ".$id;

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

			$result .= '"perfil":"'.$row['perfil_idperfil'].'",';

			$result .= '"usuario":"'.$row['usuario'].'",';

			$result .= '"password":"'.$row['clave'].'",';

			$result .= '"activo":"'.$row['persona_inactivo'].'",';

			$remito = json_decode(usuarioTieneRemito($row['idpersona']));

			if($remito->data) {

				$result .= '"remito":true';

			} else {

				$result .= '"remito":false';

			}

			$result .= '},';

		}

		$result = substr($result, 0, strlen($result)-1);

		$result .= '}';

	}

	

	return $result;

}



function getUsuarioPorNombre($nombre) {

	global $conn;

	$query = "SELECT * FROM persona WHERE NOT persona_inactivo=2 AND usuario LIKE '".$nombre."'";

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

			$result .= '"perfil":"'.$row['perfil_idperfil'].'",';

			$result .= '"usuario":"'.$row['usuario'].'",';

			$result .= '"password":"'.$row['clave'].'",';

			$result .= '"activo":"'.$row['persona_inactivo'].'",';

			$remito = json_decode(usuarioTieneRemito($row['idpersona']));

			if($remito->data) {

				$result .= '"remito":true';

			} else {

				$result .= '"remito":false';

			}

			$result .= '},';

		}

		$result = substr($result, 0, strlen($result)-1);

		$result .= '}';

	}

	

	return $result;

}



function validarUsuario($usuario,$password) {

	global $conn;

	$query = "SELECT * FROM persona WHERE usuario LIKE '".$usuario."' AND persona_inactivo=0";

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

	

	$query = "INSERT INTO persona (nombre, apellido, usuario, perfil_idperfil, clave, departamento_iddepartamento) VALUES ('$nombre','$apellido','$usuario',$perfil,'$password', $departamento)";

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

	$inactivo = $_POST['activo'];



	$query = "UPDATE persona SET nombre='$nombre', apellido='$apellido', usuario='$usuario', perfil_idperfil=$perfil, clave='$password', departamento_iddepartamento=$departamento, persona_inactivo=$inactivo WHERE idpersona = ".$id;

	$result = mysql_query($query, $conn);

	if(!$result) {

		return '{"status":"error","data":"'.mysql_error().'"}';

	} else {

		return '{"status":"OK","data":"La Persona ha sido creada."}';

	}

}



function borrarUsuario($id) {

	global $conn;



	$remito = json_decode(usuarioTieneRemito($id));

	if($remito->data) {

		return '{"status":"error","data":"La Persona tiene remitos asociados y no puede ser eliminada."}';

		exit;

	}

	$query = "UPDATE persona SET persona_inactivo=2 WHERE idpersona = ".$id;

	$result = mysql_query($query, $conn);

	if(!$result) {

		return '{"status":"error","data":"'.mysql_error().'"}';

	} else {

		return '{"status":"OK","data":"La Persona ha sido eliminada."}';

	}

}



function usuarioTieneRemito($id) {

	global $conn;

	

	$query = "SELECT * FROM remito,persona WHERE persona_idpersona=idpersona AND idpersona=".$id;

	$recordset = mysql_query($query, $conn);

	if(!$recordset) {

		return '{"status":"error","data":"'.mysql_error().'"}';

	} else {

		if(mysql_num_rows($recordset)==0) {

			return '{"status":"OK","data":false}';

		} else {

			return '{"status":"OK","data":true}';

		}

	}

}

?>