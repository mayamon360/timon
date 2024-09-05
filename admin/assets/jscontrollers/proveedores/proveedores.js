/* __________________________________________________________________________________________________________ */
$(function(){

    $("#rfc").inputmask({"mask": "A{3,4}-999999-***"});
    $("#telefono").inputmask({"mask": "(999) 999 9999"});

    /* Cargar los datos en la tabla */
    var dataTable = $('#tablaProveedores').DataTable({
        "responsive" : true,
        "order": [],
        "ajax" : {
            url:"api/mostrarProveedores",
            type:"POST"
        },
        "deferRender": true, 
        "retrieve" : true,
        "processing" : true,
        "columnDefs" : [
        {
            "targets" : [3, 7],
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
            "loadingRecords": "Cargando lista de proveedores...",
            "processing":     "Procesando...",
            "search":         "BUSCAR PROVEEDOR:",
            "zeroRecords":    "No se encontraron registros coincidentes",
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
    /* /.Cargar los datos en la tabla */

    /* Cambiar el estado de alguna categoría */
    $(document).on('click', '.estado', function(){
        var id = $(this).attr("key");
        var estado = $(this).attr("value");
        $.ajax({
            url:"api/estadoProveedor",
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
            complete: function(){ 
                dataTable.ajax.reload( null, false );
            } 
        })
    });
    /* /.Cambiar el estado de alguna categoría */

    /* Validar que el nombre del cliente no exista ya modal agregar */
    $(document).on('change', '.validar, .evalidar', function(){
        var item = $(this).attr('tipo');
        var valor = $(this).val();
        var id = $(this).attr("key");
        $.ajax({
            url:"api/validarProveedor",
            method:'POST',
            dataType: 'json',
            data : {item:item,valor:valor,id:id},
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
                                className: "btn btn-sm btn-primary btn-flat font-weight-bold text-uppercase",
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
    /* /.Validar que el nombre del proveedor no exista ya modal agregar */

    /**
     * Ajax action to api rest
    */
    function agregarProveedor(){
        var $ocrendForm = $(this), __data = {};
        $('#agregar_form').serializeArray().map(function(x){__data[x.name] = x.value;}); 

        if(undefined == $ocrendForm.data('locked') || false == $ocrendForm.data('locked')) {
            $.ajax({
                type : "POST",
                url : "api/agregarProveedor",
                dataType: 'json',
                data : __data,
                beforeSend: function(){ 
                    $ocrendForm.data('locked', true);
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
                                    className: "btn btn-sm btn-primary btn-flat font-weight-bold text-uppercase",
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
                    $("#enviar").removeAttr("disabled");
                } 
            });
        }
    } 
    /**
     * Events
     */
    $('#enviar').click(function(e) {
        e.defaultPrevented;
        agregarProveedor();
    });

    /* Cargar datos en modal editar */
    $(document).on('click', '.obtenerProveedor', function(){
        var id = $(this).attr("key");
        var metodo = "obtenerProveedor";
        $.ajax({
            url:"api/obtenerProveedor",
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
                                className: "btn btn-sm btn-primary btn-flat font-weight-bold text-uppercase",
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
                    $("#erfc").inputmask({"mask": "A{3,4}-999999-***"});
                    $("#etelefono").inputmask({"mask": "(999) 999 9999"});
                }       
            },
            error : function(xhr, status) {
                alert('Ha ocurrido un problema interno');
            }
        })
    });
    /* /. Cargar datos en modal editar */

    /**
     * Ajax action to api rest
    */
    function editarProveedor(){
        var $ocrendForm = $(this), __data = {};
        $('#editar_form').serializeArray().map(function(x){__data[x.name] = x.value;}); 

        if(undefined == $ocrendForm.data('locked') || false == $ocrendForm.data('locked')) {
            $.ajax({
                type : "POST",
                url : "api/editarProveedor",
                dataType: 'json',
                data : __data,
                beforeSend: function(){ 
                    $ocrendForm.data('locked', true);
                    $("#editar").attr("disabled", true);
                },
                success : function(json) {
                    if(json.status == 'success') {
                        $('#modalEditar').modal('hide');
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
                                    className: "btn btn-sm btn-primary btn-flat font-weight-bold text-uppercase",
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
                    $("#editar").removeAttr("disabled");
                } 
            });
        }
    } 
    /**
     * Events
     */
    $('#editar').click(function(e) {
        e.defaultPrevented;
        editarProveedor();
    });

    /* EVENTO AL HACER CLIC EN BOTÓN ELIMINAR */
    $(document).on('click', '.eliminar', function(e){
        e.defaultPrevented;
        var id = $(this).attr("key");
        swal({
            title: "!Atención¡",
            text: "¿Estás seguro de eliminar el proveedor?",
            icon: 'warning',
            closeOnClickOutside: false,
            closeOnEsc: false,
            buttons: {
                confirm: {
                    text: "Sí, eliminar",
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
            if (value) {
                $.ajax({
                    type : "POST",
                    url : "api/eliminarProveedor",
                    dataType: 'json',
                    data : {id:id},
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
                                        className: "btn btn-sm btn-primary btn-flat font-weight-bold text-uppercase",
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
            }
        });
    });

});
/* __________________________________________________________________________________________________________ */

/* Limpiar el formulario del modal agregar */
$('.agregarProveedor').on('click', function (e) {
    $("#agregar_form")[0].reset();
});