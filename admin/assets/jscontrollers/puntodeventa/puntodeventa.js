/* JS PUNTO DE VENTA */

// DOM
$(function(){
    $('[data-toggle="tooltip"]').tooltip();
    $("#rfc").inputmask({"mask": "A{3,4}-999999-***"});
    $("#telefono").inputmask({"mask": "(999) 999 9999"});
    $(".datepicker").datepicker({
        startDate: '-80y',
        endDate: '-8y',
        format: 'yyyy-mm-dd',
        language: 'es',
        clearBtn: true,
        calendarWeeks: true,
        autoclose: true
    });
});
// DOM

// Resetear busqueda
$("input#busqueda").val('');
$("input#busqueda").focus();

function datosTicket() {
    $.ajax({
        type : "POST",
        url : "api/datosTicket",
        dataType: 'json',
        success:function(json){
            $("#serie").val(json.serie);
            $("#numero").val(json.numero);
        },
        error : function(xhr, status) {
            console.log('Ha ocurrido un problema interno');
        }
    });
}

// Función para cargar la lista de productos en venta
function cargarListaVenta(){

    var cliente = $("#seleccionar_cliente").val();
    var metodoPago = $("#metodoPago").val();
    if(cliente === null){
        cliente = 1;
        metodoPago = 'efectivo';
    }
    $.ajax({
        type : "POST",
        url : "api/cargarListaVenta",
        dataType: 'json',
        beforeSend: function(){ 
            $("#listaProductosVenta").html('<td colspan="7" style="vertical-align:middle;"><p class="text-center" style="margin:0; padding:10px 0px;">Procesando, por favor espere...</p></td>');
        },
        success:function(json){
            $(".divResultadosBusquedaPV").addClass('hidden');
            $("#listaProductosVenta").html(json.tbody);
            $("#sumaCantidad").html(json.sumaCantidad);
            $("#totalCantSuperior").html(json.sumaCantidad);
            $("#total").html(json.totalG);
            $("#totalSuperior").html(json.totalG);
            $("#totalTitulo").html(json.totalG);
            $("#totalInput").val(json.total);
            $("#metodoPago").val(metodoPago);
            $("#efectivo").val(json.total);
            $("#cambio").val('');
            $("#transaccion").val(''); 
            $("#montoTarjeta").val('');
            $("#montoEfectivo").val('');
            $("#cambioMixto").val('');
            $("#transaccionMixto").val(''); 

            if(json.status == 'llena'){
                $(".divLimpiarListaVenta").removeClass('hidden');
                $(".divConfirmarVenta").removeClass('hidden');
                $("#btnModalConfirmarVenta").removeClass('hidden');
            }else if(json.status == 'vacia'){
                $(".divLimpiarListaVenta").addClass('hidden');
                $(".divConfirmarVenta").addClass('hidden');
                $("#btnModalConfirmarVenta").addClass('hidden');
                cliente = 1;
                $('#seleccionar_cliente').val(1).trigger('change');
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

            // OPCIONES AL HACER CAMBIOS EN INPUTS DE PAGO EN FECTIVO
            $("#efectivo").number(true,2);
            $("#cambio").number(true,2);
            $('#efectivo').on('change keyup copy paste cut', function(e){
                e.defaultPrevented;

                $("#transaccion").val('');

                $("#montoTarjeta").val('');
                $("#montoEfectivo").val('');
                $("#cambioMixto").val('');
                $("#transaccionMixto").val('');

                var total = Number($("#totalInput").val());
                var efectivo = Number($(this).val());

                if(efectivo > total && efectivo != 0){
                    var cambio = Number(efectivo-total);
                    $("#cambio").val(cambio);
                }else{
                    $("#cambio").val('');
                }
            });

            // OPCIONES AL HACER CAMBIOS EN INPUTS DE PAGO MIXTO
            $("#montoTarjeta").number(true,2);
            $("#montoEfectivo").number(true,2);
            $("#cambioMixto").number(true,2);
            $('#montoTarjeta').on('change keyup copy paste cut', function(){

                if (!this.value) {
                    $("#cambioMixto").val('');
                }

                $("#efectivo").val('');
                $("#cambio").val('');
                $("#transaccion").val(''); 

                var total = Number($("#totalInput").val());
                var montoTarjeta = Number($(this).val());
                var montoEfectivo = Number($("#montoEfectivo").val());
                var montoTotalMixto = montoTarjeta + montoEfectivo;

                if(montoTarjeta > 0){
                    if(montoTarjeta >= total){
                        $("#cambioMixto").val('');
                        $("#montoTarjeta").val('');
                        $("#montoEfectivo").val('')
                        swal({
                            title: "¡Opss!",
                            text: "El monto total de la venta debe estar dividido, si desea pagar todo con tarjeta seleccione el método correspondiente.",
                            icon: "error",
                            closeOnClickOutside: false,
                            closeOnEsc: true,
                            buttons: {
                                cancel: {
                                    text: "Cerrar",
                                    value: false,
                                    visible: true,
                                    className: "btn btn-sm btn-default",
                                    closeModal: true,
                                },
                                confirm: {
                                    text: "Seleccionar pago con tarjeta",
                                    value: true,
                                    visible: true,
                                    className: "btn btn-sm btn-primary",
                                    closeModal: true,
                                }
                            }
                        })
                        .then((value) => {
                            if(value){
                                $("#metodoPago").val('tarjeta').change();
                                $("#transaccion").focus(); 
                            }else{
                                $("#montoTarjeta").focus();
                            }
                        });
                    }else{
                        if(montoTotalMixto > total){
                            $("#cambioMixto").val( montoTotalMixto - total );
                        }else{
                            $("#cambioMixto").val('');
                        }
                    }
                }else{
                    $("#cambioMixto").val('');
                }

            });

            $('#montoEfectivo').on('change keyup copy paste cut', function(){

                if (!this.value) {
                    $("#cambioMixto").val('');
                }
                
                $("#efectivo").val('');
                $("#cambio").val('');
                $("#transaccion").val(''); 

                var total = Number($("#totalInput").val());
                var montoTarjeta = Number($("#montoTarjeta").val());
                var montoEfectivo = Number($(this).val());
                var montoTotalMixto = montoTarjeta + montoEfectivo;

                if(montoEfectivo > 0){
                    if(montoEfectivo >= total){
                        $("#cambioMixto").val('');
                        $("#montoTarjeta").val('');
                        $("#montoEfectivo").val('');
                        swal({
                            title: "¡Opss!",
                            text: "El monto total de la venta debe estar dividido, si desea pagar todo en efectivo seleccione el método correspondiente.",
                            icon: "error",
                            closeOnClickOutside: false,
                            closeOnEsc: true,
                            buttons: {
                                cancel: {
                                    text: "Cerrar",
                                    value: false,
                                    visible: true,
                                    className: "btn btn-sm btn-default",
                                    closeModal: true,
                                },
                                confirm: {
                                    text: "Seleccionar pago en efectivo",
                                    value: true,
                                    visible: true,
                                    className: "btn btn-sm btn-primary",
                                    closeModal: true,
                                }
                            }
                        })
                        .then((value) => {
                            if(value){
                                $("#metodoPago").val('efectivo').change();
                                $("#efectivo").focus(); 
                            }else{
                                $("#montoEfectivo").focus();
                            }
                        });
                    }else{

                        if(montoTotalMixto > total){
                            $("#cambioMixto").val( montoTotalMixto - total );
                        }else{
                            $("#cambioMixto").val('');
                        }

                    }
                }else{
                    $("#cambioMixto").val('');
                }

            })

            $(document).on('keypress', '#transaccion', function(e){
                $("#efectivo").val('');
                $("#cambio").val('');
            });
        },
        error : function(xhr, status) {
            console.log('Ha ocurrido un problema interno');
        },
        complete : function(){

            obtenerClientes(cliente);
            datosTicket();

        }
    });
}
cargarListaVenta();

// Función para obtener los clientes
function obtenerClientes(seleccionado){
    var metodo = 'cargar clientes';
    $.ajax({
        type : "POST",
        url : "api/obtenerClientes",
        dataType: 'json',
        data : {metodo:metodo,seleccionado:seleccionado},
        success:function(json){
            $("#seleccionar_cliente").html(json.clientes);
        },
        error : function(xhr, status) {
            console.log('Ha ocurrido un problema interno');
        },
        complete: function(){
            //Initialize Select2 Elements
            $('.seleccionar').select2({
                dropdownParent: $('#infoVenta'),
                width: "resolve"
            });
        }
    });
}
obtenerClientes(1);

/* Validar que el nombre del cliente no exista ya modal agregar */
$(document).on('change', '.validar', function(){
    var item = $(this).attr('tipo');
    var valor = $(this).val();
    $.ajax({
        url:"api/validarCliente",
        method:'POST',
        dataType: 'json',
        data : {item:item,valor:valor},
        success:function(json){

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
                })
                .then((value) => {
                    if(value){
                        $("#"+item).val("");
                        $("#"+item).focus();
                    }
                });
            }
        },
        error : function(xhr, status) {
            console.log('Ha ocurrido un problema interno');
        }
    })
});

$(document).on('change', '#tipoCliente', function(){
    if($(this).val() == 2){
        $(".divLealtad").addClass('hidden');
    }else{
        $(".divLealtad").removeClass('hidden');
    }
})

/**
 * Función para agregar cliente
*/
function agregarCliente(){
    var $ocrendForm = $(this), __data = {};
    $('#agregar_form').serializeArray().map(function(x){__data[x.name] = x.value;}); 
    if(undefined == $ocrendForm.data('locked') || false == $ocrendForm.data('locked')) {
        $.ajax({
            type : "POST",
            url : "api/agregarCliente",
            dataType: 'json',
            data : __data,
            beforeSend: function(){ 
                $ocrendForm.data('locked', true);
                $("#enviar").attr("disabled", true);
            },
            success : function(json) {
                if(json.status == 'success') {
                    $('#modalAgregar').modal('hide');
                    obtenerClientes(json.agregado);
                    $.notify({
                        icon: 'fas fa-check',
                        title: '<strong>'+json.title+'</strong>',
                        message: json.message,
                    }, {
                        position: 'fixed',
                        type: 'success',
                        placement: {
                            from: "bottom",
                            align: "left"
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
                $("#enviar").removeAttr("disabled");
            } 
        });
    }
} 
// Evento que dispara la función agregarCliente()
$('#enviar').click(function(e) {
    e.defaultPrevented;
    agregarCliente();
});
/* Limpiar el formulario del modal agregar */
$('.agregarCliente').on('click', function (e) {
    $("#agregar_form")[0].reset();
    $(".divLealtad").removeClass('hidden');
});

/**
 * Función para buscar producto
*/
function buscarProductoVenta(){
    var busqueda = $("#busqueda").val();
    $.ajax({
        type : "POST",
        url : "api/buscarProductoVenta",
        dataType: 'json',
        data : {busqueda:busqueda},
        beforeSend: function(){ 
            // OCULTAR tabla con resultados previos
            $(".divResultadosBusquedaPV").addClass('hidden');
            $("table.resultadosBusquedaPV tbody").html('');
            $("input#busqueda").attr('disabled','disabled');
            // Se ACTIVA el mensaje de espera
            $("#diProcesando").removeClass('hidden');
        },
        success : function(json) {
            if(json.status == ''){

                $(".divResultadosBusquedaPV").removeClass('hidden');
                $("table.resultadosBusquedaPV tbody").html(json.tr);

                $("button#buscarProducto").addClass('hidden');
                $("button#limpiarB").removeClass('hidden');

            } else if(json.status == 'success') {

                limpiarBusqueda();
                cargarListaVenta();
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
                
                $(".divResultadosBusquedaPV").addClass('hidden');
                $("table.resultadosBusquedaPV tbody").html('');
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
                        limpiarBusqueda();
                    }
                });
                
            }
        },
        error : function(xhr, status) {
            console.log('Ha ocurrido un problema interno');
        },
        complete: function(){ 
            $("input#busqueda").removeAttr('disabled');
            $("input#busqueda").val('');
            $("input#busqueda").focus();
            $("#diProcesando").addClass('hidden');
            $('[data-toggle="tooltip"]').tooltip();
        } 
    });
}
$(document).on('keypress', '#buscador input#busqueda', function(e){
    e.defaultPrevented;
    if(e.which == 13) {
        if($(this).val() != ''){
            buscarProductoVenta();
        }
        return false;
    }
}); 
$('#buscarProducto').click(function(e) {
    if($('input#busqueda').val() != ''){
        buscarProductoVenta();
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

    $(".divResultadosBusquedaPV").addClass('hidden');
    $("table.resultadosBusquedaPV tbody").html('');
}
/**
 * Función para buscar producto
*/
$(document).on('click','#btnAgregarVenta',function(e){
    var key = $(this).attr('key');
    $.ajax({
        type : "POST",
        url : "api/agregarProductoListaVenta",
        dataType: 'json',
        data : {key:key},
        success:function(json){
            if(json.status == 'success'){
                $('#modalProductos').modal('hide');
                cargarListaVenta();
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
            console.log('Ha ocurrido un problema interno');
        },
        complete: function(){
            $("input#busqueda").val('');
            $("input#busqueda").focus();
            $(".divResultadosBusquedaPV").addClass('hidden');
            $("table.resultadosBusquedaPV tbody").html('');
            limpiarBusqueda();
        }
    });
});

$(document).on('click', '.eliminarProductoListaVenta', function(){
    var key = $(this).attr('key');
    $.ajax({
        type : "POST",
        url : "api/quitarProductoListaVenta",
        dataType: 'json',
        data : {key:key},
        success:function(json){
            if(json.status == 'success'){
                cargarListaVenta();
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
                        cargarListaVenta();
                    }
                });
            }
        },
        error : function(xhr, status) {
            console.log('Ha ocurrido un problema interno');
        }
    });
});


function limpiarListaVenta() {
    $.ajax({
        type : "POST",
        url : "api/vaciarListaVenta",
        dataType: 'json',
        success:function(json){
            $(".divLimpiarListaVenta").addClass('hidden');
            $("input#busqueda").val('');
            $("input#busqueda").focus();
            cargarListaVenta();
        },
        error : function(xhr, status) {
            console.log('Ha ocurrido un problema interno');
        }
    }); 
}
// Limpiar la lista de productos
$(document).on('click', '.limpiarListaVenta', function(){
    limpiarListaVenta();
});

// MODIFICAR LA CANTIDAD --------------------------------------
function modificarCantidad(cantidad, key) {
    if(cantidad == 0 || cantidad == ''){
        cantidad = 1;
        $('#inputCantidad'+key).val(cantidad);
    }
    $.ajax({
        type : "POST",
        url : "api/modificarCantidad",
        dataType: 'json',
        data : {cantidad:cantidad,key:key},
        success:function(json){
            if(json.status == 'success'){
                cargarListaVenta();
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
                        cargarListaVenta();
                    }
                });
            }
        },
        error : function(xhr, status) {
            console.log('Ha ocurrido un problema interno');
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
        url : "api/agregarDescuento",
        dataType: 'json',
        data : {tipo:tipo,key:key,descuento:descuento},
        success:function(json){
            if(json.status == 'success'){
                cargarListaVenta();
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
                        cargarListaVenta();
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

$(document).on('change', '#metodoPago', function(e){

    e.defaultPrevented;

    cargarListaVenta();

    var metodoPago = $(this).val();
    switch (metodoPago) {
        case 'efectivo':
            $(".divPagoEfectivo").removeClass('hidden');
            $(".divPagoTarjeta").addClass('hidden');
            $(".divPagoMixto").addClass('hidden');
            $("#efectivo").focus();
        break;

        case 'tarjeta':
            $(".divPagoEfectivo").addClass('hidden');
            $(".divPagoTarjeta").removeClass('hidden');
            $(".divPagoMixto").addClass('hidden');
            $("#transaccion").focus();
        break;

        case 'mixto':
            $(".divPagoEfectivo").addClass('hidden');
            $(".divPagoTarjeta").addClass('hidden');
            $(".divPagoMixto").removeClass('hidden');
            $("#montoTarjeta").focus();
        break;

        case 'puntos':
            $(".divPagoEfectivo").addClass('hidden');
            $(".divPagoTarjeta").addClass('hidden');
            $(".divPagoMixto").addClass('hidden');
        break;

    }

});

// SALIDA POR VENTA
function confirmarVenta(){
    var cliente = $("#seleccionar_cliente").val();
    var documento = $("#documento").val();
    var metodoPago = $("#metodoPago").val();
    var efectivo = $("#efectivo").val();
    var transaccion = $("#transaccion").val();
    var montoTarjeta = $("#montoTarjeta").val();
    var montoEfectivo = $("#montoEfectivo").val();
    var transaccionMixto = $("#transaccionMixto").val(); 
    swal({
        title: "VENTA",
        text: "¿Registrar la salida de productos como venta?",
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
                url : "api/confirmarVenta",
                dataType: 'json',
                data : {cliente:cliente,documento:documento,metodoPago:metodoPago,efectivo:efectivo,transaccion:transaccion,montoTarjeta:montoTarjeta,montoEfectivo:montoEfectivo,transaccionMixto:transaccionMixto},
                beforeSend: function(){ 
                    $("#consignacion").addClass('hidden');
                    $("#ajuste").addClass('hidden');
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
                            cargarListaVenta();
                            $('#modalConfirmarVenta').modal('hide');
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
                                cargarListaVenta();
                            }
                        });
                    }
                },
                error : function(xhr, status) {
                    console.log('Ha ocurrido un problema interno');
                },
                complete : function(){
                    $("#consignacion").removeClass('hidden');
                    $("#ajuste").removeClass('hidden');
                    $("#confirmar").html('<i class="fas fa-check"></i> Confirmar Venta').removeAttr('disabled').removeClass('disabled');
                }
            });
        }
    })

}
$(document).on('click', '#confirmar', function(e){
    confirmarVenta();
});

$(document).on('keypress', '#efectivo, #transaccion', function(e){
    e.defaultPrevented;
    if(e.which == 13) {
        confirmarVenta();
        return false;
    }
});
$(document).on('keypress', '#montoTarjeta, #montoEfectivo, #transaccionMixto', function(e){
    e.defaultPrevented;
    if(e.which == 13) {
        $(".btnClick").removeClass('hidden').addClass('animated shake');
        setTimeout(function() {
            $(".btnClick").removeClass('animated shake').addClass('hidden');
        },2500);
        return false;
    }
});

// SALIDA DE CONSIGNACIÓN
function consignacionSalida(){
    var proveedor = $("#seleccionar_proveedor").val();
    var referencia = $("#referencia").val();
    swal({
        title: "SALIDA DE CONSIGNACIÓN",
        text: "¿Registrar la salida de consignación de los productos?",
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
                className: "btn btn-sm bg-navy",
                closeModal: true,
            }
        }
    })
    .then((value) => {
        if(value){
            $.ajax({
                type : "POST",
                url : "api/consignacionSalida",
                dataType: 'json',
                data : {proveedor:proveedor,referencia:referencia},
                beforeSend: function(){ 
                    $("#ajuste").addClass('hidden');
                    $("#consignacion").html('Procesando...').attr('disabled', 'disabled').addClass('disabled');
                    $("#confirmar").addClass('hidden');
                },
                success : function(json) {
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
                                    text: "Mostrar nota",
                                    value: json.idsalida,
                                    visible: true,
                                    className: "btn btn-sm btn-primary btnNota",
                                    closeModal: true,
                                }
                            }
                        })
                        .then((value) => {
                            if(value != 'cerrar'){
                                window.open("https://eltimonlibreria.com/admin/ventasDeMostrador/nota/"+value);
                            }                            
                            cargarListaVenta();
                            $('#modalConfirmarVenta').modal('hide');
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
        
                    }
        
                },
                error : function(xhr, status) {
                    console.log('Ha ocurrido un problema interno');
                },
                complete : function(){
                    $("#ajuste").removeClass('hidden');
                    $("#consignacion").html('Salida de consignación').removeAttr('disabled').removeClass('disabled');
                    $("#confirmar").removeClass('hidden');
                }
            });
        }
    })
}
$(document).on('click', '#consignacion', function(e){
    consignacionSalida();
});
$(document).on('keypress', '#referencia', function(e){
    e.defaultPrevented;
    if(e.which == 13) {
        consignacionSalida();
        return false;
    }
});

// SALIDA POR AJUSTE
function realizarAjuste(){
    var accion = $("#seleccionar_proveedor").val();
    swal({
        title: "AJUSTE(RESTAR)",
        text: "¿Registrar la salida de los productos como ajuste de inventario?",
        icon: 'warning',
        closeOnClickOutside: false,
        closeOnEsc: false,
        buttons: {
            cancel: {
                text: "Cancelar",
                value: null,
                visible: true,
                className: "btn btn-flat btn-sm btn-default font-weight-bold text-uppercase",
                closeModal: true,
            },
            confirm: {
                text: "Sí, continuar",
                value: true,
                visible: true,
                className: "btn btn-flat btn-sm bg-navy font-weight-bold text-uppercase",
                closeModal: true,
            }
        }
    })
    .then((value) => {
        if(value){
            $.ajax({
                type : "POST",
                url : "api/ajusteSalida",
                dataType: 'json',
                data : {accion:accion},
                beforeSend: function(){ 
                    $("#ajuste").html('Procesando...').attr('disabled', 'disabled').addClass('disabled');
                    $("#consignacion").addClass('hidden');
                    $("#confirmar").addClass('hidden');
                },
                success : function(json) {
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
                                    className: "btn btn-sm btn-default btnCerrar btn-flat font-weight-bold text-uppercase",
                                    closeModal: true,
                                },
                                confirm: {
                                    text: "Mostrar nota",
                                    value: json.idsalida,
                                    visible: true,
                                    className: "btn btn-sm btn-primary btnNota btn-flat font-weight-bold text-uppercase",
                                    closeModal: true,
                                }
                            }
                        })
                        .then((value) => {
                            if(value != 'cerrar'){
                                window.open("https://eltimonlibreria.com/admin/ventasDeMostrador/nota/"+value);
                            }                            
                            cargarListaVenta();
                            $('#modalConfirmarVenta').modal('hide');
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
        
                    }
        
                },
                error : function(xhr, status) {
                    console.log('Ha ocurrido un problema interno');
                },
                complete : function(){
                    $("#ajuste").html('Ajuste de inventario (restar)').removeAttr('disabled').removeClass('disabled');
                    $("#consignacion").removeClass('hidden');
                    $("#confirmar").removeClass('hidden');
                }
            });
        }
    })
}
$(document).on('click', '#ajuste', function(e){
    realizarAjuste();
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

$('#modalConfirmarVenta').on('shown.bs.modal', function (e) {
    $("#metodoPago").val('efectivo').change();
    $("#efectivo").focus();
})

$('#modalProductos').on('shown.bs.modal', function (e) {

    if ( ! $.fn.DataTable.isDataTable( '#tablaProductosVer' ) ) {

        $('#tablaProductosVer thead tr').clone(true).appendTo( '#tablaProductosVer thead' ).addClass("bg-gray");
        $('#tablaProductosVer thead tr:eq(1) th').each( function (i) {
            if(i == 3 || i == 4){
                var title = $(this).text();
                $(this).html( '<input class="form-control input-sm" type="text" placeholder="Filtrar por '+title+'" style="max-width:170px;"/>' );
                $( 'input', this ).on( 'keyup change', function () {
                    if ( dataTable.column(i).search() !== this.value ) {
                        dataTable
                            .column(i)
                            .search( this.value )
                            .draw();
                    }
                } );
            }else{
                $(this).html( '' );
            }
        } );
    }else{
        $('#tablaProductosVer').DataTable().ajax.reload();
    }

    /* Cargar los datos en la tabla */
    var dataTable = $('#tablaProductosVer').DataTable({
        //"responsive": true,
        "scrollX": true,
        "order": [],   
        "orderCellsTop": true, 
        "ajax": {
            url: "api/mostrarProductosPV",
            type: "POST",
            "complete": function () {
                $('[data-toggle="tooltip"]').tooltip();
            }
        },
        "iDisplayLength" : 10, 
        "retrieve" : true,
        "deferRender": true,
        "processing" : true,
        "searchHighlight": true,
        "columnDefs": [
            {
                "targets": 0,
                "orderable": false,
            },
            {
                "targets": [5,6],
                "className": "text-center font-weight-bold",
            },
            {
                "targets": 7,
                "orderable": false,
            }],
        "language": {
            "decimal": "",
            "emptyTable": "No hay datos disponibles en la tabla",
            "info": "Mostrando _START_ al _END_ de _TOTAL_ registros",
            "infoEmpty": "Mostrando 0 a 0 de 0 registros",
            "infoFiltered": "(filtrado de _MAX_ entradas totales)",
            "infoPostFix": "",
            "thousands": ",",
            "lengthMenu": "Mostrar _MENU_ registros",
            "loadingRecords": "Cargando lista de productos...",
            "processing": "Procesando...",
            "search": "BUSCAR PRODUCTO:",
            "zeroRecords": "No se encontrarón resultados",
            "paginate": {
                "first": "<<",
                "last": ">>",
                "next": ">",
                "previous": "<"
            },
            "aria": {
                "sortAscending": ": activar para ordenar la columna ascendente",
                "sortDescending": ": activar para ordenar la columna descendente"
            }
        }
    });
    /* /Cargar los datos en la tabla */

    $('#tablaProductosVer_filter input[type="search"]').focus();
})

$('#modalProductos').on('hide.bs.modal hidden.bs.modal', function (e) {
    setTimeout(function (){
        limpiarBusqueda();
    }, 100);
})

$('#modalConfirmarVenta').on('hide.bs.modal hidden.bs.modal', function (e) {
    setTimeout(function (){
        limpiarBusqueda();
    }, 100);
})

$('.btnCerrar .btnTicket .btnNota').on('click', function (e) {
    setTimeout(function (){
        limpiarBusqueda();
    }, 100);
})

/* JS PUNTO DE VENTA */