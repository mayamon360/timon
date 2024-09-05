var pagina = 1;
cargarDeseos(pagina);

$("#ordenar_resultados").on('change', function(){
    $("#resultados").html('');
    $("#boton_cargar_mas").removeClass('d-none');
    $(".div_cargando_texto").addClass('d-none');
    pagina = 1;
    cargarDeseos(pagina);
})

$("#boton_cargar_mas").click(function() {
    pagina++;
    cargarDeseos(pagina);
})

function cargarDeseos(pagina){

    var orden = $("#ordenar_resultados").val();
    $.ajax({
        url:"api/cargarDeseos",
        method:'GET',
        dataType: 'json',
        data : {orden:orden, pagina:pagina},
        beforeSend: function(){
            $(".spinner-grow").removeClass('d-none');
        },
        success:function(json){
            $(".coincidencias").html(json.coincidencias);

            if(json.resultados_pagina == 0 || json.ultima_pagina == true){
                $("#boton_cargar_mas").addClass('d-none');
            }
            if(json.total_resultados == 0){
                $(".div_cargando_texto").removeClass('d-none');
                $(".div_cargando_texto").html('<div class="alert alert-inverse fade show" role="alert"><span class="alert-inner--icon text-info"><i class="fas fa-info"></i></span><span class="alert-inner--text"><strong>¡La lista está vacía!</strong> No tienes ningún producto en tu lista de deseos, puedes agregarlos para continuar con tu compra más tarde.</span></div>');
            }

            $("#resultados").append(json.html);
        },
        error : function(xhr, status) {
            alert('Ha ocurrido un problema interno');
        },
        complete: function(){ 
            $(".spinner-grow").addClass('d-none');
            $("html, body").animate({scrollTop: $(".div_cargando").offset().top}, 1000);
            $('[data-toggle="tooltip"]').tooltip();

            $(".eliminar_deseo").on('click', function(event){
                event.preventDefault();
                var id = $(this).attr('id');
                $.ajax({
                    url:"api/eliminarDeseo",
                    method:'GET',
                    dataType: 'json',
                    data : {id:id},
                    success:function(json){
                        if(json.status == 'warning'){
                            swal({
                                title: json.title,
                                text: json.message,
                                icon: 'warning',
                                closeOnClickOutside: false,
                                closeOnEsc: false,
                                buttons: false,
                                timer: 800
                            });
                            setTimeout(function() {
                                window.location = 'autenticacion';
                            }, 800);
                        }else if(json.status == 'success'){

                            $("#deseo"+id+" .eliminar_deseo").tooltip('hide');
                            $("#deseo"+id).fadeOut("fast");
                            setTimeout(function() {
                                location.reload();
                            }, 200);

                        }else if(json.status == 'error'){
                            swal({
                                title: json.title,
                                text: json.message,
                                icon: 'error',
                                closeOnClickOutside: false,
                                closeOnEsc: false,
                                buttons: {
                                    cancel: {
                                        text: 'CERRAR',
                                        value: true,
                                        visible: true,
                                        className: 'btn btn-sm boton_negro',
                                        closeModal: true,
                                    }
                                }
                            })
                        }
                    },
                    error : function(xhr, status) {
                        alert('Ha ocurrido un problema interno');
                    },
                })
            });
        }
    })

}