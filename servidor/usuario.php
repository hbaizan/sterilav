<?php require_once('./db.php');

mysql_select_db($database_conn, $conn);
$tabla = "usuario";

switch($_GET['op']) {
	case "listaUsuarios":
		listaUsuarios();
		break;
	case "getUsuario":
		getUsuario($_GET['id']);
		break;
	case "putUsuario":
		putUsuario();
		break;
	case "updateUsuario":
		updateUsuario();
		break;
	case "validarUsuario":
		validarUsuario($_GET['usuario'],$_GET['password']);
		break;	
}

function listaUsuarios() {
	global $conn, $tabla;
	$query = "SELECT * FROM ".$tabla;
	$recordset = mysql_query($query, $conn) or die(mysql_error());
	
	if(mysql_num_rows($recordset)==0) {
		$result = '{"status":"error","data":"No hay Usuarios."}';
	} else {
		$result = '{"status":"OK","data":[';
		while($row = mysql_fetch_assoc($recordset)) {
			$result .= '{"id":"'.$row['idUsuario'].'",';
			$result .= '"nombre":"'.$row['nombre'].'",';
			$result .= '"apellido":"'.$row['apellido'].'",';
			$result .= '"legajo":"'.$row['legajo'].'",';
			$result .= '"perfil":"'.$row['perfil'].'",';
			$result .= '"usuario":"'.$row['usuario'].'",';
			$result .= '"password":"'.$row['password'].'",';
			$result .= '"departamento":"'.$row['departamento'].'",';
			$result .= '"iva":"'.$row['iva'].'",';
			$result .= '"cuit":"'.$row['cuit'].'",';
			$result .= '"patente":"'.$row['patente'].'"';
			$result .= '},';
		}
		$result = substr($result, 0, strlen($result)-1);
		$result .= ']}';
	}
	
	echo $result;
}

function getUsuario($id) {
	global $conn, $tabla;
	$query = "SELECT * FROM ".$tabla." WHERE idUsuario = ".$id;
	$recordset = mysql_query($query, $conn) or die(mysql_error());
	$result = "";
	
	if(mysql_num_rows($recordset)==0) {
		$result = '{"status":"error","data":"No existe el Usuario."}';
	} else {
		$result = '{"status":"OK","data":';
		if($row = mysql_fetch_assoc($recordset)) {
			$result .= '{"id":"'.$row['idUsuario'].'",';
			$result .= '"nombre":"'.$row['nombre'].'",';
			$result .= '"apellido":"'.$row['apellido'].'",';
			$result .= '"departamento":"'.$row['departamento'].'",';
			$result .= '"iva":"'.$row['iva'].'",';
			$result .= '"cuit":"'.$row['cuit'].'",';
			$result .= '"patente":"'.$row['patente'].'",';
			$result .= '"legajo":"'.$row['legajo'].'",';
			$result .= '"perfil":"'.$row['perfil'].'",';
			$result .= '"usuario":"'.$row['usuario'].'",';
			$result .= '"password":"'.$row['password'].'",';
			$result .= '"departamento":"'.$row['departamento'].'",';
			$result .= '"iva":"'.$row['iva'].'",';
			$result .= '"cuit":"'.$row['cuit'].'",';
			$result .= '"patente":"'.$row['patente'].'"';
			$result .= '},';
		}
		$result = substr($result, 0, strlen($result)-1);
		$result .= '}';
	}
	
	echo $result;
}

function validarUsuario($usuario,$password) {
	global $conn, $tabla;
	$query = "SELECT * FROM ".$tabla." WHERE usuario LIKE '".$usuario."'";
	$recordset = mysql_query($query, $conn) or die(mysql_error());
	$result = "";
	
	if(mysql_num_rows($recordset)==0) {
		$result = '{"status":"error","data":"Credenciales invalidas."}';
	} else {
		if($row = mysql_fetch_assoc($recordset)) {
			if($password==$row['password']) {
				$result = '{"status":"OK","data":{"usuario":"'.$usuario.'","perfil":"'.$row['perfil'].'"}}';
			} else {
				$result = '{"status":"error","data":"Credenciales invalidas."}';
			}
		}
	}
	
	echo $result;
}

function putUsuario() {
	global $conn, $tabla;

	$id = chequearCampo($_POST['id']);
	$legajo = chequearCampo($_POST['legajo']);
	$nombre = chequearCampo($_POST['nombre']);
	$apellido = chequearCampo($_POST['apellido']);
	$usuario = chequearCampo($_POST['usuario']);
	$perfil = chequearCampo($_POST['perfil']);
	$password = chequearCampo($_POST['password']);
	$departamento = chequearCampo($_POST['departamento']);
	$iva = chequearCampo($_POST['iva']);
	$cuit = chequearCampo($_POST['cuit']);
	$patente = chequearCampo($_POST['patente']);
	
	$query = "INSERT INTO ".$tabla." (legajo, nombre, apellido, usuario, perfil, password, departamento, iva, cuit, patente) VALUES ('$legajo', '$nombre','$apellido','$usuario',$perfil,'$password', $departamento, $iva, '$cuit', '$patente')";
	$result = mysql_query($query, $conn);
	if(!$result) {
		echo '{"status":"error","data":"'.mysql_error().'"}';
	} else {
		echo '{"status":"OK","data":"El usuario ha sido creado."}';
	}
}

function updateUsuario() {
	global $conn, $tabla;

	$id = chequearCampo($_POST['id']);
	$legajo = chequearCampo($_POST['legajo']);
	$nombre = chequearCampo($_POST['nombre']);
	$apellido = chequearCampo($_POST['apellido']);
	$usuario = chequearCampo($_POST['usuario']);
	$perfil = chequearCampo($_POST['perfil']);
	$password = chequearCampo($_POST['password']);
	$departamento = chequearCampo($_POST['departamento']);
	$iva = chequearCampo($_POST['iva']);
	$cuit = chequearCampo($_POST['cuit']);
	$patente = chequearCampo($_POST['patente']);
	$query = "UPDATE ".$tabla." SET legajo='$legajo', nombre='$nombre', apellido='$apellido', usuario='$usuario', perfil=$perfil, password='$password', departamento=$departamento, iva=$iva, cuit='$cuit', patente='$patente' WHERE idUsuario = ".$id;
	$result = mysql_query($query, $conn);
	if(!$result) {
		echo '{"status":"error","data":"'.mysql_error().'"}';
	} else {
		echo '{"status":"OK","data":"El usuario ha sido creado."}';
	}
}

function chequearCampo($campo) {
	if(!isset($campo) || $campo=="") {
		echo '{"status":"error","data":"El campo '.$campo.' no puede estar vacio"}';
		exit();
	}
	return $campo;
}
?>