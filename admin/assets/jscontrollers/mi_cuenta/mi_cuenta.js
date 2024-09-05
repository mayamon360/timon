$(function(){

    $('[data-toggle="tooltip"]').tooltip(); 

    $("#name, #email, #inputPass").on("input", function(){
        $("#guardar").removeAttr('disabled');
    })

    $("#email").on("input", function(e){
        e.defaultPrevented;
        $("#inputPassU").removeAttr('disabled');
    })

    $("#inputPass").on("input", function(e){
        e.defaultPrevented;
        $("#inputRePass, #inputPassU").removeAttr('disabled');
    })

    cargarDatos();

    /* Cargar los datos en la tabla */
    var dataTable = $('#tablaHistorial').DataTable({
        "responsive" : true,
        "lengthMenu": [[100, 200, 500, -1], [100, 200, 500, "All"]],
        "order": [],
        "ajax" : {
            url:"api/cargarHistorial",
            type:"POST"
        },
        "deferRender": true, 
        "retrieve" : true,
        "processing" : true,
        "columnDefs" : [
        {
            "targets" : [0],
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

    $('#guardar').click(function(e){
        e.defaultPrevented;
        if($("#name").val() == '' && $("#email").val() == '' && $("#inputPass").val() == '' && $("#foto")[0].files.length == 0){
            $("#guardar").attr('disabled','disabled');
        }else{
            guardarCambios();
        }
    })

    function guardarCambios(){
        var form = $("#nuevosDatos")[0];
        var form_data = new FormData(form);
        $.ajax({
            type : "POST",
            url : "api/cambiarDatosCuenta",
            dataType: 'json',
            data : form_data,
            contentType: false,
            processData: false,
            cache: false,
            beforeSend: function(){
                $('#cargarCuenta .datos').removeClass('show').addClass('hidden');
            },
            success : function(json) {
                if(json.status=='success'){
                    $("#nuevosDatos")[0].reset();
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

                    $("#guardar, #inputRePass, #inputPassU").attr('disabled','disabled');
                    $(".fotoUsuario").attr("src", json.urlFoto);
                    $(".nombreUsuario").html(json.nombre);

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
                    });

                    $("#guardar").removeAttr('disabled');
                }
            },
            error : function(xhr, status) {
                alert('Ha ocurrido un problema interno');
            },
            complete: function(){ 
                $("#nombreImagen").html('');
                $('#cargarCuenta .cargando').removeClass('hidden').addClass('show');
                cargarDatos();
                dataTable.ajax.reload( null, false );
            } 
        });
    }

});

function cargarDatos(){
    var metodo = 'cargar cuenta';
    $.ajax({
        type : "POST",
        url : "api/cargarCuenta",
        dataType: 'json',
        data : {metodo:metodo},
        beforeSend: function(){
            $("#cargarCuenta").html('<div class="box box-default">'+
                                        '<div class="box-header">'+
                                                '<h3 class="box-title">Cargando datos</h3>'+
                                        '</div>'+
                                        '<div class="box-body animated infinite flash">'+
                                                'Por favor espere...'+
                                        '</div>'+
                                        '<div class="overlay">'+
                                                '<i class="fa fa-refresh fa-spin"></i>'+
                                        '</div>'+
                                    '</div>');
        },
        success:function(json){
            $("#cargarCuenta").html(json);
        },
        error : function(xhr, status) {
            alert('Ha ocurrido un problema interno');
        } 
    })      
}

$(document).on('click', '#eliminarImagen', function(){
    $("#foto").val(null);
    $("#nombreImagen").html('');
})

$(document).on('change', '.validar', function(){
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

                $("#guardar").attr('disabled','disabled');
                $("#inputRePass, #inputPassU").attr('disabled','disabled');

            }
        },
        error : function(xhr, status) {
            alert('Ha ocurrido un problema interno');
        }
    })
});


$("#foto").change(function(){
    var imagen = this.files[0];
    if(imagen["type"] != 'image/jpeg' && imagen["type"] != 'image/png') {
        $("#foto").val(null);
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
        $("#foto").val(null);
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
        $("#guardar").removeAttr('disabled');
        $("#nombreImagen").html('<small><b>Imagen seleccionada:</b> '+imagen.name+'</small> <button type="button" class="btn btn-sm btn-danger" id="eliminarImagen"><i class="fas fa-trash-alt"></i></button>');
    }
});