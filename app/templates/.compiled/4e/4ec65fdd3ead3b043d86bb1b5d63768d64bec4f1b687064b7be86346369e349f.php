<?php

/* libro/libro.twig */
class __TwigTemplate_61216cd619c7950432f2854f03520e3d8a0cd17a65da4927db13de260f15cd9b extends Twig_Template
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
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["libro"] ?? null), "leyenda", array()), "html", null, true);
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["libro"] ?? null), "codigo", array()), "html", null, true);
        echo "</title>
    <meta name=\"title\" content=\"";
        // line 5
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["libro"] ?? null), "producto", array()), "html", null, true);
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["libro"] ?? null), "leyenda", array()), "html", null, true);
        echo ".";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["libro"] ?? null), "codigo", array()), "html", null, true);
        echo "\">";
    }

    // line 7
    public function block_ogUrl($context, array $blocks = array())
    {
        // line 8
        echo "    <meta property=\"og:url\" content=\"";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "libro/";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["cabeceras"] ?? null), "ruta", array()), "html", null, true);
        echo "\">";
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
            echo "\">";
        } else {
            // line 14
            echo "    <meta property=\"og:image\" content=\"";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["cabeceras"] ?? null), "portada", array()), "html", null, true);
            echo "\">";
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
</script>";
    }

    // line 39
    public function block_appBody($context, array $blocks = array())
    {
        // line 40
        echo "
    <main class=\"container-fluid mb-5\">";
        // line 44
        echo "        <section class=\"container p-0 info_producto\">

            <div class=\"row\">";
        // line 49
        echo "                <div class=\"col-12 my-4 mb-4 mb-sm-4 text-center text-sm-left animated fadeIn enlaces_relacionados\">";
        // line 50
        echo ($context["categoria_enlace"] ?? null);
        // line 51
        echo ($context["subcategoria_enlace"] ?? null);
        echo "
                </div>";
        // line 55
        echo "                <div class=\"col-12 col-sm-4 col-md-3 px-5 px-sm-3 d-none d-sm-inline animated fadeIn imagen_libro\">
                    
                    <div>
                        <img src=\"";
        // line 58
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["libro"] ?? null), "imagen", array()), "html", null, true);
        echo "?";
        echo twig_escape_filter($this->env, twig_random($this->env), "html", null, true);
        echo "\" width=\"100%\" class=\"rounded z-depth-3\">
                    </div>";
        // line 62
        echo "                    <div class=\"d-none d-sm-inline d-md-none animated fadeIn precios_acciones_libro movil\">";
        // line 65
        echo "                        <div class=\"row m-0 mt-3 text-center rounded z-depth-1 bg-light precios\">";
        // line 67
        echo ($context["precio"] ?? null);
        // line 69
        echo ($context["disponibilidad"] ?? null);
        // line 71
        if (twig_test_empty(($context["oferta"] ?? null))) {
        } else {
        }
        // line 80
        echo "                            
                        </div>";
        // line 85
        echo "                        <div class=\"row m-0 mt-3 d-flex align-items-center pb-3 acciones\">

                            <div class=\"col-4 pl-3 pl-sm-0 pr-1 text-left\">
                                <div class=\"card-icon-actions-lg\"> 
                                    <span class=\"div_deseo";
        // line 89
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["libro"] ?? null), "id", array()), "html", null, true);
        echo " mr-3\">";
        // line 90
        if (($context["is_logged"] ?? null)) {
            // line 91
            if ((($context["deseo"] ?? null) == "no")) {
                // line 92
                echo "                                                <a href=\"#\" class=\"love agregar_deseo\" id=\"";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["libro"] ?? null), "id", array()), "html", null, true);
                echo "\" data-toggle=\"tooltip\" data-html=\"true\" title=\"<small><b>Añadir a mi lista de deseos</b></small>\"><i class=\"far fa-heart\"></i></a>";
            } else {
                // line 94
                echo "                                                <i class=\"fas fa-heart fa-lg text-red animated pulse infinite\"></i>";
            }
        } else {
            // line 97
            echo "                                            <a href=\"";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
            echo "autenticacion\" class=\"love\" id=\"";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["libro"] ?? null), "id", array()), "html", null, true);
            echo "\" data-toggle=\"tooltip\" data-html=\"true\" title=\"<small><b>Añadir a mi lista de deseos</b></small>\"><i class=\"far fa-heart\"></i></a>";
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
                            <div class=\"col-8 pr-3 pr-sm-0 pl-1 text-right\">";
        // line 105
        if ((twig_get_attribute($this->env, $this->getSourceContext(), ($context["libro"] ?? null), "stock", array()) > 0)) {
            // line 106
            echo "                                <button type=\"button\" idLibro=\"";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["libro"] ?? null), "id", array()), "html", null, true);
            echo "\" class=\"btn btn-sm btn-icon boton_color agregar_carrito\">
                                    <span class=\"btn-inner--text\">Agregar</span>
                                    <span class=\"btn-inner--icon\"><i class=\"fas fa-shopping-bag\"></i></span>
                                </button>";
        }
        // line 111
        echo "                            </div>

                            <!--<div class=\"col-12 mt-3 text-center d-none animated-fast compartir\">
                                <button type=\"button\" class=\"btn btn-sm btn-facebook btn-icon-only rounded-circle border-0\">
                                    <span class=\"btn-inner--icon\"><i class=\"fab fa-facebook-f\"></i></span>
                                </button>
                            </div>-->

                        </div>";
        // line 125
        echo "                        
                        <div class=\"text-center mt-3 text-muted rounded p-2 border z-depth-1\">
                            <i class=\"fas fa-shipping-fast fa-lg animated fadeInLeft infinite mr-2\"></i> Envío gratis a partir de <strong>\$800.00</strong>
                        </div>

                    </div>";
        // line 132
        echo "
                </div>";
        // line 136
        echo "                <div class=\"col-12 col-sm-8 col-md-6 pr-3 pr-sm-5 datos_libro\">

                    <div class=\"row\">";
        // line 141
        echo "                        <div class=\"col-4 pr-0 d-inline d-sm-none animated fadeIn imagen_libro_movil\">
                            <img src=\"";
        // line 142
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["libro"] ?? null), "imagen", array()), "html", null, true);
        echo "?";
        echo twig_escape_filter($this->env, twig_random($this->env), "html", null, true);
        echo "\" width=\"100%\" class=\"rounded z-depth-3\">
                        </div>";
        // line 146
        echo "                        <div class=\"col-8 col-sm-12 datos_generales\">

                            <h1 class=\"font-weight-600 mt-0 mb-0 animated zoomIn titulo_libro\">";
        // line 149
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["libro"] ?? null), "producto", array()), "html", null, true);
        echo "
                            </h1>
                            <h6 class=\"my-0 animated zoomIn\">";
        // line 152
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["libro"] ?? null), "leyenda", array()), "html", null, true);
        echo "
                            </h6>

                            <div class=\"my-1 mt-0 animated zoomInLeft separador separador_color\"></div>

                            <p class=\"m-0 mt-1 animated zoomInRight\">

                                <span class=\"pl-0 autor_libro\">";
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
        echo "\">";
        // line 169
        if (twig_test_empty(($context["editorial"] ?? null))) {
            // line 170
            echo "                                            SIN SELLO";
        } else {
            // line 172
            echo twig_escape_filter($this->env, ($context["editorial"] ?? null), "html", null, true);
        }
        // line 174
        echo "                                    </a>
                                </span>

                            </p>

                            <div class=\"mt-3 animated zoomInUp\">";
        // line 180
        echo ($context["oferta"] ?? null);
        echo "
                            </div>

                        </div>";
        // line 185
        echo "
                    </div>";
        // line 190
        echo "                    <div class=\"mt-3 table-responsive animated fadeIn ficha_tecnica\">
                        <table class=\"table table-cards align-items-center text-center\">
                            <thead>
                                <tr>
                                    <th scope=\"col\" class=\"py-1 px-2 detalle\" style=\"min-width:30px;\">ID</th>
                                    <th scope=\"col\" class=\"py-1 px-2 detalle\" style=\"min-width:150px;\">ISBN-CÓDIGO</th>";
        // line 196
        echo ($context["tr_detalles"] ?? null);
        echo "
                                </tr>
                            </thead>
                            <tbody>
                                <tr class=\"bg-white\">
                                    <th scope=\"row\" class=\"py-1 px-2 font-weight-normal\">";
        // line 201
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["libro"] ?? null), "id", array()), "html", null, true);
        echo "</th>
                                    <td scope=\"row\" class=\"py-1 px-2\">";
        // line 202
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["libro"] ?? null), "codigo", array()), "html", null, true);
        echo "</td>";
        // line 203
        echo ($context["td_detalles"] ?? null);
        echo "
                                </tr>
                            </tbody>
                        </table>
                    </div>";
        // line 211
        echo "                    <div class=\"mt-3 mt-sm-5 text-justify animated fadeIn sinopsis\">
                        <h6>SINOPSIS:</h6>
                        <p class=\"text-justify\">";
        // line 214
        echo ($context["descripcion"] ?? null);
        echo "
                        </p>
                    </div>";
        // line 218
        echo "
                </div>";
        // line 223
        echo "                <div class=\"col-12 col-sm-3 offset-sm-0 d-sm-none d-md-inline pt-3 pt-sm-0 animated fadeIn precios_acciones_libro\">";
        // line 226
        echo "                    <div class=\"row text-center row rounded z-depth-1 bg-light mx-0 precios\">";
        // line 228
        echo ($context["precio"] ?? null);
        // line 230
        echo ($context["disponibilidad"] ?? null);
        // line 232
        if (twig_test_empty(($context["oferta"] ?? null))) {
        } else {
        }
        // line 241
        echo "                        
                    </div>";
        // line 246
        echo "                    <div class=\"row mt-3 d-flex align-items-center pb-3 mx-0 acciones\">

                        <div class=\"col-4 pl-3 pl-sm-0 pr-1 text-left\">
                            <div class=\"card-icon-actions-lg\"> 
                                <span class=\"div_deseo";
        // line 250
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["libro"] ?? null), "id", array()), "html", null, true);
        echo " mr-3\">";
        // line 251
        if (($context["is_logged"] ?? null)) {
            // line 252
            if ((($context["deseo"] ?? null) == "no")) {
                // line 253
                echo "                                            <a href=\"#\" class=\"love agregar_deseo\" id=\"";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["libro"] ?? null), "id", array()), "html", null, true);
                echo "\" data-toggle=\"tooltip\" data-html=\"true\" title=\"<small><b>Añadir a mi lista de deseos</b></small>\"><i class=\"far fa-heart\"></i></a>";
            } else {
                // line 255
                echo "                                            <i class=\"fas fa-heart fa-lg text-red animated pulse infinite\"></i>";
            }
        } else {
            // line 258
            echo "                                        <a href=\"";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
            echo "autenticacion\" class=\"love\" id=\"";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["libro"] ?? null), "id", array()), "html", null, true);
            echo "\" data-toggle=\"tooltip\" data-html=\"true\" title=\"<small><b>Añadir a mi lista de deseos</b></small>\"><i class=\"far fa-heart\"></i></a>";
        }
        // line 260
        echo "                                </span>
                                <!--<a href=\"#\" class=\"share\" idLibro=\"";
        // line 261
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["libro"] ?? null), "id", array()), "html", null, true);
        echo "\" compartir=\"d-block\" data-toggle=\"tooltip\" data-html=\"true\" title=\"<small><b>Compartir</b></small>\"><i class=\"fas fa-share-alt\"></i></a>-->
                            </div>
                        </div>

                        <div class=\"col-8 pr-3 pr-sm-0 pl-1 text-right\">";
        // line 266
        if ((twig_get_attribute($this->env, $this->getSourceContext(), ($context["libro"] ?? null), "stock", array()) > 0)) {
            // line 267
            echo "                            <button type=\"button\" idLibro=\"";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["libro"] ?? null), "id", array()), "html", null, true);
            echo "\" class=\"btn btn-sm btn-icon boton_color agregar_carrito\">
                                <span class=\"btn-inner--text\">Agregar</span>
                                <span class=\"btn-inner--icon\"><i class=\"fas fa-shopping-bag\"></i></span>
                            </button>";
        }
        // line 272
        echo "                        </div>

                        <!--<div class=\"col-12 mt-3 text-center d-none animated-fast compartir\">
                            <button type=\"button\" class=\"btn btn-sm btn-facebook btn-icon-only rounded-circle border-0\">
                                <span class=\"btn-inner--icon\"><i class=\"fab fa-facebook-f\"></i></span>
                            </button>
                        </div>-->

                    </div>";
        // line 282
        echo "                    
                    <div class=\"text-center mt-3 text-muted rounded p-2 border z-depth-1\">
                        <i class=\"fas fa-shipping-fast fa-lg animated fadeInLeft infinite mr-2\"></i> Envío gratis a partir de <strong>\$800.00</strong><br>
                    </div>
                            
                </div>";
        // line 289
        echo "
            </div>";
        // line 292
        echo "            
        </section>";
        // line 295
        echo "
        
        <div class=\"container p-0 mt-5\">

            <ul class=\"nav nav-pills nav-fill flex-column flex-sm-row\" id=\"myTab\" role=\"tablist\">
                <li class=\"nav-item mb-1 mb-sm-0\">
                    <a class=\"nav-link py-2 m-0 active\" id=\"libros_subcategoria-tab\" data-toggle=\"tab\" href=\"#libros_subcategoria\" role=\"tab\" aria-controls=\"libros_subcategoria\" aria-selected=\"true\">
                        + de";
        // line 302
        echo twig_escape_filter($this->env, ($context["subcategoria"] ?? null), "html", null, true);
        echo "
                    </a>
                </li>
                <li class=\"nav-item mb-1 mb-sm-0\">
                    <a class=\"nav-link py-2 m-0\" id=\"libros_categoria-tab\" data-toggle=\"tab\" href=\"#libros_categoria\" role=\"tab\" aria-controls=\"libros_categoria\" aria-selected=\"false\">
                        + de";
        // line 307
        echo twig_escape_filter($this->env, ($context["categoria"] ?? null), "html", null, true);
        echo "
                    </a>
                </li>
                <li class=\"nav-item mb-1 mb-sm-0\">
                    <a class=\"nav-link py-2 m-0\" id=\"libros_editorial-tab\" data-toggle=\"tab\" href=\"#libros_editorial\" role=\"tab\" aria-controls=\"libros_editorial\" aria-selected=\"false\">";
        // line 312
        if ((($context["editorial"] ?? null) != "Sin editorial")) {
            // line 313
            echo "                            + de";
            echo twig_escape_filter($this->env, ($context["editorial"] ?? null), "html", null, true);
        } else {
            // line 315
            echo "                            Más sin sello";
        }
        // line 317
        echo "                    </a>
                </li>
            </ul>

            <div class=\"tab-content p-2 pt-5 border-right border-left border-bottom rounded-bottom\" id=\"myTabContent\">
                <div class=\"tab-pane fade show active\" id=\"libros_subcategoria\" role=\"tabpanel\" aria-labelledby=\"libros_subcategoria-tab\">";
        // line 323
        if (twig_test_empty(($context["libros_subcategoria"] ?? null))) {
            // line 324
            echo "                        <p class=\"text-center mb-5 text-muted\">No hay más libros asociados a";
            echo twig_escape_filter($this->env, ($context["subcategoria"] ?? null), "html", null, true);
            echo ".</p>";
        } else {
            // line 326
            echo ($context["libros_subcategoria"] ?? null);
        }
        // line 328
        echo "                </div>
                <div class=\"tab-pane fade\" id=\"libros_categoria\" role=\"tabpanel\" aria-labelledby=\"libros_categoria-tab\">";
        // line 330
        if (twig_test_empty(($context["libros_categoria"] ?? null))) {
            // line 331
            echo "                        <p class=\"text-center mb-5 text-muted\">No hay más libros asociados a";
            echo twig_escape_filter($this->env, ($context["categoria"] ?? null), "html", null, true);
            echo ".</p>";
        } else {
            // line 333
            echo ($context["libros_categoria"] ?? null);
        }
        // line 335
        echo "                </div>
                <div class=\"tab-pane fade\" id=\"libros_editorial\" role=\"tabpanel\" aria-labelledby=\"libros_editorial-tab\">";
        // line 337
        if (twig_test_empty(($context["libros_editorial"] ?? null))) {
            // line 338
            echo "                        <p class=\"text-center mb-5 text-muted\">No hay más libros asociados a la editorial";
            echo twig_escape_filter($this->env, ($context["editorial"] ?? null), "html", null, true);
            echo ".</p>";
        } else {
            // line 340
            echo ($context["libros_editorial"] ?? null);
        }
        // line 342
        echo "                </div>
            </div>
        </div>


    </main>";
    }

    // line 351
    public function block_appFooter($context, array $blocks = array())
    {
        // line 352
        echo "\t<script src=\"assets/jscontrollers/libro/libro.js\"></script>";
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
        return array (  529 => 352,  526 => 351,  517 => 342,  514 => 340,  509 => 338,  507 => 337,  504 => 335,  501 => 333,  496 => 331,  494 => 330,  491 => 328,  488 => 326,  483 => 324,  481 => 323,  474 => 317,  471 => 315,  467 => 313,  465 => 312,  458 => 307,  450 => 302,  441 => 295,  438 => 292,  435 => 289,  428 => 282,  418 => 272,  410 => 267,  408 => 266,  401 => 261,  398 => 260,  391 => 258,  387 => 255,  382 => 253,  380 => 252,  378 => 251,  375 => 250,  369 => 246,  366 => 241,  362 => 232,  360 => 230,  358 => 228,  356 => 226,  354 => 223,  351 => 218,  346 => 214,  342 => 211,  335 => 203,  332 => 202,  328 => 201,  320 => 196,  313 => 190,  310 => 185,  304 => 180,  297 => 174,  294 => 172,  291 => 170,  289 => 169,  284 => 168,  273 => 160,  263 => 152,  258 => 149,  254 => 146,  247 => 142,  244 => 141,  240 => 136,  237 => 132,  230 => 125,  220 => 111,  212 => 106,  210 => 105,  203 => 100,  200 => 99,  193 => 97,  189 => 94,  184 => 92,  182 => 91,  180 => 90,  177 => 89,  171 => 85,  168 => 80,  164 => 71,  162 => 69,  160 => 67,  158 => 65,  156 => 62,  149 => 58,  144 => 55,  140 => 51,  138 => 50,  136 => 49,  132 => 44,  129 => 40,  126 => 39,  117 => 32,  111 => 29,  105 => 28,  101 => 27,  96 => 26,  91 => 25,  84 => 20,  81 => 18,  73 => 14,  67 => 12,  65 => 11,  62 => 10,  54 => 8,  51 => 7,  43 => 5,  36 => 4,  33 => 3,  11 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "libro/libro.twig", "/home4/eltimonl/public_html/app/templates/libro/libro.twig");
    }
}
