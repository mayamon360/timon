<?php

/* home/destacados.twig */
class __TwigTemplate_0c940245a2b3a46adfa33d25e373b367ec7b4b7d66a3a0446b251e02b465bc2a extends Twig_Template
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
        echo "
    <section class=\"container py-2 py-sm-3 mt-5 bg-light rounded border destacados\">
        <div class=\"container px-3 p-sm-0\">
            <div class=\"row flex align-items-center\">
                <div class=\"col-7\">
                    <h1 class=\"m-0 p-0 animated zoomIn titulo_destacado\"><i class=\"fas fa-certificate\"></i> Los más nuevos</h1>
                    <div class=\"my-1 animated zoomInLeft separador separador_color\"></div>
                    <p class=\"m-0 d-none d-sm-block animated fadeInRight descripcion_destacado\">Conoce las novedades de los últimos 30 días</p>
                </div>
                <div class=\"col-5 text-right\">
                    <a class=\"animated zoomIn enlace_negro\" href=\"";
        // line 11
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "destacados/novedades\">
                        <i class=\"fas fa-angle-right mr-1 animated fadeInLeft infinite\"></i> VER TODOS
                    </a>
                </div>
            </div>
        </div>
    </section>
    <div class=\"container p-0 px-4 px-sm-0 my-4\">
        <div class=\"owl-carousel carrusel_libros_nuevos owl-theme\">
        ";
        // line 20
        echo ($context["novedades"] ?? null);
        echo "
        </div>
    </div>

    <section class=\"container py-2 py-sm-3 mt-5 bg-light rounded border destacados\">
        <div class=\"container px-3 p-sm-0\">
            <div class=\"row flex align-items-center\">
                <div class=\"col-7 pr-0\">
                    <h1 class=\"m-0 p-0 animated zoomIn titulo_destacado\"><i class=\"fas fa-certificate\"></i> Los más vendidos</h1>
                    <div class=\"my-1 animated zoomInLeft separador separador_color\"></div>
                    <p class=\"m-0 d-none d-sm-block animated fadeInRight descripcion_destacado\">Descubre los libros preferidos por nuestros lectores</p>
                </div>
                <div class=\"col-5 text-right\">
                    <a class=\"animated zoomIn enlace_negro\" href=\"";
        // line 33
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "destacados/mas-vendidos\">
                        <i class=\"fas fa-angle-right mr-1 animated fadeInLeft infinite\"></i> VER TODOS
                    </a>
                </div>
            </div>
        </div>
    </section>
    <div class=\"container p-0 px-4 px-sm-0 my-4\">
        <div class=\"owl-carousel carrusel_libros_mas_vendidos owl-theme\">
        ";
        // line 42
        echo ($context["mas_vendidos"] ?? null);
        echo "
        </div>
    </div>
";
    }

    public function getTemplateName()
    {
        return "home/destacados.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  71 => 42,  59 => 33,  43 => 20,  31 => 11,  19 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("
    <section class=\"container py-2 py-sm-3 mt-5 bg-light rounded border destacados\">
        <div class=\"container px-3 p-sm-0\">
            <div class=\"row flex align-items-center\">
                <div class=\"col-7\">
                    <h1 class=\"m-0 p-0 animated zoomIn titulo_destacado\"><i class=\"fas fa-certificate\"></i> Los más nuevos</h1>
                    <div class=\"my-1 animated zoomInLeft separador separador_color\"></div>
                    <p class=\"m-0 d-none d-sm-block animated fadeInRight descripcion_destacado\">Conoce las novedades de los últimos 30 días</p>
                </div>
                <div class=\"col-5 text-right\">
                    <a class=\"animated zoomIn enlace_negro\" href=\"{{config.build.url}}destacados/novedades\">
                        <i class=\"fas fa-angle-right mr-1 animated fadeInLeft infinite\"></i> VER TODOS
                    </a>
                </div>
            </div>
        </div>
    </section>
    <div class=\"container p-0 px-4 px-sm-0 my-4\">
        <div class=\"owl-carousel carrusel_libros_nuevos owl-theme\">
        {{novedades|raw}}
        </div>
    </div>

    <section class=\"container py-2 py-sm-3 mt-5 bg-light rounded border destacados\">
        <div class=\"container px-3 p-sm-0\">
            <div class=\"row flex align-items-center\">
                <div class=\"col-7 pr-0\">
                    <h1 class=\"m-0 p-0 animated zoomIn titulo_destacado\"><i class=\"fas fa-certificate\"></i> Los más vendidos</h1>
                    <div class=\"my-1 animated zoomInLeft separador separador_color\"></div>
                    <p class=\"m-0 d-none d-sm-block animated fadeInRight descripcion_destacado\">Descubre los libros preferidos por nuestros lectores</p>
                </div>
                <div class=\"col-5 text-right\">
                    <a class=\"animated zoomIn enlace_negro\" href=\"{{config.build.url}}destacados/mas-vendidos\">
                        <i class=\"fas fa-angle-right mr-1 animated fadeInLeft infinite\"></i> VER TODOS
                    </a>
                </div>
            </div>
        </div>
    </section>
    <div class=\"container p-0 px-4 px-sm-0 my-4\">
        <div class=\"owl-carousel carrusel_libros_mas_vendidos owl-theme\">
        {{mas_vendidos|raw}}
        </div>
    </div>
", "home/destacados.twig", "C:\\xampp\\htdocs\\website\\app\\templates\\home\\destacados.twig");
    }
}
