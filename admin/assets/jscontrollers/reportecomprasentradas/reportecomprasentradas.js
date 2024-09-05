$(document).ready(function(){

    var fi = '';
    var ff = '';

    if(localStorage.getItem("RangoFechasCompras") != null){
        
        $('#daterange-btn span').html(localStorage.getItem("RangoFechasCompras"));
        fi = moment(localStorage.getItem("fechaIncio"), 'YYYY-MM-DD');
        ff = moment(localStorage.getItem("fechaFin"), 'YYYY-MM-DD');
        $('.clearDateRange').removeClass('hidden');

        setTimeout(function() {
            graficoCompras(localStorage.getItem("fechaIncio"), localStorage.getItem("fechaFin"));
            graficoMasComprados(localStorage.getItem("fechaIncio"), localStorage.getItem("fechaFin"));
            graficoTopProveedores(localStorage.getItem("fechaIncio"), localStorage.getItem("fechaFin"));
            $(".overlay").addClass('hidden');
        }, 500);

        $("#descargar_reporte").attr('href', 'reporteComprasEntradas/descargar_reporte/'+localStorage.getItem("fechaIncio")+'/'+localStorage.getItem("fechaFin"));
    
    }else{

        fi = moment('2019-01-01');
        ff = moment();

        setTimeout(function() {
            graficoCompras('2019-01-01', moment().format('YYYY-MM-DD'));
            graficoMasComprados('2019-01-01', moment().format('YYYY-MM-DD'));
            graficoTopProveedores('2019-01-01', moment().format('YYYY-MM-DD'));
            $(".overlay").addClass('hidden');
        }, 500);

        $("#descargar_reporte").attr('href', 'reporteComprasEntradas/descargar_reporte/2019-01-01/'+moment().format('YYYY-MM-DD'));
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
        
        localStorage.setItem("RangoFechasCompras",rango);
        localStorage.setItem("fechaIncio",fechaInicio);
        localStorage.setItem("fechaFin",fechaFin);

        $('.clearDateRange').removeClass('hidden');

        graficoCompras(fechaInicio, fechaFin);
        graficoMasComprados(fechaInicio, fechaFin);
        graficoTopProveedores(fechaInicio, fechaFin);

        $("#descargar_reporte").attr('href', 'reporteComprasEntradas/descargar_reporte/'+fechaInicio+'/'+fechaFin);

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
    localStorage.removeItem("RangoFechasCompras");
    localStorage.removeItem("fechaIncio");
    localStorage.removeItem("fechaFin");
    location.reload();
}

google.charts.load('current', {'packages': ['corechart','bar']});
google.charts.setOnLoadCallback();


/*
    GRAFICO DE COMPRAS
*/
function graficoCompras(start_date='', end_date='') {

    $.ajax({

        type : 'POST',
        url: "api/cargarCompras",
        method:"POST",
        data:{metodo:'cargarCompras',start_date:start_date,end_date:end_date},
        success:function(data){
            
            if(data.status == 'success'){
                $("#total_compras").html(data.total);
                var datos = data.message;
                cargarGraficoCompras(datos);
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
                    $("#grafico-compras .box-body #curve_chart").html('<p class="text-center">Sin datos para procesar</p>');

                    $("#grafico-mas-comprados .box-body #piechart").html('<p class="text-center">Sin datos para procesar</p>');
                    $("#grafico-mas-comprados .box-footer .ulmasComprados").html('');

                    $("#grafico-top-proveedores .box-body #piechartProveedores").html('<p class="text-center">Sin datos para procesar</p>');
                    $("#grafico-top-proveedores .box-footer .ultopProveedores").html('');

                    $("#total_compras").html('');
                })
            }
            
        }
    })

}
function cargarGraficoCompras(datos) {


    var jsonData = datos;

    var data = new google.visualization.DataTable();
    data.addColumn('string', 'Fecha');
    data.addColumn('number', 'Monto ($)');

    $.each(jsonData, function(i, jsonData){

        var fecha = jsonData.fecha;

        var compras = parseFloat($.trim(jsonData.compras));

        data.addRows([[fecha, compras]]);
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
    /.GRAFICO DE COMPRAS
*/

/*
    GRAFICO DE PRODUCTOS MÁS COMPRADOS
*/
function graficoMasComprados(start_date='', end_date='') {

    $.ajax({

        type : 'POST',
        url: "api/cargarMasComprados",
        method:"POST",
        data:{metodo:'cargarMasComprados',start_date:start_date,end_date:end_date},
        success:function(data)
        {   
            if(data.status == 'success'){
                $('.ulmasComprados').html(data.li_masComprados);
                var datos = data.message;
                cargarGraficoMasComprados(datos);
            }
        }
    })

}
function cargarGraficoMasComprados(datos) {

    var jsonData = datos;

    var data = new google.visualization.DataTable();
    data.addColumn('string', 'Producto');
    data.addColumn('number', 'Entradas');
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
    /.GRAFICO DE PRODUCTOS MÁS COMPRADOS
*/

/*
    GRAFICO DE MEJORES PROVEEDORES
*/
function graficoTopProveedores(start_date='', end_date='') {

    $.ajax({

        type : 'POST',
        url: "api/cargarTopProveedores",
        method:"POST",
        data:{metodo:'cargarTopProveedores',start_date:start_date,end_date:end_date},
        success:function(data)
        {   
            console.log("data", data);
            if(data.status == 'success'){
                $('.ultopProveedores').html(data.li_topProveedores);
                var datos = data.message;
                cargarGraficoTopProveedores(datos);
            }
        }
    })

}
function cargarGraficoTopProveedores(datos) {

    var jsonData = datos;

    var data = new google.visualization.DataTable();
    data.addColumn('string', 'Proveedor');
    data.addColumn('number', 'Cantidad');
    data.addColumn({ type: 'string', role: 'style' });

    $.each(jsonData, function(i, jsonData){

        var proveedor = jsonData.proveedor;
        var porcentaje = parseFloat($.trim(jsonData.porcentaje));
        var color = jsonData.color;

        data.addRows([[proveedor, porcentaje, color]]);
    });

    var options = {
      legend: { position: 'none' },
      bar: { groupWidth: "80%" }
    };

    var chart = new google.visualization.BarChart(document.getElementById('piechartProveedores'));

    chart.draw(data, options);

}
/* 
    /.GRAFICO DE MEJORES CLIENTES
*/