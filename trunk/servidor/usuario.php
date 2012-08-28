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
			$result .= '"password":"'.$row['password'].'"';
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
			$result .= '"legajo":"'.$row['legajo'].'",';
			$result .= '"perfil":"'.$row['perfil'].'",';
			$result .= '"usuario":"'.$row['usuario'].'",';
			$result .= '"password":"'.$row['password'].'"';
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
				$result = '{"result":"OK","data":"usuario":"'.$usuario.'"}';
			} else {
				$result = '{"result":"error","data":"Credenciales invalidas."}';
			}
		}
	}
	
	echo $result;
}

function putUsuario() {
	global $conn, $tabla;
	if(!isset($_POST['legajo']) || $_POST['legajo']=="") {
		echo '{"status":"error","data":"El legajo no puede estar vacio"}';
		exit();
	}
	$legajo = $_POST['legajo'];
	if(!isset($_POST['nombre']) || $_POST['nombre']=="") {
		echo '{"status":"error","data":"El nombre no puede estar vacio"}';
		exit();
	}
	$nombre = $_POST['nombre'];
	if(!isset($_POST['apellido']) || $_POST['apellido']=="") {
		echo '{"status":"error","data":"El apellido no puede estar vacio"}';
		exit();
	}
	$apellido = $_POST['apellido'];
	if(!isset($_POST['usuario']) || $_POST['usuario']=="") {
		echo '{"status":"error","data":"El usuario no puede estar vacio"}';
		exit();
	}
	$usuario = $_POST['usuario'];
	if(!isset($_POST['perfil']) || $_POST['perfil']=="") {
		echo '{"status":"error","data":"El perfil no puede estar vacio"}';
		exit();
	}
	$perfil = $_POST['perfil'];
	if(!isset($_POST['password']) || $_POST['password']=="") {
		echo '{"status":"error","data":"La contraseña no puede estar vacia"}';
		exit();
	}
	$password = $_POST['password'];
	$query = "INSERT INTO ".$tabla." (legajo, nombre, apellido, usuario, perfil, password) VALUES ('$legajo', '$nombre','$apellido','$usuario',$perfil,'$password')";
	$result = mysql_query($query, $conn);
	if(!$result) {
		echo '{"status":"error","data":"'.mysql_error().'"}';
	} else {
		echo '{"status":"OK","data":"El usuario ha sido creado."}';
	}
}

function updateUsuario() {
	global $conn, $tabla;
	if(!isset($_POST['id']) || $_POST['id']=="") {
		echo '{"status":"error","data":"El id no puede estar vacio"}';
		exit();
	}
	$id = $_POST['id'];
	if(!isset($_POST['legajo']) || $_POST['legajo']=="") {
		echo '{"status":"error","data":"El legajo no puede estar vacio"}';
		exit();
	}
	$legajo = $_POST['legajo'];
	if(!isset($_POST['nombre']) || $_POST['nombre']=="") {
		echo '{"status":"error","data":"El nombre no puede estar vacio"}';
		exit();
	}
	$nombre = $_POST['nombre'];
	if(!isset($_POST['apellido']) || $_POST['apellido']=="") {
		echo '{"status":"error","data":"El apellido no puede estar vacio"}';
		exit();
	}
	$apellido = $_POST['apellido'];
	if(!isset($_POST['usuario']) || $_POST['usuario']=="") {
		echo '{"status":"error","data":"El usuario no puede estar vacio"}';
		exit();
	}
	$usuario = $_POST['usuario'];
	if(!isset($_POST['perfil']) || $_POST['perfil']=="") {
		echo '{"status":"error","data":"El perfil no puede estar vacio"}';
		exit();
	}
	$perfil = $_POST['perfil'];
	if(!isset($_POST['password']) || $_POST['password']=="") {
		echo '{"status":"error","data":"La contraseña no puede estar vacia"}';
		exit();
	}
	$password = $_POST['password'];
	$query = "UPDATE ".$tabla." SET legajo='$legajo', nombre='$nombre', apellido='$apellido', usuario='$usuario', perfil=$perfil, password='$password' WHERE idUsuario = ".$id;
	$result = mysql_query($query, $conn);
	if(!$result) {
		echo '{"status":"error","data":"'.mysql_error().'"}';
	} else {
		echo '{"status":"OK","data":"El usuario ha sido creado."}';
	}
}
?>