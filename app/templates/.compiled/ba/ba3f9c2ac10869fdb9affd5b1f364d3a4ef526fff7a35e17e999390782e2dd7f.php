<?php

/* home/slides.twig */
class __TwigTemplate_0ec768edc24a16a1006ae3b1bb08dded058c0e0fe714038e6c890cbda6faf2cb extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<section class=\"container mt-0 mt-sm-5 p-0 rounded-bottom z-depth-3\">
    <div id=\"carrusel_superior\" class=\"carousel slide carousel-fade\" data-ride=\"carousel\" data-interval=\"5000\" data-pause=\"hover\" style=\"cursor:move;\">
        <div class=\"carousel-inner rounded-top\">

            ";
        // line 5
        if (twig_test_empty(($context["slides"] ?? null))) {
            // line 6
            echo "                ...
            ";
        } else {
            // line 8
            echo "                
                ";
            // line 9
            $context["cont"] = 1;
            // line 10
            echo "                ";
            $context["active"] = " active";
            // line 11
            echo "
                ";
            // line 12
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(($context["slides"] ?? null));
            foreach ($context['_seq'] as $context["_key"] => $context["s"]) {
                // line 13
                echo "
                    ";
                // line 14
                if ((($context["cont"] ?? null) != 1)) {
                    // line 15
                    echo "                        ";
                    $context["active"] = "";
                    // line 16
                    echo "                    ";
                }
                // line 17
                echo "
                    <div class=\"carousel-item ";
                // line 18
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), $context["s"], "tipo", array()), "html", null, true);
                echo twig_escape_filter($this->env, ($context["active"] ?? null), "html", null, true);
                echo "\">

                        <img src=\"";
                // line 20
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), $context["s"], "fondo", array()), "html", null, true);
                echo "\">
                        <span class=\"mask bg-dark alpha-1\"></span>

                        ";
                // line 23
                if ((twig_get_attribute($this->env, $this->getSourceContext(), $context["s"], "tipo", array()) != "tipo_cinco")) {
                    // line 24
                    echo "
                        <div class=\"container h-100 card-img-overlay contenido_carrusel\">
                            <div class=\"row h-100\">

                            ";
                    // line 28
                    if (((twig_get_attribute($this->env, $this->getSourceContext(), $context["s"], "tipo", array()) == "tipo_uno") || (twig_get_attribute($this->env, $this->getSourceContext(), $context["s"], "tipo", array()) == "tipo_tres"))) {
                        // line 29
                        echo "                                <div class=\"col-12 px-5 p-sm-0 col-sm-6 col-md-6 my-auto text-center text-sm-right\">
                                    <h1 class=\"p-0 text-truncate animated ";
                        // line 30
                        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), $context["s"], "animatedTitulo", array()), "html", null, true);
                        echo " titulo_carrusel\">
                                        <span class=\"rounded\">";
                        // line 31
                        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), $context["s"], "titulo", array()), "html", null, true);
                        echo "</span>
                                    </h1>
                                    <div class=\"my-1 mx-auto mx-sm-0 ml-sm-auto animated ";
                        // line 33
                        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), $context["s"], "animatedTitulo", array()), "html", null, true);
                        echo " separador separador_blanco\"></div>
                                    <p class=\"mt-0 mt-lg-4 rounded animated ";
                        // line 34
                        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), $context["s"], "animatedTexto", array()), "html", null, true);
                        echo " descripcion_carrusel\">
                                        ";
                        // line 35
                        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), $context["s"], "descripcion", array()), "html", null, true);
                        echo "
                                    </p>

                                    ";
                        // line 38
                        if ((twig_get_attribute($this->env, $this->getSourceContext(), $context["s"], "enlace", array()) != "")) {
                            // line 39
                            echo "                                    <a href=\"";
                            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), $context["s"], "enlace", array()), "html", null, true);
                            echo "\" class=\"btn btn-sm btn-icon noSwipe z-depth-1 animated fadeInUpBig boton_border_color\">
                                        <span class=\"btn-inner--icon\"><i class=\"fas fa-info-circle\"></i></span>
                                        <span class=\"btn-inner--text\">Más información</span>
                                    </a>
                                    ";
                        }
                        // line 44
                        echo "
                                </div>
                                <div class=\"col-5 col-sm-6 col-md-6 ";
                        // line 46
                        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), $context["s"], "alignImg", array()), "html", null, true);
                        echo " d-none d-sm-inline text-center\">
                                    <img class=\"img-fluid z-depth-4 ";
                        // line 47
                        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), $context["s"], "roundedImg", array()), "html", null, true);
                        echo " animated ";
                        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), $context["s"], "animatedImagen", array()), "html", null, true);
                        echo " imagen_carrusel\" src=\"";
                        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
                        echo "assets/plantilla/img/slides/slide1/imagen.jpg\">
                                </div>
                            ";
                    } else {
                        // line 50
                        echo "                                <div class=\"col-5 col-sm-6 col-md-6 ";
                        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), $context["s"], "alignImg", array()), "html", null, true);
                        echo " d-none d-sm-inline text-center\">
                                    <img class=\"img-fluid z-depth-4 ";
                        // line 51
                        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), $context["s"], "roundedImg", array()), "html", null, true);
                        echo " animated ";
                        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), $context["s"], "animatedImagen", array()), "html", null, true);
                        echo " imagen_carrusel\" src=\"";
                        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
                        echo "assets/plantilla/img/slides/slide2/imagen.jpg\">
                                </div>
                                <div class=\"col-12 px-5 p-sm-0 col-sm-6 col-md-6 my-auto text-center text-sm-left\">
                                    <h1 class=\"p-0 text-truncate animated ";
                        // line 54
                        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), $context["s"], "animatedTitulo", array()), "html", null, true);
                        echo " titulo_carrusel\">
                                        <span class=\"rounded\">";
                        // line 55
                        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), $context["s"], "titulo", array()), "html", null, true);
                        echo "</span>
                                    </h1>
                                    <div class=\"my-1 mx-auto mx-sm-0 mr-sm-auto animated ";
                        // line 57
                        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), $context["s"], "animatedTitulo", array()), "html", null, true);
                        echo " separador separador_blanco\"></div>
                                    <p class=\" mt-0 mt-lg-4 rounded animated ";
                        // line 58
                        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), $context["s"], "animatedTexto", array()), "html", null, true);
                        echo " descripcion_carrusel\">
                                        ";
                        // line 59
                        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), $context["s"], "descripcion", array()), "html", null, true);
                        echo "
                                    </p>

                                    ";
                        // line 62
                        if ((twig_get_attribute($this->env, $this->getSourceContext(), $context["s"], "enlace", array()) != "")) {
                            // line 63
                            echo "                                    <a href=\"";
                            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), $context["s"], "enlace", array()), "html", null, true);
                            echo "\" class=\"btn btn-sm btn-icon noSwipe z-depth-1 animated fadeInUpBig boton_border_color\">
                                        <span class=\"btn-inner--icon\"><i class=\"fas fa-info-circle\"></i></span>
                                        <span class=\"btn-inner--text\">Más información</span>
                                    </a>
                                    ";
                        }
                        // line 68
                        echo "
                                </div>
                            ";
                    }
                    // line 71
                    echo "                            </div>

                        </div>
                        ";
                } else {
                    // line 75
                    echo "                            ";
                    if ((twig_get_attribute($this->env, $this->getSourceContext(), $context["s"], "enlace", array()) != "")) {
                        // line 76
                        echo "                                <div class=\"container h-100 card-img-overlay contenido_carrusel\">
                                    <div class=\"row pb-3 align-items-end h-100\">
                                        <div class=\"col-12 px-0 pr-sm-4 text-center text-sm-right animated slideInRight\">
                                            <a href=\"";
                        // line 79
                        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), $context["s"], "enlace", array()), "html", null, true);
                        echo "\" class=\"btn btn-sm btn-icon noSwipe z-depth-1 boton_border_color\">
                                                <span class=\"btn-inner--icon\"><i class=\"fas fa-info-circle\"></i></span>
                                                <span class=\"btn-inner--text\">Más información</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            ";
                    }
                    // line 87
                    echo "                        ";
                }
                // line 88
                echo "
                    </div>
                
                    ";
                // line 91
                $context["cont"] = (($context["cont"] ?? null) + 1);
                // line 92
                echo "
                ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['s'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 94
            echo "
            ";
        }
        // line 96
        echo "
        </div>
    </div>

    <div class=\"container rounded-bottom informacion_compra_pagos\">
        <div class=\"row py-2 py-md-3 animated fadeIn\">
            <div class=\"col-6 col-lg-3 border-right border-bottom-0\">
                <div class=\"row p-0 p-sm-2 pl-lg-3\">
                    <div class=\"col-12 col-sm-2 svg_icon text-center text-sm-left\">
                        <i class=\"fas fa-shopping-bag\"></i>
                    </div>
                    <div class=\"col-12 col-sm-10 text-center text-sm-left\">
                        <span class=\"d-block text-truncate titulo\">
                            Compra en línea
                        </span>
                        <span class=\"d-block text-truncate descripcion\">
                            Es rápido y seguro
                        </span>
                    </div>
                </div>
            </div>
            <div class=\"col-6 col-lg-3 border-right border-bottom-0 eliminar_borde_md\">
                <div class=\"row p-0 p-sm-2 pl-lg-3\">
                    <div class=\"col-12 col-sm-2 svg_icon text-center text-sm-left\">
                        <i class=\"fab fa-paypal\"></i>
                    </div>
                    <div class=\"col-12 col-sm-10 text-center text-sm-left\">
                        <span class=\"d-block text-truncate titulo\">
                            Paga con PayPal
                        </span>
                        <span class=\"d-block text-truncate descripcion\">
                            Desde el sitio oficial
                        </span>
                    </div>
                </div>
            </div>
            <div class=\"col-6 col-lg-3 border-right border-bottom-0\">
                <div class=\"row p-0 p-sm-2 pl-lg-3\">
                    <div class=\"col-12 col-sm-2 svg_icon text-center text-sm-left\">
                        <i class=\"fas fa-credit-card\"></i>
                    </div>
                    <div class=\"col-12 col-sm-10 text-center text-sm-left\">
                        <span class=\"d-block text-truncate titulo\">
                            Paga con Payu
                        </span>
                        <span class=\"d-block text-truncate descripcion\">
                            Desde el sitio oficial
                        </span>
                    </div>
                </div>
            </div>
            <div class=\"col-6 col-lg-3\">
                <div class=\"row p-0 p-sm-2 pl-lg-3\">
                    <div class=\"col-12 col-sm-2 svg_icon text-center text-sm-left\">
                        <i class=\"fas fa-shipping-fast\"></i>
                    </div>
                    <div class=\"col-12 col-sm-10 text-center text-sm-left\">
                        <span class=\"d-block text-truncate titulo\">
                            Entrega garantizada
                        </span>
                        <span class=\"d-block text-truncate descripcion\">
                            De 5 a 10 días habiles
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</section>";
    }

    public function getTemplateName()
    {
        return "home/slides.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  238 => 96,  234 => 94,  227 => 92,  225 => 91,  220 => 88,  217 => 87,  206 => 79,  201 => 76,  198 => 75,  192 => 71,  187 => 68,  178 => 63,  176 => 62,  170 => 59,  166 => 58,  162 => 57,  157 => 55,  153 => 54,  143 => 51,  138 => 50,  128 => 47,  124 => 46,  120 => 44,  111 => 39,  109 => 38,  103 => 35,  99 => 34,  95 => 33,  90 => 31,  86 => 30,  83 => 29,  81 => 28,  75 => 24,  73 => 23,  66 => 20,  60 => 18,  57 => 17,  54 => 16,  51 => 15,  49 => 14,  46 => 13,  42 => 12,  39 => 11,  36 => 10,  34 => 9,  31 => 8,  27 => 6,  25 => 5,  19 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "home/slides.twig", "C:\\xampp\\htdocs\\website\\app\\templates\\home\\slides.twig");
    }
}
