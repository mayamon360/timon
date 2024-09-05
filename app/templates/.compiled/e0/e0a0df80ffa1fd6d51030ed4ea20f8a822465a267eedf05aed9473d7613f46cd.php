<?php

/* home/slides.twig */
class __TwigTemplate_246acde1319689d9320c34f4143337d33a09df5d9d6d323fbe9336d2aaaa21d6 extends Twig_Template
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
        echo "<section class=\"container-fluid px-0 pt-0 pb-0 pt-sm-5\">
<section class=\"container p-0 rounded-bottom z-depth-3\">
    <div id=\"carrusel_superior\" class=\"carousel slide carousel-fade\" data-ride=\"carousel\" data-interval=\"5000\" data-pause=\"hover\">
        <div class=\"carousel-inner rounded-top\">";
        // line 5
        if (twig_test_empty(($context["slides"] ?? null))) {
            // line 6
            echo "                ...";
        } else {
            // line 9
            $context["cont"] = 1;
            // line 10
            $context["active"] = " active";
            // line 12
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(($context["slides"] ?? null));
            foreach ($context['_seq'] as $context["_key"] => $context["s"]) {
                // line 14
                if ((($context["cont"] ?? null) != 1)) {
                    // line 15
                    $context["active"] = "";
                }
                // line 17
                echo "
                    <div class=\"carousel-item";
                // line 18
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), $context["s"], "tipo", array()), "html", null, true);
                echo twig_escape_filter($this->env, ($context["active"] ?? null), "html", null, true);
                echo "\">

                        <img src=\"";
                // line 20
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), $context["s"], "fondo", array()), "html", null, true);
                echo "\">
                        <span class=\"mask bg-dark alpha-1\"></span>";
                // line 23
                if ((twig_get_attribute($this->env, $this->getSourceContext(), $context["s"], "tipo", array()) != "tipo_cinco")) {
                    // line 24
                    echo "
                        <div class=\"container h-100 card-img-overlay contenido_carrusel\">
                            <div class=\"row h-100\">";
                    // line 28
                    if (((twig_get_attribute($this->env, $this->getSourceContext(), $context["s"], "tipo", array()) == "tipo_uno") || (twig_get_attribute($this->env, $this->getSourceContext(), $context["s"], "tipo", array()) == "tipo_tres"))) {
                        // line 29
                        echo "                                <div class=\"col-8 px-5 p-sm-0 col-sm-6 col-md-6 my-auto text-center text-sm-right\">
                                    <h1 class=\"p-0 text-truncate animated";
                        // line 30
                        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), $context["s"], "animatedTitulo", array()), "html", null, true);
                        echo " titulo_carrusel\">
                                        <span class=\"rounded\">";
                        // line 31
                        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), $context["s"], "titulo", array()), "html", null, true);
                        echo "</span>
                                    </h1>
                                    <div class=\"my-1 mx-auto mx-sm-0 ml-sm-auto animated";
                        // line 33
                        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), $context["s"], "animatedTitulo", array()), "html", null, true);
                        echo " separador separador_blanco\"></div>
                                    <p class=\"mt-0 mt-lg-4 rounded animated";
                        // line 34
                        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), $context["s"], "animatedTexto", array()), "html", null, true);
                        echo " text-center descripcion_carrusel\">";
                        // line 35
                        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), $context["s"], "descripcion", array()), "html", null, true);
                        echo "
                                    </p>";
                        // line 38
                        if ((twig_get_attribute($this->env, $this->getSourceContext(), $context["s"], "enlace", array()) != "")) {
                            // line 39
                            echo "                                    <a href=\"";
                            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), $context["s"], "enlace", array()), "html", null, true);
                            echo "\" class=\"btn btn-sm btn-icon noSwipe z-depth-1 animated fadeInUpBig boton_border_color\">
                                        <span class=\"btn-inner--icon\"><i class=\"fas fa-info-circle\"></i></span>
                                        <span class=\"btn-inner--text\">Más información</span>
                                    </a>";
                        }
                        // line 44
                        echo "
                                </div>
                                <div class=\"col-4 col-sm-6 col-md-6";
                        // line 46
                        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), $context["s"], "alignImg", array()), "html", null, true);
                        echo " text-center\">
                                    <img class=\"img-fluid z-depth-4";
                        // line 47
                        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), $context["s"], "roundedImg", array()), "html", null, true);
                        echo " animated";
                        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), $context["s"], "animatedImagen", array()), "html", null, true);
                        echo " imagen_carrusel\" src=\"";
                        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
                        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), $context["s"], "imagen", array()), "html", null, true);
                        echo "\">
                                </div>";
                    } else {
                        // line 50
                        echo "                                <div class=\"col-4 col-sm-6 col-md-6";
                        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), $context["s"], "alignImg", array()), "html", null, true);
                        echo " text-center\">
                                    <img class=\"img-fluid z-depth-4";
                        // line 51
                        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), $context["s"], "roundedImg", array()), "html", null, true);
                        echo " animated";
                        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), $context["s"], "animatedImagen", array()), "html", null, true);
                        echo " imagen_carrusel\" src=\"";
                        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
                        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), $context["s"], "imagen", array()), "html", null, true);
                        echo "\">
                                </div>
                                <div class=\"col-8 px-5 p-sm-0 col-sm-6 col-md-6 my-auto text-center text-sm-left\">
                                    <h1 class=\"p-0 text-truncate animated";
                        // line 54
                        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), $context["s"], "animatedTitulo", array()), "html", null, true);
                        echo " titulo_carrusel\">
                                        <span class=\"rounded\">";
                        // line 55
                        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), $context["s"], "titulo", array()), "html", null, true);
                        echo "</span>
                                    </h1>
                                    <div class=\"my-1 mx-auto mx-sm-0 mr-sm-auto animated";
                        // line 57
                        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), $context["s"], "animatedTitulo", array()), "html", null, true);
                        echo " separador separador_blanco\"></div>
                                    <p class=\" mt-0 mt-lg-4 rounded animated";
                        // line 58
                        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), $context["s"], "animatedTexto", array()), "html", null, true);
                        echo " text-center descripcion_carrusel\">";
                        // line 59
                        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), $context["s"], "descripcion", array()), "html", null, true);
                        echo "
                                    </p>";
                        // line 62
                        if ((twig_get_attribute($this->env, $this->getSourceContext(), $context["s"], "enlace", array()) != "")) {
                            // line 63
                            echo "                                    <a href=\"";
                            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), $context["s"], "enlace", array()), "html", null, true);
                            echo "\" class=\"btn btn-sm btn-icon noSwipe z-depth-1 animated fadeInUpBig boton_border_color\">
                                        <span class=\"btn-inner--icon\"><i class=\"fas fa-info-circle\"></i></span>
                                        <span class=\"btn-inner--text\">Más información</span>
                                    </a>";
                        }
                        // line 68
                        echo "
                                </div>";
                    }
                    // line 71
                    echo "                            </div>

                        </div>";
                } else {
                    // line 75
                    if ((twig_get_attribute($this->env, $this->getSourceContext(), $context["s"], "enlace", array()) != "")) {
                        // line 76
                        echo "                                <div class=\"container h-100 card-img-overlay contenido_carrusel\">
                                    <div class=\"row pb-2 align-items-end h-100\">
                                        <div class=\"col-12 px-0 pr-sm-4 text-right text-sm-right animated slideInUp\">
                                            <a href=\"";
                        // line 79
                        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), $context["s"], "enlace", array()), "html", null, true);
                        echo "\" class=\"btn btn-sm btn-icon noSwipe z-depth-1 boton_border_color boton_enlace_slider\">
                                                <span class=\"btn-inner--icon\"><i class=\"fas fa-info-circle\"></i></span>
                                                <span class=\"btn-inner--text ml-0\">Más información</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>";
                    }
                }
                // line 88
                echo "
                    </div>";
                // line 91
                $context["cont"] = (($context["cont"] ?? null) + 1);
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['s'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
        }
        // line 96
        echo "
        </div>
        
        <a class=\"carousel-control-prev d-none d-sm-inline-flex\" href=\"#carrusel_superior\" role=\"button\" data-slide=\"prev\">
            <span class=\"carousel-control-prev-icon\" aria-hidden=\"true\"></span>
            <span class=\"sr-only\">Previous</span>
        </a>
        <a class=\"carousel-control-next d-none d-sm-inline-flex\" href=\"#carrusel_superior\" role=\"button\" data-slide=\"next\">
            <span class=\"carousel-control-next-icon\" aria-hidden=\"true\"></span>
            <span class=\"sr-only\">Next</span>
        </a>
    </div>

    <div class=\"container rounded-bottom bg-white informacion_compra_pagos\">
        <div class=\"row py-2 py-md-3\">
            <div class=\"col-6 col-lg-3 border-right border-bottom-0\">
                <div class=\"row p-0 p-sm-2 pl-lg-3\">
                    <div class=\"col-12 col-sm-2 svg_icon text-center text-sm-left animated fadeInLeft\">
                        <i class=\"fas fa-school\"></i>
                    </div>
                    <div class=\"col-12 col-sm-10 text-center text-sm-left\">
                        <span class=\"d-block text-truncate titulo animated fadeInDown\">
                            Media superior:
                        </span>
                        <span class=\"d-block descripcion animated fadeInUp\" title=\"DGB, CoBaEm, Epoem, UAEM, Cbt, Cetis, Cebetis, Cecytem, Conalep, etc.\">
                            DGB, COBAEM, EPOEM, UAEM, CBT, CETIS, CEBETIS, CECYTEM, CONALEP
                        </span>
                    </div>
                </div>
            </div>
            <div class=\"col-6 col-lg-3 border-right border-bottom-0 eliminar_borde_md\">
                <div class=\"row p-0 p-sm-2 pl-lg-3\">
                    <div class=\"col-12 col-sm-2 svg_icon text-center text-sm-left animated fadeInLeft\">
                        <i class=\"fab fa-audible\"></i>
                    </div>
                    <div class=\"col-12 col-sm-10 text-center text-sm-left\">
                        <span class=\"d-block text-truncate titulo animated fadeInDown\">
                            Gran surtido en:
                        </span>
                        <span class=\"d-block descripcion animated fadeInUp\" title=\"Literatura clásica y contemporanea, Infantiles e Interés general\">
                            Literatura clásica y contemporánea, Infantiles e Interés general
                        </span>
                    </div>
                </div>
            </div>
            <div class=\"col-6 col-lg-3 border-right border-bottom-0\">
                <div class=\"row p-0 p-sm-2 pl-lg-3\">
                    <div class=\"col-12 col-sm-2 svg_icon text-center text-sm-left animated fadeInLeft\">
                        <i class=\"fas fa-book-reader\"></i>
                    </div>
                    <div class=\"col-12 col-sm-10 text-center text-sm-left\">
                        <span class=\"d-block text-truncate titulo animated fadeInDown\">
                            Contáctanos
                        </span>
                        <span class=\"d-block descripcion animated fadeInUp\">
                            Si no lo tenemos, te ayudamos a conseguirlo
                        </span>
                    </div>
                </div>
            </div>
            <div class=\"col-6 col-lg-3\">
                <div class=\"row p-0 p-sm-2 pl-lg-3\">
                    <div class=\"col-12 col-sm-2 svg_icon text-center text-sm-left animated fadeInLeft\">
                        <i class=\"fas fa-shopping-bag\"></i>
                    </div>
                    <div class=\"col-12 col-sm-10 text-center text-sm-left\">
                        <span class=\"d-block text-truncate titulo animated fadeInDown\">
                            Compra en línea
                        </span>
                        <span class=\"d-block descripcion animated fadeInUp\">
                            Paga con tarjeta (crédito/débito) o en efectivo con vale OXXO
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</section>
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
        return array (  198 => 96,  191 => 91,  188 => 88,  177 => 79,  172 => 76,  170 => 75,  165 => 71,  161 => 68,  153 => 63,  151 => 62,  147 => 59,  144 => 58,  140 => 57,  135 => 55,  131 => 54,  120 => 51,  115 => 50,  105 => 47,  101 => 46,  97 => 44,  89 => 39,  87 => 38,  83 => 35,  80 => 34,  76 => 33,  71 => 31,  67 => 30,  64 => 29,  62 => 28,  58 => 24,  56 => 23,  51 => 20,  45 => 18,  42 => 17,  39 => 15,  37 => 14,  33 => 12,  31 => 10,  29 => 9,  26 => 6,  24 => 5,  19 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "home/slides.twig", "/home4/eltimonl/public_html/app/templates/home/slides.twig");
    }
}
