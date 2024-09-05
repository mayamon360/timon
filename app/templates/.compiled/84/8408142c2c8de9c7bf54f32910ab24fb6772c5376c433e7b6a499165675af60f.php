<?php

/* compra/compra.twig */
class __TwigTemplate_1f9d3be797888b111e7774fa5c0efd9815b555164ab0d49b6ec3ab70428c6af9 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("overall/layout", "compra/compra.twig", 1);
        $this->blocks = array(
            'appTitle' => array($this, 'block_appTitle'),
            'appHeader' => array($this, 'block_appHeader'),
            'appBody' => array($this, 'block_appBody'),
            'appFooter' => array($this, 'block_appFooter'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "overall/layout";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_appTitle($context, array $blocks = array())
    {
        // line 4
        echo "    <title>Mi compra</title>
";
    }

    // line 7
    public function block_appHeader($context, array $blocks = array())
    {
        // line 8
        echo "<style>
    .table-cards tr.bg-white>th:after,
    .table-cards tr.bg-white>td:after {
        border-top: 1px solid var(--borderColor);
        border-bottom: 1px solid var(--borderColor);
    }
    .table-hover tr.bg-white:hover {
        background-color: #fff!important;
    }
    .table-hover tr.bg-white:hover>th,
    .table-hover tr.bg-white:hover>td{
        background-color: #f8f9fa!important;
    }
    .table-cards tr.bg-white.empty>th:after{
        border:none!important;
    }
</style>
";
    }

    // line 26
    public function block_appBody($context, array $blocks = array())
    {
        // line 27
        echo "
    <main class=\"container mt-2 destacados\">

        <div class=\"row px-0 align-items-center\">
            <div class=\"col-8 col-sm-6 col-md-7 col-lg-9\">
                <p class=\"m-0 p-0 text-muted font-weight-bold animated fadeIn\"><span class=\"resultados\"></span></p>
                <h1 class=\"m-0 p-0 animated fadeIn titulo_destacado\"><i class=\"fas fa-shopping-bag\"></i> Mi compra</h1>
\t\t\t\t<div class=\"my-1 animated fadeIn separador separador_color\"></div>
            </div>
            <div class=\"col-4 col-sm-6 col-md-5 col-lg-3 text-right\">
                <button type=\"button\" class=\"btn btn-sm btn-icon d-none boton_negro limpiar_carrito\">
                    <span class=\"btn-inner--text\">Limpiar</span>
                    <span class=\"btn-inner--icon\"><i class=\"fas fa-trash-alt\"></i></span>
                </button>
            </div>
        </div>

        <div class=\"table-responsive\">
            <table class=\"table table-hover table-cards align-items-center tabla_carrito\">
                <thead>
                    <tr>
                        <th scope=\"col\" class=\"font-weight-600 text-muted\" style=\"min-width:30px; width:30px;\"></th>
                        <th scope=\"col\" class=\"font-weight-600 text-muted\" style=\"min-width:300px;\">LIBRO</th>
                        <th scope=\"col\" class=\"text-center font-weight-600 text-muted\" style=\"min-width:140px; width:140px;\">CANTIDAD</th>
                        <th scope=\"col\" class=\"text-right font-weight-600 text-muted\" style=\"min-width:130px; width:130px;\">PRECIO</th>
                        <th scope=\"col\" class=\"text-right font-weight-600 text-muted\" style=\"min-width:130px; width:130px;\">SUBTOTAL</th>
                    </tr>
                </thead>
                <tbody>
                    
                </tbody>
                <tfoot>
                    <tr>
                        <th class=\"text-right text-black\" colspan=\"4\">
                            TOTAL:
                        </th>
                        <th class=\"text-right text-app\">
                            <p class=\"lead p-0 m-0 font-weight-700\">
                                <span class=\"total_carrito\"></span>
                            </p>
                        </th>
                    </tr>
                </tfoot>
            </table>
        </div>

    </main>
    
";
    }

    // line 77
    public function block_appFooter($context, array $blocks = array())
    {
        // line 78
        echo "    <script src=\"assets/plugins/Inputmask-5.x/jquery.inputmask.min.js\"></script>
\t<script>
        \$(\".input_cantidad\").inputmask(\"9{1,2}\",{ 
            \"placeholder\": \"0\",
            \"rightAlign\": false,
            \"oncleared\": function(){ 
                \$(this).val('');  
            }
        });

        function cargarCompra(){
            \$.ajax({
                type : 'GET',
                url : 'api/cargarCompra',
                dataType: 'json',
                success : function(json) {
                    \$('.resultados').html(json.resultados);
                    \$('.tabla_carrito tbody').html(json.content);
                    \$('.total_carrito').html(json.total);
                    \$('.cantidad_carrito').html(json.cantidad);
                    if(json.status == 'empty'){
                        \$('.limpiar_carrito').addClass('d-none');
                        \$('.tabla_carrito').removeClass('table-hover');
                    }else if(json.status == 'full'){
                        \$('.limpiar_carrito').removeClass('d-none');
                        \$('.tabla_carrito').addClass('table-hover');
                    }
                },
                error : function(xhr, status) {
                    swal({
                        title: '¡ERROR INTERNO!',
                        text: 'Para más detalles, contacte al administrador.',
                        icon: 'error',
                        closeOnClickOutside: false,
                        closeOnEsc: false,
                        buttons: {
                            cancel: {
                                text: 'CERRAR',
                                value: null,
                                visible: true,
                                className: 'btn btn-sm boton_negro',
                                closeModal: true,
                            }
                        }
                    })
                },
                complete : function() {
                    \$(\".eliminar_carrito\").on('click', function(e) {
                        e.preventDefault();
                        var id = \$(this).attr(\"idLibro\");
                        elimiarCarrito(id);
                    });
                }
            })
        }
        cargarCompra();

        function elimiarCarrito(id){
            \$.ajax({
                type : 'GET',
                url : 'api/elimiarCarrito',
                dataType: 'json',
                data : {id:id},
                success : function(json) {
                    if(json.status == 'error'){
                        swal({
                            title: json.title,
                            text: json.message,
                            icon: 'error',
                            closeOnClickOutside: false,
                            closeOnEsc: false,
                            buttons: {
                                cancel: {
                                    text: 'CERRAR',
                                    value: null,
                                    visible: true,
                                    className: 'btn btn-sm boton_negro',
                                    closeModal: true,
                                }
                            }
                        })
                    }
                },
                error : function(xhr, status) {
                    swal({
                        title: '¡ERROR INTERNO!',
                        text: 'Para más detalles, contacte al administrador.',
                        icon: 'error',
                        closeOnClickOutside: false,
                        closeOnEsc: false,
                        buttons: {
                            cancel: {
                                text: 'CERRAR',
                                value: null,
                                visible: true,
                                className: 'btn btn-sm boton_negro',
                                closeModal: true,
                            }
                        }
                    })
                },
                complete : function() {
                    cargarCompra();
                }
            })
        }

        function modificarCantidad(cantidad,id){
            if(cantidad == 0 || cantidad == ''){
                cantidad = 1;
                \$('#input_cantidad'+id).val(cantidad);
            }
            \$.ajax({
                type : 'GET',
                url : 'api/modificarCantidad',
                dataType: 'json',
                data : {cantidad:cantidad,id:id},
                success:function(json){
                    if(json.status == 'error'){
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
                    swal({
                        title: '¡ERROR INTERNO!',
                        text: 'Para más detalles, contacte al administrador.',
                        icon: 'error',
                        closeOnClickOutside: false,
                        closeOnEsc: false,
                        buttons: {
                            cancel: {
                                text: 'CERRAR',
                                value: null,
                                visible: true,
                                className: 'btn btn-sm boton_negro',
                                closeModal: true,
                            }
                        }
                    })
                },
                complete : function() {
                    cargarCompra();
                }
            })
        }
        \$(document).on('keypress', '.input_cantidad', function(e){
            e.defaultPrevented;
            var cantidad = \$(this).val();
            var id = \$(this).attr('idLibro');
            if(e.which == 13) {
                modificarCantidad(cantidad,id);
                return false;
            }
        });
        \$(document).on('blur', '.input_cantidad', function(e){
            e.defaultPrevented;
            var cantidad = \$(this).val();
            var id = \$(this).attr('idLibro');
            modificarCantidad(cantidad,id);
        });
        \$(document).on('click', '.quitar_cantidad', function(){
            var id = \$(this).attr('idLibro');
            var cantidad = parseInt(\$('#input_cantidad'+id).val());
            var n_cantidad = cantidad-1;
            if(n_cantidad > 0){
                modificarCantidad(n_cantidad,id);
            }
        });
        \$(document).on('click', '.agregar_cantidad', function(){
            var id = \$(this).attr('idLibro');
            var cantidad = parseInt(\$('#input_cantidad'+id).val());
            var n_cantidad = cantidad+1;
            modificarCantidad(n_cantidad,id);
        });

        \$(\".limpiar_carrito\").on('click', function() {
            \$.ajax({
                type : 'GET',
                url : 'api/limpiarCarrito',
                dataType: 'json',
                error : function(xhr, status) {
                    swal({
                        title: '¡ERROR INTERNO!',
                        text: 'Para más detalles, contacte al administrador.',
                        icon: 'error',
                        closeOnClickOutside: false,
                        closeOnEsc: false,
                        buttons: {
                            cancel: {
                                text: 'CERRAR',
                                value: null,
                                visible: true,
                                className: 'btn btn-sm boton_negro',
                                closeModal: true,
                            }
                        }
                    })
                },
                complete : function() {
                    cargarCompra();
                }
            })
        });

    </script>
";
    }

    public function getTemplateName()
    {
        return "compra/compra.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  121 => 78,  118 => 77,  66 => 27,  63 => 26,  42 => 8,  39 => 7,  34 => 4,  31 => 3,  11 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "compra/compra.twig", "C:\\xampp\\htdocs\\website\\app\\templates\\compra\\compra.twig");
    }
}
