<?php

/* cuenta/menu.twig */
class __TwigTemplate_ea95e3ae8055bf44da0fbef73ad0f229f2a14d2082a22e0f6dffabc245f6ae11 extends Twig_Template
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
        echo "<div class=\"container-fluid mt-0 z-depth-1\">
    <div class=\"container animated fadeIn\">
        <div class=\"nav-scroller py-1 mb-2\">
            <nav class=\"nav d-flex justify-content-between\">
                <a class=\"p-2 link_datos ";
        // line 5
        if ((($context["metodo"] ?? null) == "mis-datos")) {
            echo "active";
        }
        echo "\" href=\"";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "cuenta/mis-datos\"><i class=\"fas fa-user-cog fa-lg mr-1\"></i> <span class=\"d-none d-sm-inline\">Mis datos</span></a>
                <a class=\"p-2 link_deseos ";
        // line 6
        if ((($context["metodo"] ?? null) == "lista-deseos")) {
            echo "active";
        }
        echo "\" href=\"";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "cuenta/lista-deseos\"><i class=\"fas fa-heart fa-lg mr-1\"></i> <span class=\"d-none d-sm-inline\">Mi lista de deseos</span></a>
                <a class=\"p-2 link_libros ";
        // line 7
        if ((($context["metodo"] ?? null) == "mis-compras")) {
            echo "active";
        }
        echo "\" href=\"";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "cuenta/mis-compras\"><i class=\"fas fa-book-open fa-lg mr-1\"></i> <span class=\"d-none d-sm-inline\">Mis compras</span></a>
                <a class=\"p-2 link_cambiar_clave ";
        // line 8
        if ((($context["metodo"] ?? null) == "cambiar-contrasena")) {
            echo "active";
        }
        echo "\" href=\"";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "cuenta/cambiar-contrasena\"><i class=\"fas fa-user-lock fa-lg mr-1\"></i> <span class=\"d-none d-sm-inline\">Cambiar contraseña</span></a>
                <a class=\"p-2 link_eliminar\" href=\"";
        // line 9
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "salir\"><i class=\"fas fa-sign-out-alt fa-lg mr-1\"></i> <span class=\"d-none d-sm-inline\">Cerrar sesión</span></a>
            </nav>
        </div>
    </div>
</div>";
    }

    public function getTemplateName()
    {
        return "cuenta/menu.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  57 => 9,  49 => 8,  41 => 7,  33 => 6,  25 => 5,  19 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("<div class=\"container-fluid mt-0 z-depth-1\">
    <div class=\"container animated fadeIn\">
        <div class=\"nav-scroller py-1 mb-2\">
            <nav class=\"nav d-flex justify-content-between\">
                <a class=\"p-2 link_datos {% if metodo == 'mis-datos' %}active{% endif %}\" href=\"{{config.build.url}}cuenta/mis-datos\"><i class=\"fas fa-user-cog fa-lg mr-1\"></i> <span class=\"d-none d-sm-inline\">Mis datos</span></a>
                <a class=\"p-2 link_deseos {% if metodo == 'lista-deseos' %}active{% endif %}\" href=\"{{config.build.url}}cuenta/lista-deseos\"><i class=\"fas fa-heart fa-lg mr-1\"></i> <span class=\"d-none d-sm-inline\">Mi lista de deseos</span></a>
                <a class=\"p-2 link_libros {% if metodo == 'mis-compras' %}active{% endif %}\" href=\"{{config.build.url}}cuenta/mis-compras\"><i class=\"fas fa-book-open fa-lg mr-1\"></i> <span class=\"d-none d-sm-inline\">Mis compras</span></a>
                <a class=\"p-2 link_cambiar_clave {% if metodo == 'cambiar-contrasena' %}active{% endif %}\" href=\"{{config.build.url}}cuenta/cambiar-contrasena\"><i class=\"fas fa-user-lock fa-lg mr-1\"></i> <span class=\"d-none d-sm-inline\">Cambiar contraseña</span></a>
                <a class=\"p-2 link_eliminar\" href=\"{{config.build.url}}salir\"><i class=\"fas fa-sign-out-alt fa-lg mr-1\"></i> <span class=\"d-none d-sm-inline\">Cerrar sesión</span></a>
            </nav>
        </div>
    </div>
</div>", "cuenta/menu.twig", "/home4/eltimonl/public_html/app/templates/cuenta/menu.twig");
    }
}
