function CEmpresa() {
	var self = this;
	
	self.id = ko.observable();
	self.razonSocial = ko.observable().extend({required: true});
	self.cuit = ko.observable().extend({required: true});
	self.iva = ko.observable().extend({required: true});

	self.cargar = function(jsonData) {
		if(jsonData) {
			console.log("inicia carga Empresa");
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
	self.toString = function() {
	};
	self.salvar = function() {	
	};	
}
	