$(function(){

    $("#fecha").inputmask("9999-99-99",{ 
        "placeholder": "yyyy-mm-dd"
    });
    $(".datepicker").datepicker({
        format: 'yyyy-mm-dd',
        language: 'es',
        calendarWeeks: true,
        autoclose: true,
        todayHighlight: true,
        endDate: '+0d',
        datesDisabled: '+1d'
    });

    setTimeout(function() {
        cargarCaja(moment().format('YYYY-MM-DD'));
    }, 500);

    $("#fecha").val(moment().format('YYYY-MM-DD'));

})

google.charts.load('current', {'packages': ['corechart']});
google.charts.setOnLoadCallback();

$(document).on('change', '#fecha', function(e){

    var fecha = $(this).val();

    if(fecha == ''){
        swal({
            title: '!Opss¡',
            text: 'Es necesario especificar la fecha.',
            icon: 'error',
            closeOnClickOutside: false,
            closeOnEsc: false,
            buttons: {
                cancel: {
                    text: "Cerrar",
                    value: null,
                    visible: true,
                    className: "btn btn-sm btn-default",
                    closeModal: true,
                }
            }
        })
        .then((value) => {
            location.reload();
        });
    }else{
        cargarCaja(fecha);
    }

});

function cargarCaja(fecha){

    $.ajax({
        type : "POST",
        url : "api/cargarCajaPorDia",
        dataType: 'json',
        data : {fecha:fecha},
        success : function(json) {

            if(json.status == 'success'){

                $("span.estado").html(json.estado);
                $("#opciones_aperturaCaja").addClass('hidden');

                if(json.estado == 'Abierta'){
                    $("span.estado").removeClass('bg-black bg-gray').addClass('bg-aqua');
                    $("#opciones_cajaAbierta").removeClass('hidden');
                    $("#opciones_cajaCerrada").addClass('hidden');
                }else{
                    $("span.estado").removeClass('bg-aqua bg-gray').addClass('bg-black');
                    $("#opciones_cajaCerrada").removeClass('hidden');
                    $("#opciones_cajaAbierta").addClass('hidden');

                    if(json.fechaActual != fecha){
                        $("button.reaperturarCaja").addClass('hidden');
                    }else{
                        $("button.reaperturarCaja").removeClass('hidden');
                    }
                }

                $("span.monto").html(json.monto);

                $("span.total_ingresos").html(json.total_ingresos);
                $("#ingresos table tbody").html(json.tr_ingresos);

                $("span.total_cobros").html(json.total_cobros);
                $("#cobros table tbody").html(json.tr_cobros);
                
                $("span.total_abonos").html(json.total_abonos);
                $("#abonos table tbody").html(json.tr_abonos);

                $("span.total_egresos").html(json.total_egresos);
                $("#egresos table tbody").html(json.tr_egresos);

                $("span.total_pagos").html(json.total_pagos);
                $("#pagos table tbody").html(json.tr_pagos);

                $("span.saldo").html(json.saldo);
                
                $("span.total_tarjeta").html(json.total_tarjeta);
                $("#cobros_tarjeta table tbody").html(json.tr_cobros_tarjeta);
                
                $("span.total_stripe").html(json.total_stripe);
                $("#cobros_stripe table tbody").html(json.tr_cobros_stripe);

                $("#opciones_cajaCerrada a").attr("href", 'caja/descargar_reporte/'+fecha);

                cargarGraficoCaja(json.grafico);

            }else{
                swal({
                    title: json.title,
                    text: json.message,
                    icon: 'error',
                    closeOnClickOutside: false,
                    closeOnEsc: false,
                    buttons: {
                        cancel: {
                            text: "Cerrar",
                            value: null,
                            visible: true,
                            className: "btn btn-sm btn-default",
                            closeModal: true,
                        }
                    }
                })
                .then((value) => {

                    $("#piechart_3d").html('<p class="text-center">No hay datos para procesar el gráfico.</p>');
                    $(".monto, .total_ingresos, .total_cobros, .total_egresos, .total_pagos, .saldo").html('$ 0.00');
                    $("span.estado").removeClass('bg-black bg-aqua').addClass('bg-gray').html('Sin definir');
                    $("#ingresos table tbody, #cobros table tbody, #egresos table tbody, #pagos table tbody").html('<tr><td colspan="6" class="text-center"></td></tr>');
                    
                    $("#opciones_cajaAbierta").addClass('hidden');
                    $("#opciones_cajaCerrada").addClass('hidden');
                    if(json.error == 3){
                        $("#opciones_aperturaCaja").removeClass('hidden');
                    }else{
                        $("#opciones_aperturaCaja").addClass('hidden');
                    }

                });
            }
        },
        error : function(xhr, status) {
            alert('Ha ocurrido un problema interno');
        }
    });

}

function cargarGraficoCaja(datos) {


    var jsonData = datos;

    var data = new google.visualization.DataTable();
    data.addColumn('string', 'Movimiento');
    data.addColumn('number', 'Porcentaje');

    $.each(jsonData, function(i, jsonData){

        var movimiento = jsonData.movimiento;
        var total = parseFloat($.trim(jsonData.total));

        data.addRows([[movimiento, total]]);
    })

    var options = {
        chartArea:{left:0,top:30,bottom:30,width:'200%',height:'100%'},
        legend: 'none',
        pieStartAngle: 100,
        height:300,
        slices: {
            0: { color: '#00a65a' },
            1: { color: '#39cccc' },
            2: { color: '#d81b60' },
            3: { color: '#d33724' },
            4: { color: '#ff851b' },
            5: { color: '#605ca8' },
            6: { color: '#001f3f' }
        }
    };


    var chart = new google.visualization.PieChart(document.getElementById('piechart_3d'));
    chart.draw(data, options);

}

$(document).on('click', '#opciones_aperturaCaja button', function(e){
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
                alert('Ha ocurrido un problema interno');
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

$('li#cerrar_caja').click(function(e) {
    e.defaultPrevented;
    swal({
        title: "!Atención¡",
        text: "¿Esta seguro de realizar el cierre de caja?",
        icon: 'warning',
        closeOnClickOutside: false,
        closeOnEsc: false,
        buttons: {
            confirm: {
                text: "Sí, cerrar caja",
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
                        placeholder: "Ingresa tu contraseña",
                        type: "password",
                    },
                },
                text: 'Acción requerida:',
                closeOnClickOutside: false,
                closeOnEsc: false,
                buttons: {
                    confirm: {
                        text: "Cerrar caja",
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
                    url : "api/cerrarCaja",
                    dataType: 'json',
                    data : {pass:pass},
                    success:function(json){
                        if(json.status == 'success') {

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

$('#registrar_ingreso, #registrar_egreso, #registrar_pago').click(function(e) {
    e.defaultPrevented;
    var tipo = $(this).attr('tipo');
    $("span.tipo_movimiento").html(tipo);
    $.ajax({
        type : "POST",
        url : "api/obtenerFormularioMovimientos",
        dataType: 'json',
        data: {tipo:tipo},
        success : function(json) {
            $("#registrar_movimiento").html(json.formulario);
        },
        complete: function(){
            $("#input_monto_movimiento").number(true,2);
        }
    })
    
});

/*---------------------------------------------------------------------------------------------------*/

function registrarMovimiento(){
    var $ocrendForm = $(this), __data = {};
    $('#registrar_movimiento').serializeArray().map(function(x){__data[x.name] = x.value;}); 

    if(undefined == $ocrendForm.data('locked') || false == $ocrendForm.data('locked')) {
        $.ajax({
            type : "POST",
            url : "api/registrarMovimiento",
            dataType: 'json',
            data : __data,
            beforeSend: function(){ 
                $ocrendForm.data('locked', true);
                $("#registrar_movimiento_button").attr("disabled", true);
            },
            success : function(json) {
                if(json.status == 'success') {
                    $('#modalRegistrarMovimiento').modal('hide');
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
                alert('Ha ocurrido un problema interno');
            },
            complete: function(){ 
                $ocrendForm.data('locked', false);
                $("#registrar_movimiento_button").removeAttr("disabled");
            } 
        });
    }
}
$('#registrar_movimiento_button').click(function(e) {
    e.defaultPrevented;
    registrarMovimiento();
});


$('.reaperturarCaja').click(function(e) {
    e.defaultPrevented;

    swal({
        title: "!Atención¡",
        text: "¿Esta seguro de abrir nuevamente la caja?",
        icon: 'warning',
        closeOnClickOutside: false,
        closeOnEsc: false,
        buttons: {
            confirm: {
                text: "Sí, abrir nuevamente caja",
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
                        placeholder: "Ingresa tu contraseña",
                        type: "password",
                    },
                },
                text: 'Acción requerida:',
                closeOnClickOutside: false,
                closeOnEsc: false,
                buttons: {
                    confirm: {
                        text: "Abrir nuevamente caja",
                        value: true,
                        visible: true,
                        className: "btn btn-sm btn-primary",
                        closeModal: true,
                    }
                }
            })
            .then((value) => {

                var pass = value;
                var fecha = $("#fecha").val();
                
                $.ajax({
                    type : "POST",
                    url : "api/reaperturarCaja",
                    dataType: 'json',
                    data : {pass:pass,fecha:fecha},
                    success:function(json){
                        if(json.status == 'success') {

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
});