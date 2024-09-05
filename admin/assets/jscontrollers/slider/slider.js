/* AGREGAR SLIDER */
/* Evento que se desencadena la hacer clic en el botón .agregarSlider */
$(".agregarSlider").click(function(){
    var datos = new FormData();
    datos.append('metodo','crearSlider');
	$.ajax({
      	type : "POST",
      	url : "api/crearSlider",
      	dataType: 'json',
        data : datos,
        contentType: false,
        processData: false,
        cache: false,
      	success : function(respuesta) {
        	if(respuesta.status == 'success'){
        		swal({
                    title: respuesta.title,
                    text: respuesta.message,
                    icon: 'info',
                    closeOnClickOutside: false,
                    closeOnEsc: false,
                    buttons: {
                        confirm: {
                            text: "Ok",
                            value: true,
                            visible: true,
                            className: "btn btn-sm btn-primary",
                            closeModal: true,
                        }
                    }
                })
                .then((value) => {

                    if (value) {
      				 	window.location = "slider";
      				}
    			});
        	}
        }
	})
})
/* FIN AGREGAR SLIDER */

/* ORDENAR SLIDER */
/* Evento que se desencadena la arrastrar el item de algún slide para reordenarlo */
var itemSlide = $('.itemSlide');
$('.todo-list').sortable({
	placeholder				: 'sort-highlight',
	handle					: '.handle',
	forcePlaceholderSize	: true,
	zIndex					: 999999,
	stop: function(event){
		for(var i = 0; i < itemSlide.length; i++){
			var datos = new FormData();
			datos.append('idSlider', event.target.children[i].id);
			datos.append('orden', (i+1));
			datos.append('metodo', 'ordenarSlider');
			$.ajax({
				type : "POST",
    	    	url : "api/ordenarSlider",
    	    	dataType: 'json',
                data : datos,
                contentType: false,
                processData: false,
                cache: false
        	})
		}
	}
})
/* FIN ORDENAR SLIDER */

/* CAMBIO NOMBRE SLIDER */
/* Evento que se desencadena al cambiar el nombre */
$(".nombreSlider").on('input', function(){
	var idSlider = $(this).attr("key");
	var nombre = $(this).val();
	$("h4.title"+idSlider+" a").html(nombre);
	$("#btnActualizar"+idSlider).removeAttr("disabled");
})

/* CAMBIO VINCULO SLIDER */
/* Evento que se desencadena al cambiar el vinculo */
$(".vinculoSlider").on('input', function(){

    var idSlider = $(this).attr("key");
    var vinculo = $(this).val();

    if(vinculo != ""){

        if(vinculo != "nulo"){

            $("#vinculo"+idSlider).attr("href", vinculo);
            $("#vinculo"+idSlider).css("cursor", "pointer");

        } else {

            $("#vinculo"+idSlider).removeAttr("href");
            $("#vinculo"+idSlider).css("cursor", "default");

        }
    
    }else{

        $("#vinculo"+idSlider).removeAttr("href");
        $("#vinculo"+idSlider).css("cursor", "default");

    }

    $("#btnActualizar"+idSlider).removeAttr("disabled");  

})

/* CAMBIO TIPO SLIDER */
/* Evento que se desencadena al cambiar de tipo de slider */
$("input[name='tipoSlider']").on('ifChecked', function(event){

  	var idSlider = $(this).attr("key");
  	var tipoSlide = $(this).val();

  	$("#slider"+idSlider+" .carousel .carousel-inner .carousel-item").attr("class", "carousel-item "+tipoSlide);

  	switch(tipoSlide) {

      case 'sliderOpcion1':
        $("#slider"+idSlider+" .carousel .carousel-inner .carousel-item .carousel-caption").show();
        $("#slider"+idSlider+" .carousel .carousel-inner .carousel-item .carousel-img").show();
        $("#slider"+idSlider+" .carousel .carousel-inner .carousel-item .carousel-caption .alineacionTexto").attr("class", "alineacionTexto SlideIzquierdaCentro text-left");
        $("#slider"+idSlider+" .carousel .carousel-inner .carousel-item .carousel-img .alineacionImagen").attr("class", "alineacionImagen SlideDerechaCentroImg");
        $("#slider"+idSlider+" .carousel .carousel-inner .carousel-item .carousel-img .alineacionImagen .imagen").attr("class", "imagen float-right");
        $("#divImagen"+idSlider).removeClass("sr-only").show();
        $("#divTextos"+idSlider).removeClass("sr-only").show();
        break;

      case 'sliderOpcion2':
        $("#slider"+idSlider+" .carousel .carousel-inner .carousel-item .carousel-caption").show();
        $("#slider"+idSlider+" .carousel .carousel-inner .carousel-item .carousel-img").show();
        $("#slider"+idSlider+" .carousel .carousel-inner .carousel-item .carousel-caption .alineacionTexto").attr("class", "alineacionTexto SlideDerechaCentro text-right");
        $("#slider"+idSlider+" .carousel .carousel-inner .carousel-item .carousel-img .alineacionImagen").attr("class", "alineacionImagen SlideIzquierdaCentroImg");
        $("#slider"+idSlider+" .carousel .carousel-inner .carousel-item .carousel-img .alineacionImagen .imagen").attr("class", "imagen float-left");
        $("#divImagen"+idSlider).removeClass("sr-only").show();
        $("#divTextos"+idSlider).removeClass("sr-only").show();
        break;

      case 'sliderOpcion3':
        $("#slider"+idSlider+" .carousel .carousel-inner .carousel-item .carousel-caption").hide();
        $("#slider"+idSlider+" .carousel .carousel-inner .carousel-item .carousel-img").hide();
        $("#divImagen"+idSlider).addClass("sr-only").hide();
        $("#divTextos"+idSlider).addClass("sr-only").hide();
      break;

      case 'sliderOpcion4':
        $("#slider"+idSlider+" .carousel .carousel-inner .carousel-item .carousel-caption").show();
        $("#slider"+idSlider+" .carousel .carousel-inner .carousel-item .carousel-img").show();
        $("#slider"+idSlider+" .carousel .carousel-inner .carousel-item .carousel-caption .alineacionTexto").attr("class", "alineacionTexto SlideCentroTop text-center");
        $("#slider"+idSlider+" .carousel .carousel-inner .carousel-item .carousel-img .alineacionImagen").attr("class", "alineacionImagen SlideCentroBottomImg");
        $("#slider"+idSlider+" .carousel .carousel-inner .carousel-item .carousel-img .alineacionImagen .imagen").attr("class", "imagen float-none");
        $("#divImagen"+idSlider).removeClass("sr-only").show();
        $("#divTextos"+idSlider).removeClass("sr-only").show();
        break;

      case 'sliderOpcion5':
        $("#slider"+idSlider+" .carousel .carousel-inner .carousel-item .carousel-caption").show();
        $("#slider"+idSlider+" .carousel .carousel-inner .carousel-item .carousel-img").show();
        $("#slider"+idSlider+" .carousel .carousel-inner .carousel-item .carousel-caption .alineacionTexto").attr("class", "alineacionTexto SlideDerechaTop text-right");
        $("#slider"+idSlider+" .carousel .carousel-inner .carousel-item .carousel-img .alineacionImagen").attr("class", "alineacionImagen SlideDerechaBottomImg");
        $("#slider"+idSlider+" .carousel .carousel-inner .carousel-item .carousel-img .alineacionImagen .imagen").attr("class", "imagen float-right");
        $("#divImagen"+idSlider).removeClass("sr-only").show();
        $("#divTextos"+idSlider).removeClass("sr-only").show();
        break;

      case 'sliderOpcion6':
        $("#slider"+idSlider+" .carousel .carousel-inner .carousel-item .carousel-caption").show();
        $("#slider"+idSlider+" .carousel .carousel-inner .carousel-item .carousel-img").show();
        $("#slider"+idSlider+" .carousel .carousel-inner .carousel-item .carousel-caption .alineacionTexto").attr("class", "alineacionTexto SlideIzquierdaTop text-left");
        $("#slider"+idSlider+" .carousel .carousel-inner .carousel-item .carousel-img .alineacionImagen").attr("class", "alineacionImagen SlideIzquierdaBottomImg");
        $("#slider"+idSlider+" .carousel .carousel-inner .carousel-item .carousel-img .alineacionImagen .imagen").attr("class", "imagen float-left");
        $("#divImagen"+idSlider).removeClass("sr-only").show();
        $("#divTextos"+idSlider).removeClass("sr-only").show();
        break;

    }

  	$("#btnActualizar"+idSlider).removeAttr("disabled");

})
/* //CAMBIO TIPO SLIDER */

/* CAMBIO FONDO DEL SLIDER */
/* Evento que se desencadena al cambiar intentar cambiar el fondo del slider */
$(".fondo").change(function(){

  var idSlider = $(this).attr("key");

  var imagen = this.files[0];

  if(imagen["type"] != 'image/jpeg' && imagen["type"] != 'image/png') {

      $("#fondoSlider"+idSlider).val(null);

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

        $("#fondoSlider"+idSlider).val(null);

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

              var width = this.width;
              var height = this.height;

              if(width != 1600){

                $("#fondoSlider"+idSlider).val(null);

                swal({
                    title: "¡Opss!",
                    text: "El ancho de la imagen debe ser de 1600px.",
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

              } else if(height != 707){

                $("#fondoSlider"+idSlider).val(null);

                swal({
                    title: "¡Opss!",
                    text: "El alto de la imagen debe ser de 707px.",
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

                $("#fondo"+idSlider).attr("src", rutaImagen);
                $("#previsualizarFondo"+idSlider).attr("src", rutaImagen);
                $("#btnActualizar"+idSlider).removeAttr("disabled");

              }
              
            }

        })

    }

});
/* FIN CAMBIO FONDO DEL SLIDER */

/* CAMBIO IMAGEN ADICIONAL DEL SLIDER */
/* Evento que se desencadena al cambiar intentar cambiar la imagen adicional del slider */
$(".imagen").change(function(){

  var idSlider = $(this).attr("key");

  var imagen = this.files[0];

  $("#anchoSliderDiv"+idSlider).removeClass('hidden').addClass('show');

  if(imagen["type"] != 'image/jpeg' && imagen["type"] != 'image/png') {

      $("#imagenSlider"+idSlider).val(null);

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

        $("#imagenSlider"+idSlider).val(null);

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

        $("#imagen"+idSlider).attr("src", rutaImagen);
        $("#previsualizarImagen"+idSlider).attr("src", rutaImagen);
        $("#btnActualizar"+idSlider).removeAttr("disabled");

      });

    }

});
/* FIN CAMBIO IMAGEN ADICIONAL DEL SLIDER */

/* CAMBIO DE ANCHO DE IMAGEN */

for(var i = 0; i < itemSlide.length; i++) {

  var id = $(itemSlide[i]).attr("key");

  var anchoSlider = new Slider('#anchoSlider'+id, {

    formatter: function(value) {

      $('.anchoSlider').change(function(){

        var idSlider = $(this).attr("key");

        $('#imagen'+idSlider).css('width', value+"px");

        $("#btnActualizar"+idSlider).removeAttr("disabled");

      })

      return value;

    }

  })

}


/* CAMBIO TÍTULO SLIDER */
/* Evento que se desencadena al cambiar el título */
$(".tituloSlider").on('input', function(){

  var idSlider = $(this).attr("key");
  var titulo = $(this).val();

  $("#titulo"+idSlider).html(titulo);
  $("#btnActualizar"+idSlider).removeAttr("disabled");

})

/* CAMBIO SUBTÍTULO SLIDER */
/* Evento que se desencadena al cambiar el subtítulo */
$(".subtituloSlider").on('input', function(){

  var idSlider = $(this).attr("key");
  var subtitulo = $(this).val();

  $("#subtitulo"+idSlider).html(subtitulo);
  $("#btnActualizar"+idSlider).removeAttr("disabled");

})

/* CAMBIO DESCRIPCIÓN SLIDER */
/* Evento que se desencadena al cambiar la descripción */
$(".descSlider").on('input', function(){

  var idSlider = $(this).attr("key");
  var descSlider = $(this).val();

  $("#descripcion"+idSlider).html(descSlider);
  $("#btnActualizar"+idSlider).removeAttr("disabled");

})

/* CAMBIO DE COLORES */
$(function() { 

  for(var i = 0; i < itemSlide.length; i++) {

    var idSlider = $(itemSlide[i]).attr("key");        //Obtenemos ids 

    /* TÍTULOS ____________________________________________________________________________________________________________________*/
    var fondoT = $("#fondoT"+idSlider).val();         //Obtenemos colores de fondo del titulo
    var colorT = $("#colorT"+idSlider).val();         //Obtenemos colores de letra del titulo
    /* Colorpicker fondo */
    $('#fondoMT'+idSlider).colorpicker({              //Habilitamos el colorpicker 
        color : fondoT                                //Agregamos el color base que guardamos arriba
    }).on('changeColor', function(e) {                //Evento al detectar cambio en el colorpicker
        var fondoNT = e.color.toString('rgba');       //Guadamos el nuevo color      
        $("#fondoT"+$(this).attr("key")).val(fondoNT);//Agregamos el valor del color nuevo al input
        $(this).css("color", fondoNT);                //Cambiamos el color del cuadro donde se muestra la previsualización el color

        $("#titulo"+$(this).attr("key")).css("background", fondoNT);
        $("#btnActualizar"+$(this).attr("key")).removeAttr("disabled");
    }); 
    /* Input fondo */
    $('#fondoT'+idSlider).on('change', function(){                                          //Evento al detectar cambio en el input
        $('#fondoMT'+$(this).attr("key")).css("color", $(this).val());                      //Cambiamos el color del cuadro donde se muestra la previsualización el color por el valor del input
        $('#fondoMT'+$(this).attr("key")).colorpicker('setValue', $(this).val());           //Asignamos el valor del input al colorpicker
        $('#fondoMT'+$(this).attr("key")).colorpicker('update');                            //Actualizamos el colorpicker
                                
        if($('#fondoMT'+$(this).attr("key")).colorpicker('getValue') == '#aN' 
          || $('#fondoMT'+$(this).attr("key")).colorpicker('getValue').search('aN') >= 0) { //Si el valor del input no corresponde a un color valido

            fondoT = $('#fondoMT'+$(this).attr("key")).attr("colorBase");

            $('#fondoMT'+$(this).attr("key")).colorpicker('setValue', fondoT);              //Restauramos el valor base
            $('#fondoMT'+$(this).attr("key")).colorpicker('update');                        //Actualizamos el colorpicker nuevamente
            $("#fondoT"+$(this).attr("key")).val(fondoT);

        } else {

          $("#titulo"+$(this).attr("key")).css("background", fondoNT);
          $("#btnActualizar"+$(this).attr("key")).removeAttr("disabled");

        }

    });
    /* Colorpicker color */
    $('#colorMT'+idSlider).colorpicker({              //Habilitamos el colorpicker 
        color : colorT                                //Agregamos el color base que guardamos arriba
    }).on('changeColor', function(e) {                //Evento al detectar cambio en el colorpicker
        var colorNT = e.color.toString('rgba');       //Guadamos el nuevo color      
        $("#colorT"+$(this).attr("key")).val(colorNT);//Agregamos el valor del color nuevo al input
        $(this).css("color", colorNT);                //Cambiamos el color del cuadro donde se muestra la previsualización el color

        $("#titulo"+$(this).attr("key")).css("color", colorNT);
        $("#btnActualizar"+$(this).attr("key")).removeAttr("disabled");
    }); 
    /* Input color */
    $('#colorT'+idSlider).on('change', function(){                                          //Evento al detectar cambio en el input
        $('#colorMT'+$(this).attr("key")).css("color", $(this).val());                      //Cambiamos el color del cuadro donde se muestra la previsualización el color por el valor del input
        $('#colorMT'+$(this).attr("key")).colorpicker('setValue', $(this).val());           //Asignamos el valor del input al colorpicker
        $('#colorMT'+$(this).attr("key")).colorpicker('update');                            //Actualizamos el colorpicker
                                
        if($('#colorMT'+$(this).attr("key")).colorpicker('getValue') == '#aN' 
          || $('#colorMT'+$(this).attr("key")).colorpicker('getValue').search('aN') >= 0) { //Si el valor del input no corresponde a un color valido

            colorT = $('#colorMT'+$(this).attr("key")).attr("colorBase");

            $('#colorMT'+$(this).attr("key")).colorpicker('setValue', colorT);              //Restauramos el valor base
            $('#colorMT'+$(this).attr("key")).colorpicker('update');                        //Actualizamos el colorpicker nuevamente
            $("#colorT"+$(this).attr("key")).val(colorT);

        } else {

          $("#titulo"+$(this).attr("key")).css("color", colorNT);
          $("#btnActualizar"+$(this).attr("key")).removeAttr("disabled");

        }

    });
    /* FIN TÍTULOS */

    /* SUBTÍTULOS _________________________________________________________________________________________________________________*/
    var fondoS = $("#fondoS"+idSlider).val();         //Obtenemos colores de fondo del subtitulo
    var colorS = $("#colorS"+idSlider).val();         //Obtenemos colores de letra del subtitulo
    /* Colorpicker fondo */
    $('#fondoMS'+idSlider).colorpicker({              //Habilitamos el colorpicker 
        color : fondoS                                //Agregamos el color base que guardamos arriba
    }).on('changeColor', function(e) {                //Evento al detectar cambio en el colorpicker
        var fondoNS = e.color.toString('rgba');       //Guadamos el nuevo color      
        $("#fondoS"+$(this).attr("key")).val(fondoNS);//Agregamos el valor del color nuevo al input
        $(this).css("color", fondoNS);                //Cambiamos el color del cuadro donde se muestra la previsualización el color

        $("#subtitulo"+$(this).attr("key")).css("background", fondoNS);
        $("#btnActualizar"+$(this).attr("key")).removeAttr("disabled");
    });
    /* Input fondo */
    $('#fondoS'+idSlider).on('change', function(){                                          //Evento al detectar cambio en el input
        $('#fondoMS'+$(this).attr("key")).css("color", $(this).val());                      //Cambiamos el color del cuadro donde se muestra la previsualización el color por el valor del input
        $('#fondoMS'+$(this).attr("key")).colorpicker('setValue', $(this).val());           //Asignamos el valor del input al colorpicker
        $('#fondoMS'+$(this).attr("key")).colorpicker('update');                            //Actualizamos el colorpicker
                                
        if($('#fondoMS'+$(this).attr("key")).colorpicker('getValue') == '#aN' 
          || $('#fondoMS'+$(this).attr("key")).colorpicker('getValue').search('aN') >= 0) { //Si el valor del input no corresponde a un color valido

            fondoS = $('#fondoMS'+$(this).attr("key")).attr("colorBase");

            $('#fondoMS'+$(this).attr("key")).colorpicker('setValue', fondoS);              //Restauramos el valor base
            $('#fondoMS'+$(this).attr("key")).colorpicker('update');                        //Actualizamos el colorpicker nuevamente
            $("#fondoS"+$(this).attr("key")).val(fondoS);

        } else {

          $("#subtitulo"+$(this).attr("key")).css("background", fondoNS);
          $("#btnActualizar"+$(this).attr("key")).removeAttr("disabled");

        }

    }); 
    /* Colorpicker color */
    $('#colorMS'+idSlider).colorpicker({              //Habilitamos el colorpicker 
        color : colorS                                //Agregamos el color base que guardamos arriba
    }).on('changeColor', function(e) {                //Evento al detectar cambio en el colorpicker
        var colorNS = e.color.toString('rgba');       //Guadamos el nuevo color      
        $("#colorS"+$(this).attr("key")).val(colorNS);//Agregamos el valor del color nuevo al input
        $(this).css("color", colorNS);                //Cambiamos el color del cuadro donde se muestra la previsualización el color

        $("#subtitulo"+$(this).attr("key")).css("color", colorNS);
        $("#btnActualizar"+$(this).attr("key")).removeAttr("disabled");
    });
    /* Input color */
    $('#colorS'+idSlider).on('change', function(){                                          //Evento al detectar cambio en el input
        $('#colorMS'+$(this).attr("key")).css("color", $(this).val());                      //Cambiamos el color del cuadro donde se muestra la previsualización el color por el valor del input
        $('#colorMS'+$(this).attr("key")).colorpicker('setValue', $(this).val());           //Asignamos el valor del input al colorpicker
        $('#colorMS'+$(this).attr("key")).colorpicker('update');                            //Actualizamos el colorpicker
                                
        if($('#colorMS'+$(this).attr("key")).colorpicker('getValue') == '#aN' 
          || $('#colorMS'+$(this).attr("key")).colorpicker('getValue').search('aN') >= 0) { //Si el valor del input no corresponde a un color valido

            colorS = $('#colorMS'+$(this).attr("key")).attr("colorBase");

            $('#colorMS'+$(this).attr("key")).colorpicker('setValue', colorS);              //Restauramos el valor base
            $('#colorMS'+$(this).attr("key")).colorpicker('update');                        //Actualizamos el colorpicker nuevamente
            $("#colorS"+$(this).attr("key")).val(colorS);

        } else {

          $("#subtitulo"+$(this).attr("key")).css("color", colorNS);
          $("#btnActualizar"+$(this).attr("key")).removeAttr("disabled");

        }

    });
    /* FIN SUBTÍTULOS */

    /* SUBTÍTULOS _________________________________________________________________________________________________________________*/
    var fondoD = $("#fondoD"+idSlider).val();         //Obtenemos colores de fondo del descripcion
    var colorD = $("#colorD"+idSlider).val();         //Obtenemos colores de letra del descripcion
    /* Colorpicker fondo */
    $('#fondoMD'+idSlider).colorpicker({              //Habilitamos el colorpicker 
        color : fondoD                                //Agregamos el color base que guardamos arriba
    }).on('changeColor', function(e) {                //Evento al detectar cambio en el colorpicker
        var fondoND = e.color.toString('rgba');       //Guadamos el nuevo color      
        $("#fondoD"+$(this).attr("key")).val(fondoND);//Agregamos el valor del color nuevo al input
        $(this).css("color", fondoND);                //Cambiamos el color del cuadro donde se muestra la previsualización el color

        $("#descripcion"+$(this).attr("key")).css("background", fondoND);
        $("#btnActualizar"+$(this).attr("key")).removeAttr("disabled");
    });
    /* Input fondo */
    $('#fondoD'+idSlider).on('change', function(){                                          //Evento al detectar cambio en el input
        $('#fondoMD'+$(this).attr("key")).css("color", $(this).val());                      //Cambiamos el color del cuadro donde se muestra la previsualización el color por el valor del input
        $('#fondoMD'+$(this).attr("key")).colorpicker('setValue', $(this).val());           //Asignamos el valor del input al colorpicker
        $('#fondoMD'+$(this).attr("key")).colorpicker('update');                            //Actualizamos el colorpicker
                                
        if($('#fondoMD'+$(this).attr("key")).colorpicker('getValue') == '#aN' 
          || $('#fondoMD'+$(this).attr("key")).colorpicker('getValue').search('aN') >= 0) { //Si el valor del input no corresponde a un color valido

            fondoD = $('#fondoMD'+$(this).attr("key")).attr("colorBase");

            $('#fondoMD'+$(this).attr("key")).colorpicker('setValue', fondoD);              //Restauramos el valor base
            $('#fondoMD'+$(this).attr("key")).colorpicker('update');                        //Actualizamos el colorpicker nuevamente
            $("#fondoD"+$(this).attr("key")).val(fondoD);

        } else {

          $("#descripcion"+$(this).attr("key")).css("background", fondoND);
          $("#btnActualizar"+$(this).attr("key")).removeAttr("disabled");

        }

    }); 
    /* Colorpicker color */
    $('#colorMD'+idSlider).colorpicker({              //Habilitamos el colorpicker 
        color : colorD                                //Agregamos el color base que guardamos arriba
    }).on('changeColor', function(e) {                //Evento al detectar cambio en el colorpicker
        var colorND = e.color.toString('rgba');       //Guadamos el nuevo color      
        $("#colorD"+$(this).attr("key")).val(colorND);//Agregamos el valor del color nuevo al input
        $(this).css("color", colorND);                //Cambiamos el color del cuadro donde se muestra la previsualización el color

        $("#descripcion"+$(this).attr("key")).css("color", colorND);
        $("#btnActualizar"+$(this).attr("key")).removeAttr("disabled");
    });
    /* Input color */
    $('#colorD'+idSlider).on('change', function(){                                          //Evento al detectar cambio en el input
        $('#colorMD'+$(this).attr("key")).css("color", $(this).val());                      //Cambiamos el color del cuadro donde se muestra la previsualización el color por el valor del input
        $('#colorMD'+$(this).attr("key")).colorpicker('setValue', $(this).val());           //Asignamos el valor del input al colorpicker
        $('#colorMD'+$(this).attr("key")).colorpicker('update');                            //Actualizamos el colorpicker
                                
        if($('#colorMD'+$(this).attr("key")).colorpicker('getValue') == '#aN' 
          || $('#colorMD'+$(this).attr("key")).colorpicker('getValue').search('aN') >= 0) { //Si el valor del input no corresponde a un color valido

            colorD = $('#colorMD'+$(this).attr("key")).attr("colorBase");

            $('#colorMD'+$(this).attr("key")).colorpicker('setValue', colorD);              //Restauramos el valor base
            $('#colorMD'+$(this).attr("key")).colorpicker('update');                        //Actualizamos el colorpicker nuevamente
            $("#colorD"+$(this).attr("key")).val(colorD);

        } else {

          $("#descripcion"+$(this).attr("key")).css("color", colorND);
          $("#btnActualizar"+$(this).attr("key")).removeAttr("disabled");

        }

    });

  }

});
/* FIN CAMBIO DE COLORES */

/* EVENTO AL HACER CLIC EN BOTÓN ACTUALIZAR */
$('.actualizarSlider').click(function(e){

	e.defaultPrevented;
	idSlider = $(this).attr("idSlider");

	actualizarSlider(idSlider);

})

function actualizarSlider(idSlider) {
  var form = $("#sliderForm"+idSlider)[0];
  var form_data = new FormData(form);

  $.ajax({
      type : "POST",
      url : "api/actualizarSlider",
      dataType: 'json',
      data : form_data,
      contentType: false,
      processData: false,
      cache: false,
      success : function(json) {

      	if(json.status == 'success') {

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

            if(json.url == "nulo"){

              $("#vinculoSlider"+json.id).val('');

            }

            $("#btnActualizar"+json.id).attr("disabled", true);

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

      },
      error : function(xhr, status) {
          alert('Ha ocurrido un problema interno');
      },
      complete: function(json){ 

      	$("ul.todo-list li#itemSlide"+idSlider+" h4 i").fadeOut(); 

      } 
  });

}


/* EVENTO AL HACER CLIC EN BOTÓN ELIMINAR */
$('.eliminarSlider').click(function(e){

  e.defaultPrevented;
  idSlider = $(this).attr("idSlider");

  swal({
    title: "!Atención¡",
    text: "¿Está seguro de eliminar el slider?",
    icon: 'warning',
    closeOnClickOutside: false,
    closeOnEsc: false,
    buttons: {
      confirm: {
        text: "Sí, eliminar",
        value: true,
        visible: true,
        className: "btn btn-sm btn-danger",
        closeModal: true,
      },
      cancel: {
        text: "Cancelar",
        value: null,
        visible: true,
        className: "btn btn-sm btn-default",
        closeModal: true,
      }
    }
    })
    .then((value) => {

      if (value) {
        $.ajax({
          type : "POST",
          url : "api/eliminarSlider",
          dataType: 'json',
          data : {idSlider:idSlider},
          success : function(json) {
            if(json.status == 'success') {

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
            }
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
              });
            }
          },
          error : function(xhr, status) {
              alert('Ha ocurrido un problema interno');
          },
          complete: function(json){ 

            $("#itemSlide"+idSlider).remove();

          } 

        });
        
      }

    });

})