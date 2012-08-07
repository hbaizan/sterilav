// Este es el script principal de la aplicacion
//Creado por Hugo Baizan
//08/06/2012

//Chequeo login
$(document).ready(function() {
	if(!amplify.store.sessionStorage("sterilav.usuarioLogueado")) {
		$("#container").load("./cliente/paginas/login.html");		
		$("#logoutLink").hide();
	} else {
		$("#container").load("./cliente/paginas/principal.html");		
		$("#logoutLink").show();
	}
});

function Sesion() { 
	var self = this;

	self.logout = function() {
		amplify.store.sessionStorage("sterilav.usuarioLogueado", null);
		$("#container").load("./cliente/paginas/login.html");	
		$("#logoutLink").hide();
	};
}

var sesionModelo = new Sesion();
ko.applyBindings(sesionModelo, document.getElementById("navBar")); 	
