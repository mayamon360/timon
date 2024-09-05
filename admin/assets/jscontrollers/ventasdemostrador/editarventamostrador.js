// Función para cargar la lista de productos en venta
        
function cargarListaVentaEditar(){
    var id_venta = $("#id_venta").val();
    $.ajax({
        type : "POST",
        url : "api/cargarListaVentaEditar",
        dataType: 'json',
        data : {id_venta:id_venta},
        success:function(json){
            if(json.status == 'success'){
                $("#listaProductosVenta").html(json.tbody);
                $("#sumaCantidad").html(json.sumaCantidad);
                $("#total").html(json.totalG);
                $("#totalTitulo").html(json.totalG);
            }else{
                alert('error');
            }
        },
        error : function(xhr, status) {
            alert('Ha ocurrido un problema interno');
        },
        complete : function(){
            $('[data-toggle="tooltip"]').tooltip();

            $(".inputDevolucion").inputmask("9{1,2}",{ 
                "placeholder": "",
                "rightAlign": true,
                "oncomplete": function(){ 
                    var devolucion = parseInt($(this).val());
                    var vendido = parseInt($(this).attr('vendido'));
                    if(devolucion >= vendido){
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
cargarListaVentaEditar();

$(document).on('click', '.devolucionProducto', function(e){
    var id_producto = $(this).attr("key");
    var id_venta = $("#id_venta").val();

    swal({
        title: "Devolución",
        text: "¿Aplicar devolución de "+$(this).attr("cantidad")+" libro(s) de "+$(this).attr("producto")+"?\n\n Está acción registra en caja un egreso por: $"+$(this).attr("subtotal"),
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
                    url : "api/devolucionPorItem",
                    dataType: 'json',
                    data : {id_producto:id_producto,id_venta:id_venta,pass:pass},
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
                        cargarListaVentaEditar();
                    }
                })
            })
        }
    })
})

$(document).on('click', '.btnDevolucion', function(e){
    var id_producto = $(this).attr("key");
    var id_venta = $("#id_venta").val();
    var devolucion = $("#inputDevolucion"+id_producto).val();

    var precio = $(this).attr("precio");
    var egreso = Number(precio*devolucion);
    
    swal({
        title: "Devolución",
        text: "¿Aplicar devolución de "+devolucion+" libro(s) de "+$(this).attr("producto")+"?\n\n Está acción registra en caja un egreso por: $ "+egreso,
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
                    url : "api/devolucionPorCantidad",
                    dataType: 'json',
                    data : {id_producto:id_producto,id_venta:id_venta,pass:pass,devolucion:devolucion},
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
                        cargarListaVentaEditar();
                    }
                })
            })

        }else{
            cargarListaVentaEditar();
        }
    })
})