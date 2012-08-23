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
		$("#menuLateral").hide();
		$("#logoutLink").hide();
	};
}

function abrir(pagina) {
	switch(pagina) {
		default:
			$("#container").load("./cliente/paginas/" + pagina + ".html");
			break;
	}
}

function getUrlVars() {
    var vars = [], hash;
    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
    for(var i = 0; i < hashes.length; i++)
    {
        hash = hashes[i].split('=');
        vars.push(hash[0]);
        vars[hash[0]] = hash[1];
    }
    return vars;
}

ko.validation.init({
		grouping: {deep: true},
        registerExtenders: true,
        messagesOnModified: true,
        insertMessages: false,
        parseInputAttributes: true,
		errorMessageClass: 'error',
		errorElementClass: 'error',
        messageTemplate: null  /* Template not working  - exception */
    }); 

var sesionModelo = new Sesion();
ko.applyBindings(sesionModelo, document.getElementById("navBar")); 	
