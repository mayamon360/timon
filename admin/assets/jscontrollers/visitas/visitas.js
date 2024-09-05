$(document).ready(function(){

    google.charts.load('current', {'packages': ['geochart']});

    /* GRAFICO DE VISITAS */
    google.charts.setOnLoadCallback(graficoVisitas);

    /* Cargar los datos en la tabla */
    var groupColumn = 5;
    var dataTable = $('#tablaVisitas').DataTable({
        "lengthMenu": [[25, 50, 100, 250, 500, -1], [25, 50, 100, 250, 500, "Todos"]],
         /*buttons: [
            {
                extend: 'excelHtml5',
                title: 'Ventas',
                text: 'Excel',
                className: 'btn btn-default',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5],

                }
            }
        ],*/
        "responsive" : true,
        "order": [[ groupColumn, 'desc' ]],
        "ajax" : {
            url:"api/mostrarVisitas",
            type:"POST"
        },
        "deferRender": true, 
        "retrieve" : true,
        "processing" : true,
        "columnDefs" : [
            {
                "targets" : [3],
                "orderable" : false,
                "className": "text-right",
            },
            { 
                "visible": false, 
                "targets": groupColumn 
            }
        ],
        "language": {
            "decimal":        "",
            "emptyTable":     "No hay datos disponibles en la tabla",
            "info":           "Mostrando _START_ al _END_ de _TOTAL_ registros",
            "infoEmpty":      "Mostrando 0 a 0 de 0 registros",
            "infoFiltered":   "(filtrado de _MAX_ entradas totales)",
            "infoPostFix":    "",
            "thousands":      ",",
            "lengthMenu":     "Mostrar _MENU_ registros",
            "loadingRecords": "Cargando...",
            "processing":     "Procesando...",
            "search":         "Buscar:",
            "zeroRecords":    "No se encontraron registros coincidentes",
            "paginate": {
                "first":      "Primero",
                "last":       "Último",
                "next":       "Siguiente",
                "previous":   "Anterior"
            },
            "aria": {
                "sortAscending":  ": activar para ordenar la columna ascendente",
                "sortDescending": ": activar para ordenar la columna descendente"
            }
        },
        "footerCallback": function ( row, data, start, end, display ) {
            var api = this.api(), data;
 
            // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };
 
            // Total over all pages
            total = api
                .column( 3 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Total over this page
            pageTotal = api
                .column( 3, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Update footer
            $( api.column( 3 ).footer() ).html(
                pageTotal
            );

            $( api.column( 4 ).footer() ).html(
                '(de '+ total +')'
            );
        },
        "drawCallback": function ( settings ) {
            var api = this.api();
            var rows = api.rows( {page:'current'} ).nodes();
            var last=null;
 
            api.column(groupColumn, {page:'current'} ).data().each( function ( group, i ) {
                if ( last !== group ) {
                    $(rows).eq( i ).before(
                        '<tr class="group"><td colspan="5" class="text-center"><span class="badge bg-aqua">'+group+'</span></td></tr>'
                    );
 
                    last = group;
                }
            } );
        }
    });
    // Order by the grouping
    $('#tablaVisitas tbody').on( 'click', 'tr.group', function () {
        var currentOrder = dataTable.order()[0];
        if ( currentOrder[0] === groupColumn && currentOrder[1] === 'asc' ) {
            dataTable.order( [ groupColumn, 'desc' ] ).draw();
        }
        else {
            dataTable.order( [ groupColumn, 'asc' ] ).draw();
        }
    });
    $("#ExportReporttoExcel").on("click", function(e) {
        e.defaultPrevented;
        //dataTable.button('.buttons-excel').trigger(); Está linea la podemos activar si deseamos que se haga con la acción del botón datatables
    });
    /* /.Cargar los datos en la tabla */
})


/* GRAFICO DE VISITAS */
function graficoVisitas() {

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

}
/* /.GRAFICO DE VISITAS */