var pagina = 1;
realizarBusqueda(pagina);

$("#ordenar_resultados").on('change', function(){
    $("#resultados").html('');
    $("#boton_cargar_mas").removeClass('d-none');
    $(".div_cargando_texto").addClass('d-none');
    pagina = 1;
    realizarBusqueda(pagina);
})

$("#boton_cargar_mas").click(function() {
    pagina++;
    realizarBusqueda(pagina);
})

function realizarBusqueda(pagina){

    var orden = $("#ordenar_resultados").val();
    var consulta = $("#consulta").val(); 

    $.ajax({
        url:"api/realizarBusqueda",
        method:'GET',
        dataType: 'json',
        data : {orden:orden, pagina:pagina, consulta:consulta},
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
                $(".div_cargando_texto").html('No se han encontrado resultados para tu búsqueda.');
            }

            $("#resultados").append(json.html);
        },
        error : function(xhr, status) {
            alert('Ha ocurrido un problema interno');
        },
        complete: function(){ 
            $(".spinner-grow").addClass('d-none');
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