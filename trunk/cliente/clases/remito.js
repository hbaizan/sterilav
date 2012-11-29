function CRemito() {
	var self = this;
	
	self.id = ko.observable();
	self.deposito = ko.observable(null);
	self.empresa = ko.observable(new CEmpresa());
	self.persona = ko.observable(null);
	self.chofer = ko.observable(new CChofer());
	self.fecha = ko.observable(new Date());
	self.lineaRemitos = ko.observableArray();
	self.tipo = ko.observable();
	
	self.cargar = function(jsonData) {

		self.id(jsonData.id);
		self.deposito(new CDeposito());
		//self.deposito().cargar(jsonData.deposito);
		//self.empresa().cargar(jsonData.empresa);
		self.persona(jsonData.persona);
		//self.chofer().cargar(jsonData.chofer);
		self.fecha(jsonData.fecha);
		self.tipo(jsonData.tipo);
	};	
	self.toJson = function() {
	};
	self.toString = function() {
	};
	self.salvar = function() {	
	};	
}