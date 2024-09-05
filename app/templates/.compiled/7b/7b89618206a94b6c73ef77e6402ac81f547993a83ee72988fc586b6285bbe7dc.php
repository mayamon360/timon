<?php

/* home/home.twig */
class __TwigTemplate_9aef51f7dbe96f7746c375bdc65be25753337488a4c7f716e72d76d3b86a904a extends Twig_Template
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
        echo "\t<link rel=\"stylesheet\" href=\"./assets/plantilla/inicio.css\">";
    }

    // line 8
    public function block_appBody($context, array $blocks = array())
    {
        // line 9
        echo "
<main>";
        // line 12
        $this->loadTemplate("home/slides", "home/home.twig", 12)->display($context);
        // line 14
        if (twig_test_empty(($context["novedades"] ?? null))) {
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
    \t\t<div class=\"owl-carousel carrusel_libros_nuevos owl-theme\">";
            // line 33
            echo ($context["novedades"] ?? null);
            echo "
    \t\t</div>
    \t</div>
\t</section>";
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
    \t\t<div class=\"owl-carousel carrusel_varios owl-theme\">";
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
    \t\t<div class=\"owl-carousel carrusel_varios owl-theme\">";
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
    \t\t<div class=\"owl-carousel carrusel_varios owl-theme\">";
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
    \t\t<div class=\"owl-carousel carrusel_varios owl-theme\">";
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
    \t\t<div class=\"owl-carousel carrusel_varios owl-theme\">";
        // line 148
        echo ($context["alex"] ?? null);
        echo "
    \t\t</div>
    \t</div>
\t</section>";
        // line 153
        if (twig_test_empty(($context["mas_vendidos"] ?? null))) {
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
    \t\t<div class=\"owl-carousel carrusel_libros_mas_vendidos owl-theme\">";
            // line 172
            echo ($context["mas_vendidos"] ?? null);
            echo "
    \t\t</div>
    \t</div>
\t</section>";
        }
        // line 197
        echo "\t
<!-- Modal -->";
        // line 221
        echo "
</main>";
    }

    // line 226
    public function block_appFooter($context, array $blocks = array())
    {
        // line 227
        echo "\t<script src=\"./assets/jscontrollers/inicio/inicio.js\"></script>
\t<script>
\t    
\t    \$(\"#anuncio\").modal(\"show\");
\t</script>";
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
        return array (  258 => 227,  255 => 226,  250 => 221,  247 => 197,  240 => 172,  230 => 164,  219 => 155,  216 => 153,  210 => 148,  200 => 140,  182 => 125,  172 => 117,  155 => 103,  145 => 95,  127 => 80,  117 => 72,  99 => 57,  89 => 49,  76 => 38,  69 => 33,  59 => 25,  48 => 16,  45 => 14,  43 => 12,  40 => 9,  37 => 8,  33 => 5,  30 => 3,  11 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "home/home.twig", "/home4/eltimonl/public_html/app/templates/home/home.twig");
    }
}
