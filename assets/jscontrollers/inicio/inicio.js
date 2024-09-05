$(document).ready(function() {

    $(".carousel").swipe( {
        swipeLeft:function(event, direction, distance, duration, fingerCount) {
            $(".carousel").carousel('next');
        },
        swipeRight:function(event, direction, distance, duration, fingerCount) {
            $(".carousel").carousel('prev');
        },
        threshold:0,
        allowPageScroll:"auto"
    });

    $('.carrusel_varios, .carrusel_libros_nuevos, .carrusel_libros_mas_vendidos').owlCarousel({
        loop:false,
        rewind: false,
        margin:20,
        responsiveClass:true,
        dots: false,
        /*autoplayTimeout: 5000,*/
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
                stagePadding: 10,
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
        },
        /*autoplay: true,
        autoplayHoverPause: true*/
    })

})

$(".agregar_deseo").on('click', function(e) {
    e.preventDefault();
    var id = $(this).attr("id");
    guardarDeseo(id);
});