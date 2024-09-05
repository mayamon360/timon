// DATOS COMERCIO ////////////////////////////////////////////////////////////////////////

/* 
    Acciones al detectar cambio en datos del comercio
*/
$(".datosComercio").keyup(function(){

    $("#guardarDatosComercio").removeAttr("disabled");

})

$("#guardarDatosComercio").click(function(){

    var datos = new FormData();
    datos.append("nombre", $("#nombreComercio").val());
    datos.append("direccion", $("#direccionComercio").val());
    datos.append("telefono", $("#telefonoComercio").val());
    datos.append("correo", $("#correoComercio").val());
    datos.append("metodo", "cambiarDatos");

    $.ajax({

        type : "POST",
        url : "api/cambiarDatos",
        dataType: 'json',
        data : datos,
        contentType: false,
        processData: false,
        cache: false,
        beforeSend: function(){ 
              
        },
        success: function(json){
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

                $("#guardarDatosComercio").attr('disabled','disabled');

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
        error: function(xhr, status){

        },
        complete: function(){

        }
    })

})


// LOGOTIPO ////////////////////////////////////////////////////////////////////////

/* 
    Acciones al detectar cambio en el logotipo 
*/
$("#subirLogo").change(function(){

    var imagen = this.files[0];

    if(imagen["type"] != 'image/jpeg' && imagen["type"] != 'image/png') {

        $("#subirLogo").val(null);

        swal({
            title: "¡Opss!",
            text: "El formato de la imagen no es válido.",
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

    } else if(imagen["size"] > 2000000) {

        $("#subirLogo").val(null);

        swal({
            title: "¡Opss!",
            text: "El tamaño de la imagen superara los 2 Mb.",
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

    } else {

        var datosImagen = new FileReader;
        datosImagen.readAsDataURL(imagen);

        $(datosImagen).on("load", function(event){

            var rutaImagen = event.target.result;

            $(".previsualizarLogo").attr("src", rutaImagen);

        })

        $(".guardarLogo").removeAttr('disabled');

    }

});

$(".guardarLogo").click(function(){

    var datos = new FormData();
    datos.append("imagen", document.getElementById('subirLogo').files[0]);
    datos.append("metodo", "cambiarLogo");

    $.ajax({
        type : "POST",
        url : "api/cambiarLogo",
        dataType: 'json',
        data : datos,
        contentType: false,
        processData: false,
        cache: false,
        beforeSend: function(){ 
            
        },
        success : function(json) {
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

                $("#subirLogo").val(null);
                $(".guardarLogo").attr('disabled','disabled');

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

                $("#subirLogo").val(null);

            }

        },
        error : function(xhr, status) {
            alert('Ha ocurrido un problema interno');
        },
        complete: function(){ 
            
        } 
    });

});


/* 
    Acciones al detectar cambio en el icono 
*/
$("#subirIcono").change(function(){

    var imagen = this.files[0];

    if(imagen["type"] != 'image/jpeg' && imagen["type"] != 'image/png') {

        $("#subirIcono").val(null);

        swal({
            title: "¡Opss!",
            text: "El formato de la imagen no es válido.",
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

    } else if(imagen["size"] > 2000000) {

        $("#subirIcono").val(null);

        swal({
            title: "¡Opss!",
            text: "El tamaño de la imagen superara los 2 Mb.",
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

    } else {

        var datosImagen = new FileReader;
        datosImagen.readAsDataURL(imagen);

        $(datosImagen).on("load", function(event){

            var rutaImagen = event.target.result;

            $(".previsualizarIcono").attr("src", rutaImagen);
            $("#iconURL").attr("href", rutaImagen);
            $(".previsualizarIcono").css({ 'width':'50px', 'height':'50px' });

        })

        $(".guardarIcono").removeAttr('disabled');

    }

});

$(".guardarIcono").click(function(){

    var datos = new FormData();
    datos.append("imagen", document.getElementById('subirIcono').files[0]);
    datos.append("metodo", "cambiarIcono");

    $.ajax({
        type : "POST",
        url : "api/cambiarLogo",
        dataType: 'json',
        data : datos,
        contentType: false,
        processData: false,
        cache: false,
        beforeSend: function(){ 
            
        },
        success : function(json) {
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

                $("#subirIcono").val(null);
                $(".guardarIcono").attr('disabled','disabled');

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

                $("#subirIcono").val(null);

            }

        },
        error : function(xhr, status) {
            alert('Ha ocurrido un problema interno');
        },
        complete: function(){ 
            
        } 
    });

});


// COLORES /////////////////////////////////////////////////////////////////////////

/* 
    Acciones al detectar cambio en el los colores 
*/

$(function() { 

    var cBase1 = $("#color1 #colorInput1").val();                           //Obtenemos el valor base del color para cada input y lo guardamos

    $('#color1 #colorMuestra1').colorpicker({                               //Habilitamos el colorpicker 
        color : cBase1                                                      //Agregamos el color base que guardamos arriba
    }).on('changeColor', function(e) {                                      //Evento al detectar cambio en el colorpicker
        var cNuevo1 = e.color.toString('rgba');                             //Guadamos el nuevo color      
        $("#color1 #colorInput1").val(cNuevo1);                             //Agregamos el valor del color nuevo al input
        $(this).css("color", cNuevo1);                                     //Cambiamos el color del cuadro donde se muestra la previsualización el color
        $("#guardarColores").removeAttr('disabled');                        //Activamos el boton para guardar
    }); 

    $('#color1 #colorInput1').on('change', function(){                       //Evento al detectar cambio en el input
        $("#color1 #colorMuestra1").css("color", $(this).val());             //Cambiamos el color del cuadro donde se muestra la previsualización el color por el valor del input
        $('#color1 #colorMuestra1').colorpicker('setValue', $(this).val());  //Asignamos el valor del input al colorpicker
        $('#color1 #colorMuestra1').colorpicker('update');                   //Actualizamos el colorpicker

        if($('#color1 #colorMuestra1').colorpicker('getValue') == '#aN' || $('#color1 #colorMuestra1').colorpicker('getValue').search('aN') >= 0) {   //Si el valor del input no corresponde a un color valido
            $('#color1 #colorMuestra1').colorpicker('setValue', cBase1);     //Restauramos el valor base
            $('#color1 #colorMuestra1').colorpicker('update');               //Actualizamos el colorpicker nuevamente
        }

    });



    var cBase2 = $("#color2 #colorInput2").val();                           //Obtenemos el valor base del color para cada input y lo guardamos

    $('#color2 #colorMuestra2').colorpicker({                               //Habilitamos el colorpicker 
        color : cBase2                                                      //Agregamos el color base que guardamos arriba
    }).on('changeColor', function(e) {                                      //Evento al detectar cambio en el colorpicker
        var cNuevo2 = e.color.toString('rgba');                             //Guadamos el nuevo color      
        $("#color2 #colorInput2").val(cNuevo2);                             //Agregamos el valor del color nuevo al input
        $(this).css("color", cNuevo2);                                      //Cambiamos el color del cuadro donde se muestra la previsualización el color
        $("#guardarColores").removeAttr('disabled');                        //Activamos el boton para guardar
    }); 

    $('#color2 #colorInput2').on('change', function(){                       //Evento al detectar cambio en el input
        $("#color2 #colorMuestra2").css("color", $(this).val());             //Cambiamos el color del cuadro donde se muestra la previsualización el color por el valor del input
        $('#color2 #colorMuestra2').colorpicker('setValue', $(this).val());  //Asignamos el valor del input al colorpicker
        $('#color2 #colorMuestra2').colorpicker('update');                   //Actualizamos el colorpicker

        if($('#color2 #colorMuestra2').colorpicker('getValue') == '#aN' || $('#color2 #colorMuestra2').colorpicker('getValue').search('aN') >= 0) {   //Si el valor del input no corresponde a un color valido
            $('#color2 #colorMuestra2').colorpicker('setValue', cBase2);     //Restauramos el valor base
            $('#color2 #colorMuestra2').colorpicker('update');               //Actualizamos el colorpicker nuevamente
        }

    });



    var cBase3 = $("#color3 #colorInput3").val();                           //Obtenemos el valor base del color para cada input y lo guardamos

    $('#color3 #colorMuestra3').colorpicker({                               //Habilitamos el colorpicker 
        color : cBase3                                                      //Agregamos el color base que guardamos arriba
    }).on('changeColor', function(e) {                                      //Evento al detectar cambio en el colorpicker
        var cNuevo3 = e.color.toString('rgba');                             //Guadamos el nuevo color      
        $("#color3 #colorInput3").val(cNuevo3);                             //Agregamos el valor del color nuevo al input
        $(this).css("color", cNuevo3);                                      //Cambiamos el color del cuadro donde se muestra la previsualización el color
        $("#guardarColores").removeAttr('disabled');                        //Activamos el boton para guardar
    }); 

    $('#color3 #colorInput3').on('change', function(){                       //Evento al detectar cambio en el input
        $("#color3 #colorMuestra3").css("color", $(this).val());             //Cambiamos el color del cuadro donde se muestra la previsualización el color por el valor del input
        $('#color3 #colorMuestra3').colorpicker('setValue', $(this).val());  //Asignamos el valor del input al colorpicker
        $('#color3 #colorMuestra3').colorpicker('update');                   //Actualizamos el colorpicker

        if($('#color3 #colorMuestra3').colorpicker('getValue') == '#aN' || $('#color3 #colorMuestra3').colorpicker('getValue').search('aN') >= 0) {   //Si el valor del input no corresponde a un color valido
            $('#color3 #colorMuestra3').colorpicker('setValue', cBase3);     //Restauramos el valor base
            $('#color3 #colorMuestra3').colorpicker('update');               //Actualizamos el colorpicker nuevamente
        }

    });



    var cBase4 = $("#color4 #colorInput4").val();                           //Obtenemos el valor base del color para cada input y lo guardamos

    $('#color4 #colorMuestra4').colorpicker({                               //Habilitamos el colorpicker 
        color : cBase4                                                      //Agregamos el color base que guardamos arriba
    }).on('changeColor', function(e) {                                      //Evento al detectar cambio en el colorpicker
        var cNuevo4 = e.color.toString('rgba');                             //Guadamos el nuevo color      
        $("#color4 #colorInput4").val(cNuevo4);                             //Agregamos el valor del color nuevo al input
        $(this).css("color", cNuevo4);                                      //Cambiamos el color del cuadro donde se muestra la previsualización el color
        $("#guardarColores").removeAttr('disabled');                        //Activamos el boton para guardar
    }); 

    $('#color4 #colorInput4').on('change', function(){                       //Evento al detectar cambio en el input
        $("#color4 #colorMuestra4").css("color", $(this).val());             //Cambiamos el color del cuadro donde se muestra la previsualización el color por el valor del input
        $('#color4 #colorMuestra4').colorpicker('setValue', $(this).val());  //Asignamos el valor del input al colorpicker
        $('#color4 #colorMuestra4').colorpicker('update');                   //Actualizamos el colorpicker

        if($('#color4 #colorMuestra4').colorpicker('getValue') == '#aN' || $('#color4 #colorMuestra4').colorpicker('getValue').search('aN') >= 0) {   //Si el valor del input no corresponde a un color valido
            $('#color4 #colorMuestra4').colorpicker('setValue', cBase4);     //Restauramos el valor base
            $('#color4 #colorMuestra4').colorpicker('update');               //Actualizamos el colorpicker nuevamente
        }

    });



    var cBase5 = $("#color5 #colorInput5").val();                           //Obtenemos el valor base del color para cada input y lo guardamos

    $('#color5 #colorMuestra5').colorpicker({                               //Habilitamos el colorpicker 
        color : cBase5                                                      //Agregamos el color base que guardamos arriba
    }).on('changeColor', function(e) {                                      //Evento al detectar cambio en el colorpicker
        var cNuevo5 = e.color.toString('rgba');                             //Guadamos el nuevo color      
        $("#color5 #colorInput5").val(cNuevo5);                             //Agregamos el valor del color nuevo al input
        $(this).css("color", cNuevo5);                                      //Cambiamos el color del cuadro donde se muestra la previsualización el color
        $("#guardarColores").removeAttr('disabled');                        //Activamos el boton para guardar
    }); 

    $('#color5 #colorInput5').on('change', function(){                       //Evento al detectar cambio en el input
        $("#color5 #colorMuestra5").css("color", $(this).val());             //Cambiamos el color del cuadro donde se muestra la previsualización el color por el valor del input
        $('#color5 #colorMuestra5').colorpicker('setValue', $(this).val());  //Asignamos el valor del input al colorpicker
        $('#color5 #colorMuestra5').colorpicker('update');                   //Actualizamos el colorpicker

        if($('#color5 #colorMuestra5').colorpicker('getValue') == '#aN' || $('#color5 #colorMuestra5').colorpicker('getValue').search('aN') >= 0) {   //Si el valor del input no corresponde a un color valido
            $('#color5 #colorMuestra5').colorpicker('setValue', cBase5);     //Restauramos el valor base
            $('#color5 #colorMuestra5').colorpicker('update');               //Actualizamos el colorpicker nuevamente
        }

    });



    var cBase6 = $("#color6 #colorInput6").val();                           //Obtenemos el valor base del color para cada input y lo guardamos

    $('#color6 #colorMuestra6').colorpicker({                               //Habilitamos el colorpicker 
        color : cBase6                                                      //Agregamos el color base que guardamos arriba
    }).on('changeColor', function(e) {                                      //Evento al detectar cambio en el colorpicker
        var cNuevo6 = e.color.toString('rgba');                             //Guadamos el nuevo color      
        $("#color6 #colorInput6").val(cNuevo6);                             //Agregamos el valor del color nuevo al input
        $(this).css("color", cNuevo6);                                      //Cambiamos el color del cuadro donde se muestra la previsualización el color
        $("#guardarColores").removeAttr('disabled');                        //Activamos el boton para guardar
    }); 

    $('#color6 #colorInput6').on('change', function(){                       //Evento al detectar cambio en el input
        $("#color6 #colorMuestra6").css("color", $(this).val());             //Cambiamos el color del cuadro donde se muestra la previsualización el color por el valor del input
        $('#color6 #colorMuestra6').colorpicker('setValue', $(this).val());  //Asignamos el valor del input al colorpicker
        $('#color6 #colorMuestra6').colorpicker('update');                   //Actualizamos el colorpicker

        if($('#color6 #colorMuestra6').colorpicker('getValue') == '#aN' || $('#color6 #colorMuestra6').colorpicker('getValue').search('aN') >= 0) {   //Si el valor del input no corresponde a un color valido
            $('#color6 #colorMuestra6').colorpicker('setValue', cBase6);     //Restauramos el valor base
            $('#color6 #colorMuestra6').colorpicker('update');               //Actualizamos el colorpicker nuevamente
        }

    });

});


$("#guardarColores").click(function(){

    var datos = new FormData();
    datos.append("barraSuperior", $("#colorInput1").val());
    datos.append("textoSuperior", $("#colorInput2").val());
    datos.append("textoHover", $("#colorInput3").val());
    datos.append("borderDivisor", $("#colorInput4").val());
    datos.append("colorP1", $("#colorInput5").val());
    datos.append("colorP2", $("#colorInput6").val());
    datos.append("metodo", 'cambiarColores');

    $.ajax({
        type : 'POST',
        url : 'api/cambiarColores',
        dataType: 'json',
        data : datos,
        contentType: false,
        processData: false,
        cache: false,
        beforeSend: function(){ 
            
        },
        success : function(json) {
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

                $("#guardarColores").attr('disabled','disabled');

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
            
        } 
    });

})



// REDES SOCIALES /////////////////////////////////////////////////////////////////////////

var checkBox = $(".seleccionarRed");

/* 
    Acción al detectar cambio en la clase de los enlaces 
*/
$("input[name='colorRedSocial']").on("ifChecked", function(){//https://github.com/fronteed/iCheck
    var color = $(this).val();
    var colorRed = null;

    var filas = $(".redSocial");
    var iconos = $(".redSocial .fab");

    var redesIcono = ["facebook-square", "youtube", "twitter", "google-plus-g", "instagram"];
    var redesNombre = ["facebook", "youtube", "twitter", "google", "instagram"];

    if(color == "C") {

        colorRed = "C";

    } else if (color == "B") {

        colorRed = "B";

    } else {

        colorRed = "N";

    }

    for(var i = 0; i < filas.length; i++) { // se puede usar las variables filas, iconos o checkBox para hacer el ciclo for

        $(filas[i]).attr("class", "input-group-addon redSocial "+colorRed);
        $(iconos[i]).attr("class", "fab fa-"+redesIcono[i]+" "+redesNombre[i]+colorRed);
        $(checkBox[i]).attr("clase", redesNombre[i]+colorRed);
        
    }

    $("#guardarRedesSociales").removeAttr('disabled');

    jsonRedesSociales();

})

/* 
    Acción al detectar cambio en la url de alguna red social
*/
$(".cambiarUrlRed").change(function(){

    var cambiarUrlRed = $(".cambiarUrlRed");

    for(var i = 0; i < cambiarUrlRed.length; i++) {

        $(checkBox[i]).attr("ruta", $(cambiarUrlRed[i]).val());

    }

    $("#guardarRedesSociales").removeAttr('disabled');

    jsonRedesSociales();

})

/* 
    Quitar red social
*/
$(".seleccionarRed").on("ifUnchecked", function(){

    $(this).attr("estatus", "off");

    $("#guardarRedesSociales").removeAttr('disabled');

    jsonRedesSociales();

});


/* 
    Agregar red social
*/
$(".seleccionarRed").on("ifChecked", function(){

    $(this).attr("estatus", "on");

    $("#guardarRedesSociales").removeAttr('disabled');

    jsonRedesSociales();

});

/* 
    Crear datos json para almacenar
*/
function jsonRedesSociales() {

    var redesSociales = [];

    for(var i = 0; i < checkBox.length; i++) {

        if($(checkBox[i]).attr("estatus") == "on") {

            var ruta = ($(checkBox[i]).attr("ruta") == "") ? "" : $(checkBox[i]).attr("ruta");

            redesSociales.push({"red" : $(checkBox[i]).attr("red"),
                                "estilo" : $(checkBox[i]).attr("estilo"),
                                "clase" : $(checkBox[i]).attr("clase"),
                                "url" : ruta,
                                "estatus" : "on"});

        } else {

            redesSociales.push({"red" : $(checkBox[i]).attr("red"),
                                "estilo" : $(checkBox[i]).attr("estilo"),
                                "clase" : $(checkBox[i]).attr("clase"),
                                "url" : ruta,
                                "estatus" : "off"});

        }

    }

    return datosRedesSociales = JSON.stringify(redesSociales);

}

/* 
    Guardar cambios en redes sociales
*/

$("#guardarRedesSociales").click(function(){

    var datos = new FormData();
    datos.append("redesSociales", jsonRedesSociales());
    datos.append("metodo", "cambiarRS");

    $.ajax({

        type : 'POST',
        url : 'api/cambiarRS',
        dataType: 'json',
        data : datos,
        contentType: false,
        processData: false,
        cache: false,
        beforeSend: function(){ 
            
        },
        success : function(json) {
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

                $("#guardarRedesSociales").attr('disabled','disabled');

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
            
        } 

    })

})



// CÓDIGOS /////////////////////////////////////////////////////////////////////////

/* 
    Acciones al detectar cambio en códigos
*/
$(".codigos").keyup(function(){

    $("#pwdCodigos").removeAttr("disabled");
    $("#guardarCodigos").removeAttr("disabled");

})

$("#guardarCodigos").click(function(){

    var datos = new FormData();
    datos.append("googleMaps", $("#googleMaps").val());
    datos.append("googleAnalytics", $("#googleAnalytics").val());
    datos.append("apiFacebook", $("#apiFacebook").val());
    datos.append("pixelFacebook", $("#pixelFacebook").val());
    datos.append("password", $("#pwdCodigos").val());
    datos.append("metodo", "cambiarCodigos");

    $.ajax({

        type : 'POST',
        url : 'api/cambiarCodigos',
        dataType: 'json',
        data : datos,
        contentType: false,
        processData: false,
        cache: false,
        beforeSend: function(){ 
            
        },
        success : function(json) {
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

                $("#pwdCodigos").val('');
                $("#pwdCodigos").attr('disabled','disabled');
                $("#guardarRedesSociales").attr('disabled','disabled');

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
            
        } 

    });

});


// INFORMACIÓN COMERCIAL /////////////////////////////////////////////////////////////////////////https://www.youporn.com/watch/15117969/milf-takes-cum-in-mouth-pussy-ass-tits-compliation/

/* 
    Acciones para cargar el select con información de paises
*/

$.ajax({
    url : "api/cargarPaises",
    type : "POST",
    dataType: 'json',
    success : function(json){
        var jsonPaises = jQuery.parseJSON(json);

        jsonPaises.forEach(seleccionarPais);

        function seleccionarPais(item, index){

            var pais = item.pais;
            var codPais = item.codigo;

            if($("#codigoPais").val() == codPais) {
                $("#seleccionarPais").append('<option value="'+codPais+'" selected="selected">'+pais+'</option>');
            }else{
                $("#seleccionarPais").append('<option value="'+codPais+'">'+pais+'</option>');
            }

        }

        

    },
    error : function(xhr, status) {
        console.log('Ha ocurrido un problema interno');
    }

});


/* 
    Acciones al detectar cambio en códigos
*/
$(".informacionComercialK").keyup(function(){

    $("#pwdInformacion").removeAttr('disabled');
    $("#guardarInformacionComercial").removeAttr('disabled');

})

$(".informacionComercialR").on('ifChanged', function(event){

    $("#pwdInformacion").removeAttr('disabled');
    $("#guardarInformacionComercial").removeAttr('disabled');

})

$(".informacionComercialC").change(function(){

    $("#pwdInformacion").removeAttr('disabled');
    $("#guardarInformacionComercial").removeAttr('disabled');

})

/* 
    Acciones al hacer clic en el boton #guardarInformacionComercial
*/
$("#guardarInformacionComercial").click(function(){

    var datos = new FormData();
    datos.append('impuesto', $("#impuesto").val());
    datos.append('envioNac', $("#envioNacional").val());
    datos.append('envioInt', $("#envioInternacional").val());
    datos.append('tasaMinNac', $("#tasaMinimaNac").val())
    datos.append('tasaMinInt', $("#tasaMinimaInt").val());
    datos.append('pais', $("#seleccionarPais").val());
    datos.append('modoPaypal', $("input[name='modoPaypal']:checked").val());
    datos.append('clientID', $("#clientIdPaypal").val());
    datos.append('secretKey', $("#secretPaypal").val());
    datos.append('modoPayu', $("input[name='modoPayu']:checked").val());
    datos.append('merchantID', $("#merchantId").val());
    datos.append('accountID', $("#accountId").val());
    datos.append('apiKey', $("#apiKey").val());
    datos.append("password", $("#pwdInformacion").val());
    datos.append('metodo', 'cambiarInformacion');

    $.ajax({

        type : 'POST',
        url : 'api/cambiarInformacion',
        dataType: 'json',
        data : datos,
        contentType: false,
        processData: false,
        cache: false,
        beforeSend: function(){ 
            
        },
        success : function(json) {
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

                $("#pwdInformacion").val('');
                $("#pwdInformacion").attr('disabled','disabled');
                $("#guardarInformacionComercial").attr('disabled','disabled');

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
            
        }

    });

})