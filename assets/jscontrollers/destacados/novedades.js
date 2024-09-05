$(function(){
    $("#ordenar_resultados").val('novedad');
})
cargarNuevos();

$("#ordenar_resultados").on('change', function(){
    $("#resultados").html('');
    cargarNuevos();
})

function cargarNuevos(){

    var orden = $("#ordenar_resultados").val();
    $.ajax({
        url:"api/cargarNuevos",
        method:'GET',
        dataType: 'json',
        data : {orden:orden},
        beforeSend: function(){
            $(".div_cargando").removeClass('d-none');
        },
        success:function(json){
            $(".coincidencias").html(json.coincidencias);
            $("#resultados").append(json.html);
        },
        error : function(xhr, status) {
            alert('Ha ocurrido un problema interno');
        },
        complete: function(){ 
            
            $(".div_cargando").addClass('d-none');
            $('[data-toggle="tooltip"]').tooltip();
            
            $(".agregar_deseo").on('click', function(e) {
                e.preventDefault();
                var id = $(this).attr("id");
                guardarDeseo(id);
            });

            $(".agregar_carrito").on('click', function(e) {
                e.preventDefault();
                var id = $(this).attr("idLibro");
                agregarCarrito(id);
            });
        }
    })

}
