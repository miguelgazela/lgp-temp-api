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

    function setup($app) {
        $app->contentType('application/json');
        $app->response()->header('Access-Control-Allow-Origin', '*');
        $app->response()->header('Access-Control-Allow-Methods','POST, GET, PUT, DELETE, OPTIONS');
    }

    $app->get('/products/:productId', function ($productId) use ($app) {
        setup($app);
        $result = array(
            "product" => array(
                "id" => $id,
                "name" => "Product #".$id,
                "description" => "This will contain the full description of the product. It will be displayed in the ProductDetail Activity",
                "smallDescription" => "Small description displayed in products lists"
            )
        );
        echo json_encode($result);
    });

    $app->get('/products/', function () use ($app) {
        setup($app);
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
	      echo json_encode($result);
    });

    $app->post('/validation/', function () use($app) {
        setup($app);
        $data = json_decode($app->request()->getBody(), true);

        $result["result"] = $data["id"] === $data["encryptedID"];


        if ($data["id"] == $data["encryptedID"]) {
          $result["result"] = "valid";
          $result["product"] =  array(
                "id" => $data["productID"],
                "name" => "Product #".$data["productID"],
                "description" => "This will contain the full description of the product. It will be displayed in the ProductDetail Activity",
                "smallDescription" => "Small description displayed in products lists"
            );
        }
        echo json_encode($result);
    });


    $app->get('/backoffices/', function () use ($app) {
        setup($app);
        $result["backoffices"] = array(
            array(
                "id" => 1,
                "name" => "Gucci",
                "users" => array(array("id" => 1, "name" => "John", "access" => 0), array("id" => 2, "name" => "Michael", "access" => 1))
                ),
            array(
                "id" => 2,
                "name" => "Versace",
                "users" => array(array("id" => 1, "name" => "John", "access" => 0))
                )
            );
        echo json_encode($result);
    });
    
    $app->get('/backoffices/:id', function ($id) use ($app) {
        setup($app);
        $result["backoffice"] = array(
            "id" => 1,
            "name" => "Gucci",
            "users" => array(array("id" => 1, "name" => "John", "access" => 0), array("id" => 2, "name" => "Michael", "access" => 1))
        );

        if($id == 2) {
            $result["backoffice"] = array(
                "id" => 2,
                "name" => "Versace",
                "users" => array(array("id" => 1, "name" => "John", "access" => 0))
            );
        }
        echo json_encode($result);
    });

    $app->run();
?>
