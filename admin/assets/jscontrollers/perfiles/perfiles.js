/* __________________________________________________________________________________________________________ */
$(function(){

    /* Cargar los datos en la tabla */
    var dataTable = $('#tablaPerfiles').DataTable({
        "responsive" : true,
        "order": [],
        "ajax" : {
            url:"api/mostrarPerfiles",
            type:"POST",
            "complete": function () {
                $('[data-toggle="tooltip"]').tooltip();
            }
        },
        "deferRender": true, 
        "retrieve" : true,
        "processing" : true,
        "columnDefs" : [
        {
            "targets" : [2, 3, 4],
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
            url:"api/estadoPerfil",
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

    /* Validar que el nombre del perfil no exista ya modal agregar */
    $(document).on('change', '.validar, .eValidar', function(){
        var perfil = $(this).val();
        var id = $(this).attr('key');
        var tipo = $(this).attr('tipo');
        $.ajax({
            url:"api/validarPerfil",
            method:'POST',
            dataType: 'json',
            data : {perfil:perfil,id:id},
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
                            if(tipo == 'registro'){
                                $(".validar").val("");
                                $(".validar").focus();
                            }else if(tipo == 'edicion'){
                                $(".eValidar").val("");
                                $(".eValidar").focus();
                            }
                        }
                    });
                }
            },
            error : function(xhr, status) {
                alert('Ha ocurrido un problema interno');
            }
        })
    });
    /* /Validar que el nombre del perfil no exista ya modal agregar */

    /* Cargar datos en modal editar */
    $(document).on('click', '.obtenerPerfil', function(){
        var id = $(this).attr("key");
        var metodo = "obtenerPerfil";
        $.ajax({
            url:"api/obtenerPerfil",
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
                    $(".indeterminate").prop({checked:true,indeterminate:true});

                    $(document).on('change', 'input[type="checkbox"]', function(e){

                        var checked = $(this).prop("checked"),
                            container = $(this).parent(),
                            siblings = container.siblings();

                        container.find('input[type="checkbox"]').prop({
                            indeterminate: false,
                            checked: checked
                        });

                        function checkSiblings(el) {

                            var parent = el.parent().parent(),
                                all = true;

                            el.siblings().each(function() {
                                return all = ($(this).children('input[type="checkbox"]').prop("checked") === checked);
                            });

                        if (all && checked) {

                            parent.children('input[type="checkbox"]').prop({
                                indeterminate: false,
                                checked: checked
                            });

                            checkSiblings(parent);

                        } else if (all && !checked) {

                            parent.children('input[type="checkbox"]').prop("checked", checked);
                            parent.children('input[type="checkbox"]').prop("indeterminate", (parent.find('input[type="checkbox"]:checked').length > 0));
                            checkSiblings(parent);

                        } else {

                            el.parents("li").children('input[type="checkbox"]').prop({
                                indeterminate: true,
                                checked: true
                            });

                        }


                      }

                      checkSiblings(container);
                    });
                }       
            },
            error : function(xhr, status) {
                alert('Ha ocurrido un problema interno');
            }
        })
    });
    /* /. Cargar datos en modal editar */

    $('.agregarPerfil').click(function(e){
        e.defaultPrevented;
        $.ajax({
            type : "POST",
            url : "api/cargarModulos",
            dataType: 'json',
            success : function(json){
                $('#cargarModulos').html(json);

                $('input[type="checkbox"]').change(function(e) {

                    var checked = $(this).prop("checked"),
                        container = $(this).parent(),
                        siblings = container.siblings();

                    container.find('input[type="checkbox"]').prop({
                        indeterminate: false,
                        checked: checked
                    });

                    function checkSiblings(el) {

                        var parent = el.parent().parent(),
                            all = true;

                        el.siblings().each(function() {
                            return all = ($(this).children('input[type="checkbox"]').prop("checked") === checked);
                        });

                    if (all && checked) {

                        parent.children('input[type="checkbox"]').prop({
                            indeterminate: false,
                            checked: checked
                        });

                        checkSiblings(parent);

                    } else if (all && !checked) {

                        parent.children('input[type="checkbox"]').prop("checked", checked);
                        parent.children('input[type="checkbox"]').prop("indeterminate", (parent.find('input[type="checkbox"]:checked').length > 0));
                        checkSiblings(parent);

                    } else {

                        el.parents("li").children('input[type="checkbox"]').prop({
                            indeterminate: true,
                            checked: true
                        });

                    }

                  }

                  checkSiblings(container);
                });
            },
            error : function(xhr, status) {
                alert('Ha ocurrido un problema interno');
            }
        });
    });

    /**
     * Events
     */
    $('#enviar').click(function(e) {
        e.defaultPrevented;
        agregarPerfil();
    });
    /**
     * Ajax action to api rest
    */
    function agregarPerfil(){
        var form = $("#agregar_form")[0];
        var form_data = new FormData(form);
        $.ajax({
            type : "POST",
            url : "api/agregarPerfil",
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

    /* Editar perfil */
    $('#editar').click(function(e){
        e.defaultPrevented;
        editarPerfil();
    });
    function editarPerfil() {
        var form = $("#editar_form")[0];
        var form_data = new FormData(form);
        $.ajax({
            type : "POST",
            url : "api/editarPerfil",
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
                    swal({
                        title: json.title,
                        text: json.message,
                        icon: "success",
                        closeOnClickOutside: false,
                        closeOnEsc: true,
                        buttons: {
                            confirm: {
                                text: "Ok",
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
            text: "¿Estás seguro de eliminar el perfil?",
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
                        url : "api/eliminarPerfil",
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
                            if(json.status == 'info'){
                                swal({
                                    title: json.title,
                                    text: json.message,
                                    icon: "info",
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

/* Limpiar el formulario del modal agregar */
$('.agregarPerfil').on('click', function (e) {
    $("#agregar_form")[0].reset();
});
/* Limpiar el formulario del modal agregar */