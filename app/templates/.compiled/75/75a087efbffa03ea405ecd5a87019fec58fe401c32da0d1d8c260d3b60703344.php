<?php

/* editorial/editorial.twig */
class __TwigTemplate_023914a94f3f97d90ca6689d3ada64f383fa7c505a0317032698d8171f4f483e extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("overall/layout", "editorial/editorial.twig", 1);
        $this->blocks = array(
            'appTitle' => array($this, 'block_appTitle'),
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
        echo "    <title>Editorial ";
        echo twig_escape_filter($this->env, ($context["editorial"] ?? null), "html", null, true);
        echo "</title>
";
    }

    // line 7
    public function block_appBody($context, array $blocks = array())
    {
        // line 8
        echo "
    <main class=\"container mt-2 destacados\">
        <input type=\"hidden\" value=\"";
        // line 10
        echo twig_escape_filter($this->env, ($context["metodo"] ?? null), "html", null, true);
        echo "\" id=\"metodo\">
        <div class=\"row px-0 align-items-center\">
            <div class=\"col-12 col-sm-6 col-md-7 col-lg-8\">
                <p class=\"m-0 p-0 text-muted font-weight-bold animated fadeIn\"><span class=\"coincidencias\"></span></p>
                <h1 class=\"m-0 p-0 animated fadeIn titulo_destacado\"><i class=\"fas fa-landmark\"></i> ";
        // line 14
        echo twig_escape_filter($this->env, ($context["editorial"] ?? null), "html", null, true);
        echo "</h1>
\t\t\t\t<div class=\"my-1 animated fadeIn separador separador_color\"></div>
            </div>
            <div class=\"col-12 col-sm-6 col-md-5 col-lg-4 text-right pt-3 pt-md-0\">
                <div class=\"input-group input-group-sm mb-0 animated fadeIn my-auto\">
                    <div class=\"input-group-prepend\">
                        <label class=\"input-group-text text-uppercase py-1\" for=\"ordenar_resultados\"><small>Ordenar por</small></label>
                    </div>
                    <select class=\"custom-select custom-select-sm\" id=\"ordenar_resultados\">
                        <option value=\"relevancia\">Relevancia</option>
                        <optgroup label=\"Nombre\">
                            <option value = \"nombre\">Nombre de la A-Z</option>
                            <option value = \"nombre_desc\">Nombre de la Z-A</option>
                        </optgroup>
                        <optgroup label=\"Precio\">
                            <option value = \"precio\">Precio de menor a mayor</option>
                            <option value = \"precio_desc\">Precio de mayor a menor</option>
                        </optgroup>

                    </select>
                </div>
            </div>

            <div class=\"col-12 mt-2\">
                <div class=\"row\" id=\"resultados\">
                    
                </div>
            </div>

            <div class=\"col-12 mt-5\">
                <div class=\"text-center div_cargando\">
                    <button type=\"button\" class=\"btn btn-sm boton_negro\" id=\"boton_cargar_mas\">
                        <span class=\"spinner-grow spinner-grow-sm d-none\" role=\"status\" aria-hidden=\"true\"></span>
                        Mostrar más resultados
                    </button>
                    <p class=\"text-muted d-none animated flash div_cargando_texto\"></p>
                </div>
            </div>

        </div>
    </main>
    
";
    }

    // line 58
    public function block_appFooter($context, array $blocks = array())
    {
        // line 59
        echo "    <script src=\"assets/jscontrollers/editorial/editorial.js\"></script>
";
    }

    public function getTemplateName()
    {
        return "editorial/editorial.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  104 => 59,  101 => 58,  54 => 14,  47 => 10,  43 => 8,  40 => 7,  33 => 4,  30 => 3,  11 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("{% extends 'overall/layout' %}

{% block appTitle %}
    <title>Editorial {{editorial}}</title>
{% endblock %}

{% block appBody %}

    <main class=\"container mt-2 destacados\">
        <input type=\"hidden\" value=\"{{metodo}}\" id=\"metodo\">
        <div class=\"row px-0 align-items-center\">
            <div class=\"col-12 col-sm-6 col-md-7 col-lg-8\">
                <p class=\"m-0 p-0 text-muted font-weight-bold animated fadeIn\"><span class=\"coincidencias\"></span></p>
                <h1 class=\"m-0 p-0 animated fadeIn titulo_destacado\"><i class=\"fas fa-landmark\"></i> {{editorial}}</h1>
\t\t\t\t<div class=\"my-1 animated fadeIn separador separador_color\"></div>
            </div>
            <div class=\"col-12 col-sm-6 col-md-5 col-lg-4 text-right pt-3 pt-md-0\">
                <div class=\"input-group input-group-sm mb-0 animated fadeIn my-auto\">
                    <div class=\"input-group-prepend\">
                        <label class=\"input-group-text text-uppercase py-1\" for=\"ordenar_resultados\"><small>Ordenar por</small></label>
                    </div>
                    <select class=\"custom-select custom-select-sm\" id=\"ordenar_resultados\">
                        <option value=\"relevancia\">Relevancia</option>
                        <optgroup label=\"Nombre\">
                            <option value = \"nombre\">Nombre de la A-Z</option>
                            <option value = \"nombre_desc\">Nombre de la Z-A</option>
                        </optgroup>
                        <optgroup label=\"Precio\">
                            <option value = \"precio\">Precio de menor a mayor</option>
                            <option value = \"precio_desc\">Precio de mayor a menor</option>
                        </optgroup>

                    </select>
                </div>
            </div>

            <div class=\"col-12 mt-2\">
                <div class=\"row\" id=\"resultados\">
                    
                </div>
            </div>

            <div class=\"col-12 mt-5\">
                <div class=\"text-center div_cargando\">
                    <button type=\"button\" class=\"btn btn-sm boton_negro\" id=\"boton_cargar_mas\">
                        <span class=\"spinner-grow spinner-grow-sm d-none\" role=\"status\" aria-hidden=\"true\"></span>
                        Mostrar más resultados
                    </button>
                    <p class=\"text-muted d-none animated flash div_cargando_texto\"></p>
                </div>
            </div>

        </div>
    </main>
    
{% endblock %}

{% block appFooter %}
    <script src=\"assets/jscontrollers/editorial/editorial.js\"></script>
{% endblock %}", "editorial/editorial.twig", "C:\\xampp\\htdocs\\website\\app\\templates\\editorial\\editorial.twig");
    }
}
