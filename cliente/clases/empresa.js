function CEmpresa() {
	var self = this;
	
	self.id = ko.observable();
	self.razonSocial = ko.observable().extend({required: true});
	self.cuit = ko.observable().extend({required: true, number: {params: true, message:'Ingrese un CUIT válido'}, minLength: {params: 11, message:'Ingrese un CUIT válido'}, maxLength: {params: 11, message:'Ingrese un CUIT válido'}});
	self.iva = ko.observable().extend({required: true});

	self.cargar = function(jsonData) {
		if(jsonData) {
			var exitoEmpresa = function(empData){
				self.id(empData.data.id);
				self.razonSocial(empData.data.razonSocial);
				self.cuit(empData.data.cuit);
				self.iva(empData.data.iva);
			};
			var fallaEmpresa = function(empError){
			};
			if(!jsonData.id) {
				getDataSync("./servidor/sterilav.php?op=getEmpresa&id=" + jsonData,exitoEmpresa,fallaEmpresa);
			} else {
				self.id(jsonData.id);
				self.razonSocial(jsonData.razonSocial);
				self.cuit(jsonData.cuit);
				self.iva(jsonData.iva);
			}
		}
	};
	
	self.toJson = function() {
		var empresa = {};
		empresa.id = self.id();
		empresa.razonSocial = self.razonSocial();
		empresa.cuit = self.cuit();
		empresa.iva = self.iva();
		
		return empresa;
	};
	
	self.mockup = function() {
		self.id(0);
		self.razonSocial("empresa");
		self.cuit("00000000000");
		self.iva(0);
	};
	
	self.toString = function() {
	};
	self.salvar = function() {	
	};
}
	