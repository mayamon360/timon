<?php

/* cuenta/menu.twig */
class __TwigTemplate_0bd6f7ec8a825371de0c78ab4e28dc1b835ccdef37be9002f6fe285de3962a1f extends Twig_Template
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
                <a class=\"p-2 link_datos\" href=\"";
        // line 5
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "cuenta/mis-datos\"><i class=\"fas fa-user-cog fa-lg mr-1\"></i> Mis datos</a>
                <a class=\"p-2 link_deseos\" href=\"";
        // line 6
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "cuenta/lista-deseos\"><i class=\"fas fa-heart fa-lg mr-1\"></i> Mi lista de deseos</a>
                <a class=\"p-2 link_libros\" href=\"";
        // line 7
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "cuenta/mis-libros\"><i class=\"fas fa-book-open fa-lg mr-1\"></i> Mis libros</a>
                <a class=\"p-2 link_cambiar_clave\" href=\"";
        // line 8
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "cuenta/cambiar-contrasena\"><i class=\"fas fa-user-lock fa-lg mr-1\"></i> Cambiar contraseña</a>
                <a class=\"p-2 link_eliminar\" href=\"";
        // line 9
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "cuenta/eliminar-cuenta\"><i class=\"fas fa-user-times fa-lg mr-1\"></i> Eliminar cuenta</a>
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
        return array (  41 => 9,  37 => 8,  33 => 7,  29 => 6,  25 => 5,  19 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "cuenta/menu.twig", "C:\\xampp\\htdocs\\website\\app\\templates\\cuenta\\menu.twig");
    }
}
