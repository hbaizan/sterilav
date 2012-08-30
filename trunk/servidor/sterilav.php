<?php require_once('./db.php');
require_once('./grupo.php');
require_once('./perfil.php');
require_once('./cliente.php');
require_once('./producto.php');
require_once('./usuario.php');
require_once('./iva.php');
require_once('./departamento.php');

mysql_select_db($database_conn, $conn);

switch($_GET['op']) {
	case "listaGrupos":
		echo listaGrupos();
		break;
	case "getGrupo":
		echo getGrupo($_GET['id']);
		break;
	case "listaPerfiles":
		echo listaPerfiles();
		break;
	case "listaPermisos":
		echo listaPermisos($_GET['id']);
		break;
	case "getPerfil":
		echo getPerfil($_GET['id']);
		break;
	case "listaClientes":
		echo listaClientes();
		break;
	case "getCliente":
		echo getCliente($_GET['id']);
		break;
	case "putCliente":
		echo putCliente($_POST['cliente']);
		break;
	case "listaProductos":
		echo listaProductos();
		break;
	case "getProducto":
		echo getProducto($_GET['id']);
		break;
	case "putProducto":
		echo putProducto();
		break;
	case "updateProducto":
		echo updateProducto();
		break;
	case "listaUsuarios":
		echo listaUsuarios();
		break;
	case "getUsuario":
		echo getUsuario($_GET['id']);
		break;
	case "putUsuario":
		echo putUsuario();
		break;
	case "updateUsuario":
		echo updateUsuario();
		break;
	case "validarUsuario":
		echo validarUsuario($_GET['usuario'],$_GET['password']);
		break;	
	case "listaIvas":
		echo listaIvas();
		break;
	case "getIva":
		echo getIva($_GET['id']);
		break;
	case "listaDepartamentos":
		echo listaDepartamentos();
		break;
	case "getDepartamento":
		echo getDepartamento($_GET['id']);
		break;
}

function chequearCampo($campo) {
	if(!isset($campo) || $campo=="") {
		echo '{"status":"error","data":"El campo '.$campo.' no puede estar vacio"}';
		exit();
	}
	return $campo;
}

?>