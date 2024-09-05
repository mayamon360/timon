<?php

/* libro/libro.twig */
class __TwigTemplate_6181f4f2b44d0746be86bc74a0d314bf2acd002dce52e33651d5bebbcb229b19 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("overall/layout", "libro/libro.twig", 1);
        $this->blocks = array(
            'appTitle' => array($this, 'block_appTitle'),
            'ogUrl' => array($this, 'block_ogUrl'),
            'ogImage' => array($this, 'block_ogImage'),
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
        echo "    <title>";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["libro"] ?? null), "producto", array()), "html", null, true);
        echo " ";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["libro"] ?? null), "leyenda", array()), "html", null, true);
        echo " ";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["libro"] ?? null), "codigo", array()), "html", null, true);
        echo "</title>
    <meta name=\"title\" content=\"";
        // line 5
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["libro"] ?? null), "producto", array()), "html", null, true);
        echo " ";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["libro"] ?? null), "leyenda", array()), "html", null, true);
        echo ". ";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["libro"] ?? null), "codigo", array()), "html", null, true);
        echo "\">
";
    }

    // line 7
    public function block_ogUrl($context, array $blocks = array())
    {
        // line 8
        echo "    <meta property=\"og:url\" content=\"";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "libro/";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["cabeceras"] ?? null), "ruta", array()), "html", null, true);
        echo "\">
";
    }

    // line 10
    public function block_ogImage($context, array $blocks = array())
    {
        // line 11
        if (((twig_get_attribute($this->env, $this->getSourceContext(), ($context["cabeceras"] ?? null), "portada", array()) == "assets/plantilla/img/cabeceras/default/default.jpg") && (twig_get_attribute($this->env, $this->getSourceContext(), ($context["libro"] ?? null), "imagen", array()) != "assets/plantilla/img/productos/default/default.jpg"))) {
            // line 12
            echo "    <meta property=\"og:image\" content=\"";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["libro"] ?? null), "imagen", array()), "html", null, true);
            echo "\">
";
        } else {
            // line 14
            echo "    <meta property=\"og:image\" content=\"";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["cabeceras"] ?? null), "portada", array()), "html", null, true);
            echo "\">
";
        }
    }

    // line 18
    public function block_appHeader($context, array $blocks = array())
    {
        // line 20
        echo "<link rel=\"stylesheet\" href=\"assets/plantilla/libro.css\">
<script type=\"application/ld+json\">
{
    \"@context\": \"https://schema.org/\",
    \"@type\": \"Product\",
    \"name\": \"";
        // line 25
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["libro"] ?? null), "producto", array()), "html", null, true);
        echo " ";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["libro"] ?? null), "leyenda", array()), "html", null, true);
        echo "\",
    \"image\": \"";
        // line 26
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["libro"] ?? null), "imagen", array()), "html", null, true);
        echo "\",
    \"description\": \"";
        // line 27
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["libro"] ?? null), "descripcion", array()), "html", null, true);
        echo "\",
    \"url\" : \"";
        // line 28
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "libro/";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["libro"] ?? null), "ruta", array()), "html", null, true);
        echo "\",
    \"sku\": \"";
        // line 29
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["libro"] ?? null), "codigo", array()), "html", null, true);
        echo "\",
    \"offers\" : {
        \"@type\" : \"Offer\",
        \"price\" : \"\$";
        // line 32
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["libro"] ?? null), "precio", array()), "html", null, true);
        echo "\",
        \"priceCurrency\": \"MXN\"
    }
}
</script>
";
    }

    // line 39
    public function block_appBody($context, array $blocks = array())
    {
        // line 40
        echo "
    <main class=\"container-fluid\">

        ";
        // line 44
        echo "        <section class=\"container p-0 info_producto\">

            <div class=\"row\">

                ";
        // line 49
        echo "                <div class=\"col-12 my-4 mb-4 mb-sm-4 text-center text-sm-left animated fadeIn enlaces_relacionados\">
                    ";
        // line 50
        echo ($context["categoria_enlace"] ?? null);
        echo "
                    ";
        // line 51
        echo ($context["subcategoria_enlace"] ?? null);
        echo "
                </div>

                ";
        // line 55
        echo "                <div class=\"col-12 col-sm-4 col-md-3 px-5 px-sm-3 d-none d-sm-inline animated fadeIn imagen_libro\">
                    
                    <div>
                        <img src=\"";
        // line 58
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["libro"] ?? null), "imagen", array()), "html", null, true);
        echo "\" width=\"100%\" class=\"z-depth-1\">
                    </div>

                    ";
        // line 62
        echo "                    <div class=\"d-none d-sm-inline d-md-none animated fadeIn precios_acciones_libro movil\">
                        
                        ";
        // line 65
        echo "                        <div class=\"row m-0 mt-3 text-center rounded z-depth-1 bg-light precios\">

                            ";
        // line 67
        echo ($context["precio"] ?? null);
        echo "

                            ";
        // line 69
        echo ($context["disponibilidad"] ?? null);
        echo "

                            ";
        // line 71
        if (twig_test_empty(($context["oferta"] ?? null))) {
            // line 72
            echo "                            ";
            // line 75
            echo "                            ";
        } else {
            // line 76
            echo "                            ";
            // line 79
            echo "                            ";
        }
        // line 80
        echo "                            
                        </div>
                        ";
        // line 83
        echo "
                        ";
        // line 85
        echo "                        <div class=\"row m-0 mt-3 d-flex align-items-center pb-3 acciones\">

                            <div class=\"col-4 pl-3 pl-sm-0 pr-1 text-left\">
                                <div class=\"card-icon-actions-lg\"> 
                                    <span class=\"div_deseo";
        // line 89
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["libro"] ?? null), "id", array()), "html", null, true);
        echo " mr-3\">
                                        ";
        // line 90
        if (($context["is_logged"] ?? null)) {
            // line 91
            echo "                                            ";
            if ((($context["deseo"] ?? null) == "no")) {
                // line 92
                echo "                                                <a href=\"#\" class=\"love agregar_deseo\" id=\"";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["libro"] ?? null), "id", array()), "html", null, true);
                echo "\" data-toggle=\"tooltip\" data-html=\"true\" title=\"<small><b>Añadir a mi lista de deseos</b></small>\"><i class=\"far fa-heart\"></i></a>
                                            ";
            } else {
                // line 94
                echo "                                                <i class=\"fas fa-heart fa-lg text-red animated pulse infinite\"></i>
                                            ";
            }
            // line 96
            echo "                                        ";
        } else {
            // line 97
            echo "                                            <a href=\"";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
            echo "autenticacion\" class=\"love\" id=\"";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["libro"] ?? null), "id", array()), "html", null, true);
            echo "\" data-toggle=\"tooltip\" data-html=\"true\" title=\"<small><b>Añadir a mi lista de deseos</b></small>\"><i class=\"far fa-heart\"></i></a>
                                        ";
        }
        // line 99
        echo "                                    </span>
                                    <!--<a href=\"#\" class=\"share\" idLibro=\"";
        // line 100
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["libro"] ?? null), "id", array()), "html", null, true);
        echo "\" compartir=\"d-block\" data-toggle=\"tooltip\" data-html=\"true\" title=\"<small><b>Compartir</b></small>\"><i class=\"fas fa-share-alt\"></i></a>-->
                                </div>
                            </div>
\t\t\t    
                            <div class=\"col-8 pr-3 pr-sm-0 pl-1 text-right\">
                            ";
        // line 105
        if ((twig_get_attribute($this->env, $this->getSourceContext(), ($context["libro"] ?? null), "stock", array()) > 0)) {
            // line 106
            echo "                                <button type=\"button\" idLibro=\"";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["libro"] ?? null), "id", array()), "html", null, true);
            echo "\" class=\"btn btn-sm btn-icon boton_color agregar_carrito\">
                                    <span class=\"btn-inner--text\">Agregar</span>
                                    <span class=\"btn-inner--icon\"><i class=\"fas fa-shopping-bag\"></i></span>
                                </button>
                            ";
        }
        // line 111
        echo "                            </div>

                            <!--<div class=\"col-12 mt-3 text-center d-none animated-fast compartir\">
                                <button type=\"button\" class=\"btn btn-sm btn-facebook btn-icon-only rounded-circle border-0\">
                                    <span class=\"btn-inner--icon\"><i class=\"fab fa-facebook-f\"></i></span>
                                </button>
                            </div>-->

                        </div> 
                        ";
        // line 121
        echo "                        
                        ";
        // line 125
        echo "                        
                        <div class=\"text-center mt-3 text-muted rounded p-2 border z-depth-1\">
                            <i class=\"fas fa-shipping-fast fa-lg mr-2\"></i> Envío gratis a partir de <strong>\$800.00</strong>
                        </div>

                    </div>
                    ";
        // line 132
        echo "
                </div>

                ";
        // line 136
        echo "                <div class=\"col-12 col-sm-8 col-md-6 pr-3 pr-sm-5 datos_libro\">

                    <div class=\"row\">

                        ";
        // line 141
        echo "                        <div class=\"col-4 pr-0 d-inline d-sm-none animated fadeIn imagen_libro_movil\">
                            <img src=\"";
        // line 142
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["libro"] ?? null), "imagen", array()), "html", null, true);
        echo "\" width=\"100%\" class=\"z-depth-1\">
                        </div>

                        ";
        // line 146
        echo "                        <div class=\"col-8 col-sm-12 datos_generales\">

                            <h1 class=\"font-weight-600 mt-0 mb-0 animated zoomIn titulo_libro\">
                                ";
        // line 149
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["libro"] ?? null), "producto", array()), "html", null, true);
        echo "
                            </h1>
                            <h6 class=\"my-0 animated zoomIn\">
                                ";
        // line 152
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["libro"] ?? null), "leyenda", array()), "html", null, true);
        echo "
                            </h6>

                            <div class=\"my-1 mt-0 animated zoomInLeft separador separador_color\"></div>

                            <p class=\"m-0 mt-1 animated zoomInRight\">

                                <span class=\"pl-0 autor_libro\">
                                    ";
        // line 160
        echo ($context["autores_html"] ?? null);
        echo "
                                </span>
                                <br>
                                <span class=\"pl-0 ml-0 editorial_libro\">
                                    <span class=\"mr-1\">
                                        SELLO: 
                                    </span> 
                                    <br>
                                    <a class=\"text-truncate pixelEditorial\" href=\"";
        // line 168
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "editorial/";
        echo twig_escape_filter($this->env, ($context["ruta_editorial"] ?? null), "html", null, true);
        echo "\">
                                        ";
        // line 169
        if (twig_test_empty(($context["editorial"] ?? null))) {
            // line 170
            echo "                                            SIN SELLO
                                        ";
        } else {
            // line 172
            echo "                                            ";
            echo twig_escape_filter($this->env, ($context["editorial"] ?? null), "html", null, true);
            echo "
                                        ";
        }
        // line 174
        echo "                                    </a>
                                </span>

                            </p>

                            <div class=\"mt-3 animated zoomInUp\">
                                ";
        // line 180
        echo ($context["oferta"] ?? null);
        echo "
                            </div>

                        </div>
                        ";
        // line 185
        echo "
                    </div>
                    ";
        // line 188
        echo "
                    ";
        // line 190
        echo "                    <div class=\"mt-3 mt-sm-5 text-justify animated fadeIn sinopsis\">
                        <h6>SINOPSIS:</h6>
                        <p class=\"text-justify\">
                            ";
        // line 193
        echo ($context["descripcion"] ?? null);
        echo "
                        </p>
                    </div>
                    ";
        // line 197
        echo "
                    ";
        // line 199
        echo "                    <div class=\"table-responsive animated fadeIn ficha_tecnica\">
                        <table class=\"table table-cards align-items-center text-center\">
                            <thead>
                                <tr>
                                    <th scope=\"col\" class=\"py-1 px-2 detalle\" style=\"min-width:30px;\">ID</th>
                                    <th scope=\"col\" class=\"py-1 px-2 detalle\" style=\"min-width:150px;\">ISBN-CÓDIGO</th>
                                    ";
        // line 205
        echo ($context["tr_detalles"] ?? null);
        echo "
                                </tr>
                            </thead>
                            <tbody>
                                <tr class=\"bg-white\">
                                    <th scope=\"row\" class=\"py-1 px-2 font-weight-normal\">";
        // line 210
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["libro"] ?? null), "id", array()), "html", null, true);
        echo "</th>
                                    <td scope=\"row\" class=\"py-1 px-2\">";
        // line 211
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["libro"] ?? null), "codigo", array()), "html", null, true);
        echo "</td>
                                    ";
        // line 212
        echo ($context["td_detalles"] ?? null);
        echo "
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    ";
        // line 218
        echo "
                </div>
                ";
        // line 221
        echo "
                ";
        // line 223
        echo "                <div class=\"col-12 col-sm-3 offset-sm-0 d-sm-none d-md-inline pt-3 pt-sm-0 animated fadeIn precios_acciones_libro\">
                    
                    ";
        // line 226
        echo "                    <div class=\"row text-center row rounded z-depth-1 bg-light mx-0 precios\">

                        ";
        // line 228
        echo ($context["precio"] ?? null);
        echo "

                        ";
        // line 230
        echo ($context["disponibilidad"] ?? null);
        echo "

                        ";
        // line 232
        if (twig_test_empty(($context["oferta"] ?? null))) {
            // line 233
            echo "                        ";
            // line 236
            echo "                        ";
        } else {
            // line 237
            echo "                        ";
            // line 240
            echo "                        ";
        }
        // line 241
        echo "                        
                    </div>
                    ";
        // line 244
        echo "
                    ";
        // line 246
        echo "                    <div class=\"row mt-3 d-flex align-items-center pb-3 mx-0 acciones\">

                        <div class=\"col-4 pl-3 pl-sm-0 pr-1 text-left\">
                            <div class=\"card-icon-actions-lg\"> 
                                <span class=\"div_deseo";
        // line 250
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["libro"] ?? null), "id", array()), "html", null, true);
        echo " mr-3\">
                                    ";
        // line 251
        if (($context["is_logged"] ?? null)) {
            // line 252
            echo "                                        ";
            if ((($context["deseo"] ?? null) == "no")) {
                // line 253
                echo "                                            <a href=\"#\" class=\"love agregar_deseo\" id=\"";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["libro"] ?? null), "id", array()), "html", null, true);
                echo "\" data-toggle=\"tooltip\" data-html=\"true\" title=\"<small><b>Añadir a mi lista de deseos</b></small>\"><i class=\"far fa-heart\"></i></a>
                                        ";
            } else {
                // line 255
                echo "                                            <i class=\"fas fa-heart fa-lg text-red animated pulse infinite\"></i>
                                        ";
            }
            // line 257
            echo "                                    ";
        } else {
            // line 258
            echo "                                        <a href=\"";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
            echo "autenticacion\" class=\"love\" id=\"";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["libro"] ?? null), "id", array()), "html", null, true);
            echo "\" data-toggle=\"tooltip\" data-html=\"true\" title=\"<small><b>Añadir a mi lista de deseos</b></small>\"><i class=\"far fa-heart\"></i></a>
                                    ";
        }
        // line 260
        echo "                                </span>
                                <!--<a href=\"#\" class=\"share\" idLibro=\"";
        // line 261
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["libro"] ?? null), "id", array()), "html", null, true);
        echo "\" compartir=\"d-block\" data-toggle=\"tooltip\" data-html=\"true\" title=\"<small><b>Compartir</b></small>\"><i class=\"fas fa-share-alt\"></i></a>-->
                            </div>
                        </div>

                        <div class=\"col-8 pr-3 pr-sm-0 pl-1 text-right\">
                        ";
        // line 266
        if ((twig_get_attribute($this->env, $this->getSourceContext(), ($context["libro"] ?? null), "stock", array()) > 0)) {
            // line 267
            echo "                            <button type=\"button\" idLibro=\"";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["libro"] ?? null), "id", array()), "html", null, true);
            echo "\" class=\"btn btn-sm btn-icon boton_color agregar_carrito\">
                                <span class=\"btn-inner--text\">Agregar</span>
                                <span class=\"btn-inner--icon\"><i class=\"fas fa-shopping-bag\"></i></span>
                            </button>
                        ";
        }
        // line 272
        echo "                        </div>

                        <!--<div class=\"col-12 mt-3 text-center d-none animated-fast compartir\">
                            <button type=\"button\" class=\"btn btn-sm btn-facebook btn-icon-only rounded-circle border-0\">
                                <span class=\"btn-inner--icon\"><i class=\"fab fa-facebook-f\"></i></span>
                            </button>
                        </div>-->

                    </div> 
                    ";
        // line 282
        echo "                    
                    <div class=\"text-center mt-3 text-muted rounded p-2 border z-depth-1\">
                        <i class=\"fas fa-shipping-fast fa-lg mr-2\"></i> Envío gratis a partir de <strong>\$800.00</strong>
                    </div>

                </div>
                ";
        // line 289
        echo "
            </div>
            ";
        // line 292
        echo "            
        </section>
        ";
        // line 295
        echo "
        
        <div class=\"container p-0 mt-5\">

            <ul class=\"nav nav-pills nav-fill flex-column flex-sm-row\" id=\"myTab\" role=\"tablist\">
                <li class=\"nav-item mb-1 mb-sm-0\">
                    <a class=\"nav-link py-2 m-0 active\" id=\"libros_subcategoria-tab\" data-toggle=\"tab\" href=\"#libros_subcategoria\" role=\"tab\" aria-controls=\"libros_subcategoria\" aria-selected=\"true\">
                        + de ";
        // line 302
        echo twig_escape_filter($this->env, ($context["subcategoria"] ?? null), "html", null, true);
        echo "
                    </a>
                </li>
                <li class=\"nav-item mb-1 mb-sm-0\">
                    <a class=\"nav-link py-2 m-0\" id=\"libros_categoria-tab\" data-toggle=\"tab\" href=\"#libros_categoria\" role=\"tab\" aria-controls=\"libros_categoria\" aria-selected=\"false\">
                        + de ";
        // line 307
        echo twig_escape_filter($this->env, ($context["categoria"] ?? null), "html", null, true);
        echo "
                    </a>
                </li>
                <li class=\"nav-item mb-1 mb-sm-0\">
                    <a class=\"nav-link py-2 m-0\" id=\"libros_editorial-tab\" data-toggle=\"tab\" href=\"#libros_editorial\" role=\"tab\" aria-controls=\"libros_editorial\" aria-selected=\"false\">
                        ";
        // line 312
        if ((($context["editorial"] ?? null) != "Sin editorial")) {
            // line 313
            echo "                            + de ";
            echo twig_escape_filter($this->env, ($context["editorial"] ?? null), "html", null, true);
            echo "
                        ";
        } else {
            // line 315
            echo "                            Más sin sello
                        ";
        }
        // line 317
        echo "                    </a>
                </li>
            </ul>

            <div class=\"tab-content p-2 pt-5 border-right border-left border-bottom rounded-bottom\" id=\"myTabContent\">
                <div class=\"tab-pane fade show active\" id=\"libros_subcategoria\" role=\"tabpanel\" aria-labelledby=\"libros_subcategoria-tab\">
                    ";
        // line 323
        if (twig_test_empty(($context["libros_subcategoria"] ?? null))) {
            // line 324
            echo "                        <p class=\"text-center mb-5 text-muted\">No hay más libros asociados a ";
            echo twig_escape_filter($this->env, ($context["subcategoria"] ?? null), "html", null, true);
            echo ".</p>
                    ";
        } else {
            // line 326
            echo "                        ";
            echo ($context["libros_subcategoria"] ?? null);
            echo "
                    ";
        }
        // line 328
        echo "                </div>
                <div class=\"tab-pane fade\" id=\"libros_categoria\" role=\"tabpanel\" aria-labelledby=\"libros_categoria-tab\">
                    ";
        // line 330
        if (twig_test_empty(($context["libros_categoria"] ?? null))) {
            // line 331
            echo "                        <p class=\"text-center mb-5 text-muted\">No hay más libros asociados a ";
            echo twig_escape_filter($this->env, ($context["categoria"] ?? null), "html", null, true);
            echo ".</p>
                    ";
        } else {
            // line 333
            echo "                        ";
            echo ($context["libros_categoria"] ?? null);
            echo "
                    ";
        }
        // line 335
        echo "                </div>
                <div class=\"tab-pane fade\" id=\"libros_editorial\" role=\"tabpanel\" aria-labelledby=\"libros_editorial-tab\">
                    ";
        // line 337
        if (twig_test_empty(($context["libros_editorial"] ?? null))) {
            // line 338
            echo "                        <p class=\"text-center mb-5 text-muted\">No hay más libros asociados a la editorial ";
            echo twig_escape_filter($this->env, ($context["editorial"] ?? null), "html", null, true);
            echo ".</p>
                    ";
        } else {
            // line 340
            echo "                        ";
            echo ($context["libros_editorial"] ?? null);
            echo "
                    ";
        }
        // line 342
        echo "                </div>
            </div>
        </div>


    </main>
    
";
    }

    // line 351
    public function block_appFooter($context, array $blocks = array())
    {
        // line 352
        echo "\t<script src=\"assets/jscontrollers/libro/libro.js\"></script>
";
    }

    public function getTemplateName()
    {
        return "libro/libro.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  669 => 352,  666 => 351,  655 => 342,  649 => 340,  643 => 338,  641 => 337,  637 => 335,  631 => 333,  625 => 331,  623 => 330,  619 => 328,  613 => 326,  607 => 324,  605 => 323,  597 => 317,  593 => 315,  587 => 313,  585 => 312,  577 => 307,  569 => 302,  560 => 295,  556 => 292,  552 => 289,  544 => 282,  533 => 272,  524 => 267,  522 => 266,  514 => 261,  511 => 260,  503 => 258,  500 => 257,  496 => 255,  490 => 253,  487 => 252,  485 => 251,  481 => 250,  475 => 246,  472 => 244,  468 => 241,  465 => 240,  463 => 237,  460 => 236,  458 => 233,  456 => 232,  451 => 230,  446 => 228,  442 => 226,  438 => 223,  435 => 221,  431 => 218,  423 => 212,  419 => 211,  415 => 210,  407 => 205,  399 => 199,  396 => 197,  390 => 193,  385 => 190,  382 => 188,  378 => 185,  371 => 180,  363 => 174,  357 => 172,  353 => 170,  351 => 169,  345 => 168,  334 => 160,  323 => 152,  317 => 149,  312 => 146,  305 => 142,  302 => 141,  296 => 136,  291 => 132,  283 => 125,  280 => 121,  269 => 111,  260 => 106,  258 => 105,  250 => 100,  247 => 99,  239 => 97,  236 => 96,  232 => 94,  226 => 92,  223 => 91,  221 => 90,  217 => 89,  211 => 85,  208 => 83,  204 => 80,  201 => 79,  199 => 76,  196 => 75,  194 => 72,  192 => 71,  187 => 69,  182 => 67,  178 => 65,  174 => 62,  167 => 58,  162 => 55,  156 => 51,  152 => 50,  149 => 49,  143 => 44,  138 => 40,  135 => 39,  125 => 32,  119 => 29,  113 => 28,  109 => 27,  104 => 26,  98 => 25,  91 => 20,  88 => 18,  79 => 14,  72 => 12,  70 => 11,  67 => 10,  58 => 8,  55 => 7,  45 => 5,  36 => 4,  33 => 3,  11 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("{% extends 'overall/layout' %}

{% block appTitle %}
    <title>{{libro.producto}} {{libro.leyenda}} {{libro.codigo}}</title>
    <meta name=\"title\" content=\"{{libro.producto}} {{libro.leyenda}}. {{libro.codigo}}\">
{% endblock %}
{% block ogUrl %}
    <meta property=\"og:url\" content=\"{{config.build.url}}libro/{{cabeceras.ruta}}\">
{% endblock %}
{% block ogImage %}
{% if cabeceras.portada == 'assets/plantilla/img/cabeceras/default/default.jpg' and libro.imagen != 'assets/plantilla/img/productos/default/default.jpg' %}
    <meta property=\"og:image\" content=\"{{config.build.url}}{{libro.imagen}}\">
{% else %}
    <meta property=\"og:image\" content=\"{{config.build.url}}{{cabeceras.portada}}\">
{% endif %}
{% endblock %}

{% block appHeader %}
{# Estilos personalizados para la página #}
<link rel=\"stylesheet\" href=\"assets/plantilla/libro.css\">
<script type=\"application/ld+json\">
{
    \"@context\": \"https://schema.org/\",
    \"@type\": \"Product\",
    \"name\": \"{{libro.producto}} {{libro.leyenda}}\",
    \"image\": \"{{config.build.url}}{{libro.imagen}}\",
    \"description\": \"{{libro.descripcion}}\",
    \"url\" : \"{{config.build.url}}libro/{{libro.ruta}}\",
    \"sku\": \"{{libro.codigo}}\",
    \"offers\" : {
        \"@type\" : \"Offer\",
        \"price\" : \"\${{libro.precio}}\",
        \"priceCurrency\": \"MXN\"
    }
}
</script>
{% endblock %}

{% block appBody %}

    <main class=\"container-fluid\">

        {# .info_producto #}
        <section class=\"container p-0 info_producto\">

            <div class=\"row\">

                {# .enlaces_relacionados #}
                <div class=\"col-12 my-4 mb-4 mb-sm-4 text-center text-sm-left animated fadeIn enlaces_relacionados\">
                    {{categoria_enlace|raw}}
                    {{subcategoria_enlace|raw}}
                </div>

                {# .imagen_libro #}
                <div class=\"col-12 col-sm-4 col-md-3 px-5 px-sm-3 d-none d-sm-inline animated fadeIn imagen_libro\">
                    
                    <div>
                        <img src=\"{{config.build.url}}{{libro.imagen}}\" width=\"100%\" class=\"z-depth-1\">
                    </div>

                    {# .precios_acciones_libro.movil #}
                    <div class=\"d-none d-sm-inline d-md-none animated fadeIn precios_acciones_libro movil\">
                        
                        {# .precios #}
                        <div class=\"row m-0 mt-3 text-center rounded z-depth-1 bg-light precios\">

                            {{precio|raw}}

                            {{disponibilidad|raw}}

                            {% if oferta is empty %}
                            {#<div class=\"col-12 bg-light rounded-bottom py-2 border-top puntos_libro\">
                                {{puntos}} puntos
                            </div>#}
                            {% else %}
                            {#<div class=\"col-12 bg-light rounded-bottom py-2 border-top puntos_libro\">
                                0 puntos
                            </div>#}
                            {% endif %}
                            
                        </div>
                        {# /.precios #}

                        {# .acciones #}
                        <div class=\"row m-0 mt-3 d-flex align-items-center pb-3 acciones\">

                            <div class=\"col-4 pl-3 pl-sm-0 pr-1 text-left\">
                                <div class=\"card-icon-actions-lg\"> 
                                    <span class=\"div_deseo{{libro.id}} mr-3\">
                                        {% if is_logged %}
                                            {% if deseo == 'no' %}
                                                <a href=\"#\" class=\"love agregar_deseo\" id=\"{{libro.id}}\" data-toggle=\"tooltip\" data-html=\"true\" title=\"<small><b>Añadir a mi lista de deseos</b></small>\"><i class=\"far fa-heart\"></i></a>
                                            {% else %}
                                                <i class=\"fas fa-heart fa-lg text-red animated pulse infinite\"></i>
                                            {% endif %}
                                        {% else %}
                                            <a href=\"{{config.build.url}}autenticacion\" class=\"love\" id=\"{{libro.id}}\" data-toggle=\"tooltip\" data-html=\"true\" title=\"<small><b>Añadir a mi lista de deseos</b></small>\"><i class=\"far fa-heart\"></i></a>
                                        {% endif %}
                                    </span>
                                    <!--<a href=\"#\" class=\"share\" idLibro=\"{{libro.id}}\" compartir=\"d-block\" data-toggle=\"tooltip\" data-html=\"true\" title=\"<small><b>Compartir</b></small>\"><i class=\"fas fa-share-alt\"></i></a>-->
                                </div>
                            </div>
\t\t\t    
                            <div class=\"col-8 pr-3 pr-sm-0 pl-1 text-right\">
                            {% if libro.stock > 0 %}
                                <button type=\"button\" idLibro=\"{{libro.id}}\" class=\"btn btn-sm btn-icon boton_color agregar_carrito\">
                                    <span class=\"btn-inner--text\">Agregar</span>
                                    <span class=\"btn-inner--icon\"><i class=\"fas fa-shopping-bag\"></i></span>
                                </button>
                            {% endif %}
                            </div>

                            <!--<div class=\"col-12 mt-3 text-center d-none animated-fast compartir\">
                                <button type=\"button\" class=\"btn btn-sm btn-facebook btn-icon-only rounded-circle border-0\">
                                    <span class=\"btn-inner--icon\"><i class=\"fab fa-facebook-f\"></i></span>
                                </button>
                            </div>-->

                        </div> 
                        {# /.acciones #}
                        
                        {#<div class=\"row p-0 m-0 mt-3 text-center\">
                            <img src=\"{{config.build.url}}assets/plantilla/img/general/envios.svg\" class=\"w-100\">
                        </div>#}
                        
                        <div class=\"text-center mt-3 text-muted rounded p-2 border z-depth-1\">
                            <i class=\"fas fa-shipping-fast fa-lg mr-2\"></i> Envío gratis a partir de <strong>\$800.00</strong>
                        </div>

                    </div>
                    {# /.precios_acciones_libro.movil #}

                </div>

                {# .datos_libro #}
                <div class=\"col-12 col-sm-8 col-md-6 pr-3 pr-sm-5 datos_libro\">

                    <div class=\"row\">

                        {# .imagen_libro_movil #}
                        <div class=\"col-4 pr-0 d-inline d-sm-none animated fadeIn imagen_libro_movil\">
                            <img src=\"{{config.build.url}}{{libro.imagen}}\" width=\"100%\" class=\"z-depth-1\">
                        </div>

                        {# .datos_generales #}
                        <div class=\"col-8 col-sm-12 datos_generales\">

                            <h1 class=\"font-weight-600 mt-0 mb-0 animated zoomIn titulo_libro\">
                                {{ libro.producto }}
                            </h1>
                            <h6 class=\"my-0 animated zoomIn\">
                                {{ libro.leyenda }}
                            </h6>

                            <div class=\"my-1 mt-0 animated zoomInLeft separador separador_color\"></div>

                            <p class=\"m-0 mt-1 animated zoomInRight\">

                                <span class=\"pl-0 autor_libro\">
                                    {{ autores_html|raw }}
                                </span>
                                <br>
                                <span class=\"pl-0 ml-0 editorial_libro\">
                                    <span class=\"mr-1\">
                                        SELLO: 
                                    </span> 
                                    <br>
                                    <a class=\"text-truncate pixelEditorial\" href=\"{{config.build.url}}editorial/{{ruta_editorial}}\">
                                        {% if editorial is empty %}
                                            SIN SELLO
                                        {% else %}
                                            {{ editorial }}
                                        {% endif %}
                                    </a>
                                </span>

                            </p>

                            <div class=\"mt-3 animated zoomInUp\">
                                {{oferta|raw}}
                            </div>

                        </div>
                        {# /.datos_generales #}

                    </div>
                    {# /.row #}

                    {# .sinopsis #}
                    <div class=\"mt-3 mt-sm-5 text-justify animated fadeIn sinopsis\">
                        <h6>SINOPSIS:</h6>
                        <p class=\"text-justify\">
                            {{ descripcion|raw }}
                        </p>
                    </div>
                    {# /.sinopsis #}

                    {# .ficha_tecnica #}
                    <div class=\"table-responsive animated fadeIn ficha_tecnica\">
                        <table class=\"table table-cards align-items-center text-center\">
                            <thead>
                                <tr>
                                    <th scope=\"col\" class=\"py-1 px-2 detalle\" style=\"min-width:30px;\">ID</th>
                                    <th scope=\"col\" class=\"py-1 px-2 detalle\" style=\"min-width:150px;\">ISBN-CÓDIGO</th>
                                    {{tr_detalles|raw}}
                                </tr>
                            </thead>
                            <tbody>
                                <tr class=\"bg-white\">
                                    <th scope=\"row\" class=\"py-1 px-2 font-weight-normal\">{{libro.id}}</th>
                                    <td scope=\"row\" class=\"py-1 px-2\">{{libro.codigo}}</td>
                                    {{td_detalles|raw}}
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    {# /.ficha_tecnica #}

                </div>
                {# /.datos_libro #}

                {# .precios_acciones_libro #}
                <div class=\"col-12 col-sm-3 offset-sm-0 d-sm-none d-md-inline pt-3 pt-sm-0 animated fadeIn precios_acciones_libro\">
                    
                    {# .precios #}
                    <div class=\"row text-center row rounded z-depth-1 bg-light mx-0 precios\">

                        {{precio|raw}}

                        {{disponibilidad|raw}}

                        {% if oferta is empty %}
                        {#<div class=\"col-12 bg-light rounded-bottom py-2 border-top puntos_libro\">
                            {{puntos}} puntos
                        </div>#}
                        {% else %}
                        {#<div class=\"col-12 bg-light rounded-bottom py-2 border-top puntos_libro\">
                            0 puntos
                        </div>#}
                        {% endif %}
                        
                    </div>
                    {# /.precios #}

                    {# .acciones #}
                    <div class=\"row mt-3 d-flex align-items-center pb-3 mx-0 acciones\">

                        <div class=\"col-4 pl-3 pl-sm-0 pr-1 text-left\">
                            <div class=\"card-icon-actions-lg\"> 
                                <span class=\"div_deseo{{libro.id}} mr-3\">
                                    {% if is_logged %}
                                        {% if deseo == 'no' %}
                                            <a href=\"#\" class=\"love agregar_deseo\" id=\"{{libro.id}}\" data-toggle=\"tooltip\" data-html=\"true\" title=\"<small><b>Añadir a mi lista de deseos</b></small>\"><i class=\"far fa-heart\"></i></a>
                                        {% else %}
                                            <i class=\"fas fa-heart fa-lg text-red animated pulse infinite\"></i>
                                        {% endif %}
                                    {% else %}
                                        <a href=\"{{config.build.url}}autenticacion\" class=\"love\" id=\"{{libro.id}}\" data-toggle=\"tooltip\" data-html=\"true\" title=\"<small><b>Añadir a mi lista de deseos</b></small>\"><i class=\"far fa-heart\"></i></a>
                                    {% endif %}
                                </span>
                                <!--<a href=\"#\" class=\"share\" idLibro=\"{{libro.id}}\" compartir=\"d-block\" data-toggle=\"tooltip\" data-html=\"true\" title=\"<small><b>Compartir</b></small>\"><i class=\"fas fa-share-alt\"></i></a>-->
                            </div>
                        </div>

                        <div class=\"col-8 pr-3 pr-sm-0 pl-1 text-right\">
                        {% if libro.stock > 0 %}
                            <button type=\"button\" idLibro=\"{{libro.id}}\" class=\"btn btn-sm btn-icon boton_color agregar_carrito\">
                                <span class=\"btn-inner--text\">Agregar</span>
                                <span class=\"btn-inner--icon\"><i class=\"fas fa-shopping-bag\"></i></span>
                            </button>
                        {% endif %}
                        </div>

                        <!--<div class=\"col-12 mt-3 text-center d-none animated-fast compartir\">
                            <button type=\"button\" class=\"btn btn-sm btn-facebook btn-icon-only rounded-circle border-0\">
                                <span class=\"btn-inner--icon\"><i class=\"fab fa-facebook-f\"></i></span>
                            </button>
                        </div>-->

                    </div> 
                    {# /.acciones #}
                    
                    <div class=\"text-center mt-3 text-muted rounded p-2 border z-depth-1\">
                        <i class=\"fas fa-shipping-fast fa-lg mr-2\"></i> Envío gratis a partir de <strong>\$800.00</strong>
                    </div>

                </div>
                {# /.precios_acciones_libro #}

            </div>
            {# /.row #}
            
        </section>
        {# /.info_producto #}

        
        <div class=\"container p-0 mt-5\">

            <ul class=\"nav nav-pills nav-fill flex-column flex-sm-row\" id=\"myTab\" role=\"tablist\">
                <li class=\"nav-item mb-1 mb-sm-0\">
                    <a class=\"nav-link py-2 m-0 active\" id=\"libros_subcategoria-tab\" data-toggle=\"tab\" href=\"#libros_subcategoria\" role=\"tab\" aria-controls=\"libros_subcategoria\" aria-selected=\"true\">
                        + de {{subcategoria}}
                    </a>
                </li>
                <li class=\"nav-item mb-1 mb-sm-0\">
                    <a class=\"nav-link py-2 m-0\" id=\"libros_categoria-tab\" data-toggle=\"tab\" href=\"#libros_categoria\" role=\"tab\" aria-controls=\"libros_categoria\" aria-selected=\"false\">
                        + de {{categoria}}
                    </a>
                </li>
                <li class=\"nav-item mb-1 mb-sm-0\">
                    <a class=\"nav-link py-2 m-0\" id=\"libros_editorial-tab\" data-toggle=\"tab\" href=\"#libros_editorial\" role=\"tab\" aria-controls=\"libros_editorial\" aria-selected=\"false\">
                        {% if editorial != 'Sin editorial' %}
                            + de {{editorial}}
                        {% else %}
                            Más sin sello
                        {% endif %}
                    </a>
                </li>
            </ul>

            <div class=\"tab-content p-2 pt-5 border-right border-left border-bottom rounded-bottom\" id=\"myTabContent\">
                <div class=\"tab-pane fade show active\" id=\"libros_subcategoria\" role=\"tabpanel\" aria-labelledby=\"libros_subcategoria-tab\">
                    {% if libros_subcategoria is empty %}
                        <p class=\"text-center mb-5 text-muted\">No hay más libros asociados a {{subcategoria}}.</p>
                    {% else %}
                        {{libros_subcategoria|raw}}
                    {% endif %}
                </div>
                <div class=\"tab-pane fade\" id=\"libros_categoria\" role=\"tabpanel\" aria-labelledby=\"libros_categoria-tab\">
                    {% if libros_categoria is empty %}
                        <p class=\"text-center mb-5 text-muted\">No hay más libros asociados a {{categoria}}.</p>
                    {% else %}
                        {{libros_categoria|raw}}
                    {% endif %}
                </div>
                <div class=\"tab-pane fade\" id=\"libros_editorial\" role=\"tabpanel\" aria-labelledby=\"libros_editorial-tab\">
                    {% if libros_editorial is empty %}
                        <p class=\"text-center mb-5 text-muted\">No hay más libros asociados a la editorial {{editorial}}.</p>
                    {% else %}
                        {{libros_editorial|raw}}
                    {% endif %}
                </div>
            </div>
        </div>


    </main>
    
{% endblock %}

{% block appFooter %}
\t<script src=\"assets/jscontrollers/libro/libro.js\"></script>
{% endblock %}", "libro/libro.twig", "/home4/eltimonl/public_html/app/templates/libro/libro.twig");
    }
}
