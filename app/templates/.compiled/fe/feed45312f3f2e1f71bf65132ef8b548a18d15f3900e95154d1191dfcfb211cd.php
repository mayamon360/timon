<?php

/* informacion/informacion.twig */
class __TwigTemplate_160add82df4dc2ba74c194f7ad868fb798cb68dbcbee5ec1e732190e28818cf0 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("overall/layout", "informacion/informacion.twig", 1);
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
        echo "
    <main class=\"container-fluid\">
    
    </main>
    
";
    }

    public function getTemplateName()
    {
        return "informacion/informacion.twig";
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

    <main class=\"container-fluid\">
    
    </main>
    
{% endblock %}", "informacion/informacion.twig", "C:\\xampp\\htdocs\\website\\app\\templates\\informacion\\informacion.twig");
    }
}
