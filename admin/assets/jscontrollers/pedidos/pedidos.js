/* JS PEDIDOS */
$(function(){

    var fi = '';
    var ff = '';

    if(localStorage.getItem("RangoFechasPedidos") !== null){
        
        $('#daterange-btn span').html(localStorage.getItem("RangoFechasPedidos"));
        fi = moment(localStorage.getItem("fechaIncio"), 'YYYY-MM-DD');
        ff = moment(localStorage.getItem("fechaFin"), 'YYYY-MM-DD');
        $('.clearDateRange').removeClass('hidden');
        $('#pedidos').DataTable().destroy();
        cargarTablaPedidos(localStorage.getItem("fechaIncio"), localStorage.getItem("fechaFin")); 
    
    }else{

        fi = moment('2020-06-08');
        ff = moment();

        $('#pedidos').DataTable().destroy();
        cargarTablaPedidos('2020-06-08', moment().format('YYYY-MM-DD')); 
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
            'TODOS': [moment('2020-06-08'), moment()],
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
        
        localStorage.setItem("RangoFechasPedido",rango);
        localStorage.setItem("fechaIncio",fechaInicio);
        localStorage.setItem("fechaFin",fechaFin);

        $('.clearDateRange').removeClass('hidden');

        $('#pedidos').DataTable().destroy();
        cargarTablaPedidos(fechaInicio, fechaFin); 

    });

    $(document).on('click', '.ranges ul li', function(e){
        if($(this).attr('data-range-key') == 'TODOS'){
            limpiarFechas();
        }
    });
    $(document).on('click', '.clearDateRange', function(e){
        limpiarFechas();
    });

    function limpiarFechas(){
        $('#daterange-btn span').html('<i class="fa fa-calendar"></i> TODOS');
        localStorage.removeItem("RangoFechasPedido");
        localStorage.removeItem("fechaIncio");
        localStorage.removeItem("fechaFin");
        location.reload();
    }
    
    /* Cargar los datos en la tabla */
    function cargarTablaPedidos(start_date='', end_date='') {
        var dataTable = $('#pedidos').DataTable({
            "responsive" : true,
            "order": [[ 0, "desc" ]],
            "lengthMenu" : [[10, 50, 100, -1], [10, 50, 100, 'Todos']],
            "ajax" : {
                url:"api/mostrarPedidos",
                type:"POST",
                data:{start_date:start_date, end_date:end_date},
                "complete": function () {
                    $('[data-toggle="tooltip"]').tooltip();
                }
            },
            "deferRender": true, 
            "retrieve" : true,
            "processing" : true,
            "columnDefs" : [
            {
                "targets" : [0,2,3,4,5],
                "className": "text-center",
            },
            {
                "targets" : [6],
                "orderable" : false,
                "className": "text-center",
            }],
            "language": {
                "decimal":        "",
                "emptyTable":     "No hay datos disponibles en la tabla",
                "info":           "Mostrando _START_ al _END_ de _TOTAL_ registros",
                "infoEmpty":      "Mostrando 0 a 0 de 0 registros",
                "infoFiltered":   "(filtrado de _MAX_ entradas totales)",
                "infoPostFix":    "",
                "thousands":      ",",
                "lengthMenu":     "Mostrar _MENU_ registros",
                "loadingRecords": "Cargando lista de pedidos...",
                "processing":     "Procesando...",
                "search":         "BUSCAR PEDIDO:",
                "zeroRecords":    "No se encontraron resultados",
                "paginate": {
                    "first":      "<<",
                    "last":       ">>",
                    "next":       ">",
                    "previous":   "<"
                },
                "aria": {
                    "sortAscending":  ": activar para ordenar la columna ascendente",
                    "sortDescending": ": activar para ordenar la columna descendente"
                }
            }
        });
        
        // Coloca el foco sobre el input buscar
        $('#pedidos_filter input[type="search"]').focus();
    }

})