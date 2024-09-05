/* __________________________________________________________________________________________________________ */
$(function(){

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
    
    $(".descuento").inputmask("decimal",{ 
        integerDigits: 2,
        digits: 2,
        digitsOptional: false,
        allowPlus: false,
        allowMinus: false,
        placeholder: "0",
        "oncleared": function(){ 
            $(this).val('0.00');  
        }
    });
    $(".devolucion").inputmask("decimal",{ 
        integerDigits: 2,
        digits: 0,
        digitsOptional: false,
        allowPlus: false,
        allowMinus: false,
        placeholder: "0",
        "oncleared": function(){ 
            $(this).val('0');  
        }
    });

    /* Cargar los datos en la tabla */
    var dataTable = $('#tablaClientes').DataTable({
        "responsive" : true,
        "order": [],
        "ajax" : {
            url:"api/mostrarClientes",
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
            "targets" : 8,
            "orderable" : false
        },
        {
            "targets" : 5,
            "className": "text-center"
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
            "loadingRecords": "Cargando lista de clientes...",
            "processing":     "Procesando...",
            "search":         "BUSCAR CLIENTE:",
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
    /* /.Cargar los datos en la tabla */

    /* Cambiar el estado de algun cliente */
    $(document).on('click', '.estado', function(){
        var id = $(this).attr("key");
        var estado = $(this).attr("value");
        $.ajax({
            url:"api/estadoCliente",
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
    /* /.Cambiar el estado de alguna categoría */

    /* Validar que el nombre del cliente no exista ya modal agregar */
    $(document).on('change', '.validar, .evalidar', function(){
        var item = $(this).attr('tipo');
        var valor = $(this).val();
        var id = $(this).attr("key");
        $.ajax({
            url:"api/validarCliente",
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
    /* /.Validar que el nombre del cliente no exista ya modal agregar */

    /**
     * Ajax action to api rest
    */
    function agregarCliente(){
        var $ocrendForm = $(this), __data = {};
        $('#agregar_form').serializeArray().map(function(x){__data[x.name] = x.value;}); 

        if(undefined === $ocrendForm.data('locked') || false === $ocrendForm.data('locked')) {
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
        agregarCliente();
    });

    /* Cargar datos en modal editar */
    $(document).on('click', '.obtenerCliente', function(){
        var id = $(this).attr("key");
        var metodo = "obtenerCliente";
        $.ajax({
            url:"api/obtenerCliente",
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
                    $("#erfc").inputmask({"mask": "A{3,4}-999999-***"});
                    $("#etelefono").inputmask({"mask": "(999) 999 9999"});
                    $(".datepicker").datepicker({
                        startDate: '-80y',
                        endDate: '-8y',
                        format: 'yyyy-mm-dd',
                        language: 'es',
                        clearBtn: true,
                        calendarWeeks: true,
                        autoclose: true
                    });
                }       
            },
            error : function(xhr, status) {
                alert('Ha ocurrido un problema interno');
            },
            complete : function(){
                $(".descuento").inputmask("decimal",{ 
                    integerDigits: 2,
                    digits: 2,
                    digitsOptional: false,
                    allowPlus: false,
                    allowMinus: false,
                    placeholder: "0",
                    "oncleared": function(){ 
                        $(this).val('0.00');  
                    }
                });
                $(".devolucion").inputmask("decimal",{ 
                    integerDigits: 2,
                    digits: 0,
                    digitsOptional: false,
                    allowPlus: false,
                    allowMinus: false,
                    placeholder: "0",
                    "oncleared": function(){ 
                        $(this).val('0');  
                    }
                });
            }
        })
    });
    /* /. Cargar datos en modal editar */

    /**
     * Ajax action to api rest
    */
    $('#editar').click(function(e) {
        e.defaultPrevented;
        editarCliente();
    });
    function editarCliente(){

        var formData = new FormData();

        formData.append("idC", $("#idC").val());
        formData.append("rfc", $("#erfc").val());
        formData.append("cliente", $("#ecliente").val());
        formData.append("correoElectronico", $("#ecorreo_electronico").val());
        formData.append("telefono", $("#etelefono").val());
        formData.append("descuento", $("#edescuento").val());
        formData.append("devolucion", $("#edevolucion").val());

        $.ajax({
            type : "POST",
            url : "api/editarCliente",
            dataType: 'json',
            data : formData,
            processData: false,
            contentType: false,
            beforeSend: function(){ 
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
                $("#editar").removeAttr("disabled");
            } 
        });
    } 


    /* EVENTO AL HACER CLIC EN BOTÓN ELIMINAR */
    $(document).on('click', '.eliminar', function(e){
        e.defaultPrevented;
        var id = $(this).attr("key");
        swal({
            title: "!Atención¡",
            text: "¿Estás seguro de eliminar el cliente?",
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
            if (value) {
                $.ajax({
                    type : "POST",
                    url : "api/eliminarCliente",
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
            }
        });
    });

});
/* __________________________________________________________________________________________________________ */


/* Limpiar el formulario del modal agregar */
$('.agregarCliente').on('click', function (e) {
    $("#agregar_form")[0].reset();

    $(".divLealtad").removeClass('hidden');
});