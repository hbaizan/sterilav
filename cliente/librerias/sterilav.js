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