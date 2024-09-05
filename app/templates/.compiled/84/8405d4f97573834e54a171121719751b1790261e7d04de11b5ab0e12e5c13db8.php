<?php

/* overall/footer.twig */
class __TwigTemplate_a81d721035035caae8c39764baa4f5747e04fcdaf18aa032ad295b0bbfc0cb33 extends Twig_Template
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
        echo "<footer class=\"footer mt-5 py-3 container-fluid\">

\t<section class=\"container p-0\">

\t\t<div class=\"row flex align-items-center\">

\t\t\t<div class=\"col-12 col-lg-6\">
\t\t\t\t<div class=\"row mb-4 mb-lg-0 flex align-items-center\">
\t\t\t\t\t<div class=\"col-12 col-sm-4\">
\t\t\t\t\t\t<div class=\"text-center text-md-left logotipo_footer\">
\t\t\t\t\t\t\t";
        // line 11
        echo twig_get_attribute($this->env, $this->getSourceContext(), ($context["logotipo"] ?? null), "logotipo_footer", array());
        echo "
\t\t\t\t\t\t</div>
\t\t\t\t\t</div>
\t\t\t\t\t<div class=\"col-12 col-sm-8\">
\t\t\t\t\t
\t\t\t\t\t\t<ul class=\"px-5 px-sm-0 fa-ul mb-3 direccion_contacto\">
\t\t\t\t\t\t\t<li>
\t\t\t\t\t\t\t\t<span class=\"fa-li pr-2\">
\t\t\t\t\t\t\t\t\t<i class=\"fas fa-map-marked-alt\"></i>
\t\t\t\t\t\t\t\t</span>
\t\t\t\t\t\t\t\t";
        // line 21
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["datos"] ?? null), "direccion", array()), "html", null, true);
        echo "
\t\t\t\t\t\t\t</li>
\t\t\t\t\t\t\t<li>
\t\t\t\t\t\t\t\t<span class=\"fa-li pr-2\">
\t\t\t\t\t\t\t\t\t<i class=\"fas fa-envelope-open-text\"></i>
\t\t\t\t\t\t\t\t</span>
\t\t\t\t\t\t\t\t";
        // line 27
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["datos"] ?? null), "correo_contacto", array()), "html", null, true);
        echo "
\t\t\t\t\t\t\t</li>
\t\t\t\t\t\t\t<li>
\t\t\t\t\t\t\t\t<span class=\"fa-li pr-2\">
\t\t\t\t\t\t\t\t\t<i class=\"fas fa-phone\"></i>
\t\t\t\t\t\t\t\t</span>
\t\t\t\t\t\t\t\t";
        // line 33
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["datos"] ?? null), "telefono_contacto", array()), "html", null, true);
        echo "
\t\t\t\t\t\t\t</li>
\t\t\t\t\t\t</ul>

\t\t\t\t\t\t<div class=\"text-center redes_sociales_footer\">

\t\t\t\t\t\t\t";
        // line 39
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["redes_sociales"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["rs"]) {
            // line 40
            echo "\t\t\t\t\t\t\t\t";
            if ((twig_get_attribute($this->env, $this->getSourceContext(), $context["rs"], "estatus", array()) == "on")) {
                // line 41
                echo "\t\t\t\t\t\t\t\t\t<a class=\"px-2 px-sm-3 align-middle\" href=\"";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), $context["rs"], "url", array()), "html", null, true);
                echo "\">
\t\t\t\t\t\t\t\t\t\t<i class=\"redSocial ";
                // line 42
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), $context["rs"], "clase", array()), "html", null, true);
                echo " ";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), $context["rs"], "estilo", array()), "html", null, true);
                echo " ";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), $context["rs"], "red", array()), "html", null, true);
                echo "\"></i>
\t\t\t\t\t\t\t\t\t</a>
\t\t\t\t\t\t\t\t";
            }
            // line 45
            echo "\t\t\t\t\t\t\t";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['rs'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 46
        echo "
\t\t\t\t\t\t\t
\t\t\t\t\t\t</div>
\t\t\t\t\t</div>
\t\t\t\t</div>
\t\t\t\t
\t\t\t</div>
\t\t\t<div class=\"col-12 col-lg-6 text-center\">

\t\t\t\t<a class=\"py-2 py-sm-0 px-2 link_gris d-block d-sm-inline border-right border-bottom-0 border-dark eliminar_borde_md\" href=\"";
        // line 55
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "informacion/libreria\">
\t\t\t\t\t¿Quiénes somos?
\t\t\t\t</a>

\t\t\t\t<a class=\"py-2 py-sm-0 px-2 link_gris d-block d-sm-inline border-right border-bottom-0 border-dark eliminar_borde_md\" href=\"";
        // line 59
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "informacion/contacto\">
\t\t\t\t\tContáctanos
\t\t\t\t</a>

\t\t\t\t<a class=\"py-2 py-sm-0 px-2 link_gris d-block d-sm-inline border-right border-bottom-0 border-dark eliminar_borde_md\" href=\"";
        // line 63
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "informacion/ayuda\">
\t\t\t\t\tAyuda
\t\t\t\t</a>

\t\t\t\t<a class=\"py-2 py-sm-0 px-2 link_gris d-block d-sm-inline\" href=\"";
        // line 67
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "informacion/privacidad\">
\t\t\t\t\tAviso de privacidad
\t\t\t\t</a>

\t\t\t\t<br>

\t\t\t\t<div class=\"metodo_pago mt-3\">
\t\t\t\t\t<a href=\"https://www.paypal.com/mx/webapps/mpp/what-is-paypal\" target=\"_blank\" class=\"mb-2\">
\t\t\t\t\t\t<img src=\"https://www.paypalobjects.com/webstatic/mktg/logo-center/logotipo_paypal_pagos.png\" border=\"0\"/>
\t\t\t\t\t</a>\t\t
\t\t\t\t\t<a href=\"http://www.payulatam.com/mx/\" target=\"_blank\">
\t\t\t\t\t\t<img src=\"https://ecommerce.payulatam.com/logos/mx-credito.png\" border=\"0\" />
\t\t\t\t\t</a>
\t\t\t\t</div>
\t\t\t\t
\t\t\t</div>

\t\t</div>
\t\t
\t</section>

</footer>
<div class=\"container-fluid derechos\">
\t<div class=\"container p-0 text-center text-md-left\">
\t\t&copy; ";
        // line 91
        echo twig_escape_filter($this->env, twig_date_format_filter($this->env, "now", "Y"), "html", null, true);
        echo " ";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "name", array()), "html", null, true);
        echo ".
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
        return array (  158 => 91,  131 => 67,  124 => 63,  117 => 59,  110 => 55,  99 => 46,  93 => 45,  83 => 42,  78 => 41,  75 => 40,  71 => 39,  62 => 33,  53 => 27,  44 => 21,  31 => 11,  19 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("<footer class=\"footer mt-5 py-3 container-fluid\">

\t<section class=\"container p-0\">

\t\t<div class=\"row flex align-items-center\">

\t\t\t<div class=\"col-12 col-lg-6\">
\t\t\t\t<div class=\"row mb-4 mb-lg-0 flex align-items-center\">
\t\t\t\t\t<div class=\"col-12 col-sm-4\">
\t\t\t\t\t\t<div class=\"text-center text-md-left logotipo_footer\">
\t\t\t\t\t\t\t{{ logotipo.logotipo_footer|raw }}
\t\t\t\t\t\t</div>
\t\t\t\t\t</div>
\t\t\t\t\t<div class=\"col-12 col-sm-8\">
\t\t\t\t\t
\t\t\t\t\t\t<ul class=\"px-5 px-sm-0 fa-ul mb-3 direccion_contacto\">
\t\t\t\t\t\t\t<li>
\t\t\t\t\t\t\t\t<span class=\"fa-li pr-2\">
\t\t\t\t\t\t\t\t\t<i class=\"fas fa-map-marked-alt\"></i>
\t\t\t\t\t\t\t\t</span>
\t\t\t\t\t\t\t\t{{datos.direccion}}
\t\t\t\t\t\t\t</li>
\t\t\t\t\t\t\t<li>
\t\t\t\t\t\t\t\t<span class=\"fa-li pr-2\">
\t\t\t\t\t\t\t\t\t<i class=\"fas fa-envelope-open-text\"></i>
\t\t\t\t\t\t\t\t</span>
\t\t\t\t\t\t\t\t{{datos.correo_contacto}}
\t\t\t\t\t\t\t</li>
\t\t\t\t\t\t\t<li>
\t\t\t\t\t\t\t\t<span class=\"fa-li pr-2\">
\t\t\t\t\t\t\t\t\t<i class=\"fas fa-phone\"></i>
\t\t\t\t\t\t\t\t</span>
\t\t\t\t\t\t\t\t{{datos.telefono_contacto}}
\t\t\t\t\t\t\t</li>
\t\t\t\t\t\t</ul>

\t\t\t\t\t\t<div class=\"text-center redes_sociales_footer\">

\t\t\t\t\t\t\t{% for rs in redes_sociales %}
\t\t\t\t\t\t\t\t{% if rs.estatus == 'on' %}
\t\t\t\t\t\t\t\t\t<a class=\"px-2 px-sm-3 align-middle\" href=\"{{rs.url}}\">
\t\t\t\t\t\t\t\t\t\t<i class=\"redSocial {{rs.clase}} {{rs.estilo}} {{rs.red}}\"></i>
\t\t\t\t\t\t\t\t\t</a>
\t\t\t\t\t\t\t\t{% endif %}
\t\t\t\t\t\t\t{% endfor %}

\t\t\t\t\t\t\t
\t\t\t\t\t\t</div>
\t\t\t\t\t</div>
\t\t\t\t</div>
\t\t\t\t
\t\t\t</div>
\t\t\t<div class=\"col-12 col-lg-6 text-center\">

\t\t\t\t<a class=\"py-2 py-sm-0 px-2 link_gris d-block d-sm-inline border-right border-bottom-0 border-dark eliminar_borde_md\" href=\"{{config.build.url}}informacion/libreria\">
\t\t\t\t\t¿Quiénes somos?
\t\t\t\t</a>

\t\t\t\t<a class=\"py-2 py-sm-0 px-2 link_gris d-block d-sm-inline border-right border-bottom-0 border-dark eliminar_borde_md\" href=\"{{config.build.url}}informacion/contacto\">
\t\t\t\t\tContáctanos
\t\t\t\t</a>

\t\t\t\t<a class=\"py-2 py-sm-0 px-2 link_gris d-block d-sm-inline border-right border-bottom-0 border-dark eliminar_borde_md\" href=\"{{config.build.url}}informacion/ayuda\">
\t\t\t\t\tAyuda
\t\t\t\t</a>

\t\t\t\t<a class=\"py-2 py-sm-0 px-2 link_gris d-block d-sm-inline\" href=\"{{config.build.url}}informacion/privacidad\">
\t\t\t\t\tAviso de privacidad
\t\t\t\t</a>

\t\t\t\t<br>

\t\t\t\t<div class=\"metodo_pago mt-3\">
\t\t\t\t\t<a href=\"https://www.paypal.com/mx/webapps/mpp/what-is-paypal\" target=\"_blank\" class=\"mb-2\">
\t\t\t\t\t\t<img src=\"https://www.paypalobjects.com/webstatic/mktg/logo-center/logotipo_paypal_pagos.png\" border=\"0\"/>
\t\t\t\t\t</a>\t\t
\t\t\t\t\t<a href=\"http://www.payulatam.com/mx/\" target=\"_blank\">
\t\t\t\t\t\t<img src=\"https://ecommerce.payulatam.com/logos/mx-credito.png\" border=\"0\" />
\t\t\t\t\t</a>
\t\t\t\t</div>
\t\t\t\t
\t\t\t</div>

\t\t</div>
\t\t
\t</section>

</footer>
<div class=\"container-fluid derechos\">
\t<div class=\"container p-0 text-center text-md-left\">
\t\t&copy; {{ \"now\"|date(\"Y\") }} {{ config.build.name }}.
\t</div>
</div>", "overall/footer.twig", "C:\\xampp\\htdocs\\website\\app\\templates\\overall\\footer.twig");
    }
}
