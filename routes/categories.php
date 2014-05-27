<?php
    $app->get('/get_categories', function () use ($app) {
        setup($app);

        $ret["error"] = "000";

        if(!isRequestValid($app)) {
          $ret["error"] = "001";
        } else {
          $ret["categories"] = Category::all()->toArray();
        }
        
        echo json_encode($ret);
    });

?>