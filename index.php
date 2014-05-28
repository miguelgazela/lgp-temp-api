<?php
    ini_set('display_errors', 'On');
    require 'vendor/autoload.php';
    require 'lib/Slim/Slim.php';
    require 'lib/Twig/Autoloader.php';
    require 'database.php';

    session_cache_limiter(false);
    session_start();

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

    // fixes the cross domain problem
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']) && (   
           $_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'] == 'POST' || 
           $_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'] == 'DELETE' || 
           $_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'] == 'PUT' )) {
                 header('Access-Control-Allow-Origin: *');
                 header("Access-Control-Allow-Credentials: true"); 
                 header('Access-Control-Allow-Headers: X-Requested-With');
                 header('Access-Control-Allow-Headers: Content-Type');
                 header('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT'); // http://stackoverflow.com/a/7605119/578667
                 header('Access-Control-Max-Age: 86400'); 
          }
      exit;
    }

    function setHeaders() {
        $app = \Slim\Slim::getInstance();
        $app->response()->header('Access-Control-Allow-Origin', '*');
        $app->response()->header('Access-Control-Allow-Methods','POST, GET, PUT, DELETE, OPTIONS, HEAD');
        $app->response()->header('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept');
        $app->response()->header('Access-Control-Allow-Credentials', 'true');
    }

    function returnJson($data) {
        global $app;
        $res = $app->response();
        $res['Content-Type'] = "application/json";
        $res->body(json_encode($data));
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
        setHeaders($app);
        $app->render('index.html');
    });

    $app->post('/images/upload.php', function() use ($app) {
        setHeaders($app);
        $result = array();
        $result['errors'] = array();
        $maxSize = 2097152;
        $acceptable = array(
            'image/jpeg',
            'image/jpg',
            'image/png'
        );

        if (!empty($_FILES)) {
            $myFile = $_FILES['file'];

            if ($myFile['error'] !== UPLOAD_ERR_OK) {
                $result['result'] = "error";
            } else {

                if ($myFile['size'] >= $maxSize || $myFile['size'] == 0) {
                    $result['errors'][] = 'File too large. File must be less than 2 megabytes.';
                }

                if (!in_array($myFile['type'], $acceptable) && !empty($myFile['type'])) {
                    $result['errors'][] = "Invalid file type. Only JPG and PNG types are accepted.";
                }

                if (count($result['errors']) == 0) {
                    // ensure a safe filename
                    $name = preg_replace("/[^A-Z0-9._-]/i", "_", $myFile["name"]);
                    
                    $uploadPath = dirname(__FILE__).'/uploads/'.str_random(16).$name;

                    // don't overwrite an existing file
                    while (file_exists($uploadPath)) {
                        $uploadPath = dirname(__FILE__).'/uploads/'.str_random(16).$name;
                    }

                    move_uploaded_file($_FILES['file']['tmp_name'], $uploadPath);
                    $pos = strrpos($uploadPath, "/");
                    $name = substr($uploadPath, $pos);

                    $result['result'] = "success";
                    $result['img_path'] = $name;
                } else {
                    $result['result'] = "error";
                } 
            }
        } else {
            $result['result'] = "error";
            $result['error_msg'] = "no files";
        }
        returnJson($result);
    });

    $app->run();
?>
