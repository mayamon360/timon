<?php

/* libro/libro.twig */
class __TwigTemplate_ea4c69e89308d6bc49f6d1f27847da693a8b6560e41174e97047288eba0a75c0 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("overall/layout", "libro/libro.twig", 1);
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
        echo "    <title>";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["libro"] ?? null), "producto", array()), "html", null, true);
        echo "</title>
";
    }

    // line 7
    public function block_appHeader($context, array $blocks = array())
    {
        // line 9
        echo "\t<link rel=\"stylesheet\" href=\"assets/plantilla/libro.css\">
";
    }

    // line 12
    public function block_appBody($context, array $blocks = array())
    {
        // line 13
        echo "
    <main class=\"container-fluid\">

        ";
        // line 17
        echo "        <section class=\"container p-0 info_producto\">

            <div class=\"row\">

                ";
        // line 22
        echo "                <div class=\"col-12 my-2 mb-4 mb-sm-2 text-center text-sm-left animated fadeIn enlaces_relacionados\">
                    ";
        // line 23
        echo ($context["categoria_enlace"] ?? null);
        echo "
                    ";
        // line 24
        echo ($context["subcategoria_enlace"] ?? null);
        echo "
                </div>

                ";
        // line 28
        echo "                <div class=\"col-12 col-sm-4 col-md-3 px-5 px-sm-3 d-none d-sm-inline animated fadeIn imagen_libro\">

                    <img src=\"";
        // line 30
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["libro"] ?? null), "imagen", array()), "html", null, true);
        echo "\" width=\"100%\" class=\"rounded z-depth-1\">

                    ";
        // line 33
        echo "                    <div class=\"d-none d-sm-inline d-md-none animated fadeIn precios_acciones_libro movil\">
                        
                        ";
        // line 36
        echo "                        <div class=\"row m-0 mt-3 text-center rounded z-depth-1 bg-light precios\">

                            ";
        // line 38
        echo ($context["precio"] ?? null);
        echo "

                            ";
        // line 40
        echo ($context["disponibilidad"] ?? null);
        echo "

                            ";
        // line 42
        if (twig_test_empty(($context["oferta"] ?? null))) {
            // line 43
            echo "                            <div class=\"col-12 bg-light rounded-bottom py-2 border-top puntos_libro\">
                                ";
            // line 44
            echo twig_escape_filter($this->env, ($context["puntos"] ?? null), "html", null, true);
            echo " puntos
                            </div>
                            ";
        } else {
            // line 47
            echo "                            <div class=\"col-12 bg-light rounded-bottom py-2 border-top puntos_libro\">
                                0 puntos
                            </div>
                            ";
        }
        // line 51
        echo "                            
                        </div>
                        ";
        // line 54
        echo "
                        ";
        // line 56
        echo "                        <div class=\"row m-0 mt-3 d-flex align-items-center pb-3 acciones\">

                            <div class=\"col-4 pl-3 pl-sm-0 pr-1 text-left\">
                                <div class=\"card-icon-actions-lg\"> 
                                    <span class=\"div_deseo";
        // line 60
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["libro"] ?? null), "id", array()), "html", null, true);
        echo " mr-3\">
                                        ";
        // line 61
        if (($context["is_logged"] ?? null)) {
            // line 62
            echo "                                            ";
            if ((($context["deseo"] ?? null) == "no")) {
                // line 63
                echo "                                                <a href=\"#\" class=\"love agregar_deseo\" id=\"";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["libro"] ?? null), "id", array()), "html", null, true);
                echo "\" data-toggle=\"tooltip\" data-html=\"true\" title=\"<small><b>Añadir a mi lista de deseos</b></small>\"><i class=\"far fa-heart\"></i></a>
                                            ";
            } else {
                // line 65
                echo "                                                <i class=\"fas fa-heart fa-lg text-red animated pulse infinite\"></i>
                                            ";
            }
            // line 67
            echo "                                        ";
        } else {
            // line 68
            echo "                                            <a href=\"";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
            echo "autenticacion\" class=\"love\" id=\"";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["libro"] ?? null), "id", array()), "html", null, true);
            echo "\" data-toggle=\"tooltip\" data-html=\"true\" title=\"<small><b>Añadir a mi lista de deseos</b></small>\"><i class=\"far fa-heart\"></i></a>
                                        ";
        }
        // line 70
        echo "                                    </span>
                                    <a href=\"#\" class=\"share\" idLibro=\"";
        // line 71
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["libro"] ?? null), "id", array()), "html", null, true);
        echo "\" compartir=\"d-block\" data-toggle=\"tooltip\" data-html=\"true\" title=\"<small><b>Compartir</b></small>\"><i class=\"fas fa-share-alt\"></i></a>
                                </div>
                            </div>

                            <div class=\"col-8 pr-3 pr-sm-0 pl-1 text-right\">
                                <button type=\"button\" idLibro=\"";
        // line 76
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["libro"] ?? null), "id", array()), "html", null, true);
        echo "\" class=\"btn btn-sm btn-icon boton_color agregar_carrito\">
                                    <span class=\"btn-inner--text\">Comprar</span>
                                    <span class=\"btn-inner--icon\"><i class=\"fas fa-shopping-bag\"></i></span>
                                </button>
                            </div>

                            <div class=\"col-12 mt-3 text-center d-none animated-fast compartir\">
                                <button type=\"button\" class=\"btn btn-sm btn-facebook btn-icon-only rounded-circle border-0\">
                                    <span class=\"btn-inner--icon\"><i class=\"fab fa-facebook-f\"></i></span>
                                </button>
                            </div>

                        </div> 
                        ";
        // line 90
        echo "
                    </div>
                    ";
        // line 93
        echo "
                </div>

                ";
        // line 97
        echo "                <div class=\"col-12 col-sm-8 col-md-6 pr-3 pr-sm-5 datos_libro\">

                    <div class=\"row\">

                        ";
        // line 102
        echo "                        <div class=\"col-4 pr-0 d-inline d-sm-none animated fadeIn imagen_libro_movil\">
                            <img src=\"";
        // line 103
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["libro"] ?? null), "imagen", array()), "html", null, true);
        echo "\" width=\"100%\" class=\"z-depth-1 rounded\">
                        </div>

                        ";
        // line 107
        echo "                        <div class=\"col-8 col-sm-12 datos_generales\">

                            <h1 class=\"font-weight-600 mt-0 animated zoomIn titulo_libro\">
                                ";
        // line 110
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["libro"] ?? null), "producto", array()), "html", null, true);
        echo "
                            </h1>

                            <div class=\"my-1 animated zoomInLeft separador separador_color\"></div>

                            <p class=\"m-0 mt-2 animated zoomInRight\">

                                <span class=\"badge badge-dot pl-0 autor_libro\">
                                    <i class=\"bg-app dot\"></i> 
                                    <span class=\"mr-1\">
                                        AUTOR 
                                    </span> 
                                    ";
        // line 122
        echo ($context["autores_html"] ?? null);
        echo "
                                </span>

                                <span class=\"badge badge-dot pl-0 ml-0 ml-md-3 editorial_libro\">
                                    <i class=\"bg-app dot\"></i> 
                                    <span class=\"mr-1\">
                                        EDITORIAL 
                                    </span> 
                                    <a class=\"text-truncate pixelEditorial\" href=\"";
        // line 130
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "editorial/";
        echo twig_escape_filter($this->env, ($context["ruta_editorial"] ?? null), "html", null, true);
        echo "\" data-toggle=\"tooltip\" data-html=\"true\" title=\"<small><b>Ver libros de la editorial</b></small>\">
                                        ";
        // line 131
        if (twig_test_empty(($context["editorial"] ?? null))) {
            // line 132
            echo "                                            SIN EDITORIAL
                                        ";
        } else {
            // line 134
            echo "                                            ";
            echo twig_escape_filter($this->env, ($context["editorial"] ?? null), "html", null, true);
            echo "
                                        ";
        }
        // line 136
        echo "                                    </a>
                                </span>

                            </p>

                            <div class=\"mt-3 animated zoomInUp\">
                                ";
        // line 142
        echo ($context["nuevo"] ?? null);
        echo "
                                ";
        // line 143
        echo ($context["oferta"] ?? null);
        echo "
                            </div>

                        </div>
                        ";
        // line 148
        echo "
                    </div>
                    ";
        // line 151
        echo "
                    ";
        // line 153
        echo "                    <div class=\"mt-5 text-justify animated fadeIn sinopsis\">
                        <h6>SINOPSIS:</h6>
                        <p class=\"text-justify\">
                            ";
        // line 156
        echo nl2br(twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["libro"] ?? null), "descripcion", array()), "html", null, true));
        echo "
                        </p>
                    </div>
                    ";
        // line 160
        echo "
                    ";
        // line 162
        echo "                    <div class=\"table-responsive animated fadeIn ficha_tecnica\">
                        <table class=\"table table-cards align-items-center text-center\">
                            <thead>
                                <tr>
                                    <th scope=\"col\" class=\"py-1 px-2 detalle\" style=\"min-width:30px;\">ID</th>
                                    <th scope=\"col\" class=\"py-1 px-2 detalle\" style=\"min-width:150px;\">ISBN-CÓDIGO</th>
                                    ";
        // line 168
        echo ($context["tr_detalles"] ?? null);
        echo "
                                </tr>
                            </thead>
                            <tbody>
                                <tr class=\"bg-white\">
                                    <th scope=\"row\" class=\"py-1 px-2 font-weight-normal\">";
        // line 173
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["libro"] ?? null), "id", array()), "html", null, true);
        echo "</th>
                                    <td scope=\"row\" class=\"py-1 px-2\">";
        // line 174
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["libro"] ?? null), "codigo", array()), "html", null, true);
        echo "</td>
                                    ";
        // line 175
        echo ($context["td_detalles"] ?? null);
        echo "
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    ";
        // line 181
        echo "
                </div>
                ";
        // line 184
        echo "
                ";
        // line 186
        echo "                <div class=\"col-10 offset-1 col-sm-3 offset-sm-0 d-sm-none d-md-inline pt-3 pt-sm-0 animated fadeIn precios_acciones_libro\">
                    
                    ";
        // line 189
        echo "                    <div class=\"row text-center row rounded z-depth-1 bg-light mx-0 precios\">

                        ";
        // line 191
        echo ($context["precio"] ?? null);
        echo "

                        ";
        // line 193
        echo ($context["disponibilidad"] ?? null);
        echo "

                        ";
        // line 195
        if (twig_test_empty(($context["oferta"] ?? null))) {
            // line 196
            echo "                        <div class=\"col-12 bg-light rounded-bottom py-2 border-top puntos_libro\">
                            ";
            // line 197
            echo twig_escape_filter($this->env, ($context["puntos"] ?? null), "html", null, true);
            echo " puntos
                        </div>
                        ";
        } else {
            // line 200
            echo "                        <div class=\"col-12 bg-light rounded-bottom py-2 border-top puntos_libro\">
                            0 puntos
                        </div>
                        ";
        }
        // line 204
        echo "                        
                    </div>
                    ";
        // line 207
        echo "
                    ";
        // line 209
        echo "                    <div class=\"row mt-3 d-flex align-items-center pb-3 mx-0 acciones\">

                        <div class=\"col-4 pl-3 pl-sm-0 pr-1 text-left\">
                            <div class=\"card-icon-actions-lg\"> 
                                <span class=\"div_deseo";
        // line 213
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["libro"] ?? null), "id", array()), "html", null, true);
        echo " mr-3\">
                                    ";
        // line 214
        if (($context["is_logged"] ?? null)) {
            // line 215
            echo "                                        ";
            if ((($context["deseo"] ?? null) == "no")) {
                // line 216
                echo "                                            <a href=\"#\" class=\"love agregar_deseo\" id=\"";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["libro"] ?? null), "id", array()), "html", null, true);
                echo "\" data-toggle=\"tooltip\" data-html=\"true\" title=\"<small><b>Añadir a mi lista de deseos</b></small>\"><i class=\"far fa-heart\"></i></a>
                                        ";
            } else {
                // line 218
                echo "                                            <i class=\"fas fa-heart fa-lg text-red animated pulse infinite\"></i>
                                        ";
            }
            // line 220
            echo "                                    ";
        } else {
            // line 221
            echo "                                        <a href=\"";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
            echo "autenticacion\" class=\"love\" id=\"";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["libro"] ?? null), "id", array()), "html", null, true);
            echo "\" data-toggle=\"tooltip\" data-html=\"true\" title=\"<small><b>Añadir a mi lista de deseos</b></small>\"><i class=\"far fa-heart\"></i></a>
                                    ";
        }
        // line 223
        echo "                                </span>
                                <a href=\"#\" class=\"share\" idLibro=\"";
        // line 224
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["libro"] ?? null), "id", array()), "html", null, true);
        echo "\" compartir=\"d-block\" data-toggle=\"tooltip\" data-html=\"true\" title=\"<small><b>Compartir</b></small>\"><i class=\"fas fa-share-alt\"></i></a>
                            </div>
                        </div>

                        <div class=\"col-8 pr-3 pr-sm-0 pl-1 text-right\">
                            <button type=\"button\" idLibro=\"";
        // line 229
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["libro"] ?? null), "id", array()), "html", null, true);
        echo "\" class=\"btn btn-sm btn-icon boton_color agregar_carrito\">
                                <span class=\"btn-inner--text\">Comprar</span>
                                <span class=\"btn-inner--icon\"><i class=\"fas fa-shopping-bag\"></i></span>
                            </button>
                        </div>

                        <div class=\"col-12 mt-3 text-center d-none animated-fast compartir\">
                            <button type=\"button\" class=\"btn btn-sm btn-facebook btn-icon-only rounded-circle border-0\">
                                <span class=\"btn-inner--icon\"><i class=\"fab fa-facebook-f\"></i></span>
                            </button>
                        </div>

                    </div> 
                    ";
        // line 243
        echo "
                </div>
                ";
        // line 246
        echo "
            </div>
            ";
        // line 249
        echo "            
        </section>
        ";
        // line 252
        echo "
        
        <div class=\"container p-0 mt-5\">

            <ul class=\"nav nav-pills nav-fill flex-column flex-sm-row\" id=\"myTab\" role=\"tablist\">
                <li class=\"nav-item mb-1 mb-sm-0\">
                    <a class=\"nav-link py-2 m-0 active\" id=\"libros_editorial-tab\" data-toggle=\"tab\" href=\"#libros_editorial\" role=\"tab\" aria-controls=\"libros_editorial\" aria-selected=\"true\">
                        ";
        // line 259
        if ((($context["editorial"] ?? null) != "Sin editorial")) {
            // line 260
            echo "                            + de editorial ";
            echo twig_escape_filter($this->env, ($context["editorial"] ?? null), "html", null, true);
            echo "
                        ";
        } else {
            // line 262
            echo "                            Más libros sin editorial
                        ";
        }
        // line 264
        echo "                    </a>
                </li>
                <li class=\"nav-item mb-1 mb-sm-0\">
                    <a class=\"nav-link py-2 m-0\" id=\"libros_categoria-tab\" data-toggle=\"tab\" href=\"#libros_categoria\" role=\"tab\" aria-controls=\"libros_categoria\" aria-selected=\"false\">
                        + libros de ";
        // line 268
        echo twig_escape_filter($this->env, ($context["categoria"] ?? null), "html", null, true);
        echo "
                    </a>
                </li>
                <li class=\"nav-item mb-1 mb-sm-0\">
                    <a class=\"nav-link py-2 m-0\" id=\"libros_subcategoria-tab\" data-toggle=\"tab\" href=\"#libros_subcategoria\" role=\"tab\" aria-controls=\"libros_subcategoria\" aria-selected=\"false\">
                        + libros de ";
        // line 273
        echo twig_escape_filter($this->env, ($context["subcategoria"] ?? null), "html", null, true);
        echo "
                    </a>
                </li>
            </ul>

            <div class=\"tab-content p-2 pt-5 border-right border-left border-bottom rounded-bottom\" id=\"myTabContent\">
                <div class=\"tab-pane fade show active\" id=\"libros_editorial\" role=\"tabpanel\" aria-labelledby=\"libros_editorial-tab\">
                    ";
        // line 280
        if (twig_test_empty(($context["libros_editorial"] ?? null))) {
            // line 281
            echo "                        <p class=\"text-center mb-5 text-muted\">No hay más libros asociados a la editorial ";
            echo twig_escape_filter($this->env, ($context["editorial"] ?? null), "html", null, true);
            echo ".</p>
                    ";
        } else {
            // line 283
            echo "                        ";
            echo ($context["libros_editorial"] ?? null);
            echo "
                    ";
        }
        // line 285
        echo "                </div>
                <div class=\"tab-pane fade\" id=\"libros_categoria\" role=\"tabpanel\" aria-labelledby=\"libros_categoria-tab\">
                    ";
        // line 287
        if (twig_test_empty(($context["libros_categoria"] ?? null))) {
            // line 288
            echo "                        <p class=\"text-center mb-5 text-muted\">No hay más libros asociados a ";
            echo twig_escape_filter($this->env, ($context["categoria"] ?? null), "html", null, true);
            echo ".</p>
                    ";
        } else {
            // line 290
            echo "                        ";
            echo ($context["libros_categoria"] ?? null);
            echo "
                    ";
        }
        // line 292
        echo "                </div>
                <div class=\"tab-pane fade\" id=\"libros_subcategoria\" role=\"tabpanel\" aria-labelledby=\"libros_subcategoria-tab\">
                    ";
        // line 294
        if (twig_test_empty(($context["libros_subcategoria"] ?? null))) {
            // line 295
            echo "                        <p class=\"text-center mb-5 text-muted\">No hay más libros asociados a ";
            echo twig_escape_filter($this->env, ($context["subcategoria"] ?? null), "html", null, true);
            echo ".</p>
                    ";
        } else {
            // line 297
            echo "                        ";
            echo ($context["libros_subcategoria"] ?? null);
            echo "
                    ";
        }
        // line 299
        echo "                </div>
            </div>
        </div>


    </main>
    
";
    }

    // line 308
    public function block_appFooter($context, array $blocks = array())
    {
        // line 309
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
        return array (  570 => 309,  567 => 308,  556 => 299,  550 => 297,  544 => 295,  542 => 294,  538 => 292,  532 => 290,  526 => 288,  524 => 287,  520 => 285,  514 => 283,  508 => 281,  506 => 280,  496 => 273,  488 => 268,  482 => 264,  478 => 262,  472 => 260,  470 => 259,  461 => 252,  457 => 249,  453 => 246,  449 => 243,  433 => 229,  425 => 224,  422 => 223,  414 => 221,  411 => 220,  407 => 218,  401 => 216,  398 => 215,  396 => 214,  392 => 213,  386 => 209,  383 => 207,  379 => 204,  373 => 200,  367 => 197,  364 => 196,  362 => 195,  357 => 193,  352 => 191,  348 => 189,  344 => 186,  341 => 184,  337 => 181,  329 => 175,  325 => 174,  321 => 173,  313 => 168,  305 => 162,  302 => 160,  296 => 156,  291 => 153,  288 => 151,  284 => 148,  277 => 143,  273 => 142,  265 => 136,  259 => 134,  255 => 132,  253 => 131,  247 => 130,  236 => 122,  221 => 110,  216 => 107,  209 => 103,  206 => 102,  200 => 97,  195 => 93,  191 => 90,  175 => 76,  167 => 71,  164 => 70,  156 => 68,  153 => 67,  149 => 65,  143 => 63,  140 => 62,  138 => 61,  134 => 60,  128 => 56,  125 => 54,  121 => 51,  115 => 47,  109 => 44,  106 => 43,  104 => 42,  99 => 40,  94 => 38,  90 => 36,  86 => 33,  80 => 30,  76 => 28,  70 => 24,  66 => 23,  63 => 22,  57 => 17,  52 => 13,  49 => 12,  44 => 9,  41 => 7,  34 => 4,  31 => 3,  11 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "libro/libro.twig", "C:\\xampp\\htdocs\\website\\app\\templates\\libro\\libro.twig");
    }
}
