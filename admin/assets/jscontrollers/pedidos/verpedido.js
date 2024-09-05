$(function(){
    
    var id_pedido = $("#id_pedido").html();
    cargarListaPedidos(id_pedido);
    
});
    
function cargarListaPedidos(id_pedido) {
    var dataTable = $('#pedidos').DataTable({
        //"responsive": true,
        "order": [],   
        "orderCellsTop": true, 
        "lengthMenu" : [[10, 50, 100, -1], [10, 50, 100, 'Todos']],
        "ajax" : {
            url:"api/mostrarListaPedido",
            type:"POST",
            data:{id_pedido:id_pedido},
            "complete": function () {
                $('[data-toggle="tooltip"]').tooltip();
            }
        },
        "deferRender": true, 
        "retrieve" : true,
        "processing" : true,
        "columnDefs" : [
        {
            "targets" : [0,1,3,5,6,7,8,9,10],
            "className": "text-center",
        },
        {
            "targets" : [11],
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
    
$(document).on('click', '#cancelar_pedido', function(){
    var key = $(this).attr("key");
    var folio = $(this).attr("folio");
    var anticipo = $(this).attr("anticipo");
    
    swal({
        title: "!Atención¡",
        text: "¿Estás seguro de cancelar el pedido "+folio+"? \n\n Se registrara un egreso en caja por $ "+anticipo,
        icon: 'warning',
        closeOnClickOutside: false,
        closeOnEsc: false,
        buttons: {
            confirm: {
                text: "Sí, cancelar",
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
        if(value !== null){
            swal({
                content: {
                    element: "input",
                    attributes: {
                        placeholder: "Ingresa el código de anticipo",
                        type: "text",
                    },
                },
                title: 'Código de anticipo',
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
                if(value !== null){
                    var codigo = value;
                    $.ajax({
                        type : "POST",
                        url : "api/cancelarPedido",
                        dataType: 'json',
                        data : {key:key,codigo:codigo},
                        success:function(json){
                            if(json.status == 'success'){
                                $('#pedidos').DataTable().ajax.reload( null, false );
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
                                            className: "btn btn-sm btn-primary btn-flat font-weight-bold text-uppercase",
                                            closeModal: true,
                                        }
                                    }
                                })
                            }
                        },
                        error : function(xhr, status) {
                            alert('Ha ocurrido un problema interno');
                        }
                    });   
                }
            })
        }
    })
    
})

$(document).on('click', '#entregar_pedido', function(){
    
    var key = $(this).attr("key");
    $.ajax({
        type : "POST",
        url : "api/cargarLibroPedido",
        dataType: 'json',
        data : {key:key},
        success:function(json){
            if(json.status == 'success'){
                $("#id_producto").val(json.id_producto);
                $("#cantidad").val(json.cantidad);
                $("#resta").html(json.resta);
                $("#codigo").html(json.id_producto+' | '+json.codigo);
                $("#producto").html('<strong>'+json.producto+'</strong><br><small>'+json.leyenda+'</small>');
                $("#folio_pedido").val(json.folio_pedido);
                $("#precio").val(json.precio);
                $("#total").val(json.total);
                $("#anticipo").val(json.anticipo);
                $("#codigo_anticipo").val('');
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
                            className: "btn btn-sm btn-primary btn-flat font-weight-bold text-uppercase",
                            closeModal: true,
                        }
                    }
                })
                $('#modalConfirmarEntrega').modal('hide');
            }
        },
        error : function(xhr, status) {
            alert('Ha ocurrido un problema interno');
        }
    });
    
})

$("#codigo_anticipo").inputmask("9{5}",{ 
    "placeholder": "0",
    "oncleared": function(){ 
        $(this).val('');  
    }
});
$(document).on('click', '#confirmarEntrega', function(){
    
    var folio_pedido = $("#folio_pedido").val();
    var id_producto = $("#id_producto").val();
    var codigo_anticipo = $("#codigo_anticipo").val();
    
    var metodo_pago = $("#metodoPago").val();
    
    swal({
        title: "!Pago en "+metodo_pago+"¡",
        icon: 'warning',
        closeOnClickOutside: false,
        closeOnEsc: false,
        buttons: {
            confirm: {
                text: "Sí, continuar",
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
        if(value !== null){
            $.ajax({
                type : "POST",
                url : "api/confirmarEntrega",
                dataType: 'json',
                data : {folio_pedido:folio_pedido,id_producto:id_producto,codigo_anticipo:codigo_anticipo,metodo_pago:metodo_pago},
                beforeSend: function(){ 
                    $("#confirmarEntrega").html('Procesando...').attr('disabled', 'disabled').addClass('disabled');
                },
                success:function(json){
                    if(json.status == 'success'){
                        
                        swal({
                            title: json.title,
                            text: json.message,
                            icon: "success",
                            closeOnClickOutside: false,
                            closeOnEsc: true,
                            buttons: {
                                cancel: {
                                    text: "Cerrar",
                                    value: 'cerrar',
                                    visible: true,
                                    className: "btn btn-sm btn-default btnCerrar",
                                    closeModal: true,
                                },
                                confirm: {
                                    text: "Mostrar ticket",
                                    value: json.idsalida,
                                    visible: true,
                                    className: "btn btn-sm btn-primary btnTicket",
                                    closeModal: true,
                                }
                            }
                        })
                        .then((value) => {
                            if(value != 'cerrar'){
                                window.open("https://eltimonlibreria.com/admin/ventasDeMostrador/ticket/"+value);
                            }
                            $('#pedidos').DataTable().ajax.reload( null, false );
                            $('#modalConfirmarEntrega').modal('hide');
                        });
                        
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
                                    className: "btn btn-sm btn-primary btn-flat font-weight-bold text-uppercase",
                                    closeModal: true,
                                }
                            }
                        })
                    }
                },
                error : function(xhr, status) {
                    alert('Ha ocurrido un problema interno');
                },
                complete : function () {
                    $("#confirmarEntrega").html('<i class="fas fa-check"></i> Confirmar entrega').removeAttr('disabled').removeClass('disabled');
                }
            });
        }
    })
        
})
