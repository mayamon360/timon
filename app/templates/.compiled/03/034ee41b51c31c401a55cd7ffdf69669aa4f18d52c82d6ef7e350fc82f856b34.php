<?php

/* destacados/destacados.twig */
class __TwigTemplate_bd068dbbdf5bdd80f8729c550fde8790bf3409e83ed72987fbae89c9eda13fd8 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("overall/layout", "destacados/destacados.twig", 1);
        $this->blocks = array(
            'appBody' => array($this, 'block_appBody'),
            'appFooter' => array($this, 'block_appFooter'),
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

    // line 3
    public function block_appBody($context, array $blocks = array())
    {
        // line 4
        echo "
    <main class=\"container mt-2 destacados\">
        <div class=\"row px-0 align-items-center\">
            <div class=\"col-12 col-md-6 col-lg-8\">
                <p class=\"m-0 p-0 text-muted font-weight-bold animated fadeIn\"><span class=\"coincidencias\"></span></p>
                <h1 class=\"m-0 p-0 animated fadeIn titulo_destacado\"><i class=\"fas fa-certificate\"></i> Los más nuevos</h1>
\t\t\t\t<div class=\"my-1 animated fadeIn separador separador_color\"></div>
            </div>
            <div class=\"col-12 col-md-6 col-lg-4 text-right\">
                <div class=\"input-group input-group-sm mb-3 animated fadeIn\">
                    <div class=\"input-group-prepend\">
                        <label class=\"input-group-text text-uppercase\" for=\"ordenar_resultados\"><small>Ordenar por</small></label>
                    </div>
                    <select class=\"custom-select custom-select-sm\" id=\"ordenar_resultados\">
                        <option value=\"relevancia\">Relevancia</option>
                        <optgroup label=\"Nombre\">
                            <option value = \"nombre\">Nombre de la A-Z</option>
                            <option value = \"nombre_desc\">Nombre de la Z-A</option>
                        </optgroup>
                        <optgroup label=\"Precio\">
                            <option value = \"precio\">Precio de menor a mayor</option>
                            <option value = \"precio_desc\">Precio de mayor a menor</option>
                        </optgroup>

                    </select>
                </div>
            </div>

            <div class=\"col-12 mt-2\">
                <div class=\"row\" id=\"resultados\">
                    
                </div>
            </div>

            <div class=\"col-12 mt-5\">
                <div class=\"text-center div_cargando\">
                    <button type=\"button\" class=\"btn btn-sm boton_negro\" id=\"boton_cargar_mas\">
                        <span class=\"spinner-grow spinner-grow-sm d-none\" role=\"status\" aria-hidden=\"true\"></span>
                        Mostrar más resultados
                    </button>
                    <p class=\"text-muted d-none animated flash div_cargando_texto\"></p>
                </div>
            </div>

        </div>
    </main>
    
";
    }

    // line 53
    public function block_appFooter($context, array $blocks = array())
    {
        // line 54
        echo "\t<script>
        
        var pagina = 1;
        cargarDestacados(pagina);

        \$(\"#ordenar_resultados\").on('change', function(){
            \$(\"#resultados\").html('');
            \$(\"#boton_cargar_mas\").removeClass('d-none');
            \$(\".div_cargando_texto\").addClass('d-none');
            //\$(\"html, body\").animate({scrollTop: \$(\"html\").offset().top}, 1000);
            pagina = 1;
            cargarDestacados(pagina);
        })
        
        \$(\"#boton_cargar_mas\").click(function() {
            pagina++;
            cargarDestacados(pagina);
        })

        function cargarDestacados(pagina){

            var orden = \$(\"#ordenar_resultados\").val();
            \$.ajax({
                url:\"api/cargarDestacados\",
                method:'GET',
                dataType: 'json',
                data : {orden:orden, pagina:pagina},
                beforeSend: function(){
                    \$(\".spinner-grow\").removeClass('d-none');
                },
                success:function(json){
                    \$(\".coincidencias\").html(json.coincidencias);

                    if(json.resultados_pagina == 0 || json.ultima_pagina == true){
                        \$(\"#boton_cargar_mas\").addClass('d-none');
                    }
                    if(json.total_resultados == 0){
                        \$(\".div_cargando_texto\").removeClass('d-none');
                        \$(\".div_cargando_texto\").html('Sin resultados para mostrar en está sección.');
                    }

                    \$(\"#resultados\").append(json.html);
                },
                error : function(xhr, status) {
                    alert('Ha ocurrido un problema interno');
                },
                complete: function(){ 
                    \$(\".spinner-grow\").addClass('d-none');
                    \$(\"html, body\").animate({scrollTop: \$(\".div_cargando\").offset().top}, 1000);
                }
            })

        }
        

    </script>
";
    }

    public function getTemplateName()
    {
        return "destacados/destacados.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  86 => 54,  83 => 53,  32 => 4,  29 => 3,  11 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "destacados/destacados.twig", "C:\\xampp\\htdocs\\website\\app\\templates\\destacados\\destacados.twig");
    }
}
