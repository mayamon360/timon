/* JS COMPRAS */
$(function(){

	var fi = '';
    var ff = '';

    if(localStorage.getItem("RangoFechasCompras") != null){
        
        $('#daterange-btn span').html(localStorage.getItem("RangoFechasCompras"));
        fi = moment(localStorage.getItem("fechaIncio"), 'YYYY-MM-DD');
        ff = moment(localStorage.getItem("fechaFin"), 'YYYY-MM-DD');
        $('.clearDateRange').removeClass('hidden');
        $('#compras').DataTable().destroy();
        cargarTablaCompras(localStorage.getItem("fechaIncio"), localStorage.getItem("fechaFin")); 
    
    }else{

        fi = moment();
        ff = moment();

        $('#compras').DataTable().destroy();
        cargarTablaCompras(moment().format('YYYY-MM-DD'), moment().format('YYYY-MM-DD')); 
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
            'TODAS': [moment('2020-06-08'), moment()],
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

        $('#compras').DataTable().destroy();
        cargarTablaCompras(fechaInicio, fechaFin); 

    });

    $(document).on('click', '.ranges ul li', function(e){
        if($(this).attr('data-range-key') == 'TODAS'){
            limpiarFechas();
        }
    });
    $(document).on('click', '.clearDateRange', function(e){
        limpiarFechas();
    });

    function limpiarFechas(){
        $('#daterange-btn span').html('<i class="fa fa-calendar"></i> TODAS');
        localStorage.removeItem("RangoFechasCompras");
        localStorage.removeItem("fechaIncio");
        localStorage.removeItem("fechaFin");
        location.reload();
    }

    function cargarTablaCompras(start_date='', end_date='') {

        /* Cargar los datos en la tabla */
        var dataTable = $('#compras').DataTable({
            "responsive" : true,
            "order": [[ 0, "desc" ]],
            "lengthMenu" : [[10, 50, 100, -1], [10, 50, 100, 'Todos']],
            "ajax" : {
                url:"api/mostrarCompras",
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
                "targets" : [0],
                "visible": false,
                "searchable": false
            },
            {
                "targets" : [1],
                "className": "text-center font-weight-bold text-uppercase",
                "orderable" : false
            },
            {
                "targets" : [2],
                "className": "text-center",
            },
            {
                "targets" : [4],
                "className": "text-right font-weight-bold",
            },
            {
                "targets" : [5],
                "className": "text-gray",
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
                "loadingRecords": "Cargando lista de entradas...",
                "processing":     "Procesando...",
                "search":         "BUSCAR ENTRADA:",
                "zeroRecords":    "No se encontrarón resultados.",
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
            },
            initComplete: function () {
                this.api().column(1).every( function () {

                    var column = this;

                    var select = $('<select><option value="">TIPO DE ENTRADA</option></select>')
                    .appendTo($(column.header()).empty())
                    .on('change', function() {
                        var val = $.fn.dataTable.util.escapeRegex(
                        $(this).val()
                        );

                        column
                        .search(val ? '^' + val + '$' : '', true, false)
                        .draw();
                    });

                    column.data().unique().sort().each(function(d, j) {
                        if(d=='Compra'){
                            select.append('<option value="' + d + '" selected>' + d + '</option>')
                        }else{
                            select.append('<option value="' + d + '">' + d + '</option>')
                        }
                    });

                } );
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
                    .column( 4 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
     
                // Total over this page
                pageTotal = api
                    .column( 4, { page: 'current'} )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
     
                // Update footer
                $( api.column( 4 ).footer() ).html(
                    $.number(pageTotal,2)
                );

                $( api.column( 5 ).footer() ).html(
                    'de '+ $.number(total,2) +' (en entradas)'
                );
            }
        });

        $('#compras_filter input[type="search"]').focus();
    }

    $(document).on('click', '.mostrarCompraEntrada', function(){

        $('#modalVerCompraEntrada').modal('show');
        var folio = $(this).attr('folio');
        var tipo = $(this).attr('tipo');
        $("#tipoMovimiento").html('<strong>'+tipo+'</strong>'); 
        
        if(tipo == 'Compra'){
        	$("#folio").html('<span class="badge bg-teal">'+folio+'</span>');
        }else if(tipo == 'Ajuste'){
        	$("#folio").html('<span class="badge bg-aqua">'+folio+'</span>');
        }else if(tipo == 'Entrada de consignación'){
        	$("#folio").html('<span class="badge bg-yellow">'+folio+'</span>');
        }

        $.ajax({
            type : "POST",
            url : "api/mostrarListaCompra",
            dataType: 'json',
            data : {folio:folio},
            success:function(json){
                if(json.status == 'success'){
                    $("#modalVerCompraEntrada table tbody").html(json.tbody);
                }else{
                    swal({
                        title: json.title,
                        text: json.message,
                        icon: "error",
                        closeOnClickOutside: false,
                        closeOnEsc: true,
                        buttons: {
                            confirm: {
                                text: "Cerrar",
                                value: true,
                                visible: true,
                                className: "btn btn-sm btn-default btn-flat font-weight-bold text-uppercase",
                                closeModal: true,
                            }
                        }
                    })
                }
            },
            error : function(xhr, status) {
                console.log('Ha ocurrido un problema interno');
            },
            complete: function(){
                $(".infoDetalles").popover({ trigger: "hover focus click", html: true } );
            }
        });
    })

})

/* JS COMPRAS */