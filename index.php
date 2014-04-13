<?php
    ini_set('display_errors', 'On');

    require 'lib/Slim/Slim.php';
    \Slim\Slim::registerAutoloader();

    require 'lib/Twig/Autoloader.php';
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

    // ROUTES

    $app->get('/', function () use ($app) {
        $app->render('index.html');
    });

    $app->get('/products/:productId', function ($productId) use ($app) {
        $app->contentType('application/json');
        $result = array(
            "product" => array(
                "id" => $productId,
                "name" => "Product #".$productId,
                "description" => "This will contain the full description of the product. It will be displayed in the ProductDetail Activity",
                "smallDescription" => "Small description displayed in products lists"
            )
        );
        echo json_encode($result);
    });

    $app->get('/products/', function () use ($app) {
        $app->contentType('application/json');
        $result = array(
            "products" => array(
                array(
                    "id" => "1",
                    "name" => "Product #1",
                    "description" => "This will contain the full description of the product. It will be displayed in the ProductDetail Activity",
                    "smallDescription" => "Small description displayed in products lists"
                ),
                array(
                    "id" => "2",
                    "name" => "Product #2",
                    "description" => "This will contain the full description of the product. It will be displayed in the ProductDetail Activity",
                    "smallDescription" => "Small description displayed in products lists"
                ),
                array(
                    "id" => "3",
                    "name" => "Product #3",
                    "description" => "This will contain the full description of the product. It will be displayed in the ProductDetail Activity",
                    "smallDescription" => "Small description displayed in products lists"
                ),
            )
        );
    });

    $app->run();
?>