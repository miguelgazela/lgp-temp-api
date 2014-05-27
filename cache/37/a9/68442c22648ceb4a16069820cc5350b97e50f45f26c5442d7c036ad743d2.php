<?php

/* index.html */
class __TwigTemplate_37a968442c22648ceb4a16069820cc5350b97e50f45f26c5442d7c036ad743d2 extends Twig_Template
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
        echo "<!DOCTYPE html>
<html>
    <head>
        
    </head>
    <body>
        <h1>Welcome to the temporary API for the LGP project</h1>

        <form action=\"localhost:8888/lgp-temp-api/account/login\" method=\"POST\">
        \t<div>
        \t\t<input type=\"text\" name=\"username\" placeholder=\"username\" required>
        \t</div>
        \t<div>
        \t\t<input type=\"password\" name=\"password\" placeholder=\"password\" required>
        \t</div>
        \t<button type=\"submit\">Submit</button>
        </form>
    </body>
</html>";
    }

    public function getTemplateName()
    {
        return "index.html";
    }

    public function getDebugInfo()
    {
        return array (  19 => 1,);
    }
}
