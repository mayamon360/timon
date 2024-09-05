$(function(){

    var fi = '';
    var ff = '';

    if(localStorage.getItem("RangoFechasCreditos") !== null){
        
        $('#daterange-btn span').html(localStorage.getItem("RangoFechasCreditos"));
        fi = moment(localStorage.getItem("fechaIncio"), 'YYYY-MM-DD');
        ff = moment(localStorage.getItem("fechaFin"), 'YYYY-MM-DD');
        $('.clearDateRange').removeClass('hidden');
        $('#tablaCreditos').DataTable().destroy();
        cargarTablaCreditos(localStorage.getItem("fechaIncio"), localStorage.getItem("fechaFin")); 
    
    }else{

        fi = moment('2020-06-08');
        ff = moment();

        $('#tablaCreditos').DataTable().destroy();
        cargarTablaCreditos('2020-06-08', moment().format('YYYY-MM-DD')); 
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
            'Todo': [moment('2020-06-08'), moment()],
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
        
        localStorage.setItem("RangoFechasCreditos",rango);
        localStorage.setItem("fechaIncio",fechaInicio);
        localStorage.setItem("fechaFin",fechaFin);

        $('.clearDateRange').removeClass('hidden');

        $('#tablaCreditos').DataTable().destroy();
        cargarTablaCreditos(fechaInicio, fechaFin); 

    });

    $(document).on('click', '.ranges ul li', function(e){
        if($(this).attr('data-range-key') == 'Todo'){
            limpiarFechas();
        }
    });
    $(document).on('click', '.clearDateRange', function(e){
        limpiarFechas();
    });

    function limpiarFechas(){
        $('#daterange-btn span').html('<i class="fa fa-calendar"></i> Mostrando todo');
        localStorage.removeItem("RangoFechasCreditos");
        localStorage.removeItem("fechaIncio");
        localStorage.removeItem("fechaFin");
        location.reload();
    }

    /* Cargar los datos en la tabla */
    function cargarTablaCreditos(start_date='', end_date='') {

        var dataTable = $('#tablaCreditos').DataTable({
            "responsive" : true,
            "order": [[ 0, "desc" ]],
            "ajax" : {
                url:"api/mostrarCreditos",
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
                "className": "font-weight-bold",
            },
            {
                "targets" : [5],
                "className": "text-right font-weight-bold",
            },
            {
                "targets" : [8],
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
                "loadingRecords": "Cargando...",
                "processing":     "Procesando...",
                "search":         "BUSCAR CRÉDITO:",
                "zeroRecords":    "No se encontraron registros coincidentes",
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
                    .column( 5 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
     
                // Total over this page
                pageTotal = api
                    .column( 5, { page: 'current'} )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
     
                // Update footer
                $( api.column( 5 ).footer() ).html(
                    '$ '+$.number(pageTotal,2)
                );

                $( api.column( 6 ).footer() ).html(
                    '(de $ '+ $.number(total,2) +')'
                );
            }
        });
    
        $('#tablaCreditos_filter input[type="search"]').focus();
    }

    $(document).on('click', '.mostrarCredito', function(){

        $('#modalVerCredito').modal('show');
        var folio = $(this).attr('folio');
        var tipo = $(this).attr('tipo');
        $("#folio").html('<span class="badge bg-black">'+folio+'</span>');
        $("#tipoMovimiento").html('<strong>'+tipo+'</strong>'); 

        $.ajax({
            type : "POST",
            url : "api/mostrarListaCredito",
            dataType: 'json',
            data : {folio:folio},
            success:function(json){
                if(json.status == 'success'){
                    $("#modalVerCredito #tablaCredito").addClass('hidden');
                    $("#modalVerCredito #tablaCredito").removeClass('hidden');
                    $("#modalVerCredito #tablaCredito table tbody").html(json.tbody);
                    $("#modalVerCredito #tablaCredito table tfoot").html(json.tfoot);
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
                                className: "btn btn-sm btn-primary",
                                closeModal: true,
                            }
                        }
                    })
                }
            },
            error : function(xhr, status) {
                alert('Ha ocurrido un problema interno');
            },
            complete: function(){
                $(".infoDetalles").popover({ trigger: "hover focus click", html: true } );
            }
        });

    })

    $(document).on('click', '.calcelarCredito', function(){
        var folio = $(this).attr('folio');
        var id = $(this).attr('key');
        swal({
            title: "Cancelar "+folio,
            text: "¿Está seguro de cancelar el crédito?",
            icon: 'warning',
            closeOnClickOutside: false,
            closeOnEsc: false,
            buttons: {
                confirm: {
                    text: "Sí, cancelar crédito",
                    value: true,
                    visible: true,
                    className: "btn btn-sm btn-danger",
                    closeModal: true,
                },
                cancel: {
                    text: "Cancelar",
                    value: null,
                    visible: true,
                    className: "btn btn-sm btn-default",
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
                    text: 'Por favor ingresa tu contraseña para poder cancelar el crédito:',
                    closeOnClickOutside: false,
                    closeOnEsc: false,
                    buttons: {
                        confirm: {
                            text: "Cancelar venta",
                            value: true,
                            visible: true,
                            className: "btn btn-sm btn-primary",
                            closeModal: true,
                        }
                    }
                })
                .then((value) => {
                    var pass = value;
                    $.ajax({
                        type : "POST",
                        url : "api/cancelarCredito",
                        dataType: 'json',
                        data : {id:id,folio:folio,pass:pass},
                        success:function(json){
                            if(json.status == 'success') {
                                $('#tablaCreditos').DataTable().ajax.reload( null, false );
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
                                            className: "btn btn-sm btn-primary",
                                            closeModal: true,
                                        }
                                    }
                                });
                            }
                        },
                        error : function(xhr, status) {
                            alert('Ha ocurrido un problema interno');
                        }
                    })
                })
            }
        })
    })

})

/**
 * Ajax action to api rest
*/
function creditos(){
    var $ocrendForm = $(this), __data = {};
    $('#creditos_form').serializeArray().map(function(x){__data[x.name] = x.value;}); 

    if(undefined == $ocrendForm.data('locked') || false == $ocrendForm.data('locked')) {
        $.ajax({
            type : "POST",
            url : "api/creditos",
            dataType: 'json',
            data : __data,
            beforeSend: function(){ 
                $ocrendForm.data('locked', true) 
            },
            success : function(json) {
                if(json.success == 1) {
                    alert(json.message);
                } else {
                    alert(json.message);
                }
            },
            error : function(xhr, status) {
                alert('Ha ocurrido un problema interno');
            },
            complete: function(){ 
                $ocrendForm.data('locked', false);
            } 
        });
    }
} 

/**
 * Events
 */
$('#creditos').click(function(e) {
    e.defaultPrevented;
    creditos();
});
$('form#creditos_form input').keypress(function(e) {
    e.defaultPrevented;
    if(e.which == 13) {
        creditos();

        return false;
    }
});
