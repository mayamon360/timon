/* JS VENTAS EN LINEA */
$(function(){
    
    $(document).on('change', '#fecha', function(e){
        var fecha = $(this).val();
        $('#tablaVentasOnline').DataTable().destroy();
        cargarTablaVentas(fecha);
    })
    
    $(document).on('click', '#todas', function(e){
        $("#fecha").val('');
        $('#tablaVentasOnline').DataTable().destroy();
        cargarTablaVentas();
    })
    
    cargarTablaVentas($("#fecha").val());
    /* Cargar los datos en la tabla */
    function cargarTablaVentas(fecha='') {

        var dataTable = $('#tablaVentasOnline').DataTable({
            "responsive" : true,
            "order": [[ 0, "desc" ]],
            "lengthMenu" : [[15, 50, 100, -1], [15, 50, 100, 'Todos']],
            "ajax" : {
                url:"api/mostrarVentas",
                type:"POST",
                data:{fecha:fecha},
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
                "targets" : [1,7,8],
                "className": "text-center font-weight-bold"
            },
            {
                "targets" : [6],
                "className": "text-right"
            },
            {
                "targets" : [9],
                "className": "text-center"
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
                "loadingRecords": "Cargando lista...",
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
                    .column( 6 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
     
                // Total over this page
                pageTotal = api
                    .column( 6, { page: 'current'} )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
     
                // Update footer
                $( api.column( 6 ).footer() ).html(
                    $.number(pageTotal,2)
                );

                $( api.column( 7 ).footer() ).html(
                    $.number(total,2)
                );
            }
        });

        $('#tablaVentas_filter input[type="search"]').focus();
    }
    
    
    $(document).on('click', '.cambiar_estatus', function() {
        
        var metodo = $(this).attr('id');
        var compra = $(this).attr('compra');
        var titulo = '';
        var texto = '';
        switch (metodo) {
            
            case 'reembolzar':
                titulo = 'CANCELAR';
                texto = 'Realizar el reembolzo.';
            break;
            
            case 'preparar':
                titulo = 'PREPARAR';
                texto = 'Consultar stock para procesar la compra.';
            break;
            
            case 'enviar':
                titulo = 'ENVIAR';
                texto = 'Asignar el número de guía.';
            break;
            
            case 'entregar':
                titulo = 'ENTREGAR';
                texto = 'Entregar al cliente.';
            break;
            
            case 'confirmar_oxxo':
                titulo = 'CONFIRMAR PAGO OXXO';
                texto = 'Marcar como pago realizado.';
            break;
        }
        
        if(metodo !== 'actualizar'){
            
            swal({
                title: titulo,
                text: texto,
                icon: 'warning',
                closeOnClickOutside: false,
                closeOnEsc: false,
                buttons: {
                    confirm: {
                        text: "Sí, continuar",
                        value: true,
                        visible: true,
                        className: "btn btn-sm btn-primary btn-flat font-weight-bold text-uppercase",
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
                    if(metodo === 'enviar'){
                        
                        swal({
                            content: {
                                element: "input",
                                attributes: {
                                    placeholder: "Número de guía",
                                    type: "text",
                                    className: "form-control"
                                },
                            },
                            text: 'Por favor ingresa el número de guía:',
                            closeOnClickOutside: false,
                            closeOnEsc: false,
                            buttons: {
                                confirm: {
                                    text: "Continuar",
                                    value: true,
                                    visible: true,
                                    className: "btn btn-sm btn-primary btn-flat font-weight-bold text-uppercase",
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
                            var guia = value;
                            cambiarEstatus(metodo, compra, guia);   
                        })
                        
                    }else{
                        cambiarEstatus(metodo, compra);
                    }
                }
            })
            
        }else{
            $('#tablaVentasOnline').DataTable().ajax.reload( null, false ); 
        }
        
        
    })
    
    function cambiarEstatus(metodo, compra, guia = '') {
        $.ajax({
            type : "POST",
            url : "api/cambiarEstatus",
            dataType: 'json',
            data : {metodo:metodo,compra:compra,guia:guia},
            success:function(json){
                if (json.status == 'success') {
                    $('#tablaVentasOnline').DataTable().ajax.reload( null, false );
                } else if (json.status == 'error'){
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
            complete : function() {
              check_shopping_d();  
            },
            error : function(xhr, status) {
                console.log('Ha ocurrido un problema interno');
            }
        })
    }
    
    function check_shopping_d(){
        $.ajax({
            type : "POST",
            url : "api/checkShopping",
            success: function(json){
                $(".spanAprovadas").html(json.totalAprovadas);
                $(".spanPreparadas").html(json.totalPreparadas);
                console.log('Aprobadas edit:' + json.totalAprovadas);
                console.log('Preparadas edit:' + json.totalPreparadas);
                
                if(Number(json.totalAprovadas) <= 0){
                    $(".spanAprovadas").html('');
                }
                if(Number(json.totalPreparadas) <= 0){
                    $(".spanPreparadas").html('');
                }
            }
        })
    }
    
    $(document).on('click', '.ver_compra', function() {
        var folio = $(this).attr("folio");
        $(".folio").html(folio);
        $.ajax({
            type : "POST",
            url : "api/verCompra",
            dataType: 'json',
            data : {folio:folio},
            success:function(json){
                
                if(json.status == 'success') {
                    $(".modal-body").html(json.html);
                    
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
    
})