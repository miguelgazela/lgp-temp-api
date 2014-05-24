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
        // $app->contentType('application/json');
        $app->response()->header('Access-Control-Allow-Origin', '*');
        $app->response()->header('Access-Control-Allow-Methods','POST, GET, PUT, DELETE, OPTIONS, HEAD');
        $app->response()->header('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type');
        $app->response()->header('Access-Control-Allow-Credentials', 'true');
    }

    function returnJson($data) {
        global $app;
        $res = $app->response();
        $res['Content-Type'] = "application/json";
        $res->body($data);
    }

    // ROUTES

    $app->get('/', function () use ($app) {
        // setup($app);
        $app->render('index.html');
    });

    require 'routes/auth.php';
    require 'routes/products.php';
    require 'routes/backoffices.php';

    $app->get('/categories', function () use ($app) {
        $categories = Category::all();
        returnJson($categories);
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


    $app->get('/selling_locations/', function () use ($app) {
        setup($app);
        $result["locations"] = array(
            array(
                "id" => 1,
                "name" => "Porto",
                "coordinates"=> array("latitude"=>"0.0", "longitude"=>"0.0")
            ), array(
                "id" => 2,
                "name" => "Lisboa",
                "coordinates" => array("latitude"=>"38.76", "longitude"=>"-9.09")
            ), array(
                "id" => 3,
                "name" => "Lisboa",
                "coordinates" => array("latitude"=>"38.77", "longitude"=>"-9.09")
            ), array(
                "id" => 4,
                "name" => "Lisboa",
                "coordinates" => array("latitude"=>"38.75", "longitude"=>"-9.09")
            ));
        echo json_encode($result);
    });
    
    $app->get('/selling_locations/:id', function ($id) use ($app) {
        setup($app);
        $result["location"] = array(
            "id" => 1,
            "name" => "Porto",
            "coordinates"=> array("latitude"=>"0.0", "longitude"=>"0.0")
        );

        if($id == 2) {
            $result["location"] = array(
                "id" => 2,
                "name" => "Lisboa",
                "coordinates"=> array("latitude"=>"38.76", "longitude"=>"-9.09")
            );
        }
        echo json_encode($result);
    });

    $app->run();
?>
