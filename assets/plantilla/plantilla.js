$(document).ready(function() {

    $(".megamenu_contenido").on("click", function(e) {
        e.stopPropagation();
    });

})

function guardarDeseo(id){
    $.ajax({
        type : 'GET',
        url : 'api/guardarDeseo',
        dataType: 'json',
        data : {id:id},
        success : function(json) {
            if(json.status == 'warning'){
                swal({
                    title: json.title,
                    text: json.message,
                    icon: 'warning',
                    closeOnClickOutside: false,
                    closeOnEsc: false,
                    buttons: false,
                    timer: 800
                });
                setTimeout(function() {
                    window.location = 'autenticacion';
                }, 800);
            }else if(json.status == 'success'){

                $(".div_deseo"+id+" a").tooltip('hide');
                $(".div_deseo"+id).html('<i class="fas fa-heart fa-lg text-red animated pulse infinite"></i>');

                swal({
                    title: json.title,
                    text: json.message,
                    icon: 'success',
                    closeOnClickOutside: false,
                    closeOnEsc: false,
                    buttons: false,
                    buttons: {
                        cancel: {
                            text: 'CERRAR',
                            value: null,
                            visible: true,
                            className: 'btn btn-sm boton_negro',
                            closeModal: true,
                        },
                        confirm: {
                            text: "VER MI LISTA DE DESEOS",
                            value: true,
                            visible: true,
                            className: "btn btn-sm boton_negro",
                            closeModal: true
                        }
                    }
                })
                .then((value) => {
                    if(value){
                        window.location = 'cuenta/lista-deseos';
                    }
                })
                
            }else if(json.status == 'error'){
                swal({
                    title: json.title,
                    text: json.message,
                    icon: 'error',
                    closeOnClickOutside: false,
                    closeOnEsc: false,
                    buttons: {
                        cancel: {
                            text: 'CERRAR',
                            value: true,
                            visible: true,
                            className: 'btn btn-sm boton_negro',
                            closeModal: true,
                        }
                    }
                })
            }
        },
        error : function(xhr, status) {
            swal({
                title: '¡ERROR INTERNO!',
                text: 'Para más detalles, contacte al administrador.',
                icon: 'error',
                closeOnClickOutside: false,
                closeOnEsc: false,
                buttons: {
                    cancel: {
                        text: 'CERRAR',
                        value: null,
                        visible: true,
                        className: 'btn btn-sm boton_negro',
                        closeModal: true,
                    }
                }
            })
        }
    })
}

function agregarCarrito(id){
    $.ajax({
        type : 'GET',
        url : 'api/agregarCarrito',
        dataType: 'json',
        data : {id:id},
        success : function(json) {
            if(json.status == 'success'){
                $('.cantidad_carrito').html(json.cantidad);
                swal({
                    title: json.title,
                    text: json.message,
                    icon: 'success',
                    closeOnClickOutside: false,
                    closeOnEsc: false,
                    buttons: {
                        cancel: {
                            text: 'SEGUIR COMPRANDO',
                            value: null,
                            visible: true,
                            className: 'btn btn-sm boton_negro',
                            closeModal: true,
                        },
                        confirm: {
                            text: 'PROCESAR COMPRA',
                            value: true,
                            visible: true,
                            className: "btn btn-sm boton_color",
                            closeModal: true
                        }
                    }
                })
                .then((value) => {
                    if(value){
                        window.location = 'compra';
                    }
                })
            }else{
                swal({
                    title: json.title,
                    text: json.message,
                    icon: 'error',
                    closeOnClickOutside: false,
                    closeOnEsc: false,
                    buttons: {
                        cancel: {
                            text: 'CERRAR',
                            value: null,
                            visible: true,
                            className: 'btn btn-sm boton_negro',
                            closeModal: true,
                        },
                        confirm: {
                            text: 'PROCESAR COMPRA',
                            value: true,
                            visible: true,
                            className: "btn btn-sm boton_color",
                            closeModal: true
                        }
                    }
                })
                .then((value) => {
                    if(value){
                        window.location = 'compra';
                    }
                })
            }
        },
        error : function(xhr, status) {
            swal({
                title: '¡ERROR INTERNO!',
                text: 'Para más detalles, contacte al administrador.',
                icon: 'error',
                closeOnClickOutside: false,
                closeOnEsc: false,
                buttons: {
                    cancel: {
                        text: 'CERRAR',
                        value: null,
                        visible: true,
                        className: 'btn btn-sm boton_negro',
                        closeModal: true,
                    }
                }
            })
        }
    })
}

$(document).on('click', ".agregar_carrito", function(e) {
    e.preventDefault();
    var id = $(this).attr("idLibro");
    agregarCarrito(id);
});