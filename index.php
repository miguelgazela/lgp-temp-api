<?php
    ini_set('display_errors', 'On');
    require 'vendor/autoload.php';
    require 'lib/Slim/Slim.php';
    require 'lib/Twig/Autoloader.php';
    require 'database.php';

    \Slim\Slim::registerAutoloader();
    Twig_Autoloader::register();

    $app = new \Slim\Slim(array(
        'debug' => true,
        'view' => new \Slim\Views\Twig(),
        'templates.path' => 'templates'
    ));

    $view = $app->view();
    
    $view->parserOptions = array(
        'debug' => true,
        'cache' => dirname(__FILE__) . '/cache'
    );
    
    function setup($app) {
        $app->contentType('application/json');
        $app->response()->header('Access-Control-Allow-Origin', '*');
        $app->response()->header('Access-Control-Allow-Methods','POST, GET, PUT, DELETE, OPTIONS, HEAD');
        $app->response()->header('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept');
        $app->response()->header('Access-Control-Allow-Credentials', 'true');
    }

    function returnJson($data) {
        global $app;
        $res = $app->response();
        $res['Content-Type'] = "application/json";
        $res->body($data);
    }

    function isRequestValid($app) {
        return strtolower($app->request()->params('app')) == "bulla";
    }

    function param($app, $name) {
        return $app->request()->params($name);
    }

    // ROUTES

    require 'routes/auth.php';
    require 'routes/products.php';
    require 'routes/backoffices.php';
    require 'routes/categories.php';
    require 'routes/validation.php';
    require 'routes/selling_locations.php';

    $app->get('/', function () use ($app) {
        // setup($app);
        $app->render('index.html');
    });


    $app->run();
?>
