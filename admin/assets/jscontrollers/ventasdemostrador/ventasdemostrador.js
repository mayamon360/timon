/* JS VENTAS DE MOSTRADOR */
$(function(){
    
    /*
    var fi = moment();
    var ff = moment();
    
    if(localStorage.getItem("RangoFechasVentas") != null){
        $('#daterange-btn span').html(localStorage.getItem("RangoFechasVentas"));
        fi = moment(localStorage.getItem("fechaIncio"), 'YYYY-MM-DD');
        ff = moment(localStorage.getItem("fechaFin"), 'YYYY-MM-DD');
        $('.clearDateRange').removeClass('hidden');
        $('#tablaVentasMostrador').DataTable().destroy();
        cargarTablaVentas(localStorage.getItem("fechaIncio"), localStorage.getItem("fechaFin")); 
    }else{
        fi = moment('2020-06-08');
        ff = moment();
        $('#tablaVentasMostrador').DataTable().destroy();
        cargarTablaVentas('2020-06-08', moment().format('YYYY-MM-DD')); 
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
        localStorage.setItem("RangoFechasVentas",rango);
        localStorage.setItem("fechaIncio",fechaInicio);
        localStorage.setItem("fechaFin",fechaFin);
        $('.clearDateRange').removeClass('hidden');
        $('#tablaVentasMostrador').DataTable().destroy();
        cargarTablaVentas(fechaInicio, fechaFin); 
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
        localStorage.removeItem("RangoFechasVentas");
        localStorage.removeItem("fechaIncio");
        localStorage.removeItem("fechaFin");
        location.reload();
    }
    */
    
    $(document).on('change', '#fecha', function(e){

        var fecha = $(this).val();
        
        $('#tablaVentasMostrador').DataTable().destroy();
        cargarTablaVentas(fecha,fecha);
        
    })
    
    cargarTablaVentas();
    /* Cargar los datos en la tabla */
    function cargarTablaVentas(start_date='', end_date='') {

        var dataTable = $('#tablaVentasMostrador').DataTable({
            "responsive" : true,
            "order": [[ 0, "desc" ]],
            "lengthMenu" : [[10, 50, 100, -1], [10, 50, 100, 'Todos']],
            "ajax" : {
                url:"api/mostrarVentasMostrador",
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
                "targets" : [3],
                "className": "text-center",
                "orderable": false,
            },
            {
                "targets" : [4],
                "className": "text-right font-weight-bold",
            },
            {
                "targets" : [7],
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
                "loadingRecords": "Cargando lista de salidas...",
                "processing":     "Procesando...",
                "search":         "BUSCAR SALIDA:",
                "zeroRecords":    "No se encontrarón resultados.",
                "paginate": {
                    "first":      ">>",
                    "last":       "<<",
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

                    var select = $('<select><option value="">TIPO DE SALIDA</option></select>')
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
                        if(d=='Venta'){
                            select.append('<option value="' + d + '" selected>' + d + '</option>')
                        }else{
                            select.append('<option value="' + d + '">' + d + '</option>')
                        }
                    });

                } );
                this.api().column(3).every( function () {

                    var column = this;

                    var select = $('<select><option value="">MÉTODO DE PAGO</option></select>')
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
                        select.append('<option value="' + d + '">' + d + '</option>')
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
                    $.number(total,2)
                );
            }
        });
        
        $('#tablaVentasMostrador_filter input[type="search"]').focus();
    }

    $(document).on('click', '.mostrarVentaSalida', function(){

        $('#modalVerVentaSalida').modal('show');
        var folio = $(this).attr('folio');
        var tipo = $(this).attr('tipo');
        if(tipo == 'Venta'){
            $("#folio").html('<span class="badge bg-teal">'+folio+'</span>');
        }else if(tipo == 'Ajuste'){
            $("#folio").html('<span class="badge bg-aqua">'+folio+'</span>');
        }else if(tipo == 'Salida de consignación'){
            $("#folio").html('<span class="badge bg-yellow">'+folio+'</span>');
        }
        $("#tipoMovimiento").html('<strong>'+tipo+'</strong>'); 

        $.ajax({
            type : "POST",
            url : "api/mostrarListaVenta",
            dataType: 'json',
            data : {folio:folio},
            success:function(json){
                if(json.status == 'success'){
                    if(json.tipo == 'venta'){
                        $("#modalVerVentaSalida #tablaSalida").addClass('hidden');
                        $("#modalVerVentaSalida #tablaVenta").removeClass('hidden');
                        $("#modalVerVentaSalida #tablaVenta table tbody").html(json.tbody);
                    }else{
                        $("#modalVerVentaSalida #tablaVenta").addClass('hidden');
                        $("#modalVerVentaSalida #tablaSalida").removeClass('hidden');
                        $("#modalVerVentaSalida #tablaSalida table tbody").html(json.tbody);
                    }
                    
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

    /* /.Cargar los datos en la tabla */

    $(document).on('click', '.cancelarVenta', function(e){
        var folio = $(this).attr("folio");
        swal({
            title: "Cancelar "+folio,
            text: "Está acción registra en caja un egreso por el total de la venta",
            icon: 'warning',
            closeOnClickOutside: false,
            closeOnEsc: false,
            buttons: {
                confirm: {
                    text: "Sí, cancelar venta",
                    value: true,
                    visible: true,
                    className: "btn btn-sm btn-danger btn-flat font-weight-bold text-uppercase",
                    closeModal: true,
                },
                cancel: {
                    text: "Cancelar",
                    value: null,
                    visible: true,
                    className: "btn btn-sm btn-default btn-flat font-weight-bold text-uppercase",
                    closeModal: true,
                }
            }
        })
        .then((value) => {
            if(value){
                swal({
                    content: {
                        element: "input",
                        attributes: {
                            placeholder: "Contraseña",
                            type: "password",
                            className: "form-control"
                        },
                    },
                    text: 'Por favor ingresa tu contraseña para poder cancelar la venta:',
                    closeOnClickOutside: false,
                    closeOnEsc: false,
                    buttons: {
                        confirm: {
                            text: "Cancelar venta",
                            value: true,
                            visible: true,
                            className: "btn btn-sm btn-danger btn-flat font-weight-bold text-uppercase",
                            closeModal: true,
                        }
                    }
                })
                .then((value) => {
                    var pass = value;
                    $.ajax({
                        type : "POST",
                        url : "api/devolucionPorFolio",
                        dataType: 'json',
                        data : {folio:folio,pass:pass},
                        success:function(json){
                            if(json.status == 'success') {
                                $('#tablaVentasMostrador').DataTable().ajax.reload( null, false );
                                $.notify({
                                    icon: 'fas fa-check',
                                    title: '<strong>'+json.title+'</strong>',
                                    message: json.message,
                                }, {
                                    position: 'fixed',
                                    type: 'success',
                                    placement: {
                                        from: "top",
                                        align: "right"
                                    },
                                    z_index: 2000,
                                    delay: 5000,
                                    timer: 1000,
                                    mouse_over: "pause",
                                    animate: {
                                        enter: 'animated fadeIn',
                                        exit: 'animated fadeOut'
                                    }
                                });
                            }
                            if(json.status == 'error'){
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
                                });
                            }
                        },
                        error : function(xhr, status) {
                            console.log('Ha ocurrido un problema interno');
                        }
                    })
                })
            }
        })
    });


});
/* JS VENTAS DE MOSTRADOR */