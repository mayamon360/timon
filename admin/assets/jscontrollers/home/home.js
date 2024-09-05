$(document).ready(function(){

	//google.charts.load('current', {'packages': ['corechart', 'geochart', 'bar']});
	
	/*
		GRAFICO DE VENTAS
	*/
	//google.charts.setOnLoadCallback(graficoVentas);

	/*
		GRAFICO DE VISITAS
	*/
	//google.charts.setOnLoadCallback(graficoVisitas);

	/*
		GRAFICO MÁS VENDIDOS
	*/
	//google.charts.setOnLoadCallback(graficoMasVendidos);

	/* Cargar las variables para el día de HOY */
        /*var fechaInicio = moment().format('YYYY-MM-DD');
        var fechaFin = moment().format('YYYY-MM-DD');
        localStorage.setItem("RangoFechasVentas",fechaInicio+' - '+fechaFin);
        localStorage.setItem("RangoFechasCompras",fechaInicio+' - '+fechaFin);
        localStorage.setItem("fechaIncio",fechaInicio);
        localStorage.setItem("fechaFin",fechaFin);*/

})


/*
	GRAFICO DE VENTAS
*/
/*function graficoVentas() {

    $.ajax({

        type : 'POST',
        url: "api/cargarVentas",
        method:"POST",
        data:{metodo : 'cargarVentas'},
        success:function(data)
        {
            if(data.status == 'success'){
                var datos = data.message;
                cargarGraficoVentas(datos);
            }else{
                $("#grafico-ventas-online .box-body #curve_chart").html('<p class="text-center">Sin datos para procesar</p>');
                $("#grafico-ventas-online .box-footer").html('');
            }
        }
    })

}*/
/*function cargarGraficoVentas(datos) {

    var jsonData = datos;

    var data = new google.visualization.DataTable();
    data.addColumn('string', 'Fecha');
    data.addColumn('number', 'Ventas');

    $.each(jsonData, function(i, jsonData){

    	var fecha = jsonData.fecha;
    	var mes = fecha.split(" ")[0];
    	var anio = fecha.split(" ")[1];
		var fechaFormato = meses[mes-1];

        var ventas = parseFloat($.trim(jsonData.ventas));

        data.addRows([[fechaFormato+' '+anio, ventas]]);
    })

    var options = {
        fontSize: 12,
        curveType: 'function',
        pointSize: 7,
        legend: { position: 'bottom' },
        vAxis : {format: 'currency'},
        explorer: { actions: ['dragToZoom', 'rightClickToReset'] }
    }

    var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));
    chart.draw(data, options);

}*/
/* 
	/.GRAFICO DE VENTAS
*/

/*
	GRAFICO DE VISITAS
*/
/*function graficoVisitas() {

    $.ajax({

        type : 'POST',
        url: "api/cargarVisitas",
        method:"POST",
        data:{metodo : 'cargarVisitas'},
        success:function(data)
        {
        	var datos = data.message;
            cargarGraficoVisitas(datos);
        }
    })

}
function cargarGraficoVisitas(datos) {

    var jsonData = datos;

    var data = new google.visualization.DataTable();
    data.addColumn('string', 'Región');
    data.addColumn('number', 'Visitas');

    $.each(jsonData, function(i, jsonData){

    	var region = jsonData.region;
        var visitas = parseInt($.trim(jsonData.visitas));

        data.addRows([[region, visitas]]);
    });

    var options = {
        region: 'MX', // Mexico
        resolution: 'provinces',
        colorAxis: {colors: ['#ffa0a0', '#ff0000']},
        backgroundColor: '#FFF',
        datalessRegionColor: '#FFF',
    }

    var chart = new google.visualization.GeoChart(document.getElementById('geochart-colors'));
    chart.draw(data, options);

}*/
/* 
	/.GRAFICO DE VISITAS
*/

/*
	GRAFICO DE PRODUCTOS MÁS VENDIDOS
*/
/*function graficoMasVendidos() {

    $.ajax({

        type : 'POST',
        url: "api/cargarMasVendidos",
        method:"POST",
        data:{metodo : 'cargarMasVendidos'},
        success:function(data)
        {   
            if(data.status == 'success'){
                var datos = data.message;
                cargarGraficoMasVendidos(datos);
            }else{
                $("#grafico-mas-vendidos-online .box-body #piechart").html('<p class="text-center">Sin datos para procesar</p>');
                $("#grafico-mas-vendidos-online .box-footer .ulmasVendidos").html('');
            }
        }
    })

}
function cargarGraficoMasVendidos(datos) {

    var jsonData = datos;

    var data = new google.visualization.DataTable();
    data.addColumn('string', 'Producto');
    data.addColumn('number', 'Ventas');
    data.addColumn({ type: 'string', role: 'style' });

    $.each(jsonData, function(i, jsonData){

    	var producto = jsonData.producto;
        var porcentaje = parseFloat($.trim(jsonData.porcentaje));
        var color = jsonData.color;

        data.addRows([[producto, porcentaje, color]]);
    });

    var options = {
      legend: { position: 'none' },
      bar: { groupWidth: "80%" }
    };

    var chart = new google.visualization.BarChart(document.getElementById('piechart'));

    chart.draw(data, options);

}*/
/* 
	/.GRAFICO DE PRODUCTOS MÁS VENDIDOS
*/

function ventasPorMes() {

    var fecha = new Date();
    var anio = fecha.getFullYear();

    $.ajax({
        type : 'POST',
        url: "api/ventasPorMes",
        method:"POST",
        data:{anio : anio},
        success:function(json)
        {   
            $("table#ventasPorMes tbody").html(json.tbody);
        },
        error : function(xhr, status) {
            alert('Error al mostrar las ventas');
        }
    })

}

ventasPorMes();

function comprasPorMes() {

    var fecha = new Date();
    var anio = fecha.getFullYear();

    $.ajax({
        type : 'POST',
        url: "api/comprasPorMes",
        method:"POST",
        data:{anio : anio},
        success:function(json)
        {   
            $("table#comprasPorMes tbody").html(json.tbody);
        },
        error : function(xhr, status) {
            alert('Error al mostrar las compras');
        }
    })

}

comprasPorMes();