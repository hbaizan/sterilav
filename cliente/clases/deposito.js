function CDeposito() {
	var self = this;
	
	self.id = ko.observable();
	self.nombre = ko.observable().extend({required: true});
	self.domicilio = ko.observable().extend({required: true});
	self.departamento = ko.observable().extend({required: true});
	self.empresa = ko.observable(new CEmpresa()).extend({required: true});
	self.remito = ko.observable(false).extend({required: true});
	self.activo = ko.observable(false).extend({required: true});		
	self.productos = ko.observableArray();
	
	self.cargar = function(jsonData) {
		console.log("inicia carga Deposito");
		
		self.id(jsonData.id);
		self.nombre(jsonData.nombre);
		self.domicilio(jsonData.domicilio);
		self.departamento(jsonData.departamento);
		self.empresa().cargar(jsonData.idempresa);
		self.remito(jsonData.remito);
		self.activo(jsonData.activo);
	};
	
	self.toJson = function() {
		var deposito = {};
		deposito.id = self.id();
		deposito.nombre = self.idnombre;
		deposito.domicilio = self.domicilio();
		deposito.departamento = self.departamento();
		deposito.empresa = self.empresa().toJson();
		deposito.remito = self.remito();
		deposito.activo = self.activo();
		
		return deposito;
	};
	self.toString = function() {
	};
	self.salvar = function() {	
	};
}
	