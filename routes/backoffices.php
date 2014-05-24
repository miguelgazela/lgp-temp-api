<?php

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

?>