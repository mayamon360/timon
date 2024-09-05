<?php

/* home/home.twig */
class __TwigTemplate_482cdfc512d7b41b65c3b145f447e0a8cc27fd7c4e058c0a0800a309bb2ec8a2 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("overall/layout", "home/home.twig", 1);
        $this->blocks = array(
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
    public function block_appHeader($context, array $blocks = array())
    {
        // line 5
        echo "\t<link rel=\"stylesheet\" href=\"./assets/plantilla/inicio.css\">
";
    }

    // line 8
    public function block_appBody($context, array $blocks = array())
    {
        // line 9
        echo "
<main>

\t";
        // line 12
        $this->loadTemplate("home/slides", "home/home.twig", 12)->display($context);
        // line 13
        echo "\t
\t";
        // line 14
        if (twig_test_empty(($context["novedades"] ?? null))) {
            // line 15
            echo "\t";
        } else {
            // line 16
            echo "\t<section class=\"container-fluid p-0 pt-5 pb-2 mt-0 mt-sm-5 bg-light destacados\">
\t\t<div class=\"container px-4 p-sm-0\">
\t\t\t<div class=\"row flex align-items-center\">
\t\t\t\t<div class=\"col-7\">
\t\t\t\t\t<h1 class=\"m-0 p-0 animated zoomIn titulo_destacado\">Lo más nuevo</h1>
\t\t\t\t\t<p class=\"m-0 animated fadeInRight descripcion_destacado\">Conoce nuestras novedades</p>
\t\t\t\t\t<div class=\"my-1 animated zoomInLeft separador separador_color\"></div>
\t\t\t\t</div>
\t\t\t\t<div class=\"col-5 text-right\">
\t\t\t\t\t<a class=\"animated zoomIn enlace_negro\" href=\"";
            // line 25
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
            echo "destacados/novedades\">
\t\t\t\t\t\t<i class=\"fas fa-angle-right mr-1 animated fadeInLeft infinite\"></i> Ver todos
\t\t\t\t\t</a>
\t\t\t\t</div>
\t\t\t</div>
\t\t</div>
\t\t<div class=\"container px-2 px-sm-0 my-4\">
    \t\t<div class=\"owl-carousel carrusel_libros_nuevos owl-theme\">
    \t\t";
            // line 33
            echo ($context["novedades"] ?? null);
            echo "
    \t\t</div>
    \t</div>
\t</section>
\t";
        }
        // line 38
        echo "\t
\t<section class=\"container-fluid p-0 pb-2 mt-0 mt-5 destacados\">
\t\t<div class=\"container px-4 p-sm-0\">
\t\t\t<div class=\"row flex align-items-center\">
\t\t\t\t<div class=\"col-7\">
\t\t\t\t\t<h1 class=\"m-0 p-0 animated zoomIn titulo_destacado\">Stephen King</h1>
\t\t\t\t\t<p class=\"m-0 animated fadeInRight descripcion_destacado d-block d-md-none\">Los monstruos son reales y los fantasmas también. Viven dentro de nosotros y a veces ellos ganan...</p>
\t\t\t\t\t<p class=\"m-0 animated fadeInRight descripcion_destacado d-none d-md-block\">Los monstruos son reales y los fantasmas también.<br> Viven dentro de nosotros y a veces ellos ganan...</p>
\t\t\t\t\t<div class=\"my-1 animated zoomInLeft separador separador_color\"></div>
\t\t\t\t</div>
\t\t\t\t<div class=\"col-5 text-right\">
\t\t\t\t\t<a class=\"animated zoomIn enlace_negro\" href=\"";
        // line 49
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "autor/stephen-king\">
\t\t\t\t\t\t<i class=\"fas fa-angle-right mr-1 animated fadeInLeft infinite\"></i> Ver todos
\t\t\t\t\t</a>
\t\t\t\t</div>
\t\t\t</div>
\t\t</div>
\t\t<div class=\"container px-2 px-sm-0 my-4\">
    \t\t<div class=\"owl-carousel carrusel_varios owl-theme\">
    \t\t";
        // line 57
        echo ($context["stephen"] ?? null);
        echo "
    \t\t</div>
    \t</div>
\t</section>
\t
\t<section class=\"container-fluid p-0 pt-5 pb-2 mt-0 bg-light destacados\">
\t\t<div class=\"container px-4 p-sm-0\">
\t\t\t<div class=\"row flex align-items-center\">
\t\t\t\t<div class=\"col-7\">
\t\t\t\t\t<h1 class=\"m-0 p-0 animated zoomIn titulo_destacado\">Paulo Coelho</h1>
\t\t\t\t\t<p class=\"m-0 animated fadeInRight descripcion_destacado d-block d-md-none\">Todo me dice que estoy a punto de cometer la decisión incorrecta, pero cometer errores es parte de la vida.</p>
\t\t\t\t\t<p class=\"m-0 animated fadeInRight descripcion_destacado d-none d-md-block\">Todo me dice que estoy a punto de cometer la decisión incorrecta,<br> pero cometer errores es parte de la vida.</p>
\t\t\t\t\t<div class=\"my-1 animated zoomInLeft separador separador_color\"></div>
\t\t\t\t</div>
\t\t\t\t<div class=\"col-5 text-right\">
\t\t\t\t\t<a class=\"animated zoomIn enlace_negro\" href=\"";
        // line 72
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "autor/paulo-coelho\">
\t\t\t\t\t\t<i class=\"fas fa-angle-right mr-1 animated fadeInLeft infinite\"></i> Ver todos
\t\t\t\t\t</a>
\t\t\t\t</div>
\t\t\t</div>
\t\t</div>
\t\t<div class=\"container px-2 px-sm-0 my-4\">
    \t\t<div class=\"owl-carousel carrusel_varios owl-theme\">
    \t\t";
        // line 80
        echo ($context["paulo"] ?? null);
        echo "
    \t\t</div>
    \t</div>
\t</section>
\t
\t<section class=\"container-fluid p-0 pb-2 mt-0 mt-5 destacados\">
\t\t<div class=\"container px-4 p-sm-0\">
\t\t\t<div class=\"row flex align-items-center\">
\t\t\t\t<div class=\"col-7\">
\t\t\t\t\t<h1 class=\"m-0 p-0 animated zoomIn titulo_destacado\">Carlos Fuentes</h1>
\t\t\t\t\t<p class=\"m-0 animated fadeInRight descripcion_destacado d-block d-md-none\">No existe la libertad, sino la búsqueda de la libertad, y esa búsqueda es la que nos hace libres.</p>
\t\t\t\t\t<p class=\"m-0 animated fadeInRight descripcion_destacado d-none d-md-block\">No existe la libertad, sino la búsqueda de la libertad,<br> y esa búsqueda es la que nos hace libres.</p>
\t\t\t\t\t<div class=\"my-1 animated zoomInLeft separador separador_color\"></div>
\t\t\t\t</div>
\t\t\t\t<div class=\"col-5 text-right\">
\t\t\t\t\t<a class=\"animated zoomIn enlace_negro\" href=\"";
        // line 95
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "autor/carlos-fuentes\">
\t\t\t\t\t\t<i class=\"fas fa-angle-right mr-1 animated fadeInLeft infinite\"></i> Ver todos
\t\t\t\t\t</a>
\t\t\t\t</div>
\t\t\t</div>
\t\t</div>
\t\t<div class=\"container px-2 px-sm-0 my-4\">
    \t\t<div class=\"owl-carousel carrusel_varios owl-theme\">
    \t\t";
        // line 103
        echo ($context["carlos"] ?? null);
        echo "
    \t\t</div>
    \t</div>
\t</section>
\t
\t<section class=\"container-fluid p-0 pt-5 pb-2 mt-0 bg-light destacados\">
\t\t<div class=\"container px-4 p-sm-0\">
\t\t\t<div class=\"row flex align-items-center\">
\t\t\t\t<div class=\"col-7\">
\t\t\t\t\t<h1 class=\"m-0 p-0 animated zoomIn titulo_destacado\">Dan Brown</h1>
\t\t\t\t\t<p class=\"m-0 animated fadeInRight descripcion_destacado\">Todo es posible. Lo imposible simplemente nos lleva más tiempo</p>
\t\t\t\t\t<div class=\"my-1 animated zoomInLeft separador separador_color\"></div>
\t\t\t\t</div>
\t\t\t\t<div class=\"col-5 text-right\">
\t\t\t\t\t<a class=\"animated zoomIn enlace_negro\" href=\"";
        // line 117
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "autor/dan-brown\">
\t\t\t\t\t\t<i class=\"fas fa-angle-right mr-1 animated fadeInLeft infinite\"></i> Ver todos
\t\t\t\t\t</a>
\t\t\t\t</div>
\t\t\t</div>
\t\t</div>
\t\t<div class=\"container px-2 px-sm-0 my-4\">
    \t\t<div class=\"owl-carousel carrusel_varios owl-theme\">
    \t\t";
        // line 125
        echo ($context["dan"] ?? null);
        echo "
    \t\t</div>
    \t</div>
\t</section>
\t
\t<section class=\"container-fluid p-0 pb-2 mt-0 mt-5 destacados\">
\t\t<div class=\"container px-4 p-sm-0\">
\t\t\t<div class=\"row flex align-items-center\">
\t\t\t\t<div class=\"col-7\">
\t\t\t\t\t<h1 class=\"m-0 p-0 animated zoomIn titulo_destacado\">Gravity Falls</h1>
\t\t\t\t\t<p class=\"m-0 animated fadeInRight descripcion_destacado d-block d-md-none\">El tío Stan invita a Dipper y Mabel Pines a pasar el verano en el misterioso pueblo de Gravity Falls, Oregón.</p>
\t\t\t\t\t<p class=\"m-0 animated fadeInRight descripcion_destacado d-none d-md-block\">El tío Stan invita a Dipper y Mabel Pines a pasar el verano<br> en el misterioso pueblo de Gravity Falls, Oregón.</p>
\t\t\t\t\t<div class=\"my-1 animated zoomInLeft separador separador_color\"></div>
\t\t\t\t</div>
\t\t\t\t<div class=\"col-5 text-right\">
\t\t\t\t\t<a class=\"animated zoomIn enlace_negro\" href=\"";
        // line 140
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "autor/alex-hirsch\">
\t\t\t\t\t\t<i class=\"fas fa-angle-right mr-1 animated fadeInLeft infinite\"></i> Ver todos
\t\t\t\t\t</a>
\t\t\t\t</div>
\t\t\t</div>
\t\t</div>
\t\t<div class=\"container px-2 px-sm-0 my-4\">
    \t\t<div class=\"owl-carousel carrusel_varios owl-theme\">
    \t\t";
        // line 148
        echo ($context["alex"] ?? null);
        echo "
    \t\t</div>
    \t</div>
\t</section>

\t";
        // line 153
        if (twig_test_empty(($context["mas_vendidos"] ?? null))) {
            // line 154
            echo "\t";
        } else {
            // line 155
            echo "\t<section class=\"container-fluid p-0 pt-5 pb-2 bg-light destacados\">
\t\t<div class=\"container px-4 p-sm-0\">
\t\t\t<div class=\"row flex align-items-center\">
\t\t\t\t<div class=\"col-7 pr-0\">
\t\t\t\t\t<h1 class=\"m-0 p-0 animated zoomIn titulo_destacado\">Lo más vendido</h1>
\t\t\t\t\t<p class=\"m-0 animated fadeInRight descripcion_destacado\">Descubre los ibros preferidos</p>
\t\t\t\t\t<div class=\"my-1 animated zoomInLeft separador separador_color\"></div>
\t\t\t\t</div>
\t\t\t\t<div class=\"col-5 text-right\">
\t\t\t\t\t<a class=\"animated zoomIn enlace_negro\" href=\"";
            // line 164
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
            echo "destacados/mas-vendidos\">
\t\t\t\t\t\t<i class=\"fas fa-angle-right mr-1 animated fadeInLeft infinite\"></i> Ver todos
\t\t\t\t\t</a>
\t\t\t\t</div>
\t\t\t</div>
\t\t</div>
    \t<div class=\"container px-2 px-sm-0 my-4\">
    \t\t<div class=\"owl-carousel carrusel_libros_mas_vendidos owl-theme\">
    \t\t";
            // line 172
            echo ($context["mas_vendidos"] ?? null);
            echo "
    \t\t</div>
    \t</div>
\t</section>
\t";
        }
        // line 177
        echo "
\t";
        // line 197
        echo "
</main>

";
    }

    // line 202
    public function block_appFooter($context, array $blocks = array())
    {
        // line 203
        echo "\t<script src=\"./assets/jscontrollers/inicio/inicio.js\"></script>
";
    }

    public function getTemplateName()
    {
        return "home/home.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  281 => 203,  278 => 202,  271 => 197,  268 => 177,  260 => 172,  249 => 164,  238 => 155,  235 => 154,  233 => 153,  225 => 148,  214 => 140,  196 => 125,  185 => 117,  168 => 103,  157 => 95,  139 => 80,  128 => 72,  110 => 57,  99 => 49,  86 => 38,  78 => 33,  67 => 25,  56 => 16,  53 => 15,  51 => 14,  48 => 13,  46 => 12,  41 => 9,  38 => 8,  33 => 5,  30 => 3,  11 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("{% extends 'overall/layout' %}

{% block appHeader %}
{# Estilos personalizados para la página #}
\t<link rel=\"stylesheet\" href=\"./assets/plantilla/inicio.css\">
{% endblock %}

{% block appBody %}

<main>

\t{% include 'home/slides' %}
\t
\t{% if novedades is empty %}
\t{% else %}
\t<section class=\"container-fluid p-0 pt-5 pb-2 mt-0 mt-sm-5 bg-light destacados\">
\t\t<div class=\"container px-4 p-sm-0\">
\t\t\t<div class=\"row flex align-items-center\">
\t\t\t\t<div class=\"col-7\">
\t\t\t\t\t<h1 class=\"m-0 p-0 animated zoomIn titulo_destacado\">Lo más nuevo</h1>
\t\t\t\t\t<p class=\"m-0 animated fadeInRight descripcion_destacado\">Conoce nuestras novedades</p>
\t\t\t\t\t<div class=\"my-1 animated zoomInLeft separador separador_color\"></div>
\t\t\t\t</div>
\t\t\t\t<div class=\"col-5 text-right\">
\t\t\t\t\t<a class=\"animated zoomIn enlace_negro\" href=\"{{config.build.url}}destacados/novedades\">
\t\t\t\t\t\t<i class=\"fas fa-angle-right mr-1 animated fadeInLeft infinite\"></i> Ver todos
\t\t\t\t\t</a>
\t\t\t\t</div>
\t\t\t</div>
\t\t</div>
\t\t<div class=\"container px-2 px-sm-0 my-4\">
    \t\t<div class=\"owl-carousel carrusel_libros_nuevos owl-theme\">
    \t\t{{novedades|raw}}
    \t\t</div>
    \t</div>
\t</section>
\t{% endif %}
\t
\t<section class=\"container-fluid p-0 pb-2 mt-0 mt-5 destacados\">
\t\t<div class=\"container px-4 p-sm-0\">
\t\t\t<div class=\"row flex align-items-center\">
\t\t\t\t<div class=\"col-7\">
\t\t\t\t\t<h1 class=\"m-0 p-0 animated zoomIn titulo_destacado\">Stephen King</h1>
\t\t\t\t\t<p class=\"m-0 animated fadeInRight descripcion_destacado d-block d-md-none\">Los monstruos son reales y los fantasmas también. Viven dentro de nosotros y a veces ellos ganan...</p>
\t\t\t\t\t<p class=\"m-0 animated fadeInRight descripcion_destacado d-none d-md-block\">Los monstruos son reales y los fantasmas también.<br> Viven dentro de nosotros y a veces ellos ganan...</p>
\t\t\t\t\t<div class=\"my-1 animated zoomInLeft separador separador_color\"></div>
\t\t\t\t</div>
\t\t\t\t<div class=\"col-5 text-right\">
\t\t\t\t\t<a class=\"animated zoomIn enlace_negro\" href=\"{{config.build.url}}autor/stephen-king\">
\t\t\t\t\t\t<i class=\"fas fa-angle-right mr-1 animated fadeInLeft infinite\"></i> Ver todos
\t\t\t\t\t</a>
\t\t\t\t</div>
\t\t\t</div>
\t\t</div>
\t\t<div class=\"container px-2 px-sm-0 my-4\">
    \t\t<div class=\"owl-carousel carrusel_varios owl-theme\">
    \t\t{{stephen|raw}}
    \t\t</div>
    \t</div>
\t</section>
\t
\t<section class=\"container-fluid p-0 pt-5 pb-2 mt-0 bg-light destacados\">
\t\t<div class=\"container px-4 p-sm-0\">
\t\t\t<div class=\"row flex align-items-center\">
\t\t\t\t<div class=\"col-7\">
\t\t\t\t\t<h1 class=\"m-0 p-0 animated zoomIn titulo_destacado\">Paulo Coelho</h1>
\t\t\t\t\t<p class=\"m-0 animated fadeInRight descripcion_destacado d-block d-md-none\">Todo me dice que estoy a punto de cometer la decisión incorrecta, pero cometer errores es parte de la vida.</p>
\t\t\t\t\t<p class=\"m-0 animated fadeInRight descripcion_destacado d-none d-md-block\">Todo me dice que estoy a punto de cometer la decisión incorrecta,<br> pero cometer errores es parte de la vida.</p>
\t\t\t\t\t<div class=\"my-1 animated zoomInLeft separador separador_color\"></div>
\t\t\t\t</div>
\t\t\t\t<div class=\"col-5 text-right\">
\t\t\t\t\t<a class=\"animated zoomIn enlace_negro\" href=\"{{config.build.url}}autor/paulo-coelho\">
\t\t\t\t\t\t<i class=\"fas fa-angle-right mr-1 animated fadeInLeft infinite\"></i> Ver todos
\t\t\t\t\t</a>
\t\t\t\t</div>
\t\t\t</div>
\t\t</div>
\t\t<div class=\"container px-2 px-sm-0 my-4\">
    \t\t<div class=\"owl-carousel carrusel_varios owl-theme\">
    \t\t{{paulo|raw}}
    \t\t</div>
    \t</div>
\t</section>
\t
\t<section class=\"container-fluid p-0 pb-2 mt-0 mt-5 destacados\">
\t\t<div class=\"container px-4 p-sm-0\">
\t\t\t<div class=\"row flex align-items-center\">
\t\t\t\t<div class=\"col-7\">
\t\t\t\t\t<h1 class=\"m-0 p-0 animated zoomIn titulo_destacado\">Carlos Fuentes</h1>
\t\t\t\t\t<p class=\"m-0 animated fadeInRight descripcion_destacado d-block d-md-none\">No existe la libertad, sino la búsqueda de la libertad, y esa búsqueda es la que nos hace libres.</p>
\t\t\t\t\t<p class=\"m-0 animated fadeInRight descripcion_destacado d-none d-md-block\">No existe la libertad, sino la búsqueda de la libertad,<br> y esa búsqueda es la que nos hace libres.</p>
\t\t\t\t\t<div class=\"my-1 animated zoomInLeft separador separador_color\"></div>
\t\t\t\t</div>
\t\t\t\t<div class=\"col-5 text-right\">
\t\t\t\t\t<a class=\"animated zoomIn enlace_negro\" href=\"{{config.build.url}}autor/carlos-fuentes\">
\t\t\t\t\t\t<i class=\"fas fa-angle-right mr-1 animated fadeInLeft infinite\"></i> Ver todos
\t\t\t\t\t</a>
\t\t\t\t</div>
\t\t\t</div>
\t\t</div>
\t\t<div class=\"container px-2 px-sm-0 my-4\">
    \t\t<div class=\"owl-carousel carrusel_varios owl-theme\">
    \t\t{{carlos|raw}}
    \t\t</div>
    \t</div>
\t</section>
\t
\t<section class=\"container-fluid p-0 pt-5 pb-2 mt-0 bg-light destacados\">
\t\t<div class=\"container px-4 p-sm-0\">
\t\t\t<div class=\"row flex align-items-center\">
\t\t\t\t<div class=\"col-7\">
\t\t\t\t\t<h1 class=\"m-0 p-0 animated zoomIn titulo_destacado\">Dan Brown</h1>
\t\t\t\t\t<p class=\"m-0 animated fadeInRight descripcion_destacado\">Todo es posible. Lo imposible simplemente nos lleva más tiempo</p>
\t\t\t\t\t<div class=\"my-1 animated zoomInLeft separador separador_color\"></div>
\t\t\t\t</div>
\t\t\t\t<div class=\"col-5 text-right\">
\t\t\t\t\t<a class=\"animated zoomIn enlace_negro\" href=\"{{config.build.url}}autor/dan-brown\">
\t\t\t\t\t\t<i class=\"fas fa-angle-right mr-1 animated fadeInLeft infinite\"></i> Ver todos
\t\t\t\t\t</a>
\t\t\t\t</div>
\t\t\t</div>
\t\t</div>
\t\t<div class=\"container px-2 px-sm-0 my-4\">
    \t\t<div class=\"owl-carousel carrusel_varios owl-theme\">
    \t\t{{dan|raw}}
    \t\t</div>
    \t</div>
\t</section>
\t
\t<section class=\"container-fluid p-0 pb-2 mt-0 mt-5 destacados\">
\t\t<div class=\"container px-4 p-sm-0\">
\t\t\t<div class=\"row flex align-items-center\">
\t\t\t\t<div class=\"col-7\">
\t\t\t\t\t<h1 class=\"m-0 p-0 animated zoomIn titulo_destacado\">Gravity Falls</h1>
\t\t\t\t\t<p class=\"m-0 animated fadeInRight descripcion_destacado d-block d-md-none\">El tío Stan invita a Dipper y Mabel Pines a pasar el verano en el misterioso pueblo de Gravity Falls, Oregón.</p>
\t\t\t\t\t<p class=\"m-0 animated fadeInRight descripcion_destacado d-none d-md-block\">El tío Stan invita a Dipper y Mabel Pines a pasar el verano<br> en el misterioso pueblo de Gravity Falls, Oregón.</p>
\t\t\t\t\t<div class=\"my-1 animated zoomInLeft separador separador_color\"></div>
\t\t\t\t</div>
\t\t\t\t<div class=\"col-5 text-right\">
\t\t\t\t\t<a class=\"animated zoomIn enlace_negro\" href=\"{{config.build.url}}autor/alex-hirsch\">
\t\t\t\t\t\t<i class=\"fas fa-angle-right mr-1 animated fadeInLeft infinite\"></i> Ver todos
\t\t\t\t\t</a>
\t\t\t\t</div>
\t\t\t</div>
\t\t</div>
\t\t<div class=\"container px-2 px-sm-0 my-4\">
    \t\t<div class=\"owl-carousel carrusel_varios owl-theme\">
    \t\t{{alex|raw}}
    \t\t</div>
    \t</div>
\t</section>

\t{% if mas_vendidos is empty %}
\t{% else %}
\t<section class=\"container-fluid p-0 pt-5 pb-2 bg-light destacados\">
\t\t<div class=\"container px-4 p-sm-0\">
\t\t\t<div class=\"row flex align-items-center\">
\t\t\t\t<div class=\"col-7 pr-0\">
\t\t\t\t\t<h1 class=\"m-0 p-0 animated zoomIn titulo_destacado\">Lo más vendido</h1>
\t\t\t\t\t<p class=\"m-0 animated fadeInRight descripcion_destacado\">Descubre los ibros preferidos</p>
\t\t\t\t\t<div class=\"my-1 animated zoomInLeft separador separador_color\"></div>
\t\t\t\t</div>
\t\t\t\t<div class=\"col-5 text-right\">
\t\t\t\t\t<a class=\"animated zoomIn enlace_negro\" href=\"{{config.build.url}}destacados/mas-vendidos\">
\t\t\t\t\t\t<i class=\"fas fa-angle-right mr-1 animated fadeInLeft infinite\"></i> Ver todos
\t\t\t\t\t</a>
\t\t\t\t</div>
\t\t\t</div>
\t\t</div>
    \t<div class=\"container px-2 px-sm-0 my-4\">
    \t\t<div class=\"owl-carousel carrusel_libros_mas_vendidos owl-theme\">
    \t\t{{mas_vendidos|raw}}
    \t\t</div>
    \t</div>
\t</section>
\t{% endif %}

\t{#<div class=\"container p-2\">
\t\t<div class=\"row\">
\t\t\t<div class=\"col-five\">
\t\t\t\t
\t\t\t</div>
\t\t\t<div class=\"col-five\">
\t\t\t\t
\t\t\t</div>
\t\t\t<div class=\"col-five\">
\t\t\t\t
\t\t\t</div>
\t\t\t<div class=\"col-five\">
\t\t\t\t
\t\t\t</div>
\t\t\t<div class=\"col-five\">
\t\t\t\t
\t\t\t</div>
\t\t</div>
\t</div>#}

</main>

{% endblock %}

{% block appFooter %}
\t<script src=\"./assets/jscontrollers/inicio/inicio.js\"></script>
{% endblock %}", "home/home.twig", "/home4/eltimonl/public_html/app/templates/home/home.twig");
    }
}
