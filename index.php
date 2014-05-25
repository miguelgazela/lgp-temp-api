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
        $app->response()->header('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type');
        $app->response()->header('Access-Control-Allow-Credentials', 'true');
    }

    // ROUTES

    $app->get('/', function () use ($app) {
        setup($app);
        $app->render('index.html');
    });

    $app->get('/categories', function () use ($app) {
        setup($app);
        $categories = Category::all();

        $res = $app->response();
        $res->body($categories);
    });

    $app->post('/products', function() use ($app) {
        setup($app);
        $data = $app->request()->getBody();
        echo json_encode(array("result" => "worked!", "data" => $data));
    });

    $app->get('/products/:id', function ($id) use ($app) {
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

        $result = array();

        for($i = 0; $i < 30; $i++) {
            $result['products'][$i] = array(
                "id" => "$i",
                "name" => "Product #$i",
                "description" => "This will contain the full description of the product. It will be displayed in the ProductDetail Activity",
                "smallDescription" => "Small description displayed in products lists"
            );
        }

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

    //users access level -> 0 - base, 1 - mid, 2 - max
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

    // type -> 0 - visitor, 1 - user, 2 - admin, 3 - superuser
    $app->post('/account/login', function () use ($app) {
        setup($app);
        $data = json_decode($app->request()->getBody(), true);

        $result = array();

        if($data["username"] == "user1") {
            if($data["password"] == "a722c63db8ec8625af6cf71cb8c2d939") { //pass1
                $result["result"] = "success";
                $result["user"] = array("id"=>1, "name"=>"John", "username"=>"user1", "type"=>"superuser");
            } else {
                $result["result"] = "error";
                $result["data"] = "wrong password";
            }
        } else if($data["username"] == "user2") {
            if($data["password"] == "c1572d05424d0ecb2a65ec6a82aeacbf") { //pass2
                $result["result"] = "success";
                $result["user"] = array("id"=>2, "name"=>"Michael", "username"=>"user2", "type"=>"admin");
            } else {
                $result["result"] = "error";
                $result["data"] = "wrong password";
            }
        } else {
            $result["result"] = "error";
            $result["data"] = "User ".$data["username"]." does not exist";
        }
        
        echo json_encode($result);
    });

    $app->post('/account/logout', function () use ($app) {
        setup($app);
        $data = json_decode($app->request()->getBody(), true);
        $result["result"] = "success";
        echo json_encode($result);
    });

    $app->run();
?>
