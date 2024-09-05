/* JS REGISTRAR CRÉDITO */

// OPCIONES AL CARGAR LA PAGINA
$(function(){

    $('[data-toggle="tooltip"]').tooltip();
    $('.seleccionar').select2();
    
    // Si hay cliente en el almacenamiento local 
    if(localStorage.getItem("cliente") !== null){
        // Se coloca en #seleccionar_cliente y se activa el evento change
        $('#seleccionar_cliente').val(localStorage.getItem("cliente")).trigger('change');
    }else{
        // Se LIMPIA la lista de PRODUCTOS
        limpiarListaCredito();
    }
});

// Se limpia el input busqueda y se le coloca el foco
$("input#busqueda").val('');
$("input#busqueda").focus();










// PASO 1: SELECCIONAR CLIENTE
$(document).on('change', '#seleccionar_cliente', function(e){
    // Si se selecciono un cliente
    if($(this).val() != ''){
        /**
         * Si se selecciono un cliente y es diferente al que se tenia previamente
         * 
         * Se OCULTA el buscador
         * Se LIMPIA la busqueda
         * Se LIMPIA la lista de productos
         * 
         */
        if($(this).val() != localStorage.getItem("cliente")){
            $("#box_agregarProductos").addClass('hidden');
            limpiarBusqueda();
            limpiarListaCredito();
        } 
        
        // Se AGREGA el id del cliente al almacenamiento local
        localStorage.setItem("cliente", $(this).val());
        // Se MUESTRA el buscador
        $("#box_agregarProductos").removeClass('hidden');
    
    // Si NO se selecciono un cliente
    }else{
        
        // Se OCULTA el buscador
        $("#box_agregarProductos").addClass('hidden');
        // Se ELIMINA el id del CLIENTE del almacenamiento local
        localStorage.removeItem("cliente");
        // Se LIMPIA la busqueda
        limpiarBusqueda();
        // Se LIMPIA la lista de productos 
        limpiarListaCredito();
        
    }
})

// Función para cargar la lista de productos
function cargarListaCredito(){

    $.ajax({
        type : "POST",
        url : "api/cargarListaCredito",
        dataType: 'json',
        beforeSend: function(){ 
            $("#listaProductosCredito").html('<td colspan="7" style="vertical-align:middle;"><p class="text-center" style="margin:0; padding:10px 0px;">Procesando, por favor espere...</p></td>');
        },
        success:function(json){           
            $("#listaProductosCredito").html(json.tbody);
            $("#sumaCantidad").html(json.sumaCantidad);
            $("#totalCantSuperior").html(json.sumaCantidad);
            $("#total").html(json.totalG);
            $("#totalTitulo").html(json.totalG);
            $("#totalInput").val(json.total);

            if(json.status == 'llena'){
                $(".divLimpiarListaCredito").removeClass('hidden');
                $("#confirmar").removeClass('hidden');
            }else if(json.status == 'vacia'){
                $(".divLimpiarListaCredito").addClass('hidden');
                $("#confirmar").addClass('hidden');
            }
    
            $(".inputCantidad").inputmask("9{1,5}",{ 
                "placeholder": "0",
                "rightAlign": false,
                "oncleared": function(){ 
                    $(this).val('');  
                }
            }); 
            
            $(".inputDescuento, #descuentoGeneral, #impuestoComision").inputmask("9{1,2}",{ 
                "placeholder": "0",
                "rightAlign": true,
                "oncleared": function(){ 
                    $(this).val('');  
                }
            }); 

        },
        error : function(xhr, status) {
            console.log('Ha ocurrido un problema interno');
        }
    });
}
cargarListaCredito();

/**
 * Función para buscar producto
*/
function buscarProductoCredito(){
    var busqueda = $("#busqueda").val();
    var cliente = $("#seleccionar_cliente").val();

        $.ajax({
            type : "POST",
            url : "api/buscarProductoCredito",
            dataType: 'json',
            data : {busqueda:busqueda,cliente:cliente},
            beforeSend: function(){ 
                // OCULTAR tabla con resultados previos
                $(".divResultadosBusquedaPC").addClass('hidden');
                $("table.resultadosBusquedaPC tbody").html('');
                // Se ACTIVA el mensaje de espera
                $("#diProcesando").removeClass('hidden');
            },
            success : function(json) {
                if(json.status == ''){

                    $(".divResultadosBusquedaPC").removeClass('hidden');
                    $("table.resultadosBusquedaPC tbody").html(json.tr);

                    $("button#buscarProducto").addClass('hidden');
                    $("button#limpiarB").removeClass('hidden');

                } else if(json.status == 'success') {

                    limpiarBusqueda();
                    cargarListaCredito();
                    $.notify({
                        icon: 'fas fa-check',
                        title: '<strong>'+json.title+'</strong>',
                        message: json.message,
                    }, {
                        position: 'fixed',
                        type: 'success',
                        placement: {
                            from: "bottom",
                            align: "right"
                        },
                        z_index: 1000,
                        delay: 1000,
                        timer: 500,
                        mouse_over: "pause",
                        animate: {
                            enter: 'animated bounceIn',
                            exit: 'animated bounceOut'
                        }
                    });

                } else if(json.status == 'error') {
                    
                    $(".divResultadosBusquedaPC").addClass('hidden');
                    $("table.resultadosBusquedaPC tbody").html('');
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
                    .then((value) => {
                        if(value){
                            $("input#busqueda").val('');
                            $("input#busqueda").focus();
                            limpiarBusqueda();
                        }
                    });
                    
                }
            },
            error : function(xhr, status) {
                console.log('Ha ocurrido un problema interno');
            },
            complete: function(){ 
                $("#diProcesando").addClass('hidden');
                $('[data-toggle="tooltip"]').tooltip();
            } 
        });
    
} 
$(document).on('keypress', '#buscador input#busqueda', function(e){
    e.defaultPrevented;
    if(e.which == 13) {
        if($(this).val() != ''){
            buscarProductoCredito();
        }
        return false;
    }
});
$('#buscarProducto').click(function(e) {
    if($('input#busqueda').val() != ''){
        buscarProductoCredito();
    }
});
$('#limpiarB').click(function(e) {
    limpiarBusqueda();
});
function limpiarBusqueda(){
    $("button#buscarProducto").removeClass('hidden');
    $("button#limpiarB").addClass('hidden');

    $("input#busqueda").val('');
    $("input#busqueda").focus();

    $(".divResultadosBusquedaPC").addClass('hidden');
    $("table.resultadosBusquedaPC tbody").html('');
}
/**
 * Función para buscar producto
*/

$(document).on('click','#btnAgregarCredito',function(){
    var key = $(this).attr('key');
    var cliente = $("#seleccionar_cliente").val();

    $.ajax({
        type : "POST",
        url : "api/agregarProductoListaCredito",
        dataType: 'json',
        data : {cliente:cliente,key:key},
        success:function(json){
            if(json.status == 'success'){

                $('#modalProductos').modal('hide');
                cargarListaCredito();

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
                .then((value) => {
                    if(value){
                        cargarListaCredito();
                    }
                });

            }
        },
        error : function(xhr, status) {
            console.log('Ha ocurrido un problema interno');
        },
        complete: function(){
            $("#busqueda").val('');
            $("#busqueda").focus();
            $(".divResultadosBusquedaPC").addClass('hidden');
            $("table.resultadosBusquedaPC tbody").html('');
            limpiarBusqueda();
        }
    });
});

$(document).on('click', '.eliminarProductoListaCredito', function(){
    var key = $(this).attr('key');
    $.ajax({
        type : "POST",
        url : "api/quitarProductoListaCredito",
        dataType: 'json',
        data : {key:key},
        success:function(json){
            if(json.status == 'success'){
                cargarListaCredito();
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
                .then((value) => {
                    if(value){
                        cargarListaCredito();
                    }
                });
            }
        },
        error : function(xhr, status) {
            console.log('Ha ocurrido un problema interno');
        }
    });
});

function limpiarListaCredito() {
    $.ajax({
        type : "POST",
        url : "api/vaciarListaCredito",
        dataType: 'json',
        success:function(json){
            cargarListaCredito();
            $(".divLimpiarListaCredito").addClass('hidden');
        },
        error : function(xhr, status) {
            console.log('Ha ocurrido un problema interno');
        }
    }); 
}
$(document).on('click', '.limpiarListaCredito', function(){
    limpiarListaCredito();
});

// MODIFICAR LA CANTIDAD --------------------------------------
function modificarCantidad(cantidad, key) {
    if(cantidad == 0 || cantidad == ''){
        cantidad = 1;
        $('#inputCantidad'+key).val(cantidad);
    }
    $.ajax({
        type : "POST",
        url : "api/modificarCantidadCredito",
        dataType: 'json',
        data : {cantidad:cantidad,key:key},
        success:function(json){
            if(json.status == 'success'){
                cargarListaCredito();
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
                .then((value) => {
                    if(value){
                        $("#codigo").focus();
                        cargarListaCredito();
                    }
                });
            }
        },
        error : function(xhr, status) {
            console.log('Ha ocurrido un problema interno');
        },
        complete: function(){
            
        }
    });
}

$(document).on('keypress', '.inputCantidad', function(e){
    e.defaultPrevented;
    var nuevaCantidad = $(this).val();
    var key = $(this).attr('key');
    if(e.which == 13) {
        modificarCantidad(nuevaCantidad, key);
        return false;
    }
});
$(document).on('blur', '.inputCantidad', function(e){
    e.defaultPrevented;
    var nuevaCantidad = $(this).val();
    var key = $(this).attr('key');
    modificarCantidad(nuevaCantidad, key);
});

$(document).on('click', '.btnQuitarCantidad', function(){
    var key = $(this).attr('key');
    var inputCantidad = parseInt($('#inputCantidad'+key).val());
    var nuevaCantidad = inputCantidad-1;
    if(nuevaCantidad > 0){
        modificarCantidad(nuevaCantidad, key);
    }
});

$(document).on('click', '.btnAgregarCantidad', function(){
    var key = $(this).attr('key');
    var inputCantidad = parseInt($('#inputCantidad'+key).val());
    var nuevaCantidad = inputCantidad+1;
    modificarCantidad(nuevaCantidad, key);
});
// MODIFICAR LA CANTIDAD --------------------------------------

// APLICAR DESCUENTO GENERAL O INDIVIDUAL ---------------------
function agregarDescuento(tipo, key, descuento) {
    $.ajax({
        type : "POST",
        url : "api/agregarDescuentoCredito",
        dataType: 'json',
        data : {tipo:tipo,key:key,descuento:descuento},
        success:function(json){
            if(json.status == 'success'){
                cargarListaCredito();
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
                .then((value) => {
                    if(value){
                        $("input#busqueda").focus();
                        cargarListaCredito();
                    }
                });
            }
        },
        error : function(xhr, status) {
            console.log('Ha ocurrido un problema interno');
        },
        complete: function(){
            $("#descuentoGeneral").val('');
        }
    });
}
$(document).on('keypress', '#descuentoGeneral', function(e){
    e.defaultPrevented;
    var descuentoGeneral = $(this).val();
    if(e.which == 13) {
        agregarDescuento('general', 0, descuentoGeneral);
        return false;
    }
})

$(document).on('keypress', '.inputDescuento', function(e){
    e.defaultPrevented;
    var descuento = $(this).val();
    var key = $(this).attr('key');
    if(e.which == 13) {
        agregarDescuento('individual', key, descuento);
        return false;
    }
});

// APLICAR DESCUENTO GENERAL O INDIVIDUAL ---------------------



function confirmarVenta(){
    
    var cliente = $("#seleccionar_cliente").val();

    swal({
        title: "Procesar crédito",
        text: "¿Registrar las salidas de los productos al crédito del cliente?",
        icon: 'warning',
        closeOnClickOutside: false,
        closeOnEsc: false,
        buttons: {
            cancel: {
                text: "Cancelar",
                value: null,
                visible: true,
                className: "btn btn-sm btn-default",
                closeModal: true,
            },
            confirm: {
                text: "Sí, continuar",
                value: true,
                visible: true,
                className: "btn btn-sm btn-primary",
                closeModal: true,
            }
        }
    })
    .then((value) => {
        if(value){
            $.ajax({
                type : "POST",
                url : "api/confirmarCredito",
                dataType: 'json',
                data : {cliente:cliente},
                beforeSend: function(){ 
                    $("#confirmar").html('Procesando...').attr('disabled', 'disabled').addClass('disabled');
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
                                    value: true,
                                    visible: true,
                                    className: "btn btn-sm btn-default btnCerrar",
                                    closeModal: true,
                                }
                            }
                        })
                        .then((value) => {
                            if(value){
                                
                                localStorage.removeItem("cliente");
                                $("#box_agregarProductos").addClass('hidden');
                                $('#seleccionar_cliente').val('').trigger('change');

                                cargarListaCredito();
                            }
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
                                    className: "btn btn-sm btn-primary",
                                    closeModal: true,
                                }
                            }
                        })
                        .then((value) => {
                            if(value){
                                cargarListaCredito();
                            }
                        });
                    }
                },
                error : function(xhr, status) {
                    console.log('Ha ocurrido un problema interno');
                },
                complete : function(){
                    $("#confirmar").html('<i class="fas fa-check"></i> CONFIRMAR CRÉDITO').removeAttr('disabled').removeClass('disabled');
                }
            });
        }
    })

}
$(document).on('click', '#confirmar', function(e){
    confirmarVenta();
});


$(document).on('click', '#opciones_aperturaCaja', function(e){
    $.ajax({
        type : "POST",
        url : "api/obtenerFormularioCaja",
        dataType: 'json',
        success : function(json) {
            $("#registrar_monto_form").html(json.formulario);
        },
        complete: function(){
            $("#input_monto_inicial").number(true,2);
        }
    })
})

function registrarMontoInicial(){
    var $ocrendForm = $(this), __data = {};
    $('#registrar_monto_form').serializeArray().map(function(x){__data[x.name] = x.value;}); 

    if(undefined == $ocrendForm.data('locked') || false == $ocrendForm.data('locked')) {
        $.ajax({
            type : "POST",
            url : "api/registrarMontoInicial",
            dataType: 'json',
            data : __data,
            beforeSend: function(){ 
                $ocrendForm.data('locked', true);
                $("button#registrar_monto").attr("disabled", true);
            },
            success : function(json) {
                if(json.status == 'success') {
                    $('#modalRegistrarMonto').modal('hide');
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
                        location.reload();
                    })
                } else {
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
                console.log('Ha ocurrido un problema interno');
            },
            complete: function(){ 
                $ocrendForm.data('locked', false);
                $("button#registrar_monto").removeAttr("disabled");
            } 
        });
    }
}
$('button#registrar_monto').click(function(e) {
    e.defaultPrevented;
    registrarMontoInicial();
});

/* JS REGISTRAR CRÉDITO */