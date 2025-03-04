/* __________________________________________________________________________________________________________ */
$(function(){

    /* Cargar los datos en la tabla */
    var dataTable = $('#tablaCategorias').DataTable({
        "responsive" : true,
        "order": [],
        "ajax" : {
            url:"api/mostrarCategorias",
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
            "targets" : [0,2,3],
            "className": "text-center",
        },
        {
            "targets" : [0,2],
            "className": "text-center",
            "orderable" : false
        },
        {
            "targets" : [3, 4, 5],
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
            "loadingRecords": "Cargando lista de categorías...",
            "processing":     "Procesando...",
            "search":         "BUSCAR CATEGORÍA:",
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
    
    $('#tablaCategorias_filter input[type="search"]').focus();

    /* Cambiar el estado de alguna categoría */
    /*$(document).on('click', '.estado', function(){
        var id = $(this).attr("key");
        var estado = $(this).attr("value");
        $.ajax({
            url:"api/estadoCategoria",
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
    });*/
    /* /.Cambiar el estado de alguna categoría */

    /* Agregar categoría */
    $('#enviar').click(function(e){
        e.defaultPrevented;
        agregarCategoria();
    });
    function agregarCategoria() {
        var form = $("#agregar_form")[0];
        var form_data = new FormData(form);
        $.ajax({
            type : "POST",
            url : "api/agregarCategoria",
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
                            from: "bottom",
                            align: "center"
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
                                className: "btn btn-sm btn-flat font-weight-bold text-uppercase btn-primary",
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
    /* /.Agregar categoría */

    /* Cargar datos en modal editar */
    $(document).on('click', '.obtenerCategoria', function(){
        var id = $(this).attr("key");
        $.ajax({
            url:"api/obtenerCategoria",
            method:'POST',
            dataType: 'json',
            data : {id:id},
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
                                className: "btn btn-sm btn-flat font-weight-bold text-uppercase btn-primary",
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
                    $('.tagsinput').tagsinput({
                        maxTags: 10,
                        confirmKeys: [13,44],
                        cancelConfirmKeysOnEmpty: false,
                        trimValue: true
                    });
                    $("#eoFecha").inputmask("9999-99-99",{ 
                        "placeholder": "yyyy-mm-dd"
                    });
                    $(".datepicker").datepicker({
                        format: 'yyyy-mm-dd',
                        startDate: '0d',
                        language: 'es',
                        clearBtn: true,
                        calendarWeeks: true,
                        autoclose: true
                    });

                    $("#eoPorcentaje").inputmask({ 
                        alias: "decimal",
                        integerDigits: 2,
                        digits: 2,
                        digitsOptional: false,
                        allowPlus: false,
                        allowMinus: false,
                        "placeholder": "0", 
                        "oncomplete": function(){ 
                            $("#eoPrecio").attr("disabled", "disabled"); 
                            $("#eoPrecio").val(""); 
                        },
                        "oncleared": function(){ 
                            $("#eoPrecio").removeAttr("disabled");  
                            $("#eoPorcentaje").val(""); 
                        }
                    });

                    $("#eoPrecio").inputmask({ 
                        alias: "decimal",
                        integerDigits: 5,
                        digits: 2,
                        digitsOptional: false,
                        placeholder: "0",
                        allowMinus: false,
                        "oncomplete": function(){ 
                            $("#eoPorcentaje").attr("disabled", "disabled"); 
                            $("#eoPorcentaje").val(""); 
                        },
                        "oncleared": function(){ 
                            $("#eoPorcentaje").removeAttr("disabled"); 
                            $("#eoPrecio").val(""); 
                        }
                    });
                }       
            },
            error : function(xhr, status) {
                alert('Ha ocurrido un problema interno');
            }
        })
    });
    /* /. Cargar datos en modal editar */

    /* Editar categoría */
    $('#editar').click(function(e){
        e.defaultPrevented;
        editarCategoria();
    });
    function editarCategoria() {
        var form = $("#editar_form")[0];
        var form_data = new FormData(form);
        $.ajax({
            type : "POST",
            url : "api/editarCategoria",
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
                            from: "bottom",
                            align: "center"
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
                                className: "btn btn-sm btn-flat font-weight-bold text-uppercase btn-primary",
                                closeModal: true,
                            }
                        }
                    })
                    .then((value) => {
                        if(value){
                            $("#editar_form")[0].reset();
                            $(".eOfertaDetalles").removeClass("show").addClass("hidden");
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
    /* Editar categoría */

    /* EVENTO AL HACER CLIC EN BOTÓN ELIMINAR */
    $(document).on('click', '.eliminar', function(e){
        e.defaultPrevented;
        var id = $(this).attr("key");
        swal({
            title: "!Atención¡",
            text: "¿Estás seguro de eliminar la categoría?",
            icon: 'warning',
            closeOnClickOutside: false,
            closeOnEsc: false,
            buttons: {
                confirm: {
                    text: "Sí, eliminar",
                    value: true,
                    visible: true,
                    className: "btn btn-sm btn-flat font-weight-bold text-uppercase btn-danger",
                    closeModal: true,
                },
                cancel: {
                    text: "Cancelar",
                    value: null,
                    visible: true,
                    className: "btn btn-sm btn-flat font-weight-bold text-uppercase btn-default",
                    closeModal: true,
                }
            }
        })
        .then((value) => {
            if (value) {
                $.ajax({
                    type : "POST",
                    url : "api/eliminarCategoria",
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
                                    from: "bottom",
                                    align: "center"
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
                                        className: "btn btn-sm btn-flat font-weight-bold text-uppercase btn-primary",
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

/* Mostrar u ocultar div con campos para la oferta modal editar */
$(document).on('change', '#eOferta', function(){
    var oferta = $(this).val();
    if(oferta == 1){
        $(".eOfertaDetalles").removeClass("hidden").addClass("show"); 
    }else{
        $(".eOfertaDetalles").removeClass("show").addClass("hidden");
    }
});
/* /.Mostrar u ocultar div con campos para la oferta modal editar */

/* Limpiar el formulario del modal agregar */
$('.agregarCategoria').on('click', function (e) {
    $("#oferta").val(0);
    $("#oPrecio").removeAttr("disabled");  
    $("#oPorcentaje").removeAttr("disabled");
});
$('#modalAgregar').on('shown.bs.modal, hidden.bs.modal', function (e) {
    $("#agregar_form")[0].reset();
    $("#oPrecio").removeAttr("disabled");  
    $("#oPorcentaje").removeAttr("disabled");
    $(".ofertaDetalles").removeClass("show").addClass("hidden");
    $('.tagsinput').tagsinput('removeAll');
});
/* /.Limpiar el formulario del modal agregar */

$('.tagsinput').tagsinput({
    maxTags: 10,
    confirmKeys: [13,44],
    cancelConfirmKeysOnEmpty: false,
    trimValue: true
});