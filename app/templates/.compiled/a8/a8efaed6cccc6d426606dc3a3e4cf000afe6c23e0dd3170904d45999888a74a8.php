<?php

/* cuenta/menu.twig */
class __TwigTemplate_8e2061316644fd40f7d51c8ec52a36c2d19f4bcd798f75d676db5337029d8aa6 extends Twig_Template
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
        if ((($context["metodo"] ?? null) == "mis-libros")) {
            echo "active";
        }
        echo "\" href=\"";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "cuenta/mis-libros\"><i class=\"fas fa-book-open fa-lg mr-1\"></i> <span class=\"d-none d-sm-inline\">Mis libros</span></a>
                <a class=\"p-2 link_cambiar_clave ";
        // line 8
        if ((($context["metodo"] ?? null) == "cambiar-contrasena")) {
            echo "active";
        }
        echo "\" href=\"";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "cuenta/cambiar-contrasena\"><i class=\"fas fa-user-lock fa-lg mr-1\"></i> <span class=\"d-none d-sm-inline\">Cambiar contraseña</span></a>
                <a class=\"p-2 link_eliminar ";
        // line 9
        if ((($context["metodo"] ?? null) == "eliminar-cuenta")) {
            echo "active";
        }
        echo "\" href=\"";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "cuenta/eliminar-cuenta\"><i class=\"fas fa-user-times fa-lg mr-1\"></i> <span class=\"d-none d-sm-inline\">Eliminar cuenta</span></a>
                <a class=\"p-2 link_eliminar\" href=\"";
        // line 10
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
        return array (  65 => 10,  57 => 9,  49 => 8,  41 => 7,  33 => 6,  25 => 5,  19 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("<div class=\"container-fluid mt-0 z-depth-1\">
    <div class=\"container animated fadeIn\">
        <div class=\"nav-scroller py-1 mb-2\">
            <nav class=\"nav d-flex justify-content-between\">
                <a class=\"p-2 link_datos {% if metodo == 'mis-datos' %}active{% endif %}\" href=\"{{config.build.url}}cuenta/mis-datos\"><i class=\"fas fa-user-cog fa-lg mr-1\"></i> <span class=\"d-none d-sm-inline\">Mis datos</span></a>
                <a class=\"p-2 link_deseos {% if metodo == 'lista-deseos' %}active{% endif %}\" href=\"{{config.build.url}}cuenta/lista-deseos\"><i class=\"fas fa-heart fa-lg mr-1\"></i> <span class=\"d-none d-sm-inline\">Mi lista de deseos</span></a>
                <a class=\"p-2 link_libros {% if metodo == 'mis-libros' %}active{% endif %}\" href=\"{{config.build.url}}cuenta/mis-libros\"><i class=\"fas fa-book-open fa-lg mr-1\"></i> <span class=\"d-none d-sm-inline\">Mis libros</span></a>
                <a class=\"p-2 link_cambiar_clave {% if metodo == 'cambiar-contrasena' %}active{% endif %}\" href=\"{{config.build.url}}cuenta/cambiar-contrasena\"><i class=\"fas fa-user-lock fa-lg mr-1\"></i> <span class=\"d-none d-sm-inline\">Cambiar contraseña</span></a>
                <a class=\"p-2 link_eliminar {% if metodo == 'eliminar-cuenta' %}active{% endif %}\" href=\"{{config.build.url}}cuenta/eliminar-cuenta\"><i class=\"fas fa-user-times fa-lg mr-1\"></i> <span class=\"d-none d-sm-inline\">Eliminar cuenta</span></a>
                <a class=\"p-2 link_eliminar\" href=\"{{config.build.url}}salir\"><i class=\"fas fa-sign-out-alt fa-lg mr-1\"></i> <span class=\"d-none d-sm-inline\">Cerrar sesión</span></a>
            </nav>
        </div>
    </div>
</div>", "cuenta/menu.twig", "C:\\xampp\\htdocs\\website\\app\\templates\\cuenta\\menu.twig");
    }
}
