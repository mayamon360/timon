<?php

/* overall/footer.twig */
class __TwigTemplate_17275a64c5929e79a5523bef813e9302e0f01c52d7de10efab957180ea7cdc8e extends Twig_Template
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
        echo "

<footer class=\"pt-5 pb-3 footer footer-dark\">
      <div class=\"container\">
        <div class=\"row\">
          <div class=\"col-12 col-md-4\">
            <div class=\"pr-lg-5\">";
        // line 11
        echo twig_get_attribute($this->env, $this->getSourceContext(), ($context["logotipo"] ?? null), "logotipo_header", array());
        echo "
              <h1 class=\"heading h6 font-weight-700 mb-3\"><strong>¡Gracias por tu preferencia!</strong></h1>
              <ul class=\"fa-ul\">
\t            <li class=\"py-1\"><span class=\"fa-li\"><i class=\"mr-2 fas fa-map-marker-alt\"></i></span>Instituto Literario, esquina, Juan Aldama 300-A, 50130 <b>Toluca de Lerdo</b>, Méx.</li>
                <li class=\"py-1\"><span class=\"fa-li\"><i class=\"mr-2 far fa-clock\"></i></span>Lunes a Sábado 10:00 - 19:00 (El horario puede cambiar en días feriados)</li>
              </ul>
              <p>Encuentra con nosotros materiales de media superior como DGB, COBAEM, EPOEM, UAEM, CBT, CETIS, CEBETIS, CECYTEM, CONALEP o si lo que buscas es un buen libro para leer contamos con un gran surtido en literatura clásica, contemporánea, infantiles y de interés general además si el libro que buscas no lo tenemos, te ayudamos a conseguirlo.</p>
              <p>Para compras en línea aceptamos tarjeta de crédito y débito o si lo prefieres puedes pagar en efectivo con un vale en OXXO.</p>
            </div>
          </div>
          <div class=\"col-6 col-md\">
            <h5 class=\"heading h6 text-uppercase font-weight-700 mb-3\">bachillerato</h5>
            <ul class=\"list-unstyled text-small\">
              <li><a class=\"enlace_blanco\" href=\"";
        // line 24
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "libros/quimica/55\">Quimica</a></li>
              <li><a class=\"enlace_blanco\" href=\"";
        // line 25
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "libros/fisica/62\">Fisica</a></li>
              <li><a class=\"enlace_blanco\" href=\"";
        // line 26
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "libros/biologia/56\">Biologia</a></li>
              <li><a class=\"enlace_blanco\" href=\"";
        // line 27
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "libros/matematicas/57\">Matematicas</a></li>
              <li><a class=\"enlace_blanco\" href=\"";
        // line 28
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "libros/lenguaje-y-comunicacion/59\">Lenguaje y comunicacion</a></li>
              <li><a class=\"enlace_blanco\" href=\"";
        // line 29
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "libros/humanidades/60\">Humanidades</a></li>
              <li><a class=\"enlace_blanco\" href=\"";
        // line 30
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "libros/historia/61\">Historia</a></li>
              <li><a class=\"enlace_blanco\" href=\"";
        // line 31
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "libros/sociologia/63\">Sociologia</a></li>
              <li><a class=\"enlace_blanco\" href=\"";
        // line 32
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "libros/geografia/153\">Geografia</a></li>
              <li><a class=\"enlace_blanco\" href=\"";
        // line 33
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "libros/salud/58\">Salud</a></li>
              <li><a class=\"enlace_blanco\" href=\"";
        // line 34
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "libros/derecho/149\">Derecho</a></li>
              <li><a class=\"enlace_blanco\" href=\"";
        // line 35
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "libros/economia/152\">Economia</a></li>
              <li><a class=\"enlace_blanco\" href=\"";
        // line 36
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "libros/metodologia-de-la-investigacion/133\">Metodologia de la investigacion</a></li>
              <li><a class=\"enlace_blanco\" href=\"";
        // line 37
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "libros/informatica/64\">Informatica</a></li>
              <li><a class=\"enlace_blanco\" href=\"";
        // line 38
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "libros/talleres/154\">Talleres</a></li>
              <li><a class=\"enlace_blanco\" href=\"";
        // line 39
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "libros/educacion-media-superior-bachillerato/5\">y más . . .</a></li>
            </ul>
          </div>
          <div class=\"col-6 col-md\">
            <h5 class=\"heading h6 text-uppercase font-weight-700 mb-3\">Literatura</h5>
            <ul class=\"list-unstyled text-small\">
              <li><a class=\"enlace_blanco\" href=\"";
        // line 45
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "libros/ficcion-y-fantasia/10\">Ficcion y fantasia</a></li>
              <li><a class=\"enlace_blanco\" href=\"";
        // line 46
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "libros/poesia/26\">Poesia</a></li>
              <li><a class=\"enlace_blanco\" href=\"";
        // line 47
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "libros/terror-y-suspenso/51\">Terror y suspenso</a></li>
              <li><a class=\"enlace_blanco\" href=\"";
        // line 48
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "libros/policiaca-y-suspenso/15\">Policiaca y suspenso</a></li>
              <li><a class=\"enlace_blanco\" href=\"";
        // line 49
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "libros/juvenil/16\">Juvenil</a></li>
              <li><a class=\"enlace_blanco\" href=\"";
        // line 50
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "libros/autoayuda/13\">Autoayuda</a></li>
              <li><a class=\"enlace_blanco\" href=\"";
        // line 51
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "libros/historica/53\">Historica</a></li>
              <li><a class=\"enlace_blanco\" href=\"";
        // line 52
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "libros/narrativa-universal/76\">Universal</a></li>
              <li><a class=\"enlace_blanco\" href=\"";
        // line 53
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "libros/narrativa-iberoamericana/146\">Iberoamericana</a></li>
              <li><a class=\"enlace_blanco\" href=\"";
        // line 54
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "libros/politica/22\">Politica</a></li>
              <li><a class=\"enlace_blanco\" href=\"";
        // line 55
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "libros/erotica/71\">Erotica</a></li>
              <li><a class=\"enlace_blanco\" href=\"";
        // line 56
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "libros/cuento/19\">Cuento</a></li>
              <li><a class=\"enlace_blanco\" href=\"";
        // line 57
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "libros/novela/18\">Novela</a></li>
              <li><a class=\"enlace_blanco\" href=\"";
        // line 58
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "libros/tanatologia/111\">Tanatologia</a></li>
              <li><a class=\"enlace_blanco\" href=\"";
        // line 59
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "libros/espiritualidad/148\">Espiritualidad</a></li>
              <li><a class=\"enlace_blanco\" href=\"";
        // line 60
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "libros/mitos-y-leyendas/141\">Mitos y leyendas</a></li>
              <li><a class=\"enlace_blanco\" href=\"";
        // line 61
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "libros/literatura/8\">y más . . .</a></li>
            </ul>
          </div>
          <div class=\"col-6 col-md\">
            <h5 class=\"heading h6 text-uppercase font-weight-700 mb-3\">Sagas</h5>
            <ul class=\"list-unstyled text-small\">
              <li><a class=\"enlace_blanco\" href=\"";
        // line 67
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "libros/harry-potter/27\">Harry Potter</a></li>
              <li><a class=\"enlace_blanco\" href=\"";
        // line 68
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "libros/cazadores-de-sombras/28\">Cazadores de sombras</a></li>
              <li><a class=\"enlace_blanco\" href=\"";
        // line 69
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "libros/after/29\">After</a></li>
              <li><a class=\"enlace_blanco\" href=\"";
        // line 70
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "libros/crepusculo/38\">Crepusculo</a></li>
              <li><a class=\"enlace_blanco\" href=\"";
        // line 71
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "libros/cancion-de-hielo-y-fuego/30\">Cancion de cielo y fuego</a></li>
              <li><a class=\"enlace_blanco\" href=\"";
        // line 72
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "libros/cincuenta-sombras-de-grey/31\">Cincuenta sombras de Grey</a></li>
              <li><a class=\"enlace_blanco\" href=\"";
        // line 73
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "libros/las-cronicas-de-narnia/33\">Cronicas de Narnia</a></li>
              <li><a class=\"enlace_blanco\" href=\"";
        // line 74
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "libros/los-juegos-del-hambre/140\">Los juegos del hambre</a></li>
              <li><a class=\"enlace_blanco\" href=\"";
        // line 75
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "libros/los-heroes-del-olimpo/37\">Los heroes del Olimpo</a></li>
              <li><a class=\"enlace_blanco\" href=\"";
        // line 76
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "libros/el-fin-de-los-tiempos/49\">El fin de los tiempos</a></li>
              <li><a class=\"enlace_blanco\" href=\"";
        // line 77
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "libros/percy-jackson/35\">Percy Jackson</a></li>
              <li><a class=\"enlace_blanco\" href=\"";
        // line 78
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "libros/maze-runner/32\">Maze Runner</a></li>
              <li><a class=\"enlace_blanco\" href=\"";
        // line 79
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "libros/caballo-de-troya/36\">Caballo de Troya</a></li>
              <li><a class=\"enlace_blanco\" href=\"";
        // line 80
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "libros/las-pruebas-de-apolo/135\">La pruebas de Apolo</a></li>
              <li><a class=\"enlace_blanco\" href=\"";
        // line 81
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "libros/los-reyes-malditos/139\">Los reyes malditos</a></li>
              <li><a class=\"enlace_blanco\" href=\"";
        // line 82
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "libros/sagas/10\">y más . . .</a></li>
            </ul>
          </div>
          <div class=\"col-6 col-md\">
            <h5 class=\"heading h6 text-uppercase font-weight-700 mb-3\">Opciones</h5>
            <ul class=\"list-unstyled text-small\">
              <li><a class=\"enlace_blanco\" href=\"";
        // line 88
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "compra/\">Mi cesta <span class=\"btn-inner--icon\"><i class=\"fas fa-shopping-bag\"></i></span> <span class=\"btn-inner--text\"><label class=\"m-0 cantidad_carrito\">(";
        echo twig_escape_filter($this->env, ($context["cantidad_carrito"] ?? null), "html", null, true);
        echo ")</label></span></a></li>";
        // line 89
        if (($context["is_logged"] ?? null)) {
            // line 90
            echo "                <li><a class=\"enlace_blanco\" href=\"";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
            echo "cuenta/mis-datos\"><span class=\"btn-inner--icon\"><i class=\"fas fa-user-cog\"></i></span> Mis datos</a></li>
                <li><a class=\"enlace_blanco\" href=\"";
            // line 91
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
            echo "cuenta/lista-deseos\"><span class=\"btn-inner--icon\"><i class=\"fas fa-heart\"></i></span> Mi lista de deseos</a></li>
                <li><a class=\"enlace_blanco\" href=\"";
            // line 92
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
            echo "cuenta/mis-compras\"><span class=\"btn-inner--icon\"><i class=\"fas fa-book-open\"></i></span> Mis compras</a></li>
                <li><a class=\"enlace_blanco\" href=\"";
            // line 93
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
            echo "cuenta/cambiar-contrasena\"><span class=\"btn-inner--icon\"><i class=\"fas fa-user-lock\"></i></span> Cambiar contraseña</a></li>
                <li></li>
                <li><a class=\"enlace_blanco\" href=\"";
            // line 95
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
            echo "salir\"><span class=\"btn-inner--icon\"><i class=\"fas fa-sign-out-alt\"></i> Cerrar sesión</a></li>";
        } else {
            // line 97
            echo "                <li><a class=\"enlace_blanco\" href=\"";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
            echo "autenticacion/\"><span class=\"btn-inner--icon\"><i class=\"fas fa-sign-in-alt\"></i></span> Ingresa a tu cuenta</a></li>
                <li><a class=\"enlace_blanco\" href=\"";
            // line 98
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
            echo "autenticacion/recuperar\"><span class=\"btn-inner--icon\"><i class=\"fas fa-key\"></i></span> Solicitar contraseña</a></li>
                <li><a class=\"enlace_blanco\" href=\"";
            // line 99
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
            echo "autenticacion/registro\"><span class=\"btn-inner--icon\"><i class=\"fas fa-user-plus\"></i></span> Regístrate</a></li>";
        }
        // line 101
        echo "            </ul>
          </div>
        </div>
        <hr>
        <div class=\"row\">
          <div class=\"col-12 col-md-4 text-center text-md-left\">
            &copy;";
        // line 107
        echo twig_escape_filter($this->env, twig_date_format_filter($this->env, "now", "Y"), "html", null, true);
        echo " <strong>";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "name", array()), "html", null, true);
        echo "</strong>
          </div>
          <div class=\"col-12 col-md-8 text-center text-md-right\">
              <a class=\"enlace_blanco px-2 py-3 d-block d-sm-inline\" href=\"";
        // line 110
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "informacion/contacto\"><i class=\"fas fa-at\"></i> Contáctanos</a>
              <a class=\"enlace_blanco px-2 py-3 d-sm-inline\" href=\"https://www.facebook.com/El-timón-librería-247638102062702/\" target=\"_blank\"><i class=\"fab fa-facebook\"></i> Facebook</a>
              <a class=\"enlace_blanco px-2 py-3 d-block d-sm-inline\" href=\"";
        // line 112
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "informacion/ayuda\"><i class=\"fas fa-question-circle\"></i> Ayuda</a>
          </div>
        </div>
      </div>
    </footer>";
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
        return array (  317 => 112,  312 => 110,  304 => 107,  296 => 101,  292 => 99,  288 => 98,  283 => 97,  279 => 95,  274 => 93,  270 => 92,  266 => 91,  261 => 90,  259 => 89,  254 => 88,  245 => 82,  241 => 81,  237 => 80,  233 => 79,  229 => 78,  225 => 77,  221 => 76,  217 => 75,  213 => 74,  209 => 73,  205 => 72,  201 => 71,  197 => 70,  193 => 69,  189 => 68,  185 => 67,  176 => 61,  172 => 60,  168 => 59,  164 => 58,  160 => 57,  156 => 56,  152 => 55,  148 => 54,  144 => 53,  140 => 52,  136 => 51,  132 => 50,  128 => 49,  124 => 48,  120 => 47,  116 => 46,  112 => 45,  103 => 39,  99 => 38,  95 => 37,  91 => 36,  87 => 35,  83 => 34,  79 => 33,  75 => 32,  71 => 31,  67 => 30,  63 => 29,  59 => 28,  55 => 27,  51 => 26,  47 => 25,  43 => 24,  27 => 11,  19 => 4,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "overall/footer.twig", "/home4/eltimonl/public_html/app/templates/overall/footer.twig");
    }
}
