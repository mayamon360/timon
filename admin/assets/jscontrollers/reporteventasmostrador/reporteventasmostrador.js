/* JS REPORTE VENTAS MOSTRADOR */

$(document).ready(function(){

var fi = '';
var ff = '';

if(localStorage.getItem("RangoFechasVentas") != null){
    
    $('#daterange-btn span').html(localStorage.getItem("RangoFechasVentas"));
    fi = moment(localStorage.getItem("fechaIncio"), 'YYYY-MM-DD');
    ff = moment(localStorage.getItem("fechaFin"), 'YYYY-MM-DD');
    $('.clearDateRange').removeClass('hidden');

    setTimeout(function() {
        graficoVentas(localStorage.getItem("fechaIncio"), localStorage.getItem("fechaFin"));
        graficoMasVendidos(localStorage.getItem("fechaIncio"), localStorage.getItem("fechaFin"));
        graficoTopClientes(localStorage.getItem("fechaIncio"), localStorage.getItem("fechaFin"));
        $(".overlay").addClass('hidden');
    }, 500);

    $("#descargar_reporte").attr('href', 'reporteVentasMostrador/descargar_reporte/'+localStorage.getItem("fechaIncio")+'/'+localStorage.getItem("fechaFin"));

}else{

    fi = moment('2019-01-01');
    ff = moment();

    setTimeout(function() {
        graficoVentas('2019-01-01', moment().format('YYYY-MM-DD'));
        graficoMasVendidos('2019-01-01', moment().format('YYYY-MM-DD'));
        graficoTopClientes('2019-01-01', moment().format('YYYY-MM-DD'));
        $(".overlay").addClass('hidden');
    }, 500);

    $("#descargar_reporte").attr('href', 'reporteVentasMostrador/descargar_reporte/2019-01-01/'+moment().format('YYYY-MM-DD'));
}

$('#daterange-btn').daterangepicker({
    "locale": {
        "format": "YYYY-MM-DD",
        "separator": " - ",
        "applyLabel": "Aplicar",
        "cancelLabel": '<i class="fas fa-times"></i>',
        "fromLabel": "De",
        "toLabel": "Hasta",
        "customRangeLabel": "Personalizado",
        "weekLabel": "W",
        "daysOfWeek": [
            "Dom",
            "Lun",
            "Mar",
            "Mié",
            "Jue",
            "Vie",
            "Sáb"
        ],
        "monthNames": [
            "Enero",
            "Febrero",
            "Marzo",
            "Abril",
            "Mayo",
            "Junio",
            "Julio",
            "Agosto",
            "Septiembre",
            "Octubre",
            "Noviembre",
            "Diciembre"
        ]
    },
    startDate: fi,
    endDate: ff,
    maxDate: moment(),
    showButtonPanel: true,
    ranges: {
        'Todo': [moment('2019-01-01'), moment()],
        'Hoy': [moment(), moment()],
        'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
        'Los últimos 7 días': [moment().subtract(6, 'days'), moment()],
        'Los últimos 30 días': [moment().subtract(29, 'days'), moment()],
        'Este mes': [moment().startOf('month'), moment().endOf('month')],
        'El mes pasado': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
    }
}, 
function (start, end) {
    
    $('#daterange-btn span').html(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'));

    var fechaInicio = start.format('YYYY-MM-DD');

    var fechaFin = end.format('YYYY-MM-DD');
    
    var rango = $('#daterange-btn span').html();
    
    localStorage.setItem("RangoFechasVentas",rango);
    localStorage.setItem("fechaIncio",fechaInicio);
    localStorage.setItem("fechaFin",fechaFin);

    $('.clearDateRange').removeClass('hidden');

    graficoVentas(fechaInicio, fechaFin);
    graficoMasVendidos(fechaInicio, fechaFin);
    graficoTopClientes(fechaInicio, fechaFin);

    $("#descargar_reporte").attr('href', 'reporteVentasMostrador/descargar_reporte/'+fechaInicio+'/'+fechaFin);

});

$(document).on('click', '.ranges ul li', function(e){
    if($(this).attr('data-range-key') == 'Todo'){
        limpiarFechas();
    }
});
$(document).on('click', '.clearDateRange, .cancelBtn', function(e){
    limpiarFechas();
});

})

function limpiarFechas(){
$('#daterange-btn span').html('<i class="fa fa-calendar"></i> Mostrando todo');
localStorage.removeItem("RangoFechasVentas");
localStorage.removeItem("fechaIncio");
localStorage.removeItem("fechaFin");
location.reload();
}

google.charts.load('current', {'packages': ['corechart','bar']});
google.charts.setOnLoadCallback();


/*
GRAFICO DE VENTAS
*/
function graficoVentas(start_date='', end_date='') {

$.ajax({

    type : 'POST',
    url: "api/cargarVentasMostrador",
    method:"POST",
    data:{metodo:'cargarVentas',start_date:start_date,end_date:end_date},
    success:function(data){
        console.log("data", data);
        if(data.status == 'success'){
            $("#total_ventas").html(data.total);
            var datos = data.message;
            cargarGraficoVentas(datos);
        }else{
            swal({
                title: "!Opss¡",
                text: data.message,
                icon: 'error',
                closeOnClickOutside: false,
                closeOnEsc: false,
                buttons: {
                    cancel: {
                        text: "Cerrar",
                        value: null,
                        visible: true,
                        className: "btn btn-sm btn-default",
                        closeModal: true,
                    }
                }
            })
            .then((value) => {
                $("#grafico-ventas .box-body #curve_chart").html('<p class="text-center">Sin datos para procesar</p>');

                $("#grafico-mas-vendidos .box-body #piechart").html('<p class="text-center">Sin datos para procesar</p>');
                $("#grafico-mas-vendidos .box-footer .ulmasVendidos").html('');

                $("#grafico-top-clientes .box-body #piechartClientes").html('<p class="text-center">Sin datos para procesar</p>');
                $("#grafico-top-clientes .box-footer .ultopClientes").html('');

                $("#total_ventas").html('');
            })
        }
        
    }
})

}
function cargarGraficoVentas(datos) {


var jsonData = datos;

var data = new google.visualization.DataTable();
data.addColumn('string', 'Fecha');
data.addColumn('number', 'Monto ($)');

$.each(jsonData, function(i, jsonData){

    var fecha = jsonData.fecha;

    var salidas = parseFloat($.trim(jsonData.salidas));

    data.addRows([[fecha, salidas]]);
})

var options = {
    fontSize: 12,
    curveType: 'none',
    pointSize: 7,
    legend: { position: 'bottom' },
    vAxis : {format: 'currency'},
    explorer: { actions: ['dragToZoom'] }
}

var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));
chart.draw(data, options);

}
/* 
/.GRAFICO DE VENTAS
*/

/*
GRAFICO DE PRODUCTOS MÁS VENDIDOS
*/
function graficoMasVendidos(start_date='', end_date='') {

$.ajax({

    type : 'POST',
    url: "api/cargarMasVendidosMostrador",
    method:"POST",
    data:{metodo:'cargarMasVendidos',start_date:start_date,end_date:end_date},
    success:function(data)
    {   
        if(data.status == 'success'){
            $('.ulmasVendidos').html(data.li_masVendidos);
            var datos = data.message;
            cargarGraficoMasVendidos(datos);
        }
    }
})

}
function cargarGraficoMasVendidos(datos) {

var jsonData = datos;

var data = new google.visualization.DataTable();
data.addColumn('string', 'Producto');
data.addColumn('number', 'Salidas');
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

}
/* 
/.GRAFICO DE PRODUCTOS MÁS VENDIDOS
*/

/*
GRAFICO DE MEJORES CLIENTES
*/
function graficoTopClientes(start_date='', end_date='') {

$.ajax({

    type : 'POST',
    url: "api/cargarTopClientes",
    method:"POST",
    data:{metodo:'cargarTopClientes',start_date:start_date,end_date:end_date},
    success:function(data)
    {   
        if(data.status == 'success'){
            $('.ultopClientes').html(data.li_topClientes);
            var datos = data.message;
            cargarGraficoTopClientes(datos);
        }
    }
})

}
function cargarGraficoTopClientes(datos) {

var jsonData = datos;

var data = new google.visualization.DataTable();
data.addColumn('string', 'Cliente');
data.addColumn('number', 'Cantidad');
data.addColumn({ type: 'string', role: 'style' });

$.each(jsonData, function(i, jsonData){

    var cliente = jsonData.cliente;
    var porcentaje = parseFloat($.trim(jsonData.porcentaje));
    var color = jsonData.color;

    data.addRows([[cliente, porcentaje, color]]);
});

var options = {
  legend: { position: 'none' },
  bar: { groupWidth: "80%" }
};

var chart = new google.visualization.BarChart(document.getElementById('piechartClientes'));

chart.draw(data, options);

}
/* 
/.GRAFICO DE MEJORES CLIENTES
*/

/* JS REPORTE VENTAS MOSTRADOR */