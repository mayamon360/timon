<?php

/* overall/header.twig */
class __TwigTemplate_19820d53fe7812469dd72cd22f2cf171794709b7992b81049340fa728f7e62ac extends Twig_Template
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
\t\t<div class=\"col-12 text-center animated pulse infinite promociones_titulo\">
\t\t\t¡Aprovecha la oportunidad!
\t\t</div>
\t\t<div class=\"col-12 col-sm-6 text-center text-sm-right px-1 promociones_descipcion\">
\t\t\t<span class=\"text-danger promocion_valor\">-30%</span> en <span class=\"promocion_grupo\">Categoría</span>
\t\t</div>
\t\t<div class=\"col-12 col-sm-6 text-center text-sm-left px-1 promociones_vigencia\">
\t\t\t<p class=\"my-auto p-0 m-0\">Vigencia hasta el 23 de agosto, quedán 2 días</p>
\t\t</div>
\t</div>
</section>
<header>
    <section class=\"container-fluid navegacion_superior\">
        <div class=\"container p-0\">
            <div class=\"d-flex flex-column flex-md-row align-items-center px-0 py-2 bg-white\">
                <div class=\"my-0 mr-md-auto animated fadeIn\">
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
                <nav class=\"p-0 pt-3 pt-md-0 animated2 fadeIn\">
                    <a class=\"p-2 d-none d-md-inline\" href=\"";
        // line 24
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "informacion/libreria\">¿Quiénes somos?</a>
                    <div class=\"border-right border-dark border-bottom-0 d-none d-md-inline-flex py-0\">&nbsp</div>
                    <a class=\"p-2 d-none d-md-inline\" href=\"";
        // line 26
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "informacion/contacto\">Contáctanos</a>
                    <div class=\"border-right border-dark border-bottom-0 d-none d-md-inline-flex py-0\">&nbsp</div>
                    <a class=\"p-2 d-none d-md-inline\" href=\"";
        // line 28
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "informacion/ayuda\">Ayuda</a>
                    <div class=\"border-right border-dark border-bottom-0 d-none d-md-inline-flex py-0 ml-2\">&nbsp</div>
                    ";
        // line 30
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["redes_sociales"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["rs"]) {
            // line 31
            echo "                        ";
            if ((twig_get_attribute($this->env, $this->getSourceContext(), $context["rs"], "estatus", array()) == "on")) {
                // line 32
                echo "                            <a class=\"px-2 px-md-3 align-middle d-none d-md-inline-flex redSocial\" href=\"";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), $context["rs"], "url", array()), "html", null, true);
                echo "\"><i class=\"";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), $context["rs"], "clase", array()), "html", null, true);
                echo " ";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), $context["rs"], "estilo", array()), "html", null, true);
                echo " ";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), $context["rs"], "red", array()), "html", null, true);
                echo "\"></i></a>
                        ";
            }
            // line 34
            echo "                    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['rs'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 35
        echo "                </nav>
            </div>
        </div>
    </section>
    <section class=\"container-fluid p-0 navegacion_media\">
        <div class=\"container p-0\">
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
        // line 55
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "destacados/novedades\">Los más nuevos</a>
                                            <a class=\"dropdown-item mb-1 mb-md-0\" class=\"pixelMasVendidos\" href=\"";
        // line 56
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "destacados/mas-vendidos\">Los más vendidos</a>
                                        </div>
                                        <div class=\"col-12 col-sm-9 col-lg-7 py-3 pb-4\">
                                            <div class=\"row\">
                                                ";
        // line 60
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["categorias"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["c"]) {
            // line 61
            echo "                                                    <div class=\"col-sm-6 col-md-4 mb-3\">
                                                        <a class=\"pixelCategoria enlace_negro\" href=\"";
            // line 62
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
            echo "libros/";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), $context["c"], "ruta", array()), "html", null, true);
            echo "\">
                                                            <i class=\"fas fa-angle-right mr-1 animated fadeInLeft infinite\"></i> ";
            // line 63
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), $context["c"], "categoria", array()), "html", null, true);
            echo "
                                                        </a>
                                                        <hr class=\"mt-2 mt-sm-0 mb-2\">
                                                        ";
            // line 66
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(($context["subcategorias"] ?? null));
            foreach ($context['_seq'] as $context["_key"] => $context["s"]) {
                // line 67
                echo "                                                            ";
                if ((twig_get_attribute($this->env, $this->getSourceContext(), $context["c"], "id", array()) == twig_get_attribute($this->env, $this->getSourceContext(), $context["s"], "id_categoria", array()))) {
                    // line 68
                    echo "                                                                <a class=\"dropdown-item mb-1 mb-md-0 pixelSubcategoria\" href=\"";
                    echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
                    echo "libros/";
                    echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), $context["s"], "ruta", array()), "html", null, true);
                    echo "\">";
                    echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), $context["s"], "subcategoria", array()), "html", null, true);
                    echo "</a>
                                                            ";
                }
                // line 70
                echo "                                                        ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['s'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 71
            echo "                                                    </div>
                                                ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['c'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 73
        echo "                                            </div>
                                        </div>
                                        <div class=\"col-sm-6 col-lg-3 py-4 d-none d-lg-block\">
                                            ";
        // line 76
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
                                    <input type=\"text\" name=\"consulta\" class=\"form-control form-control-sm pl-0 input_buscar\" placeholder=\"Título, editorial, autor o ISBN\" autocomplete=\"off\">
                                    <div class=\"input-group-btn\">
                                        <button class=\"btn btn-sm boton_blanco boton_buscar\" type=\"submit\">Buscar</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class=\"ml-auto mr-3 mr-sm-0\">
                            ";
        // line 98
        if (($context["is_logged"] ?? null)) {
            // line 99
            echo "                                ";
            if ((($context["controlador"] ?? null) == "cuenta")) {
                // line 100
                echo "                                    
                                ";
            } else {
                // line 102
                echo "                                    <div class=\"dropdown\">
                                        <button class=\"btn btn-icon-only rounded-circle boton_blanco\" type=\"button\" id=\"dropdown_user_account\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
                                            <span class=\"btn-inner--icon font-weight-800 user_letter\">
                                                ";
                // line 105
                echo twig_escape_filter($this->env, twig_slice($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["owner_user"] ?? null), "cliente", array()), 0, 1), "html", null, true);
                echo "
                                            </span> 
                                        </button>
                                        <div class=\"dropdown-menu\" aria-labelledby=\"dropdown_user_account\">
                                            <h6 class=\"dropdown-header text-truncate text-muted pb-3 border-bottom user_name\">";
                // line 109
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["owner_user"] ?? null), "cliente", array()), "html", null, true);
                echo "</h6>
                                            <a class=\"dropdown-item py-2 link_cuenta\" href=\"";
                // line 110
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
                echo "cuenta/mis-datos\">
                                                <i class=\"fas fa-user-cog text-app\"></i>Mis datos
                                            </a>
                                            <a class=\"dropdown-item py-2 link_cuenta\" href=\"";
                // line 113
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
                echo "cuenta/lista-deseos\">
                                                <i class=\"fas fa-heart text-app\"></i>Mi lista de deseos
                                            </a>
                                            <a class=\"dropdown-item py-2 link_cuenta\" href=\"";
                // line 116
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
                echo "cuenta/mis-libros\">
                                                <i class=\"fas fa-book-open text-app\"></i>Mis libros
                                            </a>
                                            <a class=\"dropdown-item py-2 link_cuenta\" href=\"";
                // line 119
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
                echo "cuenta/cambiar-contrasena\">
                                                <i class=\"fas fa-user-lock text-app\"></i>Cambiar contraseña
                                            </a>
                                            <a class=\"dropdown-item py-2 link_cuenta\" href=\"";
                // line 122
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
                echo "cuenta/eliminar-cuenta\">
                                                <i class=\"fas fa-user-times text-app\"></i>Eliminar cuenta
                                            </a>
                                            <a class=\"dropdown-item py-2\" href=\"";
                // line 125
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
                echo "salir\">
                                                <i class=\"fas fa-sign-out-alt text-app\"></i>Cerrar sesión
                                            </a>
                                        </div>
                                    </div>
                                ";
            }
            // line 131
            echo "                            ";
        } else {
            // line 132
            echo "                                <a href=\"";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
            echo "autenticacion\" class=\"btn boton_blanco btn-icon-only rounded-circle\">
                                    <span class=\"btn-inner--icon\">
                                        <i class=\"fas fa-user\"></i>
                                    </span>
                                </a>
                            ";
        }
        // line 138
        echo "                            <a href=\"";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "compra/\" class=\"btn btn-sm boton_blanco btn-icon ml-1 boton_compra\">
                                <span class=\"btn-inner--icon\"><i class=\"fas fa-shopping-bag\"></i></span>
                                <span class=\"btn-inner--text d-none d-md-inline\">Mi compra <label class=\"m-0 cantidad_carrito\">(";
        // line 140
        echo twig_escape_filter($this->env, ($context["cantidad_carrito"] ?? null), "html", null, true);
        echo ")</label></span>
                                <span class=\"btn-inner--text d-inline d-md-none\"><label class=\"m-0 cantidad_carrito\">(";
        // line 141
        echo twig_escape_filter($this->env, ($context["cantidad_carrito"] ?? null), "html", null, true);
        echo ")</label></span>
                            </a>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </section>
    <section class=\"container-fluid px-3 py-1 d-block d-sm-none formulario_busqueda_movil\">
        <nav class=\"navbar p-0 justify-content-center\">
            <form id=\"formulario_busqueda_movil\" action=\"busqueda/\" method=\"GET\" class=\"form-inline\">
                <div class=\"form-group m-0\">
                    <div class=\"input-group\">
                        <div class=\"input-group-prepend\">
                            <span class=\"input-group-text i_buscar\">
                                <i class=\"fas fa-search\"></i>
                            </span>
                        </div>
                        <input type=\"text\" name=\"consulta\" class=\"form-control form-control-sm pl-0 input_buscar\" placeholder=\"Título, editorial, autor o ISBN\" autocomplete=\"off\">
                        <div class=\"input-group-btn\">
                            <button class=\"btn btn-sm boton_blanco boton_buscar\" type=\"submit\">Buscar</button>
                        </div>
                    </div>
                </div>
            </form>
        </nav>
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
        return array (  289 => 141,  285 => 140,  279 => 138,  269 => 132,  266 => 131,  257 => 125,  251 => 122,  245 => 119,  239 => 116,  233 => 113,  227 => 110,  223 => 109,  216 => 105,  211 => 102,  207 => 100,  204 => 99,  202 => 98,  177 => 76,  172 => 73,  165 => 71,  159 => 70,  149 => 68,  146 => 67,  142 => 66,  136 => 63,  130 => 62,  127 => 61,  123 => 60,  116 => 56,  112 => 55,  90 => 35,  84 => 34,  72 => 32,  69 => 31,  65 => 30,  60 => 28,  55 => 26,  50 => 24,  43 => 20,  39 => 19,  19 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("<section class=\"container-fluid promociones_timon\">
\t<div class=\"row py-2 overflow-hidden d-flex align-items-center\">
\t\t<div class=\"col-12 text-center animated pulse infinite promociones_titulo\">
\t\t\t¡Aprovecha la oportunidad!
\t\t</div>
\t\t<div class=\"col-12 col-sm-6 text-center text-sm-right px-1 promociones_descipcion\">
\t\t\t<span class=\"text-danger promocion_valor\">-30%</span> en <span class=\"promocion_grupo\">Categoría</span>
\t\t</div>
\t\t<div class=\"col-12 col-sm-6 text-center text-sm-left px-1 promociones_vigencia\">
\t\t\t<p class=\"my-auto p-0 m-0\">Vigencia hasta el 23 de agosto, quedán 2 días</p>
\t\t</div>
\t</div>
</section>
<header>
    <section class=\"container-fluid navegacion_superior\">
        <div class=\"container p-0\">
            <div class=\"d-flex flex-column flex-md-row align-items-center px-0 py-2 bg-white\">
                <div class=\"my-0 mr-md-auto animated fadeIn\">
                    <a class=\"logotipo_svg\" href=\"{{config.build.url}}\">
                        {{ logotipo.logotipo_header|raw }}
                    </a>
                </div>
                <nav class=\"p-0 pt-3 pt-md-0 animated2 fadeIn\">
                    <a class=\"p-2 d-none d-md-inline\" href=\"{{config.build.url}}informacion/libreria\">¿Quiénes somos?</a>
                    <div class=\"border-right border-dark border-bottom-0 d-none d-md-inline-flex py-0\">&nbsp</div>
                    <a class=\"p-2 d-none d-md-inline\" href=\"{{config.build.url}}informacion/contacto\">Contáctanos</a>
                    <div class=\"border-right border-dark border-bottom-0 d-none d-md-inline-flex py-0\">&nbsp</div>
                    <a class=\"p-2 d-none d-md-inline\" href=\"{{config.build.url}}informacion/ayuda\">Ayuda</a>
                    <div class=\"border-right border-dark border-bottom-0 d-none d-md-inline-flex py-0 ml-2\">&nbsp</div>
                    {% for rs in redes_sociales %}
                        {% if rs.estatus == 'on' %}
                            <a class=\"px-2 px-md-3 align-middle d-none d-md-inline-flex redSocial\" href=\"{{rs.url}}\"><i class=\"{{rs.clase}} {{rs.estilo}} {{rs.red}}\"></i></a>
                        {% endif %}
                    {% endfor %}
                </nav>
            </div>
        </div>
    </section>
    <section class=\"container-fluid p-0 navegacion_media\">
        <div class=\"container p-0\">
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
                                            <a class=\"dropdown-item mb-1 mb-md-0\" class=\"pixelDestacados\" href=\"{{config.build.url}}destacados/novedades\">Los más nuevos</a>
                                            <a class=\"dropdown-item mb-1 mb-md-0\" class=\"pixelMasVendidos\" href=\"{{config.build.url}}destacados/mas-vendidos\">Los más vendidos</a>
                                        </div>
                                        <div class=\"col-12 col-sm-9 col-lg-7 py-3 pb-4\">
                                            <div class=\"row\">
                                                {% for c in categorias %}
                                                    <div class=\"col-sm-6 col-md-4 mb-3\">
                                                        <a class=\"pixelCategoria enlace_negro\" href=\"{{config.build.url}}libros/{{c.ruta}}\">
                                                            <i class=\"fas fa-angle-right mr-1 animated fadeInLeft infinite\"></i> {{c.categoria}}
                                                        </a>
                                                        <hr class=\"mt-2 mt-sm-0 mb-2\">
                                                        {% for s in subcategorias %}
                                                            {% if c.id == s.id_categoria %}
                                                                <a class=\"dropdown-item mb-1 mb-md-0 pixelSubcategoria\" href=\"{{config.build.url}}libros/{{s.ruta}}\">{{s.subcategoria}}</a>
                                                            {% endif %}
                                                        {% endfor %}
                                                    </div>
                                                {% endfor %}
                                            </div>
                                        </div>
                                        <div class=\"col-sm-6 col-lg-3 py-4 d-none d-lg-block\">
                                            {{ libroRand|raw }} 
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
                                    <input type=\"text\" name=\"consulta\" class=\"form-control form-control-sm pl-0 input_buscar\" placeholder=\"Título, editorial, autor o ISBN\" autocomplete=\"off\">
                                    <div class=\"input-group-btn\">
                                        <button class=\"btn btn-sm boton_blanco boton_buscar\" type=\"submit\">Buscar</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class=\"ml-auto mr-3 mr-sm-0\">
                            {% if is_logged %}
                                {% if controlador == 'cuenta' %}
                                    
                                {% else %}
                                    <div class=\"dropdown\">
                                        <button class=\"btn btn-icon-only rounded-circle boton_blanco\" type=\"button\" id=\"dropdown_user_account\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
                                            <span class=\"btn-inner--icon font-weight-800 user_letter\">
                                                {{ owner_user.cliente[0:1] }}
                                            </span> 
                                        </button>
                                        <div class=\"dropdown-menu\" aria-labelledby=\"dropdown_user_account\">
                                            <h6 class=\"dropdown-header text-truncate text-muted pb-3 border-bottom user_name\">{{ owner_user.cliente }}</h6>
                                            <a class=\"dropdown-item py-2 link_cuenta\" href=\"{{config.build.url}}cuenta/mis-datos\">
                                                <i class=\"fas fa-user-cog text-app\"></i>Mis datos
                                            </a>
                                            <a class=\"dropdown-item py-2 link_cuenta\" href=\"{{config.build.url}}cuenta/lista-deseos\">
                                                <i class=\"fas fa-heart text-app\"></i>Mi lista de deseos
                                            </a>
                                            <a class=\"dropdown-item py-2 link_cuenta\" href=\"{{config.build.url}}cuenta/mis-libros\">
                                                <i class=\"fas fa-book-open text-app\"></i>Mis libros
                                            </a>
                                            <a class=\"dropdown-item py-2 link_cuenta\" href=\"{{config.build.url}}cuenta/cambiar-contrasena\">
                                                <i class=\"fas fa-user-lock text-app\"></i>Cambiar contraseña
                                            </a>
                                            <a class=\"dropdown-item py-2 link_cuenta\" href=\"{{config.build.url}}cuenta/eliminar-cuenta\">
                                                <i class=\"fas fa-user-times text-app\"></i>Eliminar cuenta
                                            </a>
                                            <a class=\"dropdown-item py-2\" href=\"{{config.build.url}}salir\">
                                                <i class=\"fas fa-sign-out-alt text-app\"></i>Cerrar sesión
                                            </a>
                                        </div>
                                    </div>
                                {% endif %}
                            {% else %}
                                <a href=\"{{config.build.url}}autenticacion\" class=\"btn boton_blanco btn-icon-only rounded-circle\">
                                    <span class=\"btn-inner--icon\">
                                        <i class=\"fas fa-user\"></i>
                                    </span>
                                </a>
                            {% endif %}
                            <a href=\"{{config.build.url}}compra/\" class=\"btn btn-sm boton_blanco btn-icon ml-1 boton_compra\">
                                <span class=\"btn-inner--icon\"><i class=\"fas fa-shopping-bag\"></i></span>
                                <span class=\"btn-inner--text d-none d-md-inline\">Mi compra <label class=\"m-0 cantidad_carrito\">({{cantidad_carrito}})</label></span>
                                <span class=\"btn-inner--text d-inline d-md-none\"><label class=\"m-0 cantidad_carrito\">({{cantidad_carrito}})</label></span>
                            </a>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </section>
    <section class=\"container-fluid px-3 py-1 d-block d-sm-none formulario_busqueda_movil\">
        <nav class=\"navbar p-0 justify-content-center\">
            <form id=\"formulario_busqueda_movil\" action=\"busqueda/\" method=\"GET\" class=\"form-inline\">
                <div class=\"form-group m-0\">
                    <div class=\"input-group\">
                        <div class=\"input-group-prepend\">
                            <span class=\"input-group-text i_buscar\">
                                <i class=\"fas fa-search\"></i>
                            </span>
                        </div>
                        <input type=\"text\" name=\"consulta\" class=\"form-control form-control-sm pl-0 input_buscar\" placeholder=\"Título, editorial, autor o ISBN\" autocomplete=\"off\">
                        <div class=\"input-group-btn\">
                            <button class=\"btn btn-sm boton_blanco boton_buscar\" type=\"submit\">Buscar</button>
                        </div>
                    </div>
                </div>
            </form>
        </nav>
    </section>
</header>", "overall/header.twig", "C:\\xampp\\htdocs\\website\\app\\templates\\overall\\header.twig");
    }
}
