$(function(){
    $('[data-toggle="tooltip"]').tooltip();
    $("#monto_abono").number(true,2);

    var id_credito = $("#id_credito").val();
    
    var groupColumn = 1;

    //--------------------------------------------------------------------------------------------------------------------------------------------------------------------
    var dataTableSalidas = $('#tablaSalidas').DataTable({
        "responsive" : true,
        "paging": false,
        "order": [[ 0, "desc" ]],
        "ajax" : {
            url:"api/cargarSalidas",
            type:"POST",
            data:{id_credito:id_credito},
            complete : function(){
                $('[data-toggle="tooltip"]').tooltip();
            }
        },
        "deferRender": true, 
        "retrieve" : true,
        "processing" : true,
        "columnDefs" : [
        { 
            "visible": false, 
            "targets": 0 
        },
        { 
            "visible": false, 
            "targets": groupColumn
        },
        {
            "targets" : [3],
            "className" : "text-right font-weight-bold"
        },
        {
            "targets" : [2,3,4],
            "orderable" : false
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
                ' '+pageTotal
            );
            $( api.column( 4 ).footer() ).html(
                '(de '+ total +')'
            );
        },
        "drawCallback": function ( settings ) {
            var api = this.api();
            var rows = api.rows( {page:'current'} ).nodes();
            var last=null;
            var cont = 0;
            api.column(groupColumn, {page:'current'} ).data().each( function ( group, i ) {
                var columns = api.columns(0).data().eq(0).unique();
                if ( last !== group ) {
                    $(rows).eq( i ).before(
                        '<tr class="group" data-toggle="tooltip" title="Descargar las de está fecha" style="background:#f9f9f9; text-align:center; cursor:pointer;" fecha="'+columns[cont]+'"><td colspan="3">'+group+'</td></tr>'
                    );
                    last = group;
                    cont++;
                }
            } );
        }
    });
    /*$('#tablaSalidas tbody').on('click', 'tr', function(){
        var credito = dataTableSalidas.row( this ).data();
        var fecha = credito[0];*/
    $('#tablaSalidas tbody').on('click', 'tr.group', function(){
        var fecha = $(this).attr('fecha');
        var url = window.location.origin;
        window.open(url+'/admin/creditos/nota/'+id_credito+'/salidas/'+fecha, '_blank');  
    });
    $(document).on('click', '#descargarSalidas', function(){
        var url = window.location.origin;
        window.open(url+'/admin/creditos/nota/'+id_credito+'/salidas');
    })
    //--------------------------------------------------------------------------------------------------------------------------------------------------------------------
    var dataTableDevoluciones = $('#tablaDevoluciones').DataTable({
        "responsive" : true,
        "paging": false,
        "order": [[ 0, "desc" ]],
        "ajax" : {
            url:"api/cargarDevoluciones",
            type:"POST",
            data:{id_credito:id_credito},
            complete : function(){
                $('[data-toggle="tooltip"]').tooltip();
            }
        },
        "deferRender": true, 
        "retrieve" : true,
        "processing" : true,
        "columnDefs" : [
        { 
            "visible": false, 
            "targets": 0 
        },
        { 
            "visible": false, 
            "targets": groupColumn
        },
        {
            "targets" : [3],
            "className" : "text-right font-weight-bold"
        },
        {
            "targets" : [2,3,4],
            "orderable" : false
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
                ' '+pageTotal
            );
            $( api.column( 4 ).footer() ).html(
                '(de '+ total +')'
            );
        },
        "drawCallback": function ( settings ) {
            var api = this.api();
            var rows = api.rows( {page:'current'} ).nodes();
            var last=null;
            var cont = 0;
            api.column(groupColumn, {page:'current'} ).data().each( function ( group, i ) {
                var columns = api.columns(0).data().eq(0).unique();
                if ( last !== group ) {
                    $(rows).eq( i ).before(
                        '<tr class="group" data-toggle="tooltip" title="Descargar las de está fecha" style="background:#f9f9f9; text-align:center; cursor:pointer;" fecha="'+columns[cont]+'"><td colspan="3">'+group+'</td></tr>'
                    );
                    last = group;
                    cont++;
                }
            } );
        }
    });
    $('#tablaDevoluciones tbody').on('click', 'tr.group', function(){
        var fecha = $(this).attr('fecha');
        var url = window.location.origin;
        window.open(url+'/admin/creditos/nota/'+id_credito+'/devoluciones/'+fecha);
    });
    $(document).on('click', '#descargarDevoluciones', function(){
        var url = window.location.origin;
        window.open(url+'/admin/creditos/nota/'+id_credito+'/devoluciones');
    })
    //--------------------------------------------------------------------------------------------------------------------------------------------------------------------
    var dataTablePagos = $('#tablaPagos').DataTable({
        "responsive" : true,
        "paging": false,
        "order": [[ 0, "desc" ]],
        "ajax" : {
            url:"api/cargarPagos",
            type:"POST",
            data:{id_credito:id_credito},
            complete : function(){
                $('[data-toggle="tooltip"]').tooltip();
            }
        },
        "deferRender": true, 
        "retrieve" : true,
        "processing" : true,
        "columnDefs" : [
        { 
            "visible": false, 
            "targets": 0 
        },
        { 
            "visible": false, 
            "targets": groupColumn
        },
        {
            "targets" : [3],
            "className" : "text-right font-weight-bold"
        },
        {
            "targets" : [2,3,4],
            "orderable" : false
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
                '$ '+$.number(pageTotal,2)
            );
            $( api.column( 4 ).footer() ).html(
                '(de $ '+ $.number(total,2) +')'
            );
        },
        "drawCallback": function ( settings ) {
            var api = this.api();
            var rows = api.rows( {page:'current'} ).nodes();
            var last=null;
            var cont = 0;
            api.column(groupColumn, {page:'current'} ).data().each( function ( group, i ) {
                var columns = api.columns(0).data().eq(0).unique();
                if ( last !== group ) {
                    $(rows).eq( i ).before(
                        '<tr class="group" data-toggle="tooltip" title="Descargar los de está fecha" style="background:#f9f9f9; text-align:center; cursor:pointer;" fecha="'+columns[cont]+'"><td colspan="3">'+group+'</td></tr>'
                    );
                    last = group;
                    cont++;
                }
            } );
        }
    });
    $('#tablaPagos tbody').on('click', 'tr.group', function(){
        var fecha = $(this).attr('fecha');
        var url = window.location.origin;
        window.open(url+'/admin/creditos/nota/'+id_credito+'/pagos/'+fecha);
    });
    $(document).on('click', '#descargarPagos', function(){
        var url = window.location.origin;
        window.open(url+'/admin/creditos/nota/'+id_credito+'/pagos');
    })
    //--------------------------------------------------------------------------------------------------------------------------------------------------------------------

    $(document).on('click', '#descargarNota', function(){
        var url = window.location.origin;
        window.open(url+'/admin/creditos/nota/'+id_credito);
    })

})

// Función para cargar la lista de productos en credito
function cargarListaCreditoEditar(){
    var id_credito = $("#id_credito").val();
    $.ajax({
        type : "POST",
        url : "api/cargarListaCreditoEditar",
        dataType: 'json',
        data : {id_credito:id_credito},
        success:function(json){
            if(json.status == 'success'){
                $("#listaProductosCredito").html(json.tbody);
                $("#sumaCantidad").html(json.sumaCantidad);
                $("#sumaDevolucion").html(json.sumaDevolucion);
                $("#sumaVendido").html(json.sumaVendido);
                $("#total").html(json.totalG);
                $("#totalTitulo").html(json.totalG);
                $("#totalNumero").html(json.totalNumero);
                $("#limite").html(json.limite);
                $("#porcentaje_devolucion").html(json.porcentaje_devolucion);
                $("#porcentaje_venta").html(json.porcentaje_venta);
                $("#pagos").html(json.pagos);
                $("#resta").html(json.resta);
            }else{
                var url = window.location.origin;
                $(location).attr('href',url+'/admin/creditos');
            }
        },
        error : function(xhr, status) {
            alert('Ha ocurrido un problema interno');
        },
        complete : function(){
            $('[data-toggle="tooltip"]').tooltip();

            $(".inputDevolucion").inputmask("9{1,4}",{ 
                "placeholder": "",
                "rightAlign": true,
                "oncomplete": function(){ 

                    var devolucion = parseInt($(this).val());
                    var vendido = parseInt($(this).attr('vendido'));

                    if(devolucion > vendido){
                        $(this).val('');
                    }

                    if($(this).val() == 0 || $(this).val() == ''){
                        $("#btnDevolucion"+$(this).attr('key')).attr('disabled','disabled');
                    }else{
                        $("#btnDevolucion"+$(this).attr('key')).removeAttr('disabled');
                    } 

                },
                "oncleared": function(){ 
                    $(this).val('');
                    $("#btnDevolucion"+$(this).attr('key')).attr('disabled','disabled');
                }
            });       
        }
    });
}
cargarListaCreditoEditar();

$(document).on('click', '.btnDevolucion', function(e){
    var producto = $(this).attr("producto");

    var id_producto = $(this).attr("key");
    var devolucion = $("#inputDevolucion"+id_producto).val();
    var id_credito = $("#id_credito").val();

    swal({
        title: "Devolución",
        text: "¿Aplicar devolución de "+devolucion+" libro(s) de "+producto+"?\n\n",
        icon: 'warning',
        closeOnClickOutside: false,
        closeOnEsc: false,
        buttons: {
            confirm: {
                text: "Sí, aplicar devolución",
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
                text: 'Por favor ingresa tu contraseña para poder aplicar la devolución:',
                closeOnClickOutside: false,
                closeOnEsc: false,
                buttons: {
                    confirm: {
                        text: "Aplicar devolución",
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
                    url : "api/devolucionCredito",
                    dataType: 'json',
                    data : {id_producto:id_producto,id_credito:id_credito,devolucion:devolucion,pass:pass},
                    success:function(json){
                        if(json.status == 'success') {
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
                    },
                    complete : function(){
                        cargarListaCreditoEditar();
                    }
                })
            })
        }else{
            cargarListaCreditoEditar();
        }
    })

})

$(document).on('click', '#cerrar_credito', function(e){
    var id_credito = $("#id_credito").val();
    
    swal({
        content: {
            element: "input",
            attributes: {
                placeholder: "Contraseña",
                type: "password",
                className: "form-control"
            },
        },
        text: 'Por favor ingresa tu contraseña para poder cerrar el crédito:',
        closeOnClickOutside: false,
        closeOnEsc: false,
        buttons: {
            confirm: {
                text: "Cerrar crédito",
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
            url : "api/cerrarCredito",
            dataType: 'json',
            data : {id_credito:id_credito,pass:pass},
            success:function(json){

                if(json.status == 'success'){
                    swal({
                        title: json.title,
                        text: json.message,
                        icon: "success",
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
                    .then((value) => {
                        if(value){
                            location.reload();
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

})

$(document).on('click', '#realizar_abono', function(e){
    $('#cobros_form')[0].reset();

    $('#modalCobros').modal('show');
    $(this).tooltip('hide');
    $('#folioCredito').html($(this).attr('folio'));

    var id_credito = $("#id_credito").val();

})

$(document).on('change', "#metodo_pago", function(e){
    if($(this).val() == 'deposito'){
        $("#group_referencia").removeClass('hidden');
        $("#group_descripcion").addClass('hidden');
    }else if($(this).val() == 'condonacion'){
        $("#group_referencia").addClass('hidden');
        $("#group_descripcion").removeClass('hidden');
    }else{
        $("#group_referencia").addClass('hidden');
        $("#group_descripcion").addClass('hidden');
    }
})

$(document).on('click', "#cobrar", function (e){

    var id_credito = $("#id_credito").val();
    var metodo_pago = $("#metodo_pago").val();
    var monto_abono = $("#monto_abono").val();
    var referencia = $("#referencia").val();
    var descripcion = $("#descripcion").val();

    swal({
        content: {
            element: "input",
            attributes: {
                placeholder: "Contraseña",
                type: "password",
                className: "form-control"
            },
        },
        text: 'Por favor ingresa tu contraseña para poder realizar el abono:',
        closeOnClickOutside: false,
        closeOnEsc: false,
        buttons: {
            confirm: {
                text: "Realizar abono",
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
            url : "api/abonarCredito",
            dataType: 'json',
            data : {id_credito:id_credito, metodo_pago:metodo_pago, monto_abono:monto_abono, referencia:referencia, descripcion:descripcion,pass:pass},
            success:function(json){

                if(json.status == 'success'){
                    swal({
                        title: json.title,
                        text: json.message,
                        icon: "success",
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
                    .then((value) => {
                        if(value){
                            location.reload();
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

})