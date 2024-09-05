<?php

/* error/404.twig */
class __TwigTemplate_2b59230d8b81db5cf0d1484ac44357def13fef3db4f19533cb31809d4c235674 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("overall/layout", "error/404.twig", 1);
        $this->blocks = array(
            'appBody' => array($this, 'block_appBody'),
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

    // line 2
    public function block_appBody($context, array $blocks = array())
    {
        // line 3
        echo "    <main>
        <div class=\"container py-5 text-center\">
            <img src=\"http://localhost/pagina/vistas/img/plantilla/404.svg\" class=\"animated bounceIn\" height=\"100\">
            <h1 class=\"mt-5 animated flash\" style=\"font-weight:500; color:var(--red)\">ERROR 404</h1>
            <p class=\"animated fadeIn\" style=\"color:var(--textAlternative);\">¡La página solicitada no ha sido encontrada!</p>
        </div>
    </main>
";
    }

    public function getTemplateName()
    {
        return "error/404.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  31 => 3,  28 => 2,  11 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("{% extends 'overall/layout' %}
{% block appBody %}
    <main>
        <div class=\"container py-5 text-center\">
            <img src=\"http://localhost/pagina/vistas/img/plantilla/404.svg\" class=\"animated bounceIn\" height=\"100\">
            <h1 class=\"mt-5 animated flash\" style=\"font-weight:500; color:var(--red)\">ERROR 404</h1>
            <p class=\"animated fadeIn\" style=\"color:var(--textAlternative);\">¡La página solicitada no ha sido encontrada!</p>
        </div>
    </main>
{% endblock %}
", "error/404.twig", "C:\\xampp\\htdocs\\website\\app\\templates\\error\\404.twig");
    }
}
