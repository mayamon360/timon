$(document).ready(function() {

    $('.carrusel_libros_editorial, .carrusel_libros_categoria, .carrusel_libros_subcategoria').owlCarousel({
        loop:false,
        rewind: false,
        margin:20,
        responsiveClass:true,
        dots: false,
        navText: [
            '<span class="btn-inner--icon" aria-label="Previous"><i class="fas fa-chevron-left"></i></span> <span class="btn-inner--text" aria-label="Previous"></span>',
            '<span class="btn-inner--text" aria-label="Next"></span> <span class="btn-inner--icon" aria-label="Next"><i class="fas fa-chevron-right"></i></span>'
        ],
        navClass: [
            'btn btn-sm btn-icon boton_negro anterior mx-0',
            'btn btn-sm btn-icon boton_negro siguiente mx-0'
        ],
        responsive:{
            0:{
                items:1,
                nav: false
            },
            320:{
                items:2,
                nav: false,
                stagePadding: 10
            },
            480:{
                items:2,
                nav: false,
                stagePadding: 15,
                margin:20
            },
            640:{
                items:3,
                nav: false,
                margin:10
            },
            800:{
                items:3,
                nav: false,
                margin:10
            },
            1024:{
                items:4,
                nav: true,
                margin:10,
                stagePadding: 1,
            },
            1200:{
                items:5,
                nav: true,
                margin:10,
                stagePadding: 1,
            }
        }
    })

    $(".share").click(function(event){
        event.preventDefault();
        var estado = $(this).attr('compartir');
        compartir(estado);
    });

})

function compartir(estado){
    if(estado == 'd-block'){
        $('.share').attr('compartir', 'd-none');
        $(".compartir").removeClass('zoomOut d-none').addClass("zoomIn");
    }else{
        $('.share').attr('compartir', 'd-block');
        $(".compartir").removeClass('zoomIn d-block').addClass("zoomOut");
    }
}

$(".agregar_deseo").on('click', function(e) {
    e.preventDefault();
    var id = $(this).attr("id");
    guardarDeseo(id);
});