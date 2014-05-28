<?php
    use Illuminate\Database\Eloquent\ModelNotFoundException;

    // ROUTES -->

    $app->get('/categories', 'getCategories');
    $app->get('/get_categories', 'getCategoriesForAndroid');


    // FUNCTIONS -->

    function getCategories() {
        setHeaders();
        $categories = Category::all()->toArray();
        returnJson($categories);
    }

    function getCategoriesForAndroid() {
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