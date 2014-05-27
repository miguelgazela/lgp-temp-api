<?php
    use Illuminate\Database\Eloquent\ModelNotFoundException;

    // ROUTES -->

    $app->get('/get_categories', 'getCategories');


    // FUNCTIONS -->

    function getCategories() {
        setHeaders();
        $app = \Slim\Slim::getInstance();

        $ret["error"] = "000";

        if(!isRequestValid($app)) {
          $ret["error"] = "001";
        } else {
          $ret["categories"] = Category::all()->toArray();
        }
        
        returnJson($ret);
    }
?>