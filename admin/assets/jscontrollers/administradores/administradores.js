/* __________________________________________________________________________________________________________ */
$(function(){
    //Initialize Select2 Elements
    $('.seleccionarPerfil, .seleccionarAlmacen').select2({
        dropdownParent: $('#agregar_form'),
        width: "resolve"
    });

    /* Cargar los datos en la tabla */
    var dataTable = $('#tablaAdministradores').DataTable({
        "responsive" : true,
        "order": [],
        "ajax" : {
            url:"api/mostrarAdministradores",
            type:"POST"
        },
        "deferRender": true, 
        "retrieve" : true,
        "processing" : true,
        "columnDefs" : [
        {
            "targets" : [1, 6, 7],
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
        }
    });
    /* /.Cargar los datos en la tabla */

    /* Cambiar el estado de algún perfil */
    $(document).on('click', '.estado', function(){
        var id = $(this).attr("key");
        var estado = $(this).attr("value");
        $.ajax({
            url:"api/estadoAdministrador",
            method:'POST',
            dataType: 'json',
            data : {id:id,estado:estado},
            beforeSend: function(){
                $("span.estado"+id).html('<i class="fas fa-spinner fa-spin fa-lg text-muted"></i>');
            },
            success:function(json){
                
                if(json.status=='error'){
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
                dataTable.ajax.reload( null, false );
            } 
        })
    });
    /* /.Cambiar el estado de algún perfil */

    /* Validar que el nombre o correo no exista ya modal agregar */
    $(document).on('change', '.validar, .evalidar', function(){
        var id = $(this).attr('key');
        var item = $(this).attr('tipo');
        var valor = $(this).val();
        $.ajax({
            url:"api/validarAdministrador",
            method:'POST',
            dataType: 'json',
            data : {id:id,item:item,valor:valor},
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
                alert('Ha ocurrido un problema interno');
            }
        })
    });
    /* /.Validar que el nombre o correo no exista ya modal agregar */

    /* Obtener perfiles excluyendo el perfil principal seleccionado */
    $(document).on('change','.seleccionarPerfil, .eseleccionarPerfil', function(){
        perfil = $(this).val();
        if(perfil == 4){ // Perfil de un administrador de sucursal
            $(".almacen").removeClass('hidden');
            $(".ealmacen").removeClass('hidden');
        }else{
            $(".almacen").addClass('hidden');
            $(".ealmacen").addClass('hidden');
            $(".almacenAdicional").html('');
            $(".ealmacenAdicional").html('');
            $('.seleccionarAlmacen').val(null).trigger('change');
            $('.eseleccionarAlmacen').val(null).trigger('change');
        }
        $.ajax({
            url:"api/perfilesAdcionales",
            method: "POST",
            dataType: "json",
            data: {perfil:perfil},
            success: function(json){
                
                $('.perfilesAdcionales').html(json.html);

            },
            error: function(xhr, status){
                alert('Ha ocurrido un problema interno');
            }
        })
    })
    /* /.Obtener perfiles excluyendo el perfil principal seleccionado  */

    $(document).on('click', '.perfilesAdicionales', function(){
        perfil = $(this).attr('idPerfil');
        if(perfil == 4){
            if($(this).is(':checked')){
                $(".almacen").removeClass('hidden');
                $(".ealmacen").removeClass('hidden');
            }else{
                $(".almacen").addClass('hidden');
                $(".ealmacen").addClass('hidden');
                $(".almacenAdicional").html('');
                $(".ealmacenAdicional").html('');
                $('.seleccionarAlmacen').val(null).trigger('change');
                $('.eseleccionarAlmacen').val(null).trigger('change');
            }
        }
    })

    /* Obtener almacenes excluyendo el alamcen principal seleccionado */
    $(document).on('change','.seleccionarAlmacen, .eseleccionarAlmacen', function(){
        almacen = $(this).val();
        $.ajax({
            url:"api/almacenesAdcionales",
            method: "POST",
            dataType: "json",
            data: {almacen:almacen},
            success: function(json){
                
                $('.almacenAdicional').html(json.html);

            },
            error: function(xhr, status){
                alert('Ha ocurrido un problema interno');
            }
        })
    })
    /* /.Obtener almacenes excluyendo el alamcen principal seleccionado  */

    /* Agregar administrador */
    $('#enviar').click(function(e){
        e.defaultPrevented;
        agregarAdministrador();
    });
    function agregarAdministrador() {
        var form = $("#agregar_form")[0];
        var form_data = new FormData(form);
        $.ajax({
            type : "POST",
            url : "api/agregarAdministrador",
            dataType: 'json',
            data : form_data,
            contentType: false,
            processData: false,
            cache: false,
            beforeSend: function(){
                $("#enviar").attr("disabled", true);
            },
            success : function(json) {
                if(json.status == 'success') {
                    $('#modalAgregar').modal('hide');
                    dataTable.ajax.reload();
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
                $("#enviar").removeAttr("disabled");
            } 
        });
    }
    /* Agregar administrador */

    /* Cargar datos en modal editar */
    $(document).on('click', '.obtenerAdministrador', function(){
        var id = $(this).attr("key");
        var metodo = "obtenerAdministrador";
        $.ajax({
            url:"api/obtenerAdministrador",
            method:'POST',
            dataType: 'json',
            data : {id:id,metodo:metodo},
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
                            $('#modalEditar').modal('hide');
                        }
                    });
                } else if(json.status == "success"){
                    $("#editar_form").html(json.formulario);
                    $('.eseleccionarPerfil, .eseleccionarAlmacen').select2({
                        dropdownParent: $('#editar_form'),
                        width: "resolve"
                    });
                }       
            },
            error : function(xhr, status) {
                alert('Ha ocurrido un problema interno');
            }
        })
    });
    /* /. Cargar datos en modal editar */

    /* Editar perfil */
    $('#editar').click(function(e){
        e.defaultPrevented;
        editarAdministrador();
    });
    function editarAdministrador() {
        var form = $("#editar_form")[0];
        var form_data = new FormData(form);
        $.ajax({
            type : "POST",
            url : "api/editarAdministrador",
            dataType: 'json',
            data : form_data,
            contentType: false,
            processData: false,
            cache: false,
            beforeSend: function(){
                $("#editar").attr("disabled", true);
            },
            success : function(json) {
                if(json.status == 'success') {
                    $('#modalEditar').modal('hide');
                    dataTable.ajax.reload( null, false );
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
            complete : function(){
                $("#editar").removeAttr("disabled");
            }
        });
    }
    /* /Editar perfil */

    /* EVENTO AL HACER CLIC EN BOTÓN ELIMINAR */
    $(document).on('click', '.eliminar', function(e){
        e.defaultPrevented;
        var id = $(this).attr("key");
        swal({
            title: "!Atención¡",
            text: "¿Estás seguro de eliminar al administrador?",
            icon: 'warning',
            closeOnClickOutside: false,
            closeOnEsc: false,
            buttons: {
                confirm: {
                    text: "Sí, eliminar",
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
                    closeOnClickOutside: false,
                    closeOnEsc: false,
                    buttons: {
                        confirm: {
                            text: "Eliminar",
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
                        url : "api/eliminarAdministrador",
                        dataType: 'json',
                        data : {id:id,pass:pass},
                        beforeSend: function(){

                        },
                        success : function(json) {
                            if(json.status == 'success') {
                                dataTable.ajax.reload( null, false );
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
                    });
                });
            }
        });
    });

});
/* __________________________________________________________________________________________________________ */

/* CAMBIO DE INPUT FILE FOTO modal agregar */
$("#foto").change(function(){
    var imagen = this.files[0];
    if(imagen["type"] != 'image/jpeg' && imagen["type"] != 'image/png') {
        $("#foto").val(null);
        swal({
            title: "¡Opss!",
            text: "El formato del archivo no corresponde a una imagen.",
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
        $("#foto").val(null);
        swal({
            title: "¡Opss!",
            text: "El tamaño de la imagen no debe superar los 2 MB.",
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
            var image = new Image();
            image.src = rutaImagen;
            image.onload = function () {
                $(".previsualizarFoto").attr("src", rutaImagen);
            }
        })
    }
});
/* /.CAMBIO DE INPUT FILE FOTO modal agregar */

/* CAMBIO DE INPUT FILE FOTO modal editar */
$(document).on('change', '#efoto', function(){
    var imagen = this.files[0];
    if(imagen["type"] != 'image/jpeg' && imagen["type"] != 'image/png') {
        $("#efoto").val(null);
        swal({
            title: "¡Opss!",
            text: "El formato del archivo no corresponde a una imagen.",
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
        $("#efoto").val(null);
        swal({
            title: "¡Opss!",
            text: "El tamaño de la imagen no debe superar los 2 MB.",
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
            var image = new Image();
            image.src = rutaImagen;
            image.onload = function () {
                $(".eprevisualizarFoto").attr("src", rutaImagen);
            }
        })
    }
});
/* /.CAMBIO DE INPUT FILE FOTO modal editar */

/* Limpiar el formulario del modal agregar */
$('.agregarAdministrador').on('click', function (e) {
    $("#agregar_form")[0].reset();
    $('.seleccionarPerfil').val(null).trigger('change');
    $('.seleccionarAlmacen').val(null).trigger('change');
    $("#foto").val(null);
    $(".previsualizarFoto").attr("src", "assets/plantilla/vistas/img/perfiles/default/default.jpg");
});
/* Limpiar el formulario del modal agregar */