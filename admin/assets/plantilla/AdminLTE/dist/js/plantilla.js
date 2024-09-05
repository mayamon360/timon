$(function () {

  $('[data-toggle="tooltip"]').tooltip();

    // Sidebar menu lateral
    $('.sidebar-menu').tree();

    /* Gráfico circular jQueryKnob */
    $('.knob').knob();

    /* Activar iCheck para inputs radio y checkbox */
    $('.informacionComercialR, .seleccionarRed, .radioRed, .tipoSlider, .iCheck').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
    });

    // Popover
    $('[data-toggle="popover"]').popover();

    // Opciones del menú
    $(document).on('click', '.sidebar-toggle', function () {
        if ($('body').hasClass("sidebar-collapse") && $('body').hasClass("sidebar-open")) {
            $('body').removeClass("sidebar-collapse");
        }
    });

})

var meses = [
  "Enero", "Febrero", "Marzo",
  "Abril", "Mayo", "Junio", "Julio",
  "Agosto", "Septiembre", "Octubre",
  "Noviembre", "Diciembre"
]; 
