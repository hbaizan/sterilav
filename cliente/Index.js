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
		if(self.esNuevoObjeto(item.aplicacion)) {
			self.limpiarObjetos();
		}
		$("#container").load("./cliente/paginas/"+item.aplicacion+".html");
	};	
	self.esNuevoObjeto = function(app) {
		return (app=="usuario") || (app=="vehiculo") || (app=="subgrupo") || (app=="producto") || (app=="grupo") || (app=="empresa") || (app=="deposito") || (app=="chofer") || (app=="remitoEntrada");
	};
	self.limpiarObjetos = function() {
		amplify.store.sessionStorage("sterilav.remitoEditado", null);
		amplify.store.sessionStorage("sterilav.usuarioEditado", null);
		amplify.store.sessionStorage("sterilav.vehiculoEditado", null);
		amplify.store.sessionStorage("sterilav.subgrupoEditado", null);
		amplify.store.sessionStorage("sterilav.productoEditado", null);
		amplify.store.sessionStorage("sterilav.grupoEditado", null);
		amplify.store.sessionStorage("sterilav.empresaEditado", null);
		amplify.store.sessionStorage("sterilav.depositoEditado", null);
		amplify.store.sessionStorage("sterilav.choferEditado", null);
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

var sesionModelo = new Sesion();
var menuModelo = new Menu();
ko.applyBindings(sesionModelo, document.getElementById("navBar")); 
ko.applyBindings(menuModelo, document.getElementById("menuLateral")); 

ko.bindingHandlers.autoComplete = {
    findSelectedItem: function (dataSource, binding, selectedValue) {
        var unwrap = ko.utils.unwrapObservable;
        //Go through the source and find the id, and use its label to set the autocomplete
        var source = unwrap(dataSource);
        var valueProp = unwrap(binding.optionsValue);

        var selectedItem = ko.utils.arrayFirst(source, function (item) {
            if (unwrap(item[valueProp]) === selectedValue)
                return true;
        }, this);

        return selectedItem;
    },
    buildDataSource: function (dataSource, labelProp, valueProp) {
        var unwrap = ko.utils.unwrapObservable;
        var source = unwrap(dataSource);
        var mapped = ko.utils.arrayMap(source, function (item) {
            var result = {};
            result.label = labelProp ? unwrap(item[labelProp]) : unwrap(item).toString();  //show in pop-up choices
            result.value = valueProp ? unwrap(item[valueProp]) : unwrap(item).toString();  //value
            return result;
        });
        return mapped;
    },
    init: function (element, valueAccessor, allBindingsAccessor, viewModel) {
        var unwrap = ko.utils.unwrapObservable;
        var dataSource = valueAccessor();
        var binding = allBindingsAccessor();
        var valueProp = unwrap(binding.optionsValue);
        var labelProp = unwrap(binding.optionsText) || valueProp;
        var displayId = $(element).attr('id') + '-display';
        var displayElement;
        var options = {};

        if (binding.autoCompleteOptions) {
            options = $.extend(options, binding.autoCompleteOptions);
        }

        //Create a new input to be the autocomplete so that the label shows
        // also hide the original control since it will be used for the value binding
        $(element).hide();
        $(element).after('<input type="text" id="' + displayId + '" />')
        displayElement = $('#' + displayId);

        //handle value changing
        var modelValue = binding.value;
        if (modelValue) {
            var handleValueChange = function (event, ui) {
                var labelToWrite = ui.item ? ui.item.label : null
                var valueToWrite = ui.item ? ui.item.value : null;
                //The Label and Value should not be null, if it is
                // then they did not make a selection so do not update the
                // ko model
                if (labelToWrite && valueToWrite) {
                    if (ko.isWriteableObservable(modelValue)) {
                        //Since this is an observable, the update part will fire and select the
                        //  appropriate display values in the controls
                        modelValue(valueToWrite);
                    } else {  //write to non-observable
                        if (binding['_ko_property_writers'] && binding['_ko_property_writers']['value']) {
                            binding['_ko_property_writers']['value'](valueToWrite);
                            //Because this is not an observable, we have to manually change the controls values
                            // since update will not do it for us (it will not fire since it is not observable)
                            displayElement.val(labelToWrite);
                            $(element).val(valueToWrite);
                        }
                    }
                }
                //They did not make a valid selection so change the autoComplete box back to the previous selection
                else {
                    var currentModelValue = unwrap(modelValue);
                    //If the currentModelValue exists and is not nothing, then find out the display
                    // otherwise just blank it out since it is an invalid value
                    if (!currentModelValue)
                        displayElement.val('');
                    else {
                        //Go through the source and find the id, and use its label to set the autocomplete
                        var selectedItem = ko.bindingHandlers.autoComplete.findSelectedItem(dataSource, binding, currentModelValue);           

                  //If we found the item then update the display
                        if (selectedItem) {
                            var displayText = labelProp ? unwrap(selectedItem[labelProp]) : unwrap(selectedItem).toString();
                            displayElement.val(displayText);
                        }
                        //if we did not find the item, then just blank it out, because it is an invalid value
                        else {
                            displayElement.val('');
                        }
                    }
                }

                return false;
            };

            var handleFocus = function (event, ui) {
                $(displayElement).val(ui.item.label);
                return false;
            };

            options.change = handleValueChange;
            options.select = handleValueChange;
            options.focus = handleFocus;
            //options.close = handleValueChange;
        }

        //handle the choices being updated in a Dependant Observable (DO), so the update function doesn't
        // have to do it each time the value is updated. Since we are passing the dataSource in DO, if it is
        // an observable, when you change the dataSource, the dependentObservable will be re-evaluated
        // and its subscribe event will fire allowing us to update the autocomplete datasource
        var mappedSource = ko.dependentObservable(function () {
            return ko.bindingHandlers.autoComplete.buildDataSource(dataSource, labelProp, valueProp);
        }, viewModel);
        //Subscribe to the knockout observable array to get new/remove items
        mappedSource.subscribe(function (newValue) {
            displayElement.autocomplete("option", "source", newValue);
        });

        options.source = mappedSource();

        displayElement.autocomplete(options);
    },
    update: function (element, valueAccessor, allBindingsAccessor, viewModel) {
        //update value based on a model change
        var unwrap = ko.utils.unwrapObservable;
        var dataSource = valueAccessor();
        var binding = allBindingsAccessor();
        var valueProp = unwrap(binding.optionsValue);
        var labelProp = unwrap(binding.optionsText) || valueProp;
        var displayId = $(element).attr('id') + '-display';
        var displayElement = $('#' + displayId);
        var modelValue = binding.value;

        if (modelValue) {
            var currentModelValue = unwrap(modelValue);
            //Set the hidden box to be the same as the viewModels Bound property
            $(element).val(currentModelValue);
            //Go through the source and find the id, and use its label to set the autocomplete
            var selectedItem = ko.bindingHandlers.autoComplete.findSelectedItem(dataSource, binding, currentModelValue);
            if (selectedItem) {
                var displayText = labelProp ? unwrap(selectedItem[labelProp]) : unwrap(selectedItem).toString();
                displayElement.val(displayText);
            }
        }
    }
};