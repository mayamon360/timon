<?php

/* overall/footer.twig */
class __TwigTemplate_996eb943fb69926924d90743eef4787079ab93fed4e634aa13ba20054789d2d2 extends Twig_Template
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
        // line 4
        echo "<div class=\"container-fluid derechos\">
\t<div class=\"container p-0 text-center text-md-left\">
\t    <div class=\"row py-3\">
\t        <div class=\"col-12 col-md-6\">
\t            &copy; ";
        // line 8
        echo twig_escape_filter($this->env, twig_date_format_filter($this->env, "now", "Y"), "html", null, true);
        echo " ";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "name", array()), "html", null, true);
        echo "
\t        </div>
\t        <div class=\"col-12 col-md-6 mt-2 mt-md-0 text-center text-md-right\">

\t\t\t\t<a class=\"px-2 py-2 d-block d-sm-inline\" href=\"";
        // line 12
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "informacion/contacto\">
\t\t\t\t\tContáctanos
\t\t\t\t</a>

\t\t\t\t<!--<a class=\"px-2 py-2 d-block d-sm-inline\" href=\"";
        // line 16
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "informacion/privacidad\">
\t\t\t\t\tAviso de privacidad
\t\t\t\t</a>-->
\t\t\t\t
\t\t\t</div>
\t    </div>
\t</div>
</div>";
    }

    public function getTemplateName()
    {
        return "overall/footer.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  41 => 16,  34 => 12,  25 => 8,  19 => 4,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "overall/footer.twig", "/home4/eltimonl/public_html/app/templates/overall/footer.twig");
    }
}
