<?php

/* overall/header.twig */
class __TwigTemplate_979856596a61974d406851d040bfc8d273d207e67671254b4d65dbe152dff619 extends Twig_Template
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
        echo "<section class=\"container-fluid promociones_timon\">
\t<div class=\"row py-2 overflow-hidden d-flex align-items-center\">
\t\t";
        // line 6
        echo "\t\t<div class=\"col-12 col-sm-6 text-center text-sm-right px-1 promociones_descipcion\">
\t\t\t<span class=\"text-danger promocion_valor\">Envío gratis</span> a partir <span class=\"promocion_grupo\">de \$800.00</span>
\t\t</div>
\t\t<div class=\"col-12 col-sm-6 text-center text-sm-left px-1 promociones_vigencia\">
\t\t\t<p class=\"my-auto p-0 m-0\">Promoción para México</p>
\t\t</div>
\t</div>
</section>
<header>
    <section class=\"container-fluid navegacion_superior\">
        <div class=\"container px-0\">
            <div class=\"d-flex flex-column flex-md-row align-items-center px-0 pt-2 pb-0 py-sm-2\">
                <div class=\"my-0 mr-md-auto animated zoomInDown\">
                    <a class=\"logotipo_svg\" href=\"";
        // line 19
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "\">
                        ";
        // line 20
        echo twig_get_attribute($this->env, $this->getSourceContext(), ($context["logotipo"] ?? null), "logotipo_header", array());
        echo "
                    </a>
                </div>
                <nav class=\"pt-3 pt-md-0 animated2 fadeIn\">
                    <a class=\"p-2 d-none d-md-inline\" href=\"";
        // line 24
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "informacion/contacto\">Contáctanos</a>
                    ";
        // line 25
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["redes_sociales"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["rs"]) {
            // line 26
            echo "                        ";
            if ((twig_get_attribute($this->env, $this->getSourceContext(), $context["rs"], "estatus", array()) == "on")) {
                // line 27
                echo "                            <a class=\"pl-2 pr-0 pl-md-3 align-middle d-none d-md-inline-flex redSocial\" href=\"";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), $context["rs"], "url", array()), "html", null, true);
                echo "\" target=\"_blank\"><i class=\"";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), $context["rs"], "clase", array()), "html", null, true);
                echo " ";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), $context["rs"], "estilo", array()), "html", null, true);
                echo " ";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), $context["rs"], "red", array()), "html", null, true);
                echo "\"></i> &nbsp; ";
                if ((twig_get_attribute($this->env, $this->getSourceContext(), $context["rs"], "red", array()) == "fa-facebook-square")) {
                    echo " El timón librería ";
                }
                echo "</a>
                        ";
            }
            // line 29
            echo "                    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['rs'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 30
        echo "                </nav>
            </div>
        </div>
    </section>
    <section class=\"container-fluid px-0 py-1 d-block d-sm-none formulario_busqueda_movil\">
        <nav class=\"navbar p-0 justify-content-center\">
            <form id=\"formulario_busqueda_movil\" action=\"busqueda/\" method=\"GET\" class=\"form-inline\">
                <div class=\"form-group m-0\">
                    <div class=\"input-group\">
                        <div class=\"input-group-prepend\">
                            <span class=\"input-group-text i_buscar\">
                                <i class=\"fas fa-search\"></i>
                            </span>
                        </div>
                        <input type=\"search\" name=\"consulta\" class=\"form-control form-control-sm pl-0 input_buscar\" placeholder=\"Título, ISBN, editorial o autor\" autocomplete=\"off\">
                        <div class=\"input-group-btn\">
                            <button class=\"btn btn-sm boton_blanco boton_buscar\" type=\"submit\">Buscar</button>
                        </div>
                    </div>
                </div>
            </form>
        </nav>
    </section>
    <section class=\"container-fluid p-0 navegacion_media\">
        <div class=\"container px-0\">
            <div class=\"row\">
                <div class=\"col-12\">
                    <nav class=\"navbar navbar-expand navbar-dark p-0\">
                        <ul class=\"navbar-nav mr-auto\">
                            <li class=\"nav-item dropdown megamenu_enlace\">
                                <a class=\"nav-link dropdown-toggle rounded-0\" href=\"#\" id=\"libros_timon\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
                                    <i class=\"fas fa-book\"></i>
                                    <span class=\"ml-1\">Nuestros libros</span>
                                </a>
                                <div class=\"dropdown-menu megamenu_contenido rounded-0\" aria-labelledby=\"libros_timon\">
                                    <div class=\"row px-3\">
                                        <div class=\"col-12 col-sm-3 col-lg-2 py-3 pb-4 border-right explorar\">
                                            <h6><i class=\"fas fa-certificate mr-1\"></i> DESTACADOS</h6>
                                            <hr class=\"mt-2 mt-sm-0 mb-2\">
                                            <a class=\"dropdown-item mb-1 mb-md-0\" class=\"pixelDestacados\" href=\"";
        // line 69
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "destacados/novedades\">Los más nuevos</a>
                                            <a class=\"dropdown-item mb-1 mb-md-0\" class=\"pixelMasVendidos\" href=\"";
        // line 70
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "destacados/mas-vendidos\">Los más vendidos</a>
                                        </div>
                                        <div class=\"col-12 col-sm-9 col-lg-7 py-3 pb-4\">
                                            <div class=\"row\">
                                                ";
        // line 74
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["categorias"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["c"]) {
            // line 75
            echo "                                                    ";
            if ((twig_get_attribute($this->env, $this->getSourceContext(), $context["c"], "id", array()) != 1)) {
                // line 76
                echo "                                                        <div class=\"col-sm-6 col-md-4 mb-3\">
                                                            <a class=\"pixelCategoria enlace_negro text-truncate\" title=\"";
                // line 77
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), $context["c"], "categoria", array()), "html", null, true);
                echo "\" href=\"";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
                echo "libros/";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), $context["c"], "ruta", array()), "html", null, true);
                echo "/";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), $context["c"], "id", array()), "html", null, true);
                echo "\">
                                                                <i class=\"fas fa-angle-right mr-1 animated fadeInLeft infinite\"></i> ";
                // line 78
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), $context["c"], "categoria", array()), "html", null, true);
                echo "
                                                            </a>
                                                            <hr class=\"mt-2 mt-sm-0 mb-2\">
                                                            ";
                // line 81
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable(($context["subcategorias"] ?? null));
                foreach ($context['_seq'] as $context["_key"] => $context["s"]) {
                    // line 82
                    echo "                                                                ";
                    if ((twig_get_attribute($this->env, $this->getSourceContext(), $context["c"], "id", array()) == twig_get_attribute($this->env, $this->getSourceContext(), $context["s"], "id_categoria", array()))) {
                        // line 83
                        echo "                                                                    <a class=\"dropdown-item mb-1 mb-md-0 text-truncate pixelSubcategoria\" title=\"";
                        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), $context["s"], "subcategoria", array()), "html", null, true);
                        echo "\" href=\"";
                        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
                        echo "libros/";
                        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), $context["s"], "ruta", array()), "html", null, true);
                        echo "/";
                        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), $context["s"], "id", array()), "html", null, true);
                        echo "\">";
                        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), $context["s"], "subcategoria", array()), "html", null, true);
                        echo "</a>
                                                                ";
                    }
                    // line 85
                    echo "                                                            ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['s'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 86
                echo "                                                        </div>
                                                    ";
            }
            // line 88
            echo "                                                ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['c'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 89
        echo "                                            </div>
                                        </div>
                                        <div class=\"col-sm-6 col-lg-3 py-4 d-none d-lg-block\">
                                            ";
        // line 92
        echo ($context["libroRand"] ?? null);
        echo " 
                                        </div>
                                    </div>
                                </div>
                            </li>\t\t\t\t\t\t\t
                        </ul>
                        <form id=\"formulario_busqueda\" action=\"busqueda/\" method=\"GET\" class=\"form-inline mx-auto d-none d-sm-inline\">
                            <div class=\"form-group m-0\">
                                <div class=\"input-group\">
                                    <div class=\"input-group-prepend\">
                                        <span class=\"input-group-text i_buscar\">
                                            <i class=\"fas fa-search\"></i>
                                        </span>
                                    </div>
                                    <input type=\"search\" name=\"consulta\" class=\"form-control form-control-sm pl-0 input_buscar font-weight-bold\" placeholder=\"Título, ISBN, editorial o autor\" autocomplete=\"off\">
                                    <div class=\"input-group-btn\">
                                        <button class=\"btn btn-sm boton_blanco boton_buscar\" type=\"submit\">Buscar</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class=\"ml-auto mr-3 mr-sm-0\">
                            ";
        // line 114
        if (($context["is_logged"] ?? null)) {
            // line 115
            echo "                                ";
            if ((($context["controlador"] ?? null) == "cuenta")) {
                // line 116
                echo "                                    
                                ";
            } else {
                // line 118
                echo "                                    <div class=\"dropdown\">
                                        <button class=\"btn btn-icon-only rounded-circle boton_blanco\" type=\"button\" id=\"dropdown_user_account\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
                                            <span class=\"btn-inner--icon font-weight-800 user_letter\">
                                                ";
                // line 121
                echo twig_escape_filter($this->env, twig_slice($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["owner_user"] ?? null), "cliente", array()), 0, 1), "html", null, true);
                echo "
                                            </span> 
                                        </button>
                                        <div class=\"dropdown-menu\" aria-labelledby=\"dropdown_user_account\" style=\"left:-168px;\">
                                            <h6 class=\"dropdown-header text-truncate text-muted pb-3 border-bottom user_name\">";
                // line 125
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["owner_user"] ?? null), "cliente", array()), "html", null, true);
                echo "</h6>
                                            <a class=\"dropdown-item py-2 link_cuenta\" href=\"";
                // line 126
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
                echo "cuenta/mis-datos\">
                                                <i class=\"fas fa-user-cog text-app\"></i>Mis datos
                                            </a>
                                            <a class=\"dropdown-item py-2 link_cuenta\" href=\"";
                // line 129
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
                echo "cuenta/lista-deseos\">
                                                <i class=\"fas fa-heart text-app\"></i>Mi lista de deseos
                                            </a>
                                            <a class=\"dropdown-item py-2 link_cuenta\" href=\"";
                // line 132
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
                echo "cuenta/mis-compras\">
                                                <i class=\"fas fa-book-open text-app\"></i>Mis compras
                                            </a>
                                            <a class=\"dropdown-item py-2 link_cuenta\" href=\"";
                // line 135
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
                echo "cuenta/cambiar-contrasena\">
                                                <i class=\"fas fa-user-lock text-app\"></i>Cambiar contraseña
                                            </a>
                                            <a class=\"dropdown-item py-2 link_cuenta\" href=\"";
                // line 138
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
                echo "cuenta/eliminar-cuenta\">
                                                <i class=\"fas fa-user-times text-app\"></i>Eliminar cuenta
                                            </a>
                                            <a class=\"dropdown-item py-2\" href=\"";
                // line 141
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
                echo "salir\">
                                                <i class=\"fas fa-sign-out-alt text-app\"></i>Cerrar sesión
                                            </a>
                                        </div>
                                    </div>
                                ";
            }
            // line 147
            echo "                            ";
        } else {
            // line 148
            echo "                                <a href=\"";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
            echo "autenticacion\" class=\"btn boton_blanco btn-icon-only rounded-circle\">
                                    <span class=\"btn-inner--icon\">
                                        <i class=\"fas fa-user\"></i>
                                    </span>
                                </a>
                            ";
        }
        // line 154
        echo "                            <a href=\"";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "compra/\" class=\"btn btn-sm boton_blanco btn-icon ml-1 boton_compra\">
                                <span class=\"btn-inner--icon\"><i class=\"fas fa-shopping-bag\"></i></span>
                                <span class=\"btn-inner--text\"><label class=\"m-0 cantidad_carrito\">(";
        // line 156
        echo twig_escape_filter($this->env, ($context["cantidad_carrito"] ?? null), "html", null, true);
        echo ")</label></span>
                            </a>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </section>
</header>";
    }

    public function getTemplateName()
    {
        return "overall/header.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  310 => 156,  304 => 154,  294 => 148,  291 => 147,  282 => 141,  276 => 138,  270 => 135,  264 => 132,  258 => 129,  252 => 126,  248 => 125,  241 => 121,  236 => 118,  232 => 116,  229 => 115,  227 => 114,  202 => 92,  197 => 89,  191 => 88,  187 => 86,  181 => 85,  167 => 83,  164 => 82,  160 => 81,  154 => 78,  144 => 77,  141 => 76,  138 => 75,  134 => 74,  127 => 70,  123 => 69,  82 => 30,  76 => 29,  60 => 27,  57 => 26,  53 => 25,  49 => 24,  42 => 20,  38 => 19,  23 => 6,  19 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "overall/header.twig", "/home4/eltimonl/public_html/app/templates/overall/header.twig");
    }
}
