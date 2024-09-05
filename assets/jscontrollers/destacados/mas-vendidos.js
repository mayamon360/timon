$(function(){
    $("#mostar_resultados").val('25');
})
cargarMasvendidos();

$("#mostar_resultados").on('change', function(){
    $("#resultados").html('');
    cargarMasvendidos();
})

function cargarMasvendidos(pagia){

    var mostrar = $("#mostar_resultados").val();
    $.ajax({
        url:"api/cargarMasvendidos",
        method:'GET',
        dataType: 'json',
        data : {mostrar:mostrar},
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