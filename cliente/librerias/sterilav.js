function getDataSync(url, exito, falla) {
	$.ajax({
		url:url,
		async:false,
		success:exito,
		error:falla,
		type:"GET",
		dataType:"json"
	});
}

function getData(url, exito, falla) {
	$.ajax({
		url:url,
		success:exito,
		error:falla,
		type:"GET",
		dataType:"json"
	});
}

function postData(url, exito, falla,datos) {
	$.ajax({
		url:url,
		success:exito,
		error:falla,
		data:datos,
		type:"POST",
		dataType:"json"
	});
}

function sterilavIni() {
	var self = this;
	
	self.cargaListas = function(contenedor, objeto) {		
		var exito = function(objetos) {
			if(objetos.status != "OK") {
				console.log("Error en carga de " + objeto);
			} else {
				for(var i=0; i< objetos.data.length; i++) {
					contenedor.push(objetos.data[i]);
				}
			}			
		};
		var falla = function(data) {
			console.log("Error en carga de " + objeto);
		};
		getDataSync("./servidor/sterilav.php?op=lista" + objeto,exito,falla);
	};
}

var sterilav = new sterilavIni();

ko.validation.init({
		grouping: {deep: true},
        registerExtenders: true,
        messagesOnModified: true,
        insertMessages: false,
        parseInputAttributes: true,
		errorMessageClass: 'error',
		errorElementClass: 'error',
		decorateElement: true,
        messageTemplate: null  /* Template not working  - exception */
}); 

function imprimir(objeto) {
	$('.print').printArea();
	return false;
}

function ModeloObjeto() {
	var self = this;
	
	self.id = ko.observable();

	self.cargar = function(jsonData) {
	};	
	self.toJson = function() {
	};
	self.toString = function() {
	};
	self.salvar = function() {	
	};
}
	