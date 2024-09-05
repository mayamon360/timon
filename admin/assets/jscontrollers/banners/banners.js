/* __________________________________________________________________________________________________________ */
$(function(){
    /* Cargar los datos en la tabla */
    var dataTable = $('#tablaBanners').DataTable({
        "responsive" : true,
        "order": [],
        "ajax" : {
            url:"api/mostrarBanners",
            type:"POST"
        },
        "deferRender": true, 
        "retrieve" : true,
        "processing" : true,
        "columnDefs" : [
        {
            "targets" : [3, 4, 8],
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

    /* Cambiar el estado de algún banner */
    $(document).on('click', '.estado', function(){
        var id = $(this).attr("key");
        var estado = $(this).attr("value");
        $.ajax({
            url:"api/estadoBanner",
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
    /* /.Cambiar el estado de algún banner */

    /* Cargar datos en modal editar */
    $(document).on('click', '.obtenerBanner', function(){
        var id = $(this).attr("key");
        var metodo = "obtenerBanner";
        $.ajax({
            url:"api/obtenerBanner",
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
        editarBanner();
    });
    function editarBanner() {
        var form = $("#editar_form")[0];
        var form_data = new FormData(form);
        $.ajax({
            type : "POST",
            url : "api/editarBanner",
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
                $("#editar").removeAttr("disabled");
            },
            error : function(xhr, status) {
                alert('Ha ocurrido un problema interno');
            }
        });
    }
    /* Editar categoría */

});
/* __________________________________________________________________________________________________________ */

/* CAMBIO DE INPUT FILE PORTADA modal agregar */
$(document).on('change', '#eImagen', function(){
    var imagen = this.files[0];
    if(imagen["type"] != 'image/jpeg' && imagen["type"] != 'image/png') {
        $("#eImagen").val(null);
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
        $("#eImagen").val(null);
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
            var image = new Image();
            image.src = rutaImagen;
            image.onload = function () {
                $(".ePrevisualizarImagen").attr("src", rutaImagen);
            }
        })
    }
});
/* /.CAMBIO DE INPUT FILE PORTADA modal agregar */