// Este es el script principal de la aplicacion
//Creado por Hugo Baizan
//08/06/2012

//Chequeo login
$(document).ready(function() {
	if(!amplify.store.sessionStorage("sterilav.usuarioLogueado")) {
		window.document.location="./cliente/paginas/login.html";
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
		amplify.store.sessionStorage("sterilav.perfilLogueado", null);	
		window.document.location="./cliente/paginas/login.html";
	};	
}

function Menu() {
	var self = this;
	
	self.permisos = ko.observableArray();
	
	self.getPermisos = function() {
		var exito = function(data) {
			if(data.status != "OK") {
			} else {
				for(var i=0; i< data.data.length; i++) {
					var newGrupo = new Grupo();
					newGrupo.nombre(data.data[i].grupo);
					for(var j=0; j< data.data[i].aplicaciones.length; j++) {
						var newPantalla = new Pantalla();
						newPantalla.pantalla = data.data[i].aplicaciones[j].pantalla;
						newPantalla.aplicacion = data.data[i].aplicaciones[j].aplicacion;
						newGrupo.pantallas().push(newPantalla);
					}
					self.permisos().push(newGrupo);
				}
			}
		};
		var falla = function(data) {
		};
		$.ajax({
			url:"./servidor/sterilav.php?op=listaPermisos&id="+amplify.store.sessionStorage("sterilav.perfilLogueado"),
			async:false,
			success:exito,
			error:falla,
			type:"GET",
			dataType:"json"
		});		
	};
	
	self.getPermisos();
}

function Grupo() {
	this.nombre = ko.observable("");
	this.pantallas = ko.observableArray();
}

function Pantalla() {
	var self = this;
	
	self.pantalla = ko.observable("");
	self.aplicacion = ko.observable("");
	
	self.abrir = function(item) {
		$("#container").load("./cliente/paginas/"+item.aplicacion+".html");
	};	
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
var menuModelo = new Menu();
ko.applyBindings(sesionModelo, document.getElementById("navBar")); 
ko.applyBindings(menuModelo, document.getElementById("menuLateral")); 